<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashborad</title>
    <link rel="stylesheet" href="../vue/font-awesome/css/all.min.css">
    <link rel="stylesheet" href="../vue/styles/stylemenu.css">
    <?php
    include_once('../controle/controleur_formation.php');
    include_once('../controle/controleur_utilisateur.php');
    $utilisateur = new UtilisateurController();
    $formation = new FormationController();
    $listeFormation = $formation->getAllFormations();
    ?>
</head>
<style>
       .header_dash{
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    .cont_dash{
        width: 100%;
        height: 100vh;
        background-color:  #00110a;
        border-radius: 5px;
    }
    
    .ail_fle{
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: space-between;
    }
    .dashcontainu{
        width: 80%;
        height: 94%;
        background-color: #b9c1be;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .tabl_contnu{
        width: 100%;
        height: 100%;
        background-color: #b9c1be;;
        color: black;
        overflow: scroll;
        text-align:center;
    }
    .tabl_contnu::-webkit-scrollbar{
        display: none;
    }
    .tabl_contnu button{
        color:black;
        padding:4px;
        border-radius:20px;
        border:none;
        display:flex;
        gap:5px;
        align-items:center;
        font-size:15px;
        background
    }
    .tabl_contnu button:hover{
        cursor:pointer;
        background:gold;
    }
    tr:nth-child(2){
        padding:10px;
    }
   table .icons_actuality{
    color:gold;
    display:flex;
    gap:10px;
    font-size:16px;
   }
   table .icons_actuality a i{
    color:rgba(0,0,0,0.7);
   }
   table .icons_actuality i:hover{
    cursor:pointer;
    color: #00110a;
    transform: scale(1.2);
    transition: all 0.3s ease;
   }
   





   /* DATA-TOOLTIP */
   

  .voir{
       position: relative;
    }
    .voir:hover::after{
        position: absolute;
        content: attr(data-tooltip);
        padding: 2px 10px;
        text-align: center;
        top: 30px;
         background-color:gold;
        left: -15px;
        font-size: 13px;
        color: white;
        border-radius: 2px;
        color:  rgba(0,0,0,0.7);
        transition: transform 0.3; 
    }
    
     .modifier{
        position: relative;
    }
    .modifier:hover::after{
        position: absolute;
        content: attr(data-tooltip);
        padding: 2px 5px;
        text-align: center;
        top: 30px;
        background-color: gold;
        left: -15px;
        font-size: 13px;
        color: white;
        border-radius: 5px;
        color:  rgba(0,0,0,0.7);
    }
    
    .supprimer_{
        position: relative;
    }
    .supprimer_:hover::after{
        position: absolute;
        content: attr(data-tooltip);
        padding: 2px 10px;
        text-align: center;
        top: 30px;
        background-color: gold;
        left: -15px;
        font-size: 13px;
        border-radius: 5px;
        color:  rgba(0,0,0,0.7);
        border:1px solid rgba(224, 10, 10, 0.06);
    }

    .ajouter{
       position: relative;
    }
    .ajouter:hover::after{
        position: absolute;
        content: attr(data-tooltip);
        padding: 2px 10px;
        text-align: center;
        top: 30px;
         background-color:gold;
        left: -15px;
        font-size: 13px;
        color: white;
        border-radius: 2px;
        color:  rgba(0,0,0,0.7);
        transition: transform 0.3; 
    }



    .ajouter a{
        padding:5px;
        background:black;
        border-radius:50%;
        position:abso lute;
        top:30px;
        color:white;
        text-align:center;
        border:3px solid white;
    }


.titre_formation {
    width: 100%;
    height:50px;
    gap:30px;
    display:flex;
    align-items:center;
    justify-content:center;
    position:fix ed;
}


 table {
    border:2px solid red;
    margin-top:20px;
      border-collapse: collapse;
      width: 100%;
      background: white;
      overflow: hidden;
      box-shadow: 0 4px 15px rgba(0,0,0,0.15);
      border-radius: 10px;
    }

    th, td {
      padding: 15px 12px;
      text-align: left;
    }

    th {
      background: linear-gradient(135deg, #021a12, #00110a);
      font-weight: bold;
      color: #ffae2b;
      font-size: 14px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    tr:not(:last-child) {
      border-bottom: 1px solid #e5e5e5;
    }
    
    tr:hover {
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        transition: all 0.3s ease;
    }

    td {
      font-size: 14px;
      font-weight: 500;
    }
    
    .formation-image {
        width: 50px;
        height: 50px;
        border-radius: 8px;
        object-fit: cover;
        border: 3px solid #00110a;
        box-shadow: 0 3px 10px rgba(0,0,0,0.2);
        transition: transform 0.3s ease;
    }
    
    .formation-image:hover {
        transform: scale(1.1);
    }
    
    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .status-en-cours {
        background: #dcfce7;
        color: #166534;
    }
    
    .status-termine {
        background: #fee2e2;
        color: #dc2626;
    }
    
    .status-bientot {
        background: #fef3c7;
        color: #92400e;
    }

    /* Styles pour la modal */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.8);
        backdrop-filter: blur(8px);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 1000;
        opacity: 0;
        transition: all 0.3s ease;
    }

    .modal-overlay.active {
        display: flex;
        opacity: 1;
    }

    .formation-card {
        background: linear-gradient(135deg, #ffffff, #f8fafc);
        border-radius: 20px;
        overflow: hidden;
        max-width: 550px;
        width: 90%;
        max-height: 90vh;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
        transform: scale(0.8) translateY(50px);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
        overflow-y: auto;
    }

    .modal-overlay.active .formation-card {
        transform: scale(1) translateY(0);
    }

    .card-header {
        background: linear-gradient(135deg, #021a12, #00110a);
        color: #ffae2b;
        padding: 40px 30px 30px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .card-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, transparent 30%, rgba(255,174,43,0.1) 50%, transparent 70%);
        animation: shimmer 3s ease-in-out infinite;
    }

    @keyframes shimmer {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }

    .formation-photo {
        width: 120px;
        height: 120px;
        border-radius: 15px;
        margin: 0 auto 20px;
        border: 4px solid #ffae2b;
        position: relative;
        z-index: 2;
        overflow: hidden;
        background: rgba(255, 255, 255, 0.1);
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    }

    .formation-photo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .formation-photo.no-image {
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        color: #ffae2b;
    }

    .card-title {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 8px;
        position: relative;
        z-index: 2;
    }

    .card-trainer {
        font-size: 1.1rem;
        opacity: 0.9;
        position: relative;
        z-index: 2;
        font-weight: 500;
        background: rgba(255,174,43,0.2);
        padding: 5px 15px;
        border-radius: 15px;
        display: inline-block;
    }

    .card-body {
        padding: 30px;
        background: white;
    }

    .info-section {
        margin-bottom: 25px;
    }

    .info-section:last-child {
        margin-bottom: 0;
    }

    .section-title {
        color: #021a12;
        font-weight: 700;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        font-size: 1.1rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .section-title i {
        margin-right: 12px;
        color: #ffae2b;
        width: 20px;
        text-align: center;
    }

    .info-grid {
        display: grid;
        gap: 12px;
    }

    .info-item {
        display: flex;
        align-items: center;
        padding: 15px;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border-radius: 12px;
        border-left: 4px solid #ffae2b;
        transition: all 0.3s ease;
    }

    .info-item:hover {
        transform: translateX(5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .info-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #021a12, #00110a);
        color: #ffae2b;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        font-size: 0.9rem;
    }

    .info-content {
        flex: 1;
    }

    .info-label {
        color: #6b7280;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 2px;
    }

    .info-value {
        color: #021a12;
        font-weight: 600;
        font-size: 1rem;
    }

    .price-display {
        background: linear-gradient(135deg, #ffae2b, #ff8f00);
        color: #021a12;
        padding: 15px 25px;
        border-radius: 25px;
        font-weight: 700;
        font-size: 1.3rem;
        text-align: center;
        margin-top: 10px;
        box-shadow: 0 5px 15px rgba(255, 174, 43, 0.3);
    }

    .duration-display {
        display: flex;
        align-items: center;
        gap: 10px;
        background: #f8fafc;
        padding: 10px 15px;
        border-radius: 15px;
        border-left: 4px solid #ffae2b;
    }

    .duration-number {
        font-size: 1.5rem;
        font-weight: 700;
        color: #021a12;
    }

    .duration-unit {
        color: #6b7280;
        font-weight: 600;
    }

    .progress-container {
        margin-top: 15px;
    }

    .progress-bar {
        width: 100%;
        height: 10px;
        background: #e5e7eb;
        border-radius: 5px;
        overflow: hidden;
        margin-bottom: 10px;
    }

    .progress-fill {
        height: 100%;
        background: linear-gradient(135deg, #ffae2b, #ff8f00);
        transition: width 0.3s ease;
        border-radius: 5px;
    }

    .progress-text {
        font-size: 0.9rem;
        font-weight: 600;
        color: #021a12;
        text-align: center;
    }

    .description-text {
        background: #f8fafc;
        padding: 15px;
        border-radius: 10px;
        border-left: 4px solid #ffae2b;
        font-style: italic;
        color: #374151;
        line-height: 1.6;
    }

    .close-btn {
        position: absolute;
        top: 15px;
        right: 15px;
        background: rgba(255, 174, 43, 0.2);
        border: none;
        color: #ffae2b;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        transition: all 0.3s ease;
        z-index: 10;
        backdrop-filter: blur(10px);
    }

    .close-btn:hover {
        background: rgba(255, 174, 43, 0.3);
        transform: rotate(90deg);
    }

    .action-buttons {
        display: flex;
        gap: 10px;
        margin-top: 20px;
    }

    .action-btn {
        flex: 1;
        padding: 12px;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-edit {
        background: linear-gradient(135deg, #ffae2b, #ff8f00);
        color: #021a12;
    }

    .btn-delete {
        background: linear-gradient(135deg, #dc2626, #ef4444);
        color: white;
    }

    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    @media (max-width: 768px) {
        .formation-card {
            margin: 20px;
            width: calc(100% - 40px);
        }
        
        .action-buttons {
            flex-direction: column;
        }
    }
</style>
<body> 
    <div class="header_dash">
        <div class="cont_dash">
            <div class="ail_fle">
                 <?php include_once('menu.php');?>
                <div class="dashcontainu">
                    <div class="tabl_contnu">
                        <div class="titre_formation">
                           
                            <h3>Liste des formations</h3>
                            <div class="ajouter" data-tooltip="ajouter une formation"><a href="../Admin/AjouteFormation.php"><i class="fas fa-plus"></i></a></div>
                        </div>
                        <div class="principal">
                            <table>
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Photo</th>
                                            <th>Nom</th>
                                            <th>Description</th>
                                            <th>Duree</th>
                                            <th>Prix</th>
                                            <th>Formateur</th>
                                            <th>Evolution Formation</th>
                                            <th>action</th>
                                        </tr>
                                    </thead>
<?php
$index = 0;
foreach($listeFormation['data'] as $liste){
    $index++;
      $formateur = $utilisateur->getUtilisateur($liste->getIdFormateur());
      
      $duree = $liste->getDuree();
      $dateDebut = $liste->getDebutFormation();
      $etat = $formation->getTempsRestantFinFormation($dateDebut,$duree);
      
      // Déterminer la classe CSS pour le statut
      $statusClass = 'status-en-cours';
      if(strpos($etat, 'Terminé') !== false) {
          $statusClass = 'status-termine';
      } elseif(strpos($etat, 'pas encore') !== false) {
          $statusClass = 'status-bientot';
      }
  
    ?>
                                    <tr>
                                        <td><?=$index?></td>
                                        <td>
                                            <img src="<?=!empty($liste->getPhoto()) ? '../controle/'.$liste->getPhoto() : 'profil.jpg' ?>" 
                                                    alt="profil" 
                                                    class="formation-image">
                                            </td>
                                        <td><?=$liste->getTitre()?></td>
                                        <td><?=substr($liste->getDescription(), 0, 50)?>...</td>
                                        <td><?=round($liste->getDuree()/30)?> mois</td>
                                        <td><?=$liste->getPrix()?> Fbu</td>
                                        <td><?php echo $formateur['data']->getNom();echo " ".$formateur['data']->getPrenom();?></td>
                                        <td>
                                            <span class="status-badge <?=$statusClass?>">
                                                <?=$etat?>
                                            </span>
                                        </td>
                                        <td>
                                                <div class="icons_actuality">
                                                     <div class="voir" data-tooltip="Voir" onclick="showFormationCard({
                                                         titre: '<?=addslashes($liste->getTitre())?>',
                                                         description: '<?=addslashes($liste->getDescription())?>',
                                                         duree: '<?=$liste->getDuree()?>',
                                                         prix: '<?=$liste->getPrix()?>',
                                                         formateur: '<?=addslashes($formateur['data']->getNom().' '.$formateur['data']->getPrenom())?>',
                                                         photo: '<?=!empty($liste->getPhoto()) ? '../controle/'.$liste->getPhoto() : ''?>',
                                                         dateDebut: '<?=$liste->getDebutFormation()?>',
                                                         etat: '<?=addslashes($etat)?>',
                                                         statusClass: '<?=$statusClass?>'
                                                     })">
                                                         <i class="fas fa-eye"></i>
                                                     </div>
                                                    <a href=""><div class="modifier" data-tooltip="modifier"><i class="fas fa-edit"></i></div></a>
                                                    <a href=""> <div class="supprimer_" data-tooltip="supprimer"><i class="fas fa-trash"></i></div></a>
                                                </div>
                                            </td>
                                    </tr>
<?php }?>
                                </table>
                        </div> 
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal pour la carte de formation -->
    <div class="modal-overlay" id="formationModal">
        <div class="formation-card">
            <button class="close-btn" onclick="closeFormationCard()">
                <i class="fas fa-times"></i>
            </button>
            
            <div class="card-header">
                <div class="formation-photo" id="formationPhoto">
                    <!-- Image sera ajoutée dynamiquement -->
                </div>
                <div class="card-title" id="cardTitle">Titre de la Formation</div>
                <div class="card-trainer" id="cardTrainer">Formateur</div>
            </div>
            
            <div class="card-body">
                <div class="info-section">
                    <h4 class="section-title">
                        <i class="fas fa-info-circle"></i>
                        Informations Générales
                    </h4>
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <div class="info-content">
                                <div class="info-label">Durée de Formation</div>
                                <div class="duration-display">
                                    <span class="duration-number" id="cardDurationMonths">0</span>
                                    <span class="duration-unit">mois</span>
                                    <span class="duration-number" id="cardDurationDays" style="margin-left: 15px;">0</span>
                                    <span class="duration-unit">jours</span>
                                </div>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            <div class="info-content">
                                <div class="info-label">Prix de Formation</div>
                                <div class="price-display" id="cardPrice">0 Fbu</div>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <div class="info-content">
                                <div class="info-label">Date de Début</div>
                                <div class="info-value" id="cardStartDate">Non définie</div>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div class="info-content">
                                <div class="info-label">État d'Avancement</div>
                                <div class="info-value">
                                    <span id="cardStatus" class="status-badge">En cours</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="info-section">
                    <h4 class="section-title">
                        <i class="fas fa-chart-pie"></i>
                        Progression
                    </h4>
                    <div class="progress-container">
                        <div class="progress-bar">
                            <div class="progress-fill" id="progressFill" style="width: 0%"></div>
                        </div>
                        <div class="progress-text" id="progressText">0% Complété</div>
                    </div>
                </div>
                
                <div class="info-section">
                    <h4 class="section-title">
                        <i class="fas fa-file-alt"></i>
                        Description de la Formation
                    </h4>
                    <div class="description-text" id="cardDescription">
                        Description de la formation...
                    </div>
                </div>
                
                <div class="action-buttons">
                    <a href="#" class="action-btn btn-edit" id="editBtn">
                        <i class="fas fa-edit"></i>
                        Modifier
                    </a>
                    <button class="action-btn btn-delete" id="deleteBtn">
                        <i class="fas fa-trash"></i>
                        Supprimer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showFormationCard(data) {
            // Remplir les informations de base
            document.getElementById('cardTitle').textContent = data.titre || 'Titre de la Formation';
            document.getElementById('cardTrainer').textContent = 'Formateur: ' + (data.formateur || 'Non assigné');
            document.getElementById('cardDescription').textContent = data.description || 'Aucune description disponible';
            document.getElementById('cardStartDate').textContent = data.dateDebut || 'Non définie';
            
            // Gestion de la photo
            const photoElement = document.getElementById('formationPhoto');
            if (data.photo && data.photo.trim() !== '') {
                photoElement.innerHTML = `<img src="${data.photo}" alt="${data.titre}">`;
                photoElement.classList.remove('no-image');
            } else {
                photoElement.innerHTML = '<i class="fas fa-graduation-cap"></i>';
                photoElement.classList.add('no-image');
            }
            
            // Calcul et affichage de la durée
            const dureeDays = parseInt(data.duree) || 0;
            const dureeMois = Math.round(dureeDays / 30);
            document.getElementById('cardDurationMonths').textContent = dureeMois;
            document.getElementById('cardDurationDays').textContent = dureeDays;
            
            // Prix
            const prix = parseFloat(data.prix) || 0;
            document.getElementById('cardPrice').textContent = prix.toLocaleString() + ' Fbu';
            
            // Statut avec classe CSS
            const statusElement = document.getElementById('cardStatus');
            statusElement.textContent = data.etat || 'En cours';
            statusElement.className = 'status-badge ' + (data.statusClass || 'status-en-cours');
            
            // Calcul du pourcentage de progression (simulation basée sur l'état)
            let progressPercent = 0;
            if (data.etat && data.etat.includes('Terminé')) {
                progressPercent = 100;
            } else if (data.etat && data.etat.includes('En cours')) {
                progressPercent = Math.random() * 60 + 20; // Entre 20% et 80%
            } else {
                progressPercent = 0;
            }
            
            document.getElementById('progressFill').style.width = progressPercent + '%';
            document.getElementById('progressText').textContent = Math.round(progressPercent) + '% Complété';
            
            // Afficher la modal
            const modal = document.getElementById('formationModal');
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
        
        function closeFormationCard() {
            const modal = document.getElementById('formationModal');
            modal.classList.remove('active');
            document.body.style.overflow = 'auto';
        }
        
        // Fermer avec clic sur overlay
        document.getElementById('formationModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeFormationCard();
            }
        });
        
        // Fermer avec Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeFormationCard();
            }
        });
    </script>
</body>
</html>