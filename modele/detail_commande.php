<?php

include_once('base.php');

class DetailCommande {
    private int $id_detail;
    private int $id_commande;
    private int $id_produit;
    private int $quantite;
    private float $prix_unitaire;

    public function __construct(int $id_detail, int $id_commande, int $id_produit, int $quantite, float $prix_unitaire)
    {
        $this->id_detail = $id_detail;
        $this->id_commande = $id_commande;
        $this->id_produit = $id_produit;
        $this->quantite = $quantite;
        $this->prix_unitaire = $prix_unitaire;
    }

    // --- GETTERS ---
    public function getIdDetail(): int { return $this->id_detail; }
    public function getIdCommande(): int { return $this->id_commande; }
    public function getIdProduit(): int { return $this->id_produit; }
    public function getQuantite(): int { return $this->quantite; }
    public function getPrixUnitaire(): float { return $this->prix_unitaire; }

    // --- SETTERS ---
    public function setIdDetail(int $id_detail): void { $this->id_detail = $id_detail; }
    public function setIdCommande(int $id_commande): void { $this->id_commande = $id_commande; }
    public function setIdProduit(int $id_produit): void { $this->id_produit = $id_produit; }
    public function setQuantite(int $quantite): void { $this->quantite = $quantite; }
    public function setPrixUnitaire(float $prix_unitaire): void { $this->prix_unitaire = $prix_unitaire; }
}

class RequeteDetailProduit {
    private $crud;

    public function __construct(){
        $pdo = new DataBase();
        $this->crud = $pdo->getConnection();
    }

    // ✅ Ajouter un détail
    public function AjouterDetailCommande(DetailCommande $detailCommande){
        $sql = "INSERT INTO details_commandes (id_detail, id_commande, id_produit, quantite, prix_unitaire)
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->crud->prepare($sql);
        $param = [
            $detailCommande->getIdDetail(),
            $detailCommande->getIdCommande(),
            $detailCommande->getIdProduit(),
            $detailCommande->getQuantite(),
            $detailCommande->getPrixUnitaire()
        ];
        return $stmt->execute($param);
    }

    // ✅ Lire un détail par ID
    public function getDetailById(int $id_detail): ?array {
        $sql = "SELECT * FROM details_commandes WHERE id_detail = ?";
        $stmt = $this->crud->prepare($sql);
        $stmt->execute([$id_detail]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    // ✅ Lister les détails d’une commande
    public function getDetailsByCommande(int $id_commande): array {
        $sql = "SELECT * FROM details_commandes WHERE id_commande = ?";
        $stmt = $this->crud->prepare($sql);
        $stmt->execute([$id_commande]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ✅ Mettre à jour un détail
    public function updateDetailCommande(DetailCommande $detailCommande): bool {
        $sql = "UPDATE details_commandes 
                SET id_commande = ?, id_produit = ?, quantite = ?, prix_unitaire = ?
                WHERE id_detail = ?";
        $stmt = $this->crud->prepare($sql);
        $param = [
            $detailCommande->getIdCommande(),
            $detailCommande->getIdProduit(),
            $detailCommande->getQuantite(),
            $detailCommande->getPrixUnitaire(),
            $detailCommande->getIdDetail()
        ];
        return $stmt->execute($param);
    }

    // ✅ Supprimer un détail
    public function deleteDetailCommande(int $id_detail): bool {
        $sql = "DELETE FROM details_commandes WHERE id_detail = ?";
        $stmt = $this->crud->prepare($sql);
        return $stmt->execute([$id_detail]);
    }

    // ✅ Calculer le total d’une commande
    public function getTotalCommande(int $id_commande): float {
        $sql = "SELECT SUM(quantite * prix_unitaire) as total FROM details_commandes WHERE id_commande = ?";
        $stmt = $this->crud->prepare($sql);
        $stmt->execute([$id_commande]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? (float)$row['total'] : 0.0;
    }
}
?>
