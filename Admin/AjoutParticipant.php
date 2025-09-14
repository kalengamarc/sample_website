<!DOCTYPE html>
<html lang="fr">
<head>
    <?php
    include_once '../controle/controleur_formation.php';
    include_once '../controle/controleur_utilisateur.php';
    $formation = new FormationController();
    $listeFormation = $formation->getAllFormations();
    $id = isset($_GET['resp']) ? $_GET['resp'] : null;
    $id = (int) $id;
    $participantController = new UtilisateurController();
    $participant = $participantController->getUtilisateur($id);
    
    ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Participant</title>
    <link rel="stylesheet" href="../vue/font-awesome/css/all.min.css">
    <link rel="stylesheet" href="../vue/styles/stylemenu.css">
    <style>
        .header_dash {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        
        .cont_dash {
            width: 100%;
            height: 100vh;
            background-color: #00110a;
            border-radius: 5px;
        }
        
        .ail_fle {
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: space-between;
        }
        
        .dashcontainu {
            width: 80%;
            height: 94%;
            background-color: #b9c1be;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .form_container {
            width: 100%;
            height: 100%;
            margin: 20px;
            padding: 20px;
            background-color: #b9c1be;
            color: black;
            overflow-y: auto;
        }
        
        .form_container::-webkit-scrollbar {
            display: none;
        }
        
        .form_title {
            color: #021a12;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .form_card {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 10px;
            padding: 30px;
            margin-bottom: 0px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .form_group {
            margin-bottom: 20px;
        }
        
        .form_group label {
            display: block;
            margin-bottom: 8px;
            color: #021a12;
            font-weight: 500;
            font-size: 14px;
        }
        
        .form_group input,
        .form_group textarea,
        .form_group select {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #021a12;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.9);
            color: #021a12;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        
        .form_group input:focus,
        .form_group textarea:focus,
        .form_group select:focus {
            outline: none;
            border-color: #ffae2b;
            box-shadow: 0 0 0 3px rgba(255, 174, 43, 0.2);
        }
        
        .form_row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        .image_upload {
            text-align: center;
            margin: 20px 0;
        }
        
        .image_preview {
            width: 150px;
            height: 150px;
            border: 2px dashed #021a12;
            border-radius: 50%;
            margin: 0 auto 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background: rgba(255, 255, 255, 0.8);
        }
        
        .image_preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: none;
            border-radius: 50%;
        }
        
        .default_text {
            color: #666;
            font-style: italic;
            text-align: center;
        }
        
        .upload_btn {
            background: #021a12;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            margin: 5px;
            transition: all 0.3s ease;
        }
        
        .upload_btn:hover {
            background: #ffae2b;
            color: #021a12;
        }
        
        .remove_btn {
            background: #dc3545;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            margin: 5px;
            transition: all 0.3s ease;
        }
        
        .remove_btn:hover {
            background: #c82333;
        }
        
        .submit_btn {
            background: #ffae2b;
            color: #021a12;
            padding: 15px 30px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: all 0.3s ease;
            margin-top: 20px;
        }
        
        .submit_btn:hover {
            background: #021a12;
            color: #ffae2b;
            transform: translateY(-2px);
        }
        
        .submit_btn:disabled {
            background: #ccc;
            cursor: not-allowed;
        }
        
        .upload_info {
            color: #666;
            font-size: 12px;
            margin-top: 10px;
        }
        
        .required {
            color: #dc3545;
        }
        
        .error_message {
            color: #dc3545;
            font-size: 12px;
            margin-top: 5px;
            display: none;
        }
        
        .success_message {
            background: #28a745;
            color: white;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            display: none;
        }
        
        .password_toggle {
            position: relative;
        }
        
        .password_toggle input {
            padding-right: 40px;
        }
        
        .toggle_icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #021a12;
        }
        
        /* Menu styles */
        .gauche {
            width: 20%;
            background: #021a12;
            color: white;
            height: 94%;
        }
        
        .gauche ul {
            list-style: none;
            padding-left: 0;
            margin: 0;
            margin-left: 40px;
        }
        
        .gauche ul li:nth-child(1) {
            padding-top: 10px;
        }
        
        .gauche li {
            margin: 10px 0;
            display: flex;
            flex-direction: column;
        }
        
        .menu input[type="radio"] {
            display: none;
        }
        
        .gauche li label {
            display: flex;
            align-items: center;
            cursor: pointer;
            padding: 8px 10px;
            font-weight: 400;
            font-size: 17px;
            color: white;
        }
        
        .gauche li label i {
            margin-right: 10px;
            color: white;
        }
        
        .gauche li label:hover {
            background-color: #16A34A;
            border-radius: 5px;
            color: white;
        }
        
        .submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
            list-style: none;
            padding-left: 20px;
            margin-top: 5px;
        }
        
        .submenu li a {
            text-decoration: none;
            color: #b9c1be;
            font-size: 17px;
            padding: 3px 0;
            display: block;
        }
        
        .submenu li a:hover {
            color: #16A34A;
        }
        
        .menu input:checked + label + .submenu {
            max-height: 200px;
        }
        
        /* Styles pour les messages de réponse */
        .btn-success-soft {
            background-color: rgba(40, 167, 69, 0.2);
            color: #28a745;
            border: 1px solid #28a745;
        }
        
        .btn-danger-soft {
            background-color: rgba(220, 53, 69, 0.2);
            color: #dc3545;
            border: 1px solid #dc3545;
        }
        
        .btn-warning-soft {
            background-color: rgba(255, 193, 7, 0.2);
            color: #ffc107;
            border: 1px solid #ffc107;
        }
        
        @media (max-width: 768px) {
            .form_row {
                grid-template-columns: 1fr;
            }
            
            .form_card {
                padding: 20px;
            }
            
            .image_preview {
                width: 120px;
                height: 120px;
            }
        }
    </style>
