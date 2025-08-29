<?php
include_once("base.php");

class Formation {
    private $id_formation;
    private $titre;
    private $description;
    private $prix;
    private $duree;
    private $id_formateur;
    private $date_creation;
    private $photo;

    public function __construct($id_formation, $titre, $description, $prix, $duree, $id_formateur, $date_creation, $photo) {
        $this->id_formation = $id_formation;
        $this->titre = $titre;
        $this->description = $description;
        $this->prix = $prix;
        $this->duree = $duree;
        $this->id_formateur = $id_formateur;
        $this->date_creation = $date_creation;
        $this->photo = $photo;
    }

    // --- GETTERS ---
    public function getIdFormation() { return $this->id_formation; }
    public function getTitre() { return $this->titre; }
    public function getDescription() { return $this->description; }
    public function getPrix() { return $this->prix; }
    public function getDuree() { return $this->duree; }
    public function getIdFormateur() { return $this->id_formateur; }
    public function getDateCreation() { return $this->date_creation; }
    public function getPhoto() { return $this->photo; }

    // --- SETTERS ---
    public function setTitre($titre) { $this->titre = $titre; }
    public function setDescription($description) { $this->description = $description; }
    public function setPrix($prix) { $this->prix = $prix; }
    public function setDuree($duree) { $this->duree = $duree; }
    public function setIdFormateur($id_formateur) { $this->id_formateur = $id_formateur; }
    public function setDateCreation($date_creation) { $this->date_creation = $date_creation; }
    public function setPhoto($photo) { $this->photo = $photo; }
}

class RequeteFormation {
    private $crud;

    public function __construct() {
        $pdo = new Database();
        $this->crud = $pdo->getConnection();
    }

    // ✅ Ajouter une formation
    public function ajouterFormation($formation) {
        $sql = "INSERT INTO formations (titre, description, prix, duree, id_formateur, date_creation, photo)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->crud->prepare($sql);
        $params = [
            $formation->getTitre(),
            $formation->getDescription(),
            $formation->getPrix(),
            $formation->getDuree(),
            $formation->getIdFormateur(),
            $formation->getDateCreation(),
            $formation->getPhoto()
        ];
        return $stmt->execute($params);
    }

    // ✅ Récupérer une formation par ID
    public function getFormationById($id) {
        $sql = "SELECT * FROM formations WHERE id_formation = ?";
        $stmt = $this->crud->prepare($sql);
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return new Formation($row['id_formation'], $row['titre'], $row['description'], $row['prix'], $row['duree'], $row['id_formateur'], $row['date_creation'], $row['photo']);
        }
        return null;
    }

    // ✅ Récupérer toutes les formations
    public function getAllFormations() {
        $sql = "SELECT * FROM formations ORDER BY date_creation DESC";
        $stmt = $this->crud->query($sql);
        $formations = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $formations[] = new Formation($row['id_formation'], $row['titre'], $row['description'], $row['prix'], $row['duree'], $row['id_formateur'], $row['date_creation'], $row['photo']);
        }
        return $formations;
    }

    // ✅ Mettre à jour une formation
    public function mettreAJourFormation($formation) {
        $sql = "UPDATE formations SET titre = ?, description = ?, prix = ?, duree = ?, id_formateur = ?, photo = ?
                WHERE id_formation = ?";
        $stmt = $this->crud->prepare($sql);
        $params = [
            $formation->getTitre(),
            $formation->getDescription(),
            $formation->getPrix(),
            $formation->getDuree(),
            $formation->getIdFormateur(),
            $formation->getPhoto(),
            $formation->getIdFormation()
        ];
        return $stmt->execute($params);
    }

    // ✅ Supprimer une formation
    public function supprimerFormation($id) {
        $sql = "DELETE FROM formations WHERE id_formation = ?";
        $stmt = $this->crud->prepare($sql);
        return $stmt->execute([$id]);
    }

    // ✅ Rechercher une formation par mot-clé
    public function rechercherFormations($mot_cle) {
        $sql = "SELECT * FROM formations WHERE titre LIKE ? OR description LIKE ?";
        $param = "%" . $mot_cle . "%";
        $stmt = $this->crud->prepare($sql);
        $stmt->execute([$param, $param]);
        $formations = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $formations[] = new Formation($row['id_formation'], $row['titre'], $row['description'], $row['prix'], $row['duree'], $row['id_formateur'], $row['date_creation'], $row['photo']);
        }
        return $formations;
    }
}
?>
a