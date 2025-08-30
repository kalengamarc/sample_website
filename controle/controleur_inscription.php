<?php

include_once("../modele/inscription.php");

class InscriptionController {
    private $requeteInscription;

    public function __construct() {
        $this->requeteInscription = new RequeteInscription();
    }

    /**
     * Créer une nouvelle inscription
     */
    public function createInscription(int $id_utilisateur, int $id_formation, string $statut = 'inscrit'): array {
        try {
            // Validation des données
            $validationErrors = $this->validateInscriptionData($id_utilisateur, $id_formation, $statut);
            
            if (!empty($validationErrors)) {
                return [
                    'success' => false,
                    'message' => 'Données invalides',
                    'errors' => $validationErrors
                ];
            }

            // Vérifier si l'inscription existe déjà
            if ($this->requeteInscription->existeDeja($id_utilisateur, $id_formation)) {
                return [
                    'success' => false,
                    'message' => 'Cet utilisateur est déjà inscrit à cette formation'
                ];
            }

            $date_inscription = date('Y-m-d H:i:s');
            $inscription = new Inscription(null, $id_utilisateur, $id_formation, $date_inscription, $statut);
            
            $result = $this->requeteInscription->ajouterInscription($inscription);
            
            if ($result !== false) {
                $inscription->setIdInscription($result);
                return [
                    'success' => true,
                    'message' => 'Inscription créée avec succès',
                    'data' => $inscription
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Erreur lors de la création de l\'inscription'
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
     * Récupérer une inscription par son ID
     */
    public function getInscription(int $id_inscription): array {
        try {
            $inscription = $this->requeteInscription->getInscriptionById($id_inscription);
            
            if ($inscription) {
                return [
                    'success' => true,
                    'data' => $inscription
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Inscription non trouvée'
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
     * Récupérer toutes les inscriptions
     */
    public function getAllInscriptions(): array {
        try {
            $inscriptions = $this->requeteInscription->getAllInscriptions();
            
            return [
                'success' => true,
                'data' => $inscriptions,
                'count' => count($inscriptions)
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Récupérer les inscriptions d'un utilisateur
     */
    public function getInscriptionsByUtilisateur(int $id_utilisateur): array {
        try {
            $inscriptions = $this->requeteInscription->getInscriptionsByUtilisateur($id_utilisateur);
            
            return [
                'success' => true,
                'data' => $inscriptions,
                'count' => count($inscriptions),
                'id_utilisateur' => $id_utilisateur
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Récupérer les inscriptions d'une formation
     */
    public function getInscriptionsByFormation(int $id_formation): array {
        try {
            $inscriptions = $this->requeteInscription->getInscriptionsByFormation($id_formation);
            
            return [
                'success' => true,
                'data' => $inscriptions,
                'count' => count($inscriptions),
                'id_formation' => $id_formation
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Mettre à jour une inscription
     */
    public function updateInscription(int $id_inscription, int $id_utilisateur, int $id_formation, string $statut): array {
        try {
            // Vérifier d'abord si l'inscription existe
            $existingInscription = $this->requeteInscription->getInscriptionById($id_inscription);
            
            if (!$existingInscription) {
                return [
                    'success' => false,
                    'message' => 'Inscription non trouvée'
                ];
            }

            // Validation des données
            $validationErrors = $this->validateInscriptionData($id_utilisateur, $id_formation, $statut);
            
            if (!empty($validationErrors)) {
                return [
                    'success' => false,
                    'message' => 'Données invalides',
                    'errors' => $validationErrors
                ];
            }

            // Conserver la date d'inscription originale
            $date_inscription = $existingInscription->getDateInscription();
            
            $inscription = new Inscription($id_inscription, $id_utilisateur, $id_formation, $date_inscription, $statut);
            
            if ($this->requeteInscription->mettreAJourInscription($inscription)) {
                return [
                    'success' => true,
                    'message' => 'Inscription mise à jour avec succès',
                    'data' => $inscription
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Erreur lors de la mise à jour de l\'inscription'
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
     * Changer le statut d'une inscription
     */
    public function changeStatut(int $id_inscription, string $statut): array {
        try {
            // Vérifier d'abord si l'inscription existe
            $existingInscription = $this->requeteInscription->getInscriptionById($id_inscription);
            
            if (!$existingInscription) {
                return [
                    'success' => false,
                    'message' => 'Inscription non trouvée'
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

            if ($this->requeteInscription->changerStatut($id_inscription, $statut)) {
                $existingInscription->setStatut($statut);
                return [
                    'success' => true,
                    'message' => 'Statut mis à jour avec succès',
                    'data' => $existingInscription
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
     * Supprimer une inscription
     */
    public function deleteInscription(int $id_inscription): array {
        try {
            // Vérifier d'abord si l'inscription existe
            $existingInscription = $this->requeteInscription->getInscriptionById($id_inscription);
            
            if (!$existingInscription) {
                return [
                    'success' => false,
                    'message' => 'Inscription non trouvée'
                ];
            }

            if ($this->requeteInscription->supprimerInscription($id_inscription)) {
                return [
                    'success' => true,
                    'message' => 'Inscription supprimée avec succès'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Erreur lors de la suppression de l\'inscription'
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
     * Compter le nombre d'inscriptions pour une formation
     */
    public function countByFormation(int $id_formation): array {
        try {
            $count = $this->requeteInscription->compterParFormation($id_formation);
            
            return [
                'success' => true,
                'data' => [
                    'id_formation' => $id_formation,
                    'count' => $count
                ]
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Obtenir les statistiques par statut
     */
    public function getStatsByStatut(): array {
        try {
            $stats = $this->requeteInscription->statsParStatut();
            
            return [
                'success' => true,
                'data' => $stats,
                'total' => array_sum($stats)
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Obtenir les dernières inscriptions
     */
    public function getRecentInscriptions(int $limit = 10): array {
        try {
            $inscriptions = $this->requeteInscription->getDernieresInscriptions($limit);
            
            return [
                'success' => true,
                'data' => $inscriptions,
                'count' => count($inscriptions),
                'limit' => $limit
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Vérifier si un utilisateur est déjà inscrit à une formation
     */
    public function checkExistingInscription(int $id_utilisateur, int $id_formation): array {
        try {
            $exists = $this->requeteInscription->existeDeja($id_utilisateur, $id_formation);
            
            return [
                'success' => true,
                'data' => [
                    'exists' => $exists,
                    'id_utilisateur' => $id_utilisateur,
                    'id_formation' => $id_formation
                ]
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Valider les données d'une inscription
     */
    private function validateInscriptionData(int $id_utilisateur, int $id_formation, string $statut): array {
        $errors = [];

        if ($id_utilisateur <= 0) {
            $errors[] = 'ID utilisateur invalide';
        }

        if ($id_formation <= 0) {
            $errors[] = 'ID formation invalide';
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
        $validStatuts = ['inscrit', 'en cours', 'terminé', 'annulé'];

        if (!in_array($statut, $validStatuts)) {
            $errors[] = 'Statut invalide. Les statuts valides sont: ' . implode(', ', $validStatuts);
        }

        return $errors;
    }

    /**
     * Obtenir les inscriptions avec filtres avancés
     */
    public function getFilteredInscriptions(?int $id_utilisateur = null, ?int $id_formation = null, ?string $statut = null): array {
        try {
            $allInscriptions = $this->requeteInscription->getAllInscriptions();
            
            // Appliquer les filtres
            $filteredInscriptions = array_filter($allInscriptions, function($inscription) use ($id_utilisateur, $id_formation, $statut) {
                $match = true;
                
                if ($id_utilisateur !== null && $inscription->getIdUtilisateur() != $id_utilisateur) {
                    $match = false;
                }
                
                if ($id_formation !== null && $inscription->getIdFormation() != $id_formation) {
                    $match = false;
                }
                
                if ($statut !== null && $inscription->getStatut() != $statut) {
                    $match = false;
                }
                
                return $match;
            });
            
            return [
                'success' => true,
                'data' => array_values($filteredInscriptions),
                'count' => count($filteredInscriptions),
                'filters' => [
                    'id_utilisateur' => $id_utilisateur,
                    'id_formation' => $id_formation,
                    'statut' => $statut
                ]
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ];
        }
    }
}