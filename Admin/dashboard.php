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
    .dash_static{
        display: flex;
        justify-content: space-between;
        gap: 5px;

    }
    .static1{
        width: 20%;
        height: 20vh;
        background-color: #ffae2b;
        border-radius: 5px;
        border: 1px solid#ffae2b;
        margin-top: 10px;
        display: flex;
        align-items: center;
        justify-content: space-evenly;

    }
     .static1:nth-child(2){
        background-color:  #021a12;
        border: 1px solid  #021a12;
     }
    .static1:nth-child(3){
        background-color: #ffae2b;
        border: 1px solid  #ffae2b;
     }
    .static1:nth-child(4){
        background-color:  #021a12;
        border: 1px solid  #021a12;
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
        color: white;
    }
    .vert_div h2{
        font-size: 25px;
        margin-top: 10px;
    }
    .static1 i{
        font-size: 25px;
        color: #00ffc2;
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
    .left_side{
        width: 72%;
        height: 35vh;
        background-color: rgba(255,255,255,0.12);
        border-radius: 5px;
        margin-top: 15px;
       
    }
    .right_side{
        width: 25%;
        height: 35vh;
        background-color:  #021a12;
        border-radius: 5px;
        margin-top: 15px;
    }
    .dash_lastdiv{
        display: flex;
        justify-content: space-between;
       
    }
    .left_last{
        width: 35%;
        height: 40vh;
        background-color: #ffae2b;;
        margin-top: 28px;
        border-radius: 5px 5px 0 0;
        color: white;
    }
    .left_inscription{
        margin: 10px;
    }
    table{
        border-collapse: collapse;
        width: 100%;
        text-align: start;
        padding: 10px;
        margin-top: 10px;
        color:black;
    }
    thead tr th{
        padding: 10px;
        text-align: start;
    }
    tbody tr td{
        padding: 10px;
    }
    .center_side{
        width: 35%;
        height: 40vh;
        background-color:rgba(255,255,255,0.09);
        margin-top: 28px;
         border-radius: 5px 5px 0 0;
    }
    .right_last{
        width: 25%;
        height: 35vh;
        background-color: #021a12;
         margin-top: 28px;
        border-radius: 5px;
        color: white;
    }
    .left_alert{
        margin: 10px;
        color: white;
    }
    .left_alert h4{
        margin-top: 20px;
        margin-left: 20px;
    }
    .left_alert p{
        margin-top: 20px;
        margin-left: 20px;
        color: white;
    }
    .left_alert hr{
        margin-top: 10px;
        opacity: 0.5;
        color: white;
    }
    .vr{
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        margin-top: 20px;
        color: white;
    }



.gauche {
    width: 20%;
    background: #f4f4f4;
    background-color: #021a12;
    color: white;
    height:94%;
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
    font-size: 17px;
}

.gauche li label i {
    margin-right: 10px;
    color: white;
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
    font-size: 17px;
    padding: 3px 0;
    display: block;
}

.submenu li a:hover {
    color: #16A34A;
}

/* ouvrir sous-menu quand radio checké */
.menu input:checked + label + .submenu {
    max-height: 200px;
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
                        <h3>Tableau de board</h3>
                        <div class="dash_static">
                            <div class="static1">
                                <div class="vert_div">
                                    <p>Etudiants</p>
                                    <h1>125</h1>
                                </div>
                                <i class="fas fa-user-graduate"></i>
                            </div>
                            <div class="static1">
                                 <div class="vert_div">
                                    <p>Formations</p>
                                    <h1>8</h1>
                                </div>
                                <i class="fas fa-chalkboard-teacher"></i>
                            </div>
                            <div class="static1">
                                 <div class="vert_div">
                                    <p>Commandes</p>
                                    <h1>5</h1>
                                </div>
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                            <div class="static1">
                                 <div class="vert_div">
                                    <p>Revenus</p>
                                    <h2>12 345$</h2>
                                </div>
                                <i class="fas fa-coins"></i>
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
            </div>
        </div>
    </div>
</body>
</html>