<?php
// Activer l'affichage des erreurs pour le débogage (à désactiver en production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Gérer les requêtes OPTIONS (CORS preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Fonction pour envoyer une réponse JSON
function sendResponse($data, $status_code = 200) {
    http_response_code($status_code);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

// Fonction pour logger les erreurs
function logError($message, $context = []) {
    $log = date('Y-m-d H:i:s') . ' - ' . $message;
    if (!empty($context)) {
        $log .= ' - Context: ' . json_encode($context);
    }
    error_log($log);
}

try {
    // Inclure les contrôleurs nécessaires avec vérification d'existence
    $controllers = [
        'controleur_panier.php',
        'controleur_favori.php', 
        'controleur_commentaire.php',
        'controleur_partage.php',
        'controleur_utilisateur.php'
    ];
    
    foreach ($controllers as $controller) {
        $path = __DIR__ . '/' . $controller;
        if (!file_exists($path)) {
            throw new Exception("Contrôleur manquant: $controller");
        }
        require_once $path;
    }
    
} catch (Exception $e) {
    logError('Erreur de chargement des contrôleurs', ['error' => $e->getMessage()]);
    sendResponse([
        'success' => false,
        'message' => 'Erreur de configuration du serveur',
        'error_code' => 'CONTROLLER_LOAD_ERROR'
    ], 500);
}

// Fonction pour valider les données POST avec types
function validatePostData($required_fields, $optional_fields = []) {
    $data = [];
    $errors = [];
    
    // Vérifier les champs obligatoires
    foreach ($required_fields as $field => $type) {
        if (is_numeric($field)) {
            $field = $type;
            $type = 'string';
        }
        
        if (!isset($_POST[$field]) || $_POST[$field] === '') {
            $errors[] = "Le champ '$field' est requis";
            continue;
        }
        
        $value = $_POST[$field];
        
        // Validation par type
        switch ($type) {
            case 'int':
                if (!is_numeric($value)) {
                    $errors[] = "Le champ '$field' doit être un nombre entier";
                } else {
                    $data[$field] = intval($value);
                }
                break;
            case 'float':
                if (!is_numeric($value)) {
                    $errors[] = "Le champ '$field' doit être un nombre décimal";
                } else {
                    $data[$field] = floatval($value);
                }
                break;
            case 'email':
                if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $errors[] = "Le champ '$field' doit être un email valide";
                } else {
                    $data[$field] = $value;
                }
                break;
            default:
                $data[$field] = trim($value);
                break;
        }
    }
    
    // Traiter les champs optionnels
    foreach ($optional_fields as $field => $type) {
        if (is_numeric($field)) {
            $field = $type;
            $type = 'string';
        }
        
        if (isset($_POST[$field]) && $_POST[$field] !== '') {
            $value = $_POST[$field];
            
            switch ($type) {
                case 'int':
                    $data[$field] = is_numeric($value) ? intval($value) : 0;
                    break;
                case 'float':
                    $data[$field] = is_numeric($value) ? floatval($value) : 0.0;
                    break;
                default:
                    $data[$field] = trim($value);
                    break;
            }
        }
    }
    
    return ['data' => $data, 'errors' => $errors];
}

// Fonction pour obtenir l'ID utilisateur de la session
function getUserId() {
    if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
        // En développement, utiliser un ID par défaut
        if (defined('DEBUG_MODE') && DEBUG_MODE) {
            return 1;
        }
        throw new Exception('Utilisateur non connecté', 401);
    }
    return intval($_SESSION['user_id']);
}

// Fonction pour vérifier si l'utilisateur est connecté
function requireAuth() {
    try {
        return getUserId();
    } catch (Exception $e) {
        sendResponse([
            'success' => false,
            'message' => 'Authentification requise',
            'error_code' => 'AUTH_REQUIRED'
        ], 401);
    }
}

