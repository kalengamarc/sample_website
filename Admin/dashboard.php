<?php
// Vérification de session en premier
require_once 'session_check.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Administratif - JosNet</title>
    <link rel="stylesheet" href="../vue/font-awesome/css/all.min.css">
    <link rel="stylesheet" href="../vue/styles/stylemenu.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
</head>
<style>
    /* Reset et styles de base */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    body {
        background-color: #f8fafc;
        color: #333;
        overflow-x: hidden;
    }
    
    /* Layout principal */
    .dashboard-container {
        display: flex;
        min-height: 100vh;
    }
    
    /* Sidebar amélioré */
    .sidebar {
        width: 280px;
        background: linear-gradient(135deg, #04221a 0%, #2c5f2d 100%);
        color: white;
        height: 100vh;
        position: fixed;
        left: 0;
        top: 0;
        z-index: 1000;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
        overflow-y: auto;
    }
    
    .sidebar-header {
        padding: 20px;
        text-align: center;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .sidebar-logo {
        width: 50px;
        height: 50px;
        margin: 0 auto 10px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        color: #ffae2b;
    }
    
    .sidebar-header h2 {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 5px;
    }
    
    .sidebar-header p {
        font-size: 12px;
        color: rgba(255, 255, 255, 0.7);
    }
    
    /* Menu styles */
    .menu {
        padding: 15px;
    }
    
    .menu-item {
        margin: 8px 0;
    }
    
    .menu-item input[type="radio"] {
        display: none;
    }
    
    .menu-item label {
        display: flex;
        align-items: center;
        padding: 12px 15px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 500;
        position: relative;
    }
    
    .menu-item label:before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 4px;
        background: #ffae2b;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .menu-item label i {
        margin-right: 12px;
        width: 20px;
        text-align: center;
        font-size: 16px;
        color: #ffae2b;
    }
    
    .menu-item label .arrow {
        margin-left: auto;
        font-size: 12px;
        transition: transform 0.3s ease;
    }
    
    .menu-item label:hover, .menu-item input:checked + label {
        background-color: rgba(255, 255, 255, 0.1);
    }
    
    .menu-item label:hover:before, .menu-item input:checked + label:before {
        opacity: 1;
    }
    
    .menu-item input:checked + label .arrow {
        transform: rotate(90deg);
    }
    
    .submenu {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.4s ease;
        padding-left: 20px;
        background: rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        margin: 5px 0 10px;
    }
    
    .submenu a {
        display: block;
        padding: 10px 15px;
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
        font-size: 14px;
        transition: all 0.3s ease;
        border-left: 2px solid transparent;
    }
    
    .submenu a:hover, .submenu a.active {
        color: white;
        border-left-color: #ffae2b;
        padding-left: 20px;
        background: rgba(255, 255, 255, 0.05);
    }
    
    .menu-item input:checked + label + .submenu {
        max-height: 500px;
        padding: 8px 0;
    }
    
    .sidebar-footer {
        padding: 15px 20px;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        margin-top: 20px;
        text-align: center;
        font-size: 12px;
        color: rgba(255, 255, 255, 0.6);
    }
    
    /* Menu par défaut pour la démo */
    .demo-menu {
        padding: 20px;
    }
    
    .demo-menu .menu-item {
        margin: 8px 0;
    }
    
    .demo-menu .menu-item label {
        display: flex;
        align-items: center;
        padding: 12px 15px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 500;
        position: relative;
    }
    
    .demo-menu .menu-item label:hover {
        background-color: rgba(255, 255, 255, 0.1);
    }
    
    .demo-menu .menu-item label i {
        margin-right: 12px;
        width: 20px;
        text-align: center;
        font-size: 16px;
        color: #ffae2b;
    }
    
    /* Contenu principal */
    .main-content {
        flex: 1;
        margin-left: 280px;
        padding: 20px;
        background-color: #f1f5f9;
        min-height: 100vh;
    }
    
    .dashboard-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .dashboard-header h1 {
        font-size: 24px;
        color: #04221a;
        font-weight: 700;
    }
    
    .header-actions {
        display: flex;
        gap: 15px;
    }
    
    .header-action-btn {
        background: white;
        border: 1px solid #e2e8f0;
        padding: 8px 15px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 14px;
    }
    
    .header-action-btn:hover {
        background: #04221a;
        color: white;
        border-color: #04221a;
    }
    
    /* Cartes de statistiques */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
    
    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.04);
        display: flex;
        align-items: center;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
    }
    
    .stat-card.primary {
        background: linear-gradient(135deg, #04221a 0%, #2c5f2d 100%);
        color: white;
    }
    
    .stat-card.warning {
        background: linear-gradient(135deg, #ffae2b 0%, #ffc107 100%);
        color: white;
    }
    
    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-right: 15px;
        background: rgba(255, 255, 255, 0.2);
    }
    
    .stat-info h3 {
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 5px;
    }
    
    .stat-info p {
        font-size: 14px;
        opacity: 0.9;
    }
    
    /* Graphiques et visualisations */
    .charts-container {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 20px;
        margin-bottom: 30px;
    }
    
    .chart-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.04);
    }
    
    .chart-card h3 {
        font-size: 18px;
        margin-bottom: 15px;
        color: #04221a;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .chart-card h3 i {
        color: #ffae2b;
    }
    
    .stat-item {
        padding: 15px 0;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .stat-item:last-child {
        border-bottom: none;
    }
    
    /* Tableaux de données */
    .data-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
    
    .data-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.04);
        overflow: hidden;
    }
    
    .data-card h3 {
        font-size: 18px;
        margin-bottom: 15px;
        color: #04221a;
        display: flex;
        align-items: center;
        gap: 10px;
        padding-bottom: 10px;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .data-card h3 i {
        color: #ffae2b;
    }
    
    .data-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .data-table th, .data-table td {
        padding: 12px 8px;
        text-align: left;
        border-bottom: 1px solid #e2e8f0;
        font-size: 14px;
    }
    
    .data-table th {
        font-weight: 600;
        color: #64748b;
    }
    
    .data-table tr:last-child td {
        border-bottom: none;
    }
    
    .data-table tr:hover {
        background-color: #f8fafc;
    }
    
    /* Alertes */
    .alert-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.04);
        border-left: 4px solid #ff6b6b;
    }
    
    .alert-card h3 {
        font-size: 18px;
        margin-bottom: 15px;
        color: #04221a;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .alert-card h3 i {
        color: #ff6b6b;
    }
    
    .alert-item {
        display: flex;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .alert-item:last-child {
        border-bottom: none;
    }
    
    .alert-icon {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: rgba(255, 107, 107, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
        color: #ff6b6b;
    }
    
    .alert-content {
        flex: 1;
    }
    
    .alert-content h4 {
        font-size: 14px;
        margin-bottom: 4px;
    }
    
    .alert-content p {
        font-size: 12px;
        color: #64748b;
    }
    
    /* Badges et indicateurs */
    .badge {
        padding: 4px 8px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    
    .badge-success {
        background: #dcfce7;
        color: #16a34a;
    }
    
    .badge-warning {
        background: #fef9c3;
        color: #ca8a04;
    }
    
    .badge-danger {
        background: #fee2e2;
        color: #dc2626;
    }
    
    /* Responsive design */
    @media (max-width: 1024px) {
        .sidebar {
            width: 80px;
            overflow: visible;
        }
        
        .sidebar-header h2, 
        .sidebar-header p, 
        .demo-menu .menu-item label span:not(.arrow), 
        .sidebar-footer {
            display: none;
        }
        
        .demo-menu .menu-item label {
            justify-content: center;
            padding: 15px 10px;
        }
        
        .demo-menu .menu-item label i {
            margin-right: 0;
            font-size: 18px;
        }
        
        .sidebar:hover {
            width: 280px;
            z-index: 1001;
        }
        
        .sidebar:hover .sidebar-header h2, 
        .sidebar:hover .sidebar-header p, 
        .sidebar:hover .demo-menu .menu-item label span:not(.arrow), 
        .sidebar:hover .sidebar-footer {
            display: block;
        }
        
        .sidebar:hover .demo-menu .menu-item label {
            justify-content: flex-start;
            padding: 12px 15px;
        }
        
        .sidebar:hover .demo-menu .menu-item label i {
            margin-right: 12px;
            font-size: 16px;
        }
        
        .main-content {
            margin-left: 80px;
        }
        
        .sidebar:hover ~ .main-content {
            margin-left: 280px;
        }
        
        .charts-container {
            grid-template-columns: 1fr;
        }
    }
    
    @media (max-width: 768px) {
        .main-content {
            margin-left: 0;
            padding: 15px;
        }
        
        .sidebar {
            transform: translateX(-100%);
            transition: transform 0.3s ease;
        }
        
        .sidebar.mobile-open {
            transform: translateX(0);
        }
        
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .data-grid {
            grid-template-columns: 1fr;
        }
        
        .dashboard-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }
        
        .header-actions {
            width: 100%;
            justify-content: space-between;
        }
    }
    
    /* Animations */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .animate-in {
        animation: fadeIn 0.6s ease forwards;
    }
    
    /* Utilitaires */
    .text-center {
        text-align: center;
    }
    
    .mt-20 {
        margin-top: 20px;
    }
    
    .mb-20 {
        margin-bottom: 20px;
    }
    
    /* Loader */
    .loader {
        display: inline-block;
        width: 20px;
        height: 20px;
        border: 3px solid rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        border-top-color: #fff;
        animation: spin 1s ease-in-out infinite;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
</style>
<body>
    <div class="dashboard-container">
        
        <!-- Sidebar / Menu -->
        <div class="sidebar">
            <?php include_once('menu.php');?>    
        </div>

        <!-- Contenu principal -->
        <div class="main-content">
            <div class="dashboard-header">
                <h1>Tableau de bord</h1>
                <div class="header-actions">
                    <div class="header-action-btn">
                        <i class="fas fa-sync-alt"></i>
                        <span>Actualiser</span>
                    </div>
                    <div class="header-action-btn">
                        <i class="fas fa-download"></i>
                        <span>Exporter</span>
                    </div>
                    <div class="header-action-btn">
                        <i class="fas fa-bell"></i>
                        <span>Notifications</span>
                    </div>
                </div>
            </div>

            <!-- Cartes de statistiques -->
            <div class="stats-grid">
                <div class="stat-card primary animate-in" style="animation-delay: 0.1s">
                    <div class="stat-icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <div class="stat-info">
                        <h3 id="totalEtudiants">247</h3>
                        <p>Étudiants inscrits</p>
                    </div>
                </div>
                
                <div class="stat-card animate-in" style="animation-delay: 0.2s">
                    <div class="stat-icon" style="background: rgba(79, 70, 229, 0.1); color: #4f46e5;">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <div class="stat-info">
                        <h3 id="totalFormations">18</h3>
                        <p>Formations actives</p>
                    </div>
                </div>
                
                <div class="stat-card warning animate-in" style="animation-delay: 0.3s">
                    <div class="stat-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="stat-info">
                        <h3 id="totalCommandes">89</h3>
                        <p>Commandes ce mois</p>
                    </div>
                </div>
                
                <div class="stat-card animate-in" style="animation-delay: 0.4s">
                    <div class="stat-icon" style="background: rgba(34, 197, 94, 0.1); color: #22c55e;">
                        <i class="fas fa-coins"></i>
                    </div>
                    <div class="stat-info">
                        <h3 id="totalRevenus">12,450 €</h3>
                        <p>Revenus totaux</p>
                    </div>
                </div>
            </div>

            <!-- Graphiques -->
            <div class="charts-container">
                <div class="chart-card animate-in" style="animation-delay: 0.5s">
                    <h3><i class="fas fa-chart-line"></i> Évolution des inscriptions</h3>
                    <canvas id="inscriptionsChart" height="250"></canvas>
                </div>
                
                <div class="chart-card animate-in" style="animation-delay: 0.6s">
                    <h3><i class="fas fa-chart-pie"></i> Statistiques rapides</h3>
                    <div id="quickStats">
                        <div class="stat-item">
                            <span>Inscriptions ce mois</span>
                            <strong id="inscriptionsMois">34</strong>
                        </div>
                        <div class="stat-item">
                            <span>Taux de présence</span>
                            <strong id="tauxPresence">87%</strong>
                        </div>
                        <div class="stat-item">
                            <span>Produits en stock</span>
                            <strong id="totalProduits">156</strong>
                        </div>
                        <div class="stat-item">
                            <span>Taux de conversion</span>
                            <strong>68%</strong>
                        </div>
                        <div class="stat-item">
                            <span>Satisfaction client</span>
                            <strong>4.7/5</strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Données récentes -->
            <div class="data-grid">
                <div class="data-card animate-in" style="animation-delay: 0.7s">
                    <h3><i class="fas fa-user-plus"></i> Dernières inscriptions</h3>
                    <div id="dernieresInscriptions">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Formation</th>
                                    <th>Date</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Marie Dupont</td>
                                    <td>Développement Web</td>
                                    <td>07/09/2024</td>
                                    <td><span class="badge badge-success">Actif</span></td>
                                </tr>
                                <tr>
                                    <td>Jean Martin</td>
                                    <td>Marketing Digital</td>
                                    <td>06/09/2024</td>
                                    <td><span class="badge badge-success">Actif</span></td>
                                </tr>
                                <tr>
                                    <td>Sophie Bernard</td>
                                    <td>Design UX/UI</td>
                                    <td>05/09/2024</td>
                                    <td><span class="badge badge-warning">En attente</span></td>
                                </tr>
                                <tr>
                                    <td>Paul Leroy</td>
                                    <td>Data Science</td>
                                    <td>04/09/2024</td>
                                    <td><span class="badge badge-success">Actif</span></td>
                                </tr>
                                <tr>
                                    <td>Emma Rousseau</td>
                                    <td>Cybersécurité</td>
                                    <td>03/09/2024</td>
                                    <td><span class="badge badge-success">Actif</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="data-card animate-in" style="animation-delay: 0.8s">
                    <h3><i class="fas fa-shopping-cart"></i> Commandes récentes</h3>
                    <div id="commandesRecentess">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Référence</th>
                                    <th>Client</th>
                                    <th>Montant</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>#CMD001</td>
                                    <td>A. Dubois</td>
                                    <td>299 €</td>
                                    <td><span class="badge badge-success">Livré</span></td>
                                </tr>
                                <tr>
                                    <td>#CMD002</td>
                                    <td>M. Laurent</td>
                                    <td>159 €</td>
                                    <td><span class="badge badge-warning">En cours</span></td>
                                </tr>
                                <tr>
                                    <td>#CMD003</td>
                                    <td>S. Garcia</td>
                                    <td>89 €</td>
                                    <td><span class="badge badge-success">Livré</span></td>
                                </tr>
                                <tr>
                                    <td>#CMD004</td>
                                    <td>L. Moreau</td>
                                    <td>449 €</td>
                                    <td><span class="badge badge-warning">En attente</span></td>
                                </tr>
                                <tr>
                                    <td>#CMD005</td>
                                    <td>C. Thomas</td>
                                    <td>199 €</td>
                                    <td><span class="badge badge-success">Livré</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Alertes -->
            <div class="alert-card animate-in" style="animation-delay: 0.9s">
                <h3><i class="fas fa-exclamation-triangle"></i> Alertes stock faible</h3>
                <div id="alertesStock">
                    <div class="alert-item">
                        <div class="alert-icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="alert-content">
                            <h4>Manuel JavaScript Avancé</h4>
                            <p>Stock critique: 3 unités restantes</p>
                        </div>
                    </div>
                    <div class="alert-item">
                        <div class="alert-icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="alert-content">
                            <h4>Kit développement Python</h4>
                            <p>Stock critique: 1 unité restante</p>
                        </div>
                    </div>
                    <div class="alert-item">
                        <div class="alert-icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="alert-content">
                            <h4>Cours React Native</h4>
                            <p>Stock critique: 2 unités restantes</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Données par défaut pour le graphique
        const defaultChartData = {
            labels: ['Avril 2024', 'Mai 2024', 'Juin 2024', 'Juillet 2024', 'Août 2024', 'Septembre 2024'],
            data: [12, 19, 25, 18, 34, 28]
        };

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
                if (usersStats.success && usersStats.data.par_role.etudiant) {
                    document.getElementById('totalEtudiants').textContent = 
                        usersStats.data.par_role.etudiant;
                }

                // Charger les statistiques des formations
                const formations = await apiCall('formation', 'getAll');
                if (formations.success && formations.data.count) {
                    document.getElementById('totalFormations').textContent = formations.data.count;
                }

                // Charger les statistiques des produits
                const productsStats = await apiCall('produit', 'getStats');
                if (productsStats.success) {
                    if (productsStats.data.total) {
                        document.getElementById('totalProduits').textContent = productsStats.data.total;
                    }
                    if (productsStats.data.total_valeur) {
                        document.getElementById('totalCommandes').textContent = productsStats.data.total_valeur;
                    }
                }

                // Charger les statistiques des paiements
                const paymentsStats = await apiCall('paiement', 'getStats');
                if (paymentsStats.success && paymentsStats.data.total_valeur) {
                    document.getElementById('totalRevenus').textContent = 
                        paymentsStats.data.total_valeur + ' €';
                }

                // Charger les dernières inscriptions
                const inscriptions = await apiCall('inscription', 'getAll');
                if (inscriptions.success && inscriptions.data.length > 0) {
                    loadLastInscriptions(inscriptions.data.slice(0, 5));
                }

                // Charger les commandes récentes
                const commandes = await apiCall('commande', 'getRecent');
                if (commandes.success && commandes.data.length > 0) {
                    loadRecentOrders(commandes.data.slice(0, 5));
                }

                // Charger les produits à faible stock
                const lowStock = await apiCall('produit', 'getLowStock', { threshold: 5 });
                if (lowStock.success && lowStock.data.length > 0) {
                    loadStockAlerts(lowStock.data);
                }

                // Charger les données pour le graphique
                await loadChartData();

            } catch (error) {
                console.error('Erreur chargement dashboard:', error);
                // En cas d'erreur, on garde les valeurs par défaut
            }
        }

        // Afficher les dernières inscriptions
        function loadLastInscriptions(inscriptions) {
            const container = document.getElementById('dernieresInscriptions');
            
            if (inscriptions.length === 0) {
                return; // Garder les données par défaut
            }

            let html = '<table class="data-table"><thead><tr><th>Nom</th><th>Formation</th><th>Date</th><th>Statut</th></tr></thead><tbody>';
            
            inscriptions.forEach(inscription => {
                html += `
                    <tr>
                        <td>${inscription.nom || 'N/A'}</td>
                        <td>${inscription.formation || 'N/A'}</td>
                        <td>${new Date(inscription.date_inscription).toLocaleDateString()}</td>
                        <td><span class="badge badge-success">Actif</span></td>
                    </tr>
                `;
            });
            
            html += '</tbody></table>';
            container.innerHTML = html;
        }

        // Afficher les commandes récentes
        function loadRecentOrders(commandes) {
            const container = document.getElementById('commandesRecentess');
            
            if (commandes.length === 0) {
                return; // Garder les données par défaut
            }

            let html = '<table class="data-table"><thead><tr><th>Référence</th><th>Client</th><th>Montant</th><th>Statut</th></tr></thead><tbody>';
            
            commandes.forEach(commande => {
                let statusClass = 'badge-success';
                if (commande.statut === 'En attente') statusClass = 'badge-warning';
                if (commande.statut === 'Annulée') statusClass = 'badge-danger';
                
                html += `
                    <tr>
                        <td>#${commande.id || 'N/A'}</td>
                        <td>${commande.client || 'N/A'}</td>
                        <td>${commande.montant || '0'} €</td>
                        <td><span class="badge ${statusClass}">${commande.statut || 'N/A'}</span></td>
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
                return; // Garder les données par défaut
            }

            let html = '';
            products.forEach(product => {
                html += `
                    <div class="alert-item">
                        <div class="alert-icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="alert-content">
                            <h4>${product.nom}</h4>
                            <p>Stock critique: ${product.stock} unités restantes</p>
                        </div>
                    </div>
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
                
                if (inscriptions.success && inscriptions.data.length > 0) {
                    const monthlyData = groupInscriptionsByMonth(inscriptions.data, sixMonthsAgo);
                    createChart(monthlyData);
                } else {
                    // Utiliser les données par défaut
                    createChart(defaultChartData);
                }
            } catch (error) {
                console.error('Erreur chargement graphique:', error);
                // Utiliser les données par défaut en cas d'erreur
                createChart(defaultChartData);
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

        // Créer le graphique avec données par défaut ou réelles
        function createChart(chartData) {
            const ctx = document.getElementById('inscriptionsChart').getContext('2d');
            
            // Détruire le graphique existant s'il y en a un
            if (window.inscriptionsChart) {
                window.inscriptionsChart.destroy();
            }
            
            window.inscriptionsChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: chartData.labels,
                    datasets: [{
                        label: 'Inscriptions par mois',
                        data: chartData.data,
                        backgroundColor: 'rgba(4, 34, 26, 0.1)',
                        borderColor: 'rgba(4, 34, 26, 1)',
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#ffae2b',
                        pointBorderColor: '#04221a',
                        pointBorderWidth: 2,
                        pointRadius: 6,
                        pointHoverRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                font: {
                                    size: 14,
                                    weight: '600'
                                },
                                color: '#04221a'
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                font: {
                                    size: 12
                                },
                                color: '#64748b'
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            }
                        },
                        x: {
                            ticks: {
                                font: {
                                    size: 12
                                },
                                color: '#64748b'
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            }
                        }
                    }
                }
            });
        }

        // Fonction d'animation des nombres
        function animateNumber(element, start, end, duration) {
            const startTime = performance.now();
            const update = (currentTime) => {
                const elapsed = currentTime - startTime;
                const progress = Math.min(elapsed / duration, 1);
                const current = Math.floor(start + (end - start) * progress);
                element.textContent = current;
                
                if (progress < 1) {
                    requestAnimationFrame(update);
                }
            };
            requestAnimationFrame(update);
        }

        // Animer les statistiques au chargement
        function animateStats() {
            const stats = [
                { id: 'totalEtudiants', value: 247 },
                { id: 'totalFormations', value: 18 },
                { id: 'totalCommandes', value: 89 },
                { id: 'inscriptionsMois', value: 34 }
            ];
            
            stats.forEach((stat, index) => {
                setTimeout(() => {
                    const element = document.getElementById(stat.id);
                    if (element && element.textContent === stat.value.toString()) {
                        animateNumber(element, 0, stat.value, 1500);
                    }
                }, index * 200);
            });
        }

        // Afficher une notification
        function showNotification(message, type = 'info') {
            const container = document.createElement('div');
            const bgColor = type === 'error' ? '#fee2e2' : type === 'success' ? '#dcfce7' : '#e0f2fe';
            const textColor = type === 'error' ? '#dc2626' : type === 'success' ? '#16a34a' : '#0369a1';
            const icon = type === 'error' ? 'exclamation-circle' : type === 'success' ? 'check-circle' : 'info-circle';
            
            container.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: ${bgColor};
                color: ${textColor};
                padding: 12px 20px;
                border-radius: 8px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                z-index: 10000;
                display: flex;
                align-items: center;
                gap: 10px;
                max-width: 300px;
                border-left: 4px solid ${textColor};
            `;
            
            container.innerHTML = `
                <i class="fas fa-${icon}"></i>
                <span>${message}</span>
            `;
            
            document.body.appendChild(container);
            
            setTimeout(() => {
                container.style.opacity = '0';
                container.style.transform = 'translateX(100%)';
                container.style.transition = 'all 0.3s ease';
                setTimeout(() => container.remove(), 300);
            }, 4000);
        }

        // Charger le dashboard au chargement de la page
        document.addEventListener('DOMContentLoaded', function() {
            // Créer d'abord le graphique par défaut
            createChart(defaultChartData);
            
            // Animer les statistiques
            setTimeout(() => {
                animateStats();
            }, 500);
            
            // Charger les vraies données (qui remplaceront les données par défaut si disponibles)
            loadDashboardStats();
            
            // Actualiser les données toutes les 5 minutes
            setInterval(loadDashboardStats, 300000);
            
            // Gestion du bouton d'actualisation
            document.querySelector('.header-action-btn:nth-child(1)').addEventListener('click', function() {
                const originalContent = this.innerHTML;
                this.innerHTML = '<div class="loader"></div><span>Actualisation...</span>';
                
                loadDashboardStats().then(() => {
                    showNotification('Données mises à jour avec succès', 'success');
                }).catch(() => {
                    showNotification('Erreur lors de la mise à jour', 'error');
                }).finally(() => {
                    setTimeout(() => {
                        this.innerHTML = originalContent;
                    }, 1000);
                });
            });
            
            // Gestion du bouton d'export
            document.querySelector('.header-action-btn:nth-child(2)').addEventListener('click', function() {
                showNotification('Fonctionnalité d\'export en cours de développement', 'info');
            });
            
            // Gestion du bouton de notifications
            document.querySelector('.header-action-btn:nth-child(3)').addEventListener('click', function() {
                showNotification('Aucune nouvelle notification', 'info');
            });
            
            // Gestion du menu mobile (si nécessaire)
            const sidebar = document.querySelector('.sidebar');
            
            // Effet de survol pour les cartes de stats
            document.querySelectorAll('.stat-card').forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-5px) scale(1.02)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0) scale(1)';
                });
            });
        });

        // Gestion responsive du menu
        function toggleMobileMenu() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('mobile-open');
        }
        
        // Ajout d'un bouton menu pour mobile (si nécessaire)
        if (window.innerWidth <= 768) {
            const mobileMenuBtn = document.createElement('button');
            mobileMenuBtn.innerHTML = '<i class="fas fa-bars"></i>';
            mobileMenuBtn.style.cssText = `
                position: fixed;
                top: 15px;
                left: 15px;
                z-index: 10001;
                background: #04221a;
                color: white;
                border: none;
                padding: 10px;
                border-radius: 5px;
                font-size: 18px;
                cursor: pointer;
                display: none;
            `;
            
            if (window.innerWidth <= 768) {
                mobileMenuBtn.style.display = 'block';
            }
            
            mobileMenuBtn.addEventListener('click', toggleMobileMenu);
            document.body.appendChild(mobileMenuBtn);
        }
    </script>
</body>
</html>