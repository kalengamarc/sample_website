<?php

include_once("Utilisateur.php");
include_once("RequeteUtilisateur.php");

class UtilisateurController {
    private $requeteUtilisateur;

    public function __construct() {
        $this->requeteUtilisateur = new RequeteUtilisateur();
    }

    /**
     * Créer un nouvel utilisateur
     */
    public function createUtilisateur(string $nom, string $prenom, string $email, string $mot_de_passe, string $telephone, string $role, string $photo = null): array {
        try {
            // Validation des données
            $validationErrors = $this->validateUtilisateurData($nom, $prenom, $email, $mot_de_passe, $telephone, $role);
            
            if (!empty($validationErrors)) {
                return [
                    'success' => false,
                    'message' => 'Données invalides',
                    'errors' => $validationErrors
                ];
            }

            // Vérifier si l'email existe déjà
            if ($this->requeteUtilisateur->getUtilisateurByEmail($email)) {
                return [
                    'success' => false,
                    'message' => 'Cet email est déjà utilisé'
                ];
            }

            $date_creation = date('Y-m-d H:i:s');
            $utilisateur = new Utilisateur(null, $nom, $prenom, $email, $mot_de_passe, $telephone, $role, $date_creation, $photo);
            
            if ($this->requeteUtilisateur->ajouterUtilisateur($utilisateur)) {
                // Récupérer l'utilisateur créé pour avoir l'ID
                $newUser = $this->requeteUtilisateur->getUtilisateurByEmail($email);
                if ($newUser) {
                    return [
                        'success' => true,
                        'message' => 'Utilisateur créé avec succès',
                        'data' => $newUser
                    ];
                }
                return [
                    'success' => true,
                    'message' => 'Utilisateur créé avec succès',
                    'data' => $utilisateur
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

    /**
     * Récupérer un utilisateur par son ID
     */
    public function getUtilisateur(int $id): array {
        try {
            $utilisateur = $this->requeteUtilisateur->getUtilisateurById($id);
            
            if ($utilisateur) {
                // Ne pas retourner le mot de passe dans la réponse
                $utilisateur->setMotDePasse('');
                return [
                    'success' => true,
                    'data' => $utilisateur
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Utilisateur non trouvé'
                ];
            }
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Récupérer un utilisateur par son email
     */
    public function getUtilisateurByEmail(string $email): array {
        try {
            $utilisateur = $this->requeteUtilisateur->getUtilisateurByEmail($email);
            
            if ($utilisateur) {
                // Ne pas retourner le mot de passe dans la réponse
                $utilisateur->setMotDePasse('');
                return [
                    'success' => true,
                    'data' => $utilisateur
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Utilisateur non trouvé'
                ];
            }
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Récupérer tous les utilisateurs
     */
    public function getAllUtilisateurs(): array {
        try {
            $utilisateurs = $this->requeteUtilisateur->getAllUtilisateurs();
            
            // Ne pas retourner les mots de passe dans la réponse
            foreach ($utilisateurs as $utilisateur) {
                $utilisateur->setMotDePasse('');
            }
            
            return [
                'success' => true,
                'data' => $utilisateurs,
                'count' => count($utilisateurs)
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Mettre à jour un utilisateur
     */
    public function updateUtilisateur(int $id, string $nom, string $prenom, string $email, string $telephone, string $role, string $photo = null): array {
        try {
            // Vérifier d'abord si l'utilisateur existe
            $existingUtilisateur = $this->requeteUtilisateur->getUtilisateurById($id);
            
            if (!$existingUtilisateur) {
                return [
                    'success' => false,
                    'message' => 'Utilisateur non trouvé'
                ];
            }

            // Validation des données
            $validationErrors = $this->validateUpdateData($nom, $prenom, $email, $telephone, $role);
            
            if (!empty($validationErrors)) {
                return [
                    'success' => false,
                    'message' => 'Données invalides',
                    'errors' => $validationErrors
                ];
            }

            // Vérifier si l'email est déjà utilisé par un autre utilisateur
            if ($email !== $existingUtilisateur->getEmail()) {
                $userWithEmail = $this->requeteUtilisateur->getUtilisateurByEmail($email);
                if ($userWithEmail && $userWithEmail->getId() !== $id) {
                    return [
                        'success' => false,
                        'message' => 'Cet email est déjà utilisé par un autre utilisateur'
                    ];
                }
            }

            // Conserver le mot de passe existant et la date de création
            $existingUtilisateur->setNom($nom);
            $existingUtilisateur->setPrenom($prenom);
            $existingUtilisateur->setEmail($email);
            $existingUtilisateur->setTelephone($telephone);
            $existingUtilisateur->setRole($role);
            if ($photo !== null) {
                $existingUtilisateur->setPhoto($photo);
            }

            if ($this->requeteUtilisateur->mettreAJourUtilisateur($existingUtilisateur)) {
                // Ne pas retourner le mot de passe dans la réponse
                $existingUtilisateur->setMotDePasse('');
                return [
                    'success' => true,
                    'message' => 'Utilisateur mis à jour avec succès',
                    'data' => $existingUtilisateur
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Erreur lors de la mise à jour de l\'utilisateur'
                ];
            }
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Mettre à jour le mot de passe d'un utilisateur
     */
    public function updatePassword(int $id, string $currentPassword, string $newPassword): array {
        try {
            // Vérifier d'abord si l'utilisateur existe
            $existingUtilisateur = $this->requeteUtilisateur->getUtilisateurById($id);
            
            if (!$existingUtilisateur) {
                return [
                    'success' => false,
                    'message' => 'Utilisateur non trouvé'
                ];
            }

            // Vérifier le mot de passe actuel
            if (!password_verify($currentPassword, $existingUtilisateur->getMotDePasse())) {
                return [
                    'success' => false,
                    'message' => 'Mot de passe actuel incorrect'
                ];
            }

            // Validation du nouveau mot de passe
            $passwordErrors = $this->validatePassword($newPassword);
            if (!empty($passwordErrors)) {
                return [
                    'success' => false,
                    'message' => 'Nouveau mot de passe invalide',
                    'errors' => $passwordErrors
                ];
            }

            $existingUtilisateur->setMotDePasse($newPassword);

            if ($this->requeteUtilisateur->mettreAJourUtilisateur($existingUtilisateur)) {
                return [
                    'success' => true,
                    'message' => 'Mot de passe mis à jour avec succès'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Erreur lors de la mise à jour du mot de passe'
                ];
            }
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Supprimer un utilisateur
     */
    public function deleteUtilisateur(int $id): array {
        try {
            // Vérifier d'abord si l'utilisateur existe
            $existingUtilisateur = $this->requeteUtilisateur->getUtilisateurById($id);
            
            if (!$existingUtilisateur) {
                return [
                    'success' => false,
                    'message' => 'Utilisateur non trouvé'
                ];
            }

            if ($this->requeteUtilisateur->supprimerUtilisateur($id)) {
                return [
                    'success' => true,
                    'message' => 'Utilisateur supprimé avec succès'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Erreur lors de la suppression de l\'utilisateur'
                ];
            }
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Authentifier un utilisateur
     */
    public function authenticate(string $email, string $password): array {
        try {
            $utilisateur = $this->requeteUtilisateur->getUtilisateurByEmail($email);
            
            if (!$utilisateur) {
                return [
                    'success' => false,
                    'message' => 'Email ou mot de passe incorrect'
                ];
            }

            if (!password_verify($password, $utilisateur->getMotDePasse())) {
                return [
                    'success' => false,
                    'message' => 'Email ou mot de passe incorrect'
                ];
            }

            // Ne pas retourner le mot de passe dans la réponse
            $utilisateur->setMotDePasse('');
            
            return [
                'success' => true,
                'message' => 'Authentification réussie',
                'data' => $utilisateur
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Récupérer les utilisateurs par rôle
     */
    public function getUtilisateursByRole(string $role): array {
        try {
            $allUtilisateurs = $this->requeteUtilisateur->getAllUtilisateurs();
            $utilisateurs = array_filter($allUtilisateurs, function($utilisateur) use ($role) {
                return $utilisateur->getRole() === $role;
            });
            
            // Ne pas retourner les mots de passe dans la réponse
            foreach ($utilisateurs as $utilisateur) {
                $utilisateur->setMotDePasse('');
            }
            
            return [
                'success' => true,
                'data' => array_values($utilisateurs),
                'count' => count($utilisateurs),
                'role' => $role
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Obtenir les statistiques des utilisateurs
     */
    public function getStats(): array {
        try {
            $allUtilisateurs = $this->requeteUtilisateur->getAllUtilisateurs();
            
            $stats = [
                'total' => count($allUtilisateurs),
                'par_role' => [],
                'par_mois' => []
            ];

            foreach ($allUtilisateurs as $utilisateur) {
                // Statistiques par rôle
                $role = $utilisateur->getRole();
                if (!isset($stats['par_role'][$role])) {
                    $stats['par_role'][$role] = 0;
                }
                $stats['par_role'][$role]++;

                // Statistiques par mois de création
                $dateCreation = $utilisateur->getDateCreation();
                $month = date('Y-m', strtotime($dateCreation));
                if (!isset($stats['par_mois'][$month])) {
                    $stats['par_mois'][$month] = 0;
                }
                $stats['par_mois'][$month]++;
            }

            return [
                'success' => true,
                'data' => $stats
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Rechercher des utilisateurs
     */
    public function searchUtilisateurs(string $searchTerm): array {
        try {
            $allUtilisateurs = $this->requeteUtilisateur->getAllUtilisateurs();
            $searchTerm = strtolower(trim($searchTerm));
            
            $utilisateurs = array_filter($allUtilisateurs, function($utilisateur) use ($searchTerm) {
                return stripos($utilisateur->getNom(), $searchTerm) !== false ||
                       stripos($utilisateur->getPrenom(), $searchTerm) !== false ||
                       stripos($utilisateur->getEmail(), $searchTerm) !== false ||
                       stripos($utilisateur->getTelephone(), $searchTerm) !== false;
            });
            
            // Ne pas retourner les mots de passe dans la réponse
            foreach ($utilisateurs as $utilisateur) {
                $utilisateur->setMotDePasse('');
            }
            
            return [
                'success' => true,
                'data' => array_values($utilisateurs),
                'count' => count($utilisateurs),
                'search_term' => $searchTerm
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Valider les données d'un utilisateur
     */
    private function validateUtilisateurData(string $nom, string $prenom, string $email, string $mot_de_passe, string $telephone, string $role): array {
        $errors = [];

        if (empty(trim($nom))) {
            $errors[] = 'Le nom est requis';
        }

        if (empty(trim($prenom))) {
            $errors[] = 'Le prénom est requis';
        }

        if (empty(trim($email))) {
            $errors[] = 'L\'email est requis';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Format d\'email invalide';
        }

        $passwordErrors = $this->validatePassword($mot_de_passe);
        $errors = array_merge($errors, $passwordErrors);

        if (!empty($telephone) && !preg_match('/^[0-9+\s()-]{10,20}$/', $telephone)) {
            $errors[] = 'Format de téléphone invalide';
        }

        $roleErrors = $this->validateRole($role);
        $errors = array_merge($errors, $roleErrors);

        return $errors;
    }

    /**
     * Valider les données de mise à jour
     */
    private function validateUpdateData(string $nom, string $prenom, string $email, string $telephone, string $role): array {
        $errors = [];

        if (empty(trim($nom))) {
            $errors[] = 'Le nom est requis';
        }

        if (empty(trim($prenom))) {
            $errors[] = 'Le prénom est requis';
        }

        if (empty(trim($email))) {
            $errors[] = 'L\'email est requis';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Format d\'email invalide';
        }

        if (!empty($telephone) && !preg_match('/^[0-9+\s()-]{10,20}$/', $telephone)) {
            $errors[] = 'Format de téléphone invalide';
        }

        $roleErrors = $this->validateRole($role);
        $errors = array_merge($errors, $roleErrors);

        return $errors;
    }

    /**
     * Valider le mot de passe
     */
    private function validatePassword(string $password): array {
        $errors = [];

        if (strlen($password) < 8) {
            $errors[] = 'Le mot de passe doit contenir au moins 8 caractères';
        }

        if (!preg_match('/[A-Z]/', $password)) {
            $errors[] = 'Le mot de passe doit contenir au moins une majuscule';
        }

        if (!preg_match('/[a-z]/', $password)) {
            $errors[] = 'Le mot de passe doit contenir au moins une minuscule';
        }

        if (!preg_match('/[0-9]/', $password)) {
            $errors[] = 'Le mot de passe doit contenir au moins un chiffre';
        }

        return $errors;
    }

    /**
     * Valider le rôle
     */
    private function validateRole(string $role): array {
        $errors = [];
        $validRoles = ['admin', 'formateur', 'etudiant', 'client']; // À adapter selon vos besoins

        if (!in_array($role, $validRoles)) {
            $errors[] = 'Rôle invalide. Les rôles valides sont: ' . implode(', ', $validRoles);
        }

        return $errors;
    }
}
?>