// Vérifier la méthode de requête
if (!in_array($_SERVER['REQUEST_METHOD'], ['POST', 'GET'])) {
    sendResponse(['success' => false, 'message' => 'Méthode non autorisée'], 405);
}

// Récupérer l'action
$action = $_POST['action'] ?? $_GET['action'] ?? '';

if (empty($action)) {
    sendResponse([
        'success' => false, 
        'message' => 'Action manquante',
        'error_code' => 'MISSING_ACTION'
    ], 400);
}

try {
    switch ($action) {
        // ===== GESTION DU PANIER =====
        case 'add_to_cart':
            $user_id = requireAuth();
            $validation = validatePostData(['id_produit' => 'int'], ['quantite' => 'int']);
            
            if (!empty($validation['errors'])) {
                sendResponse([
                    'success' => false, 
                    'message' => 'Données invalides', 
                    'errors' => $validation['errors']
                ], 400);
            }
            
            if (!class_exists('PanierController')) {
                throw new Exception('Contrôleur PanierController non trouvé');
            }
            
            $panierController = new PanierController();
            $quantite = $validation['data']['quantite'] ?? 1;
            
            if ($quantite <= 0) {
                sendResponse([
                    'success' => false,
                    'message' => 'La quantité doit être supérieure à 0'
                ], 400);
            }
            
            $result = $panierController->addToCart($user_id, $validation['data']['id_produit'], $quantite);
            sendResponse($result);
            break;
            
        case 'get_cart':
            $user_id = requireAuth();
            
            if (!class_exists('PanierController')) {
                throw new Exception('Contrôleur PanierController non trouvé');
            }
            
            $panierController = new PanierController();
            $result = $panierController->getCart($user_id);
            sendResponse($result);
            break;
            
        case 'update_cart_quantity':
            $user_id = requireAuth();
            $validation = validatePostData(['id_panier' => 'int', 'quantite' => 'int']);
            
            if (!empty($validation['errors'])) {
                sendResponse([
                    'success' => false, 
                    'message' => 'Données invalides', 
                    'errors' => $validation['errors']
                ], 400);
            }
            
            if ($validation['data']['quantite'] <= 0) {
                sendResponse([
                    'success' => false,
                    'message' => 'La quantité doit être supérieure à 0'
                ], 400);
            }
            
            $panierController = new PanierController();
            $result = $panierController->updateQuantity($validation['data']['id_panier'], $validation['data']['quantite']);
            sendResponse($result);
            break;
            
        case 'remove_from_cart':
            $user_id = requireAuth();
            $validation = validatePostData(['id_panier' => 'int']);
            
            if (!empty($validation['errors'])) {
                sendResponse([
                    'success' => false, 
                    'message' => 'Données invalides', 
                    'errors' => $validation['errors']
                ], 400);
            }
            
            $panierController = new PanierController();
            $result = $panierController->removeFromCart($validation['data']['id_panier']);
            sendResponse($result);
            break;
            
        case 'clear_cart':
            $user_id = requireAuth();
            $panierController = new PanierController();
            $result = $panierController->clearCart($user_id);
            sendResponse($result);
            break;
            
        case 'get_cart_count':
            $user_id = requireAuth();
            $panierController = new PanierController();
            $result = $panierController->getCartItemsCount($user_id);
            sendResponse($result);
            break;
            
        // ===== GESTION DES FAVORIS =====
        case 'add_to_favorites':
            $user_id = requireAuth();
            $validation = validatePostData(['type' => 'string', 'id_element' => 'int']);
            
            if (!empty($validation['errors'])) {
                sendResponse([
                    'success' => false, 
                    'message' => 'Données invalides', 
                    'errors' => $validation['errors']
                ], 400);
            }
            
            // Valider le type
            $types_valides = ['formations', 'produits', 'services'];
            if (!in_array($validation['data']['type'], $types_valides)) {
                sendResponse([
                    'success' => false,
                    'message' => 'Type non valide. Types acceptés: ' . implode(', ', $types_valides)
                ], 400);
            }
            
            $favoriController = new FavoriController();
            $result = $favoriController->addToFavorites(
                $user_id, 
                $validation['data']['type'], 
                $validation['data']['id_element']
            );
            sendResponse($result);
            break;
            
        case 'remove_from_favorites':
            $user_id = requireAuth();
            $validation = validatePostData(['type' => 'string', 'id_element' => 'int']);
            
            if (!empty($validation['errors'])) {
                sendResponse([
                    'success' => false, 
                    'message' => 'Données invalides', 
                    'errors' => $validation['errors']
                ], 400);
            }
            
            $favoriController = new FavoriController();
            $result = $favoriController->removeFromFavorites(
                $user_id, 
                $validation['data']['type'], 
                $validation['data']['id_element']
            );
            sendResponse($result);
            break;
            
        case 'toggle_favorite':
            $user_id = requireAuth();
            $validation = validatePostData(['type' => 'string', 'id_element' => 'int']);
            
            if (!empty($validation['errors'])) {
                sendResponse([
                    'success' => false, 
                    'message' => 'Données invalides', 
                    'errors' => $validation['errors']
                ], 400);
            }
            
            $favoriController = new FavoriController();
            $result = $favoriController->toggleFavorite(
                $user_id, 
                $validation['data']['type'], 
                $validation['data']['id_element']
            );
            sendResponse($result);
            break;
            
        case 'get_favorites':
            $user_id = requireAuth();
            $favoriController = new FavoriController();
            $type = $_POST['type'] ?? $_GET['type'] ?? null;
            $result = $favoriController->getUserFavorites($user_id, $type);
            sendResponse($result);
            break;
            
        case 'is_favorite':
            $user_id = requireAuth();
            $validation = validatePostData(['type' => 'string', 'id_element' => 'int']);
            
            if (!empty($validation['errors'])) {
                sendResponse([
                    'success' => false, 
                    'message' => 'Données invalides', 
                    'errors' => $validation['errors']
                ], 400);
            }
            
            $favoriController = new FavoriController();
            $result = $favoriController->isFavorite(
                $user_id, 
                $validation['data']['type'], 
                $validation['data']['id_element']
            );
            sendResponse($result);
            break;
            
        // ===== GESTION DES COMMENTAIRES =====
        case 'add_comment':
            $user_id = requireAuth();
            $validation = validatePostData([
                'id_service' => 'int', 
                'commentaire' => 'string', 
                'note' => 'int'
            ]);
            
            if (!empty($validation['errors'])) {
                sendResponse([
                    'success' => false, 
                    'message' => 'Données invalides', 
                    'errors' => $validation['errors']
                ], 400);
            }
            
            // Valider la note (généralement entre 1 et 5)
            if ($validation['data']['note'] < 1 || $validation['data']['note'] > 5) {
                sendResponse([
                    'success' => false,
                    'message' => 'La note doit être comprise entre 1 et 5'
                ], 400);
            }
            
            // Valider la longueur du commentaire
            if (strlen($validation['data']['commentaire']) > 1000) {
                sendResponse([
                    'success' => false,
                    'message' => 'Le commentaire ne peut pas dépasser 1000 caractères'
                ], 400);
            }
            
            $commentaireController = new CommentaireController();
            $result = $commentaireController->createCommentaire(
                $user_id,
                null, // id_formation
                $validation['data']['id_service'],
                $validation['data']['commentaire'],
                $validation['data']['note']
            );
            sendResponse($result);
            break;
            
        case 'get_comments':
            $validation = validatePostData(['id_service' => 'int']);
            
            if (!empty($validation['errors'])) {
                sendResponse([
                    'success' => false, 
                    'message' => 'Données invalides', 
                    'errors' => $validation['errors']
                ], 400);
            }
            
            $commentaireController = new CommentaireController();
            $result = $commentaireController->getCommentairesByProduit($validation['data']['id_service']);
            sendResponse($result);
            break;
            
        case 'get_product_comments':
            // Récupérer les commentaires d'un produit spécifique
            $validation = validatePostData(['id_produit' => 'int']);
            
            if (!empty($validation['errors'])) {
                sendResponse([
                    'success' => false, 
                    'message' => 'Données invalides', 
                    'errors' => $validation['errors']
                ], 400);
            }
            
            try {
                require_once '../modele/commentaire.php';
                require_once '../modele/utilisateur.php';
                
                $commentaireCRUD = new CommentaireCRUD();
                $utilisateurCRUD = new RequeteUtilisateur();
                
                $commentaires = $commentaireCRUD->getByProduit($validation['data']['id_produit']);
                $commentaires_avec_utilisateurs = [];
                
                foreach ($commentaires as $commentaire) {
                    $utilisateur = $utilisateurCRUD->getUtilisateurById($commentaire->getIdUtilisateur());
                    
                    $commentaires_avec_utilisateurs[] = [
                        'id' => $commentaire->getIdCommentaire(),
                        'commentaire' => $commentaire->getCommentaire(),
                        'note' => $commentaire->getNote(),
                        'date' => $commentaire->getDateCommentaire(),
                        'utilisateur' => [
                            'nom' => $utilisateur ? $utilisateur->getNom() : 'Utilisateur inconnu',
                            'prenom' => $utilisateur ? $utilisateur->getPrenom() : '',
                            'photo' => $utilisateur ? $utilisateur->getPhoto() : null
                        ]
                    ];
                }
                
                // Calculer la note moyenne
                $note_moyenne = $commentaireCRUD->getAverageNoteByProduit($validation['data']['id_produit']);
                
                sendResponse([
                    'success' => true,
                    'data' => [
                        'commentaires' => $commentaires_avec_utilisateurs,
                        'total' => count($commentaires_avec_utilisateurs),
                        'note_moyenne' => round($note_moyenne, 1)
                    ]
                ]);
                
            } catch (Exception $e) {
                sendResponse([
                    'success' => false,
                    'message' => 'Erreur lors de la récupération des commentaires: ' . $e->getMessage()
                ], 500);
            }
            break;
            
        case 'get_service_comments':
            // Récupérer les commentaires d'un service/formation spécifique
            $validation = validatePostData(['id_service' => 'int']);
            
            if (!empty($validation['errors'])) {
                sendResponse([
                    'success' => false, 
                    'message' => 'Données invalides', 
                    'errors' => $validation['errors']
                ], 400);
            }
            
            try {
                require_once '../modele/commentaire.php';
                require_once '../modele/utilisateur.php';
                
                $commentaireCRUD = new CommentaireCRUD();
                $utilisateurCRUD = new RequeteUtilisateur();
                
                $commentaires = $commentaireCRUD->getByFormation($validation['data']['id_service']);
                $commentaires_avec_utilisateurs = [];
                
                foreach ($commentaires as $commentaire) {
                    $utilisateur = $utilisateurCRUD->getUtilisateurById($commentaire->getIdUtilisateur());
                    
                    $commentaires_avec_utilisateurs[] = [
                        'id' => $commentaire->getIdCommentaire(),
                        'commentaire' => $commentaire->getCommentaire(),
                        'note' => $commentaire->getNote(),
                        'date' => $commentaire->getDateCommentaire(),
                        'utilisateur' => [
                            'nom' => $utilisateur ? $utilisateur->getNom() : 'Utilisateur inconnu',
                            'prenom' => $utilisateur ? $utilisateur->getPrenom() : '',
                            'photo' => $utilisateur ? $utilisateur->getPhoto() : null
                        ]
                    ];
                }
                
                // Calculer la note moyenne
                $note_moyenne = $commentaireCRUD->getAverageNoteByFormation($validation['data']['id_service']);
                
                sendResponse([
                    'success' => true,
                    'data' => [
                        'commentaires' => $commentaires_avec_utilisateurs,
                        'total' => count($commentaires_avec_utilisateurs),
                        'note_moyenne' => round($note_moyenne, 1)
                    ]
                ]);
                
            } catch (Exception $e) {
                sendResponse([
                    'success' => false,
                    'message' => 'Erreur lors de la récupération des commentaires: ' . $e->getMessage()
                ], 500);
            }
            break;
            
        case 'add_service_comment':
            // Ajouter un commentaire pour un service/formation
            $user_id = requireAuth();
            $validation = validatePostData([
                'id_service' => 'int',
                'note' => 'int',
                'commentaire' => 'string'
            ]);
            
            if (!empty($validation['errors'])) {
                sendResponse([
                    'success' => false, 
                    'message' => 'Données invalides', 
                    'errors' => $validation['errors']
                ], 400);
            }
            
            // Validation des données
            if ($validation['data']['note'] < 1 || $validation['data']['note'] > 5) {
                sendResponse([
                    'success' => false,
                    'message' => 'La note doit être comprise entre 1 et 5'
                ], 400);
            }
            
            if (strlen(trim($validation['data']['commentaire'])) < 10) {
                sendResponse([
                    'success' => false,
                    'message' => 'Le commentaire doit contenir au moins 10 caractères'
                ], 400);
            }
            
            try {
                require_once '../modele/commentaire.php';
                
                $commentaire = new Commentaire(
                    null, // id_commentaire (auto-increment)
                    $user_id, // id_utilisateur
                    $validation['data']['id_service'], // id_formation
                    null, // id_produit
                    $validation['data']['commentaire'], // commentaire
                    $validation['data']['note'], // note
                    date('Y-m-d H:i:s'), // date_commentaire
                    'actif', // statut
                    null // parent_id
                );
                
                $commentaireCRUD = new CommentaireCRUD();
                $result = $commentaireCRUD->create($commentaire);
                
                if ($result) {
                    sendResponse([
                        'success' => true,
                        'message' => 'Commentaire ajouté avec succès',
                        'data' => ['id_commentaire' => $result]
                    ]);
                } else {
                    sendResponse([
                        'success' => false,
                        'message' => 'Erreur lors de l\'ajout du commentaire'
                    ], 500);
                }
                
            } catch (Exception $e) {
                sendResponse([
                    'success' => false,
                    'message' => 'Erreur lors de l\'ajout du commentaire: ' . $e->getMessage()
                ], 500);
            }
            break;
            
        // ===== GESTION DU PARTAGE =====
        case 'share_item':
            $user_id = requireAuth();
            $validation = validatePostData([
                'type' => 'string', 
                'id_element' => 'int', 
                'plateforme' => 'string'
            ]);
            
            if (!empty($validation['errors'])) {
                sendResponse([
                    'success' => false, 
                    'message' => 'Données invalides', 
                    'errors' => $validation['errors']
                ], 400);
            }
            
            // Valider les plateformes autorisées
            $plateformes_valides = ['facebook', 'twitter', 'linkedin', 'whatsapp', 'email', 'link'];
            if (!in_array($validation['data']['plateforme'], $plateformes_valides)) {
                sendResponse([
                    'success' => false,
                    'message' => 'Plateforme non supportée. Plateformes acceptées: ' . implode(', ', $plateformes_valides)
                ], 400);
            }
            
            $partageController = new PartageController();
            $result = $partageController->createPartage(
                $user_id,
                $validation['data']['type'] === 'formations' ? $validation['data']['id_element'] : null,
                $validation['data']['type'] === 'produits' ? $validation['data']['id_element'] : null,
                $validation['data']['plateforme']
            );
            sendResponse($result);
            break;
            
        // ===== GESTION DES NOTIFICATIONS =====
        case 'get_notifications':
            $user_id = requireAuth();
            
            // En attendant l'implémentation complète, simuler des notifications
            $notifications = [
                [
                    'id' => 1,
                    'type' => 'shipping',
                    'title' => 'Commande Expédiée',
                    'message' => 'Votre commande #12345 a été expédiée',
                    'time' => date('H:i'),
                    'date' => date('Y-m-d'),
                    'icon' => 'fas fa-shipping-fast',
                    'color' => '#1890ff',
                    'read' => false
                ],
                [
                    'id' => 2,
                    'type' => 'payment',
                    'title' => 'Paiement Confirmé',
                    'message' => 'Votre paiement a été accepté',
                    'time' => '14:30',
                    'date' => date('Y-m-d', strtotime('-1 day')),
                    'icon' => 'fas fa-check-circle',
                    'color' => '#52c41a',
                    'read' => true
                ]
            ];
            
            sendResponse([
                'success' => true, 
                'data' => $notifications,
                'total' => count($notifications),
                'unread' => count(array_filter($notifications, fn($n) => !$n['read']))
            ]);
            break;
            
        // ===== GESTION DU PROFIL =====
        case 'get_profile':
            $user_id = requireAuth();
            $utilisateurController = new UtilisateurController();
            $result = $utilisateurController->getUtilisateur($user_id);
            sendResponse($result);
            break;
            
        case 'update_profile':
            $user_id = requireAuth();
            $validation = validatePostData([], [
                'nom' => 'string',
                'prenom' => 'string', 
                'email' => 'email',
                'telephone' => 'string'
            ]);
            
            if (!empty($validation['errors'])) {
                sendResponse([
                    'success' => false, 
                    'message' => 'Données invalides', 
                    'errors' => $validation['errors']
                ], 400);
            }
            
            $utilisateurController = new UtilisateurController();
            $result = $utilisateurController->updateUtilisateur($user_id, $validation['data']);
            sendResponse($result);
            break;
            
        // ===== GESTION DES STATISTIQUES =====
        case 'get_stats':
            $user_id = requireAuth();
            
            // Exemple de statistiques utilisateur
            $stats = [
                'commandes_total' => 12,
                'commandes_en_cours' => 2,
                'favoris_total' => 8,
                'commentaires_total' => 5,
                'points_fidelite' => 150
            ];
            
            sendResponse([
                'success' => true,
                'data' => $stats
            ]);
            break;
            
        default:
            sendResponse([
                'success' => false, 
                'message' => 'Action non reconnue',
                'error_code' => 'UNKNOWN_ACTION',
                'available_actions' => [
                    'panier' => ['add_to_cart', 'get_cart', 'update_cart_quantity', 'remove_from_cart', 'clear_cart', 'get_cart_count'],
                    'favoris' => ['add_to_favorites', 'remove_from_favorites', 'toggle_favorite', 'get_favorites', 'is_favorite'],
                    'commentaires' => ['add_comment', 'get_comments'],
                    'partage' => ['share_item'],
                    'notifications' => ['get_notifications'],
                    'profil' => ['get_profile', 'update_profile', 'get_stats']
                ]
            ], 400);
            break;
    }
    
} catch (Exception $e) {
    // Déterminer le code de statut basé sur le code d'erreur
    $status_code = 500;
    if ($e->getCode() === 401) {
        $status_code = 401;
    } elseif ($e->getCode() === 404) {
        $status_code = 404;
    }
    
    // Log de l'erreur pour le débogage
    logError('API Error', [
        'action' => $action,
        'user_id' => $_SESSION['user_id'] ?? 'non connecté',
        'error' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ]);
    
    $response = [
        'success' => false, 
        'message' => $e->getMessage(),
        'error_code' => 'SERVER_ERROR'
    ];
    
    // Ajouter les détails de débogage uniquement en mode développement
    if (defined('DEBUG_MODE') && DEBUG_MODE) {
        $response['debug'] = [
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ];
    }
    
    sendResponse($response, $status_code);
}
?>