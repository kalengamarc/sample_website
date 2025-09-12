<!DOCTYPE html>
<html lang="en">
<head>
    <?php
        include_once('../controle/controleur_produit.php');
        $ProduitController = new ProduitController();
        $produits = $ProduitController->getAllProduits();
        //print_r($produits);
        //exit();
    ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashborad</title>
    <link rel="stylesheet" href="../vue/font-awesome/css/all.min.css">
    <link rel="stylesheet" href="../vue/styles/stylemenu.css">
</head>
<style>
       .header_dash{
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    .cont_dash{
        width: 100%;
        height: 100vh;
        background-color:  #00110a;
        border-radius: 5px;
    }
    
    .ail_fle{
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: space-between;
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
        text-align:center;
    }
    .tabl_contnu::-webkit-scrollbar{
        display: none;
    }
    .tabl_contnu button{
        color:black;
        padding:4px;
        border-radius:20px;
        border:none;
        display:flex;
        gap:5px;
        align-items:center;
        font-size:15px;
        background
    }
    .tabl_contnu button:hover{
        cursor:pointer;
        background:gold;
    }
    tr:nth-child(2){
        padding:10px;
    }
   table .icons_actuality{
    color:gold;
    display:flex;
    gap:10px;
    font-size:16px;
   }
   table .icons_actuality a i{
    color:rgba(0,0,0,0.7);
   }
   table .icons_actuality i:hover{
    cursor:pointer;
    color: #00110a;
    transform: scale(1.2);
    transition: all 0.3s ease;
   }
   





   /* DATA-TOOLTIP */
   

  .voir{
       position: relative;
    }
    .voir:hover::after{
        position: absolute;
        content: attr(data-tooltip);
        padding: 2px 10px;
        text-align: center;
        top: 30px;
         background-color:gold;
        left: -15px;
        font-size: 13px;
        color: white;
        border-radius: 2px;
        color:  rgba(0,0,0,0.7);
        transition: transform 0.3; 
    }
    
     .modifier{
        position: relative;
    }
    .modifier:hover::after{
        position: absolute;
        content: attr(data-tooltip);
        padding: 2px 5px;
        text-align: center;
        top: 30px;
        background-color: gold;
        left: -15px;
        font-size: 13px;
        color: white;
        border-radius: 5px;
        color:  rgba(0,0,0,0.7);
    }
    
    .supprimer_{
        position: relative;
    }
    .supprimer_:hover::after{
        position: absolute;
        content: attr(data-tooltip);
        padding: 2px 10px;
        text-align: center;
        top: 30px;
        background-color: gold;
        left: -15px;
        font-size: 13px;
        border-radius: 5px;
        color:  rgba(0,0,0,0.7);
        border:1px solid rgba(224, 10, 10, 0.06);
    }

    .ajouter{
       position: relative;
    }
    .ajouter:hover::after{
        position: absolute;
        content: attr(data-tooltip);
        padding: 2px 10px;
        text-align: center;
        top: 30px;
         background-color:gold;
        left: -15px;
        font-size: 13px;
        color: white;
        border-radius: 2px;
        color:  rgba(0,0,0,0.7);
        transition: transform 0.3; 
    }



.titre_formation {
    width: 100%;
    height:50px;
    gap:30px;
    display:flex;
    align-items:center;
    justify-content:center;
}


/* BOUTTON AJOUTER*/

    .ajouter a{
        padding:5px;
        background:black;
        border-radius:50%;
        position:abso lute;
        top:30px;
        color:white;
        text-align:center;
        border:3px solid white;
    }


 table {
    border:2px solid red;
    margin-top:20px;
      border-collapse: collapse;
      width: 100%;
      background: white;
      overflow: hidden;
      box-shadow: 0 4px 15px rgba(0,0,0,0.15);
      border-radius: 10px;
    }

    th, td {
      padding: 15px 12px;
      text-align: left;
    }

    th {
      background: linear-gradient(135deg, #00110a, #1a1a1a);
      color: gold;
      font-weight: bold;
      font-size: 14px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    tr:not(:last-child) {
      border-bottom: 1px solid #e5e5e5;
    }
    
    tr:hover {
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        transition: all 0.3s ease;
    }

    td {
      font-size: 14px;
      font-weight: 500;
    }
    
    .product-image {
        width: 50px;
        height: 50px;
        border-radius: 8px;
        object-fit: cover;
        border: 3px solid #00110a;
        box-shadow: 0 3px 10px rgba(0,0,0,0.2);
        transition: transform 0.3s ease;
    }
    
    .product-image:hover {
        transform: scale(1.1);
    }

    /* Styles pour la modal */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.8);
        backdrop-filter: blur(8px);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 1000;
        opacity: 0;
        transition: all 0.3s ease;
    }

    .modal-overlay.active {
        display: flex;
        opacity: 1;
    }

    .product-card {
        background: linear-gradient(135deg, #ffffff, #f8fafc);
        border-radius: 20px;
        overflow: hidden;
        max-width: 500px;
        width: 90%;
        max-height: 90vh;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
        transform: scale(0.8) translateY(50px);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
        overflow-y: auto;
    }

    .modal-overlay.active .product-card {
        transform: scale(1) translateY(0);
    }

    .card-header {
        background: linear-gradient(135deg, #00110a, #1a1a1a);
        color: gold;
        padding: 40px 30px 30px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .card-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, transparent 30%, rgba(255,215,0,0.1) 50%, transparent 70%);
        animation: shimmer 3s ease-in-out infinite;
    }

    @keyframes shimmer {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }

    .product-photo {
        width: 120px;
        height: 120px;
        border-radius: 15px;
        margin: 0 auto 20px;
        border: 4px solid gold;
        position: relative;
        z-index: 2;
        overflow: hidden;
        background: rgba(255, 255, 255, 0.1);
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    }

    .product-photo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .card-name {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 8px;
        position: relative;
        z-index: 2;
    }

    .card-category {
        font-size: 1.1rem;
        opacity: 0.9;
        position: relative;
        z-index: 2;
        font-weight: 500;
        background: rgba(255,215,0,0.2);
        padding: 5px 15px;
        border-radius: 15px;
        display: inline-block;
    }

    .card-body {
        padding: 30px;
        background: white;
    }

    .info-section {
        margin-bottom: 25px;
    }

    .info-section:last-child {
        margin-bottom: 0;
    }

    .section-title {
        color: #00110a;
        font-weight: 700;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        font-size: 1.1rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .section-title i {
        margin-right: 12px;
        color: gold;
        width: 20px;
        text-align: center;
    }

    .info-grid {
        display: grid;
        gap: 12px;
    }

    .info-item {
        display: flex;
        align-items: center;
        padding: 15px;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border-radius: 12px;
        border-left: 4px solid gold;
        transition: all 0.3s ease;
    }

    .info-item:hover {
        transform: translateX(5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .info-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #00110a, #1a1a1a);
        color: gold;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        font-size: 0.9rem;
    }

    .info-content {
        flex: 1;
    }

    .info-label {
        color: #6b7280;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 2px;
    }

    .info-value {
        color: #00110a;
        font-weight: 600;
        font-size: 1rem;
    }

    .price-display {
        background: linear-gradient(135deg, gold, #ffd700);
        color: #00110a;
        padding: 10px 20px;
        border-radius: 25px;
        font-weight: 700;
        font-size: 1.2rem;
        text-align: center;
        margin-top: 10px;
        box-shadow: 0 5px 15px rgba(255, 215, 0, 0.3);
    }

    .stock-badge {
        display: inline-block;
        padding: 8px 16px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .stock-high {
        background: #dcfce7;
        color: #166534;
    }

    .stock-medium {
        background: #fef3c7;
        color: #92400e;
    }

    .stock-low {
        background: #fee2e2;
        color: #dc2626;
    }

    .description-text {
        background: #f8fafc;
        padding: 15px;
        border-radius: 10px;
        border-left: 4px solid gold;
        font-style: italic;
        color: #374151;
        line-height: 1.6;
    }

    .close-btn {
        position: absolute;
        top: 15px;
        right: 15px;
        background: rgba(255, 215, 0, 0.2);
        border: none;
        color: gold;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        transition: all 0.3s ease;
        z-index: 10;
        backdrop-filter: blur(10px);
    }

    .close-btn:hover {
        background: rgba(255, 215, 0, 0.3);
        transform: rotate(90deg);
    }

    .action-buttons {
        display: flex;
        gap: 10px;
        margin-top: 20px;
    }

    .action-btn {
        flex: 1;
        padding: 12px;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-edit {
        background: linear-gradient(135deg, gold, #ffd700);
        color: #00110a;
    }

    .btn-delete {
        background: linear-gradient(135deg, #dc2626, #ef4444);
        color: white;
    }

    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    @media (max-width: 768px) {
        .product-card {
            margin: 20px;
            width: calc(100% - 40px);
        }
        
        .action-buttons {
            flex-direction: column;
        }
    }
</style>
<body> 
    <div class="header_dash">
        <div class="cont_dash">
            <div class="ail_fle">
                 <?php include_once('menu.php');?>
                <div class="dashcontainu">
                    <div class="tabl_contnu">
                        <div class="titre_formation">
                            <h3>Liste des equipements</h3>
                            <div class="ajouter" data-tooltip="ajouter un equipement"><a href="../Admin/ajoutProduit.php"><i class="fas fa-plus"></i></a></div>
                        </div>
                        <div class="principal">
                            <table>
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nom</th>
                                            <th>Description</th>
                                            <th>categorie</th>
                                            <th>Prix</th>
                                            <th>Quantite</th>
                                            <th>Photo</th>
                                            <th>action</th>
                                        </tr>
                                    </thead>
                                   <?php $counter = 1; foreach($produits['data'] as $produit):?>
                                    <tr>
                                        <td><?=$counter++?></td>
                                        <td><?=$produit->getNom()?></td>
                                        <td><?=substr($produit->getDescription(), 0, 50)?>...</td>
                                        <td><?=$produit->getCategorie()?></td>
                                        <td><?=$produit->getPrix()?> Fbu</td>
                                        <td><?=$produit->getStock()?></td>
                                        <td><img src="../controle/<?=$produit->getPhoto()?>" alt="Equipement" class="product-image">
                                        </td>
                                        <td>
                                                <div class="icons_actuality">
                                                    <div class="voir" data-tooltip="Voir" onclick="showProductCard({
                                                        nom: '<?=addslashes($produit->getNom())?>',
                                                        description: '<?=addslashes($produit->getDescription())?>',
                                                        categorie: '<?=addslashes($produit->getCategorie())?>',
                                                        prix: '<?=$produit->getPrix()?>',
                                                        stock: '<?=$produit->getStock()?>',
                                                        photo: '../controle/<?=$produit->getPhoto()?>'
                                                    })">
                                                        <i class="fas fa-eye"></i>
                                                    </div>
                                                    <a href="AjoutProduit.php?resp=<?= $produit->getIdProduit() ?>"><div class="modifier" data-tooltip="modifier"><i class="fas fa-edit"></i></div></a>
                                                    <a href="../controle/index.php?do=produit_delete&id=<?= $produit->getIdProduit() ?>"> <div class="supprimer_" data-tooltip="supprimer"><i class="fas fa-trash"></i></div></a>
                                                </div>
                                            </td>
                                    </tr>
                                    <?php endforeach;?>
                                    
                                </table>
                                <div style="display:flex; justify-content:center; align-items:center; margin-top:20px; padding:15px; gap:30px; background: linear-gradient(135deg, #00110a, #1a1a1a); color:gold; border-radius:10px; box-shadow: 0 5px 15px rgba(0,0,0,0.3);">
                                    <h3><i class="fas fa-boxes"></i> Stock Total : <?=$produits['stats']['total_stock'] ?>   </h3>
                                    <h3><i class="fas fa-chart-line"></i> Moyenne de Prix: <?=$produits['stats']['average_price'] ?> Fbu   </h3>
                                    <h3><i class="fas fa-euro-sign"></i> Montant Total: <?=$produits['stats']['total_valeur'] ?> Fbu  </h3>
                                </div>
                        </div> 
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal pour la carte du produit -->
    <div class="modal-overlay" id="productModal">
        <div class="product-card">
            <button class="close-btn" onclick="closeProductCard()">
                <i class="fas fa-times"></i>
            </button>
            
            <div class="card-header">
                <div class="product-photo" id="productPhoto">
                    <!-- Image sera ajoutée dynamiquement -->
                </div>
                <div class="card-name" id="cardName">Nom du Produit</div>
                <div class="card-category" id="cardCategory">Catégorie</div>
            </div>
            
            <div class="card-body">
                <div class="info-section">
                    <h4 class="section-title">
                        <i class="fas fa-info-circle"></i>
                        Informations Générales
                    </h4>
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-tag"></i>
                            </div>
                            <div class="info-content">
                                <div class="info-label">Prix Unitaire</div>
                                <div class="info-value" id="cardPrice">0 €</div>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-warehouse"></i>
                            </div>
                            <div class="info-content">
                                <div class="info-label">Stock Disponible</div>
                                <div class="info-value">
                                    <span id="cardStock" class="stock-badge">0</span>
                                </div>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-list-alt"></i>
                            </div>
                            <div class="info-content">
                                <div class="info-label">Catégorie</div>
                                <div class="info-value" id="cardCategoryInfo">Catégorie</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="price-display" id="totalValue">
                        Valeur Total du Stock: 0 €
                    </div>
                </div>
                
                <div class="info-section">
                    <h4 class="section-title">
                        <i class="fas fa-file-alt"></i>
                        Description Détaillée
                    </h4>
                    <div class="description-text" id="cardDescription">
                        Description du produit...
                    </div>
                </div>
                
                <div class="action-buttons">
                    <a href="AjoutProduit.php?resp=<?= $produit->getIdProduit() ?>" class="action-btn btn-edit" id="editBtn">
                        <i class="fas fa-edit"></i>
                        Modifier
                    </a>
                    <a href="../controle/index.php?do=produit_delete&id=<?= $produit->getIdProduit() ?>" class="action-btn btn-delete" id="deleteBtn">
                        <i class="fas fa-trash"></i>
                        Supprimer
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showProductCard(data) {
            // Remplir les informations de base
            document.getElementById('cardName').textContent = data.nom || 'Nom du Produit';
            document.getElementById('cardCategory').textContent = data.categorie || 'Catégorie';
            document.getElementById('cardCategoryInfo').textContent = data.categorie || 'Catégorie';
            document.getElementById('cardPrice').textContent = (data.prix || '0') + ' Fbu';
            document.getElementById('cardDescription').textContent = data.description || 'Aucune description disponible';
            
            // Gestion de la photo
            const photoElement = document.getElementById('productPhoto');
            if (data.photo && data.photo.trim() !== '') {
                photoElement.innerHTML = `<img src="${data.photo}" alt="${data.nom}">`;
            } else {
                photoElement.innerHTML = '<i class="fas fa-image" style="font-size: 3rem; color: gold;"></i>';
            }
            
            // Gestion du stock avec couleurs
            const stock = parseInt(data.stock) || 0;
            const stockElement = document.getElementById('cardStock');
            stockElement.textContent = stock + ' unités';
            
            // Colorer selon le niveau de stock
            stockElement.className = 'stock-badge ';
            if (stock > 20) {
                stockElement.className += 'stock-high';
            } else if (stock > 5) {
                stockElement.className += 'stock-medium';
            } else {
                stockElement.className += 'stock-low';
            }
            
            // Calcul valeur totale
            const prix = parseFloat(data.prix) || 0;
            const totalValue = (prix * stock).toFixed(2);
            document.getElementById('totalValue').textContent = `Valeur Total du Stock: ${totalValue} €`;
            
            // Afficher la modal
            const modal = document.getElementById('productModal');
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
        
        function closeProductCard() {
            const modal = document.getElementById('productModal');
            modal.classList.remove('active');
            document.body.style.overflow = 'auto';
        }
        
        // Fermer avec clic sur overlay
        document.getElementById('productModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeProductCard();
            }
        });
        
        // Fermer avec Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeProductCard();
            }
        });
    </script>
</body>
</html>