<?php
require_once("/../includes/init.php");

checkSession();
checkIfAdmin();

if($_SERVER["REQUEST_METHOD"] == "POST") {

    //****************  EXCEL BESTAND IMPORTEREN ******************//
	if(isset($_FILES["excel_leerlingen"])) {
	$upload = importLeerlingenExcelFile($_FILES["excel_leerlingen"]);
	$exceldata = importLeerlingenWithExcel($upload);
	$excelheaders  = array_shift($exceldata);
	$leerlingendata = rebuildExcelClassDataArray($exceldata, $excelheaders);
	}
	
	//******  VIA EXCEL BESTAND KLAS(SEN) MET LEERLINGEN TOEVOEGEN ******//
	if(isset($_POST['submit_excel_data'])) {
		unset($_POST['submit_excel_data']);
		$leerlingendata = rebuildArray($_POST);
		//controleren of klas al bestaat, zo niet: aanmaken
		foreach($leerlingendata as $leerling) {
			$getKlas = getKlas($leerling["klas"]);
			if ($getKlas == 0) {
				addKlas($leerling);
			}
            if(checkIfUserExists($leerling['emailadres']) === FALSE) {
            	$leerling = addStudentCredentials($leerling);
            	addUser($leerling);
            	addStudent($leerling["emailadres"], $leerling["leerling_id"], $leerling["klas"]);
            }
		}
	}

    //****************  KLAS TOEVOEGEN ******************//
    if(isset($_POST['submit_add_klas'])) {
        if (isset($_POST['klas'], $_POST['examenjaar'], $_POST['docent_afk'])) {
   	       	//binnenkomende array ombouwen
        	unset($_POST['submit_add_klas']);
        	$gegevens = rebuildArray($_POST);
            if (!checkArrayForEmptyValues($gegevens)) {
                $_SESSION['message'] = "Je moet alle gegevens invullen!";               			
            }
            else { 				 
                //Ingevoerde gegevens door filter halen en trimmen
            	$gegevens = addKlasFilter($gegevens);
            	//checken of er geen lege waarden zijn ingevoerd
                foreach($gegevens as $klas_gegevens) {		

		                addKlas($klas_gegevens);	
                }
            }
        }
    }

    //********** KLAS BEWERKEN **********//
    if(isset($_POST["submit_bewerk_klas"])) {
		if ($_POST['klas'] == "" OR $_POST['examenjaar'] == "" OR $_POST['niveau'] == "" OR $_POST['docent_afk'] == "") {
			$_SESSION['message'] = "Je moet alle gegevens invullen!";
		} 
		else {
			$klas_id = intval($_POST['klas_id']);
			$gegevens = [ 
				"klas" => $_POST['klas'],
				"examenjaar" => $_POST['examenjaar'],
				"niveau" => $_POST['niveau'],
				"docent_afk" => $_POST['docent_afk'],
			];
			//******** KLAS GEGEVENS FILTEREN *********//
			updateKlasFilter($gegevens);
			//******** KLAS UPDATEN IN DATABASE ********//
			updateKlas($gegevens,$klas_id);	
		}
    }

    //******** KLAS VERWIJDEREN **********//
    if(isset($_POST["submit_verwijder_klas"])) {
    	$klas_id = intval($_POST['klas_id']);

    	deleteKlas($klas_id);
    }
}

//Pak alle klassen uit de database----------------------
$klassenlijst = getKlassen();

//Tel aantal leerlingen per klas
foreach($klassenlijst as $klas => $keys) {
	$klassenlijst[$klas]['aantal'] = getAantalLeerlingenKlas($klassenlijst[$klas]['klas']);
}

$pagename = "klassen";
?>

<?php include(ROOT_PATH . "includes/templates/header.php") ?>
	<div class="wrapper">
		<?php include(ROOT_PATH . "includes/templates/sidebar-admin.php"); ?>
		<div class="page-wrapper">
			<div class="container-fluid">
				<div class="row">
					<div class="col-sm-10">
						<div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">Klassenlijst</h3>
                            </div>                        
							<table class="table">
							    <thead>
							      <tr>
							        <th>Klas</th>
							        <th>Examenjaar</th>
							        <th>Docent</th>
							        <th>Niveau</th>
							        <th>Aantal Leerlingen</th>
							      </tr>
							    </thead>
							    <tbody>					    	
							    	<?php include(ROOT_PATH . "includes/partials/klassenlijst.html.php") ?>
								</tbody>
							</table>
							<div class="panel-footer">
				    			<!-- Button trigger leerling toevoegen modal -->
								<button type="button" class="btn btn-default btn-md" data-toggle="modal" data-target="#klas-toevoegen">
								  Klas Toevoegen
								</button>
								<div class="excel_fileupload">
								<form method="post" enctype="multipart/form-data">
									<label for="file-upload" class="btn btn-default btn-md">
									    Importeer Excel Bestand
									</label>
									<input type="file" id="file-upload" class="btn btn-default btn-md" name="excel_leerlingen" onchange="this.form.submit()">							
					        	</form>
					        	</div>
							</div>
								<!-- Klas bewerken/verwijderen Modal -->
								<?php
									foreach ($klassenlijst as $klas) {
										foreach ($klas as $key => $value) {
											include(ROOT_PATH . "includes/partials/modals/klas_bewerk_modal.html.php");
										}
									}
								?>

								<!-- Klas Toevoegen Modal -->
								<?php					
									include(ROOT_PATH . "includes/partials/modals/klas_toevoegen_modal.html.php");
								?>

								<!-- Excel Klassenlijst Modal -->
								<?php
									if(isset($upload)) {
										include(ROOT_PATH . "includes/partials/modals/excel_klassenlijst_modal.html.php");
									}
								?>																	
							</div>
						</div>
					</div>	
				</div>
			</div>
<?php include(ROOT_PATH . "includes/templates/footer.php");?>