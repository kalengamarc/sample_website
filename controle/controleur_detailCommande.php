<?php

include_once('DetailCommande.php');

class DetailCommandeController {
    private $requeteDetailCommande;

    public function __construct() {
        $this->requeteDetailCommande = new RequeteDetailCommande();
    }

    /**
     * Créer un nouveau détail de commande
     */
    public function createDetailCommande(int $id_commande, int $id_produit, int $quantite, float $prix_unitaire): array {
        try {
            $detailCommande = new DetailCommande(null, $id_commande, $id_produit, $quantite, $prix_unitaire);
            
            if ($this->requeteDetailCommande->ajouterDetailCommande($detailCommande)) {
                return [
                    'success' => true,
                    'message' => 'Détail de commande créé avec succès',
                    'data' => $detailCommande
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Erreur lors de la création du détail de commande'
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
     * Récupérer un détail de commande par son ID
     */
    public function getDetailCommande(int $id_detail): array {
        try {
            $detailCommande = $this->requeteDetailCommande->getDetailById($id_detail);
            
            if ($detailCommande) {
                return [
                    'success' => true,
                    'data' => $detailCommande
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Détail de commande non trouvé'
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
     * Récupérer tous les détails d'une commande
     */
    public function getDetailsByCommande(int $id_commande): array {
        try {
            $details = $this->requeteDetailCommande->getDetailsByCommande($id_commande);
            
            return [
                'success' => true,
                'data' => $details,
                'count' => count($details)
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Mettre à jour un détail de commande
     */
    public function updateDetailCommande(int $id_detail, int $id_commande, int $id_produit, int $quantite, float $prix_unitaire): array {
        try {
            // Vérifier d'abord si le détail existe
            $existingDetail = $this->requeteDetailCommande->getDetailById($id_detail);
            
            if (!$existingDetail) {
                return [
                    'success' => false,
                    'message' => 'Détail de commande non trouvé'
                ];
            }

            $detailCommande = new DetailCommande($id_detail, $id_commande, $id_produit, $quantite, $prix_unitaire);
            
            if ($this->requeteDetailCommande->updateDetailCommande($detailCommande)) {
                return [
                    'success' => true,
                    'message' => 'Détail de commande mis à jour avec succès',
                    'data' => $detailCommande
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Erreur lors de la mise à jour du détail de commande'
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
     * Supprimer un détail de commande
     */
    public function deleteDetailCommande(int $id_detail): array {
        try {
            // Vérifier d'abord si le détail existe
            $existingDetail = $this->requeteDetailCommande->getDetailById($id_detail);
            
            if (!$existingDetail) {
                return [
                    'success' => false,
                    'message' => 'Détail de commande non trouvé'
                ];
            }

            if ($this->requeteDetailCommande->deleteDetailCommande($id_detail)) {
                return [
                    'success' => true,
                    'message' => 'Détail de commande supprimé avec succès'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Erreur lors de la suppression du détail de commande'
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
     * Calculer le total d'une commande
     */
    public function getCommandeTotal(int $id_commande): array {
        try {
            $total = $this->requeteDetailCommande->getTotalCommande($id_commande);
            
            return [
                'success' => true,
                'data' => [
                    'id_commande' => $id_commande,
                    'total' => $total
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
     * Valider les données d'un détail de commande
     */
    private function validateDetailCommandeData(int $id_commande, int $id_produit, int $quantite, float $prix_unitaire): array {
        $errors = [];

        if ($id_commande <= 0) {
            $errors[] = 'ID commande invalide';
        }

        if ($id_produit <= 0) {
            $errors[] = 'ID produit invalide';
        }

        if ($quantite <= 0) {
            $errors[] = 'Quantité invalide';
        }

        if ($prix_unitaire <= 0) {
            $errors[] = 'Prix unitaire invalide';
        }

        return $errors;
    }

    /**
     * Créer un détail de commande avec validation
     */
    public function createDetailCommandeWithValidation(int $id_commande, int $id_produit, int $quantite, float $prix_unitaire): array {
        // Validation des données
        $validationErrors = $this->validateDetailCommandeData($id_commande, $id_produit, $quantite, $prix_unitaire);
        
        if (!empty($validationErrors)) {
            return [
                'success' => false,
                'message' => 'Données invalides',
                'errors' => $validationErrors
            ];
        }

        return $this->createDetailCommande($id_commande, $id_produit, $quantite, $prix_unitaire);
    }
}