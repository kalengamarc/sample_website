<!doctype html>
<html lang="fr">
<head>
  <?php
    include_once('../controle/controleur_formation.php');
    $formation = new FormationController();
    $listeFormation = $formation->getAllFormations();
    print_r($listeFormation);
    ?>
  ?>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>Produits</title>
<link rel="stylesheet" href="styles/style.css" />
<link rel="stylesheet" href="../vue/font-awesome/css/all.min.css">
</head>
<body>
<div class="user_message">
  <a href=""><div class="panier" data-tooltip="panier"><i class=" icon fas fa-shopping-cart" ></i></div></a>
  <a href=""><div class="notification" data-tooltip="notification"><i class=" icon fas fa-bell" ></i></div></a>
  <a href=""> <div class="profil" data-tooltip="Profil"><i class=" icon fas fa-user" ></i></div></a>
</div>
</div>
<canvas id="bg-canvas"></canvas>

<div class="wrap">
  <header>
    <!-- logo et les textes -->
    <div class="brand">
      <div class="logo" aria-hidden="true">
        <a href="index.html">
             <!-- simple SVG telecom-ish logo -->
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden>
                <path d="M4 12c2-4 8-8 12-4" stroke="#04221a" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <circle cx="12" cy="12" r="4" fill="#04221a" />
                <path d="M20 4l-4 4" stroke="#fff" stroke-opacity="0.06" stroke-width="1.2" />
                </svg>
        <!-- fin dessin logo -->
        </a>
       
      </div>
      <div>
        <h1>JosNet</h1>
        <p>Vente Télécom • Dev • Formation • Coaching</p>
      </div>
    </div>
  <!-- fin du logo et les textes ensemble -->
<!-- navigateur debut-->
    <nav class="nav">
      <a href="produits.html"><button class="btn">Produits</button></a>
      <a href="services.php"><button class="btn">Services</button></a>
      <a href="contact.html"><button class="btn">Contact</button></a>  
    </nav>
  <!-- navigateur fin-->
  </header>
<!-- service titre-->
  <div class="nom_produit">
    <h1> NOS SERVICES DISPONIBLES </h1>
  </div>
  <!-- FEATURES GRID -->
  <section class="reveal" id="features">
    <div class="grid">
      <?php
      foreach($listeFormation['data'] as $formation){
      ?>
        <article class="card" data-tilt>
          <img src="<?='../controle/'.$formation->getPhoto()?>" alt="">
          <h3><?=substr($formation->getTitre(), 0, 50) ?></h3>
          <p> <?=substr($formation->getDescription(), 0, 100) ?> . . .</p>
          <div class="alignements_icones">
            <i class=" icon fa fa-comment"></i>
            <i class=" icon fas fa-shopping-cart" ></i>
            <i class=" icon fas fa-star"></i>
            <i class="icon fas fa-share"></i>
          </div>
      </article> 
        <?php }?>
      <!-- fin card-->
    </div>
  </section>

  <footer class="reveal">
    <small>&copy; <span id="year"></span> TermaDevs — Construit pour l'avenir </small>
  </footer>
  <script src="javascript/imageAnimation.js"></script>
</div>
</body>
</html>
