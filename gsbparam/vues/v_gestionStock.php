<div class="container mt-4">
    <h2>Gestion des stocks</h2>

    <?php if (isset($messageSucces) && $messageSucces): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill"></i> <?= htmlspecialchars($messageSucces) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($erreur)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill"></i> <?= htmlspecialchars($erreur) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>


    <div class="card mb-4 border-primary">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-box-seam"></i> Stock des produits</h5>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <p class="text-muted mb-0">Consultez et modifiez les stocks de vos produits.</p>
                <div>
                    <?php 
                    $filtreActif = isset($_REQUEST['filtreCritique']) && $_REQUEST['filtreCritique'] == '1';
                    ?>
                    <?php if ($filtreActif): ?>
                        <a href="index.php?uc=administrer&action=gestionStock" class="btn btn-outline-secondary btn-sm me-2">
                            <i class="bi bi-arrow-counterclockwise"></i> Voir tous les produits
                        </a>
                        <span class="badge bg-warning text-dark"><i class="bi bi-exclamation-triangle"></i> Filtre : Stock critique uniquement</span>
                    <?php else: ?>
                        <a href="index.php?uc=administrer&action=gestionStock&filtreCritique=1" class="btn btn-outline-warning btn-sm">
                            <i class="bi bi-exclamation-triangle"></i> Afficher uniquement stock critique
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (empty($lesProduits)): ?>

                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> 
                    <?php if ($filtreActif): ?>
                        Aucun produit en stock critique. Tous les stocks sont suffisants.
                    <?php else: ?>
                        Aucun produit enregistré dans la base de données.
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <form action="index.php?uc=administrer&action=gestionStock" method="POST" id="formStock">
                    <table class="table table-striped table-hover border align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Image</th>
                                <th>Désignation</th>
                                <th>Prix</th>
                                <th>Catégorie</th>
                                <th class="text-center">Stock actuel</th>
                                <th class="text-center">Nouveau stock</th>
                                <th class="text-center">Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $seuilCritique = 5;
                            foreach ($lesProduits as $unProduit): 
                                $stockActuel = isset($unProduit->stock) ? (int)$unProduit->stock : 0;
                            ?>
                            <tr class="<?= $stockActuel <= 0 ? 'table-danger' : ($stockActuel <= $seuilCritique ? 'table-warning' : '') ?>">
                                <td><img src="<?= htmlspecialchars($unProduit->image) ?>" width="50" alt="" class="img-thumbnail"></td>
                                <td><?= htmlspecialchars($unProduit->description) ?></td>
                                <td><?= number_format($unProduit->prix, 2) ?> €</td>
                                <td><span class="badge bg-secondary"><?= htmlspecialchars($unProduit->idCategorie) ?></span></td>
                                <td class="text-center">
                                    <span class="fw-bold <?= $stockActuel <= 0 ? 'text-danger' : ($stockActuel <= $seuilCritique ? 'text-warning' : 'text-success') ?>">
                                        <?= $stockActuel ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <input type="number" 
                                           name="stocks[<?= htmlspecialchars($unProduit->id) ?>]" 
                                           value="<?= $stockActuel ?>" 
                                           class="form-control form-control-sm text-center stock-input" 
                                           style="width: 100px; margin: 0 auto;"
                                           min="0"
                                           step="1">
                                </td>
                                <td class="text-center">
                                    <?php if ($stockActuel <= 0): ?>
                                        <span class="badge bg-danger"><i class="bi bi-x-circle"></i> Rupture</span>
                                    <?php elseif ($stockActuel <= $seuilCritique): ?>
                                        <span class="badge bg-warning text-dark"><i class="bi bi-exclamation-triangle"></i> Critique</span>
                                    <?php else: ?>
                                        <span class="badge bg-success"><i class="bi bi-check-circle"></i> OK</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <div class="text-end mt-3">
                        <a href="index.php?uc=administrer&action=listeProduits" class="btn btn-secondary me-2">
                            <i class="bi bi-arrow-left"></i> Retour
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-save"></i> Enregistrer les stocks
                        </button>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
// Validation côté client
document.getElementById('formStock')?.addEventListener('submit', function(e) {
    var inputs = document.querySelectorAll('.stock-input');
    var erreurs = [];
    
    inputs.forEach(function(input) {
        var val = input.value.trim();
        
        // Vérifier que ce n'est pas des lettres
        if (val === '' || isNaN(val)) {
            erreurs.push("La valeur \"" + val + "\" n'est pas un nombre valide.");
            input.classList.add('is-invalid');
        }
        // Vérifier que ce n'est pas négatif
        else if (parseFloat(val) < 0) {
            erreurs.push("Le stock ne peut pas être négatif (" + val + ").");
            input.classList.add('is-invalid');
        }
        // Vérifier que c'est un entier
        else if (!Number.isInteger(parseFloat(val))) {
            erreurs.push("Le stock doit être un nombre entier (" + val + ").");
            input.classList.add('is-invalid');
        }
        else {
            input.classList.remove('is-invalid');
        }
    });
    
    if (erreurs.length > 0) {
        e.preventDefault();
        // Afficher les erreurs
        var alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-danger alert-dismissible fade show mt-3';
        alertDiv.innerHTML = '<i class="bi bi-exclamation-triangle-fill"></i> <strong>Erreur de saisie :</strong><ul>' + 
            erreurs.map(function(err) { return '<li>' + err + '</li>'; }).join('') + 
            '</ul><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
        
        // Supprimer l'ancien message d'erreur JS s'il existe
        var oldAlert = document.getElementById('jsStockError');
        if (oldAlert) oldAlert.remove();
        
        alertDiv.id = 'jsStockError';
        document.getElementById('formStock').prepend(alertDiv);
    }
});
</script>
