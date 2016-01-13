<?php
require_once(__DIR__ . "/../includes/init.php");

$pagename = "examens";


checkSession();
checkIfAdmin();

if (isset($_GET['verwijderexamen'])) {
    $verwijder = $_GET['verwijderexamen'];
    deleteExam($verwijder);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['submit_examen'])) {
        //voor als er een examen toegevoegd word
        if (isset($_POST['vak'], $_POST['jaar'], $_POST['tijdvak'], $_POST['nterm'], $_POST['niveau'])) {
            //checken of er geen lege waarden zijn ingevoerd
            if ($_POST['vak'] == "" OR $_POST['jaar'] == "" OR $_POST['tijdvak'] == "" OR $_POST['nterm'] == "" OR $_POST['niveau'] == "") {
                $_SESSION['message'] = 'Je moet alle gegevens invullen!';
            } else {
                // overbodige ingevoerde spaties weghalen met functie trim
                $vak = filter_var(trim($_POST['vak']), FILTER_SANITIZE_STRING);
                $jaar = filter_var(trim($_POST['jaar']), FILTER_SANITIZE_STRING);
                $tijdvak = filter_var(trim($_POST['tijdvak']), FILTER_SANITIZE_STRING); //tussenvoegsel mag spatie bevatten
                $nterm = filter_var(trim($_POST['nterm']), FILTER_SANITIZE_STRING);
                $niveau = filter_var(trim($_POST['niveau']), FILTER_SANITIZE_STRING);
                $gegevens = [
                    $vak,
                    $jaar,
                    $tijdvak,
                    $nterm,
                    $niveau
                ];
                unset($_POST['submit_examen']);
                unset($_POST['vak']);
                unset($_POST['jaar']);
                unset($_POST['tijdvak']);
                unset($_POST['nterm']);
                unset($_POST['niveau']);
                $vragen = $_POST;


                $temp_array = array_values($vragen);

                $count = count($temp_array[0]);
                for ($j = 0; $j < $count; $j++) {
                    $i = 0;
                    foreach ($vragen as $key => $waarde) {
                        $vragen[$key] = $temp_array[$i];
                        $i++;
                    }

                    $gegevens_vragen[] = $vragen;
                }

                $controleer_of_alle_vragen_ingevoerd_zijn = true;
                foreach ($gegevens_vragen as $e) {
                    if (in_array("...", $e) OR in_array("", $e)) {
                        $controleer_of_alle_vragen_ingevoerd_zijn = false;
                    }
                }
                //checken of email en afkorting uniek zijn
                if (checkIfExamExists($vak, $jaar, $tijdvak, $niveau) === FALSE) {
                    //examen bestaat niet en kan dus worden toegevoegd
                    //checken als alle vragen ingevoerd zijn
                    if ($controleer_of_alle_vragen_ingevoerd_zijn == true) {
                        // gegevens inserten
                        addExam($gegevens);
                        //ingevoerde examenvragen invoeren

                        $examen_gegevens = checkIfExamExists($vak, $jaar, $tijdvak, $niveau);


                        foreach ($gegevens_vragen as $gegeven) {

                            $r = count($gegeven) / 3;

                            for ($t = 0; $t < $r; $t++) {
                                $vraag = $gegeven['vraag' . $t];
                                $maxscore = $gegeven['maxscore' . $t];
                                $categorie = $gegeven['categorie' . $t];


                                $examen_id = $examen_gegevens['examen_id'];

                                if ($maxscore == "" OR $categorie == "...") {
                                    $_SESSION['message'] = 'Je moet alle gegevens invullen.';
                                }

                                // categorie_id bepalen.....!
                                $check_categorie_id = checkCategorie_id($categorie);
                                foreach ($check_categorie_id as $q) {
                                    $categorie_id = $q['categorie_id'];

                                    addExamQuestion($vraag, $maxscore, $categorie_id, $examen_id);
                                }
                            }
                        }
                    } else {
                        $_SESSION['message'] = 'Je moet alle gegevens correct invullen!';
                    }
                } else {
                    //examen bestaat al.
                    $_SESSION['message'] = 'Dit examen bestaat al.';
                }
            }
        }
    } else {
        // voor als een examen bewerkt wordt
        $vak = filter_var(trim($_POST['vak']), FILTER_SANITIZE_STRING);
        $jaar = filter_var(trim($_POST['jaar']), FILTER_SANITIZE_STRING);
        $tijdvak = filter_var(trim($_POST['tijdvak']), FILTER_SANITIZE_STRING);
        $nterm = filter_var(trim($_POST['nterm']), FILTER_SANITIZE_STRING);
        $niveau = filter_var(trim($_POST['niveau']), FILTER_SANITIZE_STRING);
        $gegevens = [
            $vak,
            $jaar,
            $tijdvak,
            $nterm,
            $niveau
        ];
        //examengegevens opvragen
        $examengegevens = checkIfExamExists($vak, $jaar, $tijdvak);

        //nterm updaten als veranderd is
        if ($nterm != $examengegevens['nterm']) {
            updateNterm($nterm, $examengegevens['examen_id']);
        }

        unset($_POST['submit_examen']);
        unset($_POST['vak']);
        unset($_POST['jaar']);
        unset($_POST['tijdvak']);
        unset($_POST['nterm']);
        unset($_POST['niveau']);

        $vragen = $_POST;
        if (in_array("", $vragen)) {
            $_SESSION['message'] = "Voer alle gegevens in!";
        } else {
            $vragenarray = array();
            $r = count($vragen) / 3;
            $examen_id = $examengegevens['examen_id'];
            for ($t = 0; $t < $r; $t++) {
                $vraag = $vragen['vraag' . $t];
                $maxscore = $vragen['maxscore' . $t];
                $categorie = $vragen['categorie' . $t];
                $categorie_id = checkCategorie_id($categorie);
                $categorie_id = $categorie_id['0'];
                $categorie_id = $categorie_id['categorie_id'];
                $vragenarray[$t]['vraag'] = $vraag;
                $vragenarray[$t]['maxscore'] = $maxscore;
                $vragenarray[$t]['categorie_id'] = $categorie_id;
            }
            //gegevens inserten
            foreach ($vragenarray as $examenvraag) {
                $v = $examenvraag['vraag'];
                $ms = $examenvraag['maxscore'];
                $c_id = $examenvraag['categorie_id'];
                //checken of vraag bestaat
                $check = checkIfExamQuestionExists($v, $examen_id);
                if ($check) {
                    //vraag bestaat, dus updaten
                    $check = $check['examenvraag_id'];
                    updateExamQuestion($ms, $c_id, $check);
                } else {
                    //vraag bestaat niet, dus toevoegen
                    addExamQuestion($v, $ms, $c_id, $examen_id);
                }
            }
        }
    }
}
?>
<?php include(ROOT_PATH . "includes/partials/message.html.php"); ?>
<?php include(ROOT_PATH . "includes/templates/header.php"); ?>
<div class="wrapper">
    <?php include(ROOT_PATH . "includes/templates/sidebar-admin.php"); ?>
    <div class="page-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Overzicht examens</h3>
                        </div>
                        <div class="panel-body">
                            <?php
                            $examengegevens = getAllExams();
                            if (empty($examengegevens)) {
                                echo"Het lijkt er op dat er nog geen docenten bestaan, klik op \"Toevoegen\" om een docent toe te voegen";
                            } else {
                                echo"Hieronder ziet u een overzicht van alle examens. Hier kunt u deze bewerken of verwijderen. Ook kunt u een nieuwe examens toevoegen door op de knop \"Toevoegen \" te klikken";
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="panel panel-default">
                        <?php
                        $examengegevens = getAllExams();
                        $t = 0;
                        $x = count($examengegevens);
                        foreach ($examengegevens as $examengegeven) {
                            if ($t == 0) {
                                ?>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <tr>
                                            <td class="active"><b>Examenvak</b></td>
                                            <td class="active"><b>Examenjaar</b></td>
                                            <td class="active"><b>Tijdvak</b></td>
                                            <td class="active"><b>nTerm</b></td>
                                            <td class="active"><b>Niveau</b></td>
                                            <td class="active"></td>
                                        </tr>
                                        <?php
                                    }
                                    $t++;
                                    ?>
                                    <tr>
                                        <td><?php echo $examengegeven['examenvak']; ?></td>
                                        <td><?php echo $examengegeven['examenjaar']; ?></td>
                                        <td><?php echo $examengegeven['tijdvak']; ?></td>
                                        <td><?php echo $examengegeven['nterm']; ?></td>
                                        <td><?php echo $examengegeven['niveau']; ?></td>
                                        <td><button type="button" class="btn btn-default" data-toggle="modal" data-target="#<?php echo $examengegeven['examen_id']; ?>">Bewerken</button></td>
                                    </tr>
                                    <?php
                                    if ($t == $x) {
                                        ?>
                                    </table>
                                </div>
                                <?php
                            }
                        }
                        ?>
                        <div class="panel-footer">
                            <button type="button" class="btn btn-default" data-toggle="modal" data-target="#examentoevoegen">Examen toevoegen</button>
                        </div>
                    </div>
                </div>
                <?php include(ROOT_PATH . "includes/partials/modals/examen_bewerk_modal.html.php"); ?>
            </div>
        </div>
    </div>
</div>
<?php include(ROOT_PATH . "includes/templates/footer.php"); ?>