</head>
<body>
    <div class="header_dash">
        <div class="cont_dash">
    
            <div class="ail_fle">
                <?php include_once('menu.php'); ?>
                <div class="dashcontainu">
                                    <div class="form_container">
                        <!-- Affichage des messages de réponse -->
                        <?php
// Vérifier si on est en mode modification
$isEdit = isset($_GET['resp']) && !empty($_GET['resp']) && is_numeric($_GET['resp']);
$participant = null;

if ($isEdit) {
    include_once('../controle/controleur_utilisateur.php');
    $utilisateurCtrl = new UtilisateurController();
    $participantData = $utilisateurCtrl->getUtilisateur($_GET['resp']);
    
    if ($participantData['success']) {
        $participant = $participantData['data'];
    } else {
        // Rediriger si le participant n'existe pas
        header('location: liste_participant.php');
        exit;
    }
}
?>
                        <h2 class="form_title"><?= $isEdit ? '✏️ Modifier le Participant' : '👨‍🎓 Ajouter un Nouveau Participant' ?></h2>
                        
                        <?php if(isset($_GET['resp']) && !empty($_GET['resp']) && !is_numeric($_GET['resp'])) : ?>
                            <div class="col-md-12 mb-4">
                                <?php  
                                switch($_GET['resp']) {
                                    case 10: 
                                        echo "<span class='btn btn-danger-soft col-md-12'>Veuillez renseigner tous les champs obligatoires</span>";
                                        break;
                                    case 200: 
                                        echo "<span class='btn btn-success-soft col-md-12'>Le participant a été ajouté avec succès</span>";
                                        break;
                                    case 300: 
                                        echo "<span class='btn btn-warning-soft col-md-12'>Une erreur est survenue lors de l'ajout du participant</span>";
                                        break;
                                    case 400: 
                                        echo "<span class='btn btn-danger-soft col-md-12'>L'email est déjà utilisé par un autre utilisateur</span>";
                                        break;
                                    case 401: 
                                        echo "<span class='btn btn-danger-soft col-md-12'>Les mots de passe ne correspondent pas</span>";
                                        break;
                                    case 402: 
                                        echo "<span class='btn btn-danger-soft col-md-12'>Le mot de passe doit contenir au moins 6 caractères</span>";
                                        break;
                                    case 403: 
                                        echo "<span class='btn btn-danger-soft col-md-12'>Format de fichier non autorisé</span>";
                                        break;
                                    case 404: 
                                        echo "<span class='btn btn-danger-soft col-md-12'>Fichier trop volumineux (max 5MB)</span>";
                                        break;
                                    default: 
                                        echo "<span class='btn btn-warning-soft col-md-12'>Réponse inconnue du serveur</span>";
                                }
                                ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="form_card">
                            <form method="POST" action="../controle/index.php" enctype="multipart/form-data" id="formateurForm">
                                <div class="form_row">
                                    <div class="form_group">
                                        <label for="nom">Nom <span class="required">*</span></label>
                                        <input type="text" id="nom" name="nom" value="<?= $participant ? $participant->getNom() : ($_POST['nom'] ?? '') ?>" required>
                                        <div class="error_message" id="nomError"></div>
                                    </div>
                                    
                                    <div class="form_group">
                                        <label for="prenom">Prénom <span class="required">*</span></label>
                                        <input type="text" id="prenom" name="prenom" value="<?= $participant ? $participant->getPrenom() : ($_POST['prenom'] ?? '') ?>" required>
                                        <div class="error_message" id="prenomError"></div>
                                    </div>
                                </div>
                                
                                <div class="form_row">
                                    <div class="form_group">
                                        <label for="email">Email <span class="required">*</span></label>
                                        <input type="email" id="email" name="email" value="<?= $participant ? $participant->getEmail() : ($_POST['email'] ?? '') ?>" required>
                                        <div class="error_message" id="emailError"></div>
                                    </div>
                                    
                                    <div class="form_group">
                                        <label for="telephone">Téléphone</label>
                                        <input type="tel" id="telephone" name="telephone" value="<?= $participant ? $participant->getTelephone() : ($_POST['telephone'] ?? '') ?>">
                                        <div class="error_message" id="telephoneError"></div>
                                    </div>
                                </div>
                                
                                <?php if (!$isEdit): ?>
                                <div class="form_row">
                                    <div class="form_group password_toggle">
                                        <label for="password">Mot de passe <span class="required">*</span></label>
                                        <input type="password" id="password" name="password" value="" required>
                                        <span class="toggle_icon" onclick="togglePassword('password')">
                                            <i class="fas fa-eye"></i>
                                        </span>
                                        <div class="error_message" id="passwordError"></div>
                                    </div>
                                    
                                    <div class="form_group password_toggle">
                                        <label for="confirm_password">Confirmer le mot de passe <span class="required">*</span></label>
                                        <input type="password" id="confirm_password" name="confirm_password" value="" required>
                                        <span class="toggle_icon" onclick="togglePassword('confirm_password')">
                                            <i class="fas fa-eye"></i>
                                        </span>
                                        <div class="error_message" id="confirm_passwordError"></div>
                                    </div>
                                </div>
                                <?php endif; ?>
                                
                                <div class="form_group">
                                    <label for="specialite">Domaine <span class="required">*</span></label>
                                    <input type="text" id="specialite" name="specialite" value="<?= $participant ? $participant->getSpecialite() : ($_POST['specialite'] ?? '') ?>" required>
                                    <div class="error_message" id="specialiteError"></div>
                                </div>
                                <div class="form_group">
                                    <label for="specialite">Formation <span class="required">*</span></label>
                                    <select name="id_formation" id="specialite">
                                        <?php foreach($listeFormation['data'] as $list):?>
                                        <option value="<?=$list->getIdFormation()?>"><?=$list->getTitre()?></option>
                                        <?php endforeach;?>
                                    </select>
                                    <div class="error_message" id="specialiteError"></div>
                                </div>
                                
                                <div class="form_group">
                                    <label for="bio">Biographie</label>
                                    <textarea id="bio" name="bio" rows="4" placeholder="Description du parcours et des compétences du participant..."><?= $participant ? $participant->getBio() : ($_POST['bio'] ?? '') ?></textarea>
                                    <div class="error_message" id="bioError"></div>
                                </div>
                                
                                <div class="image_upload">
                                    <label>Photo de profil</label>
                                    <div class="image_preview" id="imagePreview">
                                        <?php if ($participant && $participant->getPhoto()): ?>
                                            <img src="../controle/<?= $participant->getPhoto() ?>" alt="Photo actuelle" id="previewImage" style="display: block;">
                                            <span class="default-text" id="defaultText" style="display: none;">Aucune photo</span>
                                        <?php else: ?>
                                            <img src="" alt="Aperçu" id="previewImage" style="display: none;">
                                            <span class="default-text" id="defaultText">Aucune photo</span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <input type="file" id="photo" name="photo" accept="image/*" style="display: none;">
                                    <button type="button" class="upload_btn" onclick="document.getElementById('photo').click()">
                                        📷 <?= $participant && $participant->getPhoto() ? 'Changer la photo' : 'Choisir une photo' ?>
                                    </button>
                                    <?php if ($participant && $participant->getPhoto()): ?>
                                        <button type="button" class="remove_btn" onclick="removeImage()" style="display: inline-block;">
                                            🗑️ Supprimer
                                        </button>
                                    <?php else: ?>
                                        <button type="button" class="remove_btn" onclick="removeImage()" style="display: none;">
                                            🗑️ Supprimer
                                        </button>
                                    <?php endif; ?>
                                    
                                    <div class="upload_info">
                                        Formats acceptés: JPG, PNG, GIF, WebP (max 5MB)
                                    </div>
                                </div>
                                
                                <input type="hidden" name="role" value="participant">
                                <input type="hidden" name="do" value="<?= $isEdit ? 'participant_update' : 'participant_create' ?>">
                                <?php if ($isEdit): ?>
                                <input type="hidden" name="id" value="<?= $participant->getId() ?>">
                                <?php endif; ?>
                                
                                <button type="submit" class="submit_btn" id="submitBtn">
                                    <?= $isEdit ? '✏️ Modifier le Participant' : '💾 Enregistrer le Participant' ?>
                                </button>
                            </form>
{{ ... }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle password visibility
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = field.nextElementSibling.querySelector('i');
            
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Gestion de l'aperçu de l'image
        document.getElementById('photo').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('previewImage');
            const defaultText = document.getElementById('defaultText');
            const removeBtn = document.querySelector('.remove_btn');
            
            if (file) {
                // Validation du fichier
                const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                const maxSize = 5 * 1024 * 1024; // 5MB
                
                if (!allowedTypes.includes(file.type)) {
                    alert('Type de fichier non supporté. Veuillez choisir une image JPG, PNG, GIF ou WebP.');
                    this.value = '';
                    return;
                }
                
                if (file.size > maxSize) {
                    alert('Fichier trop volumineux. La taille maximale est de 5MB.');
                    this.value = '';
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    defaultText.style.display = 'none';
                    removeBtn.style.display = 'inline-block';
                };
                reader.readAsDataURL(file);
            }
        });

        function removeImage() {
            const input = document.getElementById('photo');
            const preview = document.getElementById('previewImage');
            const defaultText = document.getElementById('defaultText');
            const removeBtn = document.querySelector('.remove_btn');
            const uploadBtn = document.querySelector('.upload_btn');
            
            input.value = '';
            preview.src = '';
            preview.style.display = 'none';
            defaultText.style.display = 'block';
            removeBtn.style.display = 'none';
            uploadBtn.textContent = '📷 Choisir une photo';
            
            // Ajouter un champ caché pour indiquer la suppression de l'image
            let deletePhotoInput = document.getElementById('delete_photo');
            if (!deletePhotoInput) {
                deletePhotoInput = document.createElement('input');
                deletePhotoInput.type = 'hidden';
                deletePhotoInput.name = 'delete_photo';
                deletePhotoInput.id = 'delete_photo';
                document.getElementById('formateurForm').appendChild(deletePhotoInput);
            }
            deletePhotoInput.value = '1';
        }

        // Validation des mots de passe
        function validatePassword() {
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('confirm_password');
            const errorElement = document.getElementById('confirm_passwordError');
            
            if (password.value !== confirmPassword.value) {
                errorElement.textContent = 'Les mots de passe ne correspondent pas';
                errorElement.style.display = 'block';
                return false;
            }
            
            if (password.value.length < 6) {
                errorElement.textContent = 'Le mot de passe doit contenir au moins 6 caractères';
                errorElement.style.display = 'block';
                return false;
            }
            
            errorElement.style.display = 'none';
            return true;
        }

        // Validation générale du formulaire
        function validateForm() {
            let isValid = true;
            
            // Valider tous les champs requis
            const requiredFields = ['nom', 'prenom', 'email', 'password', 'specialite'];
            requiredFields.forEach(field => {
                const input = document.getElementById(field);
                const errorElement = document.getElementById(field + 'Error');
                
                if (!input.value.trim()) {
                    errorElement.textContent = 'Ce champ est requis';
                    errorElement.style.display = 'block';
                    isValid = false;
                } else {
                    errorElement.style.display = 'none';
                }
            });
            
            // Validation email
            const email = document.getElementById('email');
            const emailError = document.getElementById('emailError');
            if (email.value) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email.value)) {
                    emailError.textContent = 'Format d\'email invalide';
                    emailError.style.display = 'block';
                    isValid = false;
                }
            }
            
            // Validation mot de passe
            if (!validatePassword()) {
                isValid = false;
            }
            
            return isValid;
        }

        // Soumission du formulaire
        document.getElementById('formateurForm').addEventListener('submit', function(e) {
            // Valider le formulaire
            if (!validateForm()) {
                e.preventDefault();
                return;
            }
            
            // Désactiver le bouton de soumission
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Enregistrement en cours...';
        });

        // Validation en temps réel
        document.querySelectorAll('input[required], textarea[required]').forEach(input => {
            input.addEventListener('blur', function() {
                validateField(this);
            });
        });

        function validateField(field) {
            const errorElement = document.getElementById(field.id + 'Error');
            
            if (field.hasAttribute('required') && !field.value.trim()) {
                errorElement.textContent = 'Ce champ est requis';
                errorElement.style.display = 'block';
                return false;
            }
            
            if (field.type === 'email' && field.value) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(field.value)) {
                    errorElement.textContent = 'Format d\'email invalide';
                    errorElement.style.display = 'block';
                    return false;
                }
            }
            
            errorElement.style.display = 'none';
            return true;
        }

        // Vérification de l'email en temps réel
        document.getElementById('email').addEventListener('blur', function() {
            const email = this.value.trim();
            const errorElement = document.getElementById('emailError');
            
            if (!email) return;
            
            // Vérification du format email d'abord
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                return; // Ne pas vérifier si le format est invalide
            }
            
            // Note: La vérification de l'unicité de l'email se fera côté serveur
            // Vous pouvez ajouter une vérification AJAX ici si vous le souhaitez
        });
    </script>
</body>
</html>