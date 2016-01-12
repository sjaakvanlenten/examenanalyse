<?php
require_once('/../config/config.php');
require_once(ROOT_PATH . "includes/init.php");
$pagename = "examenresultatentoevoegen";
checkSession();
checkIfAdminIsLoggedOn();
?>

<?php
if (isset($_POST['examen_id'])) {
    $examen_id = $_POST['examen_id'];
    unset($_POST['examen_id']);
    $GEGEVENS = $_POST;

    $gebruiker = $_SESSION['gebruiker_id'];
    $totaalscore = 0;
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['test'])) {
            unset($GEGEVENS['test']);
            foreach ($GEGEVENS as $examenvraagid => $score) {
                $totaalscore = $totaalscore + $score;
                $data = getScore($gebruiker, $examenvraagid);
                if (empty($data)) {
                    //score inserten in tabel score
                    insertScore($gebruiker, $examenvraagid, $score);
                    //score updaten in tabel resultaat
                } else {
                    //score inserten in tabel score
                    updateScore($gebruiker, $examenvraagid, $score);
                }
            }
            $check = checkIfExamResultExists($_SESSION['gebruiker_id'], $examen_id);
            if ($check) {
                //totaal score updaten in tabel resultaat
                insertScoreTabelResultaat($_SESSION['gebruiker_id'], $totaalscore, $examen_id);
            } else {
                //totaal score updaten in tabel resultaat
                updateScoreTabelResultaat($_SESSION['gebruiker_id'], $totaalscore, $examen_id);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
    <body>
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
                                <div class="panel-heading"><h3 class="panel-title">Selecteer een examen om je resultaten in toe voeren of bij te werken.</h3></div>
                                <div class="panel-body">
                                    <?php
                                    $niveau = getNiveauFromStudent($_SESSION['gebruiker_id']);
                                    $examenlijst = getexamen($niveau);
                                    $categorien = checkCategorie();
                                    if (empty($examenlijst)) {
                                        echo"Jij bent ingedeeld in een <b>" . $niveau . "</b> klas. Voor dit niveau zijn er nog geen examens beschikbaar. Vraag je docent om examens toe toe voegen.";
                                    } else {
                                        ?>
                                        <div class="responsive">
                                            <table class="table">
                                                <tr>
                                                    <td>Examenvak</td>
                                                    <td>Examenjaar</td>
                                                    <td>Tijdvak</td>
                                                    <td>nTerm</td>
                                                    <td>Niveau</td>
                                                    <td></td>
                                                </tr>
                                                <?php
                                                foreach ($examenlijst as $examengegevens) {
													echo"<tr>";
                                                    $score = $examengegevens['examen_id'];
                                                    $scorelijst = getExamenvragen($score);
                                                    $punten = getPunten($score, $_SESSION['gebruiker_id']);
                                                    ?><td><?php echo $examengegevens['examenvak']; ?></td><?php
                                                    ?><td><?php echo $examengegevens['examenjaar']; ?></td><?php
                                                    ?><td><?php echo $examengegevens['tijdvak']; ?></td><?php
                                                    ?><td><?php echo $examengegevens['nterm']; ?></td><?php
                                                    ?><td><?php echo $examengegevens['niveau']; ?></td><?php
                                                    ?><td><button type="button" class="btn btn-default btn-md<?php if (empty($punten)) {
                                                        echo "btn btn-default";
                                                    } else {
                                                        echo "btn btn-info";
                                                    } ?>" data-toggle="modal" data-target="#x<?php echo $examengegevens['examen_id']; ?>">Score <?php
                                                    if (empty($punten)) {
                                                        echo "invoeren";
                                                    } else {
                                                        echo "bewerken";
                                                    }
                                                    ?></button></td>
													</tr>
                                                    <?php
                                                }
                                                ?>
                                            </table>


                                        </div>
                                        <?php
                                        foreach ($examenlijst as $examengegevens) {
                                            ?>


                                            <!-- Modal -->
                                            <div id="x<?php echo $examengegevens['examen_id']; ?>" class="modal fade" role="dialog">
                                                <div class="modal-dialog">

                                                    <!-- Modal content-->
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            <h4 class="modal-title">Score invoeren</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <table class="table">
                                                                <tr>
                                                                    <td>Examenvraag</td>
                                                                    <td>Maximale score</td>
                                                                    <td>Categorie</td>
                                                                    <td>Behaalde score</td>
                                                                </tr>
                                                                <form method="POST" action="">
                                                                    <?php
                                                                    $score = $examengegevens['examen_id'];

                                                                    $scorelijst = getExamenvragen($score);
                                                                    
                                                                    $punten = getPunten($score, $_SESSION['gebruiker_id']);
                                                                   
                                                                    $counter = 0;
                                                                    foreach ($scorelijst as $data) {
                                                                        echo"<tr>";
                                                                        ?><td><?php echo $data['examenvraag'] ?></td><?php
                                                                        ?><td><?php echo $data['maxscore'] ?></td><?php
                                                                        ?>  <td><?php
                                                                            foreach ($categorien as $categorie) {
                                                                                $categorieid = $categorie['categorie_id'];
                                                                                $categorie_omschrijving = $categorie['categorieomschrijving'];
                                                                                $categorie_id = $examengegevens['examen_id'];
                                                                                if ($data['categorie_id'] == $categorieid) {
                                                                                    echo $categorie_omschrijving;
                                                                                }
                                                                            }
                                                                            ?>
                                                                        </td>
                                                                        <td>
                                                                            <select name="<?php echo $data['examenvraag_id'] ?>">

                                                                                <?php
                                                                                for ($q = 0; $q <= $data["maxscore"]; $q++) {
                                                                                    ?>
                                                                                    <option value="<?php echo $q; ?>" <?php
                                                                                    if (!empty($punten && isset($punten[$counter]['vraag_score']))) {
                                                                                        if ($q == $punten[$counter]['vraag_score']) {
                                                                                            echo 'selected';
                                                                                        }
                                                                                    }
                                                                                    ?>><?php echo $q; ?></option>
                                                                                            <?php
                                                                                        }
                                                                                        ?>

                                                                            </select>
                                                                        </td>
                                                                        <input type="hidden" name="examen_id" value='<?php echo $examengegevens['examen_id'] ?>'>
                                                                        <?php
                                                                        echo"</tr>";
                                                                        $counter++;
                                                                    }
                                                                    ?>
                                                                    </table>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <input type="submit" class="btn btn-default" value="Score invoeren" name="test"></form>
                                                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Sluiten</button>
                                                                    </div>
                                                                    </div>

                                                                    </div>
                                                                    </div>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                            </div>
                                                            </div>
                                                            </div>
                                                            </div>
                                                            </div>
                                                            </div>
                                                            </div>
                                                            <?php include(ROOT_PATH . "includes/templates/footer.php"); ?>
                                                            </body>
                                                            </html>

