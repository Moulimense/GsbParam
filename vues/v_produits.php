<?php
if (isset($titre)) 
	echo "<h2>" . $titre . "</h2>";
else 
	echo "<h2>Nos produits</h2>";?>
<div id="produits">
<?php
// parcours du tableau contenant les produits à afficher
foreach( $lesProduits as $unProduit) 
{ 	// récupération des informations du produit
	$id = $unProduit->id;
	$description = $unProduit->description;
	$image = $unProduit->image;
	$prix = $unProduit->prix;
	// affichage d'un produit avec ses informations
	?>	
	<div id="card">
			<div>
			<div class="photoCard"><img src="<?= $image ?>" alt=image /></div>
			<div class="descrCard"><?= $description ?></div>
			<div class="prixCard"><?= $prix."€" ?></div>
			</div>
			<div class="imgCard">
				<a href="index.php?uc=voirProduits&action=voirDetails&produit=<?= $id ?>" class="btn btn-outline-info btn-sm m-1">En savoir plus</a>
				<a href="index.php?uc=gererPanier&produit=<?= $id ?>&action=ajouterAuPanier"> 
				<img src="assets/images/mettrepanier.png" title="Ajouter au panier" alt="Mettre au panier"> </a>
			</div>
			<?php if(isset($_SESSION['admin'])): ?>
			<div class="admin-actions mt-2 text-center">
				<a href="index.php?uc=administrer&action=modifier&produit=<?= $id ?>" class="btn btn-sm btn-warning mb-1" title="Modifier">📝 Modifier</a>
				<a href="index.php?uc=administrer&action=supprimer&produit=<?= $id ?>" class="btn btn-sm btn-danger mb-1" onclick="return confirm('Supprimer ce produit ?');" title="Supprimer">🗑️ Supprimer</a>
			</div>
			<?php endif; ?>
			
	</div>
<?php			
} // fin du foreach qui parcourt les produits
?>
</div>
