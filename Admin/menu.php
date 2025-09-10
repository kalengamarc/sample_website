<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Menu Administratif</title>
  <link rel="stylesheet" href="../vue/font-awesome/css/all.min.css">
  <style>
    /* Styles généraux */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f8fafc;
    }
    
    .gauche {
      width: 280px;
      background: linear-gradient(135deg, #04221a 0%, #2c5f2d 100%);
      color: white;
      height: 100vh;
      font-size: 14px;
      overflow-y: auto;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
      position: relative;
      z-index: 100;
    }
    
    /* En-tête du menu */
    .menu-header {
      padding: 20px;
      text-align: center;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
      margin-bottom: 10px;
    }
    
    .menu-header h2 {
      font-size: 18px;
      font-weight: 600;
      margin: 10px 0 5px;
      color: white;
    }
    
    .menu-header p {
      font-size: 12px;
      color: rgba(255, 255, 255, 0.7);
    }
    
    .menu-logo {
      width: 50px;
      height: 50px;
      margin: 0 auto;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 20px;
      color: #ffae2b;
    }
    
    .menu {
      padding: 0 15px 20px;
    }
    
    .gauche ul {
      list-style: none;
      padding-left: 0;
      margin: 0;
    }
    
    .gauche li {
      margin: 5px 0;
      font-size: 14px;
      position: relative;
    }
    
    /* Cacher les radios */
    .menu input[type="radio"] {
      display: none;
    }
    
    .gauche li label {
      display: flex;
      align-items: center;
      cursor: pointer;
      padding: 12px 15px;
      font-weight: 500;
      font-size: 14px;
      border-radius: 8px;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }
    
    .gauche li label:before {
      content: '';
      position: absolute;
      left: 0;
      top: 0;
      height: 100%;
      width: 4px;
      background: #ffae2b;
      opacity: 0;
      transition: opacity 0.3s ease;
    }
    
    .gauche li label i {
      margin-right: 12px;
      color: #ffae2b;
      width: 20px;
      text-align: center;
      font-size: 16px;
      transition: all 0.3s ease;
    }
    
    .gauche li label .arrow {
      margin-left: auto;
      font-size: 12px;
      transition: transform 0.3s ease;
    }
    
    .gauche li label:hover {
      background-color: rgba(255, 255, 255, 0.1);
      color: white;
    }
    
    .gauche li label:hover:before {
      opacity: 1;
    }
    
    .gauche li input:checked + label {
      background-color: rgba(22, 163, 74, 0.2);
      color: white;
    }
    
    .gauche li input:checked + label:before {
      opacity: 1;
    }
    
    .gauche li input:checked + label .arrow {
      transform: rotate(90deg);
    }
    
    /* Sous-menus */
    .submenu {
      max-height: 0;
      overflow: hidden;
      transition: max-height 0.4s ease;
      list-style: none;
      padding-left: 20px;
      margin: 0;
      background: rgba(0, 0, 0, 0.1);
      border-radius: 8px;
    }
    
    .submenu li {
      margin: 0;
      padding: 0;
    }
    
    .submenu li a {
      text-decoration: none;
      color: rgba(255, 255, 255, 0.8);
      font-size: 13.5px;
      padding: 10px 15px;
      display: block;
      transition: all 0.3s ease;
      border-left: 2px solid transparent;
      position: relative;
    }
    
    .submenu li a:hover {
      color: white;
      background: rgba(255, 255, 255, 0.05);
      border-left-color: #ffae2b;
      padding-left: 20px;
    }
    
    .submenu li a:before {
      content: "•";
      position: absolute;
      left: 5px;
      color: #ffae2b;
      opacity: 0;
      transition: opacity 0.3s ease;
    }
    
    .submenu li a:hover:before {
      opacity: 1;
    }
    
    /* Ouvrir sous-menu quand radio checké */
    .menu input:checked + label + .submenu {
      max-height: 500px;
      padding: 8px 0;
      margin: 5px 0 10px;
    }
    
    /* Indicateur de page active */
    .submenu li a.active {
      color: white;
      background: rgba(22, 163, 74, 0.2);
      border-left-color: #16A34A;
      padding-left: 20px;
    }
    
    .submenu li a.active:before {
      opacity: 1;
    }
    
    /* Footer du menu */
    .menu-footer {
      padding: 15px 20px;
      border-top: 1px solid rgba(255, 255, 255, 0.1);
      margin-top: 20px;
      text-align: center;
      font-size: 12px;
      color: rgba(255, 255, 255, 0.6);
    }
    
    /* Version responsive */
    @media (max-width: 768px) {
      .gauche {
        width: 70px;
        overflow: visible;
      }
      
      .menu-header, .menu-footer, .gauche li label span:not(.arrow), .submenu {
        display: none;
      }
      
      .gauche li label {
        justify-content: center;
        padding: 15px 10px;
      }
      
      .gauche li label i {
        margin-right: 0;
        font-size: 18px;
      }
      
      .gauche:hover {
        width: 280px;
      }
      
      .gauche:hover .menu-header, 
      .gauche:hover .menu-footer, 
      .gauche:hover .gauche li label span:not(.arrow), 
      .gauche:hover .submenu {
        display: block;
      }
      
      .gauche:hover .gauche li label {
        justify-content: flex-start;
        padding: 12px 15px;
      }
      
      .gauche:hover .gauche li label i {
        margin-right: 12px;
        font-size: 16px;
      }
    }
    
    /* Animation d'apparition */
    @keyframes fadeIn {
      from { opacity: 0; transform: translateX(-10px); }
      to { opacity: 1; transform: translateX(0); }
    }
    
    .gauche li {
      animation: fadeIn 0.4s ease forwards;
    }
    
    .gauche li:nth-child(1) { animation-delay: 0.05s; }
    .gauche li:nth-child(2) { animation-delay: 0.1s; }
    .gauche li:nth-child(3) { animation-delay: 0.15s; }
    .gauche li:nth-child(4) { animation-delay: 0.2s; }
    .gauche li:nth-child(5) { animation-delay: 0.25s; }
    .gauche li:nth-child(6) { animation-delay: 0.3s; }
    .gauche li:nth-child(7) { animation-delay: 0.35s; }
    .gauche li:nth-child(8) { animation-delay: 0.4s; }
    .gauche li:nth-child(9) { animation-delay: 0.45s; }
  </style>
</head>
<body>
    <div class="gauche">
        <div class="menu-header">
            <div class="menu-logo">
                <i class="fas fa-bolt"></i>
            </div>
            <h2>Espace Admin</h2>
            <p>Tableau de bord</p>
        </div>
        
        <ul class="menu">
            <li>
                <input type="radio" name="accordion" id="accueil">
                <label for="accueil">
                    <i class="fas fa-home"></i>
                    <span>Accueil</span>
                    <span class="arrow"><i class="fas fa-chevron-right"></i></span>
                </label>
                <ul class="submenu">
                    <li>
                        <a href="dashboard.php" class="active">Dashboard</a>
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
                    <span>Formations</span>
                    <span class="arrow"><i class="fas fa-chevron-right"></i></span>
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
                    <span>Participants</span>
                    <span class="arrow"><i class="fas fa-chevron-right"></i></span>
                </label>
                <ul class="submenu">
                    <li>
                        <a href="liste_participant.php">Liste des participants</a>
                    </li>
                    <li>
                        <a href="AjoutParticipant.php">Inscrire un participant</a>
                    </li>
                </ul>
            </li>

            <li>
                <input type="radio" name="accordion" id="formateurs">
                <label for="formateurs">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <span>Formateurs</span>
                    <span class="arrow"><i class="fas fa-chevron-right"></i></span>
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
                    <span>Équipements</span>
                    <span class="arrow"><i class="fas fa-chevron-right"></i></span>
                </label>
                <ul class="submenu">
                    <li>
                        <a href="liste_equipement.php">Liste des équipements</a>
                    </li>
                    <li>
                        <a href="AjoutProduit.php">Ajouter un équipement</a>
                    </li>
                </ul>
            </li>

            <li>
                <input type="radio" name="accordion" id="commandes">
                <label for="commandes">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Commandes</span>
                    <span class="arrow"><i class="fas fa-chevron-right"></i></span>
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
                    <span>Paiements</span>
                    <span class="arrow"><i class="fas fa-chevron-right"></i></span>
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
                    <i class="fas fa-chart-pie"></i>
                    <span>Rapports</span>
                    <span class="arrow"><i class="fas fa-chevron-right"></i></span>
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
                    <span>Paramètres</span>
                    <span class="arrow"><i class="fas fa-chevron-right"></i></span>
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
        
        <div class="menu-footer">
            <p>JosNet Admin v1.0</p>
            <p>&copy; 2023 Tous droits réservés</p>
        </div>
    </div>
</body>
</html>