<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../vue/font-awesome/css/all.min.css">
    <link rel="stylesheet" href="../vue/styles/stylemenu.css">
</head>
<style>
</style>
<body>
    <div class="equipement_admin">
        <div class="contain">
            <!-- le header pour  l'application -->
                 <?php include_once('header.php');?>
                <div class="container">
                    <?php include_once('menu.php');?>
                  <main>
                    <div class="principal">
                         <table>
                                <tr>
                                <th>Nom</th>
                                <th>Prix</th>
                                <th>Stock</th>
                                </tr>
                                <tr>
                                <td>Ordinateur<br>Fortailés</td>
                                <td>300 €</td>
                                <td>10</td>
                                </tr>
                                <tr>
                                <td>Projecteur</td>
                                <td>500 €</td>
                                <td>5</td>
                                </tr>
                                <tr>
                                <td>Tablette<br>Grapingue</td>
                                <td>300 €</td>
                                <td>15</td>
                                </tr>
                            </table>
                    </div>
                </main>
        </div> 
    </div>
    </div>
</body>
</html>