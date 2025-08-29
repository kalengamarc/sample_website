<?php
include_once(__DIR__ . "/../modeles/Commande.php");

class CommandeController {
    private $commandeDAO;

    public function __construct() {
        $this->commandeDAO = new RequeteCommande();
    }

    // Ajouter une commande
    public function ajouterCommande($id_utilisateur, $date_commande, $statut, $montant_total) {
        $commande = new Commande(null, $id_utilisateur, $date_commande, $statut, $montant_total);
        return $this->commandeDAO->ajouterCommande($commande);
    }

    // Récupérer une commande par ID
    public function getCommandeById($id_commande) {
        return $this->commandeDAO->getCommandeById($id_commande);
    }

    // Récupérer toutes les commandes
    public function getAllCommandes() {
        return $this->commandeDAO->getAllCommandes();
    }

    // Récupérer les commandes par utilisateur
    public function getCommandesByUtilisateur($id_utilisateur) {
        return $this->commandeDAO->getCommandesByUtilisateur($id_utilisateur);
    }

    // Récupérer les commandes par statut
    public function getCommandesByStatut($statut) {
        return $this->commandeDAO->getCommandesByStatut($statut);
    }

    // Récupérer les commandes entre deux dates
    public function getCommandesByDateRange($date_debut, $date_fin) {
        return $this->commandeDAO->getCommandesByDateRange($date_debut, $date_fin);
    }

    // Mettre à jour le statut d’une commande
    public function mettreAJourStatut($id_commande, $nouveau_statut) {
        return $this->commandeDAO->mettreAJourStatut($id_commande, $nouveau_statut);
    }

    // Supprimer une commande
    public function supprimerCommande($id_commande) {
        return $this->commandeDAO->supprimerCommande($id_commande);
    }
}
?>
