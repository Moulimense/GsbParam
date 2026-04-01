<ul id="erreurs" class="list-group">
<?php
foreach($msgErreurs as $erreur)
	{
?>     
	  <li class="list-group-item list-group-item-danger"><?= $erreur ?></li>
<?php	  
	}
?>
</ul>
