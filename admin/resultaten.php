<?php
require_once("/../includes/init.php");
$pagename = "resultaten";
checkSession();
checkIfAdmin();
$gebruiker_id = $_SESSION['gebruiker_id'];
$klassen = getmultipleKlasfromoneTeacher($gebruiker_id);
$aantalklassen = count($klassen);
$allecategorieen = getCategorie();
//echo "<pre>";
//var_dump($klassen);
//echo "</pre>";
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
									<h3 class="panel-title">Overzicht resultaten</h3>
								</div>
								<div class="panel-body">
									<?php
									if(empty($klassen)){
										echo"Het lijkt er op dat er nog geen klassen aan u zijn toegewezen. Er kunnen dus ook geen resultaten getoond worden. U kunt een klas aan uzelf toewijzen op de klassen pagina.";
									}else{
										echo'Hieronder ziet u de resultaten van uw klassen. Het percentage wat wordt weergegeven geeft per categorie aan hoeveel procent van de punten is gescoord in alle ingevoerde resultaten. Als er een " - " staat betekent dat dat deze categorie niet voorkomt in de opgeslagen resultaten.';
									}
									?>
								</div>
							</div>
						</div>
						<?php
							foreach ($klassen as $klas) {
						?>
							<div class="col-sm-<?php if($aantalklassen == 1){echo "12";}elseif($aantalklassen == 2){echo "6";}else{echo "4";}?>">
								<div class="panel panel-default">
									<div class="panel-heading">
										<h3 class="panel-title"><?php echo $klas['klas'];?></h3>
									</div>
									<div class="table-responsive">
										<table class="table table-bordered table-hover">
											<?php

												foreach ($allecategorieen as $categorie) {
													 $result = getScoreKlaseachCategorie($klas['klas_id'], $categorie['categorie_id']);
													if($result != NULL){	
														?>
														<tr>
															<td><?php echo $categorie['categorieomschrijving']?></td>
															<td style="width:70%;">
																<div class="progress">
																	<div class="progress-bar progress-bar-<?php if($result <= 50){echo "danger";} elseif($result <= 75){echo "warning";}else{echo "success";}?> progress-bar-striped" role="progressbar" style="width: <?php echo($result) ?>%">
																		<?php echo($result) ?>%
																	</div>
																</div>
															</td>
														</tr>
														<?php

													} else {
														?>
														<tr>
															<td><?php echo $categorie['categorieomschrijving']?></td>
															<td><div style="margin:4px">
																-
															</div></td>
														</tr>
														<?php
													}
												}
											?>
										</table>
									</div>
									<div class="panel-footer">
										<form action="<?php echo BASE_URL; ?>admin/resultaatklas.php" method="POST">
											<input type="hidden" name="klasid" value="<?php echo $klas['klas_id'];?>">
											<input type="submit" name="moreinfo" value="Meer informatie" class="btn btn-default">
										</form>
									</div>
								</div>
							</div>
						<?php
							}
						?>
					</div>
				</div>
			</div>
		</div>
	<?php include(ROOT_PATH . "includes/templates/footer.php");?>