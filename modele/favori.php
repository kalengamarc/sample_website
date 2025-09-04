<?php
class Favori {
    private $id_favori;
    private $id_utilisateur;
    private $id_formation;
    private $id_produit;
    private $date_ajout;

    // Constructeur
    public function __construct($id_favori, $id_utilisateur, $id_formation, $id_produit, $date_ajout) {
        $this->id_favori = $id_favori;
        $this->id_utilisateur = $id_utilisateur;
        $this->id_formation = $id_formation;
        $this->id_produit = $id_produit;
        $this->date_ajout = $date_ajout;

        // Validation
        if ($id_formation === null && $id_produit === null) {
            throw new InvalidArgumentException("Un favori doit être associé à une formation ou un produit");
        }
        if ($id_formation !== null && $id_produit !== null) {
            throw new InvalidArgumentException("Un favori ne peut pas être associé à une formation et un produit en même temps");
        }
    }

    // Getters
    public function getIdFavori() { return $this->id_favori; }
    public function getIdUtilisateur() { return $this->id_utilisateur; }
    public function getIdFormation() { return $this->id_formation; }
    public function getIdProduit() { return $this->id_produit; }
    public function getDateAjout() { return $this->date_ajout; }

    // Setters
    public function setIdFavori($id) { $this->id_favori = $id; }
    public function setIdUtilisateur($id) { $this->id_utilisateur = $id; }
    public function setIdFormation($id) { 
        if ($this->id_produit !== null) {
            throw new InvalidArgumentException("Impossible de définir une formation lorsque produit est déjà défini");
        }
        $this->id_formation = $id;
    }
    public function setIdProduit($id) { 
        if ($this->id_formation !== null) {
            throw new InvalidArgumentException("Impossible de définir un produit lorsque formation est déjà définie");
        }
        $this->id_produit = $id;
    }
    public function setDateAjout($date) { $this->date_ajout = $date; }

    // Méthodes utilitaires
    public function isFormationFavorite() {
        return $this->id_formation !== null;
    }

    public function isProduitFavorite() {
        return $this->id_produit !== null;
    }

    public function getType() {
        if ($this->isFormationFavorite()) return 'formation';
        if ($this->isProduitFavorite()) return 'produit';
        return null;
    }

    public function getAssociatedId() {
        return $this->isFormationFavorite() ? $this->id_formation : $this->id_produit;
    }
}
?>
<?php
require_once 'Favori.php';
require_once 'base.php';

class FavoriCRUD {
    private $pdo;

    public function __construct() {
        $this->pdo = getPDOConnection();
    }

    // CREATE
    public function create(Favori $favori) {
        // Vérifier si le favori existe déjà
        if ($this->exists($favori->getIdUtilisateur(), $favori->getAssociatedId(), $favori->getType())) {
            throw new InvalidArgumentException("Cet élément est déjà en favori");
        }

        $sql = "INSERT INTO favoris (id_utilisateur, id_formation, id_produit, date_ajout) 
                VALUES (:id_utilisateur, :id_formation, :id_produit, :date_ajout)";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':id_utilisateur' => $favori->getIdUtilisateur(),
            ':id_formation' => $favori->getIdFormation(),
            ':id_produit' => $favori->getIdProduit(),
            ':date_ajout' => $favori->getDateAjout()
        ]);

        $favori->setIdFavori($this->pdo->lastInsertId());
        return $favori;
    }

    // READ
    public function getById($id) {
        $sql = "SELECT * FROM favoris WHERE id_favori = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$data) {
            return null;
        }
        
        return $this->mapToFavori($data);
    }

    public function getAll() {
        $sql = "SELECT * FROM favoris ORDER BY date_ajout DESC";
        $stmt = $this->pdo->query($sql);
        
        $favoris = [];
        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $favoris[] = $this->mapToFavori($data);
        }
        
        return $favoris;
    }

    public function getByUtilisateur($id_utilisateur) {
        $sql = "SELECT * FROM favoris WHERE id_utilisateur = :id_utilisateur ORDER BY date_ajout DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id_utilisateur' => $id_utilisateur]);
        
        $favoris = [];
        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $favoris[] = $this->mapToFavori($data);
        }
        
        return $favoris;
    }

    public function getFormationsByUtilisateur($id_utilisateur) {
        $sql = "SELECT * FROM favoris WHERE id_utilisateur = :id_utilisateur AND id_formation IS NOT NULL ORDER BY date_ajout DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id_utilisateur' => $id_utilisateur]);
        
        $favoris = [];
        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $favoris[] = $this->mapToFavori($data);
        }
        
        return $favoris;
    }

    public function getProduitsByUtilisateur($id_utilisateur) {
        $sql = "SELECT * FROM favoris WHERE id_utilisateur = :id_utilisateur AND id_produit IS NOT NULL ORDER BY date_ajout DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id_utilisateur' => $id_utilisateur]);
        
        $favoris = [];
        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $favoris[] = $this->mapToFavori($data);
        }
        
        return $favoris;
    }

    public function exists($id_utilisateur, $id_element, $type) {
        if ($type === 'formation') {
            $sql = "SELECT COUNT(*) FROM favoris WHERE id_utilisateur = :id_utilisateur AND id_formation = :id_element";
        } else {
            $sql = "SELECT COUNT(*) FROM favoris WHERE id_utilisateur = :id_utilisateur AND id_produit = :id_element";
        }
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':id_utilisateur' => $id_utilisateur,
            ':id_element' => $id_element
        ]);
        
        return $stmt->fetchColumn() > 0;
    }

    // UPDATE
    public function update(Favori $favori) {
        $sql = "UPDATE favoris SET 
                id_utilisateur = :id_utilisateur,
                id_formation = :id_formation,
                id_produit = :id_produit,
                date_ajout = :date_ajout
                WHERE id_favori = :id_favori";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':id_utilisateur' => $favori->getIdUtilisateur(),
            ':id_formation' => $favori->getIdFormation(),
            ':id_produit' => $favori->getIdProduit(),
            ':date_ajout' => $favori->getDateAjout(),
            ':id_favori' => $favori->getIdFavori()
        ]);
    }

    // DELETE
    public function delete($id) {
        $sql = "DELETE FROM favoris WHERE id_favori = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function deleteByElement($id_utilisateur, $id_element, $type) {
        if ($type === 'formation') {
            $sql = "DELETE FROM favoris WHERE id_utilisateur = :id_utilisateur AND id_formation = :id_element";
        } else {
            $sql = "DELETE FROM favoris WHERE id_utilisateur = :id_utilisateur AND id_produit = :id_element";
        }
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':id_utilisateur' => $id_utilisateur,
            ':id_element' => $id_element
        ]);
    }

    // UTILS
    private function mapToFavori($data) {
        return new Favori(
            $data['id_favori'],
            $data['id_utilisateur'],
            $data['id_formation'],
            $data['id_produit'],
            $data['date_ajout']
        );
    }
}
?>