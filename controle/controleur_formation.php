<?php

include_once("../modele/formation.php");

class FormationController {
    private $requeteFormation;

    public function __construct() {
        $this->requeteFormation = new RequeteFormation();
    }

    /**
     * Créer une nouvelle formation
     */
    public function createFormation($titre, $description, $prix, $duree, $id_formateur, $debut_formation, $photo = null): array {
        try {
            // Validation des données
            $validationErrors = $this->validateFormationData($titre, $description, $prix, $duree, $id_formateur);
            
            if (!empty($validationErrors)) {
                return [
                    'success' => false,
                    'message' => 'Données invalides',
                    'errors' => $validationErrors
                ];
            }

            $date_creation = date('Y-m-d H:i:s');
            $formation = new Formation(null, $titre, $description, $prix, $duree, $id_formateur, $date_creation, $debut_formation, $photo);
            
            if ($this->requeteFormation->ajouterFormation($formation)) {
                return [
                    'success' => true,
                    'message' => 'Formation créée avec succès',
                    'data' => $formation
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Erreur lors de la création de la formation'
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
     * Récupérer une formation par son ID
     */
    public function getFormation($id_formation): array {
        try {
            $formation = $this->requeteFormation->getFormationById($id_formation);
            
            if ($formation) {
                return [
                    'success' => true,
                    'data' => $formation
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Formation non trouvée'
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
     * Récupérer toutes les formations
     */
    public function getAllFormations(): array {
    try {
        $formations = $this->requeteFormation->getAllFormations();
        
        return [
            'success' => true,
            'data' => $formations,
            'count' => count($formations)
        ];
    } catch (Exception $e) {
        return [
            'success' => false,
            'message' => 'Erreur: ' . $e->getMessage()
        ];
    }
}

    /**
     * Mettre à jour une formation
     */
public function updateFormation($id_formation, $titre, $description, $prix, $duree, $id_formateur, $debut_formation, $photo = null): array {
        try {
            // Vérifier d'abord si la formation existe
            $existingFormation = $this->requeteFormation->getFormationById($id_formation);
            
            if (!$existingFormation) {
                return [
                    'success' => false,
                    'message' => 'Formation non trouvée'
                ];
            }

            // Validation des données
            $validationErrors = $this->validateFormationData($titre, $description, $prix, $duree, $id_formateur);
            
            if (!empty($validationErrors)) {
                return [
                    'success' => false,
                    'message' => 'Données invalides',
                    'errors' => $validationErrors
                ];
            }

            // Conserver la date de création originale
            $date_creation = $existingFormation->getDateCreation();
            
            $formation = new Formation($id_formation, $titre, $description, $prix, $duree, $id_formateur, $date_creation, $debut_formation, $photo);
            
            if ($this->requeteFormation->mettreAJourFormation($formation)) {
                return [
                    'success' => true,
                    'message' => 'Formation mise à jour avec succès',
                    'data' => $formation
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Erreur lors de la mise à jour de la formation'
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
     * Supprimer une formation
     */
    public function deleteFormation($id_formation): array {
        try {
            // Vérifier d'abord si la formation existe
            $existingFormation = $this->requeteFormation->getFormationById($id_formation);
            
            if (!$existingFormation) {
                return [
                    'success' => false,
                    'message' => 'Formation non trouvée'
                ];
            }

            if ($this->requeteFormation->supprimerFormation($id_formation)) {
                return [
                    'success' => true,
                    'message' => 'Formation supprimée avec succès'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Erreur lors de la suppression de la formation'
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
     * Rechercher des formations par mot-clé
     */
    public function searchFormations($mot_cle): array {
        try {
            if (empty(trim($mot_cle))) {
                return [
                    'success' => false,
                    'message' => 'Le mot-clé de recherche ne peut pas être vide'
                ];
            }

            $formations = $this->requeteFormation->rechercherFormations($mot_cle);
            
            return [
                'success' => true,
                'data' => $formations,
                'count' => count($formations),
                'search_term' => $mot_cle
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Valider les données d'une formation
     */
    private function validateFormationData($titre, $description, $prix, $duree, $id_formateur): array {
        $errors = [];

        if (empty(trim($titre))) {
            $errors[] = 'Le titre est requis';
        } elseif (strlen(trim($titre)) < 3) {
            $errors[] = 'Le titre doit contenir au moins 3 caractères';
        }

        if (empty(trim($description))) {
            $errors[] = 'La description est requise';
        } elseif (strlen(trim($description)) < 10) {
            $errors[] = 'La description doit contenir au moins 10 caractères';
        }

        if (!is_numeric($prix) || $prix < 0) {
            $errors[] = 'Le prix doit être un nombre positif';
        }

        if (!is_numeric($duree) || $duree <= 0) {
            $errors[] = 'La durée doit être un nombre positif';
        }

        if (!is_numeric($id_formateur) || $id_formateur <= 0) {
            $errors[] = 'ID formateur invalide';
        }

        return $errors;
    }

    /**
     * Récupérer les formations d'un formateur spécifique
     */
    public function getFormationsByFormateur($id_formateur): array {
        try {
            $allFormations = $this->requeteFormation->getAllFormations();
            $formations = array_filter($allFormations, function($formation) use ($id_formateur) {
                return $formation->getIdFormateur() == $id_formateur;
            });
            
            return [
                'success' => true,
                'data' => array_values($formations),
                'count' => count($formations)
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Récupérer les formations récentes (limitées)
     */
    public function getRecentFormations($limit = 5): array {
        try {
            $allFormations = $this->requeteFormation->getAllFormations();
            // Trier par date de création décroissante et limiter
            usort($allFormations, function($a, $b) {
                return strtotime($b->getDateCreation()) - strtotime($a->getDateCreation());
            });
            
            $recentFormations = array_slice($allFormations, 0, $limit);
            
            return [
                'success' => true,
                'data' => $recentFormations,
                'count' => count($recentFormations)
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ];
        }
    }

    public function getTempsRestantFinFormation($timeSpeci,$duree){
        $timePresent = time();
        $timeSpecifique = strtotime($timeSpeci);
        $heureRestant = ($timeSpecifique - $timePresent)/3600;

        if ($heureRestant<=0 && $heureRestant<$duree) {
            if (abs(round($heureRestant/24))>30) {
                return round(abs(round($heureRestant/24))/30) ." mois ". abs(round($heureRestant/24))%30 ." jours de cours";
            } else {
                return abs(round($heureRestant/24))." jours de cours";
            }            
        }
        elseif ($heureRestant >= 0) {
            if (($heureRestant/24)>=30) {
                return 'la formation en preparation il reste <b> '. round(($heureRestant/24)/30) .' mois et '. round($heureRestant/24)%30 .' jours';
            }elseif (($heureRestant/24)<30 && ($heureRestant/24)>=1) {
                return 'la formation en preparation il reste <b> '. round($heureRestant/24) .'</b> jours';
            }elseif (($heureRestant/24)<1 && $heureRestant>=0) {
                return 'la formation commence aujourd\'hui';
            }
            else{
                return 'la formation en preparation il reste <b> '. round($heureRestant/24) .'</b> jours';
            }        
        }
        elseif(abs($heureRestant)>$duree) {
            return 'la formation a deja termine';
        }
    }
}