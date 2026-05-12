<div class="container mt-5">
    <h2 class="mb-4">Gestion des Promotions (Produits Mis en Avant)</h2>

    <?php if (isset($messageSucces)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($messageSucces) ?>
            <button type="button" class="btn-close" data-bs-dismiss alert="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($msgErreurs) && !empty($msgErreurs)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                <?php foreach ($msgErreurs as $erreur): ?>
                    <li><?= htmlspecialchars($erreur) ?></li>
                <?php endforeach; ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card mb-5">
        <div class="card-header bg-primary text-white">
            <h5 class="card-title mb-0">Programmer une nouvelle mise en avant</h5>
        </div>
        <div class="card-body">
            <form action="index.php?uc=administrer&action=ajouterPromotion" method="POST">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="produit" class="form-label">Produit</label>
                        <select class="form-select" id="produit" name="produit" required>
                            <option value="">Sélectionnez un produit...</option>
                            <?php foreach ($lesProduits as $unProduit): ?>
                                <option value="<?= htmlspecialchars($unProduit->id) ?>">
                                    <?= htmlspecialchars($unProduit->description) ?> (Ref: <?= htmlspecialchars($unProduit->id) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="dateDebut" class="form-label">Date de début</label>
                        <input type="date" class="form-control" id="dateDebut" name="dateDebut" required min="<?= date('Y-m-d') ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="dateFin" class="form-label">Date de fin</label>
                        <input type="date" class="form-control" id="dateFin" name="dateFin" required min="<?= date('Y-m-d') ?>">
                    </div>
                </div>
                <div class="mt-3">
                    <button type="submit" class="btn btn-success">Programmer la promotion</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-secondary text-white">
            <h5 class="card-title mb-0">Programmations existantes</h5>
        </div>
        <div class="card-body p-0">
            <?php if (empty($lesPromotions)): ?>
                <div class="p-4 text-center text-muted">
                    Aucune promotion n'est actuellement programmée.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Produit</th>
                                <th>Date de début</th>
                                <th>Date de fin</th>
                                <th class="text-center">Statut</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $aujourdHui = date('Y-m-d');
                            foreach ($lesPromotions as $unePromotion): 
                                $estActif = ($aujourdHui >= $unePromotion->dateDebut && $aujourdHui <= $unePromotion->dateFin);
                            ?>
                                <tr>
                                    <td class="align-middle">
                                        <div class="d-flex align-items-center">
                                            <?php if (!empty($unePromotion->image)): ?>
                                                <img src="<?= htmlspecialchars($unePromotion->image) ?>" alt="Image produit" class="me-2" style="width: 40px; height: 40px; object-fit: contain;">
                                            <?php endif; ?>
                                            <div>
                                                <strong><?= htmlspecialchars($unePromotion->description) ?></strong><br>
                                                <small class="text-muted">Ref: <?= htmlspecialchars($unePromotion->idProduit) ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle"><?= date('d/m/Y', strtotime($unePromotion->dateDebut)) ?></td>
                                    <td class="align-middle"><?= date('d/m/Y', strtotime($unePromotion->dateFin)) ?></td>
                                    <td class="align-middle text-center">
                                        <?php if ($estActif): ?>
                                            <span class="badge bg-success">En cours</span>
                                        <?php elseif ($aujourdHui < $unePromotion->dateDebut): ?>
                                            <span class="badge bg-warning text-dark">À venir</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Terminée</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="align-middle text-end">
                                        <form action="index.php?uc=administrer&action=supprimerPromotion" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette programmation ?');">
                                            <input type="hidden" name="promotion" value="<?= $unePromotion->id ?>">
                                            <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
