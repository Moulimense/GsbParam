<div class="container mt-4">
    <h2>Gestion du catalogue produits</h2>
    
    <?php if(isset($action) && ($action == 'ajouterProduit' || $action == 'modifierProduit')): ?>
        <div class="card mb-4 border-info">
            <div class="card-header bg-info text-white">
                <h4><?= $action == 'ajouterProduit' ? 'Ajouter un nouveau produit' : 'Modifier le produit' ?></h4>
            </div>
            <div class="card-body">
                <form action="index.php?uc=administrer&action=<?= $action ?>" method="POST">
                    <?php if($action == 'modifierProduit'): ?>
                        <input type="hidden" name="id" value="<?= isset($leProduit) ? $leProduit->id : '' ?>">
                    <?php endif; ?>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nom (Identifiant du produit, ex: c08)</label>
                        <input type="text" class="form-control" name="nom" required 
                               value="<?= isset($leProduit) ? $leProduit->id : '' ?>"
                               <?= $action == 'modifierProduit' ? 'readonly' : '' ?>>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Description</label>
                        <textarea class="form-control" name="description" rows="3" required><?= isset($leProduit) ? $leProduit->description : '' ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Prix (€)</label>
                        <input type="number" step="0.01" min="0" class="form-control" name="prix" required 
                               value="<?= isset($leProduit) ? $leProduit->prix : '' ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nom de l'image (ex: assets/images/monimage.jpg)</label>
                        <input type="text" class="form-control" name="image" required 
                               value="<?= isset($leProduit) ? $leProduit->image : '' ?>">
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Catégorie</label>
                            <select class="form-select" name="idCategorie" required>
                                <option value="">-- Sélectionner une catégorie --</option>
                                <?php if (isset($lesCategories)) {
                                    foreach ($lesCategories as $uneCategorie) {
                                        $selected = (isset($leProduit) && $leProduit->idCategorie == $uneCategorie->id) ? 'selected' : '';
                                        echo "<option value='{$uneCategorie->id}' {$selected}>{$uneCategorie->libelle}</option>";
                                    }
                                } ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Stock initial</label>
                            <input type="number" min="0" class="form-control" name="stock" required 
                                   value="<?= isset($leProduit) ? $leProduit->stock : '0' ?>">
                        </div>
                    </div>
                    
                    <div class="text-end">
                        <a href="index.php?uc=administrer&action=listeProduits" class="btn btn-secondary me-2">Annuler</a>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle"></i> Valider
                        </button>
                    </div>
                </form>
            </div>
        </div>
    <?php elseif(isset($action) && $action == 'supprimer'): ?>
        <div class="card mb-4 border-danger">
            <div class="card-header bg-danger text-white">
                <h4>Confirmer la suppression</h4>
            </div>
            <div class="card-body">
                <p>Êtes-vous sûr de vouloir supprimer définitivement le produit <strong><?= isset($leProduit) ? $leProduit->description : '' ?></strong> ?</p>
                <form action="index.php?uc=administrer&action=supprimer" method="POST">
                    <input type="hidden" name="produit" value="<?= isset($leProduit) ? $leProduit->id : '' ?>">
                    <div class="text-end">
                        <a href="index.php?uc=administrer&action=listeProduits" class="btn btn-secondary me-2">Annuler</a>
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash"></i> Oui, supprimer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <?php if (isset($messageSucces) && $messageSucces): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill"></i> <?= htmlspecialchars($messageSucces) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="mb-3 text-end">
        <a href="index.php?uc=administrer&action=supprimerCategories" class="btn btn-outline-danger me-2">
            <i class="bi bi-trash"></i> Supprimer des catégories
        </a>
        <a href="index.php?uc=administrer&action=ajouterNouvelleCategorie" class="btn btn-info me-2 text-white">
            <i class="bi bi-tags"></i> Ajouter une catégorie
        </a>
        <a href="index.php?uc=administrer&action=ajouterProduit" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Ajouter un nouveau produit
        </a>
    </div>

    <table class="table table-striped table-hover border align-middle">
        <thead class="table-dark">
            <tr>
                <th>Image</th>
                <th>Désignation</th>
                <th>Prix</th>
                <th>Catégorie</th>
                <th>Stock</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($lesProduits as $unProduit): ?>
            <tr>
                <td><img src="<?= $unProduit->image ?>" width="50" alt="" class="img-thumbnail"></td>
                <td><?= $unProduit->description ?></td>
                <td><?= number_format($unProduit->prix, 2) ?> €</td>
                <td><span class="badge bg-secondary"><?= $unProduit->idCategorie ?></span></td>
                <td>
                    <span class="badge <?= (isset($unProduit->stock) && $unProduit->stock <= 5) ? 'bg-danger' : 'bg-success' ?>">
                        <?= isset($unProduit->stock) ? $unProduit->stock : '0' ?>
                    </span>
                </td>
                <td class="text-center">
                    <a href="index.php?uc=administrer&action=modifierProduit&produit=<?= $unProduit->id ?>" class="btn btn-sm btn-warning" title="Modifier">
                        <i class="bi bi-pencil"></i> Modifier 📝
                    </a>
                    <a href="index.php?uc=administrer&action=supprimer&produit=<?= $unProduit->id ?>" 
                       class="btn btn-sm btn-danger" 
                       title="Supprimer">
                        <i class="bi bi-trash"></i> Supprimer 🗑️
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
