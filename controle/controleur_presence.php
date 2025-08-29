<?php

include_once("Presence.php");
include_once("CRUDPresence.php");

class PresenceController {
    private $crudPresence;

    public function __construct() {
        $this->crudPresence = new CRUDPresence();
    }

    /**
     * Enregistrer une présence
     */
    public function createPresence(int $id_inscription, string $date_session, string $statut = "absent"): array {
        try {
            // Validation des données
            $validationErrors = $this->validatePresenceData($id_inscription, $date_session, $statut);
            
            if (!empty($validationErrors)) {
                return [
                    'success' => false,
                    'message' => 'Données invalides',
                    'errors' => $validationErrors
                ];
            }

            // Vérifier si la présence existe déjà pour cette inscription et cette date
            if ($this->checkExistingPresence($id_inscription, $date_session)) {
                return [
                    'success' => false,
                    'message' => 'Une présence existe déjà pour cette inscription à cette date'
                ];
            }

            $presence = new Presence($id_inscription, $date_session, $statut);
            
            if ($this->crudPresence->ajouterPresence($presence)) {
                return [
                    'success' => true,
                    'message' => 'Présence enregistrée avec succès',
                    'data' => $presence
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Erreur lors de l\'enregistrement de la présence'
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
     * Récupérer une présence par son ID
     */
    public function getPresence(int $id_presence): array {
        try {
            $presence = $this->crudPresence->getPresenceById($id_presence);
            
            if ($presence) {
                return [
                    'success' => true,
                    'data' => $presence
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Présence non trouvée'
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
     * Récupérer toutes les présences
     */
    public function getAllPresences(): array {
        try {
            $presences = $this->crudPresence->getAllPresences();
            
            return [
                'success' => true,
                'data' => $presences,
                'count' => count($presences),
                'stats' => $this->calculateStats($presences)
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Récupérer les présences d'une inscription
     */
    public function getPresencesByInscription(int $id_inscription): array {
        try {
            $presences = $this->crudPresence->getPresencesByInscription($id_inscription);
            
            return [
                'success' => true,
                'data' => $presences,
                'count' => count($presences),
                'id_inscription' => $id_inscription,
                'stats' => $this->calculateStats($presences)
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Mettre à jour une présence
     */
    public function updatePresence(int $id_presence, string $statut, string $date_session): array {
        try {
            // Vérifier d'abord si la présence existe
            $existingPresence = $this->crudPresence->getPresenceById($id_presence);
            
            if (!$existingPresence) {
                return [
                    'success' => false,
                    'message' => 'Présence non trouvée'
                ];
            }

            // Validation des données
            $validationErrors = $this->validatePresenceData($existingPresence->getIdInscription(), $date_session, $statut);
            
            if (!empty($validationErrors)) {
                return [
                    'success' => false,
                    'message' => 'Données invalides',
                    'errors' => $validationErrors
                ];
            }

            $existingPresence->setStatut($statut);
            $existingPresence->setDateSession($date_session);

            if ($this->crudPresence->updatePresence($existingPresence)) {
                return [
                    'success' => true,
                    'message' => 'Présence mise à jour avec succès',
                    'data' => $existingPresence
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Erreur lors de la mise à jour de la présence'
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
     * Mettre à jour uniquement le statut d'une présence
     */
    public function updateStatut(int $id_presence, string $statut): array {
        try {
            // Vérifier d'abord si la présence existe
            $existingPresence = $this->crudPresence->getPresenceById($id_presence);
            
            if (!$existingPresence) {
                return [
                    'success' => false,
                    'message' => 'Présence non trouvée'
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

            $existingPresence->setStatut($statut);

            if ($this->crudPresence->updatePresence($existingPresence)) {
                return [
                    'success' => true,
                    'message' => 'Statut de la présence mis à jour avec succès',
                    'data' => $existingPresence
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
     * Supprimer une présence
     */
    public function deletePresence(int $id_presence): array {
        try {
            // Vérifier d'abord si la présence existe
            $existingPresence = $this->crudPresence->getPresenceById($id_presence);
            
            if (!$existingPresence) {
                return [
                    'success' => false,
                    'message' => 'Présence non trouvée'
                ];
            }

            if ($this->crudPresence->deletePresence($id_presence)) {
                return [
                    'success' => true,
                    'message' => 'Présence supprimée avec succès'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Erreur lors de la suppression de la présence'
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
     * Marquer plusieurs présences en une seule opération
     */
    public function markMultiplePresences(array $presencesData): array {
        try {
            $results = [];
            $successCount = 0;
            $errorCount = 0;

            foreach ($presencesData as $data) {
                if (!isset($data['id_inscription']) || !isset($data['date_session'])) {
                    $results[] = [
                        'success' => false,
                        'message' => 'Données manquantes pour une présence',
                        'data' => $data
                    ];
                    $errorCount++;
                    continue;
                }

                $statut = $data['statut'] ?? 'absent';
                $result = $this->createPresence(
                    $data['id_inscription'],
                    $data['date_session'],
                    $statut
                );

                $results[] = $result;
                if ($result['success']) {
                    $successCount++;
                } else {
                    $errorCount++;
                }
            }

            return [
                'success' => true,
                'message' => "Opération terminée: $successCount succès, $errorCount échecs",
                'results' => $results,
                'success_count' => $successCount,
                'error_count' => $errorCount
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Obtenir les statistiques des présences
     */
    public function getStats(): array {
        try {
            $allPresences = $this->crudPresence->getAllPresences();
            
            $stats = [
                'total' => count($allPresences),
                'par_statut' => [],
                'par_inscription' => []
            ];

            foreach ($allPresences as $presence) {
                // Statistiques par statut
                $statut = $presence->getStatut();
                if (!isset($stats['par_statut'][$statut])) {
                    $stats['par_statut'][$statut] = 0;
                }
                $stats['par_statut'][$statut]++;

                // Statistiques par inscription
                $id_inscription = $presence->getIdInscription();
                if (!isset($stats['par_inscription'][$id_inscription])) {
                    $stats['par_inscription'][$id_inscription] = ['total' => 0, 'present' => 0, 'absent' => 0];
                }
                $stats['par_inscription'][$id_inscription]['total']++;
                if ($statut === 'present') {
                    $stats['par_inscription'][$id_inscription]['present']++;
                } else {
                    $stats['par_inscription'][$id_inscription]['absent']++;
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
     * Obtenir les statistiques pour une inscription spécifique
     */
    public function getStatsByInscription(int $id_inscription): array {
        try {
            $presences = $this->crudPresence->getPresencesByInscription($id_inscription);
            
            $stats = [
                'total' => count($presences),
                'present' => 0,
                'absent' => 0,
                'taux_presence' => 0
            ];

            foreach ($presences as $presence) {
                if ($presence->getStatut() === 'present') {
                    $stats['present']++;
                } else {
                    $stats['absent']++;
                }
            }

            if ($stats['total'] > 0) {
                $stats['taux_presence'] = round(($stats['present'] / $stats['total']) * 100, 2);
            }

            return [
                'success' => true,
                'data' => $stats,
                'id_inscription' => $id_inscription
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Valider les données d'une présence
     */
    private function validatePresenceData(int $id_inscription, string $date_session, string $statut): array {
        $errors = [];

        if ($id_inscription <= 0) {
            $errors[] = 'ID inscription invalide';
        }

        if (empty(trim($date_session))) {
            $errors[] = 'La date de session est requise';
        } elseif (!strtotime($date_session)) {
            $errors[] = 'Format de date invalide';
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
        $validStatuts = ["present", "absent"];

        if (!in_array($statut, $validStatuts)) {
            $errors[] = 'Statut invalide. Les statuts valides sont: ' . implode(', ', $validStatuts);
        }

        return $errors;
    }

    /**
     * Vérifier si une présence existe déjà pour une inscription et une date
     */
    private function checkExistingPresence(int $id_inscription, string $date_session): bool {
        $allPresences = $this->crudPresence->getPresencesByInscription($id_inscription);
        
        foreach ($allPresences as $presence) {
            if ($presence->getDateSession() === $date_session) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Calculer les statistiques pour une liste de présences
     */
    private function calculateStats(array $presences): array {
        $stats = [
            'total' => count($presences),
            'present' => 0,
            'absent' => 0,
            'taux_presence' => 0
        ];

        foreach ($presences as $presence) {
            if ($presence->getStatut() === 'present') {
                $stats['present']++;
            } else {
                $stats['absent']++;
            }
        }

        if ($stats['total'] > 0) {
            $stats['taux_presence'] = round(($stats['present'] / $stats['total']) * 100, 2);
        }

        return $stats;
    }

    /**
     * Rechercher les présences par date
     */
    public function getPresencesByDate(string $date): array {
        try {
            $allPresences = $this->crudPresence->getAllPresences();
            $presences = array_filter($allPresences, function($presence) use ($date) {
                return $presence->getDateSession() === $date;
            });
            
            return [
                'success' => true,
                'data' => array_values($presences),
                'count' => count($presences),
                'date' => $date,
                'stats' => $this->calculateStats($presences)
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Obtenir les présences dans une plage de dates
     */
    public function getPresencesByDateRange(string $start_date, string $end_date): array {
        try {
            $allPresences = $this->crudPresence->getAllPresences();
            $presences = array_filter($allPresences, function($presence) use ($start_date, $end_date) {
                $date = $presence->getDateSession();
                return $date >= $start_date && $date <= $end_date;
            });
            
            // Trier par date
            usort($presences, function($a, $b) {
                return strcmp($a->getDateSession(), $b->getDateSession());
            });
            
            return [
                'success' => true,
                'data' => $presences,
                'count' => count($presences),
                'start_date' => $start_date,
                'end_date' => $end_date,
                'stats' => $this->calculateStats($presences)
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