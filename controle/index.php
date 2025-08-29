<?php
// index.php - Point d'entrée principal de l'application

// Configuration de base
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Inclure tous les contrôleurs
require_once 'controllers/UtilisateurController.php';
require_once 'controllers/FormationController.php';
require_once 'controllers/InscriptionController.php';
require_once 'controllers/PaiementController.php';
require_once 'controllers/PresenceController.php';
require_once 'controllers/ProduitController.php';
require_once 'controllers/DetailCommandeController.php';

// Inclure les modèles
require_once 'models/Utilisateur.php';
require_once 'models/Formation.php';
require_once 'models/Inscription.php';
require_once 'models/Paiement.php';
require_once 'models/Presence.php';
require_once 'models/Produit.php';
require_once 'models/DetailCommande.php';

// Inclure les classes de requête
require_once 'models/RequeteUtilisateur.php';
require_once 'models/RequeteFormation.php';
require_once 'models/RequeteInscription.php';
require_once 'models/CRUDPaiement.php';
require_once 'models/CRUDPresence.php';
require_once 'models/CRUDProduit.php';
require_once 'models/RequeteDetailCommande.php';

// Fonction pour obtenir les données de la requête
function getRequestData() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        return $_POST;
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

// Traitement de la requête
try {
    $data = getRequestData();
    $action = $data['action'] ?? '';
    $entity = $data['entity'] ?? '';

    // Initialiser les contrôleurs
    $userController = new UtilisateurController();
    $formationController = new FormationController();
    $inscriptionController = new InscriptionController();
    $paiementController = new PaiementController();
    $presenceController = new PresenceController();
    $produitController = new ProduitController();
    $detailCommandeController = new DetailCommandeController();

    // Router les requêtes
    switch ($entity) {
        case 'utilisateur':
            switch ($action) {
                case 'login':
                    $result = $userController->authenticate(
                        $data['email'] ?? '',
                        $data['password'] ?? ''
                    );
                    if ($result['success']) {
                        $_SESSION['user_id'] = $result['data']->getId();
                        $_SESSION['user_role'] = $result['data']->getRole();
                        $_SESSION['user_nom'] = $result['data']->getNom();
                        $_SESSION['user_prenom'] = $result['data']->getPrenom();
                    }
                    sendJsonResponse($result);
                    break;

                case 'logout':
                    session_destroy();
                    sendJsonResponse(['success' => true, 'message' => 'Déconnexion réussie']);
                    break;

                case 'create':
                    checkAdminPermission();
                    $result = $userController->createUtilisateur(
                        $data['nom'] ?? '',
                        $data['prenom'] ?? '',
                        $data['email'] ?? '',
                        $data['password'] ?? '',
                        $data['telephone'] ?? '',
                        $data['role'] ?? '',
                        $data['photo'] ?? null
                    );
                    sendJsonResponse($result);
                    break;

                case 'get':
                    checkAuthentication();
                    $result = $userController->getUtilisateur($data['id'] ?? 0);
                    sendJsonResponse($result);
                    break;

                case 'getAll':
                    checkAdminPermission();
                    $result = $userController->getAllUtilisateurs();
                    sendJsonResponse($result);
                    break;

                case 'update':
                    checkAuthentication();
                    $result = $userController->updateUtilisateur(
                        $data['id'] ?? 0,
                        $data['nom'] ?? '',
                        $data['prenom'] ?? '',
                        $data['email'] ?? '',
                        $data['telephone'] ?? '',
                        $data['role'] ?? '',
                        $data['photo'] ?? null
                    );
                    sendJsonResponse($result);
                    break;

                case 'updatePassword':
                    checkAuthentication();
                    $result = $userController->updatePassword(
                        $data['id'] ?? 0,
                        $data['currentPassword'] ?? '',
                        $data['newPassword'] ?? ''
                    );
                    sendJsonResponse($result);
                    break;

                case 'delete':
                    checkAdminPermission();
                    $result = $userController->deleteUtilisateur($data['id'] ?? 0);
                    sendJsonResponse($result);
                    break;

                case 'search':
                    checkAuthentication();
                    $result = $userController->searchUtilisateurs($data['searchTerm'] ?? '');
                    sendJsonResponse($result);
                    break;

                case 'getStats':
                    checkAdminPermission();
                    $result = $userController->getStats();
                    sendJsonResponse($result);
                    break;

                default:
                    sendJsonResponse(['success' => false, 'message' => 'Action non reconnue']);
            }
            break;

        case 'formation':
            switch ($action) {
                case 'create':
                    checkAuthentication();
                    $result = $formationController->createFormation(
                        $data['titre'] ?? '',
                        $data['description'] ?? '',
                        $data['prix'] ?? 0,
                        $data['duree'] ?? 0,
                        $data['id_formateur'] ?? 0,
                        $data['photo'] ?? null
                    );
                    sendJsonResponse($result);
                    break;

                case 'get':
                    $result = $formationController->getFormation($data['id'] ?? 0);
                    sendJsonResponse($result);
                    break;

                case 'getAll':
                    $result = $formationController->getAllFormations();
                    sendJsonResponse($result);
                    break;

                case 'update':
                    checkAuthentication();
                    $result = $formationController->updateFormation(
                        $data['id'] ?? 0,
                        $data['titre'] ?? '',
                        $data['description'] ?? '',
                        $data['prix'] ?? 0,
                        $data['duree'] ?? 0,
                        $data['id_formateur'] ?? 0,
                        $data['photo'] ?? null
                    );
                    sendJsonResponse($result);
                    break;

                case 'delete':
                    checkAuthentication();
                    $result = $formationController->deleteFormation($data['id'] ?? 0);
                    sendJsonResponse($result);
                    break;

                case 'search':
                    $result = $formationController->searchFormations($data['searchTerm'] ?? '');
                    sendJsonResponse($result);
                    break;

                case 'getByFormateur':
                    $result = $formationController->getFormationsByFormateur($data['id_formateur'] ?? 0);
                    sendJsonResponse($result);
                    break;

                case 'getRecent':
                    $result = $formationController->getRecentFormations($data['limit'] ?? 5);
                    sendJsonResponse($result);
                    break;

                default:
                    sendJsonResponse(['success' => false, 'message' => 'Action non reconnue']);
            }
            break;

        case 'inscription':
            switch ($action) {
                case 'create':
                    checkAuthentication();
                    $result = $inscriptionController->createInscription(
                        $data['id_utilisateur'] ?? 0,
                        $data['id_formation'] ?? 0,
                        $data['statut'] ?? 'inscrit'
                    );
                    sendJsonResponse($result);
                    break;

                case 'get':
                    checkAuthentication();
                    $result = $inscriptionController->getInscription($data['id'] ?? 0);
                    sendJsonResponse($result);
                    break;

                case 'getAll':
                    checkAuthentication();
                    $result = $inscriptionController->getAllInscriptions();
                    sendJsonResponse($result);
                    break;

                case 'getByUtilisateur':
                    checkAuthentication();
                    $result = $inscriptionController->getInscriptionsByUtilisateur($data['id_utilisateur'] ?? 0);
                    sendJsonResponse($result);
                    break;

                case 'getByFormation':
                    checkAuthentication();
                    $result = $inscriptionController->getInscriptionsByFormation($data['id_formation'] ?? 0);
                    sendJsonResponse($result);
                    break;

                case 'updateStatut':
                    checkAuthentication();
                    $result = $inscriptionController->changeStatut(
                        $data['id_inscription'] ?? 0,
                        $data['statut'] ?? ''
                    );
                    sendJsonResponse($result);
                    break;

                case 'delete':
                    checkAuthentication();
                    $result = $inscriptionController->deleteInscription($data['id'] ?? 0);
                    sendJsonResponse($result);
                    break;

                case 'getStats':
                    checkAuthentication();
                    $result = $inscriptionController->getStatsByStatut();
                    sendJsonResponse($result);
                    break;

                case 'checkExisting':
                    checkAuthentication();
                    $result = $inscriptionController->checkExistingInscription(
                        $data['id_utilisateur'] ?? 0,
                        $data['id_formation'] ?? 0
                    );
                    sendJsonResponse($result);
                    break;

                default:
                    sendJsonResponse(['success' => false, 'message' => 'Action non reconnue']);
            }
            break;

        case 'paiement':
            switch ($action) {
                case 'create':
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

                case 'get':
                    checkAuthentication();
                    $result = $paiementController->getPaiement($data['id'] ?? 0);
                    sendJsonResponse($result);
                    break;

                case 'getAll':
                    checkAuthentication();
                    $result = $paiementController->getAllPaiements();
                    sendJsonResponse($result);
                    break;

                case 'getByUtilisateur':
                    checkAuthentication();
                    $result = $paiementController->getPaiementsByUtilisateur($data['id_utilisateur'] ?? 0);
                    sendJsonResponse($result);
                    break;

                case 'updateStatut':
                    checkAuthentication();
                    $result = $paiementController->updateStatut(
                        $data['id_paiement'] ?? 0,
                        $data['statut'] ?? ''
                    );
                    sendJsonResponse($result);
                    break;

                case 'getStats':
                    checkAuthentication();
                    $result = $paiementController->getStats();
                    sendJsonResponse($result);
                    break;

                default:
                    sendJsonResponse(['success' => false, 'message' => 'Action non reconnue']);
            }
            break;

        case 'presence':
            switch ($action) {
                case 'create':
                    checkAuthentication();
                    $result = $presenceController->createPresence(
                        $data['id_inscription'] ?? 0,
                        $data['date_session'] ?? '',
                        $data['statut'] ?? 'absent'
                    );
                    sendJsonResponse($result);
                    break;

                case 'get':
                    checkAuthentication();
                    $result = $presenceController->getPresence($data['id'] ?? 0);
                    sendJsonResponse($result);
                    break;

                case 'getByInscription':
                    checkAuthentication();
                    $result = $presenceController->getPresencesByInscription($data['id_inscription'] ?? 0);
                    sendJsonResponse($result);
                    break;

                case 'updateStatut':
                    checkAuthentication();
                    $result = $presenceController->updateStatut(
                        $data['id_presence'] ?? 0,
                        $data['statut'] ?? ''
                    );
                    sendJsonResponse($result);
                    break;

                case 'markMultiple':
                    checkAuthentication();
                    $result = $presenceController->markMultiplePresences($data['presences'] ?? []);
                    sendJsonResponse($result);
                    break;

                case 'getStatsByInscription':
                    checkAuthentication();
                    $result = $presenceController->getStatsByInscription($data['id_inscription'] ?? 0);
                    sendJsonResponse($result);
                    break;

                default:
                    sendJsonResponse(['success' => false, 'message' => 'Action non reconnue']);
            }
            break;

        case 'produit':
            switch ($action) {
                case 'create':
                    checkAuthentication();
                    $result = $produitController->createProduit(
                        $data['nom'] ?? '',
                        $data['description'] ?? '',
                        $data['prix'] ?? 0,
                        $data['stock'] ?? 0,
                        $data['categorie'] ?? '',
                        $data['photo'] ?? null
                    );
                    sendJsonResponse($result);
                    break;

                case 'get':
                    $result = $produitController->getProduit($data['id'] ?? 0);
                    sendJsonResponse($result);
                    break;

                case 'getAll':
                    $result = $produitController->getAllProduits();
                    sendJsonResponse($result);
                    break;

                case 'updateStock':
                    checkAuthentication();
                    $result = $produitController->updateStock(
                        $data['id'] ?? 0,
                        $data['stock'] ?? 0
                    );
                    sendJsonResponse($result);
                    break;

                case 'increaseStock':
                    checkAuthentication();
                    $result = $produitController->increaseStock(
                        $data['id'] ?? 0,
                        $data['quantity'] ?? 0
                    );
                    sendJsonResponse($result);
                    break;

                case 'decreaseStock':
                    checkAuthentication();
                    $result = $produitController->decreaseStock(
                        $data['id'] ?? 0,
                        $data['quantity'] ?? 0
                    );
                    sendJsonResponse($result);
                    break;

                case 'getByCategorie':
                    $result = $produitController->getProduitsByCategorie($data['categorie'] ?? '');
                    sendJsonResponse($result);
                    break;

                case 'getLowStock':
                    checkAuthentication();
                    $result = $produitController->getLowStockProduits($data['threshold'] ?? 10);
                    sendJsonResponse($result);
                    break;

                case 'search':
                    $result = $produitController->searchProduits($data['searchTerm'] ?? '');
                    sendJsonResponse($result);
                    break;

                case 'getStats':
                    checkAuthentication();
                    $result = $produitController->getStats();
                    sendJsonResponse($result);
                    break;

                case 'getCategories':
                    $result = $produitController->getCategories();
                    sendJsonResponse($result);
                    break;

                default:
                    sendJsonResponse(['success' => false, 'message' => 'Action non reconnue']);
            }
            break;

        case 'detail_commande':
            switch ($action) {
                case 'create':
                    checkAuthentication();
                    $result = $detailCommandeController->createDetailCommande(
                        $data['id_commande'] ?? 0,
                        $data['id_produit'] ?? 0,
                        $data['quantite'] ?? 0,
                        $data['prix_unitaire'] ?? 0
                    );
                    sendJsonResponse($result);
                    break;

                case 'getByCommande':
                    checkAuthentication();
                    $result = $detailCommandeController->getDetailsByCommande($data['id_commande'] ?? 0);
                    sendJsonResponse($result);
                    break;

                case 'getTotalCommande':
                    checkAuthentication();
                    $result = $detailCommandeController->getCommandeTotal($data['id_commande'] ?? 0);
                    sendJsonResponse($result);
                    break;

                default:
                    sendJsonResponse(['success' => false, 'message' => 'Action non reconnue']);
            }
            break;

        default:
            // Page d'accueil ou interface principale
            if (empty($entity) && empty($action)) {
                // Afficher l'interface utilisateur
                include 'views/index.html';
            } else {
                sendJsonResponse(['success' => false, 'message' => 'Entité non reconnue']);
            }
    }

} catch (Exception $e) {
    sendJsonResponse([
        'success' => false,
        'message' => 'Erreur serveur: ' . $e->getMessage()
    ]);
}