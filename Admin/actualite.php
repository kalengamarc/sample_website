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
        margin: 20px;
        padding-top: 10px;
        background-color: #b9c1be;;
        color: black;
        overflow: scroll;
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
    color:rgba(0,0,0,0.9);
    display:flex;
    gap:10px;
    font-size:16px;
   }
   table .icons_actuality i{
    color:black;
   }
   table .icons_actuality i:hover{
    cursor:pointer;
   }

   .titre_formation {
    width: 100%;
    height:50px;
    gap:30px;
    display:flex;
    align-items:center;
    justify-content:center;
}

/* BOUTTON AJOUTER*/

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
                           
                            <h3>Actualites</h3>
                            <div class="ajouter" data-tooltip="ajouter une actualite"><a href=""><i class="fas fa-plus"></i></a></div>
                        </div>
                        <div class="principal">
                            <table>
                                    <thead>
                                        <tr>
                                            <th>Titre</th>
                                            <th>date</th>
                                            <th>auteur</th>
                                            <th>statut</th>
                                            <th>action</th>
                                        </tr>
                                    </thead>
                                    <tr>
                                        <td>mise a jour du systeme</td>
                                        <td>12/02/2024</td>
                                        <td>Admin</td>
                                        <td>publiee</td>
                                            <td>
                                                <div class="icons_actuality">
                                                    <a href=""><div class="voir" data-tooltip="Voir"><i class=" fas fa-eye"></i></div></a>
                                                    <a href=""><div class="modifier" data-tooltip="modifier"><i class="fas fa-edit"></i></div></a>
                                                    <a href=""> <div class="supprimer_" data-tooltip="supprimer"><i class="fas fa-trash"></i></div></a>
                                                </div>
                                            </td>
                                    </tr>
                                    <tr>
                                        <td>mise a jour du systeme</td>
                                        <td>12/03/2025</td>
                                        <td>Admin</td>
                                        <td>publiee</td>
                                        <td>
                                            <div class="icons_actuality">
                                                <a href=""><div class="voir" data-tooltip="Voir"><i class=" fas fa-eye"></i></div></a>
                                                    <a href=""><div class="modifier" data-tooltip="modifier"><i class="fas fa-edit"></i></div></a>
                                                    <a href=""> <div class="supprimer_" data-tooltip="supprimer"><i class="fas fa-trash"></i></div></a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>mise a jour du systeme</td>
                                        <td>18/04/2025</td>
                                        <td>Admin</td>
                                        <td>brouillon</td>
                                        <td>
                                        <div class="icons_actuality">
                                            <a href=""><div class="voir" data-tooltip="Voir"><i class=" fas fa-eye"></i></div></a>
                                                    <a href=""><div class="modifier" data-tooltip="modifier"><i class="fas fa-edit"></i></div></a>
                                                    <a href=""> <div class="supprimer_" data-tooltip="supprimer"><i class="fas fa-trash"></i></div></a>
                                         </div>
                                         </td>
                                        
                                    </tr>
                            </table>
                        </div> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>