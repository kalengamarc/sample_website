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
        width: 90%;
        height: 94%;
        background-color: #b9c1be;
        display: flex;
        justify-content: center;
        
    }
    .tabl_contnu{
        width: 90%;
        height: 100%;
        background-color: #b9c1be;;
        color: black;
        overflow: scroll;
        margin-top: 15px;
        margin-right: 15px;
        
    }
    .tabl_contnu::-webkit-scrollbar{
        display: none;
    }

/* ouvrir sous-menu quand radio checké */
.menu input:checked + label + .submenu {
    max-height: 200px;
}

.tabl_contnu h2{
    margin-top: 10px;
    margin-bottom: 10px;
    color: #021a12;
    font-size: 18px;
}
.profile_div{
    width: 98%;
    height: 112vh;
    background-color: #f4f4f4;
    margin-left: 2px;
    border-radius: 5px;
    font-size: 16px;
}
.profile_div_cont{
    margin: 9px;
    font-size: 16px;
    width: 95%;
    height: 120vh;
}
.imge_profile{
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    font-size: 16px;
}
.imgprof{
    width: 80px;
    height: 80px;
    border-radius: 50%;
    margin-top: 20px;
    font-size: 16px;
}
.imgprof img{
    width: 100%;
    height: 100%;
    border-radius: 50%;
    overflow: hidden;
    object-fit: cover;
    margin-left: 15px;
    font-size: 16px;
}
.boutonprof{
    position: relative;
}

/*=====================================================
    STYLE DU BOUTON PLUS SUR L'IMAGE PROFILE
========================================================*/
.boutonprof #plus_cirle{
    position: absolute;
    color:#05f07a;
    font-size: 20px;
    top: 50px;
    right: 176px;
    cursor: pointer;
    font-size: 25px;
}
#Upload{
    padding: 8px;
    font-size: 12px;
    background-color: #021a12;
    border: 1px solid #021a12;
    outline: none;
    color: #f4f4f4;
    font-weight: 900;   
    margin-top:15px;
    border-radius: 5px;
    margin-left: 40px;
    cursor: pointer;
    font-size: 11px;
}
#Delete{
    padding: 8px;
    font-size: 12px;
    border: 1px solid white;
    outline: none;
    margin-top: 15px;
    border-radius: 5px;
    cursor: pointer;
}
.first_inputsforms{
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    margin-top: 30px;
    font-size: 14px;
}
.input_forms{
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-direction: column;
    gap: 8px;
    font-size: 12px;
    
}
.firstname{
    display: flex;
    align-items: start;
    justify-content: start;
    gap:2px;
    flex-direction: column;
    font-size: 12px;
}
#mardiinput{
    padding: 6px;
}
.firstname input{
    padding: 8px;
    width: 450px;
    border: 1px solid #b9c1be;
    outline: none;
    border-radius: 5px;
    font-size: 12px;
    margin-left: 20px;
}
.firstname label{
    margin-left: 20px;
}
.firstname input:focus{
    border: 3px solid #021a12;
}
.second_input{
    display: flex;
    align-items: start;
    justify-content: start;
    gap:2px;
    flex-direction: column;
    margin-left: 35px;
   
}
.second_input input{
    padding: 8px;
    width: 300px;
    border: 1px solid #b9c1be;
    outline: none;
    border-radius: 5px;
}
.second_input input:focus{
    border: 3px solid #021a12;
}
#adresse_residence{
    width:790px;
    resize: none;
    padding: 8px;
    border-radius: 5px;
    outline: none;
    margin-left: 20px;
    border: 1px solid #021a12;

}
#adresse_residence:focus{
    border: 3px solid #021a12;
}
#savechanged{
    padding: 8px;
    background-color: #021a12;
    color: #b9c1be;
    outline: none;
    border: 1px solid #b9c1be;
    font-size: 16px;
    align-self:flex-start;
    margin-left: 25px;
    border-radius: 5px;
    margin-top: 20px;
    font-size: 14px;
}
/** PLUS-CIRCLE-STYLE */
#savechanged:hover{
    background-color: gold;
    color: #00110a;
    cursor: pointer;

}

