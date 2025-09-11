<?php
// index.php - Point d'entrée principal de l'application avec gestion d'images

// Configuration de base
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Inclure tous les contrôleurs
require_once '../controle/controleur_utilisateur.php';
require_once '../controle/controleur_commande.php';
require_once '../controle/controleur_detailCommande.php';
require_once '../controle/controleur_formation.php';
require_once '../controle/controleur_inscription.php';
require_once '../controle/controleur_presence.php';
require_once '../controle/controleur_produit.php';
require_once '../controle/controleur.paiement.php';

// Inclure les modèles
require_once __DIR__ . '/../modele/utilisateur.php';
require_once __DIR__ . '/../modele/formation.php';
require_once __DIR__ . '/../modele/inscription.php';
require_once __DIR__ . '/../modele/paiement.php';
require_once __DIR__ . '/../modele/presence.php';
require_once __DIR__ . '/../modele/produit.php';
require_once __DIR__ . '/../modele/detail_commande.php';

// Fonction pour obtenir les données de la requête
function getRequestData() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        return array_merge($_POST, $_FILES);
    } else {
        return $_GET;
    }
}

// Fonction pour envoyer une réponse JSON
function sendJsonResponse($data) {
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

// Fonction pour vérifier l'authentification
function checkAuthentication() {
    if (!isset($_SESSION['user_id'])) {
        sendJsonResponse([
            'success' => false,
            'message' => 'Authentification requise'
        ]);
    }
}

// Fonction pour vérifier les permissions administrateur
function checkAdminPermission() {
    if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
        sendJsonResponse([
            'success' => false,
            'message' => 'Permissions insuffisantes'
        ]);
    }
}

// Fonctions de gestion d'images
function handleImageUpload($file, $category) {
    if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'message' => 'Aucun fichier uploadé'];
    }
    
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $maxSize = 5 * 1024 * 1024;
    
    if (!in_array($file['type'], $allowedTypes)) {
        return ['success' => false, 'message' => 'Type de fichier non autorisé'];
    }
    
    if ($file['size'] > $maxSize) {
        return ['success' => false, 'message' => 'Fichier trop volumineux (max 5MB)'];
    }
    
    $uploadDir = __DIR__ . '/uploads/' . $category . '/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '_' . time() . '.' . $extension;
    $filepath = $uploadDir . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        return [
            'success' => true,
            'message' => 'Fichier uploadé avec succès',
            'filepath' => 'uploads/' . $category . '/' . $filename,
            'filename' => $filename
        ];
    }
    
    return ['success' => false, 'message' => 'Erreur lors du téléchargement'];
}

function deleteImage($filepath) {
    if (file_exists(__DIR__ . '/' . $filepath) && is_file(__DIR__ . '/' . $filepath)) {
        if (unlink(__DIR__ . '/' . $filepath)) {
            return ['success' => true, 'message' => 'Image supprimée'];
        }
    }
    return ['success' => false, 'message' => 'Erreur lors de la suppression'];
}

