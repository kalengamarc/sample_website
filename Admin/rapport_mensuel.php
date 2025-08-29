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
    .tabl_contnu::-webkit-scrollbar{
        display: none;
    }

/* ouvrir sous-menu quand radio checké */
.menu input:checked + label + .submenu {
    max-height: 200px;
}


/* Graphique*/

 .graphs{
        margin: 20px;
        height: 40vh;
        box-shadow: 2px 2px 20px rgba(0, 0, 0, 0.2);
        border-radius: 5px;
    }
    .firstline{
        display: flex;
        align-items: start;
        justify-content: space-between;
        margin-top: 25px;
        position: relative;
        
    }
    .evolutions{
        margin-top: 25px;
    }
    .firstline hr{
        width: 95%;
        margin-top: 10px;
    }
    .months{
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 50px;
    }
    .months p{
        font-size: 12px;
    }
    

</style>
<body>
    <div class="header_dash">
        <div class="cont_dash">
             <!--Partie header-->
            <?php
                include ("headers.php");
            ?>
            <div class="ail_fle">
                 <!--Partie menu-->
                <?php
                    include ("menu.php");
                ?>
                <div class="dashcontainu">
                    <div class="tabl_contnu">
                        <h4>Rapport mensuel</h4>
                       <div class="graphs">
                             <!--Partie evolutions statistics-->
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
                            <div class="bars">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>