<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashborad</title>
    <link rel="stylesheet" href="../vue/font-awesome/css/all.min.css">
    <link rel="stylesheet" href="../vue/styles/style.css">
</head>
<style>
    
    .header_dash{
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        position: fixed;
    }
    .cont_dash{
        width: 100%;
        height: 100%;
        background-color:  #00110a;
    }
    .cont_dash .dash_tete{
        width: 100%;
        height: 6%;
        background-color: rgba(255,255,255,0.09);
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-radius: 5px 5px 0 0;
        border-color: 1px solid gold;
        color: white;
    }
    .text_left{
        display: flex;
        align-items: center;
        justify-content: center;
        margin-left: 80px;
    }
    .text_left p{
        margin-left: 10px;
    }
    .ail_fle{
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: space-between;
    }
    .text_right{
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 80px;
    }
    .text_right i{
        margin-right: 20px;
        cursor: pointer;
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
        margin: 15px;
    }
    .tabl_contnu h2{
        margin-top: 20px;
        margin-bottom: 10px;
        color: #00110a;
        font-size: 16PX;
        color: #021a12;
    }
    .tabl_contnu::-webkit-scrollbar{
        display: none;
    }
    /* STYLE POUR LE TABLEAU*/
    table thead{
        font-size: 14PX;
        color: #ffae2b;
    }






/* ouvrir sous-menu quand radio checké */
.menu input:checked + label + .submenu {
    max-height: 200px;
}

.icon_action {
    display: flex;
    align-items: start;
    justify-content: start;
    gap: 10px;
    font-size: 16px;
    font-weight: 900;
}
.icon_action i:nth-child(1){
    color: #05f07a;;
    cursor: pointer;
}
.icon_action i:nth-child(2){
    color:  #ffae2b;
    cursor: pointer;
}
.icon_action i:nth-child(3){
    color:  #00ffc2;;
    cursor: pointer;
}

</style>
<body>
    <div class="header_dash">
        <div class="cont_dash">
            <?php
                include ("headers.php");
            ?>
            <div class="ail_fle">
                <?php
                include ("menu.php")
                ?>
                <div class="dashcontainu">
                    <div class="tabl_contnu">
                        <h2>Historique des commandes</h2>
                                <table>
                                    <thead>
                                         <tr>
                                            <th>
                                                <input type="checkbox">
                                            </th>
                                            <th>ID</th>
                                            <th>Clients</th>
                                            <th>Date</th>
                                            <th>Statut</th>
                                            <th>Montant</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox">
                                        </th>
                                        <th>1</th>
                                        <td>jean</td>
                                        <td>12/12/2024</td>
                                        <td>ematieses</td>
                                        <td>2000</td>
                                        <td><div class="icon_action">
                                            <i class="fas fa-eye"></i>
                                            <i class="fas fa-trash"></i>
                                        </div></td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <input type="checkbox">
                                        </th>
                                        <th>2</th>
                                        <td>marie</td>
                                        <td>12/2/2019</td>
                                        <td>ematieses</td>
                                        <td>2000</td>
                                        <td><div class="icon_action">
                                            <i class="fas fa-eye"></i>
                                            <i class="fas fa-trash"></i>
                                        </div></td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <input type="checkbox">
                                        </th>
                                        <th>3</th>
                                        <td>jean</td>
                                        <td>12/1/2020</td>
                                        <td>ematieses</td>
                                        <td>2000</td>
                                        <td><div class="icon_action">
                                            <i class="fas fa-eye"></i>
                                            <i class="fas fa-trash"></i>
                                        </div></td>
                                    </tr>
                                </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>