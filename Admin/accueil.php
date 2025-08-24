<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../vue/font-awesome/css/all.min.css">
    <link rel="stylesheet" href="../vue/styles/stylemenu.css">
    <style>
    
    .arobases{
        display: flex;
        align-items: start;
        justify-content: start;
        flex-direction: column;
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
        width: 97%;
        height: 96%;
        background-color: #b9c1be;;
        color: black;
    }

    .dash_static{
        display: flex;
        justify-content: space-between;
        gap: 5px;
    }
    .static1{
        width: 20%;
        height: 20vh;
        background-color: red;
        border-radius: 5px;
        border: 1px solid red;
        margin-top: 10px;
        display: flex;
        align-items: center;
        justify-content: space-evenly;

    }
    .vert_div{
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        margin: 10px;
        color: white;
    }
    .vert_div h1{
        margin-top: 10px;
        font-size: 25px;
    }
    .vert_div h2{
        font-size: 25px;
        margin-top: 10px;
    }
    .static1 i{
        font-size: 25px;
        color: white;
    }
    .dash_paiement{
        display: flex;
        justify-content: space-between;
       
    }
    .graphs{
        margin: 10px;
    }
    .firstline{
        display: flex;
        align-items: start;
        justify-content: space-between;
        margin-top: 15px;
    }
    .evolutions{
        margin-top: 15px;
    }
    .firstline hr{
        width: 95%;
        margin-top: 10px;
    }
    .months{
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 20px;
    }
    .months p{
        font-size: 12px;
    }
   
    .right_side{
        width: 25%;
        height: 35vh;
        background-color: brown;
        border-radius: 5px;
        margin-top: 15px;
    }
    .dash_lastdiv{
        display: flex;
        justify-content: space-between;
       
    }
    
    table{
        border-collapse: collapse;
        width: 100%;
        text-align: start;
        padding: 10px;
        margin-top: 10px;
    }
    thead tr th{
        padding: 10px;
        text-align: start;
    }
    tbody tr td{
        padding: 10px;
    }
    
    .left_alert{
        margin: 10px;
    }
    .left_alert h4{
        margin-top: 20px;
        margin-left: 20px;
    }
    .left_alert p{
        margin-top: 20px;
        margin-left: 20px;
    }
    .left_alert hr{
        margin-top: 10px;
        opacity: 0.5;
    }
    .vr{
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        margin-top: 20px;
    }
</style>
</head>
<body>
    <div class="paiement_admin">
        <div class="contain">
                <!-- le header pour  l'application -->
                 <?php include_once('header.php');?>
                <div class="container">
                    <?php include_once('menu.php');?>
                    <main>
                    <div class="dashcontainu">
                    <div class="tabl_contnu">
                        <h3>Tableau de board</h3>
                        <div class="dash_static">
                            <div class="static1">
                                <div class="vert_div">
                                    <p>Etudiants</p>
                                    <h1>125</h1>
                                </div>
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="static1">
                                 <div class="vert_div">
                                    <p>Formations</p>
                                    <h1>8</h1>
                                </div>
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="static1">
                                 <div class="vert_div">
                                    <p>Commandes</p>
                                    <h1>5</h1>
                                </div>
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="static1">
                                 <div class="vert_div">
                                    <p>Revenus</p>
                                    <h2>12 345$</h2>
                                </div>
                                <i class="fas fa-user"></i>
                            </div>
                        </div>
                        <div class="dash_paiement">
                            <div class="left_side">
                                <div class="graphs">
                                    <h4>Evolution des inscriptions</h4>
                                    <div class="evolutions">
                                        <div class="firstline">
                                            <h4>20</h4>
                                            <hr>
                                        </div>
                                        <div class="firstline">
                                            <h4>15</h4>
                                            <hr>
                                        </div>
                                        <div class="firstline">
                                            <h4>10</h4>
                                            <hr>
                                        </div>
                                        <div class="firstline">
                                            <h4>5</h4>
                                            <hr>
                                        </div>
                                        <div class="firstline">
                                            <h4>0</h4>
                                            <hr>
                                        </div>
                                       <div class="months">
                                            <p>Janvier</p>
                                            <p>Fevrier</p>
                                            <p>Mars</p>
                                            <p>Avril</p>
                                            <p>Mai</p>
                                            <p>Juin</p>
                                            <P>Juillet</P>
                                            <p>Auguste</p>
                                            <p>Octobre</p>
                                            <p>Novembre</p>
                                            <p>Decembre</p>
                                       </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="right_side">

                            </div>
                        </div>
                        <div class="dash_lastdiv">
                            <div class="left_last">
                                <div class="left_inscription">
                                    <h4>Derniere inscription</h4>
                                    <table 1border="">
                                        <thead>
                                        <tr>
                                            <th>Nom</th>
                                            <th>Formation</th>
                                            <th>Date</th>

                                        </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Julien dupont</td>
                                                <td>Formation</td>
                                                <td>20 mars</td>
                                            </tr>
                                            <tr>
                                                <td>Paul Martin</td>
                                                <td>Formation</td>
                                                <td>21 mars</td>
                                            </tr>
                                            <tr>
                                                <td>Alice dubois</td>
                                                <td>Formation</td>
                                                <td>24 mars</td>
                                            </tr>
                                            <tr>
                                                <td>Sophie mareau</td>
                                                <td>Formation</td>
                                                <td>14 juillet</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="center_side">
                                <div class="left_inscription">
                                    <h4>Commandes recentes</h4>
                                    <table 1border="">
                                        <thead>
                                        <tr>
                                            <th>Nom</th>
                                            <th>Formation</th>
                                            <th>Date</th>

                                        </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Julien dupont</td>
                                                <td>Formation</td>
                                                <td>20 mars</td>
                                            </tr>
                                            <tr>
                                                <td>Paul Martin</td>
                                                <td>Formation</td>
                                                <td>21 mars</td>
                                            </tr>
                                            <tr>
                                                <td>Alice dubois</td>
                                                <td>Formation</td>
                                                <td>24 mars</td>
                                            </tr>
                                            <tr>
                                                <td>Sophie mareau</td>
                                                <td>Formation</td>
                                                <td>14 juillet</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="right_last">
                                <div class="left_alert">
                                    <h4>Alert stock faible</h4>
                                        <div class="vr">
                                            <i></i>
                                            <h4>Casque VR</h4>
                                         </div>
                                         <hr>
                                         <p>commesco</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </main>
             </div> 
        </div>
    </div>
</body>
</html>