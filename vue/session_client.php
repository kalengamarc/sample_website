<?php
// session_client.php - Gestion de session pour les pages client

// Démarrer la session seulement si elle n'est pas déjà active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Fonction pour vérifier si l'utilisateur est connecté
function isUserLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

// Fonction pour obtenir les informations de l'utilisateur connecté
function getCurrentClientUser() {
    if (!isUserLoggedIn()) {
        return null;
    }
    
    return [
        'id' => $_SESSION['user_id'] ?? null,
        'nom' => $_SESSION['user_nom'] ?? '',
        'prenom' => $_SESSION['user_prenom'] ?? '',
        'email' => $_SESSION['user_email'] ?? '',
        'role' => $_SESSION['user_role'] ?? ''
    ];
}

// Fonction pour déconnecter l'utilisateur côté client
function logoutClient() {
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
    header('Location: connexion.php?message=logout_success');
    exit();
}

// Fonction pour afficher le nom d'utilisateur ou "Invité"
function getUserDisplayName() {
    $user = getCurrentClientUser();
    if ($user) {
        return $user['prenom'] . ' ' . $user['nom'];
    }
    return 'Invité';
}

// Fonction pour obtenir le rôle utilisateur formaté
function getUserRole() {
    $user = getCurrentClientUser();
    if ($user && !empty($user['role'])) {
        return ucfirst($user['role']);
    }
    return 'Visiteur';
}
?>
