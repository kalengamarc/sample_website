<?php
class Abonnement {
    private $id_abonnement;
    private $id_abonne;
    private $id_formateur;
    private $date_abonnement;
    private $notifications;

    // Constructeur
    public function __construct($id_abonnement, $id_abonne, $id_formateur, $date_abonnement = null, $notifications = true) {
        $this->id_abonnement = $id_abonnement;
        $this->id_abonne = $id_abonne;
        $this->id_formateur = $id_formateur;
        $this->date_abonnement = $date_abonnement ?: date('Y-m-d H:i:s');
        $this->notifications = (bool)$notifications;

        if ($id_abonne === $id_formateur) {
            throw new InvalidArgumentException("Un utilisateur ne peut pas s'abonner à lui-même");
        }
    }

    // Getters
    public function getIdAbonnement() { return $this->id_abonnement; }
    public function getIdAbonne() { return $this->id_abonne; }
    public function getIdFormateur() { return $this->id_formateur; }
    public function getDateAbonnement() { return $this->date_abonnement; }
    public function getNotifications() { return $this->notifications; }

    // Setters
    public function setIdAbonnement($id) { $this->id_abonnement = $id; }
    public function setIdAbonne($id) { $this->id_abonne = $id; }
    public function setIdFormateur($id) { $this->id_formateur = $id; }
    public function setDateAbonnement($date) { $this->date_abonnement = $date; }
    public function setNotifications($notifications) { $this->notifications = (bool)$notifications; }

    public function isValid() {
        return $this->id_abonne !== null && 
               $this->id_formateur !== null && 
               $this->id_abonne !== $this->id_formateur;
    }
}
?>
<?php
require_once 'base.php';
require_once 'modele/Abonnement.php';

class CRUDAbonnement {
    private $connexion;

    public function __construct() {
        $db = new Database();
        $this->connexion = $db->getConnexion();
    }

    /**
     * Créer un nouvel abonnement
     */
    public function create(Abonnement $abonnement): bool {
        if (!$abonnement->isValid()) {
            throw new InvalidArgumentException("Données d'abonnement invalides");
        }

        $sql = "INSERT INTO abonnements (id_abonne, id_formateur, notifications) 
                VALUES (?, ?, ?)";
        
        $stmt = $this->connexion->prepare($sql);
        return $stmt->execute([
            $abonnement->getIdAbonne(),
            $abonnement->getIdFormateur(),
            $abonnement->getNotifications() ? 1 : 0
        ]);
    }

    /**
     * Récupérer un abonnement par son ID
     */
    public function getById(int $id_abonnement): ?Abonnement {
        $sql = "SELECT * FROM abonnements WHERE id_abonnement = ?";
        $stmt = $this->connexion->prepare($sql);
        $stmt->execute([$id_abonnement]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $data ? $this->hydrate($data) : null;
    }

    /**
     * Vérifier si un abonnement existe déjà
     */
    public function exists(int $id_abonne, int $id_formateur): bool {
        $sql = "SELECT 1 FROM abonnements 
                WHERE id_abonne = ? AND id_formateur = ? 
                LIMIT 1";
        
        $stmt = $this->connexion->prepare($sql);
        $stmt->execute([$id_abonne, $id_formateur]);
        return (bool) $stmt->fetchColumn();
    }

    /**
     * Récupérer tous les abonnements d'un utilisateur (abonnements)
     */
    public function getAbonnementsByUser(int $id_abonne): array {
        $sql = "SELECT a.*, u.nom, u.prenom, u.photo 
                FROM abonnements a
                JOIN utilisateurs u ON a.id_formateur = u.id_utilisateur
                WHERE a.id_abonne = ?
                ORDER BY a.date_abonnement DESC";
        
        $stmt = $this->connexion->prepare($sql);
        $stmt->execute([$id_abonne]);
        
        $abonnements = [];
        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $abonnement = $this->hydrate($data);
            $abonnement->formateur_nom = $data['nom'] . ' ' . $data['prenom'];
            $abonnement->formateur_photo = $data['photo'];
            $abonnements[] = $abonnement;
        }
        
        return $abonnements;
    }

