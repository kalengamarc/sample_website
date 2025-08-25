<?php
require_once 'commande.php';

// Création d'une commande de test
$commande = new Commande(
    null,           // id_commande (auto-incrémenté)
    5,              // id_utilisateur (à adapter selon ta base)
    date('Y-m-d H:i:s'),
    'en attente',
    99.99
);

$requete = new RequeteCommande();

// Test ajout commande
echo "Ajout commande : ";
$result = $requete->ajouterCommande($commande);
var_dump($result);

// Test récupération de toutes les commandes
echo "<br>Liste de toutes les commandes : ";
$all = $requete->getAllCommandes();
var_dump($all);

// Test récupération par statut
echo "<br>Commandes 'en attente' : ";
$attente = $requete->getCommandesBystatut('en attente');
var_dump($attente);

// Test récupération par utilisateur
echo "<br>Commandes de l'utilisateur 1 : ";
$userCmds = $requete->getCommandesByUtilisateur(1);
var_dump($userCmds);

// Test récupération par plage de dates
echo "<br>Commandes du mois : ";
$debut = date('Y-m-01 00:00:00');
$fin = date('Y-m-t 23:59:59');
$range = $requete->getCommandesByDateRange($debut, $fin);
var_dump($range);

// Test mise à jour du statut
if (!empty($all)) {
    $id = $all[0]->getIdCommande();
    echo "<br>Mise à jour statut commande $id : ";
    $maj = $requete->mettreAJourstatut($id, 'livrée');
    var_dump($maj);
}

// Test suppression
if (!empty($all)) {
    $id = $all[0]->getIdCommande();
    echo "<br>Suppression commande $id : ";
    $suppr = $requete->supprimerCommande($id);
    var_dump($suppr);
}
?>