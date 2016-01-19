<?php
require_once(__DIR__ . "/../includes/init.php");
$pagename = "resultaten";
checkSession();
checkIfAdminIsLoggedOn();
?>
<?php include(ROOT_PATH . "includes/partials/message.html.php"); ?>
<?php include(ROOT_PATH . "includes/templates/header.php");?>
<div class="wrapper">
	<?php 
	//als docent ingelogd is sidebar-docent anders sidebar-leerling
	if(checkRole($_SESSION['gebruiker_id']) == 2){
		include(ROOT_PATH . "includes/templates/sidebar-docent.php"); 
	}else{
		include(ROOT_PATH . "includes/templates/sidebar-leerling.php"); 
	}
	?>
	<div class="page-wrapper">
		<div class="container-fluid">
			<div class="row">
				<div class="col-sm-12">
					<div class="panel panel-default">
					  <div class="panel-heading"><h3 class="panel-title">Voortgang weergegeven in een cijfer van de gemaakte examens.</h3></div>
					  <div class="panel-body">
						
					    <?php 
							include(ROOT_PATH . "includes/partials/cijfersgemaakteexamens.html.php"); 
						?>
					  </div>
					</div>
				</div>
				<div class="col-sm-12">
					<div class="panel panel-default">
					  <div class="panel-heading"><h3 class="panel-title">Je ziet hier per examen per categorie de voortgang. Het examen helemaal links heb je als eerste ingevoerd. Klik op de buttons voor meer informatie.</h3></div>
					  <div class="panel-body">
						
					    <?php 
							include(ROOT_PATH . "includes/partials/voortgangperexamenpercategorie.html.php"); 
						?>
					  </div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php include(ROOT_PATH . "includes/templates/footer.php");?>
