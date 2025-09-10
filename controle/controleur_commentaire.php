<?php
require_once __DIR__ . '/../modele/commentaire.php';

class CommentaireController {
    private $crudCommentaire;

    public function __construct() {
        $this->crudCommentaire = new CommentaireCRUD();
    }

    /**
     * Créer un nouveau commentaire
     */
    public function createCommentaire(int $id_utilisateur, ?int $id_formation, ?int $id_produit, 
                                     string $commentaire, ?int $note = null, ?int $parent_id = null): array {
        try {
            // Validation des données
            $validationErrors = $this->validateCommentaireData($id_utilisateur, $id_formation, $id_produit, $commentaire, $note);
            
            if (!empty($validationErrors)) {
                return [
                    'success' => false,
                    'message' => 'Données invalides',
                    'errors' => $validationErrors
                ];
            }

            // Vérifier si c'est une réponse à un commentaire existant
            if ($parent_id !== null) {
                $parentComment = $this->crudCommentaire->getById($parent_id);
                if (!$parentComment) {
                    return [
                        'success' => false,
                        'message' => 'Le commentaire parent n\'existe pas'
                    ];
                }
            }

            $date_commentaire = date('Y-m-d H:i:s');
            $commentaireObj = new Commentaire(
                null,
                $id_utilisateur,
                $id_formation,
                $id_produit,
                $commentaire,
                $note,
                $date_commentaire,
                'actif',
                $parent_id
            );

            $result = $this->crudCommentaire->create($commentaireObj);
            
            if ($result) {
                return [
                    'success' => true,
                    'message' => 'Commentaire créé avec succès',
                    'data' => $commentaireObj
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Erreur lors de la création du commentaire'
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
     * Récupérer un commentaire par son ID
     */
    public function getCommentaire(int $id_commentaire): array {
        try {
            $commentaire = $this->crudCommentaire->getById($id_commentaire);
            
            if ($commentaire) {
                return [
                    'success' => true,
                    'data' => $commentaire
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Commentaire non trouvé'
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
     * Récupérer tous les commentaires
     */
    public function getAllCommentaires(): array {
        try {
            $commentaires = $this->crudCommentaire->getAll();
            
            return [
                'success' => true,
                'data' => $commentaires,
                'count' => count($commentaires)
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Récupérer les commentaires d'un utilisateur
     */
    public function getCommentairesByUtilisateur(int $id_utilisateur): array {
        try {
            $commentaires = $this->crudCommentaire->getByUtilisateur($id_utilisateur);
            
            return [
                'success' => true,
                'data' => $commentaires,
                'count' => count($commentaires),
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
     * Récupérer les commentaires d'une formation
     */
    public function getCommentairesByFormation(int $id_formation): array {
        try {
            $commentaires = $this->crudCommentaire->getByFormation($id_formation);
            $note_moyenne = $this->crudCommentaire->getAverageNoteByFormation($id_formation);
            
            return [
                'success' => true,
                'data' => $commentaires,
                'count' => count($commentaires),
                'note_moyenne' => (float)$note_moyenne,
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
     * Récupérer les commentaires d'un produit
     */
    public function getCommentairesByProduit(int $id_produit): array {
        try {
            $commentaires = $this->crudCommentaire->getByProduit($id_produit);
            $note_moyenne = $this->crudCommentaire->getAverageNoteByProduit($id_produit);
            
            return [
                'success' => true,
                'data' => $commentaires,
                'count' => count($commentaires),
                'note_moyenne' => (float)$note_moyenne,
                'id_produit' => $id_produit
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Récupérer les réponses à un commentaire
     */
    public function getReponsesCommentaire(int $parent_id): array {
        try {
            $reponses = $this->crudCommentaire->getReplies($parent_id);
            
            return [
                'success' => true,
                'data' => $reponses,
                'count' => count($reponses),
                'parent_id' => $parent_id
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Mettre à jour un commentaire
     */
    public function updateCommentaire(int $id_commentaire, string $commentaire, ?int $note = null): array {
        try {
            // Vérifier si le commentaire existe
            $existingCommentaire = $this->crudCommentaire->getById($id_commentaire);
            
            if (!$existingCommentaire) {
                return [
                    'success' => false,
                    'message' => 'Commentaire non trouvé'
                ];
            }

            // Validation des données
            $validationErrors = $this->validateCommentaireData(
                $existingCommentaire->getIdUtilisateur(),
                $existingCommentaire->getIdFormation(),
                $existingCommentaire->getIdProduit(),
                $commentaire,
                $note
            );
            
            if (!empty($validationErrors)) {
                return [
                    'success' => false,
                    'message' => 'Données invalides',
                    'errors' => $validationErrors
                ];
            }

            $existingCommentaire->setCommentaire($commentaire);
            if ($note !== null) {
                $existingCommentaire->setNote($note);
            }

            if ($this->crudCommentaire->update($existingCommentaire)) {
                return [
                    'success' => true,
                    'message' => 'Commentaire mis à jour avec succès',
                    'data' => $existingCommentaire
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Erreur lors de la mise à jour du commentaire'
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
     * Modérer un commentaire (changer son statut)
     */
    public function modererCommentaire(int $id_commentaire, string $statut): array {
        try {
            // Vérifier si le commentaire existe
            $existingCommentaire = $this->crudCommentaire->getById($id_commentaire);
            
            if (!$existingCommentaire) {
                return [
                    'success' => false,
                    'message' => 'Commentaire non trouvé'
                ];
            }

            // Validation du statut
            $statuts_valides = ['actif', 'modéré', 'supprimé'];
            if (!in_array($statut, $statuts_valides)) {
                return [
                    'success' => false,
                    'message' => 'Statut invalide. Statuts valides: ' . implode(', ', $statuts_valides)
                ];
            }

            if ($this->crudCommentaire->updateStatut($id_commentaire, $statut)) {
                $existingCommentaire->setStatut($statut);
                return [
                    'success' => true,
                    'message' => 'Statut du commentaire mis à jour avec succès',
                    'data' => $existingCommentaire
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Erreur lors de la modification du statut'
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
     * Supprimer un commentaire (soft delete)
     */
    public function deleteCommentaire(int $id_commentaire): array {
        try {
            // Vérifier si le commentaire existe
            $existingCommentaire = $this->crudCommentaire->getById($id_commentaire);
            
            if (!$existingCommentaire) {
                return [
                    'success' => false,
                    'message' => 'Commentaire non trouvé'
                ];
            }

            if ($this->crudCommentaire->softDelete($id_commentaire)) {
                return [
                    'success' => true,
                    'message' => 'Commentaire supprimé avec succès'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Erreur lors de la suppression du commentaire'
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
     * Supprimer définitivement un commentaire
     */
    public function hardDeleteCommentaire(int $id_commentaire): array {
        try {
            // Vérifier si le commentaire existe
            $existingCommentaire = $this->crudCommentaire->getById($id_commentaire);
            
            if (!$existingCommentaire) {
                return [
                    'success' => false,
                    'message' => 'Commentaire non trouvé'
                ];
            }

            if ($this->crudCommentaire->delete($id_commentaire)) {
                return [
                    'success' => true,
                    'message' => 'Commentaire supprimé définitivement avec succès'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Erreur lors de la suppression définitive du commentaire'
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
     * Obtenir les statistiques des commentaires
     */
    public function getStats(): array {
        try {
            $allCommentaires = $this->crudCommentaire->getAll();
            
            $stats = [
                'total' => count($allCommentaires),
                'par_statut' => [],
                'par_type' => [],
                'notes_moyennes' => []
            ];

            foreach ($allCommentaires as $commentaire) {
                // Statistiques par statut
                $statut = $commentaire->getStatut();
                if (!isset($stats['par_statut'][$statut])) {
                    $stats['par_statut'][$statut] = 0;
                }
                $stats['par_statut'][$statut]++;

                // Statistiques par type
                $type = $commentaire->getIdFormation() ? 'formation' : 'produit';
                if (!isset($stats['par_type'][$type])) {
                    $stats['par_type'][$type] = 0;
                }
                $stats['par_type'][$type]++;

                // Notes moyennes
                if ($commentaire->getNote() !== null) {
                    $id_entity = $commentaire->getIdFormation() ?: $commentaire->getIdProduit();
                    $entity_type = $commentaire->getIdFormation() ? 'formation' : 'produit';
                    
                    if (!isset($stats['notes_moyennes'][$entity_type])) {
                        $stats['notes_moyennes'][$entity_type] = [];
                    }
                    
                    if (!isset($stats['notes_moyennes'][$entity_type][$id_entity])) {
                        $stats['notes_moyennes'][$entity_type][$id_entity] = [
                            'total_notes' => 0,
                            'somme_notes' => 0,
                            'moyenne' => 0
                        ];
                    }
                    
                    $stats['notes_moyennes'][$entity_type][$id_entity]['total_notes']++;
                    $stats['notes_moyennes'][$entity_type][$id_entity]['somme_notes'] += $commentaire->getNote();
                }
            }

            // Calcul des moyennes
            foreach ($stats['notes_moyennes'] as $entity_type => $entities) {
                foreach ($entities as $id_entity => $data) {
                    if ($data['total_notes'] > 0) {
                        $stats['notes_moyennes'][$entity_type][$id_entity]['moyenne'] = 
                            $data['somme_notes'] / $data['total_notes'];
                    }
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
     * Valider les données d'un commentaire
     */
    private function validateCommentaireData(int $id_utilisateur, ?int $id_formation, ?int $id_produit, 
                                           string $commentaire, ?int $note): array {
        $errors = [];

        if ($id_utilisateur <= 0) {
            $errors[] = 'ID utilisateur invalide';
        }

        if ($id_formation === null && $id_produit === null) {
            $errors[] = 'Le commentaire doit être associé à une formation ou un produit';
        }

        if ($id_formation !== null && $id_produit !== null) {
            $errors[] = 'Le commentaire ne peut pas être associé à une formation et un produit en même temps';
        }

        if (empty(trim($commentaire))) {
            $errors[] = 'Le commentaire ne peut pas être vide';
        } elseif (strlen(trim($commentaire)) < 5) {
            $errors[] = 'Le commentaire doit contenir au moins 5 caractères';
        } elseif (strlen(trim($commentaire)) > 1000) {
            $errors[] = 'Le commentaire ne peut pas dépasser 1000 caractères';
        }

        if ($note !== null && ($note < 1 || $note > 5)) {
            $errors[] = 'La note doit être comprise entre 1 et 5';
        }

        return $errors;
    }

    /**
     * Rechercher des commentaires
     */
    public function searchCommentaires(string $searchTerm, ?string $type = null, ?string $statut = null): array {
        try {
            $allCommentaires = $this->crudCommentaire->getAll();
            $searchTerm = strtolower(trim($searchTerm));
            
            $filteredCommentaires = array_filter($allCommentaires, function($commentaire) use ($searchTerm, $type, $statut) {
                $match = true;
                
                // Filtre par terme de recherche
                if (!empty($searchTerm)) {
                    $textMatch = stripos($commentaire->getCommentaire(), $searchTerm) !== false;
                    if (!$textMatch) {
                        $match = false;
                    }
                }
                
                // Filtre par type
                if ($type !== null) {
                    if ($type === 'formation' && $commentaire->getIdFormation() === null) {
                        $match = false;
                    }
                    if ($type === 'produit' && $commentaire->getIdProduit() === null) {
                        $match = false;
                    }
                }
                
                // Filtre par statut
                if ($statut !== null && $commentaire->getStatut() !== $statut) {
                    $match = false;
                }
                
                return $match;
            });
            
            return [
                'success' => true,
                'data' => array_values($filteredCommentaires),
                'count' => count($filteredCommentaires),
                'search_term' => $searchTerm,
                'filters' => [
                    'type' => $type,
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
?>