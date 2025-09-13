
        <?php

            include_once("../../controle/controleur_commentaire.php");  
            if(isset($_GET["id_cible"]) && !empty($_GET["id_cible"]) &&
            isset($_GET["type"]) && !empty($_GET["type"])) {
                $id_cible = intval($_GET["id_cible"]);
                $type = intval($_GET["type"]);
                $objetComment = new CommentaireController();
                $list_commentaire = $objetComment->getCommentairesByProduit($id_cible); 
        ?>
             

            <div class="comment-list py-2 text-black">
				<ul class="">
                <?php
                    if(!empty($list_commentaire["data"])) {
                        foreach($list_commentaire["data"] as $comment) {
                ?>
                    <li class="gap-3 mt-3 d-flex">
						<div class="img"></div>
						<p><?= $comment["commentaire"]?></p>
					</li> 
                <?php
                        }
                    }else{ ?>
                        <li class="gap-3 mt-3 d-flex"> 
                            <p>Aucun commentaire trouvé</p>
                        </li>
                <?php
                    }
                ?> 
				</ul>
			</div>

			<hr class="border">

			<div class="comment-add">
				<form action="" style="border:none;box-shadow:none;width:100%" style="overflow-y:scroll;" onsubmit="return false" name="comment">
                    <div id="messages" class=""></div>
					<div class=""> 
						<textarea name="commentaire" id="" class="form-control" rows="3" placeholder="comment..."></textarea>
					</div>
                    <input type="hidden" name="id_utilisateur" value="2">
                    <input type="hidden" name="id_formation" value="<?=$id_cible?>">
                    <input type="hidden" name="id_produit" value="">
                    
                    <input type="hidden" name="parent_id" value="">
                    <input type="hidden" name="note" value=""> 

                    <input type="hidden" name="do" value="ajout_commentaire">
					<div class="mt-3">
						<button type="submit" class="border btn btn-success" onclick="enregistrecommentaire()">Commenter</button>
					</div>
				</form>
			</div>

        <?php } ?>