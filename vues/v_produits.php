<?php
if (isset($titre)) 
	echo "<h2>" . $titre . "</h2>";
else 
	echo "<h2>Nos produits</h2>";

if (isset($messageSucces) && $messageSucces): ?>
    <div class="alert alert-success alert-dismissible fade show container mt-2" role="alert">
        <i class="bi bi-check-circle-fill"></i> <?= htmlspecialchars($messageSucces) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (isset($produitsEnPromotion) && !empty($produitsEnPromotion)): ?>
<div class="container mt-2 mb-4">
    <h3 class="text-center mb-4">⭐ Produits Mis en Avant</h3>
    <div class="row row-cols-1 row-cols-md-3 g-4">
        <?php foreach ($produitsEnPromotion as $unProduit): ?>
            <div class="col">
                <div class="card h-100 shadow-sm border-primary">
                    <img src="<?= htmlspecialchars($unProduit->image) ?>" class="card-img-top p-3" alt="<?= htmlspecialchars($unProduit->description) ?>" style="height: 200px; object-fit: contain;">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?= htmlspecialchars($unProduit->description) ?></h5>
                        <p class="card-text text-muted">
                            <?php if (!empty($unProduit->marque)): ?>
                                <span class="badge bg-secondary"><?= htmlspecialchars($unProduit->marque) ?></span>
                            <?php endif; ?>
                        </p>
                        <div class="mt-auto d-flex justify-content-between align-items-center">
                            <span class="fs-5 fw-bold text-primary"><?= number_format($unProduit->prix, 2, ',', ' ') ?> €</span>
                            <a href="index.php?uc=voirProduits&action=voirDetails&produit=<?= $unProduit->id ?>" target="_blank" class="btn btn-outline-primary btn-sm">En savoir plus</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

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
				<a href="index.php?uc=voirProduits&action=voirDetails&produit=<?= $id ?>" target="_blank" class="btn btn-outline-info btn-sm m-1">En savoir plus</a>
				<?php if (isset($_SESSION['idClient'])): ?>
				<a href="index.php?uc=gererPanier&produit=<?= $id ?>&action=ajouterAuPanier"> 
				<img src="assets/images/mettrepanier.png" title="Ajouter au panier" alt="Mettre au panier"> </a>
				<?php endif; ?>
			</div>

	</div>
<?php			
} // fin du foreach qui parcourt les produits
?>
</div>
