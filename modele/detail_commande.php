<?php

include_once('base.php');

class DetailCommande{
    private int $id_detail;
    private int $id_commande;
    private int $id_produit;
    private int $quantite;
    private double $prix_unitaire;

    public function __construct(int $id_detail, int $id_commande, int $id_produit, int $quantite, double $prix_unitaire)
    {
        $this->id_detail = $id_detail;
        $this->id_commande = $id_commande;
        $this->id_produit = $id_produit;
        $this->quantite = $quantite;
        $this->prix_unitaire = $prix_unitaire;
    }
    public function getIdDetail(): int
    {
        return $this->id_detail;
    }
    public function getIdCommande(): int
    {
        return $this->id_commande;
    }
    public function getIdProduit(): int
    {
        return $this->id_produit;
    }
    public function getQuantite(): int
    {
        return $this->quantite;
    }
    public function getPrixUnitaire(): float
    {
        return $this->prix_unitaire;
    }
    public function setIdDetail(int $id_detail): void
    {
        $this->id_detail = $id_detail;
    }
    public function setIdCommande(int $id_commande): void
    {
        $this->id_commande = $id_commande;
    }
    public function setIdProduit(int $id_produit): void
    {
        $this->id_produit = $id_produit;
    }
    public function setQuantite(int $quantite): void
    {
        $this->quantite = $quantite;
    }
    public function setPrixUnitaire(float $prix_unitaire): void
    {
        $this->prix_unitaire = $prix_unitaire;
    }    
}

class RequeteDetailProduit{
    private $crud;
    public function __construct(){
        $pdo = new DataBase();
        $this->crud = $pdo->getConnection();
    }
    
    public function AjouterDetailCommande($detailCommande){
        $sql = "INSERT INTO details_commandes (id_detail, id_commande, id_produit, quantite, prix_unitaire)
        VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->crud->prepare($sql);
        $param =[
            $detailCommande->getIdDetail(),
            $detailCommande->getIdCommande(),
            $detailCommande->getIdProduit(),
            $detailCommande->getQuantite(),
            $detailCommande->getPrixUnitaire()
        ];
       return $stmt->execute($param);
    }

}
?>