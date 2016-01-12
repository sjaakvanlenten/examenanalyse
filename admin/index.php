<?php
// de admin pagina, dit wordt natuurlijk nog uitgebreid.
require_once("/../includes/init.php");

$pagename = "dashboard";
checkSession();
checkIfAdmin();

$countleerling = countAllusersbyRole("1");
$countdocent = countAllusersbyRole("2") + countAllusersbyRole("3");
$countexams = count(getAllExams());
$countgemaakteexams = countGemaakteExamens();

?>
<!DOCTYPE html>
<html>
	<body>
		<?php include(ROOT_PATH . "includes/templates/header.php");?>
		<div class="wrapper">
			<?php include(ROOT_PATH . "includes/templates/sidebar-admin.php"); ?>
			<div class="page-wrapper">
				<div class="container-fluid">
					<div class="row">
						<div class="col-sm-8">
							<div class="panel panel-default">
								<div class="panel-heading">
									<h3 class="panel-title">Dashboard!</h3>
								</div>
								<div class="panel-body">
									Welkom op het dashboard! Hier beheert u alle onderdelen van deze applicatie. 
								</div>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="panel panel-default">
								<div class="panel-heading">
									<h3 class="panel-title">Statistieken</h3>
								</div>
								<div class="table-responsive">
									<table class="table table-bordered table-hover">
										<tr>
											<td><b>Aantal docenten:</b></td>
											<td><?php echo $countdocent;?></td>
										</tr>
										<tr>
											<td><b>Aantal leerlingen:</b></td>
											<td><?php echo $countleerling;?></td>
										</tr>
										<tr>
											<td><b>Aantal examens:</b></td>
											<td><?php echo $countexams;?></td>
										</tr>
										<tr>
											<td><b>Aantal gemaakte examens:</b></td>
											<td><?php echo $countgemaakteexams;?></td>
										</tr>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>