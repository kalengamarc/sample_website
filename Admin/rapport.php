<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../vue/font-awesome/css/all.min.css">
    <link rel="stylesheet" href="../vue/styles/stylemenu.css">
</head>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>commande</title>
    <link rel="stylesheet" href="../vue/font-awesome/css/all.min.css">
    <link rel="stylesheet" href="../vue/styles/stylemenu.css">
    <style>

.principal{
    display: flex;
    flex-direction: column;
    background-color:white;
    flex: 1;
    margin: 10px;
    height:auto;
    border-radius: 10px;
    gap: 0px;
}

.tache{
    display: flex;
    flex-direction:row;
    margin: 10px;
    height:60px;
    align-items: center;
    gap: 20px;
}
.tache:nth-child(1){
    margin-top: 20px;
}
.tache:not(:last-child) {
      border-bottom: 1px solid #e5e5e5;
    }
.tache i{
    color: white;
    padding: 4px;
    background-color:blue;
    border-radius: 5px;
    margin-right: 10px;
}
.image{
    width: 50px;
    height: 50px;
    background-color: blue;
    border-radius: 50px;
    margin-left: 20px;
}
.image img{
    width: 100%;
    height: 100%;
    border-radius: 10px;
    border: 1px solid whitesmoke;
    object-fit: cove;
}
.description{
    height: 50px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex: 1;
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
                        <div class="principal">
                        <div class="tache">
                            <div class="image"><img src="../vue/images/onduleur.jpg" alt="web"></div>
                            <div class="description">Developpement web<i class="fas fa-chevron-down"></i></div>
                        </div>
                        <div class="tache">
                            <div class="image"><img src="../vue/images/cable.jpg" alt="web"></div>
                            <div class="description">gestion de projet<i class="fas fa-chevron-down"></i></div>
                        </div>
                        <div class="tache">
                            <div class="image"><img src="../vue/images/windows.jpg" alt="web"></div>
                            <div class="description">Marketing digital<i class="fas fa-chevron-down"></i></div>
                        </div>
                        </div>
                    </main>
             </div> 
        </div>
    </div>
</body>
</html>