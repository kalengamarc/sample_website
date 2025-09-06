<?php
class Commentaire {
    private $id_commentaire;
    private $id_utilisateur;
    private $id_formation;
    private $id_produit;
    private $commentaire;
    private $note;
    private $date_commentaire;
    private $statut;
    private $parent_id;

    // Constructeur
    public function __construct($id_commentaire, $id_utilisateur, $id_formation, $id_produit, 
                               $commentaire, $note, $date_commentaire, $statut = 'actif', $parent_id = null) {
        $this->id_commentaire = $id_commentaire;
        $this->id_utilisateur = $id_utilisateur;
        $this->id_formation = $id_formation;
        $this->id_produit = $id_produit;
        $this->commentaire = $commentaire;
        $this->note = $note;
        $this->date_commentaire = $date_commentaire;
        $this->statut = $statut;
        $this->parent_id = $parent_id;
    }

    // Getters
    public function getIdCommentaire() { return $this->id_commentaire; }
    public function getIdUtilisateur() { return $this->id_utilisateur; }
    public function getIdFormation() { return $this->id_formation; }
    public function getIdProduit() { return $this->id_produit; }
    public function getCommentaire() { return $this->commentaire; }
    public function getNote() { return $this->note; }
    public function getDateCommentaire() { return $this->date_commentaire; }
    public function getStatut() { return $this->statut; }
    public function getParentId() { return $this->parent_id; }

    // Setters
    public function setIdCommentaire($id) { $this->id_commentaire = $id; }
    public function setIdUtilisateur($id) { $this->id_utilisateur = $id; }
    public function setIdFormation($id) { $this->id_formation = $id; }
    public function setIdProduit($id) { $this->id_produit = $id; }
    public function setCommentaire($commentaire) { $this->commentaire = $commentaire; }
    public function setNote($note) { 
        if ($note >= 1 && $note <= 5) {
            $this->note = $note;
        }
    }
    public function setDateCommentaire($date) { $this->date_commentaire = $date; }
    public function setStatut($statut) { 
        $statuts_valides = ['actif', 'modéré', 'supprimé'];
        if (in_array($statut, $statuts_valides)) {
            $this->statut = $statut;
        }
    }
    public function setParentId($parent_id) { $this->parent_id = $parent_id; }

    // Méthodes utilitaires
    public function isReply() {
        return $this->parent_id !== null;
    }

    public function isValid() {
        return !empty($this->commentaire) && 
               ($this->id_formation !== null || $this->id_produit !== null) &&
               $this->id_utilisateur !== null;
    }
}
?>
<?php
require_once 'base.php';

class CommentaireCRUD {
    private $pdo;

    public function __construct() {
        $db = new DataBase();
        $this->pdo = $db->getConnection();
    }

