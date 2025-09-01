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
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    th, td {
      padding: 20px 15px;
      text-align: left;
    }

    th {
      background: #021a12;
      font-weight: bold;
      color : #ffae2b;
    }

    tr:not(:last-child) {
      border-bottom: 1px solid #021a12;
    }

    td {
      font-size: 14px;
    }
</style>
<body> 
    <div class="header_dash">
        <div class="cont_dash">
             <?php include_once('header.php');?>
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
  
    ?>
                                    <tr>
                                        <td><?=$index?></td>
                                                                                    <td>
                                            <img src="<?=!empty($liste->getPhoto()) ? '../controle/'.$liste->getPhoto() : 'profil.jpg' ?>" 
                                                    alt="profil" 
                                                    style="width:40px; height:40px; border-radius:50%; object-fit:cover; border:2px solid #00110a;">
                                            </td>
                                        <td><?=$liste->getTitre()?></td>
                                        <td><?=$liste->getDescription()?></td>
                                        <td><?=round($liste->getDuree()/30)?> mois</td>
                                        <td><?=$liste->getPrix()?>Fbu</td>
                                        <td><?php echo $formateur['data']->getNom();echo " ".$formateur['data']->getPrenom();?></td>
                                        <td>
                                            <?php
                                                $duree = $liste->getDuree();
                                                $dateDebut = $liste->getDebutFormation();
                                                $etat = $formation->getTempsRestantFinFormation($dateDebut,$duree);
                                                echo $etat;
                                            ?>
                                        </td>
                                        <td>
                                                <div class="icons_actuality">
                                                     <a href=""><div class="voir" data-tooltip="Voir"><i class=" fas fa-eye"></i></div></a>
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
</body>
</html>