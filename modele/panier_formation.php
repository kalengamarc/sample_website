<?php
class PanierFormation {
    private $id_panier_formation;
    private $id_utilisateur;
    private $id_formation;
    private $date_ajout;

    // Constructeur
    public function __construct($id_panier_formation, $id_utilisateur, $id_formation, $date_ajout = null) {
        $this->id_panier_formation = $id_panier_formation;
        $this->id_utilisateur = $id_utilisateur;
        $this->id_formation = $id_formation;
        $this->date_ajout = $date_ajout ?: date('Y-m-d H:i:s');
    }

    // Getters
    public function getIdPanierFormation() { return $this->id_panier_formation; }
    public function getIdUtilisateur() { return $this->id_utilisateur; }
    public function getIdFormation() { return $this->id_formation; }
    public function getDateAjout() { return $this->date_ajout; }

    // Setters
    public function setIdPanierFormation($id) { $this->id_panier_formation = $id; }
    public function setIdUtilisateur($id) { $this->id_utilisateur = $id; }
    public function setIdFormation($id) { $this->id_formation = $id; }
    public function setDateAjout($date) { $this->date_ajout = $date; }

    public function isValid() {
        return $this->id_utilisateur !== null && $this->id_formation !== null;
    }
}
?>
<?php
require_once 'base.php';

class PanierFormationCRUD {
    private $pdo;

    public function __construct() {
        $db = new DataBase();
        $this->pdo = $db->getConnection();
    }

    // CREATE
    public function create(PanierFormation $panierFormation) {
        if (!$panierFormation->isValid()) {
            throw new InvalidArgumentException("Le panier formation n'est pas valide");
        }

        // Vérifier si la formation est déjà dans le panier
        if ($this->exists($panierFormation->getIdUtilisateur(), $panierFormation->getIdFormation())) {
            throw new InvalidArgumentException("Cette formation est déjà dans le panier");
        }

        $sql = "INSERT INTO panier_formation (id_utilisateur, id_formation, date_ajout) 
                VALUES (:id_utilisateur, :id_formation, :date_ajout)";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':id_utilisateur' => $panierFormation->getIdUtilisateur(),
            ':id_formation' => $panierFormation->getIdFormation(),
            ':date_ajout' => $panierFormation->getDateAjout()
        ]);

        $panierFormation->setIdPanierFormation($this->pdo->lastInsertId());
        return $panierFormation;
    }

    // READ
    public function getById($id) {
        $sql = "SELECT * FROM panier_formation WHERE id_panier_formation = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$data) {
            return null;
        }
        
        return $this->mapToPanierFormation($data);
    }

    public function getByUtilisateur($id_utilisateur) {
        $sql = "SELECT * FROM panier_formation WHERE id_utilisateur = :id_utilisateur ORDER BY date_ajout DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id_utilisateur' => $id_utilisateur]);
        
        $panierFormations = [];
        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $panierFormations[] = $this->mapToPanierFormation($data);
        }
        
        return $panierFormations;
    }

    public function exists($id_utilisateur, $id_formation) {
        $sql = "SELECT COUNT(*) FROM panier_formation WHERE id_utilisateur = :id_utilisateur AND id_formation = :id_formation";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':id_utilisateur' => $id_utilisateur,
            ':id_formation' => $id_formation
        ]);
        
        return $stmt->fetchColumn() > 0;
    }

    public function countByUtilisateur($id_utilisateur) {
        $sql = "SELECT COUNT(*) FROM panier_formation WHERE id_utilisateur = :id_utilisateur";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id_utilisateur' => $id_utilisateur]);
        
        return $stmt->fetchColumn();
    }

    // UPDATE
    public function update(PanierFormation $panierFormation) {
        $sql = "UPDATE panier_formation SET 
                id_utilisateur = :id_utilisateur,
                id_formation = :id_formation,
                date_ajout = :date_ajout
                WHERE id_panier_formation = :id_panier_formation";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':id_utilisateur' => $panierFormation->getIdUtilisateur(),
            ':id_formation' => $panierFormation->getIdFormation(),
            ':date_ajout' => $panierFormation->getDateAjout(),
            ':id_panier_formation' => $panierFormation->getIdPanierFormation()
        ]);
    }

    // DELETE
    public function delete($id) {
        $sql = "DELETE FROM panier_formation WHERE id_panier_formation = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function deleteByUtilisateur($id_utilisateur) {
        $sql = "DELETE FROM panier_formation WHERE id_utilisateur = :id_utilisateur";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id_utilisateur' => $id_utilisateur]);
    }

    public function deleteByUtilisateurAndFormation($id_utilisateur, $id_formation) {
        $sql = "DELETE FROM panier_formation WHERE id_utilisateur = :id_utilisateur AND id_formation = :id_formation";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':id_utilisateur' => $id_utilisateur,
            ':id_formation' => $id_formation
        ]);
    }

    // UTILS
    private function mapToPanierFormation($data) {
        return new PanierFormation(
            $data['id_panier_formation'],
            $data['id_utilisateur'],
            $data['id_formation'],
            $data['date_ajout']
        );
    }
}
?>