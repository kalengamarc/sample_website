<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>headers</title>
    <link rel="stylesheet" href="../vue/font-awesome/css/all.min.css">
    <link rel="stylesheet" href="../vue/styles/stylemenu.css">
    <link rel="stylesheet" href="../vue/styles/style.css">
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
        color: white;
    }
    .text_right{
        display: flex;
        align-items: center;
        justify-content: center;
        margin-left: 80px;
    }
    .text_left{
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }
    .text_right p{
        margin-left: 10px;
    }
    .text_left p{
        margin-left: 10px;
    }
    .text_right i{
        margin-right: 20px;
        cursor: pointer;
    }
   
</style>
<body>
    <div class="dash_tete">
                <div class="text_left">
                    <i class="fas fa-user"></i>
                    <p>Centre de formation</p>
                </div>
                <div class="text_right">
                    <i class=" fas fa-envelope"></i>
                    <i class="fas fa-bell"></i>
                    <i class="fas fa-trash"></i>
                    <i class=" fas fa-sign-out-alt"></i>
                    <p>Deconnection</p>

                </div>
    </div>
</body>
</html>