<!doctype html>
<html lang="fr">
<head>
<?php

require_once 'session_client.php';
include_once('../controle/controleur_produit.php');
include_once('../controle/controleur_utilisateur.php');
$utilisateurController = new UtilisateurController();
$produitController = new ProduitController();
$produitsResult = $produitController->getAllProduits();
$produits = $produitsResult['success'] ? $produitsResult['data'] : [];

// Traitement des actions sans API
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'ajouter_commentaire':
                if (isset($_POST['id_produit'], $_POST['note'], $_POST['commentaire'])) {
                    // Rediriger vers le contrôleur principal pour traiter le commentaire
                    $_SESSION['comment_data'] = [
                        'id_produit' => $_POST['id_produit'],
                        'commentaire' => $_POST['commentaire'],
                        'note' => $_POST['note']
                    ];
                    header("Location: ../controle/index.php?do=comment_create");
                    exit();
                }
                break;
                
            case 'ajouter_panier':
                if (isset($_POST['id_produit'])) {
                    // Rediriger vers le contrôleur principal pour traiter le panier
                    $_SESSION['panier_data'] = [
                        'id_produit' => $_POST['id_produit'],
                        'quantite' => $_POST['quantite'] ?? 1
                    ];
                    header("Location: ../controle/index.php?do=panier_add");
                    exit();
                }
                break;
                
            case 'ajouter_favoris':
                if (isset($_POST['id_produit'])) {
                    // Rediriger vers le contrôleur principal pour traiter les favoris
                    $_SESSION['favori_data'] = [
                        'type' => 'produit',
                        'id_element' => $_POST['id_produit']
                    ];
                    header("Location: ../controle/index.php?do=favori_add");
                    exit();
                }
                break;
        }
    }
}

// Récupérer les messages depuis l'URL ou la session
$message = $_GET['message'] ?? $_SESSION['message'] ?? '';
$message_type = $_GET['type'] ?? $_SESSION['message_type'] ?? '';
unset($_SESSION['message']);
unset($_SESSION['message_type']);

include_once("../controle/controleur_panier.php");

