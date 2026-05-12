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
                    <?php if (empty($unProduit->noteClient)): ?>
                        <span class="text-muted fst-italic">Aucune note moyenne pour ce produit</span>
                    <?php else: ?>
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
                    <?php endif; ?>
                </div>
            </div>

            <div class="mb-4">
                <?php 
                $seuil = defined('SEUIL_ALERTE_STOCK') ? SEUIL_ALERTE_STOCK : 5;
                if($unProduit->stock > $seuil): ?>
                    <span class="text-success fw-bold"><i class="bi bi-check-circle"></i> En stock</span>
                <?php elseif($unProduit->stock > 0): ?>
                    <span class="text-warning fw-bold"><i class="bi bi-exclamation-triangle"></i> Attention, plus que <?= $unProduit->stock ?> en stock</span>
                <?php else: ?>
                    <span class="text-danger fw-bold"><i class="bi bi-x-circle"></i> Rupture de stock</span>
                <?php endif; ?>
            </div>

            <div class="mb-4">
                <?php if (isset($_SESSION['idClient'])): ?>
                    <?php if($unProduit->stock > 0): ?>
                        <form action="index.php?uc=gererPanier&produit=<?= $unProduit->id ?>&action=ajouterAuPanier" method="POST" class="d-flex align-items-center">
                            <label for="quantite" class="me-2 fw-bold">Quantité :</label>
                            <select name="quantite" id="quantite" class="form-select me-3" style="width: 80px;">
                                <?php for($i=1; $i<=$unProduit->stock; $i++): ?>
                                    <option value="<?= $i ?>"><?= $i ?></option>
                                <?php endfor; ?>
                            </select>
                            <button type="submit" class="btn btn-success btn-lg">
                                <img src="assets/images/mettrepanier.png" title="Ajouter au panier" alt="Mettre au panier" style="width:24px; margin-right:8px;"> Ajouter au panier
                            </button>
                        </form>
                    <?php else: ?>
                        <button class="btn btn-success btn-lg disabled">
                            <img src="assets/images/mettrepanier.png" title="Ajouter au panier" alt="Mettre au panier" style="width:24px; margin-right:8px;"> Ajouter au panier
                        </button>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            
            <div class="mt-4">
                <a href="javascript:history.back()" class="btn btn-outline-secondary">← Retour au catalogue</a>
            </div>
        </div>
    </div>

    <!-- Reviews Section -->
    <div class="row mt-5 pt-4 border-top">
        <h3 class="mb-4">Avis des clients</h3>

        <?php if (isset($_SESSION['idClient'])): ?>
            <?php if (isset($aDejaDonneAvis) && $aDejaDonneAvis): ?>
                <div class="alert alert-success" role="alert">
                    <i class="bi bi-check-circle"></i> Vous avez déjà donné votre avis sur ce produit. Merci de votre contribution !
                </div>
            <?php else: ?>
                <div class="card mb-4 bg-light">
                    <div class="card-body">
                        <h5 class="card-title">Laisser un avis</h5>
                        <form action="index.php?uc=voirProduits&action=ajouterAvis" method="POST">
                            <input type="hidden" name="idProduit" value="<?= $unProduit->id ?>">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Votre note :</label>
                                <div class="rating">
                                    <?php for($i=5; $i>=1; $i--): ?>
                                        <input type="radio" name="note" value="<?= $i ?>" id="star<?= $i ?>">
                                        <label for="star<?= $i ?>">★</label>
                                    <?php endfor; ?>
                                </div>
                                <style>
                                    .rating { direction: rtl; display: inline-block; }
                                    .rating input { display: none; }
                                    .rating label { color: #ddd; font-size: 2em; padding: 0; cursor: pointer; }
                                    .rating input:checked ~ label, .rating label:hover, .rating label:hover ~ label { color: #ffc107; }
                                </style>
                            </div>
                            <div class="mb-3">
                                <label for="commentaire" class="form-label fw-bold">Votre commentaire (optionnel) :</label>
                                <textarea name="commentaire" id="commentaire" class="form-control" rows="3"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Valider mon avis</button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="alert alert-info" role="alert">
                <i class="bi bi-info-circle"></i> Vous devez être connecté pour laisser un avis. <a href="index.php?uc=connexion&action=demanderConnexion" class="alert-link">Se connecter ou s'inscrire</a>.
            </div>
        <?php endif; ?>

        <div class="avis-list">
            <?php if(!empty($lesAvis)): ?>
                <?php foreach($lesAvis as $avis): ?>
                    <div class="card mb-3 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="card-subtitle text-muted mb-0">
                                    <i class="bi bi-person-circle"></i> <?= htmlspecialchars($avis->prenom . ' ' . $avis->nom) ?>
                                </h6>
                                <small class="text-muted"><?= date('d/m/Y', strtotime($avis->dateAvis)) ?></small>
                            </div>
                            <div class="text-warning mb-2" style="font-size: 1.1em;">
                                <?php 
                                for($i=1; $i<=5; $i++) {
                                    if($i <= $avis->note) echo "★";
                                    else echo "☆";
                                }
                                ?>
                            </div>
                            <?php if(!empty($avis->commentaire)): ?>
                                <p class="card-text"><?= nl2br(htmlspecialchars($avis->commentaire)) ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-muted fst-italic">Aucun avis pour ce produit pour le moment. Soyez le premier à donner votre avis !</p>
            <?php endif; ?>
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
