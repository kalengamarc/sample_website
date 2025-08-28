<?php
class Produit {
    private $id_produit;
    private $nom;
    private $description;
    private $prix;
    private $stock;
    private $categorie;
    private $date_ajout;
    private $photo;

    // Constructeur
    public function __construct($nom, $description, $prix, $stock, $categorie, $photo, $date_ajout = null) {
        $this->nom = $nom;
        $this->description = $description;
        $this->prix = $prix;
        $this->stock = $stock;
        $this->categorie = $categorie;
        $this->photo = $photo;
        $this->date_ajout = $date_ajout;
    }

    // Getters
    public function getIdProduit() { return $this->id_produit; }
    public function getNom() { return $this->nom; }
    public function getDescription() { return $this->description; }
    public function getPrix() { return $this->prix; }
    public function getStock() { return $this->stock; }
    public function getCategorie() { return $this->categorie; }
    public function getDateAjout() { return $this->date_ajout; }
    public function getPhoto() { return $this->photo; }

    // Setters
    public function setIdProduit($id) { $this->id_produit = $id; }
    public function setNom($nom) { $this->nom = $nom; }
    public function setDescription($desc) { $this->description = $desc; }
    public function setPrix($prix) { $this->prix = $prix; }
    public function setStock($stock) { $this->stock = $stock; }
    public function setCategorie($cat) { $this->categorie = $cat; }
    public function setDateAjout($date) { $this->date_ajout = $date; }
    public function setPhoto($photo) { $this->photo = $photo; }
}
?>
<?php
include_once('base.php');

class CRUDProduit {
    private $connexion;

    public function __construct() {
        $db = new Database();
        $this->connexion = $db->getConnexion();
    }

    // Ajouter un produit
    public function ajouterProduit(Produit $p) {
        $sql = "INSERT INTO produit (nom, description, prix, stock, categorie, photo) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->connexion->prepare($sql);
        $ok = $stmt->execute([
            $p->getNom(),
            $p->getDescription(),
            $p->getPrix(),
            $p->getStock(),
            $p->getCategorie(),
            $p->getPhoto()
        ]);
        if($ok) $p->setIdProduit($this->connexion->lastInsertId());
        return $ok;
    }

    // Lire un produit par ID
    public function getProduitById($id) {
        $sql = "SELECT * FROM produit WHERE id_produit = ?";
        $stmt = $this->connexion->prepare($sql);
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->hydrate($row) : null;
    }

    // Lire tous les produits
    public function getAllProduits() {
        $sql = "SELECT * FROM produit ORDER BY date_ajout DESC";
        $stmt = $this->connexion->query($sql);
        $produits = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $produits[] = $this->hydrate($row);
        }
        return $produits;
    }

    // Mettre à jour un produit
    public function updateProduit(Produit $p) {
        $sql = "UPDATE produit SET nom = ?, description = ?, prix = ?, stock = ?, categorie = ?, photo = ? WHERE id_produit = ?";
        $stmt = $this->connexion->prepare($sql);
        return $stmt->execute([
            $p->getNom(),
            $p->getDescription(),
            $p->getPrix(),
            $p->getStock(),
            $p->getCategorie(),
            $p->getPhoto(),
            $p->getIdProduit()
        ]);
    }

    // Supprimer un produit
    public function deleteProduit($id) {
        $sql = "DELETE FROM produit WHERE id_produit = ?";
        $stmt = $this->connexion->prepare($sql);
        return $stmt->execute([$id]);
    }

    // Hydrater un objet Produit à partir d'un tableau
    private function hydrate($data) {
        $p = new Produit(
            $data['nom'],
            $data['description'],
            $data['prix'],
            $data['stock'],
            $data['categorie'],
            $data['photo'],
            $data['date_ajout']
        );
        $p->setIdProduit($data['id_produit']);
        return $p;
    }
}
?>
