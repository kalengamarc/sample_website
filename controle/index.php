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
require_once '../controle/controleur_commentaire.php';
require_once '../controle/controleur_favori.php';
require_once '../controle/controleur_panier.php';

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
    error_log("DEBUG handleImageUpload: File info: " . print_r($file, true));
    
    if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
        $errorMsg = 'Aucun fichier uploadé';
        if ($file && $file['error'] !== UPLOAD_ERR_OK) {
            $errorMsg = 'Erreur upload: ' . $file['error'];
        }
        error_log("DEBUG handleImageUpload: " . $errorMsg);
        return ['success' => false, 'message' => $errorMsg];
    }
    
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $maxSize = 5 * 1024 * 1024;
    
    if (!in_array($file['type'], $allowedTypes)) {
        error_log("DEBUG handleImageUpload: Type non autorisé: " . $file['type']);
        return ['success' => false, 'message' => 'Type de fichier non autorisé: ' . $file['type']];
    }
    
    if ($file['size'] > $maxSize) {
        error_log("DEBUG handleImageUpload: Fichier trop volumineux: " . $file['size']);
        return ['success' => false, 'message' => 'Fichier trop volumineux (max 5MB)'];
    }
    
    $uploadDir = __DIR__ . '/uploads/' . $category . '/';
    error_log("DEBUG handleImageUpload: Upload directory: " . $uploadDir);
    
    if (!file_exists($uploadDir)) {
        $created = mkdir($uploadDir, 0755, true);
        error_log("DEBUG handleImageUpload: Directory created: " . ($created ? 'yes' : 'no'));
    }
    
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '_' . time() . '.' . $extension;
    $filepath = $uploadDir . $filename;
    
    error_log("DEBUG handleImageUpload: Attempting to move file to: " . $filepath);
    
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        error_log("DEBUG handleImageUpload: File moved successfully");
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
    $do = $data['do'] ?? $_GET['do'] ?? '';
    // Initialiser les contrôleurs
    $userController = new UtilisateurController();
    $formationController = new FormationController();
    $inscriptionController = new InscriptionController();
    $paiementController = new PaiementController();
    $presenceController = new PresenceController();
    $produitController = new ProduitController();
    $detailCommandeController = new DetailCommandeController();
    $commentaireController = new CommentaireController();
    $favoriController = new FavoriController();
    $panierController = new PanierController();

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
        case 'user_register':
        // Inscription d'un nouvel utilisateur client
        $nom = $data['nom'] ?? '';
        $prenom = $data['prenom'] ?? '';
        $email = $data['email'] ?? '';
        $telephone = $data['telephone'] ?? '';
        $password = $data['password'] ?? '';
        $role = 'client'; // Force le rôle client
        
        if (!empty($nom) && !empty($prenom) && !empty($email) && !empty($password)) {
            // Utiliser la même méthode que pour les formateurs mais avec rôle client
            $result = $userController->createUtilisateur(
                $nom,           // nom
                $prenom,        // prenom  
                $email,         // email
                $password,      // password
                $telephone,     // telephone
                $role,          // role = 'client'
                '',             // bio (vide pour les clients)
                null,           // photo (null pour les clients)
                '',             // specialite (vide pour les clients)
                null            // id_formation (null pour les clients)
            );
            
            if ($result['success']) {
                // Redirection vers la page de connexion avec message de succès
                header('Location: ../vue/connexion.php?message=Inscription réussie ! Vous pouvez maintenant vous connecter.&type=success');
                exit();
            } else {
                // Redirection vers la page d'inscription avec message d'erreur
                header('Location: ../vue/register.php?message=' . urlencode($result['message']) . '&type=error');
                exit();
            }
        } else {
            header('Location: ../vue/register.php?message=Tous les champs obligatoires doivent être remplis&type=error');
            exit();
        }
        break;
        
    case 'user_login':
            $result = $userController->authenticate($data['email'] ?? '',$data['password'] ?? '');
            if ($result['success']) {
                $_SESSION['user_id'] = $result['data']->getId();
                $_SESSION['user_role'] = $result['data']->getRole();
                $_SESSION['user_nom'] = $result['data']->getNom();
                $_SESSION['user_prenom'] = $result['data']->getPrenom();
                
                // Ajouter l'URL de redirection basée sur le rôle
                if ($result['data']->getRole() === 'admin') {
                    $result['redirect_url'] = '../Admin/dashboard.php';
                } else {
                    $result['redirect_url'] = '../vue/produits.php';
                }
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

        case "ajout_commentaire": 
            $result = $CommentaireController->createCommentaire(
                $data['id_utilisateur'] ?? "",
                $data['id_formation'] ?? null,
                $data['id_produit'] ? intval($data['id_produit']) : null,
                $data['commentaire'] ?? "",
                $data['note'] ? intval($data['note']) : null,
                $data['parent_id'] ? intval($data['parent_id']) : null, 
            );
            echo json_encode($result); 
            break;

        case 'user_participant':
                // Debug: Log received data
                error_log("DEBUG user_participant: " . print_r($data, true));
                
                //checkAdminPermission();
                // Gestion de l'upload d'image pour les utilisateurs
                $photoPath = null;
                if (isset($data['photo']) && $data['photo']['error'] === UPLOAD_ERR_OK) {
                    $uploadResult = handleImageUpload($data['photo'], 'utilisateurs');
                    if ($uploadResult['success']) {
                        $photoPath = $uploadResult['filepath'];
                    } else {
                        error_log("Photo upload failed: " . print_r($uploadResult, true));
                        header("Location: ../Admin/AjoutParticipant.php?resp=403");
                        exit;
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
                
                // Debug: Log result
                error_log("DEBUG createUtilisateur participant result: " . print_r($result, true));
                
                if ($result['success']) {
                    header("Location: ../Admin/liste_participant.php?success=1");
                } else {
                    header("Location: ../Admin/AjoutParticipant.php?error=" . urlencode($result['message']));
                }
                exit;
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

        // Gestion des formateurs
        case 'formateur_create':
            // Debug: Log received data
            error_log("DEBUG formateur_create: " . print_r($data, true));
            error_log("DEBUG FILES: " . print_r($_FILES, true));
            
            $photoPath = null;
            if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                error_log("DEBUG: Photo file detected, processing upload...");
                $uploadResult = handleImageUpload($_FILES['photo'], 'utilisateurs');
                error_log("DEBUG: Upload result: " . print_r($uploadResult, true));
                if ($uploadResult['success']) {
                    $photoPath = $uploadResult['filepath'];
                    error_log("DEBUG: Photo path set to: " . $photoPath);
                } else {
                    error_log("Photo upload failed: " . print_r($uploadResult, true));
                    header("Location: ../Admin/AjoutFormateur.php?resp=403&error=" . urlencode($uploadResult['message']));
                    exit;
                }
            } else {
                error_log("DEBUG: No photo uploaded or upload error. FILES photo: " . print_r($_FILES['photo'] ?? 'not set', true));
            }
            
            $result = $userController->createUtilisateur(
                $data['nom'] ?? '',
                $data['prenom'] ?? '',
                $data['email'] ?? '',
                $data['password'] ?? '',
                $data['telephone'] ?? '',
                'formateur',
                $data['bio'] ?? '',
                $photoPath,
                $data['specialite'] ?? '',
                $data['id_formation'] ?? 2
            );
            
            // Debug: Log result
            error_log("DEBUG createUtilisateur result: " . print_r($result, true));
            
            if ($result['success']) {
                header("Location: ../Admin/liste_formateur.php?success=1");
            } else {
                header("Location: ../Admin/AjoutFormateur.php?error=" . urlencode($result['message']));
            }
            exit;
            break;

        case 'formateur_update':
            error_log("DEBUG formateur_update: " . print_r($data, true));
            error_log("DEBUG FILES: " . print_r($_FILES, true));
            
            $existingUser = $userController->getUtilisateur($data['id'] ?? 0);
            if (!$existingUser['success']) {
                error_log("DEBUG: User not found for ID: " . ($data['id'] ?? 'not set'));
                header("Location: ../Admin/AjoutFormateur.php?error=user_not_found");
                exit;
            }
            
            $currentPhoto = $existingUser['data']->getPhoto();
            error_log("DEBUG: Current photo path: " . ($currentPhoto ?? 'null'));
            
            $photoToUpdate = null; // Don't update photo by default
            
            // Check if user wants to delete existing photo
            if (isset($data['delete_photo']) && $data['delete_photo'] == '1') {
                error_log("DEBUG: User requested photo deletion");
                if ($currentPhoto && file_exists(__DIR__ . '/' . $currentPhoto)) {
                    unlink(__DIR__ . '/' . $currentPhoto);
                    error_log("DEBUG: Existing photo deleted: " . $currentPhoto);
                }
                $photoToUpdate = ''; // Set empty string to clear photo in database
            } elseif (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                error_log("DEBUG: New photo uploaded, processing...");
                if ($currentPhoto && file_exists(__DIR__ . '/' . $currentPhoto)) {
                    unlink(__DIR__ . '/' . $currentPhoto);
                    error_log("DEBUG: Old photo deleted: " . $currentPhoto);
                }
                
                $uploadResult = handleImageUpload($_FILES['photo'], 'utilisateurs');
                error_log("DEBUG: Upload result: " . print_r($uploadResult, true));
                if ($uploadResult['success']) {
                    $photoToUpdate = $uploadResult['filepath'];
                    error_log("DEBUG: New photo path: " . $photoToUpdate);
                } else {
                    error_log("DEBUG: Photo upload failed: " . $uploadResult['message']);
                    header("Location: ../Admin/AjoutFormateur.php?resp=" . $data['id'] . "&error=" . urlencode($uploadResult['message']));
                    exit;
                }
            } else {
                error_log("DEBUG: No new photo uploaded. Keeping existing photo unchanged");
            }
            
            $result = $userController->updateUtilisateur(
                $data['id'] ?? 0,
                $data['nom'] ?? '',
                $data['prenom'] ?? '',
                $data['email'] ?? '',
                $data['telephone'] ?? '',
                'formateur',
                $photoToUpdate, // null if no new photo, filepath if new photo
                $data['bio'] ?? '',
                $data['specialite'] ?? '',
                $data['id_formation'] ?? 2
            );
            
            error_log("DEBUG: Update result: " . print_r($result, true));
            
            if ($result['success']) {
                header("Location: ../Admin/liste_formateur.php?success=1");
            } else {
                header("Location: ../Admin/AjoutFormateur.php?resp=" . $data['id'] . "&error=" . urlencode($result['message']));
            }
            exit;
            break;

        case 'formateur_delete':
            $user = $userController->getUtilisateur($data['id'] ?? 0);
            if ($user['success'] && $user['data']->getPhoto()) {
                $photoPath = $user['data']->getPhoto();
                if (file_exists(__DIR__ . '/' . $photoPath)) {
                    unlink(__DIR__ . '/' . $photoPath);
                }
            }
            
            $result = $userController->deleteUtilisateur($data['id'] ?? 0);
            header("Location: ../Admin/liste_formateur.php");
            exit;
            break;

        // Gestion des participants
        case 'participant_create':
            $photoPath = null;
            if (isset($data['photo']) && $data['photo']['error'] === UPLOAD_ERR_OK) {
                $uploadResult = handleImageUpload($data['photo'], 'utilisateurs');
                if ($uploadResult['success']) {
                    $photoPath = $uploadResult['filepath'];
                } else {
                    header("Location: ../Admin/AjoutParticipant.php?resp=403");
                    exit;
                }
            }
            
            $result = $userController->createUtilisateur(
                $data['nom'] ?? '',
                $data['prenom'] ?? '',
                $data['email'] ?? '',
                $data['password'] ?? '',
                $data['telephone'] ?? '',
                'etudiant',
                $data['bio'] ?? '',
                $photoPath,
                $data['specialite'] ?? '',
                $data['id_formation'] ?? ''
            );
            header("Location: ../Admin/liste_participant.php");
            exit;
            break;

        case 'participant_update':
            $existingUser = $userController->getUtilisateur($data['id'] ?? 0);
            $photoPath = $existingUser['success'] ? $existingUser['data']->getPhoto() : null;
            
            if (isset($data['photo']) && $data['photo']['error'] === UPLOAD_ERR_OK) {
                if ($photoPath && file_exists(__DIR__ . '/' . $photoPath)) {
                    unlink(__DIR__ . '/' . $photoPath);
                }
                
                $uploadResult = handleImageUpload($data['photo'], 'utilisateurs');
                if ($uploadResult['success']) {
                    $photoPath = $uploadResult['filepath'];
                } else {
                    header("Location: ../Admin/AjoutParticipant.php?resp=" . $data['id'] . "&error=403");
                    exit;
                }
            }
            
            $result = $userController->updateUtilisateur(
                $data['id'] ?? 0,
                $data['nom'] ?? '',
                $data['prenom'] ?? '',
                $data['email'] ?? '',
                $data['telephone'] ?? '',
                'etudiant',
                $photoPath,
                $data['bio'] ?? ''
            );
            header("Location: ../Admin/liste_participant.php");
            exit;
            break;

        case 'participant_delete':
            $user = $userController->getUtilisateur($data['id'] ?? 0);
            if ($user['success'] && $user['data']->getPhoto()) {
                $photoPath = $user['data']->getPhoto();
                if (file_exists(__DIR__ . '/' . $photoPath)) {
                    unlink(__DIR__ . '/' . $photoPath);
                }
            }
            
            $result = $userController->deleteUtilisateur($data['id'] ?? 0);
            header("Location: ../Admin/liste_participant.php");
            exit;
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
            //checkAuthentication();
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
            header("Location: ../Admin/liste_formation.php");
            exit;
            break;

        case 'formation_delete':
            //checkAuthentication();
            // Supprimer la photo de la formation si elle existe
            $formation = $formationController->getFormation($data['id'] ?? 0);
            if ($formation['success'] && $formation['data']->getPhoto()) {
                $photoPath = $formation['data']->getPhoto();
                if (file_exists(__DIR__ . '/' . $photoPath)) {
                    unlink(__DIR__ . '/' . $photoPath);
                }
            }
            
            $result = $formationController->deleteFormation($data['id'] ?? 0);
            header("Location: ../Admin/liste_formation.php");
            exit;
            break;

        case 'formation_search':
            $result = $formationController->searchFormations($data['searchTerm'] ?? '');
            header("Location: ../Admin/liste_formation.php");
            exit;
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
            header("Location: ../Admin/liste_equipement.php");
            exit;
            break;

        case 'produit_updateStock':
            checkAuthentication();
            $result = $produitController->updateStock(
                $data['id'] ?? 0,
                $data['stock'] ?? 0
            );
            header("Location: ../Admin/liste_equipement.php");
            exit;
            break;

        case 'produit_increaseStock':
            checkAuthentication();
            $result = $produitController->increaseStock(
                $data['id'] ?? 0,
                $data['quantity'] ?? 0
            );
            header("Location: ../Admin/liste_equipement.php");
            exit;
            break;

        case 'produit_decreaseStock':
            checkAuthentication();
            $result = $produitController->decreaseStock(
                $data['id'] ?? 0,
                $data['quantity'] ?? 0
            );
            header("Location: ../Admin/liste_equipement.php");
            exit;
            break;

        case 'produit_delete':
            checkAuthentication();
            // Supprimer la photo du produit si elle existe
            $produit = $produitController->getProduit($_GET['id'] ?? 0);
            if ($produit['success'] && $produit['data']->getPhoto()) {
                $photoPath = $produit['data']->getPhoto();
                if (file_exists(__DIR__ . '/' . $photoPath)) {
                    unlink(__DIR__ . '/' . $photoPath);
                }
            }
            
            $result = $produitController->deleteProduit($_GET['id'] ?? 0);
            header("Location: ../Admin/liste_equipement.php");
            exit;
            break;

        case 'produit_getByCategorie':
            $result = $produitController->getProduitsByCategorie($data['categorie'] ?? '');
            sendJsonResponse($result);
            break;

        case 'produit_getLowStock':
            checkAuthentication();
            $result = $produitController->getLowStockProduits($data['threshold'] ?? 10);
            header("Location: ../Admin/liste_equipement.php");
            exit;
            break;

        case 'produit_search':
            $result = $produitController->searchProduits($data['searchTerm'] ?? '');
            header("Location: ../Admin/liste_equipement.php");
            exit;
            break;

        case 'produit_getStats':
            checkAuthentication();
            $result = $produitController->getStats();
            header("Location: ../Admin/liste_equipement.php");
            exit;
            break;

        case 'produit_getCategories':
            $result = $produitController->getCategories();
            header("Location: ../Admin/liste_equipement.php");
            exit;
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

        // Gestion des commentaires
        case 'comment_create':
            checkAuthentication();
            
            // Check if data comes from session (form submission) or direct data
            $commentData = $_SESSION['comment_data'] ?? [];
            if (!empty($commentData)) {
                unset($_SESSION['comment_data']);
                $data = array_merge($data, $commentData);
            }
            
            $result = $commentaireController->createCommentaire(
                $_SESSION['user_id'],
                $data['id_formation'] ?? null,
                $data['id_produit'] ?? null,
                $data['commentaire'] ?? '',
                $data['note'] ?? null,
                $data['parent_id'] ?? null
            );
            
            // If it's a form submission, redirect back with message
            if (!empty($commentData)) {
                if ($result['success']) {
                    $redirectUrl = '../vue/produits.php?message=' . urlencode('Commentaire ajouté avec succès!') . '&type=success';
                } else {
                    $redirectUrl = '../vue/produits.php?message=' . urlencode($result['message']) . '&type=error';
                }
                header('Location: ' . $redirectUrl);
                exit();
            }
            
            sendJsonResponse($result);
            break;

        case 'comment_get':
            $result = $commentaireController->getCommentaire($data['id'] ?? 0);
            sendJsonResponse($result);
            break;

        case 'comment_getByFormation':
            $result = $commentaireController->getCommentairesByFormation($data['id_formation'] ?? 0);
            sendJsonResponse($result);
            break;

        case 'comment_getByProduit':
            $result = $commentaireController->getCommentairesByProduit($data['id_produit'] ?? 0);
            sendJsonResponse($result);
            break;

        case 'comment_update':
            checkAuthentication();
            $result = $commentaireController->updateCommentaire(
                $data['id'] ?? 0,
                $data['commentaire'] ?? '',
                $data['note'] ?? null
            );
            sendJsonResponse($result);
            break;

        case 'comment_delete':
            checkAuthentication();
            $result = $commentaireController->deleteCommentaire($data['id'] ?? 0);
            sendJsonResponse($result);
            break;

        // Gestion des favoris
        case 'add_to_favorites':
            checkAuthentication();
            
            $type = $data['type'] ?? '';
            $id_element = $data['id_element'] ?? 0;
            
            $result = $favoriController->addToFavorites(
                $_SESSION['user_id'],
                $type,
                $id_element
            );
            
            // Si la requête vient d'un appel AJAX, renvoyer la réponse JSON
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                sendJsonResponse($result);
            } else {
                // Sinon, rediriger avec un message
                if ($result['success']) {
                    $message = $type === 'formation' ? 'Formation ajoutée aux favoris !' : 'Produit ajouté aux favoris !';
                    $redirectUrl = $type === 'formation' ? '../vue/formationpratique.php' : '../vue/produits.php';
                    $redirectUrl .= '?message=' . urlencode($message) . '&type=success';
                } else {
                    $redirectUrl = $type === 'formation' ? '../vue/formationpratique.php' : '../vue/produits.php';
                    $redirectUrl .= '?message=' . urlencode($result['message']) . '&type=error';
                }
                header('Location: ' . $redirectUrl);
                exit();
            }
            
            break;

        case 'favori_remove':
            checkAuthentication();
            $result = $favoriController->removeFromFavorites(
                $_SESSION['user_id'],
                $data['type'] ?? '',
                $data['id_element'] ?? 0
            );
            sendJsonResponse($result);
            break;

        case 'favori_getByUser':
            checkAuthentication();
            $result = $favoriController->getUserFavorites($_SESSION['user_id']);
            sendJsonResponse($result);
            break;

        case 'favori_check':
            checkAuthentication();
            $result = $favoriController->isFavorite(
                $_SESSION['user_id'],
                $data['type'] ?? '',
                $data['id_element'] ?? 0
            );
            sendJsonResponse($result);
            break;

        case 'toggle_favorite':
            checkAuthentication();
            
            $type = $data['type'] ?? '';
            $id_element = $data['id_element'] ?? 0;
            
            // Vérifier d'abord si l'élément est déjà dans les favoris
            $checkFavorite = $favoriController->isFavorite(
                $_SESSION['user_id'],
                $type,
                $id_element
            );
            
            if ($checkFavorite['success'] && $checkFavorite['is_favorite']) {
                // Si l'élément est déjà dans les favoris, le retirer
                $result = $favoriController->removeFromFavorites(
                    $_SESSION['user_id'],
                    $type,
                    $id_element
                );
                if ($result['success']) {
                    $result['action'] = 'removed';
                    $result['message'] = $type === 'formation' ? 'Formation retirée des favoris.' : 'Produit retiré des favoris.';
                }
            } else {
                // Sinon, l'ajouter aux favoris
                $result = $favoriController->addToFavorites(
                    $_SESSION['user_id'],
                    $type,
                    $id_element
                );
                if ($result['success']) {
                    $result['action'] = 'added';
                    $result['message'] = $type === 'formation' ? 'Formation ajoutée aux favoris !' : 'Produit ajouté aux favoris !';
                }
            }
            
            // Si la requête vient d'un appel AJAX, renvoyer la réponse JSON
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                sendJsonResponse($result);
            } else {
                // Sinon, rediriger avec un message
                $redirectUrl = $type === 'formation' ? '../vue/services.php' : '../vue/produits.php';
                $messageType = $result['success'] ? 'success' : 'error';
                $redirectUrl .= '?message=' . urlencode($result['message'] ?? 'Une erreur est survenue.') . '&type=' . $messageType;
                header('Location: ' . $redirectUrl);
                exit();
            }
            break;

        // Gestion du panier
        case 'add_to_cart':
            checkAuthentication();
            
            $type = $data['type'] ?? 'produit'; // Par défaut à 'produit' pour la rétrocompatibilité
            $id_element = $data['id_element'] ?? 0;
            $quantite = $data['quantite'] ?? 1;
            
            $result = $panierController->addToCart(
                $_SESSION['user_id'],
                $type,
                $id_element,
                $quantite
            );
            
            // Si la requête vient d'un appel AJAX, renvoyer la réponse JSON
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                sendJsonResponse($result);
            } else {
                // Sinon, rediriger avec un message
                if ($result['success']) {
                    $message = $type === 'formation' ? 'Formation ajoutée au panier !' : 'Produit ajouté au panier !';
                    $redirectUrl = $type === 'formation' ? '../vue/services.php' : '../vue/produits.php';
                    $redirectUrl .= '?message=' . urlencode($message) . '&type=success';
                } else {
                    $redirectUrl = $type === 'formation' ? '../vue/services.php' : '../vue/produits.php';
                    $redirectUrl .= '?message=' . urlencode($result['message'] ?? 'Une erreur est survenue') . '&type=error';
                }
                header('Location: ' . $redirectUrl);
                exit();
            }
            break;
            
        case 'panier_add': // Ancien cas conservé pour la rétrocompatibilité
            checkAuthentication();
            
            // Check if data comes from session (form submission) or direct data
            $panierData = $_SESSION['panier_data'] ?? [];
            if (!empty($panierData)) {
                unset($_SESSION['panier_data']);
                $data = array_merge($data, $panierData);
            }
            
            $result = $panierController->addToCart(
                $_SESSION['user_id'],
                'produit', // Type par défaut pour l'ancienne méthode
                $data['id_produit'] ?? 0,
                $data['quantite'] ?? 1
            );
            
            // If it's a form submission, redirect back with message
            if (!empty($panierData)) {
                if ($result['success']) {
                    $redirectUrl = '../vue/produits.php?message=' . urlencode('Produit ajouté au panier!') . '&type=success';
                } else {
                    $redirectUrl = '../vue/produits.php?message=' . urlencode($result['message']) . '&type=error';
                }
                header('Location: ' . $redirectUrl);
                exit();
            }
            
            sendJsonResponse($result);
            break;

        case 'panier_update':
            checkAuthentication();
            $result = $panierController->updateQuantity(
                $data['id_panier'] ?? 0,
                $data['quantite'] ?? 1
            );
            sendJsonResponse($result);
            break;

        case 'panier_remove':
            checkAuthentication();
            $type = $data['type'] ?? 'produit';
            $id_element = $data['id_element'] ?? 0;
            
            if (empty($id_element)) {
                // Ancienne méthode avec ID panier direct (pour la rétrocompatibilité)
                $result = $panierController->removeFromCart($_SESSION['user_id'], 'produit', $data['id_panier'] ?? 0);
            } else {
                // Nouvelle méthode avec type et ID élément
                $result = $panierController->removeFromCart($_SESSION['user_id'], $type, $id_element);
            }
            
            sendJsonResponse($result);
            break;

        case 'panier_get':
            checkAuthentication();
            $result = $panierController->getCartItems($_SESSION['user_id']);
            sendJsonResponse($result);
            break;

        case 'panier_clear':
            checkAuthentication();
            $result = $panierController->clearCart($_SESSION['user_id']);
            sendJsonResponse($result);
            break;

        case 'panier_count':
            checkAuthentication();
            $result = $panierController->getCartCount($_SESSION['user_id']);
            sendJsonResponse($result);
            break;

        // Gestion du partage
        case 'share_generate':
            $type = $data['type'] ?? ''; // 'produit' ou 'formation'
            $id = $data['id'] ?? 0;
            $platform = $data['platform'] ?? 'general'; // 'facebook', 'twitter', 'whatsapp', 'email', 'general'
            
            $shareData = [
                'success' => true,
                'data' => [
                    'url' => $data['url'] ?? '',
                    'title' => $data['title'] ?? '',
                    'description' => $data['description'] ?? '',
                    'image' => $data['image'] ?? '',
                    'platform' => $platform
                ]
            ];
            
            // Générer les URLs de partage selon la plateforme
            switch ($platform) {
                case 'facebook':
                    $shareData['data']['share_url'] = 'https://www.facebook.com/sharer/sharer.php?u=' . urlencode($shareData['data']['url']);
                    break;
                case 'twitter':
                    $shareData['data']['share_url'] = 'https://twitter.com/intent/tweet?url=' . urlencode($shareData['data']['url']) . '&text=' . urlencode($shareData['data']['title']);
                    break;
                case 'whatsapp':
                    $shareData['data']['share_url'] = 'https://wa.me/?text=' . urlencode($shareData['data']['title'] . ' ' . $shareData['data']['url']);
                    break;
                case 'email':
                    $shareData['data']['share_url'] = 'mailto:?subject=' . urlencode($shareData['data']['title']) . '&body=' . urlencode($shareData['data']['description'] . ' ' . $shareData['data']['url']);
                    break;
                default:
                    $shareData['data']['share_url'] = $shareData['data']['url'];
            }
            
            sendJsonResponse($shareData);
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