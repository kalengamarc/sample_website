<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Formateurs - JosNet</title>
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
    
    /* Tableau des formateurs */
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
    
    /* Image de profil */
    .profile-img {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #e5e7eb;
        transition: all 0.3s ease;
    }
    
    .data-table tr:hover .profile-img {
        border-color: #ffae2b;
        transform: scale(1.05);
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
        text-decoration: none;
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
    
    /* Modal de détail du formateur */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        z-index: 1000;
        align-items: center;
        justify-content: center;
        padding: 20px;
        backdrop-filter: blur(5px);
    }
    
    .modal-content {
        background: white;
        border-radius: 16px;
        width: 100%;
        max-width: 800px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        animation: modalFadeIn 0.4s ease;
    }
    
    @keyframes modalFadeIn {
        from {
            opacity: 0;
            transform: translateY(-50px) scale(0.9);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }
    
    .modal-header {
        background: linear-gradient(135deg, #04221a 0%, #2c5f2d 100%);
        color: white;
        padding: 25px 30px;
        border-radius: 16px 16px 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .modal-header h2 {
        font-size: 24px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 15px;
    }
    
    .modal-header h2 i {
        color: #ffae2b;
    }
    
    .modal-close {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        color: white;
        font-size: 20px;
    }
    
    .modal-close:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: rotate(90deg);
    }
    
    .modal-body {
        padding: 30px;
    }
    
    /* Carte de profil */
    .profile-card {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: 30px;
        margin-bottom: 30px;
    }
    
    @media (max-width: 768px) {
        .profile-card {
            grid-template-columns: 1fr;
            text-align: center;
        }
    }
    
    .profile-image {
        text-align: center;
    }
    
    .profile-img-large {
        width: 180px;
        height: 180px;
        border-radius: 50%;
        object-fit: cover;
        border: 5px solid #e5e7eb;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }
    
    .profile-img-large:hover {
        transform: scale(1.02);
        border-color: #ffae2b;
    }
    
    .profile-info {
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    
    .profile-name {
        font-size: 28px;
        font-weight: 700;
        color: #04221a;
        margin-bottom: 10px;
    }
    
    .profile-role {
        font-size: 18px;
        color: #6b7280;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .profile-role-badge {
        background: linear-gradient(135deg, #04221a 0%, #2c5f2d 100%);
        color: white;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 600;
    }
    
    /* Détails du formateur */
    .profile-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
    
    .detail-card {
        background: #f8fafc;
        padding: 20px;
        border-radius: 12px;
        border-left: 4px solid #ffae2b;
    }
    
    .detail-card h3 {
        font-size: 16px;
        color: #6b7280;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .detail-card h3 i {
        color: #ffae2b;
    }
    
    .detail-card p {
        font-size: 18px;
        color: #04221a;
        font-weight: 600;
    }
    
    /* Statistiques */
    .profile-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
    
    .stat-card {
        background: white;
        padding: 20px;
        border-radius: 12px;
        text-align: center;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.04);
        border: 1px solid #e5e7eb;
        transition: all 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }
    
    .stat-number {
        font-size: 32px;
        font-weight: 700;
        color: #04221a;
        margin-bottom: 5px;
    }
    
    .stat-label {
        font-size: 14px;
        color: #6b7280;
    }
    
    /* Actions modal */
    .modal-actions {
        display: flex;
        gap: 15px;
        justify-content: flex-end;
        padding: 20px 30px;
        border-top: 1px solid #e5e7eb;
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
        
        .data-table th, 
        .data-table td {
            padding: 12px 15px;
        }
        
        .action-buttons {
            flex-wrap: wrap;
        }
        
        .modal {
            padding: 10px;
        }
        
        .modal-content {
            max-height: 95vh;
        }
        
        .modal-actions {
            flex-direction: column;
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
           
            <div class="menu">
                <?php include_once('menu.php');?>
            </div>
                <!-- Autres éléments du menu -->
        </div>

        <!-- Contenu principal -->
        <div class="main-content">
            <div class="page-header">
                <h1><i class="fas fa-chalkboard-teacher"></i> Liste des Formateurs</h1>
                <div class="header-actions">
                    <a href="AjoutFormateur.php" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Nouveau Formateur
                    </a>
                </div>
            </div>

            <!-- Tableau des formateurs -->
            <div class="table-container animate-in">
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Photo</th>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Téléphone</th>
                                <th>Spécialité</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            include_once('../controle/controleur_utilisateur.php');
                            $utilisateurCtrl = new UtilisateurController();
                            $usersData = $utilisateurCtrl->getUtilisateursByRole('formateur');
                            ?>
                            
                            <?php if($usersData['success'] && count($usersData['data']) > 0): ?>
                                <?php foreach($usersData['data'] as $index => $user): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td>
                                            <img src="<?= !empty($user->getPhoto()) ? '../controle/'.$user->getPhoto() : 'https://via.placeholder.com/150?text=No+Image' ?>" 
                                                 alt="Profil" 
                                                 class="profile-img"
                                                 onerror="this.src='https://via.placeholder.com/150?text=No+Image'">
                                        </td>
                                        <td><?= htmlspecialchars($user->getNom() . ' ' . $user->getPrenom()) ?></td>
                                        <td><?= htmlspecialchars($user->getEmail()) ?></td>
                                        <td><?= htmlspecialchars($user->getTelephone()) ?></td>
                                        <td><?= htmlspecialchars($user->getSpecialite()) ?></td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="#" class="action-btn action-view" 
                                                   onclick="showFormateurDetails(<?= htmlspecialchars(json_encode([
                                                       'id' => $user->getId(),
                                                       'nom' => $user->getNom(),
                                                       'prenom' => $user->getPrenom(),
                                                       'email' => $user->getEmail(),
                                                       'telephone' => $user->getTelephone(),
                                                       'specialite' => $user->getSpecialite(),
                                                       'photo' => !empty($user->getPhoto()) ? '../controle/'.$user->getPhoto() : 'https://via.placeholder.com/150?text=No+Image',
                                                       'role' => $user->getRole(),
                                                       'date_creation' => $user->getDateCreation()
                                                   ]), ENT_QUOTES, 'UTF-8') ?>)" 
                                                   title="Voir détails">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="AjoutFormateur.php?resp=<?= $user->getId() ?>" class="action-btn action-edit" title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="../controle/index.php?do=formateur_delete&id=<?= $user->getId() ?>" 
                                                   onclick="return confirm('Voulez-vous vraiment supprimer ce formateur ?')" 
                                                   class="action-btn action-delete" 
                                                   title="Supprimer">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" style="text-align: center; padding: 30px; color: #6b7280;">
                                        <i class="fas fa-user-slash" style="font-size: 48px; margin-bottom: 15px; display: block;"></i>
                                        Aucun formateur trouvé
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de détail du formateur -->
    <div class="modal" id="formateurModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="fas fa-chalkboard-teacher"></i> Détails du Formateur</h2>
                <button class="modal-close" onclick="closeModal()">&times;</button>
            </div>
            
            <div class="modal-body">
                <div class="profile-card">
                    <div class="profile-image">
                        <img src="" alt="Photo de profil" class="profile-img-large" id="modalPhoto">
                    </div>
                    
                    <div class="profile-info">
                        <h1 class="profile-name" id="modalName"></h1>
                        <div class="profile-role">
                            <span class="profile-role-badge" id="modalRole"></span>
                            <span id="modalSpecialite"></span>
                        </div>
                    </div>
                </div>
                
                <div class="profile-details">
                    <div class="detail-card">
                        <h3><i class="fas fa-envelope"></i> Email</h3>
                        <p id="modalEmail"></p>
                    </div>
                    
                    <div class="detail-card">
                        <h3><i class="fas fa-phone"></i> Téléphone</h3>
                        <p id="modalTelephone"></p>
                    </div>
                    
                    <div class="detail-card">
                        <h3><i class="fas fa-calendar-alt"></i> Membre depuis</h3>
                        <p id="modalDateCreation"></p>
                    </div>
                    
                    <div class="detail-card">
                        <h3><i class="fas fa-id-card"></i> ID</h3>
                        <p id="modalId"></p>
                    </div>
                </div>
                
                <div class="profile-stats">
                    <div class="stat-card">
                        <div class="stat-number">12</div>
                        <div class="stat-label">Formations</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-number">245</div>
                        <div class="stat-label">Étudiants</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-number">4.8</div>
                        <div class="stat-label">Note moyenne</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-number">98%</div>
                        <div class="stat-label">Satisfaction</div>
                    </div>
                </div>
            </div>
            
            <div class="modal-actions">
                <button class="btn btn-primary" onclick="editFormateur()">
                    <i class="fas fa-edit"></i> Modifier
                </button>
                <button class="btn" onclick="closeModal()" style="background: #f3f4f6; color: #374151;">
                    <i class="fas fa-times"></i> Fermer
                </button>
            </div>
        </div>
    </div>

    <script>
        // Fonction pour afficher les détails du formateur
        function showFormateurDetails(formateur) {
            // Remplir les informations du modal
            document.getElementById('modalPhoto').src = formateur.photo;
            document.getElementById('modalPhoto').alt = `Photo de ${formateur.prenom} ${formateur.nom}`;
            document.getElementById('modalName').textContent = `${formateur.prenom} ${formateur.nom}`;
            document.getElementById('modalRole').textContent = formateur.role;
            document.getElementById('modalSpecialite').textContent = formateur.specialite;
            document.getElementById('modalEmail').textContent = formateur.email;
            document.getElementById('modalTelephone').textContent = formateur.telephone || 'Non renseigné';
            document.getElementById('modalDateCreation').textContent = formateur.date_creation ? new Date(formateur.date_creation).toLocaleDateString('fr-FR') : 'Date inconnue';
            document.getElementById('modalId').textContent = `#${formateur.id}`;
            
            // Afficher le modal
            document.getElementById('formateurModal').style.display = 'flex';
            
            // Stocker l'ID du formateur pour l'édition
            document.getElementById('formateurModal').dataset.formateurId = formateur.id;
        }
        
        // Fonction pour fermer le modal
        function closeModal() {
            document.getElementById('formateurModal').style.display = 'none';
        }
        
        // Fonction pour éditer le formateur
        function editFormateur() {
            const formateurId = document.getElementById('formateurModal').dataset.formateurId;
            if (formateurId) {
                window.location.href = `modifier.php?id=${formateurId}`;
            }
        }
        
        // Fermer le modal en cliquant à l'extérieur
        window.addEventListener('click', function(event) {
            const modal = document.getElementById('formateurModal');
            if (event.target === modal) {
                closeModal();
            }
        });
        
        // Fermer le modal avec la touche Échap
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeModal();
            }
        });
        
        // Gestion des erreurs d'image
        document.addEventListener('DOMContentLoaded', function() {
            const images = document.querySelectorAll('img');
            images.forEach(img => {
                img.onerror = function() {
                    this.src = 'https://via.placeholder.com/150?text=No+Image';
                };
            });
        });
    </script>
</body>
</html>