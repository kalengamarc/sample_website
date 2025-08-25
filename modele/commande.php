<?php
include_once('base.php');
 class Commande{
    private $id_commande;
    private $id_utilisateur;
    private $date_commande;
    private $statut;
    private $montant_total;

    public function __construct($id_commande,$id_utilisateur,$date_commande,$statut,$montant_total)
    {
        $this->id_commande = $id_commande;
        $this->id_utilisateur = $id_utilisateur;
        $this->date_commande = $date_commande;
        $this->statut = $statut;
        $this->montant_total = $montant_total;
    }
    public function getIdCommande()
    {
        return $this->id_commande;
    }

    public function getIdUtilisateur()
    {
        return $this->id_utilisateur;
    }

    public function getDateCommande()
    {
        return $this->date_commande;
    }

    public function getstatut()
    {
        return $this->statut;
    }

    public function getMontantTotal()
    {
        return $this->montant_total;
    }
    public function setIdCommande($id_commande)
    {
        $this->id_commande = $id_commande;
    }

    public function setIdUtilisateur($id_utilisateur)
    {
        $this->id_utilisateur = $id_utilisateur;
    }

    public function setDateCommande($date_commande)
    {
        $this->date_commande = $date_commande;
    }

    public function setstatut($statut)
    {
        $this->statut = $statut;
    }

    public function setMontantTotal($montant_total)
    {
        $this->montant_total = $montant_total;
    }
 }

class RequeteCommande{
    private $crud;
    public function __construct()
    {
        $pdo = new DataBase();
        $this->crud = $pdo->getConnection();
    }

    // Ajouter une commande
    public function ajouterCommande($commande) {
        $sql = "INSERT INTO commandes (id_utilisateur, date_commande, statut, montant_total) VALUES (?, ?, ?, ?)";
        $stmt = $this->crud->prepare($sql);
        $params = [
            $commande->getIdUtilisateur(),
            $commande->getDateCommande(),
            $commande->getstatut(),
            $commande->getMontantTotal()
        ];
        return $stmt->execute($params);
    }

    // Récupérer une commande par son ID
    public function getCommandeById($id_commande) {
        $sql = "SELECT * FROM commandes WHERE id_commande = ?";
        $stmt = $this->crud->prepare($sql);
        $stmt->execute([$id_commande]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return new Commande($row['id_commande'], $row['id_utilisateur'], $row['date_commande'], $row['statut'], $row['montant_total']);
        }
        return null;
    }

    // Récupérer toutes les commandes d'un utilisateur
    public function getCommandesByUtilisateur($id_utilisateur) {
        $sql = "SELECT * FROM commandes WHERE id_utilisateur = ?";
        $stmt = $this->crud->prepare($sql);
        $stmt->execute([$id_utilisateur]);
        $commandes = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $commandes[] = new Commande($row['id_commande'], $row['id_utilisateur'], $row['date_commande'], $row['statut'], $row['montant_total']);
        }
        return $commandes;
    }

    // Mettre à jour le statut d'une commande
    public function mettreAJourstatut($id_commande, $nouveau_statut) {
        $sql = "UPDATE commandes SET statut = ? WHERE id_commande = ?";
        $stmt = $this->crud->prepare($sql);
        return $stmt->execute([$nouveau_statut, $id_commande]);
    }

    // Supprimer une commande
    public function supprimerCommande($id_commande) {
        $sql = "DELETE FROM commandes WHERE id_commande = ?";
        $stmt = $this->crud->prepare($sql);
        return $stmt->execute([$id_commande]);
    }
    public function getAllCommandes() {
        $sql = "SELECT * FROM commandes";
        $stmt = $this->crud->query($sql);
        $commandes = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $commandes[] = new Commande($row['id_commande'], $row['id_utilisateur'], $row['date_commande'], $row['statut'], $row['montant_total']);
        }
        return $commandes;
}
public function getCommandesBystatut($statut) {
    $sql = "SELECT * FROM commandes WHERE statut = ?";
    $stmt = $this->crud->prepare($sql);
    $stmt->execute([$statut]);
    $commandes = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $commandes[] = new Commande($row['id_commande'], $row['id_utilisateur'], $row['date_commande'], $row['statut'], $row['montant_total']);
    }
    return $commandes;
}
public function getCommandesByDateRange($date_debut, $date_fin) {
    $sql = "SELECT * FROM commandes WHERE date_commande BETWEEN ? AND ?";
    $stmt = $this->crud->prepare($sql);
    $stmt->execute([$date_debut, $date_fin]);
    $commandes = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $commandes[] = new Commande($row['id_commande'], $row['id_utilisateur'], $row['date_commande'], $row['statut'], $row['montant_total']);
    }
    return $commandes;
}   
// ...existing code...
}
?>