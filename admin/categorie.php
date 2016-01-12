<?php
require_once("/../includes/init.php");
$pagename = "categorieën";
checkSession();
checkIfAdmin();
//CATEGORIE VERWIJDEREN
if (isset($_POST['deletecategorie'])) {
    $verwijder = $_POST['verwijdercategorie'];
    $check = checkifCategoriehasQuestions($verwijder);
    if ($check === true) {
    deleteCategorie($verwijder);
	} else {
		$_SESSION['message'] = "Er zijn nog vragen die aan deze categorie zijn toegewezen, verwijder deze eerst voordat u de categorie kunt verwijderen";
	}
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	//CATEGORIE TOEVOEGEN
	if (isset($_POST['submit_categorie'])){
		if(isset($_POST['categorie'], $_POST['categorieomschrijving'])) {
			if($_POST['categorie'] == "" OR $_POST['categorieomschrijving'] == "") {
				$_SESSION['message'] = 'Je moet alle gegevens invullen!';
			} else {
				$categorie = filter_var(trim($_POST['categorie']), FILTER_SANITIZE_STRING);
				$categorieomschrijving = filter_var(trim($_POST['categorieomschrijving']), FILTER_SANITIZE_STRING);
				addCategorie($categorie, $categorieomschrijving);
			}
		}
	}
	//CATEGORIE WIJZIGEN
	if (isset($_POST['categorieopslaan'])){
		if (isset($_POST['categorie'], $_POST['categorieomschrijving'])){
			if($_POST['categorie'] == "" OR $_POST['categorieomschrijving'] == "") {
				$_SESSION['message'] = 'Je moet alle gegevens invullen!';
			} else {
			$categorie_id = filter_var(trim($_POST['categorieid']), FILTER_SANITIZE_STRING);
			$categorie = filter_var(trim($_POST['categorie']), FILTER_SANITIZE_STRING);
			$categorieomschrijving = filter_var(trim($_POST['categorieomschrijving']), FILTER_SANITIZE_STRING);
			updateCategorie($categorie, $categorieomschrijving, $categorie_id);
			}
		}
	}
}
?>
<!DOCTYPE html>
	<?php include(ROOT_PATH . "includes/templates/header.php");?>
		<div class="wrapper">
			<?php include(ROOT_PATH . "includes/templates/sidebar-admin.php"); ?>
			<div class="page-wrapper">
				<div class="container-fluid">
					<div class="row">
						<div class="col-sm-12">
							<div class="panel panel-default">
								<div class="panel-heading">
									<h3 class="panel-title">Overzicht categorieën</h3>
								</div>
								<div class="panel-body">
									Hieronder ziet u een overzicht van de categorieën die betrekking hebben op de examens:
								</div>
							</div>
						</div>
						<div class="col-sm-12">
							<div class="panel panel-default">
								<div class="table-responsive">
									<table class="table table-bordered table-hover">
										<tr>
											<td class="active"><b>Categorienummer</b></td>
											<td class="active"><b>Categorie</b></td>
											<td class="active"><b>Uitgebreide omschrijving</b></td>
											<td class="active"></td>
										</tr>
										<?php
											$categorieën = getCategorie();
											$temporarilycategorieid = 0;
											foreach ($categorieën as $categorie){
												$temporarilycategorieid = $temporarilycategorieid + 1;
										?>
											<tr>
												<td><?php echo $temporarilycategorieid?></td>
												<td><?php echo $categorie['categorieomschrijving']?></td>
												<td><?php echo $categorie['categorieomschrijving_uitgebreid']?></td>
												<td><button type="button" class="btn btn-default" data-toggle="modal" data-target="#bewerk<?php echo $categorie['categorie_id']; ?>">Bewerken</button></td>
											</tr>
										<?php
											}
										?>
									</table>
								</div>
								<div class="panel-footer">
									<button type="button" class="btn btn-default" data-toggle="modal" data-target="#categorietoevoegen">Toevoegen</button>
								</div>
							</div>
						</div>
						<!--CATEGORIE MODALS-->
						<?php include(ROOT_PATH . "includes/partials/modals/categorie_bewerk_modal.html.php") ?>
					</div>
				</div>
			</div>
		</div>
	<?php include(ROOT_PATH . "includes/templates/footer.php");?>