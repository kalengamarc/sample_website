<?php
require_once __DIR__ . '/../modele/partage.php';

class PartageController {
    private $partageCRUD;

    public function __construct() {
        $this->partageCRUD = new PartageCRUD();
    }

    /**
     * Enregistrer un nouveau partage
     */
    public function enregistrerPartage($data) {
        try {
            // Validation des données requises
            if (!isset($data['id_utilisateur']) || !isset($data['plateforme'])) {
                return [
                    'success' => false,
                    'message' => 'Données manquantes: id_utilisateur et plateforme sont requis'
                ];
            }

            // Vérifier qu'au moins un ID de formation ou produit est fourni
            if (!isset($data['id_formation']) && !isset($data['id_produit'])) {
                return [
                    'success' => false,
                    'message' => 'Données manquantes: id_formation ou id_produit requis'
                ];
            }

            // Récupérer l'adresse IP et user agent si non fournis
            $ip_address = $data['ip_address'] ?? $_SERVER['REMOTE_ADDR'] ?? null;
            $user_agent = $data['user_agent'] ?? $_SERVER['HTTP_USER_AGENT'] ?? null;

            // Créer l'objet Partage
            $partage = new Partage(
                null, // id_partage sera généré automatiquement
                $data['id_utilisateur'],
                $data['id_formation'] ?? null,
                $data['id_produit'] ?? null,
                $data['plateforme'],
                null, // date_partage sera générée automatiquement
                $ip_address,
                $user_agent
            );

            // Enregistrer en base de données
            $partageCree = $this->partageCRUD->create($partage);

            return [
                'success' => true,
                'message' => 'Partage enregistré avec succès',
                'data' => [
                    'id_partage' => $partageCree->getIdPartage(),
                    'date_partage' => $partageCree->getDatePartage()
                ]
            ];

        } catch (InvalidArgumentException $e) {
            return [
                'success' => false,
                'message' => 'Données invalides: ' . $e->getMessage()
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur lors de l\'enregistrement: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Récupérer les statistiques de partage pour un élément
     */
    public function getStatistiquesPartage($id_element, $type) {
        try {
            // Validation du type
            if (!in_array($type, ['formation', 'produit'])) {
                return [
                    'success' => false,
                    'message' => 'Type invalide. Doit être "formation" ou "produit"'
                ];
            }

            $stats = $this->partageCRUD->getStatsByElement($id_element, $type);

            // Formater les résultats
            $total = 0;
            $parPlateforme = [];

            foreach ($stats as $stat) {
                $parPlateforme[$stat['plateforme']] = (int)$stat['count'];
                $total += (int)$stat['count'];
            }

            return [
                'success' => true,
                'data' => [
                    'total' => $total,
                    'par_plateforme' => $parPlateforme,
                    'element_id' => $id_element,
                    'type' => $type
                ]
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur lors de la récupération des statistiques: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Récupérer l'historique des partages d'un utilisateur
     */
    public function getHistoriqueUtilisateur($id_utilisateur, $limit = 50, $offset = 0) {
        try {
            $partages = $this->partageCRUD->getByUtilisateur($id_utilisateur);

            // Formater les résultats
            $resultats = [];
            foreach ($partages as $partage) {
                $resultats[] = $this->formaterPartage($partage);
            }

            return [
                'success' => true,
                'data' => [
                    'partages' => $resultats,
                    'total' => count($resultats)
                ]
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur lors de la récupération de l\'historique: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Récupérer les partages par plateforme
     */
    public function getPartagesParPlateforme($plateforme) {
        try {
            $partages = $this->partageCRUD->getByPlateforme($plateforme);

            $resultats = [];
            foreach ($partages as $partage) {
                $resultats[] = $this->formaterPartage($partage);
            }

            return [
                'success' => true,
                'data' => [
                    'partages' => $resultats,
                    'total' => count($resultats),
                    'plateforme' => $plateforme
                ]
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur lors de la récupération des partages: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Vérifier si un utilisateur a déjà partagé un élément
     */
    public function aDejaPartage($id_utilisateur, $id_element, $type) {
        try {
            if ($type === 'formation') {
                $partages = $this->partageCRUD->getByFormation($id_element);
            } else {
                $partages = $this->partageCRUD->getByProduit($id_element);
            }

            foreach ($partages as $partage) {
                if ($partage->getIdUtilisateur() == $id_utilisateur) {
                    return [
                        'success' => true,
                        'data' => [
                            'a_partage' => true,
                            'partage' => $this->formaterPartage($partage)
                        ]
                    ];
                }
            }

            return [
                'success' => true,
                'data' => ['a_partage' => false]
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur lors de la vérification: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Formater un objet Partage pour l'affichage
     */
    private function formaterPartage(Partage $partage) {
        return [
            'id_partage' => $partage->getIdPartage(),
            'id_utilisateur' => $partage->getIdUtilisateur(),
            'type_element' => $partage->getType(),
            'id_element' => $partage->getAssociatedId(),
            'plateforme' => $partage->getPlateforme(),
            'date_partage' => $partage->getDatePartage(),
            'est_reseau_social' => $partage->isSocialMedia(),
            'est_partage_direct' => $partage->isDirectShare(),
            'ip_address' => $partage->getIpAddress(),
            'user_agent' => $partage->getUserAgent()
        ];
    }

    /**
     * Obtenir la liste des plateformes valides
     */
    public function getPlateformesValides() {
        return [
            'success' => true,
            'data' => [
                'plateformes' => Partage::getPlateformesValides()
            ]
        ];
    }

    /**
     * Supprimer un partage
     */
    public function supprimerPartage($id_partage) {
        try {
            $success = $this->partageCRUD->delete($id_partage);

            return [
                'success' => $success,
                'message' => $success ? 'Partage supprimé avec succès' : 'Échec de la suppression'
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur lors de la suppression: ' . $e->getMessage()
            ];
        }
    }
}

// Exemple d'utilisation du contrôleur
/*
$controller = new PartageController();

// Enregistrer un partage
$result = $controller->enregistrerPartage([
    'id_utilisateur' => 1,
    'id_formation' => 5,
    'plateforme' => 'facebook'
]);

// Obtenir les statistiques
$stats = $controller->getStatistiquesPartage(5, 'formation');

// Historique utilisateur
$historique = $controller->getHistoriqueUtilisateur(1);
*/
?>