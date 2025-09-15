<?php
// Inclure la gestion de session client
require_once __DIR__ . '/../session_client.php';
$currentUser = getCurrentClientUser();
$isLoggedIn = isUserLoggedIn();
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary" href="produits.php">
            <i class="fas fa-bolt me-2"></i>JosNet
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="produits.php">
                        <i class="fas fa-shopping-bag me-1"></i>Produits
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="services.php">
                        <i class="fas fa-cogs me-1"></i>Services
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="formationpratique.php">
                        <i class="fas fa-graduation-cap me-1"></i>Formations
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="contact.php">
                        <i class="fas fa-envelope me-1"></i>Contact
                    </a>
                </li>
            </ul>
            
            <ul class="navbar-nav">
                <?php if ($isLoggedIn): ?>
                    <!-- Utilisateur connecté -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <div class="user-avatar me-2">
                                <i class="fas fa-user-circle fs-4 text-primary"></i>
                            </div>
                            <div class="user-info d-none d-md-block">
                                <small class="d-block text-muted mb-0"><?= htmlspecialchars(getUserRole()) ?></small>
                                <span class="fw-semibold"><?= htmlspecialchars(getUserDisplayName()) ?></span>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <div class="dropdown-header">
                                    <strong><?= htmlspecialchars(getUserDisplayName()) ?></strong>
                                    <br><small class="text-muted"><?= htmlspecialchars($currentUser['email']) ?></small>
                                </div>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="profile.php">
                                    <i class="fas fa-user me-2"></i>Mon Profil
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="mes_commandes.php">
                                    <i class="fas fa-shopping-cart me-2"></i>Mes Commandes
                                </a>
                            </li>
                            <?php if ($currentUser['role'] === 'admin'): ?>
                            <li>
                                <a class="dropdown-item" href="../Admin/dashboard.php">
                                    <i class="fas fa-tachometer-alt me-2"></i>Administration
                                </a>
                            </li>
                            <?php endif; ?>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger" href="logout_client.php" onclick="return confirm('Êtes-vous sûr de vouloir vous déconnecter ?')">
                                    <i class="fas fa-sign-out-alt me-2"></i>Se déconnecter
                                </a>
                            </li>
                        </ul>
                    </li>
                    
                    <!-- Panier -->
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="panier.php">
                            <i class="fas fa-shopping-cart"></i>
                            <?php if (isset($_SESSION['panier']) && count($_SESSION['panier']) > 0): ?>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    <?= count($_SESSION['panier']) ?>
                                </span>
                            <?php endif; ?>
                        </a>
                    </li>
                <?php else: ?>
                    <!-- Utilisateur non connecté -->
                    <li class="nav-item">
                        <a class="nav-link" href="register.php">
                            <i class="fas fa-user-plus me-1"></i>S'inscrire
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-primary text-white px-3 ms-2" href="connexion.php">
                            <i class="fas fa-sign-in-alt me-1"></i>Se connecter
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<style>
.user-avatar {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.user-info {
    line-height: 1.2;
}

.navbar-nav .dropdown-menu {
    min-width: 250px;
}

.dropdown-header {
    padding: 0.5rem 1rem;
    margin-bottom: 0;
    font-size: 0.875rem;
    color: #6c757d;
    white-space: nowrap;
}

.nav-link.btn {
    border-radius: 20px;
    transition: all 0.3s ease;
}

.nav-link.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,123,255,0.3);
}

@media (max-width: 768px) {
    .user-info {
        display: none !important;
    }
    
    .navbar-nav .dropdown-menu {
        min-width: 200px;
    }
}
</style>
