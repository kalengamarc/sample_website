<?php

include_once("Produit.php");
include_once("CRUDProduit.php");

class ProduitController {
    private $crudProduit;

    public function __construct() {
        $this->crudProduit = new CRUDProduit();
    }

    /**
     * Créer un nouveau produit
     */
    public function createProduit(string $nom, string $description, float $prix, int $stock, string $categorie, string $photo = null): array {
        try {
            // Validation des données
            $validationErrors = $this->validateProduitData($nom, $description, $prix, $stock, $categorie);
            
            if (!empty($validationErrors)) {
                return [
                    'success' => false,
                    'message' => 'Données invalides',
                    'errors' => $validationErrors
                ];
            }

            $date_ajout = date('Y-m-d H:i:s');
            $produit = new Produit($nom, $description, $prix, $stock, $categorie, $photo, $date_ajout);
            
            if ($this->crudProduit->ajouterProduit($produit)) {
                return [
                    'success' => true,
                    'message' => 'Produit créé avec succès',
                    'data' => $produit
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Erreur lors de la création du produit'
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
     * Récupérer un produit par son ID
     */
    public function getProduit(int $id_produit): array {
        try {
            $produit = $this->crudProduit->getProduitById($id_produit);
            
            if ($produit) {
                return [
                    'success' => true,
                    'data' => $produit
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Produit non trouvé'
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
     * Récupérer tous les produits
     */
    public function getAllProduits(): array {
        try {
            $produits = $this->crudProduit->getAllProduits();
            
            return [
                'success' => true,
                'data' => $produits,
                'count' => count($produits),
                'stats' => $this->calculateStats($produits)
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Mettre à jour un produit
     */
    public function updateProduit(int $id_produit, string $nom, string $description, float $prix, int $stock, string $categorie, string $photo = null): array {
        try {
            // Vérifier d'abord si le produit existe
            $existingProduit = $this->crudProduit->getProduitById($id_produit);
            
            if (!$existingProduit) {
                return [
                    'success' => false,
                    'message' => 'Produit non trouvé'
                ];
            }

            // Validation des données
            $validationErrors = $this->validateProduitData($nom, $description, $prix, $stock, $categorie);
            
            if (!empty($validationErrors)) {
                return [
                    'success' => false,
                    'message' => 'Données invalides',
                    'errors' => $validationErrors
                ];
            }

            // Conserver la date d'ajout originale
            $date_ajout = $existingProduit->getDateAjout();
            
            $produit = new Produit($nom, $description, $prix, $stock, $categorie, $photo, $date_ajout);
            $produit->setIdProduit($id_produit);

            if ($this->crudProduit->updateProduit($produit)) {
                return [
                    'success' => true,
                    'message' => 'Produit mis à jour avec succès',
                    'data' => $produit
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Erreur lors de la mise à jour du produit'
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
     * Mettre à jour uniquement le stock d'un produit
     */
    public function updateStock(int $id_produit, int $stock): array {
        try {
            // Vérifier d'abord si le produit existe
            $existingProduit = $this->crudProduit->getProduitById($id_produit);
            
            if (!$existingProduit) {
                return [
                    'success' => false,
                    'message' => 'Produit non trouvé'
                ];
            }

            // Validation du stock
            if ($stock < 0) {
                return [
                    'success' => false,
                    'message' => 'Le stock ne peut pas être négatif'
                ];
            }

            $existingProduit->setStock($stock);

            if ($this->crudProduit->updateProduit($existingProduit)) {
                return [
                    'success' => true,
                    'message' => 'Stock mis à jour avec succès',
                    'data' => $existingProduit
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Erreur lors de la mise à jour du stock'
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
     * Augmenter le stock d'un produit
     */
    public function increaseStock(int $id_produit, int $quantity): array {
        try {
            // Vérifier d'abord si le produit existe
            $existingProduit = $this->crudProduit->getProduitById($id_produit);
            
            if (!$existingProduit) {
                return [
                    'success' => false,
                    'message' => 'Produit non trouvé'
                ];
            }

            if ($quantity <= 0) {
                return [
                    'success' => false,
                    'message' => 'La quantité doit être positive'
                ];
            }

            $newStock = $existingProduit->getStock() + $quantity;
            $existingProduit->setStock($newStock);

            if ($this->crudProduit->updateProduit($existingProduit)) {
                return [
                    'success' => true,
                    'message' => 'Stock augmenté avec succès',
                    'data' => $existingProduit
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Erreur lors de l\'augmentation du stock'
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
     * Diminuer le stock d'un produit
     */
    public function decreaseStock(int $id_produit, int $quantity): array {
        try {
            // Vérifier d'abord si le produit existe
            $existingProduit = $this->crudProduit->getProduitById($id_produit);
            
            if (!$existingProduit) {
                return [
                    'success' => false,
                    'message' => 'Produit non trouvé'
                ];
            }

            if ($quantity <= 0) {
                return [
                    'success' => false,
                    'message' => 'La quantité doit être positive'
                ];
            }

            $currentStock = $existingProduit->getStock();
            if ($quantity > $currentStock) {
                return [
                    'success' => false,
                    'message' => 'Stock insuffisant. Stock actuel: ' . $currentStock
                ];
            }

            $newStock = $currentStock - $quantity;
            $existingProduit->setStock($newStock);

            if ($this->crudProduit->updateProduit($existingProduit)) {
                return [
                    'success' => true,
                    'message' => 'Stock diminué avec succès',
                    'data' => $existingProduit
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Erreur lors de la diminution du stock'
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
     * Supprimer un produit
     */
    public function deleteProduit(int $id_produit): array {
        try {
            // Vérifier d'abord si le produit existe
            $existingProduit = $this->crudProduit->getProduitById($id_produit);
            
            if (!$existingProduit) {
                return [
                    'success' => false,
                    'message' => 'Produit non trouvé'
                ];
            }

            if ($this->crudProduit->deleteProduit($id_produit)) {
                return [
                    'success' => true,
                    'message' => 'Produit supprimé avec succès'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Erreur lors de la suppression du produit'
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
     * Récupérer les produits par catégorie
     */
    public function getProduitsByCategorie(string $categorie): array {
        try {
            $allProduits = $this->crudProduit->getAllProduits();
            $produits = array_filter($allProduits, function($produit) use ($categorie) {
                return $produit->getCategorie() === $categorie;
            });
            
            return [
                'success' => true,
                'data' => array_values($produits),
                'count' => count($produits),
                'categorie' => $categorie,
                'stats' => $this->calculateStats($produits)
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Récupérer les produits avec faible stock
     */
    public function getLowStockProduits(int $threshold = 10): array {
        try {
            $allProduits = $this->crudProduit->getAllProduits();
            $produits = array_filter($allProduits, function($produit) use ($threshold) {
                return $produit->getStock() <= $threshold;
            });
            
            // Trier par stock croissant
            usort($produits, function($a, $b) {
                return $a->getStock() - $b->getStock();
            });
            
            return [
                'success' => true,
                'data' => $produits,
                'count' => count($produits),
                'threshold' => $threshold,
                'stats' => $this->calculateStats($produits)
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Rechercher des produits
     */
    public function searchProduits(string $searchTerm): array {
        try {
            $allProduits = $this->crudProduit->getAllProduits();
            $searchTerm = strtolower(trim($searchTerm));
            
            $produits = array_filter($allProduits, function($produit) use ($searchTerm) {
                return stripos($produit->getNom(), $searchTerm) !== false ||
                       stripos($produit->getDescription(), $searchTerm) !== false ||
                       stripos($produit->getCategorie(), $searchTerm) !== false;
            });
            
            return [
                'success' => true,
                'data' => array_values($produits),
                'count' => count($produits),
                'search_term' => $searchTerm,
                'stats' => $this->calculateStats($produits)
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Obtenir les statistiques des produits
     */
    public function getStats(): array {
        try {
            $allProduits = $this->crudProduit->getAllProduits();
            
            $stats = [
                'total' => count($allProduits),
                'total_stock' => 0,
                'total_valeur' => 0,
                'par_categorie' => [],
                'low_stock' => 0,
                'out_of_stock' => 0
            ];

            foreach ($allProduits as $produit) {
                $stock = $produit->getStock();
                $valeur = $produit->getPrix() * $stock;
                
                $stats['total_stock'] += $stock;
                $stats['total_valeur'] += $valeur;

                // Statistiques par catégorie
                $categorie = $produit->getCategorie();
                if (!isset($stats['par_categorie'][$categorie])) {
                    $stats['par_categorie'][$categorie] = [
                        'count' => 0,
                        'stock' => 0,
                        'valeur' => 0
                    ];
                }
                $stats['par_categorie'][$categorie]['count']++;
                $stats['par_categorie'][$categorie]['stock'] += $stock;
                $stats['par_categorie'][$categorie]['valeur'] += $valeur;

                // Produits à faible stock
                if ($stock <= 10) {
                    $stats['low_stock']++;
                }
                
                // Produits en rupture de stock
                if ($stock === 0) {
                    $stats['out_of_stock']++;
                }
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
     * Valider les données d'un produit
     */
    private function validateProduitData(string $nom, string $description, float $prix, int $stock, string $categorie): array {
        $errors = [];

        if (empty(trim($nom))) {
            $errors[] = 'Le nom est requis';
        } elseif (strlen(trim($nom)) < 2) {
            $errors[] = 'Le nom doit contenir au moins 2 caractères';
        }

        if (empty(trim($description))) {
            $errors[] = 'La description est requise';
        } elseif (strlen(trim($description)) < 10) {
            $errors[] = 'La description doit contenir au moins 10 caractères';
        }

        if ($prix <= 0) {
            $errors[] = 'Le prix doit être positif';
        }

        if ($stock < 0) {
            $errors[] = 'Le stock ne peut pas être négatif';
        }

        if (empty(trim($categorie))) {
            $errors[] = 'La catégorie est requise';
        }

        return $errors;
    }

    /**
     * Calculer les statistiques pour une liste de produits
     */
    private function calculateStats(array $produits): array {
        $stats = [
            'count' => count($produits),
            'total_stock' => 0,
            'total_valeur' => 0,
            'average_price' => 0,
            'min_price' => null,
            'max_price' => null
        ];

        if ($stats['count'] > 0) {
            $totalPrice = 0;
            $minPrice = PHP_FLOAT_MAX;
            $maxPrice = PHP_FLOAT_MIN;

            foreach ($produits as $produit) {
                $stock = $produit->getStock();
                $price = $produit->getPrix();
                $valeur = $price * $stock;
                
                $stats['total_stock'] += $stock;
                $stats['total_valeur'] += $valeur;
                $totalPrice += $price;

                if ($price < $minPrice) $minPrice = $price;
                if ($price > $maxPrice) $maxPrice = $price;
            }

            $stats['average_price'] = round($totalPrice / $stats['count'], 2);
            $stats['min_price'] = $minPrice;
            $stats['max_price'] = $maxPrice;
        }

        return $stats;
    }

    /**
     * Obtenir les catégories disponibles
     */
    public function getCategories(): array {
        try {
            $allProduits = $this->crudProduit->getAllProduits();
            $categories = [];

            foreach ($allProduits as $produit) {
                $categorie = $produit->getCategorie();
                if (!in_array($categorie, $categories)) {
                    $categories[] = $categorie;
                }
            }

            sort($categories);

            return [
                'success' => true,
                'data' => $categories,
                'count' => count($categories)
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Récupérer les produits récemment ajoutés
     */
    public function getRecentProduits(int $limit = 10): array {
        try {
            $allProduits = $this->crudProduit->getAllProduits();
            
            // Trier par date d'ajout décroissante
            usort($allProduits, function($a, $b) {
                return strtotime($b->getDateAjout()) - strtotime($a->getDateAjout());
            });
            
            $recentProduits = array_slice($allProduits, 0, $limit);
            
            return [
                'success' => true,
                'data' => $recentProduits,
                'count' => count($recentProduits),
                'limit' => $limit
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ];
        }
    }
}