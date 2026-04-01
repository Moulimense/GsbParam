<div id="creationCommande">
<div class="contenuCentre">
<form method="POST" action="index.php?uc=gererPanier&action=confirmerCommande" id="formC">
<div class="mb-3 row">
    <label for="nom" class="col-sm-2 col-form-label">Nom prénom*</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="nom" name="nom" value="<?= $nom ?>">
    </div>
  </div>
  <div class="mb-3 row">
    <label for="rue" class="col-sm-2 col-form-label">Rue*</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="rue" name="rue" value="<?= $rue ?>">
    </div>
  </div>
  <div class="mb-3 row">
    <label for="cp" class="col-sm-2 col-form-label">Code postal* </label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="cp" name="cp" value="<?= $cp ?>">
    </div>
  </div>
  <div class="mb-3 row">
    <label for="ville" class="col-sm-2 col-form-label">Ville* </label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="ville" name="ville" value="<?= $ville ?>">
    </div>
  </div>
  <div class="mb-3 row">
    <label for="mail" class="col-sm-2 col-form-label">Mail* </label>
    <div class="col-sm-10">
      <input type="mail" class="form-control" id="mail" name="mail" value="<?= $mail ?>">
    </div>
  </div>
  <div class="btnCentre">
         <button class="btn btn-primary" type="submit" name="valider">Valider</button> 
   </div>
</form>
</div>
</div>