<?php

include_once("Paiement.php");
include_once("CRUDPaiement.php");

class PaiementController {
    private $crudPaiement;

    public function __construct() {
        $this->crudPaiement = new CRUDPaiement();
    }

    /**
     * Créer un nouveau paiement
     */
    public function createPaiement(int $id_utilisateur, string $type, int $id_reference, float $montant, string $mode, string $statut = "en attente"): array {
        try {
            // Validation des données
            $validationErrors = $this->validatePaiementData($id_utilisateur, $type, $id_reference, $montant, $mode, $statut);
            
            if (!empty($validationErrors)) {
                return [
                    'success' => false,
                    'message' => 'Données invalides',
                    'errors' => $validationErrors
                ];
            }

            $paiement = new Paiement($id_utilisateur, $type, $id_reference, $montant, $mode, $statut);
            
            if ($this->crudPaiement->createPaiement($paiement)) {
                // Récupérer l'ID généré
                $lastId = $this->crudPaiement->getLastInsertId();
                if ($lastId) {
                    $paiement->setIdPaiement($lastId);
                }
                
                return [
                    'success' => true,
                    'message' => 'Paiement créé avec succès',
                    'data' => $paiement
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Erreur lors de la création du paiement'
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
     * Récupérer un paiement par son ID
     */
    public function getPaiement(int $id_paiement): array {
        try {
            $paiement = $this->crudPaiement->getPaiementById($id_paiement);
            
            if ($paiement) {
                return [
                    'success' => true,
                    'data' => $paiement
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Paiement non trouvé'
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
     * Récupérer tous les paiements
     */
    public function getAllPaiements(): array {
        try {
            $paiements = $this->crudPaiement->getAllPaiements();
            
            return [
                'success' => true,
                'data' => $paiements,
                'count' => count($paiements),
                'total_montant' => $this->calculateTotalAmount($paiements)
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Mettre à jour un paiement
     */
    public function updatePaiement(int $id_paiement, string $statut, string $mode, float $montant): array {
        try {
            // Vérifier d'abord si le paiement existe
            $existingPaiement = $this->crudPaiement->getPaiementById($id_paiement);
            
            if (!$existingPaiement) {
                return [
                    'success' => false,
                    'message' => 'Paiement non trouvé'
                ];
            }

            // Validation des données
            $validationErrors = $this->validateUpdateData($statut, $mode, $montant);
            
            if (!empty($validationErrors)) {
                return [
                    'success' => false,
                    'message' => 'Données invalides',
                    'errors' => $validationErrors
                ];
            }

            // Mettre à jour les propriétés
            $existingPaiement->setStatut($statut);
            $existingPaiement->setMode($mode);
            $existingPaiement->setMontant($montant);

            if ($this->crudPaiement->updatePaiement($existingPaiement)) {
                return [
                    'success' => true,
                    'message' => 'Paiement mis à jour avec succès',
                    'data' => $existingPaiement
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Erreur lors de la mise à jour du paiement'
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
     * Mettre à jour uniquement le statut d'un paiement
     */
    public function updateStatut(int $id_paiement, string $statut): array {
        try {
            // Vérifier d'abord si le paiement existe
            $existingPaiement = $this->crudPaiement->getPaiementById($id_paiement);
            
            if (!$existingPaiement) {
                return [
                    'success' => false,
                    'message' => 'Paiement non trouvé'
                ];
            }

            // Validation du statut
            $validationErrors = $this->validateStatut($statut);
            
            if (!empty($validationErrors)) {
                return [
                    'success' => false,
                    'message' => 'Statut invalide',
                    'errors' => $validationErrors
                ];
            }

            // Si le statut devient "payé", mettre à jour la date de paiement
            if ($statut === "payé" && $existingPaiement->getStatut() !== "payé") {
                $existingPaiement->setDatePaiement(date('Y-m-d H:i:s'));
            }

            $existingPaiement->setStatut($statut);

            if ($this->crudPaiement->updatePaiement($existingPaiement)) {
                return [
                    'success' => true,
                    'message' => 'Statut du paiement mis à jour avec succès',
                    'data' => $existingPaiement
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Erreur lors de la mise à jour du statut'
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
     * Supprimer un paiement
     */
    public function deletePaiement(int $id_paiement): array {
        try {
            // Vérifier d'abord si le paiement existe
            $existingPaiement = $this->crudPaiement->getPaiementById($id_paiement);
            
            if (!$existingPaiement) {
                return [
                    'success' => false,
                    'message' => 'Paiement non trouvé'
                ];
            }

            if ($this->crudPaiement->deletePaiement($id_paiement)) {
                return [
                    'success' => true,
                    'message' => 'Paiement supprimé avec succès'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Erreur lors de la suppression du paiement'
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
     * Récupérer les paiements d'un utilisateur
     */
    public function getPaiementsByUtilisateur(int $id_utilisateur): array {
        try {
            $allPaiements = $this->crudPaiement->getAllPaiements();
            $paiements = array_filter($allPaiements, function($paiement) use ($id_utilisateur) {
                return $paiement->getIdUtilisateur() == $id_utilisateur;
            });
            
            return [
                'success' => true,
                'data' => array_values($paiements),
                'count' => count($paiements),
                'id_utilisateur' => $id_utilisateur,
                'total_montant' => $this->calculateTotalAmount($paiements)
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Récupérer les paiements par type et référence
     */
    public function getPaiementsByReference(string $type, int $id_reference): array {
        try {
            $allPaiements = $this->crudPaiement->getAllPaiements();
            $paiements = array_filter($allPaiements, function($paiement) use ($type, $id_reference) {
                return $paiement->getType() === $type && $paiement->getIdReference() == $id_reference;
            });
            
            return [
                'success' => true,
                'data' => array_values($paiements),
                'count' => count($paiements),
                'type' => $type,
                'id_reference' => $id_reference,
                'total_montant' => $this->calculateTotalAmount($paiements)
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Récupérer les paiements par statut
     */
    public function getPaiementsByStatut(string $statut): array {
        try {
            $allPaiements = $this->crudPaiement->getAllPaiements();
            $paiements = array_filter($allPaiements, function($paiement) use ($statut) {
                return $paiement->getStatut() === $statut;
            });
            
            return [
                'success' => true,
                'data' => array_values($paiements),
                'count' => count($paiements),
                'statut' => $statut,
                'total_montant' => $this->calculateTotalAmount($paiements)
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Obtenir les statistiques des paiements
     */
    public function getStats(): array {
        try {
            $allPaiements = $this->crudPaiement->getAllPaiements();
            
            $stats = [
                'total' => count($allPaiements),
                'total_montant' => $this->calculateTotalAmount($allPaiements),
                'par_statut' => [],
                'par_mode' => [],
                'par_type' => []
            ];

            foreach ($allPaiements as $paiement) {
                // Statistiques par statut
                $statut = $paiement->getStatut();
                if (!isset($stats['par_statut'][$statut])) {
                    $stats['par_statut'][$statut] = ['count' => 0, 'montant' => 0];
                }
                $stats['par_statut'][$statut]['count']++;
                $stats['par_statut'][$statut]['montant'] += $paiement->getMontant();

                // Statistiques par mode
                $mode = $paiement->getMode();
                if (!isset($stats['par_mode'][$mode])) {
                    $stats['par_mode'][$mode] = ['count' => 0, 'montant' => 0];
                }
                $stats['par_mode'][$mode]['count']++;
                $stats['par_mode'][$mode]['montant'] += $paiement->getMontant();

                // Statistiques par type
                $type = $paiement->getType();
                if (!isset($stats['par_type'][$type])) {
                    $stats['par_type'][$type] = ['count' => 0, 'montant' => 0];
                }
                $stats['par_type'][$type]['count']++;
                $stats['par_type'][$type]['montant'] += $paiement->getMontant();
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
     * Valider les données d'un paiement
     */
    private function validatePaiementData(int $id_utilisateur, string $type, int $id_reference, float $montant, string $mode, string $statut): array {
        $errors = [];

        if ($id_utilisateur <= 0) {
            $errors[] = 'ID utilisateur invalide';
        }

        if (empty(trim($type))) {
            $errors[] = 'Le type est requis';
        }

        if ($id_reference <= 0) {
            $errors[] = 'ID référence invalide';
        }

        if ($montant <= 0) {
            $errors[] = 'Le montant doit être positif';
        }

        if (empty(trim($mode))) {
            $errors[] = 'Le mode de paiement est requis';
        }

        $statutErrors = $this->validateStatut($statut);
        $errors = array_merge($errors, $statutErrors);

        return $errors;
    }

    /**
     * Valider les données de mise à jour
     */
    private function validateUpdateData(string $statut, string $mode, float $montant): array {
        $errors = [];

        if ($montant <= 0) {
            $errors[] = 'Le montant doit être positif';
        }

        if (empty(trim($mode))) {
            $errors[] = 'Le mode de paiement est requis';
        }

        $statutErrors = $this->validateStatut($statut);
        $errors = array_merge($errors, $statutErrors);

        return $errors;
    }

    /**
     * Valider le statut
     */
    private function validateStatut(string $statut): array {
        $errors = [];
        $validStatuts = ["en attente", "payé", "échoué", "annulé", "remboursé"];

        if (!in_array($statut, $validStatuts)) {
            $errors[] = 'Statut invalide. Les statuts valides sont: ' . implode(', ', $validStatuts);
        }

        return $errors;
    }

    /**
     * Calculer le montant total d'une liste de paiements
     */
    private function calculateTotalAmount(array $paiements): float {
        $total = 0;
        foreach ($paiements as $paiement) {
            $total += $paiement->getMontant();
        }
        return $total;
    }

    /**
     * Méthode utilitaire pour obtenir le dernier ID inséré (à adapter selon votre implémentation)
     */
    private function getLastInsertId(): ?int {
        // Cette méthode dépend de votre implémentation de la base de données
        // Vous devrez peut-être l'adapter selon votre classe CRUDPaiement
        return null; // À implémenter selon votre configuration
    }
}