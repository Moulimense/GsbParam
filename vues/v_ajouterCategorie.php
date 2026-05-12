<div class="container mt-4">
    <h2>Gestion des catégories</h2>

    <div class="card mb-4 border-info">
        <div class="card-header bg-info text-white">
            <h4>Ajouter une nouvelle catégorie</h4>
        </div>
        <div class="card-body">
            <?php if (isset($erreur)): ?>
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle"></i> <?= htmlspecialchars($erreur) ?>
                </div>
            <?php endif; ?>

            <form action="index.php?uc=administrer&action=ajouterNouvelleCategorie" method="POST">
                <div class="mb-3">
                    <label class="form-label fw-bold">Nom de la catégorie</label>
                    <input type="text" class="form-control" name="nom" placeholder="Ex: Maquillage, Parfums..."
                        value="<?= isset($_POST['nom']) ? htmlspecialchars($_POST['nom']) : '' ?>">
                </div>

                <div class="text-end">
                    <a href="index.php?uc=administrer&action=listeProduits" class="btn btn-secondary me-2">Annuler</a>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle"></i> Créer la catégorie
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>