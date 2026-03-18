<ul id="categories">

<?php
foreach ((array)$lesCategories as $uneCategorie)
{
	$idCategorie = $uneCategorie->id;
	$libCategorie = $uneCategorie->libelle;
	?>
	<li>
		<a class="text-decoration-none text-light" href="index.php?uc=voirProduits&action=voirProduits&categorie=<?= $idCategorie ?>">
		<?= $libCategorie ?></a>
	</li>
<?php
}
?>

</ul>