/*=================================================
    SITTINGS SIDE
==================================================
.settings_side{
    width: 25%;
    height: 25vh;
    background-color: #f4f4f4;
    margin-top: 25px;
    border-radius: 5px 5px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.settings_side .listedesmarametres{
    display: flex;
    align-items: start;
    justify-content: start;
    gap: 10px;
    flex-direction: column;
}
.settings_side .listedesmarametres .iconssss{
    display: flex;
    align-items: start;
    justify-content: start;
    gap: 5px;
    width: 250px;
    height: 40px;
    padding: 5px;
}
.settings_side .listedesmarametres .iconssss:hover{
    background-color: #021a12;
    justify-content: start;
    align-items: start;
    color: #ffae2b;
}
.settings_side .listedesmarametres .iconssss:hover a{
    color: #f4f4f4;
}
.settings_side .listedesmarametres .iconssss i{
    color: #ffae2b;
    margin-top: 5px;
    margin-left: 10px;
}
.settings_side .listedesmarametres .iconssss a{
    text-decoration: none;
    color: #021a12;
    margin-top: 5px;
}*/
</style>
<body>
    <div class="header_dash">
        <div class="cont_dash">
    <?php
        include ("headers.php");
    ?>
            <div class="ail_fle">

            <?php
                include ("menu.php");
            ?>
                <div class="dashcontainu">
                    <div class="settings_side">
                         <!--PARAMETTRES--><!--
                        <div class="listedesmarametres">
                            <div class="iconssss">
                                <i class="fas fa-user"></i>
                                <a href="#">Profile</a>
                            </div>
                            <div class="iconssss">
                                <i class="fas fa-user"></i>
                                <a href="#">Paramaitres generals</a>
                            </div>
                            <div class="iconssss">
                                <i class="fas fa-user"></i>
                                <a href="#">Notifications</a>
                            </div>
                        </div>-->
                    </div>
                    <div class="tabl_contnu">
                       <div class="profile_div">
                            <div class="profile_div_cont">
                                 <!--LELEMENTS DU PROFILE-->
                                <div class="imge_profile">
                                    <div class="imageprof">
                                        <div class="imgprof">
                                             <!--IMAGE_PROFILE-->
                                            <img src="../vue/defi7/images/Student day _ Premium Photo.jpeg" alt="">
                                        </div>
                                    </div>
                                     <!--BOUTON POUR CHANGER LA PHOTO DE PROFILE-->
                                    <div class="boutonprof">
                                        <button id="Upload"> Upload </button>
                                        <button id="Delete">Delete avatar</button>
                                            <!--ICONE PLUS-CIRCLE-->
                                        <i class="fas fa-plus-circle" id="plus_cirle"></i>
                                    </div>
                                </div>
                                <!-- INPUT FORMS-->
                                <div class="input_forms">
                                    <div class="first_inputsforms">
                                        <div class="firstname">
                                            <label for="">First name</label><br>
                                            <input type="text" placeholder="firstname" id="mardiinput">
                                        </div>
                                        <div class="second_input">
                                            <label for="">Lastname</label><br>
                                            <input type="text" placeholder="Lastname" id="mardiinput">
                                        </div>
                                    </div>
                                    <div class="first_inputsforms">
                                        <div class="firstname">
                                            <label for="">E-mail</label><br>
                                            <input type="email" placeholder="E-mai">
                                        </div>
                                        <div class="second_input">
                                            <label for="">Mobile Number</label><br>
                                            <input type="tel" placeholder="Mobile Number">
                                        </div>
                                    </div>
                                    <div class="first_inputsforms">
                                        <div class="firstname">
                                            <label for="">Gender</label><br>
                                            <input type="email" placeholder="Gender">
                                        </div>
                                        <div class="second_input">
                                            <label for="">ID</label><br>
                                            <input type="tel" placeholder="ID">
                                        </div>
                                    </div>
                                    <div class="first_inputsforms">
                                        <div class="firstname">
                                            <label for="">Tax identification Number</label><br>
                                            <input type="email" placeholder="Tax identification Number">
                                        </div>
                                        <div class="second_input">
                                            <label for="">Tax Identification Country</label><br>
                                            <input type="tel" placeholder="Tax Identification Country">
                                        </div>
                                    </div>
                                    <div class="first_inputsforms">
                                        <div class="firstname">
                                            <label for="">Residence adress</label><br>
                                            <textarea name="" id="adresse_residence"></textarea>
                                        </div>
                                        
                                    </div>
                                     <button id="savechanged">Save changed</button>
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