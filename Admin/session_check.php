<?php
// session_check.php - Vérification de session pour les pages admin

// Démarrer la session seulement si elle n'est pas déjà active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Fonction pour vérifier si l'utilisateur est connecté
function checkAdminSession() {
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
        // Rediriger vers la page de connexion si pas connecté
        header('Location: ../vue/connexion.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
        exit();
    }
    
    // Vérifier si l'utilisateur a les droits admin (optionnel - peut être retiré si tous les utilisateurs peuvent accéder à l'admin)
    if ($_SESSION['user_role'] !== 'admin' && $_SESSION['user_role'] !== 'formateur') {
        // Rediriger vers une page d'erreur ou la page d'accueil
        header('Location: ../vue/produits.php?error=access_denied');
        exit();
    }
}

// Fonction pour obtenir les informations de l'utilisateur connecté
function getCurrentUser() {
    return [
        'id' => $_SESSION['user_id'] ?? null,
        'nom' => $_SESSION['user_nom'] ?? '',
        'prenom' => $_SESSION['user_prenom'] ?? '',
        'email' => $_SESSION['user_email'] ?? '',
        'role' => $_SESSION['user_role'] ?? ''
    ];
}

// Fonction pour déconnecter l'utilisateur
function logout() {
    // Détruire toutes les variables de session
    $_SESSION = array();
    
    // Détruire le cookie de session si il existe
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    
    // Détruire la session
    session_destroy();
    
    // Supprimer le cookie "remember me" si il existe
    if (isset($_COOKIE['remember_token'])) {
        setcookie('remember_token', '', time() - 3600, '/');
    }
    
    // Rediriger vers la page de connexion
    header('Location: ../vue/connexion.php?message=logout_success');
    exit();
}

// Vérifier la session automatiquement
checkAdminSession();
?>
