<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../vue/font-awesome/css/all.min.css">
</head>
<style>
    .dash_tete{
        width: 100%;
        height: 6%;
        background-color: rgba(255,255,255,0.09);
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-radius: 5px 5px 0 0;
        border-color: 1px solid gold;
        color:white;
        background: rgba(255,255,255,0.09);
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
    .text_right{
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 80px;
    }
    .text_right a i{
        margin-right: 20px;
        cursor: pointer;
        color:white;
    }

/* TOOLTIP*/

  .message{
       position: relative;
    }
    .message:hover::after{
        position: absolute;
        content: attr(data-tooltip);
        padding: 2px 10px;
        text-align: center;
        top: 35px;
         background-color:gold;
        left: -15px;
        font-size: 13px;
        color: white;
        border-radius: 2px;
        color:  rgba(0,0,0,0.7);
        transition: transform 0.3; 
    }
    
     .notification{
        position: relative;
    }
    .notification:hover::after{
        position: absolute;
        content: attr(data-tooltip);
        padding: 2px 5px;
        text-align: center;
        top: 35px;
         background-color: gold;
        left: -15px;
        font-size: 13px;
        color: white;
        border-radius: 5px;
        color:  rgba(0,0,0,0.7);
    }
    
    .supprimer{
        position: relative;
    }
    .supprimer:hover::after{
        position: absolute;
        content: attr(data-tooltip);
        padding: 2px 10px;
        text-align: center;
        top: 35px;
        background-color: gold;
        left: -15px;
        font-size: 13px;
        border-radius: 5px;
        color:  rgba(0,0,0,0.7);
        border:1px solid rgba(224, 10, 10, 0.06);
    }



</style>
<body>
     <div class="dash_tete">
                <div class="text_left">
                    <i class="fas fa-user"></i>
                    <p>Centre de formation</p>
                </div>
                <div class="text_right">
                     <a href=""><div class="message" data-tooltip="message"><i class=" fas fa-envelope"></i></div></a>
                    <a href=""><div class="notification" data-tooltip="notification"><i class="fas fa-bell"></i></div></a>
                    <a href=""> <div class="supprimer" data-tooltip="supprimer"><i class="fas fa-trash"></i></div></a>
                    <a href=""><div class="" data-tooltip=""><i class=" fas fa-sign-out-alt"></i></div></a>
                    <p>Deconnection</p>
                </div>
            </div>
</body>
</html>