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
    private $debut_formation;
    private $photo;

    public function __construct($id_formation, $titre, $description, $prix, $duree, $id_formateur, $date_creation, $debut_formation, $photo) {
        $this->id_formation = $id_formation;
        $this->titre = $titre;
        $this->description = $description;
        $this->prix = $prix;
        $this->duree = $duree;
        $this->id_formateur = $id_formateur;
        $this->date_creation = $date_creation;
        $this->debut_formation = $debut_formation;
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
    public function getDebutFormation() { return $this->debut_formation; }
    public function getPhoto() { return $this->photo; }

    // --- SETTERS ---
    public function setTitre($titre) { $this->titre = $titre; }
    public function setDescription($description) { $this->description = $description; }
    public function setPrix($prix) { $this->prix = $prix; }
    public function setDuree($duree) { $this->duree = $duree; }
    public function setIdFormateur($id_formateur) { $this->id_formateur = $id_formateur; }
    public function setDateCreation($date_creation) { $this->date_creation = $date_creation; }
    public function setDebutFormation($debut_formation) { $this->debut_formation = $debut_formation; }
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
        $sql = "INSERT INTO formations (titre, description, prix, duree, id_formateur, date_creation, debut_formation, photo)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->crud->prepare($sql);
        $params = [
            $formation->getTitre(),
            $formation->getDescription(),
            $formation->getPrix(),
            $formation->getDuree(),
            $formation->getIdFormateur(),
            $formation->getDateCreation(),
            $formation->getDebutFormation(),
            $formation->getPhoto()
        ];
        return $stmt->execute($params);
    }

    // Récupérer une formation par ID (alias de getFormationById pour la compatibilité)
    public function getById($id) {
        return $this->getFormationById($id);
    }

    // Récupérer une formation par ID
    public function getFormationById($id) {
        $sql = "SELECT * FROM formations WHERE id_formation = ?";
        $stmt = $this->crud->prepare($sql);
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return new Formation($row['id_formation'], $row['titre'], $row['description'], $row['prix'], $row['duree'], $row['id_formateur'], $row['date_creation'], $row['debut_formation'], $row['photo']);
        }
        return null;
    }

    // Récupérer toutes les formations
    public function getAllFormations() {
        $sql = "SELECT * FROM formations ORDER BY date_creation DESC";
        $stmt = $this->crud->query($sql);
        $formations = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $formations[] = new Formation($row['id_formation'], $row['titre'], $row['description'], $row['prix'], $row['duree'], $row['id_formateur'], $row['date_creation'], $row['debut_formation'], $row['photo']);
        }
        return $formations;
    }

    // Mettre à jour une formation
    public function mettreAJourFormation($formation) {
        $sql = "UPDATE formations SET titre = ?, description = ?, prix = ?, duree = ?, id_formateur = ?, debut_formation = ?, photo = ?
                WHERE id_formation = ?";
        $stmt = $this->crud->prepare($sql);
        $params = [
            $formation->getTitre(),
            $formation->getDescription(),
            $formation->getPrix(),
            $formation->getDuree(),
            $formation->getIdFormateur(),
            $formation->getDebutFormation(),
            $formation->getPhoto(),
            $formation->getIdFormation()
        ];
        return $stmt->execute($params);
    }

    // Supprimer une formation avec gestion des contraintes
    public function supprimerFormation($id) {
        try {
            // Commencer une transaction
            $this->crud->beginTransaction();
            
            // 1. D'abord récupérer les IDs des inscriptions pour cette formation
            $sqlGetInscriptions = "SELECT id_inscription FROM inscriptions WHERE id_formation = ?";
            $stmtGetInscriptions = $this->crud->prepare($sqlGetInscriptions);
            $stmtGetInscriptions->execute([$id]);
            $inscriptions = $stmtGetInscriptions->fetchAll(PDO::FETCH_COLUMN);
            
            // 2. Supprimer les présences liées à ces inscriptions
            if (!empty($inscriptions)) {
                $placeholders = str_repeat('?,', count($inscriptions) - 1) . '?';
                $sqlPresences = "DELETE FROM presences WHERE id_inscription IN ($placeholders)";
                $stmtPresences = $this->crud->prepare($sqlPresences);
                $stmtPresences->execute($inscriptions);
            }
            
            // 3. Supprimer les inscriptions (contrainte RESTRICT)
            $sqlInscriptions = "DELETE FROM inscriptions WHERE id_formation = ?";
            $stmtInscriptions = $this->crud->prepare($sqlInscriptions);
            $stmtInscriptions->execute([$id]);
            
            // 4. Supprimer la formation (les autres tables ont CASCADE)
            $sqlFormation = "DELETE FROM formations WHERE id_formation = ?";
            $stmtFormation = $this->crud->prepare($sqlFormation);
            $result = $stmtFormation->execute([$id]);
            
            // Valider la transaction
            $this->crud->commit();
            return $result;
            
        } catch (Exception $e) {
            // Annuler la transaction en cas d'erreur
            $this->crud->rollback();
            throw new Exception("Erreur lors de la suppression de la formation: " . $e->getMessage());
        }
    }

    // ✅ Rechercher une formation par mot-clé
    public function rechercherFormations($mot_cle) {
        $sql = "SELECT * FROM formations WHERE titre LIKE ? OR description LIKE ?";
        $param = "%" . $mot_cle . "%";
        $stmt = $this->crud->prepare($sql);
        $stmt->execute([$param, $param]);
        $formations = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $formations[] = new Formation($row['id_formation'], $row['titre'], $row['description'], $row['prix'], $row['duree'], $row['id_formateur'], $row['date_creation'], $row['debut_formation'], $row['photo']);
        }
        return $formations;
    }
}
?>