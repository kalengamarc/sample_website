<?php
class Panier {
    private $id_panier;
    private $id_utilisateur;
    private $id_produit;
    private $quantite;
    private $date_ajout;
    private $date_modification;

    // Constructeur
    public function __construct($id_panier, $id_utilisateur, $id_produit, $quantite = 1, 
                               $date_ajout = null, $date_modification = null) {
        $this->id_panier = $id_panier;
        $this->id_utilisateur = $id_utilisateur;
        $this->id_produit = $id_produit;
        $this->setQuantite($quantite);
        $this->date_ajout = $date_ajout ?: date('Y-m-d H:i:s');
        $this->date_modification = $date_modification ?: date('Y-m-d H:i:s');
    }

    // Getters
    public function getIdPanier() { return $this->id_panier; }
    public function getIdUtilisateur() { return $this->id_utilisateur; }
    public function getIdProduit() { return $this->id_produit; }
    public function getQuantite() { return $this->quantite; }
    public function getDateAjout() { return $this->date_ajout; }
    public function getDateModification() { return $this->date_modification; }

    // Setters
    public function setIdPanier($id) { $this->id_panier = $id; }
    public function setIdUtilisateur($id) { $this->id_utilisateur = $id; }
    public function setIdProduit($id) { $this->id_produit = $id; }
    public function setQuantite($quantite) { 
        if ($quantite <= 0) {
            throw new InvalidArgumentException("La quantité doit être positive");
        }
        $this->quantite = $quantite;
        $this->date_modification = date('Y-m-d H:i:s');
    }
    public function setDateAjout($date) { $this->date_ajout = $date; }
    public function setDateModification($date) { $this->date_modification = $date; }

    // Méthodes utilitaires
    public function augmenterQuantite($quantite = 1) {
        $this->setQuantite($this->quantite + $quantite);
    }

    public function diminuerQuantite($quantite = 1) {
        if ($this->quantite - $quantite <= 0) {
            throw new InvalidArgumentException("La quantité ne peut pas être négative ou nulle");
        }
        $this->setQuantite($this->quantite - $quantite);
    }

    public function isValid() {
        return $this->id_utilisateur !== null && 
               $this->id_produit !== null && 
               $this->quantite > 0;
    }
}
?>

<?php

require_once 'base.php';

class PanierCRUD {
    private $pdo;

    public function __construct() {
        $this->pdo = getPDOConnection();
    }

    // CREATE
    public function create(Panier $panier) {
        if (!$panier->isValid()) {
            throw new InvalidArgumentException("Le panier n'est pas valide");
        }

        // Vérifier si le produit est déjà dans le panier
        $existing = $this->getByUtilisateurAndProduit($panier->getIdUtilisateur(), $panier->getIdProduit());
        if ($existing) {
            $existing->augmenterQuantite($panier->getQuantite());
            return $this->update($existing);
        }

        $sql = "INSERT INTO panier (id_utilisateur, id_produit, quantite, date_ajout, date_modification) 
                VALUES (:id_utilisateur, :id_produit, :quantite, :date_ajout, :date_modification)";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':id_utilisateur' => $panier->getIdUtilisateur(),
            ':id_produit' => $panier->getIdProduit(),
            ':quantite' => $panier->getQuantite(),
            ':date_ajout' => $panier->getDateAjout(),
            ':date_modification' => $panier->getDateModification()
        ]);

        $panier->setIdPanier($this->pdo->lastInsertId());
        return $panier;
    }

    // READ
    public function getById($id) {
        $sql = "SELECT * FROM panier WHERE id_panier = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$data) {
            return null;
        }
        
        return $this->mapToPanier($data);
    }

    public function getByUtilisateur($id_utilisateur) {
        $sql = "SELECT * FROM panier WHERE id_utilisateur = :id_utilisateur ORDER BY date_ajout DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id_utilisateur' => $id_utilisateur]);
        
        $paniers = [];
        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $paniers[] = $this->mapToPanier($data);
        }
        
        return $paniers;
    }

    public function getByUtilisateurAndProduit($id_utilisateur, $id_produit) {
        $sql = "SELECT * FROM panier WHERE id_utilisateur = :id_utilisateur AND id_produit = :id_produit";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':id_utilisateur' => $id_utilisateur,
            ':id_produit' => $id_produit
        ]);
        
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$data) {
            return null;
        }
        
        return $this->mapToPanier($data);
    }

    public function getTotalQuantiteByUtilisateur($id_utilisateur) {
        $sql = "SELECT SUM(quantite) as total FROM panier WHERE id_utilisateur = :id_utilisateur";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id_utilisateur' => $id_utilisateur]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
    }

    // UPDATE
    public function update(Panier $panier) {
        $sql = "UPDATE panier SET 
                id_utilisateur = :id_utilisateur,
                id_produit = :id_produit,
                quantite = :quantite,
                date_ajout = :date_ajout,
                date_modification = :date_modification
                WHERE id_panier = :id_panier";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':id_utilisateur' => $panier->getIdUtilisateur(),
            ':id_produit' => $panier->getIdProduit(),
            ':quantite' => $panier->getQuantite(),
            ':date_ajout' => $panier->getDateAjout(),
            ':date_modification' => $panier->getDateModification(),
            ':id_panier' => $panier->getIdPanier()
        ]);
    }

    public function updateQuantite($id_panier, $quantite) {
        $panier = $this->getById($id_panier);
        if ($panier) {
            $panier->setQuantite($quantite);
            return $this->update($panier);
        }
        return false;
    }

    // DELETE
    public function delete($id) {
        $sql = "DELETE FROM panier WHERE id_panier = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function deleteByUtilisateur($id_utilisateur) {
        $sql = "DELETE FROM panier WHERE id_utilisateur = :id_utilisateur";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id_utilisateur' => $id_utilisateur]);
    }

    public function deleteByUtilisateurAndProduit($id_utilisateur, $id_produit) {
        $sql = "DELETE FROM panier WHERE id_utilisateur = :id_utilisateur AND id_produit = :id_produit";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':id_utilisateur' => $id_utilisateur,
            ':id_produit' => $id_produit
        ]);
    }

    // UTILS
    private function mapToPanier($data) {
        return new Panier(
            $data['id_panier'],
            $data['id_utilisateur'],
            $data['id_produit'],
            $data['quantite'],
            $data['date_ajout'],
            $data['date_modification']
        );
    }
}
?>