// Traitement de la requête
try {
    $data = getRequestData();
    $do = $data['do'] ?? '';

    // Initialiser les contrôleurs
    $userController = new UtilisateurController();
    $formationController = new FormationController();
    $inscriptionController = new InscriptionController();
    $paiementController = new PaiementController();
    $presenceController = new PresenceController();
    $produitController = new ProduitController();
    $detailCommandeController = new DetailCommandeController();

    // Router les requêtes en fonction du paramètre "do"
    switch ($do) {
        // Gestion des fichiers
        case 'uploadImage':
            checkAuthentication();
            $result = handleImageUpload($data['image'] ?? null, $data['category'] ?? 'general');
            sendJsonResponse($result);
            break;
            
        case 'deleteImage':
            checkAuthentication();
            $result = deleteImage($data['filepath'] ?? '');
            sendJsonResponse($result);
            break;

        // Gestion des utilisateurs
        case 'user_login':
            $result = $userController->authenticate($data['email'] ?? '',$data['password'] ?? '');
            if ($result['success']) {
                $_SESSION['user_id'] = $result['data']->getId();
                $_SESSION['user_role'] = $result['data']->getRole();
                $_SESSION['user_nom'] = $result['data']->getNom();
                $_SESSION['user_prenom'] = $result['data']->getPrenom();
            }
            sendJsonResponse($result);
            break;

        case 'user_logout':
            session_destroy();
            sendJsonResponse(['success' => true, 'message' => 'Déconnexion réussie']);
            break;

        case 'user_create':
            //checkAdminPermission();
            // Gestion de l'upload d'image pour les utilisateurs
            $photoPath = null;
            if (isset($data['photo']) && $data['photo']['error'] === UPLOAD_ERR_OK) {
                $uploadResult = handleImageUpload($data['photo'], 'utilisateurs');
                if ($uploadResult['success']) {
                    $photoPath = $uploadResult['filepath'];
                } else {
                    sendJsonResponse($uploadResult);
                    break;
                }
            }
            
            $result = $userController->createUtilisateur(
                $data['nom'] ?? '',
                $data['prenom'] ?? '',
                $data['email'] ?? '',
                $data['password'] ?? '',
                $data['telephone'] ?? '',
                $data['role'] ?? '',
                $data['bio'] ?? '',
                $photoPath,
                $data['specialite'] ?? ''
            );
            if ($data['role'] === 'formateur') {
                $redirectPath = '../Admin/liste_formateur.php';
                header("Location: $redirectPath");
                exit;
            }

            break;

        case 'user_participant':
                //checkAdminPermission();
                // Gestion de l'upload d'image pour les utilisateurs
                $photoPath = null;
                if (isset($data['photo']) && $data['photo']['error'] === UPLOAD_ERR_OK) {
                    $uploadResult = handleImageUpload($data['photo'], 'utilisateurs');
                    if ($uploadResult['success']) {
                        $photoPath = $uploadResult['filepath'];
                    } else {
                        sendJsonResponse($uploadResult);
                        break;
                    }
                }
                
                $result = $userController->createUtilisateur(
                    $data['nom'] ?? '',
                    $data['prenom'] ?? '',
                    $data['email'] ?? '',
                    $data['password'] ?? '',
                    $data['telephone'] ?? '',
                    $data['role'] ?? '',
                    $data['bio'] ?? '',
                    $photoPath,
                    $data['specialite'] ?? '',
                    $data['id_formation'] ?? ''
                );
                
                if ($data['role'] === 'etudiant') {
                    $redirectPath = '../Admin/liste_participant.php';
                    header("Location: $redirectPath");
                    exit;
                }
    
                break;

        case 'user_get':
            checkAuthentication();
            $result = $userController->getUtilisateur($data['id'] ?? 0);
            sendJsonResponse($result);
            break;

        case 'user_getAll':
            checkAdminPermission();
            $result = $userController->getAllUtilisateurs();
            sendJsonResponse($result);
            break;

        case 'user_update':
            checkAuthentication();
            // Gestion de l'upload d'image pour la mise à jour
            $photoPath = $data['existing_photo'] ?? null;
            if (isset($data['photo']) && $data['photo']['error'] === UPLOAD_ERR_OK) {
                // Supprimer l'ancienne photo si elle existe
                if ($photoPath && file_exists(__DIR__ . '/' . $photoPath)) {
                    unlink(__DIR__ . '/' . $photoPath);
                }
                
                $uploadResult = handleImageUpload($data['photo'], 'utilisateurs');
                if ($uploadResult['success']) {
                    $photoPath = $uploadResult['filepath'];
                } else {
                    sendJsonResponse($uploadResult);
                    break;
                }
            }
            
            $result = $userController->updateUtilisateur(
                $data['id'] ?? 0,
                $data['nom'] ?? '',
                $data['prenom'] ?? '',
                $data['email'] ?? '',
                $data['telephone'] ?? '',
                $data['role'] ?? '',
                $photoPath
            );
            sendJsonResponse($result);
            break;

        case 'user_updatePassword':
            checkAuthentication();
            $result = $userController->updatePassword(
                $data['id'] ?? 0,
                $data['currentPassword'] ?? '',
                $data['newPassword'] ?? ''
            );
            sendJsonResponse($result);
            break;

        case 'user_delete':
            //checkAdminPermission();
            // Supprimer la photo de l'utilisateur si elle existe
            $user = $userController->getUtilisateur($data['id'] ?? 0);
            if ($user['success'] && $user['data']->getPhoto()) {
                $photoPath = $user['data']->getPhoto();
                if (file_exists(__DIR__ . '/' . $photoPath)) {
                    unlink(__DIR__ . '/' . $photoPath);
                }
            }
            
            $result = $userController->deleteUtilisateur($data['id'] ?? 0);
            sendJsonResponse($result);
            break;

        case 'user_search':
            //checkAuthentication();
            $result = $userController->searchUtilisateurs($data['searchTerm'] ?? '');
            sendJsonResponse($result);
            break;

        case 'user_getStats':
            //checkAdminPermission();
            $result = $userController->getStats();
            sendJsonResponse($result);
            break;

        // Gestion des formations
        case 'create_formation':
            //checkAuthentication();
            // Gestion de l'upload d'image pour les formations
            $photoPath = null;
            if (isset($data['photo']) && $data['photo']['error'] === UPLOAD_ERR_OK) {
                $uploadResult = handleImageUpload($data['photo'], 'formations');
                if ($uploadResult['success']) {
                    $photoPath = $uploadResult['filepath'];
                } else {
                    sendJsonResponse($uploadResult);
                    break;
                }
            }
            
            $result = $formationController->createFormation(
                $data['titre'] ?? '',
                $data['description'] ?? '',
                $data['prix'] ?? 0,
                $data['duree'] ?? 0,
                $data['id_formateur'] ?? 0,
                $data['debut_formation'] ?? 0,
                $photoPath
            );
            $redirectPath = '../Admin/liste_formation.php';
                header("Location: $redirectPath");
                exit;
            break;

        case 'formation_get':
            $result = $formationController->getFormation($data['id'] ?? 0);
            sendJsonResponse($result);
            break;

        case 'formation_getAll':
            $result = $formationController->getAllFormations();
            sendJsonResponse($result);
            break;

        case 'formation_update':
            checkAuthentication();
            // Gestion de l'upload d'image pour la mise à jour
            $photoPath = $data['existing_photo'] ?? null;
            if (isset($data['photo']) && $data['photo']['error'] === UPLOAD_ERR_OK) {
                // Supprimer l'ancienne photo si elle existe
                if ($photoPath && file_exists(__DIR__ . '/' . $photoPath)) {
                    unlink(__DIR__ . '/' . $photoPath);
                }
                
                $uploadResult = handleImageUpload($data['photo'], 'formations');
                if ($uploadResult['success']) {
                    $photoPath = $uploadResult['filepath'];
                } else {
                    sendJsonResponse($uploadResult);
                    break;
                }
            }
            
            $result = $formationController->updateFormation(
                $data['id'] ?? 0,
                $data['titre'] ?? '',
                $data['description'] ?? '',
                $data['prix'] ?? 0,
                $data['duree'] ?? 0,
                $data['id_formateur'] ?? 0,
                $data['debut_formation'] ?? 0,
                $photoPath
            );
            sendJsonResponse($result);
            break;

        case 'formation_delete':
            checkAuthentication();
            // Supprimer la photo de la formation si elle existe
            $formation = $formationController->getFormation($data['id'] ?? 0);
            if ($formation['success'] && $formation['data']->getPhoto()) {
                $photoPath = $formation['data']->getPhoto();
                if (file_exists(__DIR__ . '/' . $photoPath)) {
                    unlink(__DIR__ . '/' . $photoPath);
                }
            }
            
            $result = $formationController->deleteFormation($data['id'] ?? 0);
            sendJsonResponse($result);
            break;

        case 'formation_search':
            $result = $formationController->searchFormations($data['searchTerm'] ?? '');
            sendJsonResponse($result);
            break;

        case 'formation_getByFormateur':
            $result = $formationController->getFormationsByFormateur($data['id_formateur'] ?? 0);
            sendJsonResponse($result);
            break;

        case 'formation_getRecent':
            $result = $formationController->getRecentFormations($data['limit'] ?? 5);
            sendJsonResponse($result);
            break;

        // Gestion des produits
        case 'produit_create':
            //checkAuthentication();
            // Gestion de l'upload d'image pour les produits
            $photoPath = null;
            if (isset($data['photo']) && $data['photo']['error'] === UPLOAD_ERR_OK) {
                $uploadResult = handleImageUpload($data['photo'], 'produits');
                if ($uploadResult['success']) {
                    $photoPath = $uploadResult['filepath'];
                } else {
                    sendJsonResponse($uploadResult);
                    break;
                }
            }
            
            $result = $produitController->createProduit(
                $data['nom'] ?? '',
                $data['description'] ?? '',
                $data['prix'] ?? 0,
                $data['stock'] ?? 0,
                $data['categorie'] ?? '',
                $photoPath
            );
            $redirectPath = '../Admin/liste_equipement.php';
                header("Location: $redirectPath");
                exit;
            break;

        case 'produit_get':
            $result = $produitController->getProduit($data['id'] ?? 0);
            sendJsonResponse($result);
            break;

        case 'produit_getAll':
            $result = $produitController->getAllProduits();
            sendJsonResponse($result);
            break;

        case 'produit_update':
            checkAuthentication();
            // Gestion de l'upload d'image pour la mise à jour
            $photoPath = $data['existing_photo'] ?? null;
            if (isset($data['photo']) && $data['photo']['error'] === UPLOAD_ERR_OK) {
                // Supprimer l'ancienne photo si elle existe
                if ($photoPath && file_exists(__DIR__ . '/' . $photoPath)) {
                    unlink(__DIR__ . '/' . $photoPath);
                }
                
                $uploadResult = handleImageUpload($data['photo'], 'produits');
                if ($uploadResult['success']) {
                    $photoPath = $uploadResult['filepath'];
                } else {
                    sendJsonResponse($uploadResult);
                    break;
                }
            }
            
            $result = $produitController->updateProduit(
                $data['id'] ?? 0,
                $data['nom'] ?? '',
                $data['description'] ?? '',
                $data['prix'] ?? 0,
                $data['stock'] ?? 0,
                $data['categorie'] ?? '',
                $photoPath
            );
            sendJsonResponse($result);
            break;

        case 'produit_updateStock':
            checkAuthentication();
            $result = $produitController->updateStock(
                $data['id'] ?? 0,
                $data['stock'] ?? 0
            );
            sendJsonResponse($result);
            break;

        case 'produit_increaseStock':
            checkAuthentication();
            $result = $produitController->increaseStock(
                $data['id'] ?? 0,
                $data['quantity'] ?? 0
            );
            sendJsonResponse($result);
            break;

        case 'produit_decreaseStock':
            checkAuthentication();
            $result = $produitController->decreaseStock(
                $data['id'] ?? 0,
                $data['quantity'] ?? 0
            );
            sendJsonResponse($result);
            break;

        case 'produit_delete':
            checkAuthentication();
            // Supprimer la photo du produit si elle existe
            $produit = $produitController->getProduit($data['id'] ?? 0);
            if ($produit['success'] && $produit['data']->getPhoto()) {
                $photoPath = $produit['data']->getPhoto();
                if (file_exists(__DIR__ . '/' . $photoPath)) {
                    unlink(__DIR__ . '/' . $photoPath);
                }
            }
            
            $result = $produitController->deleteProduit($data['id'] ?? 0);
            sendJsonResponse($result);
            break;

        case 'produit_getByCategorie':
            $result = $produitController->getProduitsByCategorie($data['categorie'] ?? '');
            sendJsonResponse($result);
            break;

        case 'produit_getLowStock':
            checkAuthentication();
            $result = $produitController->getLowStockProduits($data['threshold'] ?? 10);
            sendJsonResponse($result);
            break;

        case 'produit_search':
            $result = $produitController->searchProduits($data['searchTerm'] ?? '');
            sendJsonResponse($result);
            break;

        case 'produit_getStats':
            checkAuthentication();
            $result = $produitController->getStats();
            sendJsonResponse($result);
            break;

        case 'produit_getCategories':
            $result = $produitController->getCategories();
            sendJsonResponse($result);
            break;

        // Gestion des inscriptions
        case 'inscription_create':
            checkAuthentication();
            $result = $inscriptionController->createInscription(
                $data['id_utilisateur'] ?? 0,
                $data['id_formation'] ?? 0,
                $data['statut'] ?? 'inscrit'
            );
            sendJsonResponse($result);
            break;

        case 'inscription_get':
            checkAuthentication();
            $result = $inscriptionController->getInscription($data['id'] ?? 0);
            sendJsonResponse($result);
            break;

        case 'inscription_getAll':
            checkAuthentication();
            $result = $inscriptionController->getAllInscriptions();
            sendJsonResponse($result);
            break;

        case 'inscription_getByUtilisateur':
            checkAuthentication();
            $result = $inscriptionController->getInscriptionsByUtilisateur($data['id_utilisateur'] ?? 0);
            sendJsonResponse($result);
            break;

        case 'inscription_getByFormation':
            checkAuthentication();
            $result = $inscriptionController->getInscriptionsByFormation($data['id_formation'] ?? 0);
            sendJsonResponse($result);
            break;

        case 'inscription_updateStatut':
            checkAuthentication();
            $result = $inscriptionController->changeStatut(
                $data['id_inscription'] ?? 0,
                $data['statut'] ?? ''
            );
            sendJsonResponse($result);
            break;

        case 'inscription_delete':
            checkAuthentication();
            $result = $inscriptionController->deleteInscription($data['id'] ?? 0);
            sendJsonResponse($result);
            break;

        case 'inscription_getStats':
            checkAuthentication();
            $result = $inscriptionController->getStatsByStatut();
            sendJsonResponse($result);
            break;

        case 'inscription_checkExisting':
            checkAuthentication();
            $result = $inscriptionController->checkExistingInscription(
                $data['id_utilisateur'] ?? 0,
                $data['id_formation'] ?? 0
            );
            sendJsonResponse($result);
            break;

        // Gestion des paiements
        case 'paiement_create':
            checkAuthentication();
            $result = $paiementController->createPaiement(
                $data['id_utilisateur'] ?? 0,
                $data['type'] ?? '',
                $data['id_reference'] ?? 0,
                $data['montant'] ?? 0,
                $data['mode'] ?? '',
                $data['statut'] ?? 'en attente'
            );
            sendJsonResponse($result);
            break;

        case 'paiement_get':
            checkAuthentication();
            $result = $paiementController->getPaiement($data['id'] ?? 0);
            sendJsonResponse($result);
            break;

        case 'paiement_getAll':
            checkAuthentication();
            $result = $paiementController->getAllPaiements();
            sendJsonResponse($result);
            break;

        case 'paiement_getByUtilisateur':
            checkAuthentication();
            $result = $paiementController->getPaiementsByUtilisateur($data['id_utilisateur'] ?? 0);
            sendJsonResponse($result);
            break;

        case 'paiement_updateStatut':
            checkAuthentication();
            $result = $paiementController->updateStatut(
                $data['id_paiement'] ?? 0,
                $data['statut'] ?? ''
            );
            sendJsonResponse($result);
            break;

        case 'paiement_getStats':
            checkAuthentication();
            $result = $paiementController->getStats();
            sendJsonResponse($result);
            break;

        // Gestion des présences
        case 'presence_create':
            checkAuthentication();
            $result = $presenceController->createPresence(
                $data['id_inscription'] ?? 0,
                $data['date_session'] ?? '',
                $data['statut'] ?? 'absent'
            );
            sendJsonResponse($result);
            break;

        case 'presence_get':
            checkAuthentication();
            $result = $presenceController->getPresence($data['id'] ?? 0);
            sendJsonResponse($result);
            break;

        case 'presence_getByInscription':
            checkAuthentication();
            $result = $presenceController->getPresencesByInscription($data['id_inscription'] ?? 0);
            sendJsonResponse($result);
            break;

        case 'presence_updateStatut':
            checkAuthentication();
            $result = $presenceController->updateStatut(
                $data['id_presence'] ?? 0,
                $data['statut'] ?? ''
            );
            sendJsonResponse($result);
            break;

        case 'presence_markMultiple':
            checkAuthentication();
            $result = $presenceController->markMultiplePresences($data['presences'] ?? []);
            sendJsonResponse($result);
            break;

        case 'presence_getStatsByInscription':
            checkAuthentication();
            $result = $presenceController->getStatsByInscription($data['id_inscription'] ?? 0);
            sendJsonResponse($result);
            break;

        // Gestion des détails de commande
        case 'detail_commande_create':
            checkAuthentication();
            $result = $detailCommandeController->createDetailCommande(
                $data['id_commande'] ?? 0,
                $data['id_produit'] ?? 0,
                $data['quantite'] ?? 0,
                $data['prix_unitaire'] ?? 0
            );
            sendJsonResponse($result);
            break;

        case 'detail_commande_getByCommande':
            checkAuthentication();
            $result = $detailCommandeController->getDetailsByCommande($data['id_commande'] ?? 0);
            sendJsonResponse($result);
            break;

        case 'detail_commande_getTotalCommande':
            checkAuthentication();
            $result = $detailCommandeController->getCommandeTotal($data['id_commande'] ?? 0);
            sendJsonResponse($result);
            break;

        // Page d'accueil par défaut
        case '':
            // Afficher l'interface utilisateur
            if (file_exists('index.html')) {
                include 'index.html';
            } else {
                echo "Bienvenue sur l'application. Utilisez le paramètre 'do' pour effectuer des actions.";
            }
            break;

        default:
            sendJsonResponse(['success' => false, 'message' => 'Action non reconnue: ' . $do]);
    }

} catch (Exception $e) {
    sendJsonResponse([
        'success' => false,
        'message' => 'Erreur serveur: ' . $e->getMessage()
    ]);
}
?>