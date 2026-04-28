<div class="alert alert-light" role="alert" id="panier">Votre panier :</div>
<div id="produits">
<?php
foreach( $lesProduitsDuPanier as $unProduit) 
{
	// récupération des données d'un produit
	$id = $unProduit->id;
	$description = $unProduit->description;
	$image = $unProduit->image;
	$prix = $unProduit->prix;
	// affichage
	?>
	<div id="card">
	<div>
	<div class="photoCard"><img src="<?= $image ?>" alt="image descriptive" /></div>
	<div class="descrCard"><?= $description ?></div>
	<div class="prixCard"><?= $prix."€" ?></div>
	<div class="qteCard">
		Quantité : <input type="number" name="qte" value="<?= $_SESSION['produits'][$id] ?>" 
       onchange="window.location.href='index.php?uc=gererPanier&action=modifier&produit=<?= $id ?>&qte='+this.value" style="width: 60px;">
	</div>
	</div>
	<div class="imgCard"><a class="btn-suppr" href="index.php?uc=gererPanier&action=retirerDuPanier&produit=<?php echo $id ?>" 
   onclick="return confirm('Voulez-vous vraiment retirer cet article ?');">
   <img src="assets/images/retirerpanier.png" title="Retirer du panier" alt="Panier">
</a></div>
	</div>
	<?php
}
?>
</div>
<div class="contenuCentre">
<?php if (isset($_SESSION['idClient'])): ?>
<a href="index.php?uc=gererPanier&action=passerCommande"><button type="button" class="btn btn-primary">Commander</button></a>
<?php else: ?>
<p class="text-danger fw-bold">Veuillez vous connecter pour passer commande.</p>
<?php endif; ?>
</div>
