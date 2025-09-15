<?php

include_once("../modele/utilisateur.php");

class UtilisateurController {
    private $requeteUtilisateur;

    public function __construct() {
        $this->requeteUtilisateur = new RequeteUtilisateur();
    }

    /**
     * Créer un nouvel utilisateur
     */
    public function createUtilisateur(
        string $nom,
        string $prenom,
        string $email,
        string $password,
        string $telephone,
        string $role,
        string $description = '',
        $photoFile = null,
        string $specialite = '',
        $id_formation = '2'
    ): array {
        try {
            // Debug logging
            error_log("DEBUG createUtilisateur called with: nom='$nom', prenom='$prenom', email='$email', password='$password', role='$role', telephone='$telephone'");
            
            // Validation des données
            $validationErrors = $this->validateUtilisateurData($nom, $prenom, $email, $password, $telephone, $role);
            if (!empty($validationErrors)) {
                error_log("DEBUG Validation errors: " . print_r($validationErrors, true));
                return ['success' => false, 'message' => implode(', ', $validationErrors), 'errors' => $validationErrors];
            }

            // Vérifier si l'email existe déjà (temporairement désactivé pour debug)
            // $existingUser = $this->requeteUtilisateur->getUtilisateurByEmail($email);
            // if ($existingUser) {
            //     error_log("DEBUG Email already exists: $email");
            //     return ['success' => false, 'message' => 'Cet email est déjà utilisé'];
            // }

            $photoPath = null;

            // Gestion des fichiers
            if ($photoFile && is_array($photoFile)) {
                // Upload réel via $_FILES
                $photoPath = $this->handleFileUpload($photoFile, 'utilisateurs');
            } elseif ($photoFile && is_string($photoFile)) {
                // Chemin existant déjà sur le serveur
                $photoPath = $photoFile;
            }

            $date_creation = date('Y-m-d H:i:s');
            $utilisateur = new Utilisateur(
                null,
                $nom,
                $prenom,
                $email,
                $password,
                $telephone,
                $role,
                $description,
                $date_creation,
                $photoPath,
                $specialite,
                $id_formation
            );

            error_log("DEBUG About to call ajouterUtilisateur");
            $insertResult = $this->requeteUtilisateur->ajouterUtilisateur($utilisateur);
            error_log("DEBUG ajouterUtilisateur returned: " . ($insertResult ? 'true' : 'false'));
            
            if ($insertResult) {
                $newUser = $this->requeteUtilisateur->getUtilisateurByEmail($email);
                error_log("DEBUG User created successfully, retrieved: " . ($newUser ? 'found' : 'not found'));
                return [
                    'success' => true,
                    'message' => 'Utilisateur créé avec succès',
                    'data' => $newUser ?? $utilisateur
                ];
            } else {
                error_log("DEBUG Failed to insert user into database");
                return [
                    'success' => false,
                    'message' => 'Erreur lors de la création de l\'utilisateur'
                ];
            }
        } catch (Exception $e) {
            error_log("DEBUG Exception in createUtilisateur: " . $e->getMessage());
            error_log("DEBUG Exception trace: " . $e->getTraceAsString());
            return [
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ];
        }
    }
    public function getUtilisateursByRole(string $role): array {
        try {
            $utilisateurs = $this->requeteUtilisateur->getUtilisateursByRole($role);
            foreach ($utilisateurs as $u) $u->setMotDePasse('');
            return ['success' => true, 'data' => $utilisateurs, 'count' => count($utilisateurs)];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Erreur: ' . $e->getMessage()];
        }
    }

    /**
     * Authentifier un utilisateur
     */
    public function authenticateUser(string $email, string $password): array {
        try {
            $utilisateur = $this->requeteUtilisateur->getUtilisateurByEmail($email);
            
            if (!$utilisateur) {
                return ['success' => false, 'message' => 'Email ou mot de passe incorrect'];
            }
            
            if (password_verify($password, $utilisateur->getMotDePasse())) {
                // Connexion réussie - ne pas retourner le mot de passe
                $utilisateur->setMotDePasse('');
                return [
                    'success' => true, 
                    'message' => 'Connexion réussie',
                    'data' => $utilisateur
                ];
            } else {
                return ['success' => false, 'message' => 'Email ou mot de passe incorrect'];
            }
            
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Erreur: ' . $e->getMessage()];
        }
    }

    /**
     * Gérer le téléchargement de fichier
     */
    private function handleFileUpload($file, $category): string {
        $uploadDir = __DIR__ . '/../uploads/' . $category . '/';
        if (!file_exists($uploadDir)) mkdir($uploadDir, 0755, true);

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $maxSize = 5 * 1024 * 1024; // 5MB

        if (!in_array($file['type'], $allowedTypes)) {
            throw new Exception('Type de fichier non autorisé');
        }

        if ($file['size'] > $maxSize) {
            throw new Exception('Fichier trop volumineux (max 5MB)');
        }

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '_' . time() . '.' . $extension;
        $filepath = $uploadDir . $filename;

        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            return 'uploads/' . $category . '/' . $filename;
        }

