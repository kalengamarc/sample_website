<?php
// Affichage des messages d'erreur ou de succès depuis l'URL
$message = '';
$message_type = '';

if (isset($_GET['message'])) {
    $message = $_GET['message'];
    $message_type = $_GET['type'] ?? 'info';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - JosNet</title>
    <link rel="stylesheet" href="font-awesome/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #04221a 0%, #2c5f2d 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .register-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            width: 100%;
            max-width: 500px;
            animation: slideIn 0.6s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .register-header {
            background: linear-gradient(135deg, #04221a 0%, #2c5f2d 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }

        .register-header h1 {
            font-size: 2.5em;
            font-weight: 700;
            margin-bottom: 10px;
            color: #ffae2b;
        }

        .register-header h2 {
            font-size: 1.5em;
            margin-bottom: 10px;
        }

        .register-header p {
            opacity: 0.9;
            font-size: 1em;
        }

        .register-form {
            padding: 40px 30px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
            font-size: 14px;
        }

        .required {
            color: #dc3545;
        }

        .form-group input {
            width: 100%;
            padding: 15px;
            border: 2px solid #e1e5e9;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .form-group input:focus {
            outline: none;
            border-color: #ffae2b;
            background: white;
            box-shadow: 0 0 0 3px rgba(255, 174, 43, 0.1);
        }

        .password-strength {
            margin-top: 5px;
            font-size: 12px;
            color: #666;
        }

        .password-match {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 10px;
            font-size: 14px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .password-match.show {
            opacity: 1;
        }

        .password-match.valid {
            color: #28a745;
        }

        .password-match.invalid {
            color: #dc3545;
        }

        .terms-checkbox {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin: 25px 0;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
        }

        .terms-checkbox input[type="checkbox"] {
            margin: 0;
            width: auto;
        }

        .terms-checkbox label {
            margin: 0;
            font-size: 14px;
            line-height: 1.5;
        }

        .terms-link {
            color: #04221a;
            text-decoration: underline;
            font-weight: 600;
        }

        .register-btn {
            width: 100%;
            background: linear-gradient(135deg, #ffae2b 0%, #ffc107 100%);
            color: #04221a;
            border: none;
            padding: 18px;
            border-radius: 10px;
            font-size: 18px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .register-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(255, 174, 43, 0.4);
        }

        .register-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .login-link {
            text-align: center;
            margin-top: 25px;
            padding-top: 25px;
            border-top: 1px solid #e1e5e9;
        }

        .login-link p {
            color: #666;
            margin-bottom: 15px;
        }

        .login-link a {
            color: #04221a;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .login-link a:hover {
            color: #ffae2b;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #666;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s ease;
        }

        .back-link:hover {
            color: #04221a;
        }

        .message {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-weight: 500;
        }

        .message.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .message.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        @media (max-width: 768px) {
            .register-container {
                margin: 10px;
            }
            
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .register-header {
                padding: 30px 20px;
            }
            
            .register-form {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-header">
            <h1>JOSNET</h1>
            <h2>Créer un compte</h2>
            <p>Inscrivez-vous pour accéder à JosNet</p>
        </div>
        
        <div class="register-form">
            <?php if (!empty($message)): ?>
                <div class="message <?= $message_type ?>">
                    <?= $message ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="../controle/index.php" class="register-form" id="registerForm">
                <div class="form-row">
                    <div class="form-group">
                        <label for="nom">Nom <span class="required">*</span></label>
                        <input type="text" id="nom" name="nom" required value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label for="prenom">Prénom <span class="required">*</span></label>
                        <input type="text" id="prenom" name="prenom" required value="<?= htmlspecialchars($_POST['prenom'] ?? '') ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="email">E-mail <span class="required">*</span></label>
                    <input type="email" id="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label for="telephone">Téléphone</label>
                    <input type="tel" id="telephone" name="telephone" value="<?= htmlspecialchars($_POST['telephone'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label for="password">Mot de passe <span class="required">*</span></label>
                    <input type="password" id="password" name="password" required>
                    <div class="password-strength" id="passwordStrength"></div>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Confirmer le mot de passe <span class="required">*</span></label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                    <div class="password-match" id="passwordMatch">
                        <i class="fas fa-check"></i>
                        <span>Les mots de passe correspondent</span>
                    </div>
                </div>
                
                <div class="terms-checkbox">
                    <input type="checkbox" id="accept_terms" name="accept_terms" required>
                    <label for="accept_terms">
                        J'accepte <a href="#" class="terms-link">les conditions d'utilisation et la politique de confidentialité</a> <span class="required">*</span>
                    </label>
                </div>
                <input type="hidden" name="role" value="client">
                <input type="hidden" name="do" value="user_register">
                
                <button type="submit" class="register-btn" id="submitBtn">
                    Créer mon compte
                </button>
            </form>
            
            <div class="login-link">
                <p>Vous avez déjà un compte ?</p>
                <a href="connexion.php">Se connecter</a>
                <br><br>
                <a href="produits.php" class="back-link">
                    <i class="fas fa-arrow-left"></i>
                    Retour à l'accueil
                </a>
            </div>
        </div>
    </div>

    <script>
        // Validation en temps réel
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('confirm_password');
        const passwordStrength = document.getElementById('passwordStrength');
        const passwordMatch = document.getElementById('passwordMatch');
        const submitBtn = document.getElementById('submitBtn');
        
        // Vérification de la force du mot de passe
        password.addEventListener('input', function() {
            const value = this.value;
            let strength = '';
            let color = '';
            
            if (value.length === 0) {
                strength = '';
            } else if (value.length < 6) {
                strength = 'Trop court (minimum 6 caractères)';
                color = '#dc3545';
            } else if (value.length < 8) {
                strength = 'Faible';
                color = '#ffc107';
            } else if (value.match(/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/)) {
                strength = 'Fort';
                color = '#28a745';
            } else {
                strength = 'Moyen';
                color = '#17a2b8';
            }
            
            passwordStrength.textContent = strength;
            passwordStrength.style.color = color;
            
            checkPasswordMatch();
        });
        
        // Vérification de la correspondance des mots de passe
        confirmPassword.addEventListener('input', checkPasswordMatch);
        
        function checkPasswordMatch() {
            const match = password.value === confirmPassword.value && password.value.length > 0;
            
            if (confirmPassword.value.length > 0) {
                passwordMatch.classList.add('show');
                if (match) {
                    passwordMatch.classList.add('valid');
                    passwordMatch.classList.remove('invalid');
                    passwordMatch.innerHTML = '<i class="fas fa-check"></i><span>Les mots de passe correspondent</span>';
                } else {
                    passwordMatch.classList.add('invalid');
                    passwordMatch.classList.remove('valid');
                    passwordMatch.innerHTML = '<i class="fas fa-times"></i><span>Les mots de passe ne correspondent pas</span>';
                }
            } else {
                passwordMatch.classList.remove('show');
            }
            
            validateForm();
        }
        
        // Validation du formulaire
        function validateForm() {
            const nom = document.getElementById('nom').value.trim();
            const prenom = document.getElementById('prenom').value.trim();
            const email = document.getElementById('email').value.trim();
            const passwordValue = password.value;
            const confirmPasswordValue = confirmPassword.value;
            const acceptTerms = document.getElementById('accept_terms').checked;
            
            const isValid = nom && prenom && email && 
                           passwordValue.length >= 6 && 
                           passwordValue === confirmPasswordValue && 
                           acceptTerms;
            
            submitBtn.disabled = !isValid;
        }
        
        // Validation en temps réel sur tous les champs
        document.querySelectorAll('input').forEach(input => {
            input.addEventListener('input', validateForm);
            input.addEventListener('change', validateForm);
        });
        
        // Validation initiale
        validateForm();
    </script>
</body>
</html>