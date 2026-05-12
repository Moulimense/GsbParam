<div class="container mt-4">
    <h2><i class="bi bi-receipt"></i> Gestion des commandes</h2>

    <?php if (isset($messageSucces) && $messageSucces): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill"></i> <?= htmlspecialchars($messageSucces) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm border-0 mt-3">
        <div class="card-body p-0">
            <?php if (empty($lesCommandes)): ?>
                <div class="alert alert-warning m-3">
                    <i class="bi bi-exclamation-triangle"></i> Aucune commande n'a été enregistrée pour le moment.
                </div>
            <?php else: ?>
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">ID Commande</th>
                            <th>Date</th>
                            <th>Client</th>
                            <th>Ville</th>
                            <th>Email</th>
                            <th class="text-center">Articles</th>
                            <th class="text-center">État</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $etats = ['En attente', 'En préparation', 'Expédiée', 'Livrée', 'Annulée'];
                        $etatBadge = [
                            'En attente' => 'bg-secondary',
                            'En préparation' => 'bg-info text-dark',
                            'Expédiée' => 'bg-primary',
                            'Livrée' => 'bg-success',
                            'Annulée' => 'bg-danger'
                        ];
                        foreach ($lesCommandes as $cmd): 
                            $etatActuel = $cmd->etat ?? 'En attente';
                            $badgeClass = $etatBadge[$etatActuel] ?? 'bg-secondary';
                        ?>
                        <tr>
                            <td class="ps-4 fw-bold">#<?= htmlspecialchars($cmd->id) ?></td>
                            <td><?= htmlspecialchars($cmd->dateCommande) ?></td>
                            <td><?= htmlspecialchars($cmd->nomPrenomClient) ?></td>
                            <td><?= htmlspecialchars($cmd->cpClient) ?> <?= htmlspecialchars($cmd->villeClient) ?></td>
                            <td><small><?= htmlspecialchars($cmd->mailClient) ?></small></td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-info btn-articles" 
                                        data-id="<?= htmlspecialchars($cmd->id) ?>"
                                        data-bs-toggle="modal" data-bs-target="#modalArticles">
                                    <i class="bi bi-list-ul"></i> Liste des articles
                                </button>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm <?= $badgeClass ?> dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        <?= htmlspecialchars($etatActuel) ?>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <?php foreach ($etats as $etat): ?>
                                            <?php if ($etat !== $etatActuel): ?>
                                            <li>
                                                <form action="index.php?uc=administrer&action=modifierEtatCommande" method="POST">
                                                    <input type="hidden" name="idCommande" value="<?= htmlspecialchars($cmd->id) ?>">
                                                    <input type="hidden" name="etat" value="<?= htmlspecialchars($etat) ?>">
                                                    <button type="submit" class="dropdown-item"><?= htmlspecialchars($etat) ?></button>
                                                </form>
                                            </li>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal Articles -->
<div class="modal fade" id="modalArticles" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Articles de la commande <span id="modalCmdId"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="articlesLoading" class="text-center py-4">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-2">Chargement...</p>
                </div>
                <table class="table table-striped d-none" id="tableArticles">
                    <thead>
                        <tr>
                            <th>Produit</th>
                            <th>Marque</th>
                            <th>Catégorie</th>
                            <th class="text-end">Prix unitaire</th>
                            <th class="text-center">Quantité</th>
                            <th class="text-end">Sous-total</th>
                        </tr>
                    </thead>
                    <tbody id="articlesBody"></tbody>
                    <tfoot>
                        <tr class="table-dark">
                            <td colspan="5" class="text-end fw-bold">Total :</td>
                            <td class="text-end fw-bold" id="totalCommande"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.btn-articles').forEach(btn => {
    btn.addEventListener('click', function() {
        const idCmd = this.dataset.id;
        document.getElementById('modalCmdId').textContent = '#' + idCmd;
        document.getElementById('articlesLoading').classList.remove('d-none');
        document.getElementById('tableArticles').classList.add('d-none');
        
        fetch('index.php?uc=administrer&action=articlesCommande&idCommande=' + idCmd)
            .then(r => r.json())
            .then(data => {
                const tbody = document.getElementById('articlesBody');
                tbody.innerHTML = '';
                data.articles.forEach(art => {
                    tbody.innerHTML += '<tr>' +
                        '<td>' + art.description + '</td>' +
                        '<td>' + art.marque + '</td>' +
                        '<td><span class="badge bg-secondary">' + art.categorie + '</span></td>' +
                        '<td class="text-end">' + art.prix + ' €</td>' +
                        '<td class="text-center">' + art.quantite + '</td>' +
                        '<td class="text-end">' + art.sousTotal + ' €</td>' +
                        '</tr>';
                });
                document.getElementById('totalCommande').textContent = data.total + ' €';
                document.getElementById('articlesLoading').classList.add('d-none');
                document.getElementById('tableArticles').classList.remove('d-none');
            });
    });
});
</script>