        throw new Exception('Erreur lors du téléchargement du fichier');
    }

    // ==================== Fonctions de récupération et mise à jour ====================

    public function getUtilisateur(int $id): array {
        try {
            $utilisateur = $this->requeteUtilisateur->getUtilisateurById($id);
            if ($utilisateur) {
                $utilisateur->setMotDePasse('');
                return ['success' => true, 'data' => $utilisateur];
            }
            return ['success' => false, 'message' => 'Utilisateur non trouvé'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Erreur: ' . $e->getMessage()];
        }
    }

    public function getUtilisateurByEmail(string $email): array {
        try {
            $utilisateur = $this->requeteUtilisateur->getUtilisateurByEmail($email);
            if ($utilisateur) {
                //$utilisateur->setMotDePasse('');
                return ['success' => true, 'data' => $utilisateur];
            }
            return ['success' => false, 'message' => 'Utilisateur non trouvé'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Erreur: ' . $e->getMessage()];
        }
    }

    public function getAllUtilisateurs(): array {
        try {
            $utilisateurs = $this->requeteUtilisateur->getAllUtilisateurs();
            foreach ($utilisateurs as $u) {
                $u->setMotDePasse(''); // Masquer le mot de passe
            }
            return ['success' => true, 'data' => $utilisateurs, 'count' => count($utilisateurs)];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Erreur: ' . $e->getMessage()];
        }
    }

    public function updateUtilisateur(
        int $id,
        string $nom,
        string $prenom,
        string $email,
        string $telephone,
        string $role,
        string $photo = null,
        string $bio = null,
        string $specialite = null,
        $id_formation = null
    ): array {
        try {
            $existingUtilisateur = $this->requeteUtilisateur->getUtilisateurById($id);
            if (!$existingUtilisateur) return ['success' => false, 'message' => 'Utilisateur non trouvé'];

            $validationErrors = $this->validateUpdateData($nom, $prenom, $email, $telephone, $role);
            if (!empty($validationErrors)) return ['success' => false, 'message' => 'Données invalides', 'errors' => $validationErrors];

            if ($email !== $existingUtilisateur->getEmail()) {
                $userWithEmail = $this->requeteUtilisateur->getUtilisateurByEmail($email);
                if ($userWithEmail && $userWithEmail->getId() !== $id) {
                    return ['success' => false, 'message' => 'Cet email est déjà utilisé par un autre utilisateur'];
                }
            }

            $existingUtilisateur->setNom($nom);
            $existingUtilisateur->setPrenom($prenom);
            $existingUtilisateur->setEmail($email);
            $existingUtilisateur->setTelephone($telephone);
            $existingUtilisateur->setRole($role);
            if ($photo !== null) $existingUtilisateur->setPhoto($photo);
            if ($bio !== null) $existingUtilisateur->setBio($bio);
            if ($specialite !== null) $existingUtilisateur->setSpecialite($specialite);
            if ($id_formation !== null) $existingUtilisateur->setIdFormation($id_formation);

            if ($this->requeteUtilisateur->mettreAJourUtilisateur($existingUtilisateur)) {
                $existingUtilisateur->setMotDePasse('');
                return ['success' => true, 'message' => 'Utilisateur mis à jour avec succès', 'data' => $existingUtilisateur];
            }

            return ['success' => false, 'message' => 'Erreur lors de la mise à jour de l\'utilisateur'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Erreur: ' . $e->getMessage()];
        }
    }

    // ==================== Validation ====================

    private function validateUtilisateurData(string $nom, string $prenom, string $email, string $password, string $telephone, string $role): array {
        $errors = [];
        if (empty(trim($nom))) $errors[] = 'Le nom est requis';
        if (empty(trim($prenom))) $errors[] = 'Le prénom est requis';
        if (empty(trim($email))) $errors[] = 'L\'email est requis';
        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Format d\'email invalide';
        $errors = array_merge($errors, $this->validatePassword($password));
        // Validation téléphone plus flexible
        if (!empty($telephone) && strlen(trim($telephone)) < 8) $errors[] = 'Le téléphone doit contenir au moins 8 caractères';
        $errors = array_merge($errors, $this->validateRole($role));
        return $errors;
    }

    private function validateUpdateData(string $nom, string $prenom, string $email, string $telephone, string $role): array {
        $errors = [];
        if (empty(trim($nom))) $errors[] = 'Le nom est requis';
        if (empty(trim($prenom))) $errors[] = 'Le prénom est requis';
        if (empty(trim($email))) $errors[] = 'L\'email est requis';
        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Format d\'email invalide';
        // Validation téléphone plus flexible
        if (!empty($telephone) && strlen(trim($telephone)) < 8) $errors[] = 'Le téléphone doit contenir au moins 8 caractères';
        $errors = array_merge($errors, $this->validateRole($role));
        return $errors;
    }

    private function validatePassword(string $password): array {
        $errors = [];
        if (empty($password)) {
            $errors[] = 'Le mot de passe est requis';
            return $errors;
        }
        if (strlen($password) < 6) $errors[] = 'Le mot de passe doit contenir au moins 6 caractères';
        return $errors;
    }

    private function validateRole(string $role): array {
        $errors = [];
        $validRoles = ['admin', 'formateur', 'etudiant', 'client'];
        if (!in_array($role, $validRoles)) $errors[] = 'Rôle invalide. Les rôles valides sont: ' . implode(', ', $validRoles);
        return $errors;
    }

public function authenticate($email, $mot_de_passe) {
        $utilisateur = new RequeteUtilisateur();
        $authentifiedUser = $utilisateur->authentifier($email, $mot_de_passe);
        if ($authentifiedUser) {
            $authentifiedUser->setMotDePasse(''); // Ne pas exposer le mot de passe
            return ['success' => true, 'data' => $authentifiedUser];
        }
    }
}

?>
