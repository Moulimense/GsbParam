<div class="container mt-4">
    <h2>Gestion des catégories</h2>
    
    <div class="card mb-4 border-danger">
        <div class="card-header bg-danger text-white">
            <h4>Supprimer des catégories</h4>
        </div>
        <div class="card-body">
            <?php if (isset($erreur)): ?>
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle"></i> <?= htmlspecialchars($erreur) ?>
                </div>
            <?php endif; ?>

            <form action="index.php?uc=administrer&action=supprimerCategories" method="POST">
                <p class="text-muted mb-3">Cochez les catégories que vous souhaitez supprimer :</p>
                
                <?php if (isset($lesCategories) && count($lesCategories) > 0): ?>
                    <div class="list-group mb-3">
                        <?php foreach ($lesCategories as $uneCategorie): ?>
                            <label class="list-group-item d-flex align-items-center">
                                <input class="form-check-input me-3" type="checkbox" name="categories[]" value="<?= htmlspecialchars($uneCategorie->id) ?>">
                                <span>
                                    <strong><?= htmlspecialchars($uneCategorie->libelle) ?></strong>
                                    <small class="text-muted ms-2">(<?= htmlspecialchars($uneCategorie->id) ?>)</small>
                                </span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">Aucune catégorie disponible.</div>
                <?php endif; ?>
                
                <div class="text-end">
                    <a href="index.php?uc=administrer&action=listeProduits" class="btn btn-secondary me-2">Annuler</a>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash"></i> Supprimer les catégories sélectionnées
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
