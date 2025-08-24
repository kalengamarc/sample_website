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
    *{
       margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: sans-serif;
    }
    .equipement_admin{
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100vh;
    }
   .contain{
        display: flex;
        flex-direction: column;
        border-radius: 10px;
        display: flex;
        width:95%;
        align-items: center;
        height: 90vh;
        justify-content: center;
    }
    
 table {
      border-collapse: collapse;
      width: 100%;
      background: white;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    th, td {
      padding: 20px 15px;
      text-align: left;
    }

    th {
      background: #f0f2f5;
      font-weight: bold;
    }

    tr:not(:last-child) {
      border-bottom: 1px solid #e5e5e5;
    }

    td {
      font-size: 14px;
    }
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