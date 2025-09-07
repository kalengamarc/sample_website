<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Actualités - JosNet</title>
    <link rel="stylesheet" href="../vue/font-awesome/css/all.min.css">
    <link rel="stylesheet" href="../vue/styles/stylemenu.css">
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
    
    /* Contenu principal */
    .main-content {
        flex: 1;
        margin-left: 280px;
        padding: 30px;
        background-color: #f1f5f9;
        min-height: 100vh;
    }
    
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .page-header h1 {
        font-size: 28px;
        color: #04221a;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 15px;
    }
    
    .page-header h1 i {
        color: #ffae2b;
    }
    
    .header-actions {
        display: flex;
        gap: 15px;
    }
    
    .btn {
        padding: 12px 20px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        border: none;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #04221a 0%, #2c5f2d 100%);
        color: white;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(4, 34, 26, 0.3);
    }
    
    .btn-secondary {
        background: white;
        border: 1px solid #e2e8f0;
        color: #64748b;
    }
    
    .btn-secondary:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
    }
    
    /* Filtres et recherche */
    .filters-container {
        background: white;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 25px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.04);
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        align-items: center;
    }
    
    .search-box {
        position: relative;
        flex: 1;
        min-width: 250px;
    }
    
    .search-box input {
        width: 100%;
        padding: 12px 15px 12px 45px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.3s ease;
    }
    
    .search-box input:focus {
        outline: none;
        border-color: #04221a;
        box-shadow: 0 0 0 3px rgba(4, 34, 26, 0.1);
    }
    
    .search-box i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
    }
    
    .filter-select {
        padding: 12px 15px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 14px;
        background: white;
        min-width: 150px;
    }
    
    /* Tableau des actualités */
    .table-container {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.04);
        margin-bottom: 30px;
    }
    
    .table-responsive {
        overflow-x: auto;
    }
    
    .data-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .data-table th {
        background: #f8fafc;
        padding: 16px 20px;
        text-align: left;
        font-weight: 600;
        color: #374151;
        border-bottom: 1px solid #e5e7eb;
        font-size: 14px;
    }
    
    .data-table td {
        padding: 16px 20px;
        border-bottom: 1px solid #e5e7eb;
        font-size: 14px;
        color: #4b5563;
    }
    
    .data-table tr:last-child td {
        border-bottom: none;
    }
    
    .data-table tr:hover {
        background-color: #f9fafb;
    }
    
    /* Badges de statut */
    .badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }
    
    .badge-published {
        background: #dcfce7;
        color: #16a34a;
    }
    
    .badge-draft {
        background: #fef3c7;
        color: #d97706;
    }
    
    .badge-archived {
        background: #fee2e2;
        color: #dc2626;
    }
    
    /* Actions */
    .action-buttons {
        display: flex;
        gap: 10px;
    }
    
    .action-btn {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        color: #6b7280;
        background: transparent;
        border: 1px solid #e5e7eb;
    }
    
    .action-btn:hover {
        transform: translateY(-2px);
    }
    
    .action-view:hover {
        background: #eff6ff;
        color: #3b82f6;
        border-color: #bfdbfe;
    }
    
    .action-edit:hover {
        background: #f0fdf4;
        color: #22c55e;
        border-color: #bbf7d0;
    }
    
    .action-delete:hover {
        background: #fef2f2;
        color: #ef4444;
        border-color: #fecaca;
    }
    
    /* Pagination */
    .pagination {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.04);
    }
    
    .pagination-info {
        color: #6b7280;
        font-size: 14px;
    }
    
    .pagination-controls {
        display: flex;
        gap: 8px;
    }
    
    .pagination-btn {
        padding: 8px 12px;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        background: white;
        color: #4b5563;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 14px;
    }
    
    .pagination-btn:hover {
        background: #f9fafb;
        border-color: #d1d5db;
    }
    
    .pagination-btn.active {
        background: #04221a;
        color: white;
        border-color: #04221a;
    }
    
    /* Modal de confirmation */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        align-items: center;
        justify-content: center;
    }
    
    .modal-content {
        background: white;
        border-radius: 12px;
        padding: 30px;
        width: 90%;
        max-width: 500px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }
    
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    
    .modal-header h3 {
        font-size: 20px;
        color: #04221a;
    }
    
    .modal-close {
        background: none;
        border: none;
        font-size: 20px;
        cursor: pointer;
        color: #6b7280;
    }
    
    .modal-body {
        margin-bottom: 25px;
    }
    
    .modal-footer {
        display: flex;
        justify-content: flex-end;
        gap: 15px;
    }
    
    .btn-cancel {
        background: #f3f4f6;
        color: #374151;
    }
    
    .btn-cancel:hover {
        background: #e5e7eb;
    }
    
    .btn-danger {
        background: #dc2626;
        color: white;
    }
    
    .btn-danger:hover {
        background: #b91c1c;
    }
    
    /* Responsive design */
    @media (max-width: 1024px) {
        .sidebar {
            width: 80px;
            overflow: visible;
        }
        
        .sidebar-header h2, 
        .sidebar-header p, 
        .menu-item label span:not(.arrow), 
        .submenu,
        .sidebar-footer {
            display: none;
        }
        
        .menu-item label {
            justify-content: center;
            padding: 15px 10px;
        }
        
        .menu-item label i {
            margin-right: 0;
            font-size: 18px;
        }
        
        .sidebar:hover {
            width: 280px;
            z-index: 1001;
        }
        
        .sidebar:hover .sidebar-header h2, 
        .sidebar:hover .sidebar-header p, 
        .sidebar:hover .menu-item label span:not(.arrow), 
        .sidebar:hover .submenu,
        .sidebar:hover .sidebar-footer {
            display: block;
        }
        
        .sidebar:hover .menu-item label {
            justify-content: flex-start;
            padding: 12px 15px;
        }
        
        .sidebar:hover .menu-item label i {
            margin-right: 12px;
            font-size: 16px;
        }
        
        .main-content {
            margin-left: 80px;
        }
        
        .sidebar:hover ~ .main-content {
            margin-left: 280px;
        }
    }
    
    @media (max-width: 768px) {
        .main-content {
            margin-left: 0;
            padding: 20px 15px;
        }
        
        .sidebar {
            transform: translateX(-100%);
            transition: transform 0.3s ease;
        }
        
        .sidebar.mobile-open {
            transform: translateX(0);
        }
        
        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }
        
        .header-actions {
            width: 100%;
            justify-content: space-between;
        }
        
        .filters-container {
            flex-direction: column;
            align-items: stretch;
        }
        
        .search-box {
            min-width: 100%;
        }
        
        .data-table th, 
        .data-table td {
            padding: 12px 15px;
        }
        
        .action-buttons {
            flex-wrap: wrap;
        }
        
        .pagination {
            flex-direction: column;
            gap: 15px;
            align-items: stretch;
        }
        
        .pagination-controls {
            justify-content: center;
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
</style>
<body>
    <div class="dashboard-container">
        <!-- Sidebar / Menu -->
         
         <div class="sidebar">
                <?php include_once('menu.php');?>      
        </div>

        <!-- Contenu principal -->
        <div class="main-content">
            <div class="page-header">
                <h1><i class="fas fa-newspaper"></i> Gestion des Actualités</h1>
                <div class="header-actions">
                    <button class="btn btn-secondary">
                        <i class="fas fa-download"></i> Exporter
                    </button>
                    <a href="ajouter_actualite.php" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Nouvelle actualité
                    </a>
                </div>
            </div>

            <!-- Filtres et recherche -->
            <div class="filters-container animate-in">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Rechercher une actualité..." id="searchInput">
                </div>
                
                <select class="filter-select" id="statusFilter">
                    <option value="">Tous les statuts</option>
                    <option value="published">Publié</option>
                    <option value="draft">Brouillon</option>
                    <option value="archived">Archivé</option>
                </select>
                
                <select class="filter-select" id="authorFilter">
                    <option value="">Tous les auteurs</option>
                    <option value="admin">Admin</option>
                    <option value="moderator">Modérateur</option>
                </select>
                
                <select class="filter-select" id="dateFilter">
                    <option value="">Toutes les dates</option>
                    <option value="today">Aujourd'hui</option>
                    <option value="week">Cette semaine</option>
                    <option value="month">Ce mois</option>
                </select>
            </div>

            <!-- Tableau des actualités -->
            <div class="table-container animate-in" style="animation-delay: 0.2s">
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Titre</th>
                                <th>Date</th>
                                <th>Auteur</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Mise à jour du système</td>
                                <td>12/02/2024</td>
                                <td>Admin</td>
                                <td><span class="badge badge-published">Publiée</span></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="action-btn action-view" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="action-btn action-edit" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="action-btn action-delete" title="Supprimer" onclick="openDeleteModal(1)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Nouveaux cours disponibles</td>
                                <td>12/03/2024</td>
                                <td>Admin</td>
                                <td><span class="badge badge-published">Publiée</span></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="action-btn action-view" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="action-btn action-edit" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="action-btn action-delete" title="Supprimer" onclick="openDeleteModal(2)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Maintenance programmée</td>
                                <td>18/04/2024</td>
                                <td>Admin</td>
                                <td><span class="badge badge-draft">Brouillon</span></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="action-btn action-view" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="action-btn action-edit" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="action-btn action-delete" title="Supprimer" onclick="openDeleteModal(3)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Événement annuel 2024</td>
                                <td>05/01/2024</td>
                                <td>Modérateur</td>
                                <td><span class="badge badge-archived">Archivée</span></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="action-btn action-view" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="action-btn action-edit" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="action-btn action-delete" title="Supprimer" onclick="openDeleteModal(4)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div class="pagination animate-in" style="animation-delay: 0.3s">
                <div class="pagination-info">
                    Affichage de 1 à 4 sur 12 résultats
                </div>
                <div class="pagination-controls">
                    <button class="pagination-btn">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="pagination-btn active">1</button>
                    <button class="pagination-btn">2</button>
                    <button class="pagination-btn">3</button>
                    <button class="pagination-btn">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmation de suppression -->
    <div class="modal" id="deleteModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Confirmer la suppression</h3>
                <button class="modal-close" onclick="closeModal()">&times;</button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer cette actualité ? Cette action est irréversible.</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-cancel" onclick="closeModal()">Annuler</button>
                <button class="btn btn-danger" onclick="confirmDelete()">Supprimer</button>
            </div>
        </div>
    </div>

    <script>
        // Fonctions pour la modal de suppression
        let currentDeleteId = null;
        
        function openDeleteModal(id) {
            currentDeleteId = id;
            document.getElementById('deleteModal').style.display = 'flex';
        }
        
        function closeModal() {
            document.getElementById('deleteModal').style.display = 'none';
            currentDeleteId = null;
        }
        
        function confirmDelete() {
            if (currentDeleteId) {
                // Ici, vous ajouteriez le code pour supprimer l'actualité
                console.log('Suppression de l\'actualité avec ID:', currentDeleteId);
                alert('Actualité supprimée avec succès !');
                closeModal();
                
                // Recharger les données ou actualiser l'interface
                // loadActualites();
            }
        }
        
        // Fermer la modal en cliquant à l'extérieur
        window.addEventListener('click', function(event) {
            const modal = document.getElementById('deleteModal');
            if (event.target === modal) {
                closeModal();
            }
        });
        
        // Recherche et filtrage
        document.getElementById('searchInput').addEventListener('input', function() {
            filterActualites();
        });
        
        document.getElementById('statusFilter').addEventListener('change', function() {
            filterActualites();
        });
        
        document.getElementById('authorFilter').addEventListener('change', function() {
            filterActualites();
        });
        
        document.getElementById('dateFilter').addEventListener('change', function() {
            filterActualites();
        });
        
        function filterActualites() {
            const searchText = document.getElementById('searchInput').value.toLowerCase();
            const statusFilter = document.getElementById('statusFilter').value;
            const authorFilter = document.getElementById('authorFilter').value;
            const dateFilter = document.getElementById('dateFilter').value;
            
            const rows = document.querySelectorAll('.data-table tbody tr');
            
            rows.forEach(row => {
                const title = row.cells[0].textContent.toLowerCase();
                const date = row.cells[1].textContent;
                const author = row.cells[2].textContent.toLowerCase();
                const status = row.cells[3].querySelector('.badge').textContent.toLowerCase();
                
                const matchesSearch = title.includes(searchText);
                const matchesStatus = !statusFilter || status.includes(statusFilter);
                const matchesAuthor = !authorFilter || author.includes(authorFilter);
                const matchesDate = !dateFilter || checkDateFilter(date, dateFilter);
                
                if (matchesSearch && matchesStatus && matchesAuthor && matchesDate) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
        
        function checkDateFilter(date, filter) {
            // Implémentation basique de filtrage par date
            // Vous devriez adapter cette fonction selon vos besoins
            const today = new Date();
            const rowDate = new Date(date.split('/').reverse().join('-'));
            
            switch(filter) {
                case 'today':
                    return rowDate.toDateString() === today.toDateString();
                case 'week':
                    const weekStart = new Date(today);
                    weekStart.setDate(today.getDate() - today.getDay());
                    return rowDate >= weekStart;
                case 'month':
                    return rowDate.getMonth() === today.getMonth() && 
                           rowDate.getFullYear() === today.getFullYear();
                default:
                    return true;
            }
        }
        
        // Chargement initial
        document.addEventListener('DOMContentLoaded', function() {
            // Initialiser les tooltips
            const tooltips = document.querySelectorAll('[title]');
            tooltips.forEach(el => {
                el.addEventListener('mouseenter', function(e) {
                    // Vous pourriez implémenter un système de tooltips personnalisés ici
                });
            });
        });
    </script>
</body>
</html>