<?php
require_once __DIR__ . '/../modele/favori.php';
require_once __DIR__ . '/../modele/formation.php';
require_once __DIR__ . '/../modele/produit.php';


class FavoriController {
    private $crudFavori;
    private $crudFormation;
    private $crudProduit;

    public function __construct() {
        $this->crudFavori = new FavoriCRUD();
        $this->crudFormation = new RequeteFormation();
        $this->crudProduit = new CRUDProduit();
    }

    /**
     * Ajouter un élément aux favoris
     */
    public function addToFavorites(int $id_utilisateur, string $type, int $id_element): array {
        try {
            // Validation des données
            $validationErrors = $this->validateFavoriData($id_utilisateur, $type, $id_element);
            
            if (!empty($validationErrors)) {
                return [
                    'success' => false,
                    'message' => 'Données invalides',
                    'errors' => $validationErrors
                ];
            }

            // Vérifier si l'élément existe
            $elementExists = $this->checkElementExists($type, $id_element);
            if (!$elementExists['success']) {
                return $elementExists;
            }

            // Vérifier si l'élément est déjà en favori
            if ($this->crudFavori->exists($id_utilisateur, $id_element, $type)) {
                return [
                    'success' => false,
                    'message' => 'Cet élément est déjà dans vos favoris'
                ];
            }

            // Préparer les données selon le type
            $id_formation = $type === 'formation' ? $id_element : null;
            $id_produit = $type === 'produit' ? $id_element : null;

            $date_ajout = date('Y-m-d H:i:s');
            $favori = new Favori(null, $id_utilisateur, $id_formation, $id_produit, $date_ajout);
            
            $result = $this->crudFavori->create($favori);
            
            if ($result) {
                return [
                    'success' => true,
                    'message' => 'Élément ajouté aux favoris avec succès',
                    'data' => $favori
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Erreur lors de l\'ajout aux favoris'
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
     * Retirer un élément des favoris
     */
    public function removeFromFavorites(int $id_utilisateur, string $type, int $id_element): array {
        try {
            // Validation des données
            $validationErrors = $this->validateFavoriData($id_utilisateur, $type, $id_element);
            
            if (!empty($validationErrors)) {
                return [
                    'success' => false,
                    'message' => 'Données invalides',
                    'errors' => $validationErrors
                ];
            }

            // Vérifier si l'élément est dans les favoris
            if (!$this->crudFavori->exists($id_utilisateur, $id_element, $type)) {
                return [
                    'success' => false,
                    'message' => 'Cet élément n\'est pas dans vos favoris'
                ];
            }

            if ($this->crudFavori->deleteByElement($id_utilisateur, $id_element, $type)) {
                return [
                    'success' => true,
                    'message' => 'Élément retiré des favoris avec succès',
                    'data' => [
                        'id_utilisateur' => $id_utilisateur,
                        'type' => $type,
                        'id_element' => $id_element
                    ]
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Erreur lors de la suppression des favoris'
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
     * Récupérer tous les favoris d'un utilisateur
     */
    public function getUserFavorites(int $id_utilisateur): array {
        try {
            $favoris = $this->crudFavori->getByUtilisateur($id_utilisateur);
            $favoritesWithDetails = $this->getFavoritesDetails($favoris);
            
            return [
                'success' => true,
                'data' => $favoritesWithDetails,
                'count' => count($favoris),
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
     * Récupérer les formations favorites d'un utilisateur
     */
    public function getUserFormationFavorites(int $id_utilisateur): array {
        try {
            $favoris = $this->crudFavori->getFormationsByUtilisateur($id_utilisateur);
            $favoritesWithDetails = $this->getFormationFavoritesDetails($favoris);
            
            return [
                'success' => true,
                'data' => $favoritesWithDetails,
                'count' => count($favoris),
                'id_utilisateur' => $id_utilisateur,
                'type' => 'formation'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Récupérer les produits favoris d'un utilisateur
     */
    public function getUserProduitFavorites(int $id_utilisateur): array {
        try {
            $favoris = $this->crudFavori->getProduitsByUtilisateur($id_utilisateur);
            $favoritesWithDetails = $this->getProduitFavoritesDetails($favoris);
            
            return [
                'success' => true,
                'data' => $favoritesWithDetails,
                'count' => count($favoris),
                'id_utilisateur' => $id_utilisateur,
                'type' => 'produit'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Vérifier si un élément est dans les favoris
     */
    public function isFavorite(int $id_utilisateur, string $type, int $id_element): array {
        try {
            // Validation des données
            $validationErrors = $this->validateFavoriData($id_utilisateur, $type, $id_element);
            
            if (!empty($validationErrors)) {
                return [
                    'success' => false,
                    'message' => 'Données invalides',
                    'errors' => $validationErrors
                ];
            }

            $isFavorite = $this->crudFavori->exists($id_utilisateur, $id_element, $type);
            
            return [
                'success' => true,
                'is_favorite' => $isFavorite,
                'data' => [
                    'id_utilisateur' => $id_utilisateur,
                    'type' => $type,
                    'id_element' => $id_element
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
     * Basculer l'état favori d'un élément
     */
    public function toggleFavorite(int $id_utilisateur, string $type, int $id_element): array {
        try {
            // Vérifier l'état actuel
            $checkResult = $this->isFavorite($id_utilisateur, $type, $id_element);
            
            if (!$checkResult['success']) {
                return $checkResult;
            }

            if ($checkResult['is_favorite']) {
                // Retirer des favoris
                return $this->removeFromFavorites($id_utilisateur, $type, $id_element);
            } else {
                // Ajouter aux favoris
                return $this->addToFavorites($id_utilisateur, $type, $id_element);
            }
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Supprimer tous les favoris d'un utilisateur
     */
    public function clearAllFavorites(int $id_utilisateur): array {
        try {
            $favoris = $this->crudFavori->getByUtilisateur($id_utilisateur);
            
            if (empty($favoris)) {
                return [
                    'success' => true,
                    'message' => 'Aucun favori à supprimer',
                    'count' => 0
                ];
            }

            $deletedCount = 0;
            $errors = [];

            foreach ($favoris as $favori) {
                try {
                    if ($this->crudFavori->delete($favori->getIdFavori())) {
                        $deletedCount++;
                    }
                } catch (Exception $e) {
                    $errors[] = [
                        'favori_id' => $favori->getIdFavori(),
                        'error' => $e->getMessage()
                    ];
                }
            }

            return [
                'success' => true,
                'message' => 'Favoris supprimés avec succès',
                'deleted_count' => $deletedCount,
                'errors' => $errors,
                'total' => count($favoris)
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Obtenir les statistiques des favoris
     */
    public function getFavoritesStats(int $id_utilisateur): array {
        try {
            $formations = $this->crudFavori->getFormationsByUtilisateur($id_utilisateur);
            $produits = $this->crudFavori->getProduitsByUtilisateur($id_utilisateur);
            
            $stats = [
                'total' => count($formations) + count($produits),
                'formations' => count($formations),
                'produits' => count($produits),
                'par_mois' => $this->getFavoritesByMonth($id_utilisateur)
            ];

            return [
                'success' => true,
                'data' => $stats,
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
     * Rechercher dans les favoris
     */
    public function searchFavorites(int $id_utilisateur, string $searchTerm, ?string $type = null): array {
        try {
            $allFavorites = $this->crudFavori->getByUtilisateur($id_utilisateur);
            $searchTerm = strtolower(trim($searchTerm));
            
            $filteredFavorites = array_filter($allFavorites, function($favori) use ($searchTerm, $type) {
                $match = true;
                
                // Filtre par type
                if ($type !== null) {
                    if ($type === 'formation' && !$favori->isFormationFavorite()) {
                        $match = false;
                    }
                    if ($type === 'produit' && !$favori->isProduitFavorite()) {
                        $match = false;
                    }
                }
                
                return $match;
            });

            // Pour une recherche textuelle, nous devons charger les détails
            if (!empty($searchTerm)) {
                $favoritesWithDetails = $this->getFavoritesDetails($filteredFavorites);
                $filteredFavorites = array_filter($favoritesWithDetails, function($item) use ($searchTerm) {
                    $nom = strtolower($item['details']['nom'] ?? '');
                    $description = strtolower($item['details']['description'] ?? '');
                    
                    return stripos($nom, $searchTerm) !== false || 
                           stripos($description, $searchTerm) !== false;
                });
            } else {
                $favoritesWithDetails = $this->getFavoritesDetails($filteredFavorites);
            }
            
            return [
                'success' => true,
                'data' => array_values($favoritesWithDetails),
                'count' => count($favoritesWithDetails),
                'search_term' => $searchTerm,
                'filters' => ['type' => $type]
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Valider les données des favoris
     */
    private function validateFavoriData(int $id_utilisateur, string $type, int $id_element): array {
        $errors = [];

        if ($id_utilisateur <= 0) {
            $errors[] = 'ID utilisateur invalide';
        }

        if (!in_array($type, ['formation', 'produit'])) {
            $errors[] = 'Type invalide. Doit être "formation" ou "produit"';
        }

        if ($id_element <= 0) {
            $errors[] = 'ID élément invalide';
        }

        return $errors;
    }

    /**
     * Vérifier si un élément existe
     */
    private function checkElementExists(string $type, int $id_element): array {
        try {
            if ($type === 'formation') {
                $element = $this->crudFormation->getById($id_element);
            } else {
                $element = $this->crudProduit->getById($id_element);
            }

            if (!$element) {
                return [
                    'success' => false,
                    'message' => ucfirst($type) . ' non trouvé'
                ];
            }

            return [
                'success' => true,
                'element' => $element
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur de vérification: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Obtenir les détails des favoris
     */
    private function getFavoritesDetails(array $favoris): array {
        $result = [];

        foreach ($favoris as $favori) {
            try {
                $details = null;
                $type = $favori->getType();
                $id_element = $favori->getAssociatedId();

                if ($type === 'formation') {
                    $formation = $this->crudFormation->getById($id_element);
                    if ($formation) {
                        $details = [
                            'id' => $formation->getIdFormation(),
                            'nom' => $formation->getTitre(),
                            'description' => $formation->getDescription(),
                            'prix' => $formation->getPrix(),
                            'photo' => $formation->getPhoto(),
                            'type' => 'formation'
                        ];
                    }
                } else {
                    $produit = $this->crudProduit->getById($id_element);
                    if ($produit) {
                        $details = [
                            'id' => $produit->getIdProduit(),
                            'nom' => $produit->getNom(),
                            'description' => $produit->getDescription(),
                            'prix' => $produit->getPrix(),
                            'photo' => $produit->getPhoto(),
                            'stock' => $produit->getStock(),
                            'type' => 'produit'
                        ];
                    }
                }

                if ($details) {
                    $result[] = [
                        'favori' => $favori,
                        'details' => $details
                    ];
                }
            } catch (Exception $e) {
                // Continuer avec les autres favoris en cas d'erreur
                continue;
            }
        }

        return $result;
    }

    /**
     * Obtenir les détails des formations favorites
     */
    private function getFormationFavoritesDetails(array $favoris): array {
        $result = [];

        foreach ($favoris as $favori) {
            try {
                $formation = $this->crudFormation->getById($favori->getIdFormation());
                if ($formation) {
                    $result[] = [
                        'favori' => $favori,
                        'formation' => $formation
                    ];
                }
            } catch (Exception $e) {
                continue;
            }
        }

        return $result;
    }

    /**
     * Obtenir les détails des produits favoris
     */
    private function getProduitFavoritesDetails(array $favoris): array {
        $result = [];

        foreach ($favoris as $favori) {
            try {
                $produit = $this->crudProduit->getById($favori->getIdProduit());
                if ($produit) {
                    $result[] = [
                        'favori' => $favori,
                        'produit' => $produit
                    ];
                }
            } catch (Exception $e) {
                continue;
            }
        }

        return $result;
    }

    /**
     * Obtenir les favoris par mois
     */
    private function getFavoritesByMonth(int $id_utilisateur): array {
        try {
            $favoris = $this->crudFavori->getByUtilisateur($id_utilisateur);
            $monthlyData = [];

            foreach ($favoris as $favori) {
                $month = date('Y-m', strtotime($favori->getDateAjout()));
                
                if (!isset($monthlyData[$month])) {
                    $monthlyData[$month] = [
                        'formations' => 0,
                        'produits' => 0,
                        'total' => 0
                    ];
                }

                if ($favori->isFormationFavorite()) {
                    $monthlyData[$month]['formations']++;
                } else {
                    $monthlyData[$month]['produits']++;
                }
                $monthlyData[$month]['total']++;
            }

            return $monthlyData;
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Obtenir les éléments les plus populaires (les plus mis en favori)
     */
    public function getPopularItems(string $type, int $limit = 10): array {
        try {
            if (!in_array($type, ['formation', 'produit'])) {
                return [
                    'success' => false,
                    'message' => 'Type invalide. Doit être "formation" ou "produit"'
                ];
            }

            $allFavorites = $this->crudFavori->getAll();
            $popularity = [];

            foreach ($allFavorites as $favori) {
                if (($type === 'formation' && $favori->isFormationFavorite()) ||
                    ($type === 'produit' && $favori->isProduitFavorite())) {
                    
                    $id_element = $favori->getAssociatedId();
                    
                    if (!isset($popularity[$id_element])) {
                        $popularity[$id_element] = 0;
                    }
                    $popularity[$id_element]++;
                }
            }

            // Trier par popularité décroissante
            arsort($popularity);
            $popularItems = array_slice($popularity, 0, $limit, true);

            // Récupérer les détails des éléments populaires
            $result = [];
            foreach ($popularItems as $id_element => $count) {
                try {
                    if ($type === 'formation') {
                        $element = $this->crudFormation->getById($id_element);
                    } else {
                        $element = $this->crudProduit->getById($id_element);
                    }

                    if ($element) {
                        $result[] = [
                            'element' => $element,
                            'favorite_count' => $count
                        ];
                    }
                } catch (Exception $e) {
                    continue;
                }
            }

            return [
                'success' => true,
                'data' => $result,
                'type' => $type,
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
?>