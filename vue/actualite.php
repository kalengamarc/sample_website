<!doctype html>
<html lang="fr">
<head>
    <?php
    session_start();
    require_once 'session_client.php';
    include_once('../controle/controleur_formation.php');
    include_once('../controle/controleur_utilisateur.php');
    $utilisateur = new UtilisateurController();
    $formations = new FormationController();
    $listeFormation = $formations->getAllFormations();
    ?>
  
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualités - JosNet</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: #333;
        }

        /* Header Styles */
        .wrap {
            max-width: 1100px;
            margin: 20px auto;
            padding: 20px;
        }

        header {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            margin-top: 100px;
            padding: 20px 30px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo svg {
            width: 50px;
            height: 50px;
        }

        .brand h1 {
            color: #04221a;
            font-size: 2em;
            font-weight: 700;
        }

        .brand p {
            color: #666;
            font-size: 0.9em;
        }

        .nav {
            display: flex;
            gap: 15px;
        }

        .nav .btn {
            background: linear-gradient(135deg, #04221a 0%, #2c5f2d 100%);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 25px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .nav .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(4, 34, 26, 0.3);
        }

        /* User Message Icons */
        .user_message {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 100;
            display: flex;
            gap: 10px;
        }

        .user_message a {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #04221a;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .user_message a:hover {
            transform: translateY(-2px);
            background: yellowgreen;
        }

        /* Title Section */
        .nom_service {
            text-align: center;
            margin-bottom: 40px;
        }

        .nom_service h1 {
            color: white;
            font-size: 3em;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            margin-bottom: 10px;
        }

        /* Services Grid */
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 30px;
            padding: 0;
        }

        .card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            transition: all 0.4s ease;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        }

        .image-container {
            width: 100%;
            height: 250px;
            overflow: hidden;
            position: relative;
        }

        .card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.4s ease;
        }

        .card:hover img {
            transform: scale(1.1);
        }

        .card-content {
            padding: 25px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .card h3 {
            margin: 0 0 15px 0;
            color: #04221a;
            font-size: 1.4em;
            line-height: 1.3;
            font-weight: 600;
        }

        .card p {
            color: #666;
            margin: 0 0 20px 0;
            line-height: 1.6;
            flex-grow: 1;
        }

        /* Service Actions */
        .alignements_icones {
            display: flex;
            justify-content: space-around;
            margin-top: 20px;
            padding: 20px 0;
            border-top: 1px solid #eee;
        }

        .icon {
            font-size: 22px;
            cursor: pointer;
            transition: all 0.3s ease;
            padding: 12px;
            border: none;
            background: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .icon:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }

        .fa-comment { 
            color: #17a2b8; 
            background: rgba(23, 162, 184, 0.1);
        }
        .fa-shopping-cart { 
            color: #0c521cff; 
            background: rgba(40, 167, 69, 0.1);
        }
        .fa-star { 
            color: #ffc107; 
            background: rgba(255, 193, 7, 0.1);
        }
        .fa-share { 
            color: #6f42c1; 
            background: rgba(111, 66, 193, 0.1);
        }

        .icon:hover {
            color: white !important;
        }

        .fa-comment:hover { background: #17a2b8; }
        .fa-shopping-cart:hover { background: #28a745; }
        .fa-star:hover { background: #ffc107; }
        .fa-share:hover { background: #6f42c1; }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.6);
            backdrop-filter: blur(5px);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .modal.show {
            opacity: 1;
        }

        .modal-content {
            background: linear-gradient(145deg, #ffffff, #f8f9fa);
            margin: 5% auto;
            padding: 0;
            border-radius: 20px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.25);
            position: relative;
            transform: translateY(-50px);
            transition: transform 0.3s ease;
            overflow: hidden;
        }

        .modal.show .modal-content {
            transform: translateY(0);
        }

        .modal-header {
            background: linear-gradient(135deg, #04221a 0%, #2c5f2d 100%);
            color: white;
            padding: 25px 30px;
            position: relative;
        }

        .modal-header h3 {
            margin: 0;
            font-size: 1.8em;
            font-weight: 600;
        }

        .close {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: white;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .close:hover {
            background-color: rgba(255,255,255,0.2);
            transform: translateY(-50%) rotate(90deg);
        }

        .modal-body {
            padding: 30px;
        }

        /* Rating System */
        .rating-section {
            margin-bottom: 25px;
        }

        .rating-label {
            display: block;
            margin-bottom: 15px;
            font-weight: 600;
            color: #333;
            font-size: 1.1em;
        }

        .rating {
            display: flex;
            justify-content: center;
            gap: 5px;
            margin-bottom: 10px;
        }

        .rating input {
            display: none;
        }

        .rating label {
            font-size: 32px;
            color: #ddd;
            cursor: pointer;
            transition: all 0.3s ease;
            padding: 5px;
            border-radius: 50%;
        }

        .rating label:hover,
        .rating label:hover ~ label,
        .rating input:checked ~ label {
            color: #ffc107;
            text-shadow: 0 0 10px rgba(255, 193, 7, 0.5);
            transform: scale(1.1);
        }

        .rating-text {
            text-align: center;
            font-size: 14px;
            color: #666;
            font-style: italic;
            min-height: 20px;
        }

        /* Comment Section */
        .comment-section {
            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
            font-size: 1.1em;
        }

        .form-group textarea {
            width: 100%;
            min-height: 120px;
            padding: 15px;
            border: 2px solid #e1e5e9;
            border-radius: 12px;
            font-family: inherit;
            font-size: 14px;
            resize: vertical;
            transition: all 0.3s ease;
            background: #fafbfc;
        }

        .form-group textarea:focus {
            outline: none;
            border-color: #04221a;
            box-shadow: 0 0 15px rgba(4, 34, 26, 0.2);
            background: white;
        }

        .char-counter {
            text-align: right;
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }

        .char-counter.warning {
            color: #ff6b6b;
        }

        /* Modal Actions */
        .modal-actions {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
            margin-top: 30px;
        }

        .btn {
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-cancel {
            background: #6c757d;
            color: white;
        }

        .btn-cancel:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }

        .btn-submit {
            background: linear-gradient(135deg, #04221a 0%, #2c5f2d 100%);
            color: white;
            position: relative;
            overflow: hidden;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(4, 34, 26, 0.3);
        }

        .btn-submit:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
        }

        /* Alert Messages */
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            font-weight: 500;
            animation: slideIn 0.5s ease;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* User Profile Dropdown */
        .user-profile-dropdown {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 100;
        }

        .profile-toggle {
            background: rgba(255, 255, 255, 0.9);
            border: none;
            border-radius: 25px;
            padding: 8px 15px;
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .profile-toggle:hover {
            background: rgba(255, 255, 255, 1);
            transform: translateY(-2px);
        }

        .profile-avatar {
            width: 30px;
            height: 30px;
            background: #04221a;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .profile-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            background: white;
            border-radius: 10px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.15);
            min-width: 250px;
            margin-top: 10px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
        }

        .profile-dropdown.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .profile-dropdown .dropdown-header {
            padding: 15px;
            border-bottom: 1px solid #eee;
        }

        .profile-dropdown .dropdown-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 15px;
            text-decoration: none;
            color: #333;
            transition: background 0.2s ease;
        }

        .profile-dropdown .dropdown-item:hover {
            background: #f8f9fa;
        }

        .profile-dropdown .dropdown-item.logout {
            color: #dc3545;
            border-top: 1px solid #eee;
        }

        /* Footer */
        footer {
            text-align: center;
            margin-top: 50px;
            padding: 30px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            color: #666;
        }

        /* Animations */
        @keyframes slideIn {
            from {
                transform: translateX(-100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .pulse {
            animation: pulse 0.6s ease-in-out;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .wrap {
                padding: 15px;
            }
            
            header {
                flex-direction: column;
                gap: 20px;
                text-align: center;
            }
            
            .nav {
                justify-content: center;
            }
            
            .grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            .modal-content {
                width: 95%;
                margin: 10% auto;
            }
            
            .modal-body {
                padding: 20px;
            }
            
            .rating label {
                font-size: 28px;
            }
            
            .modal-actions {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
            }
            
            .nom_service h1 {
                font-size: 2em;
            }
            
        }
        /* WhatsApp-like popup styles */
        .whatsapp-popup {
            position: fixed;
            top: 80px;
            right: 20px;
            width: 280px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            z-index: 1000;
            display: none;
            overflow: hidden;
        }

        .whatsapp-popup-header {
            background: linear-gradient(135deg, #04221a 0%, #2c5f2d 100%);
            color: white;
            padding: 12px 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .whatsapp-popup-header i {
            font-size: 16px;
        }

        .whatsapp-popup-header h3 {
            font-size: 14px;
            font-weight: 600;
            margin: 0;
        }

        .whatsapp-popup-content {
            padding: 12px;
            max-height: 250px;
            overflow-y: auto;
        }

        .whatsapp-popup-item {
            display: flex;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .whatsapp-popup-item:last-child {
            border-bottom: none;
        }

        .whatsapp-popup-item-icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            font-size: 14px;
            color: #04221a;
        }

        .whatsapp-popup-item-content {
            flex: 1;
        }

        .whatsapp-popup-item-title {
            font-weight: 600;
            color: #04221a;
            margin-bottom: 2px;
            font-size: 13px;
        }

        .whatsapp-popup-item-desc {
            font-size: 12px;
            color: #666;
        }

        .whatsapp-popup-item-time {
            font-size: 11px;
            color: #999;
        }

        .whatsapp-popup-footer {
            padding: 8px 12px;
            text-align: center;
            background: #f9f9f9;
            border-top: 1px solid #eee;
        }

        .whatsapp-popup-footer a {
            color: #04221a;
            text-decoration: none;
            font-weight: 600;
            font-size: 12px;
        }

        /* Badge for notifications */
        .badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: #dc2626;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
        }

        .user_message a {
            position: relative;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .wrap {
                padding: 15px;
            }
            
            header {
                flex-direction: column;
                gap: 20px;
                text-align: center;
            }
            
            .nav {
                justify-content: center;
            }
            
            .grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            .modal-content {
                width: 95%;
                margin: 10% auto;
            }
            
            .modal-body {
                padding: 20px;
            }
            
            .rating label {
                font-size: 28px;
            }
            
            .modal-actions {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
            }
            
            .nom_service h1 {
                font-size: 2em;
            }

            .whatsapp-popup {
                width: 280px;
                right: 10px;
            }
        }
    </style>
</head>

<body>
    <!-- User Profile Dropdown -->
    <div class="user-profile-dropdown">
        <?php if (isUserLoggedIn()): ?>
            <?php $user = getCurrentClientUser(); ?>
            <button class="profile-toggle" onclick="toggleProfileDropdown()">
                <div class="profile-avatar">
                    <?= strtoupper(substr($user['prenom'], 0, 1)) ?>
                </div>
                <span><?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?></span>
                <i class="fas fa-chevron-down"></i>
            </button>
            <div class="profile-dropdown" id="profileDropdown">
                <div class="dropdown-header">
                    <strong><?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?></strong>
                    <small><?= htmlspecialchars(ucfirst($user['role'])) ?></small>
                </div>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-user"></i>
                    Mon Profil
                </a>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-shopping-bag"></i>
                    Mes Commandes
                </a>
                <?php if ($user['role'] === 'admin'): ?>
                    <a href="../Admin/dashboard.php" class="dropdown-item">
                        <i class="fas fa-cog"></i>
                        Administration
                    </a>
                <?php endif; ?>
                <a href="logout_client.php" class="dropdown-item logout" onclick="return confirm('Êtes-vous sûr de vouloir vous déconnecter ?')">
                    <i class="fas fa-sign-out-alt"></i>
                    Se déconnecter
                </a>
            </div>
        <?php else: ?>
            <a href="connexion.php" class="profile-toggle">
                <i class="fas fa-sign-in-alt"></i>
                Se connecter
            </a>
        <?php endif; ?>
    </div>

<!-- User Message Icons -->
    <div class="user_message">
        <a href="#" title="Panier" onclick="togglePopup('cartPopup'); return false;">
            <i class="icon fas fa-shopping-cart"></i>
            <span class="badge">3</span>
        </a>
        <a href="#" title="Notifications" onclick="togglePopup('notificationPopup'); return false;">
            <i class="icon fas fa-bell"></i>
            <span class="badge">5</span>
        </a>
        <a href="#" title="Profil" onclick="togglePopup('profilePopup'); return false;">
            <i class="icon fas fa-user"></i>
        </a>
    </div>

    <!-- WhatsApp-style Popups -->
    <div class="whatsapp-popup" id="cartPopup">
        <div class="whatsapp-popup-header">
            <i class="fas fa-shopping-cart"></i>
            <h3>Votre Panier</h3>
        </div>
        <div class="whatsapp-popup-content">
            <div class="whatsapp-popup-item">
                <div class="whatsapp-popup-item-icon">
                    <i class="fas fa-mobile-alt"></i>
                </div>
                <div class="whatsapp-popup-item-content">
                    <div class="whatsapp-popup-item-title">iPhone 13 Pro</div>
                    <div class="whatsapp-popup-item-desc">Quantité: 1</div>
                </div>
                <div class="whatsapp-popup-item-time">999€</div>
            </div>
            <div class="whatsapp-popup-item">
                <div class="whatsapp-popup-item-icon">
                    <i class="fas fa-headphones"></i>
                </div>
                <div class="whatsapp-popup-item-content">
                    <div class="whatsapp-popup-item-title">Écouteurs Bluetooth</div>
                    <div class="whatsapp-popup-item-desc">Quantité: 2</div>
                </div>
                <div class="whatsapp-popup-item-time">79€</div>
            </div>
            <div class="whatsapp-popup-item">
                <div class="whatsapp-popup-item-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div class="whatsapp-popup-item-content">
                    <div class="whatsapp-popup-item-title">Protection Écran</div>
                    <div class="whatsapp-popup-item-desc">Quantité: 1</div>
                </div>
                <div class="whatsapp-popup-item-time">19€</div>
            </div>
        </div>
        <div class="whatsapp-popup-footer">
            <a href="#">Total: 1176€ | Voir le panier complet</a>
        </div>
    </div>

    <div class="whatsapp-popup" id="notificationPopup">
        <div class="whatsapp-popup-header">
            <i class="fas fa-bell"></i>
            <h3>Notifications</h3>
        </div>
        <div class="whatsapp-popup-content">
            <div class="whatsapp-popup-item">
                <div class="whatsapp-popup-item-icon" style="background-color: #e6f7ff;">
                    <i class="fas fa-shipping-fast" style="color: #1890ff;"></i>
                </div>
                <div class="whatsapp-popup-item-content">
                    <div class="whatsapp-popup-item-title">Commande Expédiée</div>
                    <div class="whatsapp-popup-item-desc">Votre commande #12345 a été expédiée</div>
                </div>
                <div class="whatsapp-popup-item-time">10:30</div>
            </div>
            <div class="whatsapp-popup-item">
                <div class="whatsapp-popup-item-icon" style="background-color: #f6ffed;">
                    <i class="fas fa-check-circle" style="color: #52c41a;"></i>
                </div>
                <div class="whatsapp-popup-item-content">
                    <div class="whatsapp-popup-item-title">Paiement Confirmé</div>
                    <div class="whatsapp-popup-item-desc">Votre paiement a été accepté</div>
                </div>
                <div class="whatsapp-popup-item-time">Hier</div>
            </div>
            <div class="whatsapp-popup-item">
                <div class="whatsapp-popup-item-icon" style="background-color: #fff7e6;">
                    <i class="fas fa-gift" style="color: #fa8c16;"></i>
                </div>
                <div class="whatsapp-popup-item-content">
                    <div class="whatsapp-popup-item-title">Offre Spéciale</div>
                    <div class="whatsapp-popup-item-desc">-20% sur tous les accessoires</div>
                </div>
                <div class="whatsapp-popup-item-time">Hier</div>
            </div>
            <div class="whatsapp-popup-item">
                <div class="whatsapp-popup-item-icon" style="background-color: #f9f0ff;">
                    <i class="fas fa-users" style="color: #722ed1;"></i>
                </div>
                <div class="whatsapp-popup-item-content">
                    <div class="whatsapp-popup-item-title">Nouveau Message</div>
                    <div class="whatsapp-popup-item-desc">Vous avez un nouveau message</div>
                </div>
                <div class="whatsapp-popup-item-time">12/06</div>
            </div>
        </div>
        <div class="whatsapp-popup-footer">
            <a href="#">Marquer tout comme lu</a>
        </div>
    </div>

    <div class="whatsapp-popup" id="profilePopup">
        <div class="whatsapp-popup-header">
            <i class="fas fa-user"></i>
            <h3>Mon Profil</h3>
        </div>
        <div class="whatsapp-popup-content">
            <div class="whatsapp-popup-item">
                <div class="whatsapp-popup-item-icon" style="background-color: #f0f0f0;">
                    <i class="fas fa-user-circle" style="color: #04221a;"></i>
                </div>
                <div class="whatsapp-popup-item-content">
                    <div class="whatsapp-popup-item-title">Jean Dupont</div>
                    <div class="whatsapp-popup-item-desc">Membre depuis: Jan 2023</div>
                </div>
            </div>
            <div class="whatsapp-popup-item">
                <div class="whatsapp-popup-item-icon" style="background-color: #e6f7ff;">
                    <i class="fas fa-envelope" style="color: #1890ff;"></i>
                </div>
                <div class="whatsapp-popup-item-content">
                    <div class="whatsapp-popup-item-title">jean.dupont@email.com</div>
                    <div class="whatsapp-popup-item-desc">Adresse email vérifiée</div>
                </div>
            </div>
            <div class="whatsapp-popup-item">
                <div class="whatsapp-popup-item-icon" style="background-color: #f6ffed;">
                    <i class="fas fa-map-marker-alt" style="color: #52c41a;"></i>
                </div>
                <div class="whatsapp-popup-item-content">
                    <div class="whatsapp-popup-item-title">Paris, France</div>
                    <div class="whatsapp-popup-item-desc">Adresse de livraison principale</div>
                </div>
            </div>
            <div class="whatsapp-popup-item">
                <div class="whatsapp-popup-item-icon" style="background-color: #fff7e6;">
                    <i class="fas fa-shopping-bag" style="color: #fa8c16;"></i>
                </div>
                <div class="whatsapp-popup-item-content">
                    <div class="whatsapp-popup-item-title">12 Commandes</div>
                    <div class="whatsapp-popup-item-desc">Dernière: 12 juin 2023</div>
                </div>
            </div>
        </div>
        <div class="whatsapp-popup-footer">
            <a href="#">Modifier le profil</a>
        </div>
    </div>


    <div class="wrap">
        <!-- Header -->
        <header>
            <div class="brand">
                <div class="logo" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path d="M4 12c2-4 8-8 12-4" stroke="#04221a" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <circle cx="12" cy="12" r="4" fill="#04221a" />
                        <path d="M20 4l-4 4" stroke="#fff" stroke-opacity="0.06" stroke-width="1.2" />
                    </svg>
                </div>
                <div>
                    <h1>JosNet</h1>
                    <p>Vente Télécom • Dev • Formation • Coaching</p>
                </div>
            </div>
            
            <nav class="nav">
                <a href="actualite.php" class="btn">Actualites</a>
                <a href="produits.php" class="btn">Equipements</a>
                <a href="services.php" class="btn">Services</a>
                <a href="contact.php" class="btn">Contact</a>
            </nav>
        </header>

        <!-- Page Title -->
        <div class="nom_service">
            <h1>NOS ACTUALITES</h1>
        </div>
                
        <!-- Services Section -->
        <section class="reveal" id="features">
            <div class="grid">
                <?php if (!empty($listeFormation['data'])): ?>
                    <?php foreach ($listeFormation['data'] as $formation): ?>
                        <article class="card" data-tilt>
                            <div class="image-container">
                                <img src="<?='../controle/'.$formation->getPhoto()?>" 
                                     alt="<?='Image de '.htmlspecialchars($formation->getTitre())?>"
                                     onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KICA8cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZGRkIi8+CiAgPHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtc2l6ZT0iMTgiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIiBmaWxsPSIjNTU1Ij5JbWFnZSBub24gZGlzcG9uaWJsZTwvdGV4dD4KPC9zdmc+'">
                            </div>
                            
                            <div class="card-content">
                                <h3><?=htmlspecialchars(substr($formation->getTitre(), 0, 50))?></h3>
                                <p><?=htmlspecialchars(substr($formation->getDescription(), 0, 100))?> . . .</p>
                                <p>
                                          <?php
                                                $duree = $formation->getDuree();
                                                $dateDebut = $formation->getDebutFormation();
                                                $etat = $formations->getTempsRestantFinFormation($dateDebut,$duree);
                                                echo $etat;
                                            ?>
                              </p>
                                
                                <div class="alignements_icones">
                                    <button class="icon fa fa-comment" 
                                            onclick="openCommentModal(<?=$formation->getIdFormation()?>)" 
                                            title="Commenter">
                                    </button>
                                    <button class="icon fas fa-star" 
                                            onclick="addToFavorites(<?=$formation->getIdFormation()?>)" 
                                            title="Ajouter aux favoris">
                                    </button>
                                    <button class="icon fas fa-share" 
                                            onclick="openShareModal(<?=$formation->getIdFormation()?>, 'service')" 
                                            title="Partager">
                                    </button>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="grid-column: 1 / -1; text-align: center; padding: 40px; background: white; border-radius: 15px;">
                        <h3 style="color: #666; margin-bottom: 20px;">Aucun service disponible</h3>
                        <p style="color: #999;">Revenez plus tard pour découvrir nos nouveaux services.</p>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <!-- Comment Modal -->
        <div id="commentModal" class="modal" role="dialog" aria-labelledby="commentModalTitle" aria-hidden="true">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 id="commentModalTitle"><i class="fas fa-star"></i> Votre avis compte</h3>
                    <span class="close" onclick="closeModal('commentModal')" title="Fermer">&times;</span>
                </div>
                
                <div class="modal-body">
                    <form method="post" id="commentForm">
                        <input type="hidden" name="csrf_token" value="<?=$_SESSION['csrf_token'] ?? ''?>">
                        <input type="hidden" name="action" value="ajouter_commentaire">
                        <input type="hidden" name="id_service" id="comment_service_id">
                        
                        <!-- Rating Section -->
                        <div class="rating-section">
                            <label class="rating-label">
                                <i class="fas fa-star"></i> Votre note
                            </label>
                            <div class="rating" id="starRating">
                                <input type="radio" id="star5" name="note" value="5" />
                                <label for="star5" title="5 étoiles" data-value="5">★</label>
                                <input type="radio" id="star4" name="note" value="4" />
                                <label for="star4" title="4 étoiles" data-value="4">★</label>
                                <input type="radio" id="star3" name="note" value="3" />
                                <label for="star3" title="3 étoiles" data-value="3">★</label>
                                <input type="radio" id="star2" name="note" value="2" />
                                <label for="star2" title="2 étoiles" data-value="2">★</label>
                                <input type="radio" id="star1" name="note" value="1" />
                                <label for="star1" title="1 étoile" data-value="1">★</label>
                            </div>
                            <div class="rating-text" id="ratingText">Cliquez sur les étoiles pour noter</div>
                        </div>
                        
                        <!-- Comment Section -->
                        <div class="comment-section">
                            <div class="form-group">
                                <label for="commentaire">
                                    <i class="fas fa-edit"></i> Votre commentaire
                                </label>
                                <textarea name="commentaire" 
                                          id="commentaire" 
                                          placeholder="Partagez votre expérience avec ce service..." 
                                          required 
                                          maxlength="500"
                                          oninput="updateCharCounter()"></textarea>
                                <div class="char-counter">
                                    <span id="charCount">0</span>/500 caractères
                                </div>
                            </div>
                        </div>
                        
                        <div class="modal-actions">
                            <button type="button" class="btn btn-cancel" onclick="closeModal('commentModal')">
                                <i class="fas fa-times"></i> Annuler
                            </button>
                            <button type="submit" class="btn btn-submit" id="submitBtn">
                                <i class="fas fa-paper-plane"></i> Publier
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Share Modal -->
        <div id="shareModal" class="modal" role="dialog" aria-labelledby="shareModalTitle" aria-hidden="true">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 id="shareModalTitle"><i class="fas fa-share"></i> Partager ce service</h3>
                    <span class="close" onclick="closeModal('shareModal')" title="Fermer">&times;</span>
                </div>
                <div class="modal-body">
                    <div class="plateformes-partage" style="display: flex; flex-wrap: wrap; justify-content: space-around; gap: 15px; margin-top: 20px;">
                        <button class="fab fa-facebook" style="font-size: 24px; cursor: pointer; padding: 15px; border-radius: 50%; transition: all 0.3s ease; border: none; background: none; color: #3b5998;" onclick="shareOnPlatform('facebook')" title="Facebook"></button>
                        <button class="fab fa-twitter" style="font-size: 24px; cursor: pointer; padding: 15px; border-radius: 50%; transition: all 极好.3s ease; border: none; background: none; color: #1da1f2;" onclick="shareOnPlatform('twitter')" title="Twitter"></button>
                        <button class="fab fa-linkedin" style="font-size: 24px; cursor: pointer; padding: 15px; border-radius: 50%; transition: all 0.3s ease; border: none; background: none; color: #0077b5;" onclick="shareOnPlatform('linkedin')" title="LinkedIn"></button>
                        <button class="fab fa-whatsapp" style="font-size: 24px; cursor: pointer; padding: 15px; border-radius: 50%; transition: all 0.3s ease; border: none; background: none; color: #25d366;" onclick="shareOnPlatform('whatsapp')" title="WhatsApp"></button>
                        <button class="fas fa-envelope" style="font-size: 24px; cursor: pointer; padding: 15px; border-radius: 50%; transition: all 0.3s ease; border: none; background: none; color: #d44638;" onclick="shareOnPlatform('email')" title="Email"></button>
                        <button class="fas fa-link" style="font-size: 24px; cursor: pointer; padding: 15px; border-radius: 50%; transition: all 0.3s ease; border: none; background: none; color: #6c757d;" onclick="shareOnPlatform('lien')" title="Copier le lien"></button>
                    </div>
                    <form id="shareForm" method="post" style="display:none;">
                        <input type="hidden" name="csrf_token" value="<?=$_SESSION['csrf_token'] ?? ''?>">
                        <input type="hidden" name="action" value="partager">
                        <input type="hidden" name="id_service" id="share_service_id">
                        <input type="hidden" name="plateforme" id="share_platform">
                    </form>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="reveal">
            <small>&copy; <span id="year"></span> JosNet — Construit pour l'avenir</small>
        </footer>
    </div>

    <script>
        // Variables globales
        let currentRating = 0;
        const ratingTexts = {
            1: "Très décevant 😞",
            2: "Pas terrible 😐", 
            3: "Correct 🙂",
            4: "Très bien 😊",
            5: "Excellent ! 🤩"
        };

        // Initialisation au chargement de la page
        document.addEventListener('DOMContentLoaded', function() {
            initializeStarRating();
            document.getElementById('year').textContent = new Date().getFullYear();
        });

        // Fonction pour ouvrir la modale de commentaire
        function openCommentModal(serviceId) {
            if (!serviceId) {
                showAlert('Erreur: ID du service manquant', 'error');
                return;
            }
            
            document.getElementById('comment_service_id').value = serviceId;
            const modal = document.getElementById('commentModal');
            modal.style.display = 'block';
            
            // Animation d'ouverture
            setTimeout(() => {
                modal.classList.add('show');
            }, 10);
            
            modal.setAttribute('aria-hidden', 'false');
            
            // Focus sur le textarea
            const textarea = modal.querySelector('textarea');
            if (textarea) {
                setTimeout(() => textarea.focus(), 300);
            }
        }

        // Fonction pour fermer la modale
        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.remove('show');
                setTimeout(() => {
                    modal.style.display = 'none';
                }, 300);
                modal.setAttribute('aria-hidden', 'true');
                if (modalId === 'commentModal') {
                    resetForm();
                }
            }
        }

        // Initialiser le système d'étoiles
        function initializeStarRating() {
            const stars = document.querySelectorAll('.rating label');
            const ratingText = document.getElementById('ratingText');
            
            stars.forEach((star, index) => {
                star.addEventListener('mouseenter', function() {
                    const value = this.getAttribute('data-value');
                    highlightStars(value);
                    ratingText.textContent = ratingTexts[value] || 'Cliquez pour noter';
                });
                
                star.addEventListener('click', function() {
                    const value = this.getAttribute('data-value');
                    currentRating = value;
                    document.getElementById(`star${value}`).checked = true;
                    ratingText.textContent = ratingTexts[value];
                    
                    // Animation sur le clic
                    this.classList.add('pulse');
                    setTimeout(() => {
                        this.classList.remove('pulse');
                    }, 600);
                });
            });
            
            document.querySelector('.rating').addEventListener('mouseleave', function() {
                if (currentRating > 0) {
                    highlightStars(currentRating);
                    ratingText.textContent = ratingTexts[currentRating];
                } else {
                    resetStars();
                    ratingText.textContent = 'Cliquez sur les étoiles pour noter';
                }
            });
        }

        function highlightStars(rating) {
            const stars = document.querySelectorAll('.rating label');
            stars.forEach((star, index) => {
                const starValue = star.getAttribute('data-value');
                if (starValue <= rating) {
                    star.style.color = '#ffc107';
                    star.style.textShadow = '0 0 10px rgba(255, 193, 7, 0.5)';
                    star.style.transform = 'scale(1.1)';
                } else {
                    star.style.color = '#ddd';
                    star.style.textShadow = 'none';
                    star.style.transform = 'scale(1)';
                }
            });
        }

        function resetStars() {
            const stars = document.querySelectorAll('.rating label');
            stars.forEach(star => {
                star.style.color = '#ddd';
                star.style.textShadow = 'none';
                star.style.transform = 'scale(1)';
            });
        }

        // Compteur de caractères
        function updateCharCounter() {
            const textarea = document.getElementById('commentaire');
            const charCount = document.getElementById('charCount');
            const counter = document.querySelector('.char-counter');
            
            const currentLength = textarea.value.length;
            charCount.textContent = currentLength;
            
            if (currentLength > 450) {
                counter.classList.add('warning');
            } else {
                counter.classList.remove('warning');
            }
        }

        // Validation et soumission du formulaire
        document.getElementById('commentForm').addEventListener('submit', function(event) {
            event.preventDefault();
            
            const commentaire = document.getElementById('commentaire').value.trim();
            const rating = document.querySelector('input[name="note"]:checked');
            
            if (!rating) {
                showAlert('Veuillez sélectionner une note.', 'error');
                return false;
            }
            
            if (commentaire.length === 0) {
                showAlert('Veuillez saisir un commentaire.', 'error');
                return false;
            }
            
            if (commentaire.length > 500) {
                showAlert('Le commentaire ne peut pas dépasser 500 caractères.', 'error');
                return false;
            }
            
            // Simulation d'envoi
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Envoi en cours...';
            
            setTimeout(() => {
                showAlert('Commentaire ajouté avec succès !', 'success');
                setTimeout(() => {
                    closeModal('commentModal');
                }, 1500);
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Publier';
            }, 1500);
        });

        // Fonction pour afficher les alertes
        function showAlert(message, type) {
            // Supprimer les anciennes alertes
            const existingAlert = document.querySelector('.alert');
            if (existingAlert) {
                existingAlert.remove();
            }
            
            const alert = document.createElement('div');
            alert.className = `alert alert-${type}`;
            alert.textContent = message;
            
            const modalBody = document.querySelector('.modal-body');
            if (modalBody) {
                modalBody.insertBefore(alert, modalBody.firstChild);
                
                setTimeout(() => {
                    if (alert.parentNode) {
                        alert.remove();
                    }
                }, 5000);
            }
        }

        // Réinitialiser le formulaire
        function resetForm() {
            document.getElementById('commentForm').reset();
            currentRating = 0;
            resetStars();
            document.getElementById('ratingText').textContent = 'Cliquez sur les étoiles pour noter';
            document.getElementById('charCount').textContent = '0';
            document.querySelector('.char-counter').classList.remove('warning');
            
            // Supprimer les alertes
            const alert = document.querySelector('.alert');
            if (alert) alert.remove();
        }

        // Fonction pour ouvrir la modale de partage
        function openShareModal(id, type) {
            if (type === 'service') {
                document.getElementById('share_service_id').value = id;
            }
            
            const modal = document.getElementById('shareModal');
            modal.style.display = 'block';
            setTimeout(() => {
                modal.classList.add('show');
            }, 10);
            modal.setAttribute('aria-hidden', 'false');
        }

        // Fonction pour partager sur une plateforme spécifique
        function shareOnPlatform(platform) {
            if (!platform) {
                alert('Erreur: Plateforme non spécifiée');
                return;
            }
            
            document.getElementById('share_platform').value = platform;
            
            // Simulation de partage
            let message = '';
            switch(platform) {
                case 'facebook':
                    message = 'Partagé sur Facebook !';
                    break;
                case 'twitter':
                    message = 'Partagé sur Twitter !';
                    break;
                case 'linkedin':
                    message = 'Partagé sur LinkedIn !';
                    break;
                case 'whatsapp':
                    message = 'Partagé sur WhatsApp !';
                    break;
                case 'email':
                    message = 'Envoyé par email !';
                    break;
                case 'lien':
                    message = 'Lien copié dans le presse-papiers !';
                    break;
                default:
                    message = 'Partagé avec succès !';
            }
            
            alert(message);
            closeModal('shareModal');
        }

        // Fonction pour ajouter au panier
        function addToCart(serviceId) {
            if (!serviceId) {
                alert('Erreur: ID du service manquant');
                return;
            }
            
            // Animation du bouton
            const cartBtn = event.target;
            cartBtn.style.transform = 'scale(1.2)';
            cartBtn.style.background = '#28a745';
            
            setTimeout(() => {
                favBtn.style.transform = 'scale(1)';
                favBtn.style.background = 'rgba(255, 193, 7, 0.1)';
                alert('Produit ajouté aux favoris !');
            }, 300);
        }

        // Fermer les modales en cliquant en dehors
        window.addEventListener('click', function(event) {
            const modals = document.getElementsByClassName('modal');
            for (let i = 0; i < modals.length; i++) {
                if (event.target === modals[i]) {
                    modals[i].classList.remove('show');
                    setTimeout(() => {
                        modals[i].style.display = "none";
                    }, 300);
                    modals[i].setAttribute('aria-hidden', 'true');
                    if (modals[i].id === 'commentModal') {
                        resetForm();
                    }
                }
            }
        });

        // Gestion des touches clavier pour les modales
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                const openModals = Array.from(document.getElementsByClassName('modal'))
                    .filter(modal => modal.style.display === 'block');
                openModals.forEach(modal => {
                    modal.classList.remove('show');
                    setTimeout(() => {
                        modal.style.display = 'none';
                    }, 300);
                    modal.setAttribute('aria-hidden', 'true');
                    if (modal.id === 'commentModal') {
                        resetForm();
                    }
                });
            }
        });

        // Animation de hover pour les plateformes de partage
        document.addEventListener('DOMContentLoaded', function() {
            const shareButtons = document.querySelectorAll('.plateformes-partage button');
            shareButtons.forEach(button => {
                button.addEventListener('mouseenter', function() {
                    this.style.transform = 'scale(1.2)';
                });
                button.addEventListener('mouseleave', function() {
                    this.style.transform = 'scale(1)';
                });
            });
        });

        // Gestion des popups
        function togglePopup(popupId) {
            const popup = document.getElementById(popupId);
            
            // Fermer tous les autres popups
            document.querySelectorAll('.whatsapp-popup').forEach(p => {
                if (p.id !== popupId) {
                    p.style.display = 'none';
                }
            });
            
            // Toggle le popup demandé
            if (popup.style.display === 'block') {
                popup.style.display = 'none';
            } else {
                popup.style.display = 'block';
            }
        }

        // Fonction pour toggle le dropdown profil
        function toggleProfileDropdown() {
            const dropdown = document.getElementById('profileDropdown');
            if (dropdown) {
                dropdown.classList.toggle('show');
            }
        }

        // Fermer les popups en cliquant en dehors
        document.addEventListener('click', function(event) {
            if (!event.target.closest('.user_message') && !event.target.closest('.whatsapp-popup')) {
                document.querySelectorAll('.whatsapp-popup').forEach(popup => {
                    popup.style.display = 'none';
                });
            }

            // Fermer le dropdown profil si on clique ailleurs
            const dropdown = document.getElementById('profileDropdown');
            const toggle = document.querySelector('.profile-toggle');
            
            if (dropdown && toggle && !toggle.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.classList.remove('show');
            }
        });

        // Fermer les popups avec Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                // Fermer les popups WhatsApp
                document.querySelectorAll('.whatsapp-popup').forEach(popup => {
                    popup.style.display = 'none';
                });
            }
        });
    </script>
</body>
</html>