    /**
     * Récupérer tous les abonnés d'un formateur
     */
    public function getAbonnesByFormateur(int $id_formateur): array {
        $sql = "SELECT a.*, u.nom, u.prenom, u.photo 
                FROM abonnements a
                JOIN utilisateurs u ON a.id_abonne = u.id_utilisateur
                WHERE a.id_formateur = ?
                ORDER BY a.date_abonnement DESC";
        
        $stmt = $this->connexion->prepare($sql);
        $stmt->execute([$id_formateur]);
        
        $abonnes = [];
        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $abonnement = $this->hydrate($data);
            $abonnement->abonne_nom = $data['nom'] . ' ' . $data['prenom'];
            $abonnement->abonne_photo = $data['photo'];
            $abonnes[] = $abonnement;
        }
        
        return $abonnes;
    }

    /**
     * Compter le nombre d'abonnés d'un formateur
     */
    public function countAbonnes(int $id_formateur): int {
        $sql = "SELECT COUNT(*) FROM abonnements WHERE id_formateur = ?";
        $stmt = $this->connexion->prepare($sql);
        $stmt->execute([$id_formateur]);
        return (int) $stmt->fetchColumn();
    }

    /**
     * Compter le nombre d'abonnements d'un utilisateur
     */
    public function countAbonnements(int $id_abonne): int {
        $sql = "SELECT COUNT(*) FROM abonnements WHERE id_abonne = ?";
        $stmt = $this->connexion->prepare($sql);
        $stmt->execute([$id_abonne]);
        return (int) $stmt->fetchColumn();
    }

    /**
     * Mettre à jour les préférences de notifications
     */
    public function updateNotifications(int $id_abonnement, bool $notifications): bool {
        $sql = "UPDATE abonnements SET notifications = ? WHERE id_abonnement = ?";
        $stmt = $this->connexion->prepare($sql);
        return $stmt->execute([$notifications ? 1 : 0, $id_abonnement]);
    }

    /**
     * Supprimer un abonnement
     */
    public function delete(int $id_abonnement): bool {
        $sql = "DELETE FROM abonnements WHERE id_abonnement = ?";
        $stmt = $this->connexion->prepare($sql);
        return $stmt->execute([$id_abonnement]);
    }

    /**
     * Supprimer un abonnement spécifique
     */
    public function deleteByRelation(int $id_abonne, int $id_formateur): bool {
        $sql = "DELETE FROM abonnements 
                WHERE id_abonne = ? AND id_formateur = ?";
        $stmt = $this->connexion->prepare($sql);
        return $stmt->execute([$id_abonne, $id_formateur]);
    }

    /**
     * Récupérer les abonnements récents
     */
    public function getRecentAbonnements(int $limit = 10): array {
        $sql = "SELECT a.*, 
                u_abonne.nom as abonne_nom, u_abonne.prenom as abonne_prenom,
                u_formateur.nom as formateur_nom, u_formateur.prenom as formateur_prenom
                FROM abonnements a
                JOIN utilisateurs u_abonne ON a.id_abonne = u_abonne.id_utilisateur
                JOIN utilisateurs u_formateur ON a.id_formateur = u_formateur.id_utilisateur
                ORDER BY a.date_abonnement DESC 
                LIMIT ?";
        
        $stmt = $this->connexion->prepare($sql);
        $stmt->execute([$limit]);
        
        $abonnements = [];
        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $abonnement = $this->hydrate($data);
            $abonnement->abonne_complet = $data['abonne_nom'] . ' ' . $data['abonne_prenom'];
            $abonnement->formateur_complet = $data['formateur_nom'] . ' ' . $data['formateur_prenom'];
            $abonnements[] = $abonnement;
        }
        
        return $abonnements;
    }

    /**
     * Vérifier les abonnements mutuels
     */
    public function checkMutualAbonnement(int $id_utilisateur1, int $id_utilisateur2): bool {
        $sql = "SELECT COUNT(*) FROM abonnements 
                WHERE (id_abonne = ? AND id_formateur = ?)
                OR (id_abonne = ? AND id_formateur = ?)";
        
        $stmt = $this->connexion->prepare($sql);
        $stmt->execute([$id_utilisateur1, $id_utilisateur2, $id_utilisateur2, $id_utilisateur1]);
        
        return $stmt->fetchColumn() == 2;
    }

    /**
     * Récupérer les suggestions d'abonnement pour un utilisateur
     */
    public function getSuggestions(int $id_utilisateur, int $limit = 5): array {
        $sql = "SELECT u.id_utilisateur, u.nom, u.prenom, u.photo, u.role,
                COUNT(a.id_abonnement) as nombre_abonnes,
                (SELECT COUNT(*) FROM formations WHERE id_formateur = u.id_utilisateur) as nombre_formations
                FROM utilisateurs u
                LEFT JOIN abonnements a ON u.id_utilisateur = a.id_formateur
                WHERE u.id_utilisateur != ? 
                AND u.role IN ('formateur', 'admin')
                AND u.id_utilisateur NOT IN (
                    SELECT id_formateur FROM abonnements WHERE id_abonne = ?
                )
                GROUP BY u.id_utilisateur
                ORDER BY nombre_abonnes DESC, nombre_formations DESC
                LIMIT ?";
        
        $stmt = $this->connexion->prepare($sql);
        $stmt->execute([$id_utilisateur, $id_utilisateur, $limit]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Hydrater un objet Abonnement à partir des données de la base
     */
    private function hydrate(array $data): Abonnement {
        return new Abonnement(
            $data['id_abonnement'],
            $data['id_abonne'],
            $data['id_formateur'],
            $data['date_abonnement'],
            (bool) $data['notifications']
        );
    }

    /**
     * Récupérer les statistiques des abonnements
     */
    public function getStats(): array {
        $stats = [];

        // Total d'abonnements
        $sql = "SELECT COUNT(*) as total FROM abonnements";
        $stmt = $this->connexion->query($sql);
        $stats['total_abonnements'] = (int) $stmt->fetchColumn();

        // Abonnements par mois
        $sql = "SELECT DATE_FORMAT(date_abonnement, '%Y-%m') as mois, 
                COUNT(*) as nombre
                FROM abonnements 
                WHERE date_abonnement >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
                GROUP BY mois 
                ORDER BY mois";
        $stmt = $this->connexion->query($sql);
        $stats['par_mois'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Top formateurs par nombre d'abonnés
        $sql = "SELECT u.id_utilisateur, u.nom, u.prenom, 
                COUNT(a.id_abonnement) as nombre_abonnes
                FROM utilisateurs u
                JOIN abonnements a ON u.id_utilisateur = a.id_formateur
                GROUP BY u.id_utilisateur
                ORDER BY nombre_abonnes DESC
                LIMIT 10";
        $stmt = $this->connexion->query($sql);
        $stats['top_formateurs'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $stats;
    }

    /**
     * Nettoyer les abonnements obsolètes (utilisateurs supprimés)
     */
    public function cleanOrphanedAbonnements(): int {
        $sql = "DELETE a FROM abonnements a
                LEFT JOIN utilisateurs u_abonne ON a.id_abonne = u_abonne.id_utilisateur
                LEFT JOIN utilisateurs u_formateur ON a.id_formateur = u_formateur.id_utilisateur
                WHERE u_abonne.id_utilisateur IS NULL OR u_formateur.id_utilisateur IS NULL";
        
        $stmt = $this->connexion->prepare($sql);
        $stmt->execute();
        return $stmt->rowCount();
    }
}
?>