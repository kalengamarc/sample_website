<?php
require_once __DIR__ . '/../modele/panier.php';
require_once __DIR__ . '/../modele/produit.php'; // Pour vérifier le stock

class PanierController {
    private $crudPanier;
    private $crudProduit;

    public function __construct() {
        $this->crudPanier = new PanierCRUD();
        $this->crudProduit = new CRUDProduit();
    }

    /**
     * Ajouter un produit au panier
     */
    public function addToCart(int $id_utilisateur, int $id_produit, int $quantite = 1): array {
        try {
            // Validation des données
            $validationErrors = $this->validatePanierData($id_utilisateur, $id_produit, $quantite);
            
            if (!empty($validationErrors)) {
                return [
                    'success' => false,
                    'message' => 'Données invalides',
                    'errors' => $validationErrors
                ];
            }

            // Vérifier le stock disponible
            $stockCheck = $this->checkStock($id_produit, $quantite);
            if (!$stockCheck['success']) {
                return $stockCheck;
            }

            $panier = new Panier(null, $id_utilisateur, $id_produit, $quantite);
            $result = $this->crudPanier->create($panier);
            
            if ($result) {
                return [
                    'success' => true,
                    'message' => 'Produit ajouté au panier avec succès',
                    'data' => $panier
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Erreur lors de l\'ajout au panier'
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
     * Récupérer le panier d'un utilisateur
     */
    public function getCart(int $id_utilisateur): array {
        try {
            $panierItems = $this->crudPanier->getByUtilisateur($id_utilisateur);
            $cartDetails = $this->getCartDetails($panierItems);
            
            return [
                'success' => true,
                'data' => $panierItems,
                'details' => $cartDetails,
                'count' => count($panierItems)
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Mettre à jour la quantité d'un produit dans le panier
     */
    public function updateQuantity(int $id_panier, int $quantite): array {
        try {
            // Vérifier si l'item existe
            $panierItem = $this->crudPanier->getById($id_panier);
            
            if (!$panierItem) {
                return [
                    'success' => false,
                    'message' => 'Article non trouvé dans le panier'
                ];
            }

            // Validation de la quantité
            if ($quantite <= 0) {
                return [
                    'success' => false,
                    'message' => 'La quantité doit être positive'
                ];
            }

            // Vérifier le stock
            $stockCheck = $this->checkStock($panierItem->getIdProduit(), $quantite);
            if (!$stockCheck['success']) {
                return $stockCheck;
            }

            if ($this->crudPanier->updateQuantite($id_panier, $quantite)) {
                $updatedItem = $this->crudPanier->getById($id_panier);
                return [
                    'success' => true,
                    'message' => 'Quantité mise à jour avec succès',
                    'data' => $updatedItem
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Erreur lors de la mise à jour de la quantité'
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
     * Augmenter la quantité d'un produit
     */
    public function increaseQuantity(int $id_panier, int $quantite = 1): array {
        try {
            $panierItem = $this->crudPanier->getById($id_panier);
            
            if (!$panierItem) {
                return [
                    'success' => false,
                    'message' => 'Article non trouvé dans le panier'
                ];
            }

            $nouvelleQuantite = $panierItem->getQuantite() + $quantite;
            
            // Vérifier le stock
            $stockCheck = $this->checkStock($panierItem->getIdProduit(), $nouvelleQuantite);
            if (!$stockCheck['success']) {
                return $stockCheck;
            }

            return $this->updateQuantity($id_panier, $nouvelleQuantite);
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Diminuer la quantité d'un produit
     */
    public function decreaseQuantity(int $id_panier, int $quantite = 1): array {
        try {
            $panierItem = $this->crudPanier->getById($id_panier);
            
            if (!$panierItem) {
                return [
                    'success' => false,
                    'message' => 'Article non trouvé dans le panier'
                ];
            }

            $nouvelleQuantite = $panierItem->getQuantite() - $quantite;
            
            if ($nouvelleQuantite <= 0) {
                return $this->removeFromCart($id_panier);
            }

            return $this->updateQuantity($id_panier, $nouvelleQuantite);
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Supprimer un produit du panier
     */
    public function removeFromCart(int $id_panier): array {
        try {
            // Vérifier si l'item existe
            $panierItem = $this->crudPanier->getById($id_panier);
            
            if (!$panierItem) {
                return [
                    'success' => false,
                    'message' => 'Article non trouvé dans le panier'
                ];
            }

            if ($this->crudPanier->delete($id_panier)) {
                return [
                    'success' => true,
                    'message' => 'Produit retiré du panier avec succès',
                    'data' => $panierItem
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
     * Vider le panier d'un utilisateur
     */
    public function clearCart(int $id_utilisateur): array {
        try {
            $panierItems = $this->crudPanier->getByUtilisateur($id_utilisateur);
            
            if (empty($panierItems)) {
                return [
                    'success' => true,
                    'message' => 'Le panier est déjà vide',
                    'count' => 0
                ];
            }

            if ($this->crudPanier->deleteByUtilisateur($id_utilisateur)) {
                return [
                    'success' => true,
                    'message' => 'Panier vidé avec succès',
                    'items_removed' => count($panierItems)
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Erreur lors de la suppression du panier'
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
     * Obtenir le total du panier
     */
    public function getCartTotal(int $id_utilisateur): array {
        try {
            $panierItems = $this->crudPanier->getByUtilisateur($id_utilisateur);
            $cartDetails = $this->getCartDetails($panierItems);
            
            return [
                'success' => true,
                'data' => $cartDetails,
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
     * Vérifier la disponibilité des produits dans le panier
     */
    public function checkCartAvailability(int $id_utilisateur): array {
        try {
            $panierItems = $this->crudPanier->getByUtilisateur($id_utilisateur);
            $unavailableItems = [];
            $availableItems = [];

            foreach ($panierItems as $item) {
                $stockCheck = $this->checkStock($item->getIdProduit(), $item->getQuantite());
                
                if (!$stockCheck['success']) {
                    $unavailableItems[] = [
                        'panier_item' => $item,
                        'reason' => $stockCheck['message'],
                        'available_stock' => $stockCheck['available_stock'] ?? 0
                    ];
                } else {
                    $availableItems[] = $item;
                }
            }

            return [
                'success' => true,
                'available_items' => $availableItems,
                'unavailable_items' => $unavailableItems,
                'all_available' => empty($unavailableItems),
                'total_items' => count($panierItems)
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Synchroniser le panier (ajouter plusieurs produits)
     */
    public function syncCart(int $id_utilisateur, array $items): array {
        try {
            $results = [];
            $errors = [];

            foreach ($items as $item) {
                if (!isset($item['id_produit']) || !isset($item['quantite'])) {
                    $errors[] = [
                        'item' => $item,
                        'error' => 'Données manquantes (id_produit ou quantite)'
                    ];
                    continue;
                }

                $result = $this->addToCart(
                    $id_utilisateur,
                    $item['id_produit'],
                    $item['quantite']
                );

                if (!$result['success']) {
                    $errors[] = [
                        'item' => $item,
                        'error' => $result['message']
                    ];
                } else {
                    $results[] = $result['data'];
                }
            }

            return [
                'success' => true,
                'added_items' => $results,
                'errors' => $errors,
                'total_added' => count($results),
                'total_errors' => count($errors)
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Valider les données du panier
     */
    private function validatePanierData(int $id_utilisateur, int $id_produit, int $quantite): array {
        $errors = [];

        if ($id_utilisateur <= 0) {
            $errors[] = 'ID utilisateur invalide';
        }

        if ($id_produit <= 0) {
            $errors[] = 'ID produit invalide';
        }

        if ($quantite <= 0) {
            $errors[] = 'La quantité doit être positive';
        }

        if ($quantite > 100) {
            $errors[] = 'La quantité ne peut pas dépasser 100 unités';
        }

        return $errors;
    }

    /**
     * Vérifier le stock disponible
     */
    private function checkStock(int $id_produit, int $quantite): array {
        try {
            $produit = $this->crudProduit->getById($id_produit);
            
            if (!$produit) {
                return [
                    'success' => false,
                    'message' => 'Produit non trouvé',
                    'available_stock' => 0
                ];
            }

            $stock = $produit->getStock();
            
            if ($stock < $quantite) {
                return [
                    'success' => false,
                    'message' => 'Stock insuffisant',
                    'available_stock' => $stock,
                    'requested_quantity' => $quantite
                ];
            }

            return [
                'success' => true,
                'available_stock' => $stock
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur de vérification du stock: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Obtenir les détails du panier avec les informations produits
     */
    private function getCartDetails(array $panierItems): array {
        $total = 0;
        $total_items = 0;
        $items = [];

        foreach ($panierItems as $item) {
            try {
                $produit = $this->crudProduit->getById($item->getIdProduit());
                
                if ($produit) {
                    $prix = $produit->getPrix();
                    $sous_total = $prix * $item->getQuantite();
                    
                    $items[] = [
                        'panier_item' => $item,
                        'produit' => [
                            'id' => $produit->getIdProduit(),
                            'nom' => $produit->getNom(),
                            'prix' => $prix,
                            'photo' => $produit->getPhoto(),
                            'stock' => $produit->getStock()
                        ],
                        'sous_total' => $sous_total
                    ];

                    $total += $sous_total;
                    $total_items += $item->getQuantite();
                }
            } catch (Exception $e) {
                // Continuer avec les autres items en cas d'erreur
                continue;
            }
        }

        return [
            'items' => $items,
            'total' => $total,
            'total_items' => $total_items,
            'count' => count($items)
        ];
    }

    /**
     * Obtenir le nombre total d'articles dans le panier
     */
    public function getCartItemsCount(int $id_utilisateur): array {
        try {
            $count = $this->crudPanier->getTotalQuantiteByUtilisateur($id_utilisateur);
            
            return [
                'success' => true,
                'count' => (int)$count,
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
     * Transférer le panier d'un utilisateur à un autre (en cas de fusion de comptes)
     */
    public function transferCart(int $from_user_id, int $to_user_id): array {
        try {
            if ($from_user_id === $to_user_id) {
                return [
                    'success' => false,
                    'message' => 'Les IDs utilisateur doivent être différents'
                ];
            }

            $panierItems = $this->crudPanier->getByUtilisateur($from_user_id);
            
            if (empty($panierItems)) {
                return [
                    'success' => true,
                    'message' => 'Aucun article à transférer',
                    'transferred_items' => 0
                ];
            }

            $transferred = 0;
            $errors = [];

            foreach ($panierItems as $item) {
                try {
                    // Vérifier si le produit existe déjà dans le panier destination
                    $existing = $this->crudPanier->getByUtilisateurAndProduit($to_user_id, $item->getIdProduit());
                    
                    if ($existing) {
                        // Fusionner les quantités
                        $newQuantity = $existing->getQuantite() + $item->getQuantite();
                        $this->crudPanier->updateQuantite($existing->getIdPanier(), $newQuantity);
                    } else {
                        // Créer un nouvel item
                        $newItem = new Panier(
                            null,
                            $to_user_id,
                            $item->getIdProduit(),
                            $item->getQuantite()
                        );
                        $this->crudPanier->create($newItem);
                    }

                    // Supprimer l'item original
                    $this->crudPanier->delete($item->getIdPanier());
                    $transferred++;

                } catch (Exception $e) {
                    $errors[] = [
                        'item_id' => $item->getIdPanier(),
                        'error' => $e->getMessage()
                    ];
                }
            }

            return [
                'success' => true,
                'transferred_items' => $transferred,
                'errors' => $errors,
                'total_items' => count($panierItems)
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ];
        }
    }
}