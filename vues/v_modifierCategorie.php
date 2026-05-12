<div class="container mt-4">
    <h2>Gestion des catégories</h2>

    <div class="card mb-4 border-primary">
        <div class="card-header bg-primary text-white">
            <h4>Modifier une catégorie</h4>
        </div>
        <div class="card-body">
            <?php if (isset($erreur)): ?>
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle"></i> <?= htmlspecialchars($erreur) ?>
                </div>
            <?php endif; ?>

            <?php if (!isset($laCategorieSelected)): ?>
                <!-- Étape 1 : Sélection de la catégorie -->
                <form action="index.php?uc=administrer&action=modifierCategorie" method="POST">
                    <p class="text-muted mb-3">Sélectionnez la catégorie que vous souhaitez modifier :</p>
                    
                    <div class="mb-3">
                        <label for="idCategorie" class="form-label fw-bold">Catégorie</label>
                        <select name="idCategorie" id="idCategorie" class="form-select border-primary">
                            <option value="">-- Choisir une catégorie --</option>
                            <?php foreach ($lesCategories as $uneCategorie): ?>
                                <option value="<?= htmlspecialchars($uneCategorie->id) ?>">
                                    <?= htmlspecialchars($uneCategorie->libelle) ?> (<?= htmlspecialchars($uneCategorie->id) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="text-end">
                        <a href="index.php?uc=administrer&action=listeProduits" class="btn btn-secondary me-2">Annuler</a>
                        <button type="submit" name="validerSelection" class="btn btn-primary">
                            <i class="bi bi-arrow-right"></i> Continuer
                        </button>
                    </div>
                </form>
            <?php else: ?>
                <!-- Étape 3 : Saisie du nouveau nom -->
                <form action="index.php?uc=administrer&action=modifierCategorie" method="POST">
                    <input type="hidden" name="idCategorie" value="<?= htmlspecialchars($laCategorieSelected->id) ?>">
                    
                    <p class="mb-3">Modification de la catégorie : <strong><?= htmlspecialchars($laCategorieSelected->libelle) ?></strong> (<?= htmlspecialchars($laCategorieSelected->id) ?>)</p>

                    <div class="mb-3">
                        <label for="nouveauNom" class="form-label fw-bold">Nouveau nom de la catégorie</label>
                        <input type="text" name="nouveauNom" id="nouveauNom" class="form-control border-primary" 
                               value="<?= isset($nouveauNom) ? htmlspecialchars($nouveauNom) : htmlspecialchars($laCategorieSelected->libelle) ?>" required>
                    </div>

                    <div class="text-end">
                        <a href="index.php?uc=administrer&action=modifierCategorie" class="btn btn-secondary me-2">Retour</a>
                        <button type="submit" name="validerModification" class="btn btn-success">
                            <i class="bi bi-check-lg"></i> Valider la modification
                        </button>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>
