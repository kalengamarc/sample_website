<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une Formation</title>
    <link rel="stylesheet" href="../vue/font-awesome/css/all.min.css">
    <link rel="stylesheet" href="../vue/styles/stylemenu.css">
    <?php
        include_once('../controle/controleur_utilisateur.php');
        $userController = new UtilisateurController();
        $formateurs = $userController->getUtilisateursByRole('formateur');
    ?>
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
            margin-bottom: 20px;
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
        
        .form_group textarea {
            height: 120px;
            resize: vertical;
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
            width: 200px;
            height: 150px;
            border: 2px dashed #021a12;
            border-radius: 10px;
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
        }
        
        .default_text {
            color: #666;
            font-style: italic;
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
        
        @media (max-width: 768px) {
            .form_row {
                grid-template-columns: 1fr;
            }
            
            .form_card {
                padding: 20px;
            }
            
            .image_preview {
                width: 150px;
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
                        <h2 class="form_title"><?= $isEdit ? '✏️ Modifier la Formation' : '➕ Ajouter une Nouvelle Formation' ?></h2>
                        
                        <!-- Affichage des messages de réponse -->
                        <?php
session_start();
if(!isset($_SESSION['user'])){
    header('location:../vue/connexion.html');
}

// Vérifier si on est en mode modification
$isEdit = isset($_GET['resp']) && !empty($_GET['resp']);
$formation = null;

if ($isEdit) {
    include_once('../controle/controleur_formation.php');
    $formationCtrl = new FormationController();
    $formationData = $formationCtrl->getFormation($_GET['resp']);
    
    if ($formationData['success']) {
        $formation = $formationData['data'];
    } else {
        // Rediriger si la formation n'existe pas
        header('location: liste_formation.php');
        exit;
    }
}
?>
                            <div class="col-md-12 mb-4">
                                <?php  
                                switch($_GET['resp']) {
                                    case 10: 
                                        echo "<span class='btn btn-danger-soft col-md-12'>Veuillez renseigner tous les champs obligatoires</span>";
                                        break;
                                    case 200: 
                                        echo "<span class='btn btn-success-soft col-md-12'>La formation a été ajoutée avec succès</span>";
                                        break;
                                    case 300: 
                                        echo "<span class='btn btn-warning-soft col-md-12'>Une erreur est survenue lors de l'ajout de la formation</span>";
                                        break;
                                    case 400: 
                                        echo "<span class='btn btn-danger-soft col-md-12'>Le formateur sélectionné n'existe pas</span>";
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
                            <form method="POST" action="../controle/index.php" enctype="multipart/form-data" id="formationForm">
                                <div class="form_row">
                                    <div class="form_group">
                                        <label for="titre">Titre de la formation <span class="required">*</span></label>
                                        <input type="text" id="titre" name="titre" value="<?= $formation ? $formation->getTitre() : ($_POST['titre'] ?? '') ?>" required>
                                        <div class="error_message" id="titreError"></div>
                                    </div>
                                    
                                    <div class="form_group">
                                        <label for="prix">Prix (€) <span class="required">*</span></label>
                                        <input type="number" id="prix" name="prix" step="0.01" min="0" value="<?= $formation ? $formation->getPrix() : ($_POST['prix'] ?? '') ?>" required>
                                        <div class="error_message" id="prixError"></div>
                                    </div>
                                </div>
                                
                                <div class="form_row">
                                    <div class="form_group">
                                        <label for="duree">Durée (heures) <span class="required">*</span></label>
                                        <input type="number" id="duree" name="duree" min="1" value="<?= $formation ? $formation->getDuree() : ($_POST['duree'] ?? '') ?>" required>
                                        <div class="error_message" id="dureeError"></div>
                                    </div>
                                    <div class="form_group">
                                        <label for="duree">Debut de la formation <span class="required">*</span></label>
                                        <input type="date" id="debut_formation" name="debut_formation" min="1" value="<?= $formation ? $formation->getDebutFormation() : ($_POST['debut_formation'] ?? '') ?>" required>
                                        <div class="error_message" id="dureeError"></div>
                                    </div>
                                    
                                    <div class="form_group">
                                        <label for="formateur">Formateur <span class="required">*</span></label>
                                        <select id="formateur" name="id_formateur" required>
                                            <option value="">Sélectionner un formateur</option>
                                            <?php
                                            // Charger les formateurs depuis la base de données
                                            // Vous devrez adapter cette partie selon votre architecture
                                            try {
                                                foreach ($formateurs['data'] as $formateur) {
                                                    $selected = (isset($_POST['id_formateur']) && $_POST['id_formateur'] == $formateur->getId()) ? 'selected' : '';
                                                    echo "<option value='{$formateur->getId()}' $selected>{$formateur->getPrenom()} {$formateur->getNom()}</option>";
                                                }
                                               
                                            } catch (Exception $e) {
                                                echo "<option value=''>Erreur de chargement des formateurs</option>";
                                            }
                                            ?>
                                        </select>
                                        <div class="error_message" id="formateurError"></div>
                                    </div>
                                </div>
                                
                                <div class="form_group">
                                    <label for="description">Description <span class="required">*</span></label>
                                    <textarea id="description" name="description" required><?= $formation ? $formation->getDescription() : ($_POST['description'] ?? '') ?></textarea>
                                    <div class="error_message" id="descriptionError"></div>
                                </div>
                                
                                <div class="image_upload">
                                    <label>Image de la formation</label>
                                    <div class="image_preview" id="imagePreview">
                                        <img src="" alt="Aperçu" id="previewImage">
                                        <span class="default-text" id="defaultText">Aucune image sélectionnée</span>
                                    </div>
                                    
                                    <input type="file" id="photo" name="photo" accept="image/*" style="display: none;">
                                    <button type="button" class="upload_btn" onclick="document.getElementById('photo').click()">
                                        📷 Choisir une image
                                    </button>
                                    <button type="button" class="remove_btn" onclick="removeImage()" style="display: none;">
                                        🗑️ Supprimer
                                    </button>
                                    
                                    <div class="upload_info">
                                        Formats acceptés: JPG, PNG, GIF, WebP (max 5MB)
                                    </div>
                                </div>
                                
                                <input type="hidden" name="do" value="<?= $isEdit ? 'formation_update' : 'formation_create' ?>">
                                <?php if ($isEdit): ?>
                                <input type="hidden" name="id" value="<?= $formation->getIdFormation() ?>">
                                <?php endif; ?>
                                
                                <button type="submit" class="submit_btn" id="submitBtn">
                                    <?= $isEdit ? '✏️ Modifier la Formation' : '💾 Enregistrer la Formation' ?>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
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
            
            input.value = '';
            preview.style.display = 'none';
            defaultText.style.display = 'block';
            removeBtn.style.display = 'none';
        }

        // Validation générale du formulaire
        function validateForm() {
            let isValid = true;
            
            // Valider tous les champs requis
            const requiredFields = ['titre', 'prix', 'duree', 'id_formateur', 'description'];
            requiredFields.forEach(field => {
                const input = document.querySelector(`[name="${field}"]`);
                const errorElement = document.getElementById(field + 'Error');
                
                if (!input.value.trim()) {
                    errorElement.textContent = 'Ce champ est requis';
                    errorElement.style.display = 'block';
                    isValid = false;
                } else {
                    errorElement.style.display = 'none';
                }
            });
            
            // Validation des nombres
            const prix = document.getElementById('prix');
            const prixError = document.getElementById('prixError');
            if (prix.value && parseFloat(prix.value) <= 0) {
                prixError.textContent = 'Le prix doit être supérieur à 0';
                prixError.style.display = 'block';
                isValid = false;
            }
            
            const duree = document.getElementById('duree');
            const dureeError = document.getElementById('dureeError');
            if (duree.value && parseInt(duree.value) <= 0) {
                dureeError.textContent = 'La durée doit être supérieure à 0';
                dureeError.style.display = 'block';
                isValid = false;
            }
            
            return isValid;
        }

        // Soumission du formulaire
        document.getElementById('formationForm').addEventListener('submit', function(e) {
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
        document.querySelectorAll('input[required], textarea[required], select[required]').forEach(input => {
            input.addEventListener('blur', function() {
                validateField(this);
            });
        });

        function validateField(field) {
            const fieldName = field.name;
            const errorElement = document.getElementById(fieldName + 'Error');
            
            if (!field.value.trim()) {
                errorElement.textContent = 'Ce champ est requis';
                errorElement.style.display = 'block';
                return false;
            }
            
            if (field.type === 'number' && field.value <= 0) {
                errorElement.textContent = 'La valeur doit être supérieure à 0';
                errorElement.style.display = 'block';
                return false;
            }
            
            errorElement.style.display = 'none';
            return true;
        }

        // Charger les formateurs (vous devrez adapter cette fonction selon votre architecture)
        function loadFormateurs() {
            // Cette fonction devrait charger les formateurs depuis votre base de données
            // Exemple avec une requête AJAX si nécessaire :
            /*
            fetch('../controle/index.php?do=get_formateurs')
                .then(response => response.json())
                .then(data => {
                    const select = document.getElementById('formateur');
                    select.innerHTML = '<option value="">Sélectionner un formateur</option>';
                    data.forEach(formateur => {
                        const option = document.createElement('option');
                        option.value = formateur.id;
                        option.textContent = `${formateur.prenom} ${formateur.nom}`;
                        select.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Erreur chargement formateurs:', error);
                });
            */
        }

        // Charger les formateurs au démarrage
        document.addEventListener('DOMContentLoaded', function() {
            loadFormateurs();
        });
    </script>
</body>
</html>