<div class="container mt-4">
    <div class="row">
        <!-- Image Section -->
        <div class="col-md-5 text-center">
            <img src="<?= $unProduit->image ?>" class="img-fluid rounded shadow" alt="<?= $unProduit->description ?>" style="max-height: 400px; object-fit: contain;">
        </div>

        <!-- Details Section -->
        <div class="col-md-7">
            <h2 class="mb-3"><?= $unProduit->description ?></h2>
            
            <div class="mb-3">
                <span class="badge bg-primary text-white p-2" style="font-size: 1.1em;"><?= $unProduit->marque ?></span>
                <span class="badge bg-secondary text-white p-2 ms-2" style="font-size: 1.1em;">Contenance: <?= $unProduit->contenance ?></span>
            </div>

            <div class="mb-4">
                <h3 class="text-success fw-bold"><?= number_format($unProduit->prix, 2) ?> €</h3>
                <div class="d-flex align-items-center mt-2">
                    <span class="text-warning me-2" style="font-size: 1.2em;">
                        <?php 
                        $stars = round($unProduit->noteClient);
                        for($i=1; $i<=5; $i++) {
                            if($i <= $stars) echo "★";
                            else echo "☆";
                        }
                        ?>
                    </span>
                    <span class="text-muted">(Note client: <?= $unProduit->noteClient ?>/5)</span>
                </div>
            </div>

            <div class="mb-4">
                <?php if($unProduit->stock > 0): ?>
                    <span class="text-success fw-bold"><i class="bi bi-check-circle"></i> En stock (<?= $unProduit->stock ?> disponibles)</span>
                <?php else: ?>
                    <span class="text-danger fw-bold"><i class="bi bi-x-circle"></i> Rupture de stock</span>
                <?php endif; ?>
            </div>

            <div class="mb-4">
                <a href="index.php?uc=gererPanier&produit=<?= $unProduit->id ?>&action=ajouterAuPanier" class="btn btn-success btn-lg <?= $unProduit->stock <= 0 ? 'disabled' : '' ?>">
                    <img src="assets/images/mettrepanier.png" title="Ajouter au panier" alt="Mettre au panier" style="width:24px; margin-right:8px;"> Ajouter au panier
                </a>
            </div>
            
            <div class="mt-4">
                <a href="javascript:history.back()" class="btn btn-outline-secondary">← Retour au catalogue</a>
            </div>
        </div>
    </div>

    <!-- Cross Selling Section -->
    <?php if(!empty($produitsAssocies)): ?>
    <div class="row mt-5 pt-4 border-top">
        <h3 class="mb-4 text-center">Vous pourriez aussi aimer...</h3>
        <div class="d-flex justify-content-center flex-wrap">
            <?php foreach($produitsAssocies as $produitAssocie): ?>
                <div class="card mx-2 mb-3 shadow-sm" style="width: 14rem;">
                    <img src="<?= $produitAssocie->image ?>" class="card-img-top p-3" alt="<?= $produitAssocie->description ?>" style="height: 180px; object-fit: contain;">
                    <div class="card-body text-center d-flex flex-column">
                        <h6 class="card-title text-truncate" title="<?= $produitAssocie->description ?>"><?= $produitAssocie->description ?></h6>
                        <p class="card-text fw-bold text-success mt-auto mb-2"><?= number_format($produitAssocie->prix, 2) ?> €</p>
                        <a href="index.php?uc=voirProduits&action=voirDetails&produit=<?= $produitAssocie->id ?>" class="btn btn-sm btn-outline-info w-100">En savoir plus</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>
