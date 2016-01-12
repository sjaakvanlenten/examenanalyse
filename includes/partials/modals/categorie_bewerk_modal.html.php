<!--CATEGORIE TOEVOEGEN-->
	<div class="modal fade" id="categorietoevoegen">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Voeg categorie <?php echo$temporarilycategorieid + 1;?> toe:</h4>
				</div>
				<form method="post" action="">
					<div class="table-responsive">
						<table class="table table-bordered table-hover">
							<tr>
								<td class="active"><b>Titel</b></td>
								<td class="active"><b>Omschrijving</b></td>
							</tr>						
							<tr>
								<td><input type="text" class="form-control" name="categorie"></td>
								<td><input type="text" class="form-control" name="categorieomschrijving"></td>
							</tr>
						</table>
					</div>
					<div class="modal-footer">
						<input type="submit" class="btn btn-default" name="submit_categorie" value="Toevoegen">
					</div>
				</form>
			</div>
		</div>
	</div>
	<?php
		$temporarilycategorieid = 0;
		foreach ($categorieën as $categorie){
			$temporarilycategorieid = $temporarilycategorieid + 1;
	?>
	<!--CATEGORIE BEWERKEN-->
	<div class="modal fade" id="bewerk<?php echo $categorie['categorie_id']; ?>" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Categorie <?php echo $temporarilycategorieid?> bewerken</h4>
				</div>
				<form action="" method="POST">
					<div class="table-responsive">
						<table class="table table-bordered table-hover">
							<tr>
								<td class="active"><b>Categorie</b></td>
								<td class="active"><b>Uitgebreide omschrijving</b></td>
							</tr>
							<tr>
								<td><input type="text" class="form-control" name="categorie" value="<?php echo $categorie['categorieomschrijving']?>"></td>
								<td><input type="text" class="form-control" name="categorieomschrijving" value="<?php echo $categorie['categorieomschrijving_uitgebreid']?>"></td>
							</tr>
						</table>
						<input type="hidden" name="categorieid" value="<?php echo $categorie['categorie_id']?>">
					</div>
					<div class="modal-footer">
						<button style="float:left" type="button" class="btn btn-danger" data-toggle="modal"  data-dismiss="modal" data-target="#verwijder<?php echo $categorie['categorie_id']; ?>">Verwijderen</button>
						<input type="submit" class="btn btn-default" name="categorieopslaan" value="Opslaan">
						<button type="button" class="btn btn-danger" data-dismiss="modal">Sluiten</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<!--CATEGORIE VERWIJDEREN-->
	<div id="verwijder<?php echo $categorie['categorie_id']; ?>" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Weet u zeker dat u categorie <?php echo $temporarilycategorieid?> wilt verwijderen?</h4>
				</div>
				<div class="table-responsive">
					<table class="table table-bordered table-hover">
						<tr>
							<td class="active"><b>Categorie</b></td>
							<td class="active"><b>Uitgebreide omschrijving</b></td>
						</tr>
						<tr>
							<td><?php echo $categorie['categorieomschrijving']?></td>
							<td><?php echo $categorie['categorieomschrijving_uitgebreid']?></td>
						</tr>
					</table>
				</div>
				<div class="modal-footer">
					<form action="" method="POST">
						<input type="hidden" name="verwijdercategorie" value="<?php echo $categorie['categorie_id'] ?>">
						<input type="submit" class="btn btn-danger" style="float:left;" name="deletecategorie" value="Ja">
					</form>
					<button type="button" class="btn btn-default" data-dismiss="modal">Nee</button>
				</div>
			</div>
		</div>
	</div>
	<?php
		}
	?>