$panierController = new PanierController();
$panier = $panierController->getCart($_SESSION['user_id']);
?>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Produits - JosNet</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="styles/features.css">
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

        /* User Info Display */
        .user-info-display {
            position: fixed;
            bottom: 180px;
            right: 20px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 15px 20px;
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.15);
            z-index: 998;
            text-align: center;
            min-width: 200px;
        }

        .user-info-display .user-name {
            font-weight: 600;
            color: #04221a;
            margin-bottom: 10px;
            font-size: 16px;
        }

        .user-info-display .logout-btn-floating {
            background: #ffae2b;
            color: #04221a;
            padding: 8px 16px;
            border: none;
            border-radius: 20px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
        }

        .user-info-display .logout-btn-floating:hover {
            background: #04221a;
            color: #ffae2b;
            transform: translateY(-2px);
        }

        /* User Message Icons */
        .user_message {
            position: fixed;
            top: 20px;
            right: 20px;
            display: flex;
            flex-direction: row;
            gap: 15px;
            z-index: 999;
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
            position: relative;
        }

        .user_message a:hover {
            transform: translateY(-2px);
            background: yellowgreen;
        }

        .user_message .badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
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

        /* Products Grid */
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

        .card .price {
            font-size: 1.3em;
            font-weight: bold;
            color: #2c5f2d;
            margin-bottom: 15px;
        }

        /* Product Actions */
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

        .fa-eye { 
            color: #007bff; 
            background: rgba(0, 123, 255, 0.1);
        }
        .fa-comment { 
            color: #17a2b8; 
            background: rgba(23, 162, 184, 0.1);
        }
        .fa-shopping-cart { 
            color: #28a745; 
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

        .fa-eye:hover { background: #007bff; }
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
            display: block;
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
            display: inline-flex;
            align-items: center;
            gap: 8px;
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

        /* Product Details Modal */
        .product-details-modal {
            display: none;
            position: fixed;
            z-index: 2000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.8);
            backdrop-filter: blur(5px);
            opacity: 0;
            transition: opacity 0.3s ease;
            align-items: center;
            justify-content: center;
        }

        .product-details-modal.show {
            opacity: 1;
            display: flex !important;
        }

        .product-details-content {
            background: linear-gradient(145deg, #ffffff, #f8f9fa);
            margin: 1% auto;
            padding: 0;
            border-radius: 20px;
            width: 95%;
            max-width: 1200px;
            height: 95vh;
            box-shadow: 0 25px 50px rgba(0,0,0,0.3);
            position: relative;
            transform: translateY(-50px);
            transition: transform 0.3s ease;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .product-details-modal.show .product-details-content {
            transform: translateY(0);
        }

        .product-details-header {
            background: linear-gradient(135deg, #04221a 0%, #2c5f2d 100%);
            color: white;
            padding: 25px 30px;
            position: relative;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .product-details-header h2 {
            margin: 0;
            font-size: 2em;
            font-weight: 600;
        }

        .product-details-close {
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

        .product-details-close:hover {
            background-color: rgba(255,255,255,0.2);
            transform: translateY(-50%) rotate(90deg);
        }

        .product-details-body {
            display: flex;
            flex: 1;
            overflow: hidden;
            min-height: 0;
        }

        .product-details-left {
            flex: 1;
            padding: 30px;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            min-height: 0;
        }

        .product-details-right {
            flex: 1;
            padding: 30px;
            background: #f8f9fa;
            overflow-y: auto;
            min-height: 0;
        }

        .product-image-large {
            width: 100%;
            height: 300px;
            object-fit: cover;
            border-radius: 15px;
            margin-bottom: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            flex-shrink: 0;
        }

        .product-info {
            flex: 1;
            min-height: 0;
            overflow-y: auto;
        }

        .product-price-large {
            font-size: 2.5em;
            font-weight: bold;
            color: #2c5f2d;
            margin-bottom: 20px;
        }

        .product-description-large {
            color: #666;
            line-height: 1.8;
            font-size: 1.1em;
            margin-bottom: 30px;
        }

        .product-specs {
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .product-specs h4 {
            color: #04221a;
            margin-bottom: 15px;
            font-size: 1.3em;
        }

        .spec-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }

        .spec-item:last-child {
            border-bottom: none;
        }

        .spec-label {
            font-weight: 600;
            color: #333;
        }

        .spec-value {
            color: #666;
        }
        
        .comments-section {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .comments-section h4 {
            color: #04221a;
            margin-bottom: 20px;
            font-size: 1.3em;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .comments-list {
            max-height: 350px;
            overflow-y: auto;
        }

        .comment-item {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 15px;
            border-left: 4px solid #04221a;
        }

        .comment-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .comment-author {
            font-weight: 600;
            color: #04221a;
        }

        .comment-date {
            font-size: 0.9em;
            color: #666;
        }

        .comment-rating {
            display: flex;
            gap: 2px;
            margin-bottom: 8px;
        }

        .comment-rating .star {
            color: #ffc107;
            font-size: 14px;
        }

        .comment-rating .star.empty {
            color: #ddd;
        }

        .comment-text {
            color: #333;
            line-height: 1.6;
        }

        .no-comments {
            text-align: center;
            color: #666;
            font-style: italic;
            padding: 40px 20px;
        }

        .product-actions {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            flex-shrink: 0;
        }

        .btn-details {
            background: linear-gradient(135deg, #04221a 0%, #2c5f2d 100%);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 25px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-details:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(4, 34, 26, 0.3);
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
            
            /* Responsive pour le popup de détails produit */
            .product-details-content {
                width: 98%;
                margin: 1% auto;
                height: 95vh;
            }
            
            .product-details-body {
                flex-direction: column;
            }
            
            .product-details-left,
            .product-details-right {
                flex: none;
                padding: 20px;
                min-height: 0;
                overflow-y: auto;
            }
            
            .product-image-large {
                height: 250px;
            }
            
            .product-price-large {
                font-size: 2em;
            }
            
            .product-actions {
                flex-direction: column;
                gap: 10px;
            }
            
            .btn-details {
                width: 100%;
                justify-content: center;
            }
            
            .comments-list {
                max-height: 250px;
            }
        }

        /* Comments Section Styles - Original Design */
        .comments-section {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .comments-section h4 {
            color: #04221a;
            margin-bottom: 20px;
            font-size: 1.3em;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .comments-list {
            max-height: 350px;
            overflow-y: auto;
        }

        .comment-item {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 15px;
            border-left: 4px solid #04221a;
        }

        .comment-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .comment-author {
            font-weight: 600;
            color: #04221a;
        }

        .comment-date {
            font-size: 0.9em;
            color: #666;
        }

        .comment-rating {
            display: flex;
            gap: 2px;
            margin-bottom: 8px;
        }

        .comment-rating .star {
            color: #ffc107;
            font-size: 14px;
        }

        .comment-rating .star.empty {
            color: #ddd;
        }

        .comment-text {
            color: #333;
            line-height: 1.6;
        }

        .no-comments {
            text-align: center;
            color: #666;
            font-style: italic;
            padding: 40px 20px;
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
            <span class="badge"><?=$panier['count']?></span>
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

        <?php if (!empty($panier['data'])): ?>
            <?php 
            $totalPanier = 0;
            foreach($panier['data'] as $paniers): 
                // Calcul du sous-total pour chaque article
                $sousTotal = ($paniers['prix'] ?? 0) * ($paniers['quantite'] ?? 1);
                $totalPanier += $sousTotal;
                
                if($paniers['type'] == 'produit'): ?>
                    <div class="whatsapp-popup-item">
                        <div class="whatsapp-popup-item-icon">
                            <i class="fas fa-headphones"></i>
                        </div>
                        <div class="whatsapp-popup-item-content">
                            <div class="whatsapp-popup-item-title"><?= htmlspecialchars($paniers['nom'] ?? '') ?></div>
                            <div class="whatsapp-popup-item-desc">
                                <?= number_format($paniers['prix'] ?? 0, 0, ',', ' ') ?> fbu x <?= (int)($paniers['quantite'] ?? 1) ?>
                            </div>
                        </div>
                        <div class="whatsapp-popup-item-time">
                            <?= number_format($sousTotal, 0, ',', ' ') ?> fbu
                        </div>
                    </div>
                <?php else: // Type formation ?>
                    <div class="whatsapp-popup-item">
                        <div class="whatsapp-popup-item-icon">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <div class="whatsapp-popup-item-content">
                            <div class="whatsapp-popup-item-title"><?= htmlspecialchars($paniers['titre'] ?? '') ?></div>
                            <div class="whatsapp-popup-item-desc">
                                <?= htmlspecialchars(mb_substr($paniers['description'] ?? '', 0, 30)) ?>...
                            </div>
                        </div>
                        <div class="whatsapp-popup-item-desc">
                            <?= number_format($sousTotal, 0, ',', ' ') ?> fbu
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
            
            <!-- Ligne du total -->
            <div class="whatsapp-popup-item" style="border-top: 1px solid #eee; padding-top: 10px; margin-top: 5px;">
                <div class="whatsapp-popup-item-content">
                    <div class="whatsapp-popup-item-title" style="font-weight: bold;">Total</div>
                </div>
                <div class="whatsapp-popup-item-time" style="font-weight: bold;">
                    <?= number_format($totalPanier, 0, ',', ' ') ?> fbu
                </div>
            </div>
            
        <?php else: ?>
            <div style="padding: 20px; text-align: center; color: #666;">
                <i class="fas fa-shopping-cart" style="font-size: 24px; margin-bottom: 10px; display: block;"></i>
                Votre panier est vide
            </div>
        <?php endif; ?>
        </div>
        <div class="whatsapp-popup-footer">
            <a href="#">Total: <?= number_format($totalPanier, 0, ',', ' ') ?> fbu | Voir le panier complet</a>
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
            <?php 
            // Débogage
            $user = $utilisateurController->getUtilisateur($_SESSION['user_id']);
            ?>  
            <?php if (isset($user['data']) && is_object($user['data'])): ?>
                <!-- Informations personnelles -->
                <div class="whatsapp-popup-item">
                    <div class="whatsapp-popup-item-icon" style="background-color: #e6f7ff;">
                        <i class="fas fa-user" style="color: #1890ff;"></i>
                    </div>
                    <div class="whatsapp-popup-item-content">
                        <div class="whatsapp-popup-item-title">
                            <?= htmlspecialchars($user['data']->getPrenom() ?? '') ?> 
                            <?= htmlspecialchars($user['data']->getNom() ?? '') ?>
                        </div>
                        <div class="whatsapp-popup-item-desc">
                            <?= htmlspecialchars($user['data']->getRole() ?? 'Utilisateur') ?>
                        </div>
                    </div>
                </div>

                <!-- Email -->
                <div class="whatsapp-popup-item">
                    <div class="whatsapp-popup-item-icon" style="background-color: #f6ffed;">
                        <i class="fas fa-envelope" style="color: #52c41a;"></i>
                    </div>
                    <div class="whatsapp-popup-item-content">
                        <div class="whatsapp-popup-item-title">
                            <?= htmlspecialchars($user['data']->getEmail() ?? '') ?>
                        </div>
                        <div class="whatsapp-popup-item-desc">
                            <?= method_exists($user['data'], 'isVerified') && $user['data']->isVerified() ? 'Email vérifié' : 'Email non vérifié' ?>
                        </div>
                    </div>
                </div>

                <!-- Date d'inscription -->
                <div class="whatsapp-popup-item">
                    <div class="whatsapp-popup-item-icon" style="background-color: #fff7e6;">
                        <i class="fas fa-calendar-alt" style="color: #fa8c16;"></i>
                    </div>
                    <div class="whatsapp-popup-item-content">
                        <div class="whatsapp-popup-item-title">
                            Membre depuis
                        </div>
                        <div class="whatsapp-popup-item-desc">
                            <?= date('d/m/Y', strtotime($user['data']->getDateCreation() ?? 'now')) ?>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <!-- Message d'erreur si les données ne sont pas chargées -->
                <div class="whatsapp-popup-item">
                    <div class="whatsapp-popup-item-content">
                        <div class="whatsapp-popup-item-desc" style="text-align: center; padding: 20px;">
                            <i class="fas fa-exclamation-triangle" style="color: #ff4d4f; font-size: 24px; display: block; margin-bottom: 10px;"></i>
                            Impossible de charger les informations du profil
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <div class="whatsapp-popup-footer">
            <a href="#">Modifier le profil</a>
        </div>
    </div>

    <!-- Message de notification -->
    <?php if (!empty($message)): ?>
    <div class="notification notification-<?= $message_type ?>" style="position: fixed; top: 20px; right: 20px; z-index: 10000; padding: 15px; border-radius: 5px; color: white; background: <?= $message_type === 'success' ? '#28a745' : '#dc3545' ?>;">
        <?= $message ?>
        <button onclick="this.parentElement.style.display='none'" style="background: none; border: none; color: white; margin-left: 10px; cursor: pointer;">×</button>
    </div>
    <?php endif; ?>

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
            <h1>NOS EQUIPEMENTS DISPONIBLES</h1>
        </div>
                
        <!-- Products Section -->
        <section class="reveal" id="features">
            <div class="grid">
                <?php if (!empty($produits)): ?>
                    <?php foreach ($produits as $produit): ?>
                        <article class="card" data-tilt>
                            <div class="image-container">
                                <img src="<?='../controle/'.$produit->getPhoto()?>" 
                                     alt="<?='Image de '.htmlspecialchars($produit->getNom())?>"
                                     onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KICA8cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZGRkIi8+CiAgPHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtc2l6ZT0iMTgiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIiBmaWxsPSIjNTU1Ij5JbWFnZSBub24gZGlzcG9uaWJsZTwvdGV4dD4KPC9zdmc+'">
                            </div>
                            
                            <div class="card-content">
                                <h3><?=htmlspecialchars($produit->getNom())?></h3>
                                <?php if (method_exists($produit, 'getPrix')): ?>
                                    <div class="price"><?=number_format($produit->getPrix(), 2, ',', ' ')?> €</div>
                                <?php endif; ?>
                                <p><?=htmlspecialchars($produit->getDescription())?></p>
                                
                                <div class="alignements_icones">
                                    <button class="icon fas fa-eye" 
                                            onclick="openProductDetails(<?=$produit->getIdProduit()?>)" 
                                            title="Voir détails">
                                    </button>
                                    <button class="icon fa fa-comment" 
                                            onclick="openCommentModal(<?=$produit->getIdProduit()?>)" 
                                            title="Commenter">
                                    </button>
                                    <form method="post" style="display: inline;">
                                        <input type="hidden" name="action" value="ajouter_panier">
                                        <input type="hidden" name="id_produit" value="<?=$produit->getIdProduit()?>">
                                        <button type="submit" class="icon fas fa-shopping-cart" title="Ajouter au panier">
                                        </button>
                                    </form>
                                    <form method="post" style="display: inline;">
                                        <input type="hidden" name="action" value="ajouter_favoris">
                                        <input type="hidden" name="id_produit" value="<?=$produit->getIdProduit()?>">
                                        <button type="submit" class="icon fas fa-star <?= in_array($produit->getIdProduit(), $_SESSION['favoris'] ?? []) ? 'favori-actif' : '' ?>" 
                                                title="<?= in_array($produit->getIdProduit(), $_SESSION['favoris'] ?? []) ? 'Retirer des favoris' : 'Ajouter aux favoris' ?>">
                                        </button>
                                    </form>
                                    <button class="icon fas fa-share" 
                                            onclick="openShareModal(<?=$produit->getIdProduit()?>, 'produit')" 
                                            title="Partager">
                                    </button>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="grid-column: 1 / -1; text-align: center; padding: 40px; background: white; border-radius: 15px;">
                        <h3 style="color: #666; margin-bottom: 20px;">Aucun produit disponible</h3>
                        <p style="color: #999;">Revenez plus tard pour découvrir nos nouveaux produits.</p>
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
                        <input type="hidden" name="action" value="ajouter_commentaire">
                        <input type="hidden" name="id_produit" id="comment_produit_id">
                        
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
                                          placeholder="Partagez votre expérience avec ce produit..." 
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
                    <h3 id="shareModalTitle"><i class="fas fa-share"></i> Partager ce produit</h3>
                    <span class="close" onclick="closeModal('shareModal')" title="Fermer">&times;</span>
                </div>
                <div class="modal-body">
                    <div class="plateformes-partage" style="display: flex; flex-wrap: wrap; justify-content: space-around; gap: 15px; margin-top: 20px;">
                        <button class="fab fa-facebook" style="font-size: 24px; cursor: pointer; padding: 15px; border-radius: 50%; transition: all 0.3s ease; border: none; background: none; color: #3b5998;" onclick="shareOnPlatform('facebook')" title="Facebook"></button>
                        <button class="fab fa-twitter" style="font-size: 24px; cursor: pointer; padding: 15px; border-radius: 50%; transition: all 0.3s ease; border: none; background: none; color: #1da1f2;" onclick="shareOnPlatform('twitter')" title="Twitter"></button>
                        <button class="fab fa-linkedin" style="font-size: 24px; cursor: pointer; padding: 15px; border-radius: 50%; transition: all 0.3s ease; border: none; background: none; color: #0077b5;" onclick="shareOnPlatform('linkedin')" title="LinkedIn"></button>
                        <button class="fab fa-whatsapp" style="font-size: 24px; cursor: pointer; padding: 15px; border-radius: 50%; transition: all 0.3s ease; border: none; background: none; color: #25d366;" onclick="shareOnPlatform('whatsapp')" title="WhatsApp"></button>
                        <button class="fas fa-envelope" style="font-size: 24px; cursor: pointer; padding: 15px; border-radius: 50%; transition: all 0.3s ease; border: none; background: none; color: #d44638;" onclick="shareOnPlatform('email')" title="Email"></button>
                        <button class="fas fa-link" style="font-size: 24px; cursor: pointer; padding: 15px; border-radius: 50%; transition: all 0.3s ease; border: none; background: none; color: #6c757d;" onclick="shareOnPlatform('lien')" title="Copier le lien"></button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Details Modal -->
        <div id="productDetailsModal" class="product-details-modal" role="dialog" aria-labelledby="productDetailsTitle" aria-hidden="true">
            <div class="product-details-content">
                <div class="product-details-header">
                    <h2 id="productDetailsTitle"><i class="fas fa-box"></i> Détails du produit</h2>
                    <span class="product-details-close" onclick="closeProductDetails()" title="Fermer">&times;</span>
                </div>
                
                <div class="product-details-body">
                    <div class="product-details-left">
                        <img id="productDetailsImage" class="product-image-large" src="" alt="Image du produit">
                        
                        <div class="product-info">
                            <div id="productDetailsPrice" class="product-price-large"></div>
                            <div id="productDetailsDescription" class="product-description-large"></div>
                            
                            <div class="product-specs">
                                <h4><i class="fas fa-info-circle"></i> Spécifications</h4>
                                <div id="productSpecs">
                                    <!-- Les spécifications seront chargées dynamiquement -->
                                </div>
                            </div>
                            
                            <div class="product-actions">
                                <form method="post" style="display: inline;">
                                    <input type="hidden" name="action" value="ajouter_panier">
                                    <input type="hidden" name="id_produit" id="details_produit_id">
                                    <button type="submit" class="btn-details">
                                        <i class="fas fa-shopping-cart"></i> Ajouter au panier
                                    </button>
                                </form>
                                <form method="post" style="display: inline;">
                                    <input type="hidden" name="action" value="ajouter_favoris">
                                    <input type="hidden" name="id_produit" id="details_favoris_id">
                                    <button type="submit" class="btn-details">
                                        <i class="fas fa-star"></i> Ajouter aux favoris
                                    </button>
                                </form>
                                <button class="btn-details" onclick="openShareModalFromDetails()">
                                    <i class="fas fa-share"></i> Partager
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="product-details-right">
                        <div class="comments-section">
                            <h4><i class="fas fa-comments"></i> Avis clients</h4>
                            <div id="productComments" class="comments-list" data-product-id="">
                                <!-- Les commentaires seront chargés dynamiquement -->
                            </div>
                        </div>
                    </div>
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
        let currentProductId = null;
        const ratingTexts = {
            1: "Très décevant 😞",
            2: "Pas terrible 😐", 
            3: "Correct 🙂",
            4: "Très bien 😊",
            5: "Excellent ! 🤩"
        };

        // Données des produits (simulées - à remplacer par des données réelles)
        const productsData = {
            <?php if (!empty($produits)): ?>
                <?php foreach ($produits as $produit): ?>
                    <?=$produit->getIdProduit()?>: {
                        id: <?=$produit->getIdProduit()?>,
                        nom: "<?=addslashes($produit->getNom())?>",
                        description: "<?=addslashes($produit->getDescription())?>",
                        prix: <?=method_exists($produit, 'getPrix') ? $produit->getPrix() : 0?>,
                        photo: "<?=addslashes($produit->getPhoto())?>",
                        specifications: {
                            "Marque": "<?=addslashes($produit->getNom())?>",
                            "Catégorie": "Équipement télécom",
                            "Garantie": "2 ans",
                            "Stock": "En stock"
                        }
                    },
                <?php endforeach; ?>
            <?php endif; ?>
        };

        // Fonction pour afficher les notifications
        function showNotification(message, type = 'info') {
            // Créer l'élément de notification
            const notification = document.createElement('div');
            notification.className = `notification notification-${type}`;
            notification.innerHTML = `
                <div class="notification-content">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
                    <span>${message}</span>
                </div>
                <button class="notification-close" onclick="this.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            `;
            
            // Ajouter les styles si pas déjà présents
            if (!document.querySelector('#notification-styles')) {
                const styles = document.createElement('style');
                styles.id = 'notification-styles';
                styles.textContent = `
                    .notification {
                        position: fixed;
                        top: 20px;
                        right: 20px;
                        background: white;
                        border-radius: 10px;
                        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
                        padding: 15px 20px;
                        display: flex;
                        align-items: center;
                        gap: 15px;
                        z-index: 10000;
                        min-width: 300px;
                        animation: slideInRight 0.3s ease;
                    }
                    
                    .notification-success {
                        border-left: 4px solid #28a745;
                    }
                    
                    .notification-error {
                        border-left: 4px solid #dc3545;
                    }
                    
                    .notification-info {
                        border-left: 4px solid #17a2b8;
                    }
                    
                    .notification-content {
                        display: flex;
                        align-items: center;
                        gap: 10px;
                        flex: 1;
                    }
                    
                    .notification-content i {
                        font-size: 18px;
                    }
                    
                    .notification-success .notification-content i {
                        color: #28a745;
                    }
                    
                    .notification-error .notification-content i {
                        color: #dc3545;
                    }
                    
                    .notification-info .notification-content i {
                        color: #17a2b8;
                    }
                    
                    .notification-close {
                        background: none;
                        border: none;
                        color: #666;
                        cursor: pointer;
                        padding: 5px;
                        border-radius: 50%;
                        transition: all 0.3s ease;
                    }
                    
                    .notification-close:hover {
                        background: #f0f0f0;
                        color: #333;
                    }
                    
                    @keyframes slideInRight {
                        from {
                            transform: translateX(100%);
                            opacity: 0;
                        }
                        to {
                            transform: translateX(0);
                            opacity: 1;
                        }
                    }
                `;
                document.head.appendChild(styles);
            }
            
            // Ajouter la notification au DOM
            document.body.appendChild(notification);
            
            // Supprimer automatiquement après 5 secondes
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.style.animation = 'slideInRight 0.3s ease reverse';
                    setTimeout(() => {
                        if (notification.parentNode) {
                            notification.remove();
                        }
                    }, 300);
                }
            }, 5000);
        }

        // Initialisation au chargement de la page
        document.addEventListener('DOMContentLoaded', function() {
            initializeStarRating();
            document.getElementById('year').textContent = new Date().getFullYear();
            
            // Masquer automatiquement les notifications après 5 secondes
            setTimeout(() => {
                const notification = document.querySelector('.notification');
                if (notification) {
                    notification.style.display = 'none';
                }
            }, 5000);
        });

        // Fonction pour ouvrir le popup de détails produit
        function openProductDetails(productId) {
            console.log('openProductDetails called with ID:', productId);
            
            if (!productId) {
                console.error('ID du produit manquant');
                alert('Erreur: ID du produit manquant');
                return;
            }
            
            currentProductId = productId;
            const product = productsData[productId];
            
            console.log('Product data:', product);
            console.log('All products data:', productsData);
            
            if (!product) {
                console.error('Produit non trouvé pour ID:', productId);
                alert('Erreur: Produit non trouvé');
                return;
            }
            
            // Remplir les informations du produit
            document.getElementById('productDetailsTitle').innerHTML = `<i class="fas fa-box"></i> ${product.nom}`;
            document.getElementById('productDetailsImage').src = `../controle/${product.photo}`;
            document.getElementById('productDetailsImage').alt = `Image de ${product.nom}`;
            document.getElementById('productDetailsPrice').textContent = `${product.prix.toFixed(2).replace('.', ',')} €`;
            document.getElementById('productDetailsDescription').textContent = product.description;
            document.getElementById('details_produit_id').value = productId;
            document.getElementById('details_favoris_id').value = productId;
            
            // Remplir les spécifications
            const specsContainer = document.getElementById('productSpecs');
            specsContainer.innerHTML = '';
            for (const [key, value] of Object.entries(product.specifications)) {
                const specItem = document.createElement('div');
                specItem.className = 'spec-item';
                specItem.innerHTML = `
                    <span class="spec-label">${key}:</span>
                    <span class="spec-value">${value}</span>
                `;
                specsContainer.appendChild(specItem);
            }
            
            // Mettre à jour l'ID du produit pour les commentaires
            document.getElementById('productComments').setAttribute('data-product-id', productId);
            
            // Charger les commentaires
            if (window.josnetFeatures && typeof window.josnetFeatures.loadProductComments === 'function') {
                window.josnetFeatures.loadProductComments(productId);
            } else {
                console.warn('JosNetFeatures not loaded yet, will try to load comments after initialization');
                // Attendre que JosNetFeatures soit chargé
                setTimeout(() => {
                    if (window.josnetFeatures && typeof window.josnetFeatures.loadProductComments === 'function') {
                        window.josnetFeatures.loadProductComments(productId);
                    } else {
                        console.error('JosNetFeatures still not available, loading comments manually');
                        // Charger les commentaires manuellement
                        loadCommentsManually(productId);
                    }
                }, 500);
            }
            
            // Afficher le modal
            const modal = document.getElementById('productDetailsModal');
            console.log('Modal element:', modal);
            
            if (!modal) {
                console.error('Modal element not found!');
                alert('Erreur: Modal non trouvée');
                return;
            }
            
            modal.style.display = 'flex';
            console.log('Modal display set to flex');
            
            // Animation d'ouverture
            setTimeout(() => {
                modal.classList.add('show');
                console.log('Show class added to modal');
            }, 10);
            
            modal.setAttribute('aria-hidden', 'false');
        }

        // Fonction pour charger les commentaires manuellement
        async function loadCommentsManually(productId) {
            const commentsContainer = document.getElementById('productComments');
            if (!commentsContainer) return;
            
            commentsContainer.innerHTML = '<p class="no-comments">Chargement des commentaires...</p>';
            
            try {
                const formData = new FormData();
                formData.append('action', 'get_product_comments');
                formData.append('id_produit', productId);
                
                const response = await fetch('../controle/api.php', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    displayProductCommentsManually(result.data);
                } else {
                    commentsContainer.innerHTML = '<p class="no-comments">Erreur lors du chargement des commentaires.</p>';
                }
            } catch (error) {
                console.error('Erreur lors du chargement des commentaires:', error);
                commentsContainer.innerHTML = '<p class="no-comments">Erreur lors du chargement des commentaires.</p>';
            }
        }
        
        // Fonction pour afficher les commentaires manuellement
        function displayProductCommentsManually(data) {
            const container = document.getElementById('productComments');
            if (!container) return;

            let html = '';
            
            // Afficher la note moyenne si disponible
            if (data.note_moyenne > 0) {
                const stars = generateStarsManually(Math.round(data.note_moyenne));
                html += `
                    <div class="average-rating">
                        <div class="rating-stars">${stars}</div>
                        <span class="rating-text">${data.note_moyenne}/5 (${data.total} avis)</span>
                    </div>
                `;
            }
            
            if (data.commentaires && data.commentaires.length > 0) {
                data.commentaires.forEach(comment => {
                    const stars = generateStarsManually(comment.note);
                    const date = new Date(comment.date).toLocaleDateString('fr-FR');
                    const userPhoto = comment.utilisateur.photo ? 
                        `<img src="../controle/uploads/utilisateurs/${comment.utilisateur.photo}" alt="Photo utilisateur" class="user-avatar">` :
                        `<div class="user-avatar-placeholder"><i class="fas fa-user"></i></div>`;
                    
                    html += `
                        <div class="comment-item" data-comment-id="${comment.id}">
                            <div class="comment-header">
                                <div class="user-info">
                                    ${userPhoto}
                                    <div class="user-details">
                                        <span class="comment-author">${comment.utilisateur.prenom} ${comment.utilisateur.nom}</span>
                                        <span class="comment-date">${date}</span>
                                    </div>
                                </div>
                                ${comment.note ? `<div class="comment-rating">${stars}</div>` : ''}
                            </div>
                            <div class="comment-content">${comment.commentaire}</div>
                        </div>
                    `;
                });
            } else {
                html += '<p class="no-comments">Aucun avis pour ce produit.</p>';
            }
            
            container.innerHTML = html;
        }
        
        // Fonction pour générer les étoiles manuellement
        function generateStarsManually(rating) {
            if (!rating) return '';
            
            let stars = '';
            for (let i = 1; i <= 5; i++) {
                stars += `<i class="fas fa-star ${i <= rating ? 'active' : ''}"></i>`;
            }
            return stars;
        }

        // Fonction pour fermer le popup de détails produit
        function closeProductDetails() {
            const modal = document.getElementById('productDetailsModal');
            if (modal) {
                modal.classList.remove('show');
                setTimeout(() => {
                    modal.style.display = 'none';
                }, 300);
                modal.setAttribute('aria-hidden', 'true');
                currentProductId = null;
            }
        }

        // Fonction pour charger les commentaires d'un produit
        function loadProductComments(productId) {
            const commentsContainer = document.getElementById('productComments');
            
            // Pour cette version sans API, on simule des commentaires
            commentsContainer.innerHTML = `
                <div class="comment-item">
                    <div class="comment-header">
                        <span class="comment-author">Utilisateur #123</span>
                        <span class="comment-date">15 juin 2023</span>
                    </div>
                    <div class="comment-rating">
                        <span class="star">★</span>
                        <span class="star">★</span>
                        <span class="star">★</span>
                        <span class="star">★</span>
                        <span class="star empty">★</span>
                    </div>
                    <div class="comment-text">Produit de très bonne qualité, je recommande!</div>
                </div>
                <div class="comment-item">
                    <div class="comment-header">
                        <span class="comment-author">Utilisateur #456</span>
                        <span class="comment-date">10 juin 2023</span>
                    </div>
                    <div class="comment-rating">
                        <span class="star">★</span>
                        <span class="star">★</span>
                        <span class="star">★</span>
                        <span class="star empty">★</span>
                        <span class="star empty">★</span>
                    </div>
                    <div class="comment-text">Bon produit mais livraison un peu longue.</div>
                </div>
                <style>
                    /* Profile toggle JavaScript */
                    .profile-dropdown.show {
                        opacity: 1 !important;
                        visibility: visible !important;
                        transform: translateY(0) !important;
                    }
                </style>
                <head>
                    <body>
                        <!-- Messages d'alerte -->
                        <?php if (!empty($message)): ?>
                            <div class="alert alert-<?= $message_type === 'success' ? 'success' : 'danger' ?>" style="position: fixed; top: 10px; left: 50%; transform: translateX(-50%); z-index: 9999; padding: 15px 20px; border-radius: 8px; margin: 0;">
                                <?= htmlspecialchars($message) ?>
                                <button type="button" class="btn-close" onclick="this.parentElement.style.display='none'">×</button>
                            </div>
                        <?php endif; ?>

                        <!-- User Profile Dropdown -->
                        <?php if (isUserLoggedIn()): ?>
                            <div class="user-profile-dropdown">
                                <button class="profile-toggle" onclick="toggleProfileDropdown()">
                                    <div class="profile-avatar">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div>
                                        <div style="font-weight: 600; font-size: 14px;"><?= htmlspecialchars(getUserDisplayName()) ?></div>
                                        <div style="font-size: 12px; color: #666;"><?= htmlspecialchars(getUserRole()) ?></div>
                                    </div>
                                    <i class="fas fa-chevron-down" style="margin-left: auto;"></i>
                                </button>
                                <div class="profile-dropdown" id="profileDropdown">
                                    <div class="dropdown-header">
                                        <strong><?= htmlspecialchars(getUserDisplayName()) ?></strong>
                                        <br><small style="color: #666;"><?= htmlspecialchars(getCurrentClientUser()['email']) ?></small>
                                    </div>
                                    <a href="profile.php" class="dropdown-item">
                                        <i class="fas fa-user"></i>
                                        Mon Profil
                                    </a>
                                    <a href="mes_commandes.php" class="dropdown-item">
                                        <i class="fas fa-shopping-cart"></i>
                                        Mes Commandes
                                    </a>
                                    <?php if (getCurrentClientUser()['role'] === 'admin'): ?>
                                    <a href="../Admin/dashboard.php" class="dropdown-item">
                                        <i class="fas fa-tachometer-alt"></i>
                                        Administration
                                    </a>
                                    <?php endif; ?>
                                    <a href="logout_client.php" class="dropdown-item logout" onclick="return confirm('Êtes-vous sûr de vouloir vous déconnecter ?')">
                                        <i class="fas fa-sign-out-alt"></i>
                                        Se déconnecter
                                    </a>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="user-profile-dropdown">
                                <a href="connexion.php" class="profile-toggle" style="text-decoration: none; color: inherit;">
                                    <div class="profile-avatar">
                                        <i class="fas fa-sign-in-alt"></i>
                                    </div>
                                    <div>
                                        <div style="font-weight: 600; font-size: 14px;">Se connecter</div>
                                        <div style="font-size: 12px; color: #666;">Visiteur</div>
                                    </div>
                                </a>
                            </div>
                        <?php endif; ?>

                        <!-- User Message Icons -->
                        <div class="user_message">
                            <a href="#" onclick="openShareModal()" title="Partager">
                                <i class="fas fa-share-alt"></i>
                            </a>
                            <a href="#" onclick="toggleFavorites()" title="Favoris">
                                <i class="fas fa-heart"></i>
                                <?php if (isset($_SESSION['favoris']) && count($_SESSION['favoris']) > 0): ?>
                                    <span class="badge"><?= count($_SESSION['favoris']) ?></span>
                                <?php endif; ?>
                            </a>
                            <a href="#" onclick="toggleCart()" title="Panier">
                                <i class="fas fa-shopping-cart"></i>
                                <?php if (isset($_SESSION['panier']) && count($_SESSION['panier']) > 0): ?>
                                    <span class="badge"><?= count($_SESSION['panier']) ?></span>
                                <?php endif; ?>
                            </a>
                        </div>
            `;
        }

        // Fonctions pour les actions depuis le popup de détails
        function openShareModalFromDetails() {
            if (currentProductId) {
                openShareModal(currentProductId, 'produit');
            }
        }

        // Fonction pour ouvrir la modale de commentaire
        function openCommentModal(produitId) {
            if (!produitId) {
                alert('Erreur: ID du produit manquant');
                return;
            }
            
            document.getElementById('comment_produit_id').value = produitId;
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

        // Fonction pour afficher les alertes
        function showAlert(message, type) {
            // Créer une alerte simple
            alert(message);
        }

        // Fonction pour ouvrir la modale de partage
        function openShareModal(id, type) {
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
                    // Copier le lien dans le presse-papiers
                    navigator.clipboard.writeText(window.location.href)
                        .then(() => {
                            alert('Lien copié dans le presse-papiers !');
                        })
                        .catch(err => {
                            alert('Erreur lors de la copie du lien');
                        });
                    return;
                default:
                    message = 'Partagé avec succès !';
            }
            
            alert(message);
            closeModal('shareModal');
        }

        // Fonction pour toggle le dropdown de profil
        function toggleProfileDropdown() {
            const dropdown = document.getElementById('profileDropdown');
            dropdown.classList.toggle('show');
        }

        // Fermer le dropdown si on clique ailleurs
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('profileDropdown');
            const toggle = document.querySelector('.profile-toggle');
            
            if (dropdown && !toggle.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.classList.remove('show');
            }
        });

        // Fonction pour confirmer la déconnexion
        function confirmLogout() {
            if (confirm('Êtes-vous sûr de vouloir vous déconnecter ?')) {
                window.location.href = 'logout_client.php';
            }
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
            
            // Gestion du popup de détails produit
            const productDetailsModal = document.getElementById('productDetailsModal');
            if (event.target === productDetailsModal) {
                closeProductDetails();
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
                
                // Fermer le popup de détails produit
                const productDetailsModal = document.getElementById('productDetailsModal');
                if (productDetailsModal && productDetailsModal.style.display === 'block') {
                    closeProductDetails();
                }
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
                // Fermer les popups WhatsApp
                document.querySelectorAll('.whatsapp-popup').forEach(popup => {
                    popup.style.display = 'none';
                });
                
                activePopup = null;
            }
        });

        // Réinitialiser le formulaire
        function resetForm() {
            document.getElementById('commentForm').reset();
            currentRating = 0;
            resetStars();
            document.getElementById('ratingText').textContent = 'Cliquez sur les étoiles pour noter';
            document.getElementById('charCount').textContent = '0';
            document.querySelector('.char-counter').classList.remove('warning');
        }
    </script>
    <script src="javascript/features.js"></script>
</body>
</html>