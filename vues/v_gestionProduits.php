<div class="container mt-4">
    <h2>Gestion du catalogue produits</h2>
    
    <div class="mb-3 text-end">
        <a href="index.php?uc=administrer&action=formAjouterProduit" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Ajouter un produit
        </a>
    </div>

    <table class="table table-striped table-hover border">
        <thead class="table-dark">
            <tr>
                <th>Image</th>
                <th>Désignation</th>
                <th>Prix</th>
                <th>Catégorie</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($lesProduits as $unProduit): ?>
            <tr>
                <td><img src="<?= $unProduit->image ?>" width="50" alt=""></td>
                <td><?= $unProduit->description ?></td>
                <td><?= number_format($unProduit->prix, 2) ?> €</td>
                <td><?= $unProduit->idCategorie ?></td>
                <td class="text-center">
                    <a href="index.php?uc=administrer&action=formModifierProduit&id=<?= $unProduit->id ?>" class="btn btn-sm btn-warning" title="Modifier">
                        <i class="bi bi-pencil"></i> Modifier
                    </a>
                    <a href="index.php?uc=administrer&action=supprimerProduit&id=<?= $unProduit->id ?>" 
                       class="btn btn-sm btn-danger" 
                       onclick="return confirm('Voulez-vous vraiment supprimer ce produit ?')" title="Supprimer">
                        <i class="bi bi-trash"></i> Supprimer
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
