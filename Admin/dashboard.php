
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../vue/font-awesome/css/all.min.css">
    <link rel="stylesheet" href="../vue/styles/stylemenu.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<style>
    /* Styles pour les tableaux */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 12px;
        }

        table th, table td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        /* Styles pour les alertes */
        .vr {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 10px 0;
        }

        .vr i {
            color: #ff6b6b;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .dash_static {
                flex-wrap: wrap;
            }
            
            .static1 {
                width: 45%;
                margin-bottom: 10px;
            }
            
            .dash_lastdiv {
                flex-direction: column;
            }
            
            .left_last, .center_side, .right_last {
                width: 100%;
                margin-bottom: 20px;
            }
        }
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
        margin: 20px;
        padding-top: 10px;
        background-color: #b9c1be;;
        color: black;
        overflow: scroll;
    }
    .tabl_contnu::-webkit-scrollbar{
        display: none;
    }
    .dash_static{
        display: flex;
        justify-content: space-between;
        gap: 5px;

    }
    .static1{
        width: 20%;
        height: 20vh;
        background-color: #ffae2b;
        border-radius: 5px;
        border: 1px solid#ffae2b;
        margin-top: 10px;
        display: flex;
        align-items: center;
        justify-content: space-evenly;

    }
     .static1:nth-child(2){
        background-color:  #021a12;
        border: 1px solid  #021a12;
     }
    .static1:nth-child(3){
        background-color: #ffae2b;
        border: 1px solid  #ffae2b;
     }
    .static1:nth-child(4){
        background-color:  #021a12;
        border: 1px solid  #021a12;
     }
    .vert_div{
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        margin: 10px;
        color: white;
    }
    .vert_div h1{
        margin-top: 10px;
        font-size: 25px;
        color: white;
    }
    .vert_div h2{
        font-size: 25px;
        margin-top: 10px;
    }
    .static1 i{
        font-size: 25px;
        color: #00ffc2;
    }
    .dash_paiement{
        display: flex;
        justify-content: space-between;
       
    }
    .graphs{
        margin: 10px;
    }
    .firstline{
        display: flex;
        align-items: start;
        justify-content: space-between;
        margin-top: 15px;
    }
    .evolutions{
        margin-top: 15px;
    }
    .firstline hr{
        width: 95%;
        margin-top: 10px;
    }
    .months{
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 20px;
    }
    .months p{
        font-size: 12px;
    }
    .left_side{
        width: 72%;
        height: 35vh;
        background-color: rgba(255,255,255,0.12);
        border-radius: 5px;
        margin-top: 15px;
       
    }
    .right_side{
        width: 25%;
        height: 35vh;
        background-color:  #021a12;
        border-radius: 5px;
        margin-top: 15px;
    }
    .dash_lastdiv{
        display: flex;
        justify-content: space-between;
       
    }
    .left_last{
        width: 35%;
        height: 40vh;
        background-color: #ffae2b;;
        margin-top: 28px;
        border-radius: 5px 5px 0 0;
        color: white;
    }
    .left_inscription{
        margin: 10px;
    }
    table{
        border-collapse: collapse;
        width: 100%;
        text-align: start;
        padding: 10px;
        margin-top: 10px;
        color:black;
    }
    thead tr th{
        padding: 10px;
        text-align: start;
    }
    tbody tr td{
        padding: 10px;
    }
    .center_side{
        width: 35%;
        height: 40vh;
        background-color:rgba(255,255,255,0.09);
        margin-top: 28px;
         border-radius: 5px 5px 0 0;
    }
    .right_last{
        width: 25%;
        height: 35vh;
        background-color: #021a12;
         margin-top: 28px;
        border-radius: 5px;
        color: white;
    }
    .left_alert{
        margin: 10px;
        color: white;
    }
    .left_alert h4{
        margin-top: 20px;
        margin-left: 20px;
    }
    .left_alert p{
        margin-top: 20px;
        margin-left: 20px;
        color: white;
    }
    .left_alert hr{
        margin-top: 10px;
        opacity: 0.5;
        color: white;
    }
    .vr{
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        margin-top: 20px;
        color: white;
    }



.gauche {
    width: 20%;
    background: #f4f4f4;
    background-color: #021a12;
    color: white;
    height:94%;
}

.gauche ul {
    list-style: none;
    padding-left: 0;
    margin: 0;
    margin-left: 40px;
}
  .gauche ul li:nth-child(1){
padding-top: 10px;
}
.gauche li {
    margin: 10px 0;
    display: flex;
    flex-direction: column;
}

/* cacher les radios */
.menu input[type="radio"] {
    display: none;
}

