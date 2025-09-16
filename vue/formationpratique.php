<?php include_once("composants/head.php") ?>
<?php include_once("../controle/controleur_formation.php") ?>

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
      <a href="services.html"><button class="btn">Services</button></a>
      <a href="contact.html"><button class="btn">Contact</button></a>  
    </nav>
  <!-- navigateur fin-->
  </header>
  <!-- service titre-->
  <div class="nom_service">
    <h1> FORMATIONS PRATIQUES </h1>
  </div>

 
  <!-- FEATURES GRID -->
  <section class="reveal" id="features">
    <div class="grid">
    <?php 
        $ObjetFormation = new FormationController();
        $formation = $ObjetFormation->getAllFormations();
        $list_formation = $formation["data"];
        if(!empty($list_formation)) { 
        foreach($list_formation as $form) { 
    ?> 
        <article class="card" data-tilt>
          <img src="../controle/<?= $form->getPhoto()?>" alt="">
          <h3><?=$form->getTitre()?></h3>
          <p><?= $form->getDescription()?></h3>
          <div class="alignements_icones" data-idformation="<?= $form->getIdFormation()?>">
            <span onclick="showCommentList(<?= $form->getIdFormation()?>)"><i class="icon fa fa-comment"></i></span>
            <span onclick="addToCart(<?= $form->getIdFormation()?>, 'formation')"><i class="icon fas fa-shopping-cart"></i></span>
            <span onclick="addToFavorites(<?= $form->getIdFormation()?>, 'formation')"><i class="icon fas fa-star"></i></span>
            <span><i class="icon fas fa-share"></i></span>
          </div>
        </article> 

      <?php 
          }} else{ 
      ?>
        <article class="card" data-tilt>
          <img src="../vue/images/windows.jpg" alt=""> 
          <h3>Aucune formation trouvée</h3> 
        </article> 
      <?php
      }
      ?>
    </div>
  </section>
   
<!-- le modal(pop up des commentaire) -->
<?php include_once("composants/conteneur-d-commentaire.php") ?>

<script>
  // Fonction pour ajouter un élément au panier (produit ou formation)
  function addToCart(id, type) {
    // Préparer les données à envoyer
    const data = {
      action: 'add_to_cart',
      id_element: id,
      type: type
    };
    
    // Si c'est un produit, on ajoute la quantité (1 par défaut)
    if (type === 'produit') {
      data.quantite = 1; // Vous pouvez permettre à l'utilisateur de modifier cette valeur
    }
    
    // Afficher un indicateur de chargement
    const loadingMessage = type === 'formation' 
      ? 'Ajout de la formation au panier...' 
      : 'Ajout du produit au panier...';
    
    // Envoyer la requête AJAX
    $.ajax({
      type: 'POST',
      url: '../controle/index.php',
      data: data,
      dataType: 'json',
      beforeSend: function() {
        // Afficher un indicateur de chargement
        alert(loadingMessage);
      },
      success: function(response) {
        if (response.success) {
          const successMessage = type === 'formation' 
            ? 'Formation ajoutée au panier avec succès !' 
            : 'Produit ajouté au panier avec succès !';
          
          // Mettre à jour le compteur du panier
          updateCartCount();
          
          // Afficher un message de succès
          alert(successMessage);
          
          // Rafraîchir la page pour mettre à jour l'affichage si nécessaire
          // window.location.reload();
        } else {
          // Afficher le message d'erreur du serveur
          alert('Erreur: ' + (response.message || 'Action impossible'));
        }
      },
      error: function(xhr, status, error) {
        console.error('Erreur AJAX:', error);
        alert('Une erreur est survenue lors de la communication avec le serveur');
      }
    });
  }

  // Fonction pour ajouter une formation aux favoris
  function addToFavorites(id, type) {
    $.ajax({
      type: 'POST',
      url: '../controle/index.php',
      data: {
        action: 'add_to_favorites',
        id_element: id,
        type: type
      },
      dataType: 'json',
      success: function(response) {
        if (response.success) {
          alert('Formation ajoutée aux favoris avec succès!');
        } else {
          alert('Erreur: ' + response.message);
        }
      },
      error: function(xhr, status, error) {
        console.error('Erreur AJAX:', error);
        alert('Une erreur est survenue lors de l\'ajout aux favoris');
      }
    });
  }

  // Fonction pour mettre à jour le compteur du panier
  function updateCartCount() {
    $.ajax({
      type: 'POST',
      url: '../controle/index.php',
      data: { action: 'get_cart_count' },
      dataType: 'json',
      success: function(response) {
        if (response.success) {
          // Mettre à jour l'affichage du compteur du panier
          // Vous devrez peut-être ajuster ce sélecteur selon votre structure HTML
          $('.cart-count').text(response.count);
        }
      }
    });
  }

  function hideModal() {
    let modal = document.querySelector(".modal-container");
    modal.classList.add("d-none")
  }

  function showCommentList(id_produit) {  
    $.ajax({
      type:'GET',
      data:{id_cible:id_produit,type:"formation"},
      url:"./composants/back-modal-commentaire.php",
      success:function(response) {  
      $.ajax({
			type:'GET',
			data:{id_cible:id_produit,type:"formation"},
			url:"./composants/back-modal-commentaire.php",
			success:function(response) {  
        document.getElementById("contener_comment").innerHTML = response;
        let modal = document.querySelector(".modal-container");
		    modal.classList.remove("d-none")
        console.log(response);
			},
			error:function(xhr) {
				console.error("une erreur est survenu:"+xhr.status+" "+xhr.message);
			}, 
		})
	}

  function enregistrecommentaire() { 
      let form = document.forms.comment;
      const formdata = new FormData(form);  

      $.ajax({
        url:"../controle/index.php",
        data:formdata,
        type:"POST",
        processData:false,
        contentType:false,
        success : function(response) { 
          console.log(response);
          try{  
            let rs = JSON.parse(response);
            if(rs.success) {
              document.querySelector("#messages").innerHTML = ('<span class="text-success" style="color:green">Commentaire enregistree avec success</span>'); 
              form.reset();
              setTimeout(() => { location.reload(); }, 4000);
            }
            else {
              document.querySelector("#messages").innerHTML = ('<span class="text-danger" style="color:red">'+rs.message+':'+rs.errors+'</span>');  
            }
          }catch(e){
            document.querySelector("#messages").innerHTML = ("<span class='text-danger' >Une erreur est survenu,merci de resaayer plus tard</span>"); 
            console.log("detail de l'erreur:"+response);
          }
        },
        error:function(xhr) {
          console.log(xhr.status);
        } 
      }) 
  }

</script>
<?php include_once("composants/footer.php") ?>