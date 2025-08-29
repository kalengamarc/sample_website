<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>commande</title>
    <link rel="stylesheet" href="../vue/font-awesome/css/all.min.css">
    <link rel="stylesheet" href="../vue/styles/stylemenu.css">
    <link rel="stylesheet" href="../vue/styles/style.css">

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
                            <table>
                                    <tr>
                                        <th>Clients</th>
                                        <th>Date</th>
                                        <th>Statut</th>
                                        <th>Montant</th>
                                    </tr>
                                    <tr>
                                        <td>jean</td>
                                        <td>12/12/2024</td>
                                        <td>ematieses</td>
                                        <td>2000</td>
                                    </tr>
                                    <tr>
                                        <td>marie</td>
                                        <td>12/2/2019</td>
                                        <td>ematieses</td>
                                        <td>2000</td>
                                    </tr>
                                    <tr>
                                        <td>jean</td>
                                        <td>12/1/2020</td>
                                        <td>ematieses</td>
                                        <td>2000</td>
                                    </tr>
                                </table>
                        </div>
                    </main>
             </div> 
        </div>
    </div>
</body>
</html>