    // CREATE
    public function create(Commentaire $commentaire) {
        if (!$commentaire->isValid()) {
            throw new InvalidArgumentException("Le commentaire n'est pas valide");
        }

        $sql = "INSERT INTO commentaires (id_utilisateur, id_formation, id_produit, commentaire, note, date_commentaire, statut, parent_id) 
                VALUES (:id_utilisateur, :id_formation, :id_produit, :commentaire, :note, :date_commentaire, :statut, :parent_id)";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':id_utilisateur' => $commentaire->getIdUtilisateur(),
            ':id_formation' => $commentaire->getIdFormation(),
            ':id_produit' => $commentaire->getIdProduit(),
            ':commentaire' => $commentaire->getCommentaire(),
            ':note' => $commentaire->getNote(),
            ':date_commentaire' => $commentaire->getDateCommentaire(),
            ':statut' => $commentaire->getStatut(),
            ':parent_id' => $commentaire->getParentId()
        ]);

        $commentaire->setIdCommentaire($this->pdo->lastInsertId());
        return $commentaire;
    }

    // READ
    public function getById($id) {
        $sql = "SELECT * FROM commentaires WHERE id_commentaire = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$data) {
            return null;
        }
        
        return $this->mapToCommentaire($data);
    }

    public function getAll() {
        $sql = "SELECT * FROM commentaires ORDER BY date_commentaire DESC";
        $stmt = $this->pdo->query($sql);
        
        $commentaires = [];
        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $commentaires[] = $this->mapToCommentaire($data);
        }
        
        return $commentaires;
    }

    public function getByUtilisateur($id_utilisateur) {
        $sql = "SELECT * FROM commentaires WHERE id_utilisateur = :id_utilisateur ORDER BY date_commentaire DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id_utilisateur' => $id_utilisateur]);
        
        $commentaires = [];
        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $commentaires[] = $this->mapToCommentaire($data);
        }
        
        return $commentaires;
    }

    public function getByFormation($id_formation) {
        $sql = "SELECT * FROM commentaires WHERE id_formation = :id_formation AND statut = 'actif' ORDER BY date_commentaire DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id_formation' => $id_formation]);
        
        $commentaires = [];
        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $commentaires[] = $this->mapToCommentaire($data);
        }
        
        return $commentaires;
    }

    public function getByProduit($id_produit) {
        $sql = "SELECT * FROM commentaires WHERE id_produit = :id_produit AND statut = 'actif' ORDER BY date_commentaire DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id_produit' => $id_produit]);
        
        $commentaires = [];
        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $commentaires[] = $this->mapToCommentaire($data);
        }
        
        return $commentaires;
    }

    public function getReplies($parent_id) {
        $sql = "SELECT * FROM commentaires WHERE parent_id = :parent_id AND statut = 'actif' ORDER BY date_commentaire ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':parent_id' => $parent_id]);
        
        $commentaires = [];
        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $commentaires[] = $this->mapToCommentaire($data);
        }
        
        return $commentaires;
    }

    public function getAverageNoteByFormation($id_formation) {
        $sql = "SELECT AVG(note) as moyenne FROM commentaires WHERE id_formation = :id_formation AND statut = 'actif'";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id_formation' => $id_formation]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC)['moyenne'];
    }

    public function getAverageNoteByProduit($id_produit) {
        $sql = "SELECT AVG(note) as moyenne FROM commentaires WHERE id_produit = :id_produit AND statut = 'actif'";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id_produit' => $id_produit]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC)['moyenne'];
    }

    // UPDATE
    public function update(Commentaire $commentaire) {
        $sql = "UPDATE commentaires SET 
                id_utilisateur = :id_utilisateur,
                id_formation = :id_formation,
                id_produit = :id_produit,
                commentaire = :commentaire,
                note = :note,
                date_commentaire = :date_commentaire,
                statut = :statut,
                parent_id = :parent_id
                WHERE id_commentaire = :id_commentaire";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':id_utilisateur' => $commentaire->getIdUtilisateur(),
            ':id_formation' => $commentaire->getIdFormation(),
            ':id_produit' => $commentaire->getIdProduit(),
            ':commentaire' => $commentaire->getCommentaire(),
            ':note' => $commentaire->getNote(),
            ':date_commentaire' => $commentaire->getDateCommentaire(),
            ':statut' => $commentaire->getStatut(),
            ':parent_id' => $commentaire->getParentId(),
            ':id_commentaire' => $commentaire->getIdCommentaire()
        ]);
    }

    public function updateStatut($id_commentaire, $statut) {
        $sql = "UPDATE commentaires SET statut = :statut WHERE id_commentaire = :id_commentaire";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':statut' => $statut,
            ':id_commentaire' => $id_commentaire
        ]);
    }

    // DELETE
    public function delete($id) {
        $sql = "DELETE FROM commentaires WHERE id_commentaire = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function softDelete($id) {
        return $this->updateStatut($id, 'supprimé');
    }

    // UTILS
    private function mapToCommentaire($data) {
        return new Commentaire(
            $data['id_commentaire'],
            $data['id_utilisateur'],
            $data['id_formation'],
            $data['id_produit'],
            $data['commentaire'],
            $data['note'],
            $data['date_commentaire'],
            $data['statut'],
            $data['parent_id']
        );
    }
}
?>