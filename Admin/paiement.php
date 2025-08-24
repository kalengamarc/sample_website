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
    <div class="paiement_admin">
        <div class="contain">
                <!-- le header pour  l'application -->
                 <?php include_once('header.php');?>
                <div class="container">
                    <?php include_once('menu.php');?>
                    <main>
                        <div class="principal">
                            <table>
                                    <tr>
                                        <th>Types</th>
                                        <th>References</th>
                                        <th>Montant</th>
                                    </tr>
                                    <tr>
                                        <td>Formations</td>
                                        <td>FORM123</td>
                                        <td>500 €</td>
                                    </tr>
                                    <tr>
                                        <td>Commande</td>
                                        <td>CMD488</td>
                                        <td>200 €</td>
                                    </tr>
                                    <tr>
                                        <td>Formation</td>
                                        <td>FORM793</td>
                                        <td>300 €</td>
                                    </tr>
                                </table>
                        </div>
                    </main>
             </div> 
        </div>
    </div>
</body>
</html>