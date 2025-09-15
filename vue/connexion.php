<?php
session_start();

// Configuration et variables
$errors = [];
$success_message = '';
$email = '';
$remember_me = false;

// Fonction pour sécuriser les données
function sanitize($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

// Fonction pour valider l'email
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Fonction pour vérifier la force du mot de passe
function isValidPassword($password) {
    return strlen($password) >= 6;
}

// Générer un token CSRF
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Gestion de la déconnexion
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// Vérifier si l'utilisateur est déjà connecté
if (isset($_SESSION['user_id'])) {
    $success_message = "Vous êtes déjà connecté en tant que " . sanitize($_SESSION['user_email']);
}

// Traitement du formulaire de connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {
    // Vérification du token CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $errors[] = "Erreur de sécurité. Veuillez réessayer.";
    } else {
        // Récupération et nettoyage des données
        $email = sanitize($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $remember_me = isset($_POST['remember_me']);
        
        // Validation des champs
        if (empty($email)) {
            $errors[] = "L'email est requis.";
        } elseif (!validateEmail($email)) {
            $errors[] = "Format d'email invalide.";
        }
        
        if (empty($password)) {
            $errors[] = "Le mot de passe est requis.";
        } elseif (!isValidPassword($password)) {
            $errors[] = "Le mot de passe doit contenir au moins 6 caractères.";
        }
        
        // Si pas d'erreurs de validation, tentative de connexion
        if (empty($errors)) {
            try {
                // Utiliser le contrôleur d'authentification
                include_once('../controle/controleur_utilisateur.php');
                $authController = new UtilisateurController();
                $authResult = $authController->authenticateUser($email, $password);
                
                if ($authResult['success']) {
                    $user = $authResult['data'];
                    // Connexion réussie
                    $_SESSION['user_id'] = $user->getId(); 
                    $_SESSION['user_email'] = $email;
                    $_SESSION['user_role'] = $user->getRole();
                    $_SESSION['user_nom'] = $user->getNom();
                    $_SESSION['user_prenom'] = $user->getPrenom();
                    $_SESSION['login_time'] = time();
                    
                    // Gestion du "Se souvenir de moi"
                    if ($remember_me) {
                        $token = bin2hex(random_bytes(32));
                        setcookie('remember_token', $token, time() + (86400 * 30), '/', '', false, true); // 30 jours
                        // Dans un vrai projet, stockez ce token en base de données
                    }
                    
                    // Redirection basée sur le rôle de l'utilisateur
                    if (isset($_GET['redirect'])) {
                        $redirect_url = $_GET['redirect'];
                    } elseif ($user->getRole() === 'admin') {
                        $redirect_url = '../Admin/dashboard.php';
                    } else {
                        $redirect_url = 'produits.php';
                    }
                    
                    header('Location: ' . $redirect_url);
                    exit();
                } else {
                    $errors[] = "Email ou mot de passe incorrect.";
                    // Log de la tentative de connexion échouée
                    error_log("Tentative de connexion échouée pour: " . $email . " depuis " . $_SERVER['REMOTE_ADDR']);
                }
                
            } catch (Exception $e) {
                $errors[] = "Erreur système. Veuillez réessayer plus tard.";
                error_log("Erreur de connexion: " . $e->getMessage());
            }
        }
    }
    
    // Régénérer le token CSRF après une tentative
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Vérification du cookie "Se souvenir de moi"
if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_token'])) {
    // Dans un vrai projet, vérifiez le token en base de données
    // et connectez automatiquement l'utilisateur si valide
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - JosNet</title>
    <link rel="stylesheet" href="../vue/font-awesome/css/all.min.css">
    <style>
        :root{
            --shadow: 2px 10px 8px rgba(83, 163, 83, 0.15);
            --color1: #1f5d1f;
            --color2: rgba(44, 122, 44, 0.75);
            --color3: #f4f9f4;
            --neon: #1e90ff;
            --glass: rgba(255, 255, 255, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .page_connexion {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #021a12 0%, #0d3b2a 100%);
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }   

        .connexion-container {
            border-radius: 15px;
            background: linear-gradient(180deg, var(--color1), #0d3b2a);
            backdrop-filter: blur(10px);
            width: 100%;
            max-width: 420px;
            min-height: 500px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3), 0 0 20px rgba(30, 144, 255, 0.1);
            position: relative;
            padding: 30px;
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from {
                transform: translateY(30px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .connexion-container h1 {
            color: var(--neon);
            text-align: center;
            font-size: 32px;
            margin-bottom: 10px;
            text-shadow: 0 0 10px rgba(30, 144, 255, 0.5);
        }

        .connexion-container h2 {
            color: white;
            text-align: center;
            font-size: 18px;
            margin-bottom: 30px;
            opacity: 0.9;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            color: white;
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
        }

        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            background: rgba(255, 255, 255, 0.1);
            color: white;
            font-size: 16px;
            transition: all 0.3s ease;
            backdrop-filter: blur(5px);
        }

        .form-group input::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .form-group input:focus {
            outline: none;
            border: 2px solid var(--neon);
            box-shadow: 0 0 10px rgba(30, 144, 255, 0.3);
            background: rgba(255, 255, 255, 0.15);
        }

        .password-container {
            position: relative;
        }

        .password-container .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: rgba(255, 255, 255, 0.7);
            transition: color 0.3s ease;
        }

        .password-container .toggle-password:hover {
            color: var(--neon);
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .checkbox-group input[type="checkbox"] {
            width: auto;
            margin-right: 10px;
        }

        .checkbox-group label {
            color: rgba(255, 255, 255, 0.8);
            cursor: pointer;
            font-size: 14px;
        }

        .forgot-password {
            text-align: center;
            margin-bottom: 20px;
        }

        .forgot-password a {
            color: var(--neon);
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s ease;
        }

        .forgot-password a:hover {
            color: #4da6ff;
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            background: linear-gradient(45deg, var(--neon), #4da6ff);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(30, 144, 255, 0.4);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .btn-login:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .btn-logout {
            background: linear-gradient(45deg, #e74c3c, #c0392b);
        }

        .register-link {
            text-align: center;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .register-link p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 14px;
        }

        .register-link a {
            color: var(--neon);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .register-link a:hover {
            color: #4da6ff;
        }

        /* Messages d'erreur et de succès */
        .alert {
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            animation: fadeIn 0.3s ease;
        }

        .alert-error {
            background: rgba(231, 76, 60, 0.2);
            border: 1px solid rgba(231, 76, 60, 0.3);
            color: #ff6b6b;
        }

        .alert-success {
            background: rgba(46, 204, 113, 0.2);
            border: 1px solid rgba(46, 204, 113, 0.3);
            color: #2ecc71;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .error-list {
            list-style: none;
            padding: 0;
        }

        .error-list li {
            margin-bottom: 5px;
        }

        /* User info pour utilisateur connecté */
        .user-info {
            text-align: center;
            color: white;
        }

        .user-info h3 {
            color: var(--neon);
            margin-bottom: 15px;
        }

        .user-info .user-email {
            background: rgba(255, 255, 255, 0.1);
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        /* Loading spinner */
        .loading {
            display: none;
        }

        .loading.show {
            display: inline-block;
        }

        .spinner {
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top: 2px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-left: 10px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Responsive */
        @media (max-width: 480px) {
            .connexion-container {
                margin: 10px;
                padding: 20px;
            }
            
            .connexion-container h1 {
                font-size: 28px;
            }
        }

        /* Champ invalide */
        .form-group input.invalid {
            border-color: #e74c3c;
            box-shadow: 0 0 5px rgba(231, 76, 60, 0.3);
        }

        /* Animation de validation */
        .form-group.valid input {
            border-color: #2ecc71;
        }

        .form-group.valid::after {
            content: "✓";
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #2ecc71;
            font-weight: bold;
        }

        .password-container.valid::after {
            right: 45px;
        }
    </style>
</head>
<body>
    <div class="page_connexion">
        <div class="connexion-container">
            <h1>JOSNET</h1>
            
            <?php if (!isset($_SESSION['user_id'])): ?>
                <h2>Connexion à JosNet</h2>
                
                <!-- Affichage des erreurs -->
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-error">
                        <ul class="error-list">
                            <?php foreach ($errors as $error): ?>
                                <li><?= sanitize($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <form method="POST" id="loginForm" novalidate>
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <input type="hidden" name="action" value="login">
                    
                    <p style="color: rgba(255,255,255,0.8); margin-bottom: 20px; font-size: 14px;">
                        Entrez vos identifiants pour accéder à votre compte
                    </p>
                    
                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               placeholder="votre@email.com" 
                               value="<?= sanitize($email) ?>"
                               required 
                               autocomplete="email">
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Mot de passe *</label>
                        <div class="password-container">
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   placeholder="Votre mot de passe" 
                                   required 
                                   autocomplete="current-password">
                            <span class="toggle-password" onclick="togglePassword()">
                                <i class="fa fa-eye" id="eyeIcon"></i>
                            </span>
                        </div>
                        <input type="hidden" name="do" value="user_login">
                    </div>
                    
                    <div class="checkbox-group">
                        <input type="checkbox" id="remember_me" name="remember_me" <?= $remember_me ? 'checked' : '' ?>>
                        <label for="remember_me">Se souvenir de moi</label>
                    </div>
                    
                    <div class="forgot-password">
                        <a href="forgot-password.php">Mot de passe oublié ?</a>
                    </div>
                    
                    <button type="submit" class="btn-login" id="loginBtn">
                        Se connecter
                        <span class="loading" id="loadingSpinner">
                            <span class="spinner"></span>
                        </span>
                    </button>
                    
                    <div class="register-link">
                        <p>Pas encore de compte ? <a href="register.php">S'inscrire</a></p>
                    </div>
                </form>
            <?php else: ?>
                <!-- Utilisateur connecté -->
                <div class="user-info">
                    <h3>Bienvenue !</h3>
                    <?php if ($success_message): ?>
                        <div class="alert alert-success">
                            <?= $success_message ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="user-email">
                        <i class="fas fa-user"></i> <?= sanitize($_SESSION['user_email']) ?>
                    </div>
                    
                    <p style="color: rgba(255,255,255,0.7); margin-bottom: 20px;">
                        Connecté depuis <?= date('H:i', $_SESSION['login_time']) ?>
                    </p>
                    
                    <a href="produits.php" class="btn-login" style="display: inline-block; text-decoration: none; margin-bottom: 15px;">
                        Accéder aux produits
                    </a>
                    
                    <form method="GET" style="margin-top: 15px;">
                        <input type="hidden" name="action" value="logout">
                        <button type="submit" class="btn-login btn-logout">
                            <i class="fas fa-sign-out-alt"></i> Se déconnecter
                        </button>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Fonction pour basculer la visibilité du mot de passe
        function togglePassword() {
            const passwordInput = document.getElementById("password");
            const eyeIcon = document.getElementById("eyeIcon");
            
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                eyeIcon.className = "fa fa-eye-slash";
            } else {
                passwordInput.type = "password";
                eyeIcon.className = "fa fa-eye";
            }
        }
        
        // Validation en temps réel
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('loginForm');
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            const loginBtn = document.getElementById('loginBtn');
            const loadingSpinner = document.getElementById('loadingSpinner');
            
            // Validation email en temps réel
            if (emailInput) {
                emailInput.addEventListener('blur', function() {
                    const email = this.value.trim();
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    const formGroup = this.closest('.form-group');
                    
                    if (email && emailRegex.test(email)) {
                        formGroup.classList.add('valid');
                        this.classList.remove('invalid');
                    } else if (email) {
                        formGroup.classList.remove('valid');
                        this.classList.add('invalid');
                    }
                });
                
                emailInput.addEventListener('input', function() {
                    this.classList.remove('invalid');
                    this.closest('.form-group').classList.remove('valid');
                });
            }
            
            // Validation mot de passe en temps réel
            if (passwordInput) {
                passwordInput.addEventListener('input', function() {
                    const password = this.value;
                    const formGroup = this.closest('.form-group');
                    
                    if (password.length >= 6) {
                        formGroup.classList.add('valid');
                        this.classList.remove('invalid');
                    } else {
                        formGroup.classList.remove('valid');
                        if (password.length > 0) {
                            this.classList.add('invalid');
                        }
                    }
                });
            }
            
            // Gestion de la soumission du formulaire
            if (form) {
                form.addEventListener('submit', function(e) {
                    const email = emailInput.value.trim();
                    const password = passwordInput.value;
                    
                    // Validation côté client
                    let hasErrors = false;
                    
                    if (!email) {
                        emailInput.classList.add('invalid');
                        hasErrors = true;
                    }
                    
                    if (!password) {
                        passwordInput.classList.add('invalid');
                        hasErrors = true;
                    }
                    
                    if (hasErrors) {
                        e.preventDefault();
                        return false;
                    }
                    
                    // Afficher le spinner de chargement
                    loginBtn.disabled = true;
                    loadingSpinner.classList.add('show');
                    loginBtn.style.opacity = '0.8';
                });
            }
            
            // Auto-remplissage pour les tests (développement seulement)
            const testButtons = document.querySelectorAll('.test-account');
            testButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const email = this.dataset.email;
                    const password = this.dataset.password;
                    
                    emailInput.value = email;
                    passwordInput.value = password;
                    
                    // Déclencher les validations
                    emailInput.dispatchEvent(new Event('blur'));
                    passwordInput.dispatchEvent(new Event('input'));
                });
            });
        });
        
        // Gestion des raccourcis clavier
        document.addEventListener('keydown', function(e) {
            // Echap pour effacer les champs
            if (e.key === 'Escape') {
                const inputs = document.querySelectorAll('input[type="email"], input[type="password"]');
                inputs.forEach(input => {
                    input.value = '';
                    input.classList.remove('invalid');
                    input.closest('.form-group').classList.remove('valid');
                });
            }
        });
        
        // Masquer les messages d'erreur après 10 secondes
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 10000);
    </script>
</body>
</html>