<?php
require_once(__DIR__ . "/../includes/init.php");
$pagename = "resultaten";
checkSession();
checkIfAdminIsLoggedOn();
?>
<?php include(ROOT_PATH . "includes/partials/message.html.php"); ?>
<?php include(ROOT_PATH . "includes/templates/header.php"); ?>
<div class="wrapper">
    <?php
    //als docent ingelogd is sidebar-docent anders sidebar-leerling
    if (checkRole($_SESSION['gebruiker_id']) == 2) {
        include(ROOT_PATH . "includes/templates/sidebar-docent.php");
    } else {
        include(ROOT_PATH . "includes/templates/sidebar-leerling.php");
    }
    ?>
    <div class="page-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-default">
                        <div class="panel-heading"><h3 class="panel-title">Resultaat van <?php
                                if (isset($_GET['examen']) && $_GET['examen'] != "") {
                                    $examen_id = $_GET['examen'];
                                    $data = getExamQuestionResultsFromExamen($_SESSION['gebruiker_id'], $examen_id);
                                    foreach ($data as $key => $value) {
                                        echo $key;
                                    }
                                }
                                ?></h3></div>
                        <div class="panel-body">
                            <?php
                            if (isset($_GET['examen']) && $_GET['examen'] != "") {
                                $examen_id = $_GET['examen'];
                                $data = getExamQuestionResultsFromExamen($_SESSION['gebruiker_id'], $examen_id);
                                $check = getAllCreatedExamsWithExamId($examen_id);
                                if (empty($check)) {
                                    header('Location: ' . 'resultaten.php');
                                    exit;
                                }
                                ?>
                                <div class="table-responsive">
                                    <table class="table">

                                        <?php
                                        $test = checkCategorie();


                                        foreach ($test as $t) {

                                            echo"<tr>";
                                            $q = $t['categorieomschrijving'];
                                            echo "<th>" . $q . "</th>";
                                            foreach ($data as $key => $value) {
                                                if (array_key_exists($q, $value)) {
                                                    echo "<td style='width:85%'>";

                                                    if ($value[$q] < 50) {
                                                        ?>
                                                        <div class="progress">
                                                            <div class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar" aria-valuenow="<?php echo $value[$q]; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $value[$q] + 1.5; ?>%">
                                                                <?php echo $value[$q] . "%"; ?>
                                                            </div>
                                                        </div>
                                                        <?php
                                                    } elseif ($value[$q] >= 50 && $value[$q] <= 75) {
                                                        ?>
                                                        <div class="progress">
                                                            <div class="progress-bar progress-bar-warning  progress-bar-striped" role="progressbar" aria-valuenow="<?php echo $value[$q]; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $value[$q]; ?>%">
                                                                <?php echo $value[$q] . "%"; ?>
                                                            </div>
                                                        </div>

                                                        <?php
                                                    } elseif ($value[$q] > 75) {
                                                        ?>
                                                        <div class="progress">
                                                            <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="<?php echo $value[$q]; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $value[$q]; ?>%">
                                                                <?php echo $value[$q] . "%"; ?>
                                                            </div>
                                                        </div>
                                                        <?php
                                                    }

                                                    echo "</td>";
                                                } else {
                                                    echo"<td>Dit onderdeel kwam niet voor in dit examen.</td>";
                                                }
                                            }


                                            echo"</tr>";
                                        }
                                        ?>
                                    </table>
                                </div>
                                <?php
                            } else {
                                header('Location: ' . BASE_URL);
                                exit;
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <?php
                if (isset($data)) {
                    ?>
                    <div class="col-sm-12">
                        <div class="panel panel-default">
                            <div class="panel-heading"><h3 class="panel-title">Deze onderdelen heb je onder de 75% goed gescoord:</h3></div>
                            <div class="panel-body">
                                <?php
                                $getal = 1;
                                foreach ($data as $key => $value) {
                                    foreach ($value as $k => $v) {
                                        if ($v < 75 && $k != "examen_id") {
                                            $getal++;
                                            ?><div class="col-sm-6">
                                                <?php
                                                $categoriedata = checkCategorie();
                                                foreach ($categoriedata as $key => $value) {
                                                    if ($value['categorieomschrijving'] == $k) {
                                                        $categorie_id = $value['categorie_id'];
                                                        $e = $value['categorieomschrijving_uitgebreid'];
                                                    }
                                                }
                                                $categoriedata = getAllExamQuestionsWithCategorie($categorie_id);
                                                echo"Je hebt onder de 75% goed gescoord in de categorie <b title='" . $e . "'>" . $k . "</b>. De onderstaande vragen vallen onder de zelfde categorie. Probeer deze nog eens te oefenen:";
                                                echo"<ul>";
                                                foreach ($categoriedata as $key => $value) {
                                                    echo "<li>" . $value['examenvak'] . " " . $value['examenjaar'] . " tijdvak " . $value['tijdvak'] . " vraag " . $value['examenvraag'] . "</li>";
                                                }
                                                echo"</ul>";
                                                ?></div><?php
                                        }
                                    }
                                    if ($getal == 1) {
                                        echo"Goed bezig!! Je hebt op alle onderdelen boven de 75% goed gescoord!";
                                    }
                                }
                                ?>
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
<?php include(ROOT_PATH . "includes/templates/footer.php"); ?>

