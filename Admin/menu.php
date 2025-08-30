<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="../vue/font-awesome/css/all.min.css">

</head>
  <style> 
.gauche {
    width: 20%;
    background: #f4f4f4;
    background-color: #021a12;
    color: white;
    height:94%;
    font-size: 14px;
}

.gauche ul {
    list-style: none;
    padding-left: 0;
    margin: 0;
    margin-left: 40px;
}
  .gauche ul li:nth-child(1){
  padding-top: 10px;
  }
.gauche li {
    margin: 10px 0;
    display: flex;
    flex-direction: column;
    font-size: 14px;
}

/* cacher les radios */
.menu input[type="radio"] {
    display: none;
}

.gauche li label {
    display: flex;
    align-items: center;
    cursor: pointer;
    padding: 8px 10px;
    font-weight: 400;
    font-size: 14px;
}

.gauche li label i {
    margin-right: 10px;
    color: #ffae2b;
}

.gauche li label:hover {
    background-color:#16A34A;
    border-radius: 5px;
    color:white;
}

/* sous-menus */
.submenu {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease;
    list-style: none;
    padding-left: 20px;
    margin-top: 5px;
}

.submenu li a {
    text-decoration: none;
    color: #b9c1be;
    font-size: 14px;
    padding: 3px 0;
    display: block;
}

.submenu li a:hover {
    color: #16A34A;
}

/* ouvrir sous-menu quand radio checké */
.menu input:checked + label + .submenu {
    max-height: 200px;
    font-size: 14px;
}

</style>

<body>
    <div class="gauche">
        <ul class="menu">
            <li>
                <input type="radio" name="accordion" id="accueil">
                <label for="accueil"><i class="fas fa-home"></i>Accueil</label>
                <ul class="submenu">
                    <li>
                        <a href="dashboard.php">Dashboard</a>
                    </li>
                    <li>
                        <a href="actualite.php">Actualités</a>
                    </li>
                </ul>
            </li>

            <li>
                <input type="radio" name="accordion" id="formations">
                <label for="formations">
                    <i class="fas fa-book"></i>
                    Formations
                </label>
                <ul class="submenu">
                    <li>
                        <a href="liste_formation.php">Liste des formations</a>
                    </li>
                    <li>
                        <a href="AjouteFormation.php">Créer une formation</a>
                    </li>
                </ul>
            </li>

            <li>
                <input type="radio" name="accordion" id="participants">
                <label for="participants">
                    <i class="fas fa-users"></i>
                    Participants
                </label>
                <ul class="submenu">
                    <li>
                        <a href="liste_participant.php">Liste des participants</a>
                    </li>
                    <li>
                        <a href="#">Inscrire un participant</a>
                    </li>
                </ul>
            </li>

            <li>
                <input type="radio" name="accordion" id="formateurs">
                <label for="formateurs">
                    <i class="fas fa-chalkboard-teacher"></i>
                    Formateurs
                </label>
                <ul class="submenu">
                    <li>
                        <a href="liste_formateur.php">Liste des formateurs</a>
                    </li>
                    <li>
                        <a href="AjoutFormateur.php">Ajouter un formateur</a>
                    </li>
                </ul>
            </li>

            <li>
                <input type="radio" name="accordion" id="equipements">
                <label for="equipements">
                    <i class="fas fa-tools"></i>
                    Équipements
                </label>
                <ul class="submenu">
                    <li>
                        <a href="liste_equipement.php">
                            Liste des équipements
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            Ajouter un équipement
                        </a>
                    </li>
                </ul>
            </li>

            <li>
                <input type="radio" name="accordion" id="commandes">
                <label for="commandes">
                    <i class="fas fa-shopping-cart"></i>
                    Commandes
                </label>
                <ul class="submenu">
                    <li>
                        <a href="../Admin/_histo_mmandes.php">Toutes les commandes</a>
                    </li>
                    <li>
                        <a href="../Admin/nouvelle_commandes.php">Nouvelle commande</a>
                    </li>
                </ul>
            </li>
            <li>
                <input type="radio" name="accordion" id="paiements">
                <label for="paiements">
                    <i class="fas fa-credit-card"></i>
                    Paiements
                </label>
                <ul class="submenu">
                    <li>
                        <a href="../Admin/paiements.php">Historique</a>
                    </li>
                    <li>
                        <a href="../Admin/paiement.php">Effectuer un paiement</a>
                    </li>
                </ul>
            </li>

            <li>
                <input type="radio" name="accordion" id="rapports">
                <label for="rapports">
                    <i class="fas fa-chart-pie"></i>Rapports
                </label>
                <ul class="submenu">
                    <li>
                        <a href="../Admin/rapport_mensuel.php">Rapport mensuel</a>
                    </li>
                    <li>
                        <a href="../Admin/rapport_annuel.php">Rapport annuel</a>
                    </li>
                </ul>
            </li>

            <li>
                <input type="radio" name="accordion" id="parametres">
                <label for="parametres">
                    <i class="fas fa-cog"></i>
                    Paramètres
                </label>
                <ul class="submenu">
                    <li>
                        <a href="#">Général</a>
                    </li>
                    <li>
                        <a href="#">Sécurité</a>
                    </li>
                    <li>
                        <a href="../Admin/profile_profile.php">Profil</a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</body>
</html>