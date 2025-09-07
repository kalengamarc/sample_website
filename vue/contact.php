<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - JosNet</title>
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
            padding-bottom: 20px;
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
            cursor: pointer;
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

        /* Contact Section */
        .contact-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-bottom: 50px;
        }

        @media (max-width: 900px) {
            .contact-container {
                grid-template-columns: 1fr;
            }
        }

        .contact-info {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }

        .contact-info h2 {
            color: #04221a;
            margin-bottom: 25px;
            font-size: 1.8em;
            position: relative;
            padding-bottom: 10px;
        }

        .contact-info h2:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 3px;
            background: linear-gradient(135deg, #04221a 0%, #2c5f2d 100%);
            border-radius: 3px;
        }

        .info-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 25px;
            cursor: pointer;
            transition: all 0.3s ease;
            padding: 15px;
            border-radius: 12px;
        }

        .info-item:hover {
            background: rgba(4, 34, 26, 0.05);
            transform: translateX(5px);
        }

        .info-icon {
            background: linear-gradient(135deg, #04221a 0%, #2c5f2d 100%);
            color: white;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            flex-shrink: 0;
            transition: all 0.3s ease;
        }

        .info-item:hover .info-icon {
            transform: scale(1.1);
            box-shadow: 0 5px 15px rgba(4, 34, 26, 0.3);
        }

        .info-content h3 {
            color: #04221a;
            margin-bottom: 5px;
            font-size: 1.2em;
        }

        .info-content p {
            color: #666;
            line-height: 1.6;
        }

        .social-links {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }

        .social-links a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: #f1f1f1;
            color: #04221a;
            transition: all 0.3s ease;
            text-decoration: none;
            font-size: 1.2em;
        }

        .social-links a:hover {
            background: linear-gradient(135deg, #04221a 0%, #2c5f2d 100%);
            color: white;
            transform: translateY(-3px);
        }

        /* Contact Form */
        .contact-form {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }

        .contact-form h2 {
            color: #04221a;
            margin-bottom: 25px;
            font-size: 1.8em;
            position: relative;
            padding-bottom: 10px;
        }

        .contact-form h2:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 3px;
            background: linear-gradient(135deg, #04221a 0%, #2c5f2d 100%);
            border-radius: 3px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }

        .form-control {
            width: 100%;
            padding: 15px;
            border: 2px solid #e1e5e9;
            border-radius: 12px;
            font-family: inherit;
            font-size: 14px;
            transition: all 0.3s ease;
            background: #fafbfc;
        }

        .form-control:focus {
            outline: none;
            border-color: #04221a;
            box-shadow: 0 0 15px rgba(4, 34, 26, 0.2);
            background: white;
        }

        textarea.form-control {
            min-height: 150px;
            resize: vertical;
        }

        .btn-submit {
            background: linear-gradient(135deg, #04221a 0%, #2c5f2d 100%);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 12px;
            cursor: pointer;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
            display: inline-block;
            width: 100%;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(4, 34, 26, 0.3);
        }

        /* Map Section */
        .map-section {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            margin-bottom: 40px;
        }

        .map-section h2 {
            color: #04221a;
            margin-bottom: 25px;
            font-size: 1.8em;
            position: relative;
            padding-bottom: 10px;
        }

        .map-section h2:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 3px;
            background: linear-gradient(135deg, #04221a 0%, #2c5f2d 100%);
            border-radius: 3px;
        }

        .map-container {
            border-radius: 15px;
            overflow: hidden;
            height: 400px;
            background: #eee;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #666;
            font-size: 1.1em;
        }

        /* FAQ Section */
        .faq-section {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }

        .faq-section h2 {
            color: #04221a;
            margin-bottom: 25px;
            font-size: 1.8em;
            position: relative;
            padding-bottom: 10px;
            text-align: center;
        }

        .faq-section h2:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: linear-gradient(135deg, #04221a 0%, #2c5f2d 100%);
            border-radius: 3px;
        }

        .faq-item {
            margin-bottom: 20px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .faq-question {
            background: linear-gradient(135deg, #04221a 0%, #2c5f2d 100%);
            color: white;
            padding: 18px 20px;
            cursor: pointer;
            font-weight: 600;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s ease;
        }

        .faq-question:hover {
            background: linear-gradient(135deg, #04221a 0%, #3a7a3c 100%);
        }

        .faq-answer {
            background: white;
            padding: 20px;
            display: none;
        }

        .faq-answer p {
            color: #666;
            line-height: 1.6;
        }

        .faq-active .faq-answer {
            display: block;
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
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .reveal {
            animation: fadeIn 1s ease forwards;
        }

        /* Popup Styles */
        .popup {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(5px);
        }

        .popup-content {
            background: white;
            border-radius: 20px;
            padding: 40px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
            position: relative;
            animation: popupFadeIn 0.4s ease;
        }

        @keyframes popupFadeIn {
            from {
                opacity: 0;
                transform: scale(0.9) translateY(-50px);
            }
            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .popup-header {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
        }

        .popup-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 20px;
            font-size: 24px;
            color: white;
            flex-shrink: 0;
        }

        .popup-icon.address {
            background: linear-gradient(135deg, #04221a 0%, #2c5f2d 100%);
        }

        .popup-icon.phone {
            background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
        }

        .popup-icon.email {
            background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
        }

        .popup-icon.cart {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }

        .popup-icon.profile {
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        }

        .popup-icon.notification {
            background: linear-gradient(135deg, #ec4899 0%, #db2777 100%);
        }

        .popup-title {
            font-size: 24px;
            font-weight: 700;
            color: #04221a;
        }

        .popup-body {
            margin-bottom: 30px;
        }

        .popup-body p {
            font-size: 16px;
            line-height: 1.6;
            color: #666;
            margin-bottom: 15px;
        }

        .popup-body strong {
            color: #04221a;
            font-weight: 600;
        }

        .popup-actions {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
        }

        .popup-btn {
            padding: 12px 25px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .popup-btn.primary {
            background: linear-gradient(135deg, #04221a 0%, #2c5f2d 100%);
            color: white;
        }

        .popup-btn.primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(4, 34, 26, 0.3);
        }

        .popup-btn.secondary {
            background: #f3f4f6;
            color: #374151;
        }

        .popup-btn.secondary:hover {
            background: #e5e7eb;
        }

        .popup-close {
            position: absolute;
            top: 20px;
            right: 20px;
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #9ca3af;
            transition: all 0.3s ease;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .popup-close:hover {
            background: #f3f4f6;
            color: #374151;
            transform: rotate(90deg);
        }

        /* WhatsApp-like popup styles */
        .whatsapp-popup {
            position: fixed;
            bottom: 90px;
            right: 20px;
            width: 300px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            display: none;
            overflow: hidden;
        }

        .whatsapp-popup-header {
            background: linear-gradient(135deg, #04221a 0%, #2c5f2d 100%);
            color: white;
            padding: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .whatsapp-popup-header i {
            font-size: 20px;
        }

        .whatsapp-popup-header h3 {
            font-size: 16px;
            font-weight: 600;
        }

        .whatsapp-popup-content {
            padding: 15px;
            max-height: 300px;
            overflow-y: auto;
        }

        .whatsapp-popup-item {
            display: flex;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .whatsapp-popup-item:last-child {
            border-bottom: none;
        }

        .whatsapp-popup-item-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            color: #04221a;
        }

        .whatsapp-popup-item-content {
            flex: 1;
        }

        .whatsapp-popup-item-title {
            font-weight: 600;
            color: #04221a;
            margin-bottom: 3px;
        }

        .whatsapp-popup-item-desc {
            font-size: 14px;
            color: #666;
        }

        .whatsapp-popup-item-time {
            font-size: 12px;
            color: #999;
        }

        .whatsapp-popup-footer {
            padding: 10px 15px;
            text-align: center;
            background: #f9f9f9;
            border-top: 1px solid #eee;
        }

        .whatsapp-popup-footer a {
            color: #04221a;
            text-decoration: none;
            font-weight: 600;
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
                flex-wrap: wrap;
            }
            
            .nom_service h1 {
                font-size: 2em;
            }
            
            .contact-container {
                gap: 20px;
            }
            
            .info-item {
                flex-direction: column;
                text-align: center;
            }
            
            .info-icon {
                margin-right: 0;
                margin-bottom: 10px;
            }
            
            .social-links {
                justify-content: center;
            }
            
            .popup-content {
                padding: 30px 20px;
                margin: 20px;
            }
            
            .popup-header {
                flex-direction: column;
                text-align: center;
                gap: 15px;
            }
            
            .popup-icon {
                margin-right: 0;
            }
            
            .popup-actions {
                flex-direction: column;
            }

            .whatsapp-popup {
                width: 280px;
                right: 10px;
            }
        }
    </style>
</head>
<body>
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
            <h1>CONTACTEZ-NOUS</h1>
        </div>
        
        <!-- Contact Section -->
        <section class="contact-container reveal">
            <div class="contact-info">
                <h2>Nos Coordonnées</h2>
                
                <div class="info-item" onclick="showPopup('address')">
                    <div class="info-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="info-content">
                        <h3>Adresse</h3>
                        <p>123 Avenue des Télécoms<br>75000 Paris, France</p>
                    </div>
                </div>
                
                <div class="info-item" onclick="showPopup('phone')">
                    <div class="info-icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div class="info-content">
                        <h3>Téléphone</h3>
                        <p>+33 1 23 45 67 89<br>Lun-Ven: 9h-18h</p>
                    </div>
                </div>
                
                <div class="info-item" onclick="showPopup('email')">
                    <div class="info-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="info-content">
                        <h3>Email</h3>
                        <p>contact@josnet.fr<br>support@josnet.fr</p>
                    </div>
                </div>
                
                <div class="social-links">
                    <a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" title="Twitter"><i class="fab fa-twitter"></i></a>
                    <a href="#" title="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#" title="Instagram"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
            
            <div class="contact-form">
                <h2>Envoyez-nous un message</h2>
                
                <form id="contactForm" method="post">
                    <div class="form-group">
                        <label for="name">Nom complet</label>
                        <input type="text" id="name" name="name" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Adresse email</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="subject">Sujet</label>
                        <input type="text" id="subject" name="subject" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="message">Message</label>
                        <textarea id="message" name="message" class="form-control" required></textarea>
                    </div>
                    
                    <button type="submit" class="btn-submit">Envoyer le message</button>
                </form>
            </div>
        </section>
        
        <!-- Map Section -->
        <section class="map-section reveal">
            <h2>Notre Localisation</h2>
            <div class="map-container">
                <i class="fas fa-map-marked-alt" style="font-size: 3em; margin-right: 15px;"></i>
                <span>Carte interactive (Google Maps)</span>
            </div>
        </section>
        
        <!-- FAQ Section -->
        <section class="faq-section reveal">
            <h2>Questions Fréquentes</h2>
                        <div class="faq-item">
                <div class="faq-question">
                    <span>Quels sont vos horaires d'ouverture ?</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Nos bureaux sont ouverts du lundi au vendredi de 9h à 18h. Notre service client est disponible par téléphone aux mêmes horaires, et vous pouvez nous contacter par email 24h/24.</p>
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">
                    <span>Comment puis-je suivre ma commande ?</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Une fois votre commande expédiée, vous recevrez un email de confirmation avec un numéro de suivi. Vous pouvez également vous connecter à votre compte pour suivre l'état de votre commande.</p>
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">
                    <span>Proposez-vous des formations en entreprise ?</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Oui, nous proposons des formations sur mesure adaptées aux besoins spécifiques de votre entreprise. Contactez-nous pour discuter de vos besoins et obtenir un devis personnalisé.</p>
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">
                    <span>Quelles sont vos méthodes de paiement acceptées ?</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Nous acceptons les cartes bancaires (Visa, MasterCard, American Express), les virements bancaires, PayPal ainsi que les chèques pour les commandes en France métropolitaine.</p>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="reveal">
            <small>&copy; <span id="year"></span> JosNet — Construit pour l'avenir</small>
        </footer>
    </div>

    <!-- Popup Templates -->
    <div class="popup" id="addressPopup">
        <div class="popup-content">
            <button class="popup-close" onclick="closePopup('addressPopup')">&times;</button>
            <div class="popup-header">
                <div class="popup-icon address">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <h2 class="popup-title">Notre Adresse</h2>
            </div>
            <div class="popup-body">
                <p><strong>Adresse complète :</strong><br>123 Avenue des Télécoms<br>75000 Paris, France</p>
                <p><strong>Heures d'ouverture :</strong><br>Lundi - Vendredi: 9h00 - 18h00<br>Samedi: 10h00 - 16h00<br>Dimanche: Fermé</p>
                <p><strong>Accès :</strong><br>Métro: Ligne 4 - Station Télécoms<br>Bus: Lignes 42, 67, 89<br>Parking: Stationnement souterrain disponible</p>
            </div>
            <div class="popup-actions">
                <button class="popup-btn secondary" onclick="closePopup('addressPopup')">
                    <i class="fas fa-times"></i> Fermer
                </button>
                <button class="popup-btn primary" onclick="openMaps()">
                    <i class="fas fa-directions"></i> Itinéraire
                </button>
            </div>
        </div>
    </div>

    <div class="popup" id="phonePopup">
        <div class="popup-content">
            <button class="popup-close" onclick="closePopup('phonePopup')">&times;</button>
            <div class="popup-header">
                <div class="popup-icon phone">
                    <i class="fas fa-phone"></i>
                </div>
                <h2 class="popup-title">Nous Appeler</h2>
            </div>
            <div class="popup-body">
                <p><strong>Service client :</strong><br>+33 1 23 45 67 89</p>
                <p><strong>Support technique :</strong><br>+33 1 23 45 67 90</p>
                <p><strong>Service commercial :</strong><br>+33 1 23 45 67 91</p>
                <p><strong>Heures d'ouverture :</strong><br>Lundi - Vendredi: 9h00 - 18h00<br>Samedi: 10h00 - 16h00</p>
                <p><strong>Urgences :</strong><br>Pour les urgences techniques en dehors des heures d'ouverture, composez le +33 6 12 34 56 78</p>
            </div>
            <div class="popup-actions">
                <button class="popup-btn secondary" onclick="closePopup('phonePopup')">
                    <i class="fas fa-times"></i> Fermer
                </button>
                <button class="popup-btn primary" onclick="callNumber('+33123456789')">
                    <i class="fas fa-phone"></i> Appeler maintenant
                </button>
            </div>
        </div>
    </div>

    <div class="popup" id="emailPopup">
        <div class="popup-content">
            <button class="popup-close" onclick="closePopup('emailPopup')">&times;</button>
            <div class="popup-header">
                <div class="popup-icon email">
                    <i class="fas fa-envelope"></i>
                </div>
                <h2 class="popup-title">Nous Écrire</h2>
            </div>
            <div class="popup-body">
                <p><strong>Service client :</strong><br>contact@josnet.fr</p>
                <p><strong>Support technique :</strong><br>support@josnet.fr</p>
                <p><strong>Service commercial :</strong><br>commercial@josnet.fr</p>
                <p><strong>Recrutement :</strong><br>recrutement@josnet.fr</p>
                <p><strong>Partenariats :</strong><br>partenariats@josnet.fr</p>
                <p><strong>Temps de réponse :</strong><br>Nous nous engageons à répondre à tous les emails dans un délai de 24 heures ouvrées.</p>
            </div>
            <div class="popup-actions">
                <button class="popup-btn secondary" onclick="closePopup('emailPopup')">
                    <i class="fas fa-times"></i> Fermer
                </button>
                <button class="popup-btn primary" onclick="sendEmail('contact@josnet.fr')">
                    <i class="fas fa-envelope"></i> Envoyer un email
                </button>
            </div>
        </div>
    </div>

    <script>
        // Initialisation au chargement de la page
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('year').textContent = new Date().getFullYear();
            
            // Gestion des FAQ
            const faqQuestions = document.querySelectorAll('.faq-question');
            faqQuestions.forEach(question => {
                question.addEventListener('click', () => {
                    const faqItem = question.parentElement;
                    faqItem.classList.toggle('faq-active');
                    
                    const icon = question.querySelector('i');
                    if (faqItem.classList.contains('faq-active')) {
                        icon.classList.remove('fa-chevron-down');
                        icon.classList.add('fa-chevron-up');
                    } else {
                        icon.classList.remove('fa-chevron-up');
                        icon.classList.add('fa-chevron-down');
                    }
                });
            });
            
            // Gestion du formulaire de contact
            const contactForm = document.getElementById('contactForm');
            if (contactForm) {
                contactForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    // Récupération des valeurs du formulaire
                    const name = document.getElementById('name').value;
                    const email = document.getElementById('email').value;
                    const subject = document.getElementById('subject').value;
                    const message = document.getElementById('message').value;
                    
                    // Ici, vous ajouteriez le code pour envoyer les données au serveur
                    // Pour l'instant, nous allons simplement afficher une alerte
                    
                    alert(`Merci ${name} ! Votre message a été envoyé. Nous vous répondrons à l'adresse ${email} sous peu.`);
                    contactForm.reset();
                });
            }
        });

        // Fonctions pour les popups
        function showPopup(type) {
            const popup = document.getElementById(`${type}Popup`);
            if (popup) {
                popup.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            }
        }

        function closePopup(type) {
            const popup = document.getElementById(`${type}Popup`);
            if (popup) {
                popup.style.display = 'none';
                document.body.style.overflow = 'auto';
            }
        }

        // Fermer les popups en cliquant à l'extérieur
        document.querySelectorAll('.popup').forEach(popup => {
            popup.addEventListener('click', (e) => {
                if (e.target === popup) {
                    popup.style.display = 'none';
                    document.body.style.overflow = 'auto';
                }
            });
        });

        // Fonctions d'action pour les boutons
        function openMaps() {
            window.open('https://www.google.com/maps?q=123+Avenue+des+Télécoms,75000+Paris,France', '_blank');
            closePopup('addressPopup');
        }

        function callNumber(number) {
            window.location.href = `tel:${number}`;
            closePopup('phonePopup');
        }

        function sendEmail(email) {
            window.location.href = `mailto:${email}`;
            closePopup('emailPopup');
        }

        // Fonctions pour les popups style WhatsApp
        let activePopup = null;

        function togglePopup(popupId) {
            const popup = document.getElementById(popupId);
            
            // Fermer tous les autres popups
            document.querySelectorAll('.whatsapp-popup').forEach(p => {
                if (p.id !== popupId) {
                    p.style.display = 'none';
                }
            });
            
            // Ouvrir ou fermer le popup actuel
            if (popup.style.display === 'block') {
                popup.style.display = 'none';
                activePopup = null;
            } else {
                popup.style.display = 'block';
                activePopup = popupId;
            }
        }

        // Fermer les popups WhatsApp en cliquant à l'extérieur
        document.addEventListener('click', function(e) {
            if (activePopup) {
                const popup = document.getElementById(activePopup);
                const userMessageIcons = document.querySelector('.user_message');
                
                // Vérifier si le clic est en dehors du popup et des icônes
                if (!popup.contains(e.target) && !userMessageIcons.contains(e.target)) {
                    popup.style.display = 'none';
                    activePopup = null;
                }
            }
        });

        // Fermer avec la touche Échap
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                // Fermer les popups de contact
                document.querySelectorAll('.popup').forEach(popup => {
                    popup.style.display = 'none';
                });
                
                // Fermer les popups WhatsApp
                document.querySelectorAll('.whatsapp-popup').forEach(popup => {
                    popup.style.display = 'none';
                });
                
                document.body.style.overflow = 'auto';
                activePopup = null;
            }
        });
    </script>
</body>
</html>