.gauche li label {
    display: flex;
    align-items: center;
    cursor: pointer;
    padding: 8px 10px;
    font-weight: 400;
    font-size: 17px;
}

.gauche li label i {
    margin-right: 10px;
    color: white;
}

.gauche li label:hover {
    background-color:#16A34A;
    border-radius: 5px;
    color:white;
}

/* sous-menus */
.submenu {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease;
    list-style: none;
    padding-left: 20px;
    margin-top: 5px;
}

.submenu li a {
    text-decoration: none;
    color: #b9c1be;
    font-size: 17px;
    padding: 3px 0;
    display: block;
}

.submenu li a:hover {
    color: #16A34A;
}

/* ouvrir sous-menu quand radio checké */
.menu input:checked + label + .submenu {
    max-height: 200px;
}
</style>
<body>
    <div class="header_dash">
        <div class="cont_dash">
            <?php include_once('header.php');?>
            <div class="ail_fle">
                <?php include_once('menu.php');?>
                <div class="dashcontainu">
                    <div class="tabl_contnu">
                        <h3>Tableau de bord</h3>
                        
                        <!-- Statistiques -->
                        <div class="dash_static">
                            <div class="static1">
                                <div class="vert_div">
                                    <p>Étudiants</p>
                                    <h1 id="totalEtudiants">0</h1>
                                </div>
                                <i class="fas fa-user-graduate"></i>
                            </div>
                            <div class="static1">
                                <div class="vert_div">
                                    <p>Formations</p>
                                    <h1 id="totalFormations">0</h1>
                                </div>
                                <i class="fas fa-chalkboard-teacher"></i>
                            </div>
                            <div class="static1">
                                <div class="vert_div">
                                    <p>Commandes</p>
                                    <h1 id="totalCommandes">0</h1>
                                </div>
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                            <div class="static1">
                                <div class="vert_div">
                                    <p>Revenus</p>
                                    <h2 id="totalRevenus">0 €</h2>
                                </div>
                                <i class="fas fa-coins"></i>
                            </div>
                        </div>

                        <!-- Graphique des inscriptions -->
                        <div class="dash_paiement">
                            <div class="left_side">
                                <div class="graphs">
                                    <h4>Évolution des inscriptions</h4>
                                    <canvas id="inscriptionsChart" width="400" height="200"></canvas>
                                </div>
                            </div>
                            
                            <div class="right_side">
                                <h4>Statistiques rapides</h4>
                                <div id="quickStats">
                                    <p>Inscriptions ce mois: <span id="inscriptionsMois">0</span></p>
                                    <p>Taux de présence: <span id="tauxPresence">0%</span></p>
                                    <p>Produits en stock: <span id="totalProduits">0</span></p>
                                </div>
                            </div>
                        </div>

                        <!-- Dernières données -->
                        <div class="dash_lastdiv">
                            <!-- Dernières inscriptions -->
                            <div class="left_last">
                                <div class="left_inscription">
                                    <h4>Dernières inscriptions</h4>
                                    <div id="dernieresInscriptions">
                                        <p>Chargement...</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Commandes récentes -->
                            <div class="center_side">
                                <div class="left_inscription">
                                    <h4>Commandes récentes</h4>
                                    <div id="commandesRecentess">
                                        <p>Chargement...</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Alertes stock -->
                            <div class="right_last">
                                <div class="left_alert">
                                    <h4>Alertes stock faible</h4>
                                    <div id="alertesStock">
                                        <p>Chargement...</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Fonction pour appeler l'API
        async function apiCall(entity, action, data = {}) {
            data.entity = entity;
            data.action = action;
            
            try {
                const response = await fetch('../index.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams(data)
                });
                
                return await response.json();
            } catch (error) {
                console.error('Erreur API:', error);
                return { success: false, message: 'Erreur de connexion' };
            }
        }

        // Charger les statistiques du dashboard
        async function loadDashboardStats() {
            try {
                // Charger les statistiques des utilisateurs
                const usersStats = await apiCall('utilisateur', 'getStats');
                if (usersStats.success) {
                    document.getElementById('totalEtudiants').textContent = 
                        usersStats.data.par_role.etudiant || 0;
                }

                // Charger les statistiques des formations
                const formations = await apiCall('formation', 'getAll');
                if (formations.success) {
                    document.getElementById('totalFormations').textContent = formations.data.count;
                }

                // Charger les statistiques des produits
                const productsStats = await apiCall('produit', 'getStats');
                if (productsStats.success) {
                    document.getElementById('totalProduits').textContent = productsStats.data.total;
                    document.getElementById('totalCommandes').textContent = productsStats.data.total_valeur;
                }

                // Charger les statistiques des paiements
                const paymentsStats = await apiCall('paiement', 'getStats');
                if (paymentsStats.success) {
                    document.getElementById('totalRevenus').textContent = 
                        paymentsStats.data.total_valeur + ' €';
                }

                // Charger les dernières inscriptions
                const inscriptions = await apiCall('inscription', 'getAll');
                if (inscriptions.success) {
                    loadLastInscriptions(inscriptions.data.slice(0, 5));
                }

                // Charger les produits à faible stock
                const lowStock = await apiCall('produit', 'getLowStock', { threshold: 5 });
                if (lowStock.success) {
                    loadStockAlerts(lowStock.data);
                }

                // Charger les données pour le graphique
                loadChartData();

            } catch (error) {
                console.error('Erreur chargement dashboard:', error);
            }
        }

        // Afficher les dernières inscriptions
        function loadLastInscriptions(inscriptions) {
            const container = document.getElementById('dernieresInscriptions');
            
            if (inscriptions.length === 0) {
                container.innerHTML = '<p>Aucune inscription récente</p>';
                return;
            }

            let html = '<table border="1"><thead><tr><th>Nom</th><th>Formation</th><th>Date</th></tr></thead><tbody>';
            
            inscriptions.forEach(inscription => {
                // Vous devrez adapter cette partie selon votre structure de données
                html += `
                    <tr>
                        <td>${inscription.id_utilisateur}</td>
                        <td>${inscription.id_formation}</td>
                        <td>${new Date(inscription.date_inscription).toLocaleDateString()}</td>
                    </tr>
                `;
            });
            
            html += '</tbody></table>';
            container.innerHTML = html;
        }

        // Afficher les alertes de stock
        function loadStockAlerts(products) {
            const container = document.getElementById('alertesStock');
            
            if (products.length === 0) {
                container.innerHTML = '<p>Aucune alerte stock</p>';
                return;
            }

            let html = '';
            products.forEach(product => {
                html += `
                    <div class="vr">
                        <i class="fas fa-exclamation-triangle"></i>
                        <h4>${product.nom}</h4>
                    </div>
                    <hr>
                    <p>Stock: ${product.stock} unités</p>
                `;
            });
            
            container.innerHTML = html;
        }

        // Charger les données pour le graphique
        async function loadChartData() {
            try {
                // Récupérer les inscriptions des 6 derniers mois
                const sixMonthsAgo = new Date();
                sixMonthsAgo.setMonth(sixMonthsAgo.getMonth() - 6);
                
                const inscriptions = await apiCall('inscription', 'getAll');
                
                if (inscriptions.success) {
                    const monthlyData = groupInscriptionsByMonth(inscriptions.data, sixMonthsAgo);
                    createChart(monthlyData);
                }
            } catch (error) {
                console.error('Erreur chargement graphique:', error);
            }
        }

        // Grouper les inscriptions par mois
        function groupInscriptionsByMonth(inscriptions, startDate) {
            const monthlyData = {};
            const months = [];
            
            // Initialiser les 6 derniers mois
            for (let i = 0; i < 6; i++) {
                const date = new Date();
                date.setMonth(date.getMonth() - i);
                const monthKey = date.toLocaleString('fr-FR', { month: 'long', year: 'numeric' });
                monthlyData[monthKey] = 0;
                months.unshift(monthKey);
            }
            
            // Compter les inscriptions par mois
            inscriptions.forEach(inscription => {
                const inscriptionDate = new Date(inscription.date_inscription);
                if (inscriptionDate >= startDate) {
                    const monthKey = inscriptionDate.toLocaleString('fr-FR', { month: 'long', year: 'numeric' });
                    if (monthlyData[monthKey] !== undefined) {
                        monthlyData[monthKey]++;
                    }
                }
            });
            
            return {
                labels: months,
                data: months.map(month => monthlyData[month])
            };
        }

        // Créer le graphique
        function createChart(chartData) {
            const ctx = document.getElementById('inscriptionsChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: chartData.labels,
                    datasets: [{
                        label: 'Inscriptions par mois',
                        data: chartData.data,
                        backgroundColor: 'rgba(255, 174, 43, 0.2)',
                        borderColor: 'rgba(255, 174, 43, 1)',
                        borderWidth: 2,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        }

        // Charger le dashboard au chargement de la page
        document.addEventListener('DOMContentLoaded', function() {
            loadDashboardStats();
            
            // Actualiser les données toutes les 5 minutes
            setInterval(loadDashboardStats, 300000);
        });
    </script>
</body>
</html>