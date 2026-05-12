<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-link-45deg"></i> Gestion des produits associés</h2>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCreer">
            <i class="bi bi-plus-circle"></i> Créer une association
        </button>
    </div>

    <?php if (isset($messageSucces) && $messageSucces): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill"></i> <?= htmlspecialchars($messageSucces) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($erreur) && $erreur): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill"></i> <?= htmlspecialchars($erreur) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <?php if (empty($lesAssociations)): ?>
                <div class="alert alert-info m-3">
                    <i class="bi bi-info-circle"></i> Aucune association de produits n'a été créée pour le moment.
                </div>
            <?php else: ?>
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Produit Principal</th>
                            <th>Produit Associé</th>
                            <th class="text-center" style="width: 200px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lesAssociations as $uneAssoc): ?>
                            <tr>
                                <td class="ps-4 align-middle">
                                    <span class="badge bg-secondary me-2"><?= htmlspecialchars($uneAssoc->idProduit) ?></span>
                                    <?= htmlspecialchars($uneAssoc->desc1) ?>
                                </td>
                                <td class="align-middle">
                                    <span class="badge bg-info text-dark me-2"><?= htmlspecialchars($uneAssoc->idProduitAssocie) ?></span>
                                    <?= htmlspecialchars($uneAssoc->desc2) ?>
                                </td>
                                <td class="text-center align-middle pe-4">
                                    <button class="btn btn-sm btn-outline-warning me-1 btn-modifier" 
                                            data-id1="<?= htmlspecialchars($uneAssoc->idProduit) ?>"
                                            data-id2="<?= htmlspecialchars($uneAssoc->idProduitAssocie) ?>"
                                            data-bs-toggle="modal" data-bs-target="#modalModifier">
                                        <i class="bi bi-pencil"></i> Modifier
                                    </button>
                                    <form action="index.php?uc=administrer&action=gestionAssociations" method="POST" class="d-inline" onsubmit="return confirm('Supprimer cette association ?');">
                                        <input type="hidden" name="actionP" value="supprimer">
                                        <input type="hidden" name="idProduit" value="<?= htmlspecialchars($uneAssoc->idProduit) ?>">
                                        <input type="hidden" name="idProduitAssocie" value="<?= htmlspecialchars($uneAssoc->idProduitAssocie) ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i> Supprimer
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal Créer -->
<div class="modal fade" id="modalCreer" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="index.php?uc=administrer&action=gestionAssociations" method="POST" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Créer une association</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="actionP" value="creer">
                <div class="mb-3">
                    <label class="form-label">Produit Principal</label>
                    <select name="idProduit" class="form-select select2" required>
                        <option value="">Sélectionnez un produit...</option>
                        <?php foreach ($lesProduits as $p): ?>
                            <option value="<?= htmlspecialchars($p->id) ?>"><?= htmlspecialchars($p->id) ?> - <?= htmlspecialchars($p->description) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Produit à associer</label>
                    <select name="idProduitAssocie" class="form-select select2" required>
                        <option value="">Sélectionnez un produit...</option>
                        <?php foreach ($lesProduits as $p): ?>
                            <option value="<?= htmlspecialchars($p->id) ?>"><?= htmlspecialchars($p->id) ?> - <?= htmlspecialchars($p->description) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" class="btn btn-primary">Créer</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Modifier -->
<div class="modal fade" id="modalModifier" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="index.php?uc=administrer&action=gestionAssociations" method="POST" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modifier une association</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="actionP" value="modifier">
                <input type="hidden" name="ancienIdProduit" id="modAncienId1">
                <input type="hidden" name="ancienIdProduitAssocie" id="modAncienId2">
                
                <div class="mb-3">
                    <label class="form-label">Produit Principal</label>
                    <select name="idProduit" id="modId1" class="form-select" required>
                        <?php foreach ($lesProduits as $p): ?>
                            <option value="<?= htmlspecialchars($p->id) ?>"><?= htmlspecialchars($p->id) ?> - <?= htmlspecialchars($p->description) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Produit à associer</label>
                    <select name="idProduitAssocie" id="modId2" class="form-select" required>
                        <?php foreach ($lesProduits as $p): ?>
                            <option value="<?= htmlspecialchars($p->id) ?>"><?= htmlspecialchars($p->id) ?> - <?= htmlspecialchars($p->description) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" class="btn btn-warning">Modifier</button>
            </div>
        </form>
    </div>
</div>

<script>
document.querySelectorAll('.btn-modifier').forEach(btn => {
    btn.addEventListener('click', function() {
        const id1 = this.dataset.id1;
        const id2 = this.dataset.id2;
        
        document.getElementById('modAncienId1').value = id1;
        document.getElementById('modAncienId2').value = id2;
        document.getElementById('modId1').value = id1;
        document.getElementById('modId2').value = id2;
    });
});
</script>
