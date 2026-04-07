<div id="choixC" class="card shadow-sm mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="card-title mb-0"><i class="bi bi-filter"></i> Filtrer les produits</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="index.php?uc=voirProduits&action=voirProduits" class="row g-3">

            <div class="col-md-4">
                <label class="form-label fw-bold small text-uppercase">Catégorie</label>
                <select name="categorie" class="form-select border-primary">
                    <option value="tous">Toute la boutique</option>
                    <?php foreach ($lesCategories as $uneCat): ?>
                        <option value="<?= $uneCat->id ?>" <?= (isset($_REQUEST['categorie']) && $_REQUEST['categorie'] == $uneCat->id) ? 'selected' : '' ?>>
                            <?= $uneCat->libelle ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-bold small text-uppercase">Marque</label>
                <select name="marque" class="form-select border-primary">
                    <option value="">Toutes les marques</option>
                    <?php foreach ($lesMarques as $uneMarque): ?>
                        <option value="<?= $uneMarque->marque ?>" <?= (isset($_REQUEST['marque']) && $_REQUEST['marque'] == $uneMarque->marque) ? 'selected' : '' ?>>
                            <?= $uneMarque->marque ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-bold small text-uppercase">Fourchette de prix (€)</label>
                <div class="input-group">
                    <input type="number" name="prixMin" class="form-control" placeholder="Min" aria-label="Prix Minimum"
                        value="<?= $_REQUEST['prixMin'] ?? '' ?>">
                    <span class="input-group-text bg-light">à</span>
                    <input type="number" name="prixMax" class="form-control" placeholder="Max" aria-label="Prix Maximum"
                        value="<?= $_REQUEST['prixMax'] ?? '' ?>">
                </div>
            </div>

            <div class="col-12 d-flex justify-content-end gap-2 mt-3">
                <a href="index.php?uc=voirProduits&action=nosProduits" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-counterclockwise"></i> Réinitialiser
                </a>
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-search"></i> Appliquer les filtres
                </button>
            </div>
        </form>
    </div>
</div>