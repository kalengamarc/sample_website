<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../vue/styles/style.css">
    <style>
    .header_dash{
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .cont_dash{
        width: 95%;
        height: 120vh;
        background-color:  #00110a;
        border-radius: 5px;
    }
    .dash_tete{
        width: 100%;
        height: 6%;
        background-color: rgba(255,255,255,0.09);
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-radius: 5px 5px 0 0;
        border-color: 1px solid gold;
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
    .text_right i{
        margin-right: 20px;
        cursor: pointer;
    }
    </style>
    <title>Document</title>
</head>
<body>
    <div class="header_side">
       <div class="dash_tete">
                <div class="text_left">
                    <i class="fas fa-user"></i>
                    <p>Centre de formtion</p>
                </div>
                <div class="text_right">
                    <i class=" fas fa-envelope"></i>
                    <i class="fas fa-bell"></i>
                    <i class="fas fa-trash"></i>
                    <i class=" fas fa-cog"></i>
                    <p>Deconnection</p>
                </div>
            </div>
    </div> 
</body>
</html>

