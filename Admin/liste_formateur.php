<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashborad</title>
    <link rel="stylesheet" href="../vue/font-awesome/css/all.min.css">
    <link rel="stylesheet" href="../vue/styles/stylemenu.css">
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
      background: #f0f2f5;
      font-weight: bold;
    }

    tr:not(:last-child) {
      border-bottom: 1px solid #e5e5e5;
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
                           
                            <h3>Liste des formateurs</h3>
                            <div class="ajouter" data-tooltip="ajouter un formateur"><a href=""><i class="fas fa-plus"></i></a></div>
                        </div>
                        <?php
                    // Inclure ton contrôleur
                    include_once('../controle/controleur_utilisateur.php');
                    $utilisateurCtrl = new UtilisateurController();
                    $usersData = $utilisateurCtrl->getUtilisateursByRole('formateur'); // récupération de tous les utilisateurs
                    ?>
                    <div class="principal">
                        <table>
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Photo</th>
                                    <th>Nom</th>
                                    <th>Email</th>
                                    <th>Téléphone</th>
                                    <th>Role</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($usersData['success'] && count($usersData['data']) > 0): ?>
                                    <?php foreach($usersData['data'] as $index => $user): ?>
                                        <tr>
                                            <td><?= $index + 1 ?></td>
                                            <td>
                                            <img src="<?=!empty($user->getPhoto()) ? '../controle/'.$user->getPhoto() : 'profil.jpg' ?>" 
                                                    alt="profil" 
                                                    style="width:40px; height:40px; border-radius:50%; object-fit:cover; border:2px solid #00110a;">
                                            </td>
                                            <td><?= htmlspecialchars($user->getNom() . ' ' . $user->getPrenom()) ?></td>
                                            <td><?= htmlspecialchars($user->getEmail()) ?></td>
                                            <td><?= htmlspecialchars($user->getTelephone()) ?></td>
                                            <td><?= htmlspecialchars($user->getRole()) ?></td>
                                            <td>
                                                <div class="icons_actuality">
                                                    <a href="voir.php?id=<?= $user->getId() ?>"><div class="voir" data-tooltip="Voir"><i class="fas fa-eye"></i></div></a>
                                                    <a href="modifier.php?id=<?= $user->getId() ?>"><div class="modifier" data-tooltip="Modifier"><i class="fas fa-edit"></i></div></a>
                                                    <a href="supprimer.php?id=<?= $user->getId() ?>" onclick="return confirm('Voulez-vous vraiment supprimer cet utilisateur ?')">
                                                        <div class="supprimer_" data-tooltip="Supprimer"><i class="fas fa-trash"></i></div>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" style="text-align:center;">Aucun utilisateur trouvé</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>