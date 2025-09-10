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
        string $mot_de_passe,
        string $telephone,
        string $role,
        string $description = '',
        $photoFile = null,
        string $specialite = '',
        int $id_formation
    ): array {
        try {
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
                $mot_de_passe,
                $telephone,
                $role,
                $description,
                $date_creation,
                $photoPath,
                $specialite,
                $id_formation
            );

            if ($this->requeteUtilisateur->ajouterUtilisateur($utilisateur)) {
                $newUser = $this->requeteUtilisateur->getUtilisateurByEmail($email);
                return [
                    'success' => true,
                    'message' => 'Utilisateur créé avec succès',
                    'data' => $newUser ?? $utilisateur
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Erreur lors de la création de l\'utilisateur'
                ];
            }
        } catch (Exception $e) {
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
            foreach ($utilisateurs as $u) //$u->setMotDePasse('');
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

    private function validateUtilisateurData(string $nom, string $prenom, string $email, string $mot_de_passe, string $telephone, string $role): array {
        $errors = [];
        if (empty(trim($nom))) $errors[] = 'Le nom est requis';
        if (empty(trim($prenom))) $errors[] = 'Le prénom est requis';
        if (empty(trim($email))) $errors[] = 'L\'email est requis';
        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Format d\'email invalide';
        $errors = array_merge($errors, $this->validatePassword($mot_de_passe));
        if (!empty($telephone) && !preg_match('/^[0-9+\s()-]{10,20}$/', $telephone)) $errors[] = 'Format de téléphone invalide';
        $errors = array_merge($errors, $this->validateRole($role));
        return $errors;
    }

    private function validateUpdateData(string $nom, string $prenom, string $email, string $telephone, string $role): array {
        $errors = [];
        if (empty(trim($nom))) $errors[] = 'Le nom est requis';
        if (empty(trim($prenom))) $errors[] = 'Le prénom est requis';
        if (empty(trim($email))) $errors[] = 'L\'email est requis';
        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Format d\'email invalide';
        if (!empty($telephone) && !preg_match('/^[0-9+\s()-]{10,20}$/', $telephone)) $errors[] = 'Format de téléphone invalide';
        $errors = array_merge($errors, $this->validateRole($role));
        return $errors;
    }

    private function validatePassword(string $password): array {
        $errors = [];
        if (strlen($password) < 8) $errors[] = 'Le mot de passe doit contenir au moins 8 caractères';
        if (!preg_match('/[A-Z]/', $password)) $errors[] = 'Le mot de passe doit contenir au moins une majuscule';
        if (!preg_match('/[a-z]/', $password)) $errors[] = 'Le mot de passe doit contenir au moins une minuscule';
        if (!preg_match('/[0-9]/', $password)) $errors[] = 'Le mot de passe doit contenir au moins un chiffre';
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
