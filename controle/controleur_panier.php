<?php
require_once __DIR__ . '/../modele/panier.php';
require_once __DIR__ . '/../modele/panier_formation.php';
require_once __DIR__ . '/../modele/produit.php'; // Pour vérifier le stock
require_once __DIR__ . '/../modele/formation.php'; // Pour les formations

class PanierController {
    private $crudPanier;
    private $crudPanierFormation;
    private $crudProduit;
    private $crudFormation;

    public function __construct() {
        $this->crudPanier = new PanierCRUD();
        $this->crudPanierFormation = new PanierFormationCRUD();
        $this->crudProduit = new CRUDProduit();
        $this->crudFormation = new RequeteFormation();
    }

    /**
     * Ajouter un produit au panier
     */
    public function addToCart(int $id_utilisateur, string $type, int $id_element, int $quantite = 1): array {
        // Vérifier le type d'élément (produit ou formation)
        if ($type === 'formation') {
            return $this->addFormationToCart($id_utilisateur, $id_element);
        } else if ($type === 'produit') {
            return $this->addProductToCart($id_utilisateur, $id_element, $quantite);
        } else {
            return [
                'success' => false,
                'message' => 'Type d\'élément non valide. Doit être "produit" ou "formation"'
            ];
        }
    }

    /**
     * Ajouter une formation au panier
     */
    private function addFormationToCart(int $id_utilisateur, int $id_formation): array {
        try {
            // Vérifier si la formation existe
            $formation = $this->crudFormation->getFormationById($id_formation);
            if (!$formation) {
                return [
                    'success' => false,
                    'message' => 'Formation non trouvée'
                ];
            }

            // Vérifier si la formation est déjà dans le panier
            if ($this->crudPanierFormation->exists($id_utilisateur, $id_formation)) {
                return [
                    'success' => false,
                    'message' => 'Cette formation est déjà dans votre panier'
                ];
            }

            // Créer et sauvegarder la formation dans le panier
            $panierFormation = new PanierFormation(null, $id_utilisateur, $id_formation);
            $result = $this->crudPanierFormation->create($panierFormation);
            
            if ($result) {
                return [
                    'success' => true,
                    'message' => 'Formation ajoutée au panier avec succès',
                    'data' => $panierFormation
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Erreur lors de l\'ajout de la formation au panier'
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
     * Ajouter un produit au panier (méthode interne)
     */
    private function addProductToCart(int $id_utilisateur, int $id_produit, int $quantite = 1): array {
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
     * Récupérer le panier d'un utilisateur avec les détails complets
     * 
     * @param int $id_utilisateur L'ID de l'utilisateur
     * @param bool $details Si true, retourne les détails complets des produits/formations
     * @return array Tableau contenant les éléments du panier et les détails
     */
    public function getCart(int $id_utilisateur, bool $details = true): array {
        try {
            // Récupérer les produits du panier
            $panierProduits = $this->crudPanier->getByUtilisateur($id_utilisateur);
            $produitsDetails = [];
            $totalProduits = 0;
            $sousTotalProduits = 0;
            
            // Récupérer les détails de chaque produit si demandé
            if ($details) {
                foreach ($panierProduits as $item) {
                    $produit = $this->crudProduit->getProduitById($item->getIdProduit());
                    if ($produit) {
                        $sousTotal = $produit->getPrix() * $item->getQuantite();
                        $produitsDetails[] = [
                            'id_panier' => $item->getIdPanier(),
                            'type' => 'produit',
                            'id_element' => $produit->getIdProduit(),
                            'nom' => $produit->getNom(),
                            'description' => $produit->getDescription(),
                            'prix' => $produit->getPrix(),
                            'photo' => $produit->getPhoto(),
                            'quantite' => $item->getQuantite(),
                            'sous_total' => $sousTotal,
                            'stock' => $produit->getStock()
                        ];
                        $totalProduits += $item->getQuantite();
                        $sousTotalProduits += $sousTotal;
                    }
                }
            } else {
                $totalProduits = array_reduce($panierProduits, function($carry, $item) {
                    return $carry + $item->getQuantite();
                }, 0);
            }
            
            // Récupérer les formations du panier
            $panierFormations = $this->crudPanierFormation->getByUtilisateur($id_utilisateur);
            $formationsDetails = [];
            $sousTotalFormations = 0;
            
            // Récupérer les détails de chaque formation si demandé
            if ($details) {
                foreach ($panierFormations as $item) {
                    $formation = $this->crudFormation->getFormationById($item->getIdFormation());
                    if ($formation) {
                        $sousTotal = $formation->getPrix(); // Les formations ont une quantité de 1
                        $formationsDetails[] = [
                            'id_panier_formation' => $item->getIdPanierFormation(),
                            'type' => 'formation',
                            'id_element' => $formation->getIdFormation(),
                            'titre' => $formation->getTitre(),
                            'description' => $formation->getDescription(),
                            'prix' => $formation->getPrix(),
                            'photo' => $formation->getPhoto(),
                            'duree' => $formation->getDuree(),
                            'debut_formation' => $formation->getDebutFormation(),
                            'sous_total' => $sousTotal
                        ];
                        $sousTotalFormations += $sousTotal;
                    }
                }
            }
            
            $totalItems = $totalProduits + count($panierFormations);
            $grandTotal = $sousTotalProduits + $sousTotalFormations;
            
            // Préparer la réponse en fonction du niveau de détail demandé
            if ($details) {
                return [
                    'success' => true,
                    'data' => array_merge($produitsDetails, $formationsDetails),
                    'details' => [
                        'produits' => $produitsDetails,
                        'formations' => $formationsDetails,
                        'totaux' => [
                            'total_items' => $totalItems,
                            'total_produits' => $totalProduits,
                            'total_formations' => count($formationsDetails),
                            'sous_total_produits' => $sousTotalProduits,
                            'sous_total_formations' => $sousTotalFormations,
                            'grand_total' => $grandTotal
                        ]
                    ],
                    'count' => $totalItems
                ];
            } else {
                return [
                    'success' => true,
                    'data' => [
                        'produits' => $panierProduits,
                        'formations' => $panierFormations
                    ],
                    'count' => $totalItems
                ];
            }
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur lors de la récupération du panier: ' . $e->getMessage(),
                'count' => 0
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
    public function removeFromCart(int $id_utilisateur, string $type, int $id_element): array {
        try {
            if ($type === 'formation') {
                // Vérifier que la formation appartient bien à l'utilisateur
                $panierFormation = $this->crudPanierFormation->getById($id_element);
                if (!$panierFormation || $panierFormation->getIdUtilisateur() != $id_utilisateur) {
                    return [
                        'success' => false,
                        'message' => 'Formation non trouvée ou accès non autorisé'
                    ];
                }

                $result = $this->crudPanierFormation->delete($id_element);
                $message = 'Formation retirée du panier avec succès';
            } else if ($type === 'produit') {
                // Vérifier que le produit appartient bien à l'utilisateur
                $panier = $this->crudPanier->getById($id_element);
                if (!$panier || $panier->getIdUtilisateur() != $id_utilisateur) {
                    return [
                        'success' => false,
                        'message' => 'Produit non trouvé ou accès non autorisé'
                    ];
                }

                $result = $this->crudPanier->delete($id_element);
                $message = 'Produit retiré du panier avec succès';
            } else {
                return [
                    'success' => false,
                    'message' => 'Type d\'élément non valide. Doit être "produit" ou "formation"'
                ];
            }

            if ($result) {
                return [
                    'success' => true,
                    'message' => $message
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Erreur lors du retrait de l\'élément du panier'
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
            $panierFormations = $this->crudPanierFormation->getByUtilisateur($id_utilisateur);
            
            if (empty($panierItems) && empty($panierFormations)) {
                return [
                    'success' => true,
                    'message' => 'Le panier est déjà vide',
                    'count' => 0
                ];
            }

            if ($this->crudPanier->deleteByUtilisateur($id_utilisateur) && $this->crudPanierFormation->deleteByUtilisateur($id_utilisateur)) {
                return [
                    'success' => true,
                    'message' => 'Panier vidé avec succès',
                    'items_removed' => count($panierItems) + count($panierFormations)
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
     * Obtenir le total du panier avec les détails des produits et formations
     * 
     * @param int $id_utilisateur L'ID de l'utilisateur
     * @return array Tableau contenant les totaux et détails du panier
     */
    public function getCartTotal(int $id_utilisateur): array {
        try {
            // Récupérer les produits du panier avec leurs détails
            $panierProduits = $this->crudPanier->getByUtilisateur($id_utilisateur);
            $produitsDetails = [];
            $sousTotalProduits = 0;
            $totalProduits = 0;
            
            // Calculer le total pour les produits
            foreach ($panierProduits as $item) {
                $produit = $this->crudProduit->getById($item->getIdProduit());
                if ($produit) {
                    $sousTotal = $produit->getPrix() * $item->getQuantite();
                    $sousTotalProduits += $sousTotal;
                    $totalProduits += $item->getQuantite();
                    
                    $produitsDetails[] = [
                        'id_panier' => $item->getIdPanier(),
                        'type' => 'produit',
                        'id_element' => $produit->getIdProduit(),
                        'nom' => $produit->getNom(),
                        'prix_unitaire' => $produit->getPrix(),
                        'quantite' => $item->getQuantite(),
                        'sous_total' => $sousTotal,
                        'photo' => $produit->getPhoto(),
                        'stock' => $produit->getStock()
                    ];
                }
            }
            
            // Récupérer les formations du panier avec leurs détails
            $panierFormations = $this->crudPanierFormation->getByUtilisateur($id_utilisateur);
            $formationsDetails = [];
            $sousTotalFormations = 0;
            
            // Calculer le total pour les formations
            foreach ($panierFormations as $item) {
                $formation = $this->crudFormation->getFormationById($item->getIdFormation());
                if ($formation) {
                    $sousTotal = $formation->getPrix(); // Les formations ont une quantité de 1
                    $sousTotalFormations += $sousTotal;
                    
                    $formationsDetails[] = [
                        'id_panier_formation' => $item->getIdPanierFormation(),
                        'type' => 'formation',
                        'id_element' => $formation->getIdFormation(),
                        'titre' => $formation->getTitre(),
                        'prix' => $formation->getPrix(),
                        'photo' => $formation->getPhoto(),
                        'duree' => $formation->getDuree(),
                        'debut_formation' => $formation->getDebutFormation(),
                        'sous_total' => $sousTotal
                    ];
                }
            }
            
            // Calculer les totaux généraux
            $totalItems = $totalProduits + count($panierFormations);
            $grandTotal = $sousTotalProduits + $sousTotalFormations;
            
            return [
                'success' => true,
                'data' => [
                    'produits' => $produitsDetails,
                    'formations' => $formationsDetails,
                    'totaux' => [
                        'total_items' => $totalItems,
                        'total_produits' => $totalProduits,
                        'total_formations' => count($panierFormations),
                        'sous_total_produits' => $sousTotalProduits,
                        'sous_total_formations' => $sousTotalFormations,
                        'grand_total' => $grandTotal
                    ]
                ],
                'id_utilisateur' => $id_utilisateur
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur lors du calcul du total du panier: ' . $e->getMessage(),
                'id_utilisateur' => $id_utilisateur
            ];
        }
    }

    /**
     * Vérifier la disponibilité des produits et formations dans le panier
     * 
     * Pour les produits, vérifie le stock disponible.
     * Pour les formations, vérifie qu'elles sont toujours actives et non complètes.
     * 
     * @param int $id_utilisateur L'ID de l'utilisateur
     * @return array Tableau contenant les éléments disponibles et non disponibles
     */
    public function checkCartAvailability(int $id_utilisateur): array {
        try {
            // Récupérer les produits du panier
            $panierProduits = $this->crudPanier->getByUtilisateur($id_utilisateur);
            $unavailableItems = [];
            $availableItems = [];
            $totalItems = 0;
            
            // Vérifier la disponibilité des produits (stock)
            foreach ($panierProduits as $item) {
                $produit = $this->crudProduit->getById($item->getIdProduit());
                $totalItems += $item->getQuantite();
                
                if (!$produit) {
                    $unavailableItems[] = [
                        'id_panier' => $item->getIdPanier(),
                        'type' => 'produit',
                        'id_element' => $item->getIdProduit(),
                        'nom' => 'Produit inconnu',
                        'quantite' => $item->getQuantite(),
                        'reason' => 'Ce produit n\'existe plus',
                        'available_stock' => 0
                    ];
                    continue;
                }
                
                $stockCheck = $this->checkStock($item->getIdProduit(), $item->getQuantite());
                $itemData = [
                    'id_panier' => $item->getIdPanier(),
                    'type' => 'produit',
                    'id_element' => $produit->getIdProduit(),
                    'nom' => $produit->getNom(),
                    'prix_unitaire' => $produit->getPrix(),
                    'quantite' => $item->getQuantite(),
                    'sous_total' => $produit->getPrix() * $item->getQuantite(),
                    'photo' => $produit->getPhoto()
                ];
                
                if (!$stockCheck['success']) {
                    $unavailableItems[] = array_merge($itemData, [
                        'reason' => $stockCheck['message'],
                        'available_stock' => $stockCheck['available_stock'] ?? 0
                    ]);
                } else {
                    $availableItems[] = array_merge($itemData, [
                        'available_stock' => $stockCheck['available_stock']
                    ]);
                }
            }
            
            // Récupérer les formations du panier
            $panierFormations = $this->crudPanierFormation->getByUtilisateur($id_utilisateur);
            
            // Vérifier la disponibilité des formations
            foreach ($panierFormations as $item) {
                $formation = $this->crudFormation->getFormationById($item->getIdFormation());
                $totalItems++; // Chaque formation compte pour 1 élément
                
                if (!$formation) {
                    $unavailableItems[] = [
                        'id_panier_formation' => $item->getIdPanierFormation(),
                        'type' => 'formation',
                        'id_element' => $item->getIdFormation(),
                        'titre' => 'Formation inconnue',
                        'reason' => 'Cette formation n\'existe plus',
                        'available' => false
                    ];
                    continue;
                }
                
                $itemData = [
                    'id_panier_formation' => $item->getIdPanierFormation(),
                    'type' => 'formation',
                    'id_element' => $formation->getIdFormation(),
                    'titre' => $formation->getTitre(),
                    'prix' => $formation->getPrix(),
                    'sous_total' => $formation->getPrix(),
                    'photo' => $formation->getPhoto(),
                    'duree' => $formation->getDuree(),
                    'debut_formation' => $formation->getDebutFormation()
                ];
                
                // Vérifier si la formation est toujours disponible
                $now = new DateTime();
                $debutFormation = new DateTime($formation->getDebutFormation());
                $finFormation = clone $debutFormation;
                $finFormation->add(new DateInterval('P' . $formation->getDuree() . 'D'));
                
                if ($now > $finFormation) {
                    $unavailableItems[] = array_merge($itemData, [
                        'reason' => 'Cette formation est terminée',
                        'available' => false
                    ]);
                } else if ($now > $debutFormation) {
                    $unavailableItems[] = array_merge($itemData, [
                        'reason' => 'Cette formation a déjà commencé',
                        'available' => false
                    ]);
                } else {
                    // Vérifier si la formation est complète (si applicable)
                    // Note: Implémentez cette logique selon votre modèle de données
                    $availableItems[] = array_merge($itemData, [
                        'reason' => 'Disponible',
                        'available' => true
                    ]);
                }
            }
            
            // Préparer la réponse
            $response = [
                'success' => true,
                'available_items' => $availableItems,
                'unavailable_items' => $unavailableItems,
                'all_available' => empty($unavailableItems),
                'total_items' => $totalItems,
                'counts' => [
                    'total' => $totalItems,
                    'available' => count($availableItems),
                    'unavailable' => count($unavailableItems),
                    'produits' => count($panierProduits),
                    'formations' => count($panierFormations)
                ]
            ];
            
            return $response;
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur lors de la vérification de la disponibilité du panier: ' . $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ];
        }
    }

    /**
     * Synchroniser le panier (ajouter plusieurs produits et/ou formations)
     * 
     * @param int $id_utilisateur L'ID de l'utilisateur
     * @param array $items Tableau d'éléments à ajouter au panier
     *        Format pour un produit: ['type' => 'produit', 'id_element' => X, 'quantite' => Y]
     *        Format pour une formation: ['type' => 'formation', 'id_element' => X]
     * @return array Résultat de la synchronisation
     */
    public function syncCart(int $id_utilisateur, array $items): array {
        try {
            $results = [];
            $errors = [];
            $stats = [
                'produits_ajoutes' => 0,
                'produits_erreurs' => 0,
                'formations_ajoutees' => 0,
                'formations_erreurs' => 0
            ];

            foreach ($items as $index => $item) {
                // Validation des champs obligatoires
                if (!isset($item['type']) || !in_array($item['type'], ['produit', 'formation'])) {
                    $errors[] = [
                        'index' => $index,
                        'item' => $item,
                        'error' => 'Type d\'élément invalide. Doit être "produit" ou "formation"'
                    ];
                    continue;
                }

                if (!isset($item['id_element']) || !is_numeric($item['id_element'])) {
                    $errors[] = [
                        'index' => $index,
                        'item' => $item,
                        'error' => 'ID d\'élément manquant ou invalide'
                    ];
                    continue;
                }

                try {
                    if ($item['type'] === 'produit') {
                        // Gestion des produits
                        $quantite = isset($item['quantite']) ? (int)$item['quantite'] : 1;
                        
                        if ($quantite <= 0) {
                            throw new Exception('La quantité doit être positive');
                        }
                        
                        $result = $this->addProductToCart($id_utilisateur, (int)$item['id_element'], $quantite);
                        
                        if ($result['success']) {
                            $stats['produits_ajoutes']++;
                            $results[] = [
                                'type' => 'produit',
                                'id_element' => (int)$item['id_element'],
                                'quantite' => $quantite,
                                'panier_id' => $result['data']->getIdPanier()
                            ];
                        } else {
                            throw new Exception($result['message']);
                        }
                    } else {
                        // Gestion des formations
                        $result = $this->addFormationToCart($id_utilisateur, (int)$item['id_element']);
                        
                        if ($result['success']) {
                            $stats['formations_ajoutees']++;
                            $results[] = [
                                'type' => 'formation',
                                'id_element' => (int)$item['id_element'],
                                'panier_formation_id' => $result['data']->getIdPanierFormation()
                            ];
                        } else {
                            throw new Exception($result['message']);
                        }
                    }
                } catch (Exception $e) {
                    if ($item['type'] === 'produit') {
                        $stats['produits_erreurs']++;
                    } else {
                        $stats['formations_erreurs']++;
                    }
                    
                    $errors[] = [
                        'index' => $index,
                        'item' => $item,
                        'error' => $e->getMessage()
                    ];
                }
            }

            // Préparer la réponse
            $response = [
                'success' => true,
                'added_items' => $results,
                'errors' => $errors,
                'stats' => array_merge($stats, [
                    'total_added' => count($results),
                    'total_errors' => count($errors),
                    'total_processed' => count($items)
                ])
            ];

            // Si des erreurs se sont produites, ajouter un message d'avertissement
            if (!empty($errors)) {
                $response['message'] = sprintf(
                    'Synchronisation partielle du panier. %d éléments ajoutés, %d erreurs.',
                    count($results),
                    count($errors)
                );
            } else {
                $response['message'] = 'Panier synchronisé avec succès';
            }

            return $response;
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur lors de la synchronisation du panier: ' . $e->getMessage(),
                'trace' => $e->getTraceAsString()
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
        $idPrendre = new CRUDProduit();

        try {
            $produit = $idPrendre->getProduitById($id_produit);
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
     * Obtenir le nombre total d'articles dans le panier avec les détails complets
     */
    public function getCartItems(int $id_utilisateur): array {
        try {
            // Récupérer les produits du panier
            $panierProduits = $this->crudPanier->getByUtilisateur($id_utilisateur);
            $produitsDetails = [];
            $totalProduits = 0;
            $sousTotalProduits = 0;
            
            // Récupérer les détails de chaque produit
            foreach ($panierProduits as $item) {
                $produit = $this->crudProduit->getById($item->getIdProduit());
                if ($produit) {
                    $sousTotal = $produit->getPrix() * $item->getQuantite();
                    $produitsDetails[] = [
                        'id_panier' => $item->getIdPanier(),
                        'type' => 'produit',
                        'id_element' => $produit->getIdProduit(),
                        'nom' => $produit->getNom(),
                        'description' => $produit->getDescription(),
                        'prix' => $produit->getPrix(),
                        'photo' => $produit->getPhoto(),
                        'quantite' => $item->getQuantite(),
                        'sous_total' => $sousTotal,
                        'stock' => $produit->getStock()
                    ];
                    $totalProduits += $item->getQuantite();
                    $sousTotalProduits += $sousTotal;
                }
            }
            
            // Récupérer les formations du panier
            $panierFormations = $this->crudPanierFormation->getByUtilisateur($id_utilisateur);
            $formationsDetails = [];
            $sousTotalFormations = 0;
            
            // Récupérer les détails de chaque formation
            foreach ($panierFormations as $item) {
                $formation = $this->crudFormation->getFormationById($item->getIdFormation());
                if ($formation) {
                    $sousTotal = $formation->getPrix(); // Les formations ont une quantité de 1
                    $formationsDetails[] = [
                        'id_panier_formation' => $item->getIdPanierFormation(),
                        'type' => 'formation',
                        'id_element' => $formation->getIdFormation(),
                        'titre' => $formation->getTitre(),
                        'description' => $formation->getDescription(),
                        'prix' => $formation->getPrix(),
                        'photo' => $formation->getPhoto(),
                        'duree' => $formation->getDuree(),
                        'debut_formation' => $formation->getDebutFormation(),
                        'sous_total' => $sousTotal
                    ];
                    $sousTotalFormations += $sousTotal;
                }
            }
            
            $totalItems = $totalProduits + count($formationsDetails);
            $grandTotal = $sousTotalProduits + $sousTotalFormations;
            
            return [
                'success' => true,
                'data' => [
                    'produits' => $produitsDetails,
                    'formations' => $formationsDetails,
                    'totaux' => [
                        'total_items' => $totalItems,
                        'total_produits' => $totalProduits,
                        'total_formations' => count($formationsDetails),
                        'sous_total_produits' => $sousTotalProduits,
                        'sous_total_formations' => $sousTotalFormations,
                        'grand_total' => $grandTotal
                    ]
                ]
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur lors de la récupération du panier: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Transférer le panier d'un utilisateur à un autre (en cas de fusion de comptes)
     * 
     * @param int $from_user_id ID de l'utilisateur source
     * @param int $to_user_id ID de l'utilisateur destination
     * @return array Résultat du transfert avec statistiques
     */
    public function transferCart(int $from_user_id, int $to_user_id): array {
        try {
            if ($from_user_id === $to_user_id) {
                return [
                    'success' => false,
                    'message' => 'Les IDs utilisateur doivent être différents',
                    'code' => 'SAME_USER_IDS'
                ];
            }

            // Initialiser les statistiques
            $stats = [
                'produits' => [
                    'transferred' => 0,
                    'merged' => 0,
                    'errors' => 0,
                    'total' => 0
                ],
                'formations' => [
                    'transferred' => 0,
                    'errors' => 0,
                    'total' => 0
                ]
            ];

            // Transférer les produits du panier
            $panierProduits = $this->crudPanier->getByUtilisateur($from_user_id);
            $stats['produits']['total'] = count($panierProduits);
            $produitsErrors = [];

            foreach ($panierProduits as $item) {
                try {
                    // Vérifier si le produit existe déjà dans le panier destination
                    $existing = $this->crudPanier->getByUtilisateurAndProduit($to_user_id, $item->getIdProduit());
                    
                    if ($existing) {
                        // Fusionner les quantités
                        $newQuantity = $existing->getQuantite() + $item->getQuantite();
                        $this->crudPanier->updateQuantite($existing->getIdPanier(), $newQuantity);
                        $stats['produits']['merged']++;
                    } else {
                        // Créer un nouvel item
                        $newItem = new Panier(
                            null,
                            $to_user_id,
                            $item->getIdProduit(),
                            $item->getQuantite()
                        );
                        $this->crudPanier->create($newItem);
                        $stats['produits']['transferred']++;
                    }

                    // Supprimer l'item original
                    $this->crudPanier->delete($item->getIdPanier());

                } catch (Exception $e) {
                    $stats['produits']['errors']++;
                    $produitsErrors[] = [
                        'item_id' => $item->getIdPanier(),
                        'produit_id' => $item->getIdProduit(),
                        'error' => $e->getMessage(),
                        'type' => 'produit'
                    ];
                }
            }

            // Transférer les formations du panier
            $panierFormations = $this->crudPanierFormation->getByUtilisateur($from_user_id);
            $stats['formations']['total'] = count($panierFormations);
            $formationsErrors = [];

            foreach ($panierFormations as $item) {
                try {
                    // Vérifier si la formation est déjà dans le panier destination
                    $existing = $this->crudPanierFormation->getByUtilisateurAndFormation($to_user_id, $item->getIdFormation());
                    
                    if ($existing) {
                        // Si la formation existe déjà, on ne fait rien (pas de doublons pour les formations)
                        $stats['formations']['errors']++;
                        $formationsErrors[] = [
                            'item_id' => $item->getIdPanierFormation(),
                            'formation_id' => $item->getIdFormation(),
                            'error' => 'Formation déjà présente dans le panier de destination',
                            'type' => 'formation'
                        ];
                        continue;
                    }

                    // Créer un nouvel item de formation
                    $newItem = new PanierFormation(
                        null,
                        $to_user_id,
                        $item->getIdFormation()
                    );
                    
                    if ($this->crudPanierFormation->create($newItem)) {
                        // Supprimer l'item original si le transfert a réussi
                        $this->crudPanierFormation->delete($item->getIdPanierFormation());
                        $stats['formations']['transferred']++;
                    } else {
                        throw new Exception('Échec de la création de l\'élément de formation');
                    }

                } catch (Exception $e) {
                    $stats['formations']['errors']++;
                    $formationsErrors[] = [
                        'item_id' => $item->getIdPanierFormation(),
                        'formation_id' => $item->getIdFormation(),
                        'error' => $e->getMessage(),
                        'type' => 'formation'
                    ];
                }
            }

            // Préparer la réponse
            $totalTransferred = $stats['produits']['transferred'] + $stats['produits']['merged'] + $stats['formations']['transferred'];
            $totalErrors = $stats['produits']['errors'] + $stats['formations']['errors'];
            $allErrors = array_merge($produitsErrors, $formationsErrors);

            $response = [
                'success' => $totalErrors === 0,
                'message' => $totalErrors > 0 
                    ? sprintf('Transfert partiellement réussi avec %d erreur(s)', $totalErrors)
                    : 'Transfert du panier effectué avec succès',
                'stats' => [
                    'total_items' => $stats['produits']['total'] + $stats['formations']['total'],
                    'total_transferred' => $totalTransferred,
                    'total_errors' => $totalErrors,
                    'details' => $stats
                ],
                'errors' => $allErrors
            ];

            // Si aucune erreur, on peut vider le panier source
            if ($totalErrors === 0) {
                $this->crudPanier->deleteByUtilisateur($from_user_id);
                $this->crudPanierFormation->deleteByUtilisateur($from_user_id);
            }

            return $response;

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur lors du transfert du panier: ' . $e->getMessage(),
                'code' => 'TRANSFER_ERROR',
                'trace' => $e->getTraceAsString()
            ];
        }
    }
}