<?php
require_once(__DIR__ . "/../includes/init.php");
$pagename = "resultaten";
checkSession();
checkIfAdmin();
if (!isset($_POST['moreinfo'])) {
    header('Location: ' . BASE_URL . 'admin/resultaten.php');
    exit;
}
$leerling_id = $_POST['leerlingid'];
$userdata = getUserData($leerling_id);
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
                        <div class="panel-heading"><h3 class="panel-title">Voortgang van <?php echo $userdata['voornaam'] . " " . $userdata['tussenvoegsel'] . " " . $userdata['achternaam']; ?></h3></div>
                        <div class="panel-body">
                            <?php
                            $examencijferresultaten = getAllExamResultsWithNterm($_POST['leerlingid']);

                            if (empty($examencijferresultaten)) {
                                echo $userdata['voornaam'] . " " . $userdata['tussenvoegsel'] . " " . $userdata['achternaam'] . " heeft nog geen resultaten toegevoegd.";
                            } else {
                                echo"<p>Hieronder een figuur waarin een overzicht wordt gegeven hoe de door <b>" . $userdata['voornaam'] . "</b> ingevoerde examens gemaakt zijn. Cijfers zijn berekend met de juiste N-term van het bijbehorende examen.</p>";
                                ?>
                                <script type="text/javascript">
                                    $(function (aantalexamens) {


                                        var data = [
    <?php
    $examencijferresultaten = getAllExamResultsWithNterm($_POST['leerlingid']);
    foreach ($examencijferresultaten as $resultaat) {
        //algoritme om cijfer uit te rekenen van cito zie http://www.cito.nl/~/media/cito_nl/files/voortgezet%20onderwijs/omzettingstabel.ashx?la=nl
        // en http://www.cito.nl/~/media/cito_nl/files/voortgezet%20onderwijs/cito_afrondingsalgoritme.ashx?la=nl

        $hoofd = 9.0 * ($resultaat['examen_score'] / $resultaat['maxscore']) + $resultaat['nterm'];
        $lo = 1 + $resultaat['examen_score'] * (9 / $resultaat['maxscore']) * 2;
        $lb = 10 - ($resultaat['maxscore'] - $resultaat['examen_score']) * (9 / $resultaat['maxscore']) * 0.5;
        $ro = 1 + $resultaat['examen_score'] * (9 / $resultaat['maxscore']) * 0.5;
        $rb = 10 - ($resultaat['maxscore'] - $resultaat['examen_score']) * (9 / $resultaat['maxscore']) * 2;
        if (isset($resultaat['examen_score'])) {
            if ($resultaat['nterm'] > 1) {
                $cijfer = min($hoofd, $lo, $lb);
            } else {
                if ($resultaat['nterm'] < 1) {
                    $cijfer = max($hoofd, $ro, $rb);
                } else {
                    $cijfer = $hoofd;
                }
            }
        }
        echo '["	' . $resultaat['examenjaar'] . ' Tijdvak ' . $resultaat['tijdvak'] . "<br>Cijfer: " . round($cijfer, 1) . '",' . $cijfer . "],";
    }
    ?>
                                        ];
                                        $.plot("#examencijferresultaten", [data], {
                                            series: {
                                                bars: {
                                                    show: true,
                                                    barWidth: 0.3,
                                                    align: "center",
                                                    lineWidth: 0,
                                                    fillColor: "rgba(27,188,155, 0.8)"
                                                }
                                                //lines: { show: true, fill: true, },points: { show: true }

                                            },
                                            xaxis: {
                                                mode: "categories",
                                                tickLength: 2,
                                                autoscaleMargin: 0.05


                                            },
                                            yaxis: {
                                                min: 0,
                                                max: 10,
                                                ticks: 10
                                            }
                                        });
                                    });
                                </script>
                                <style>

                                    .examencijferresultaten-container {
                                        width: 100%;
                                        height: 400px;
                                    }

                                    .examencijferresultaten-placeholder {
                                        width: 100%;
                                        height: 90%;
                                        font-size: 16px;
                                        line-height: 1.0em;
                                    }


                                </style>

                                <div style="height:200px;" class="examencijferresultaten-container">
                                    <div id="examencijferresultaten" style="height:200px;" class="examencijferresultaten-placeholder"></div>
                                </div>


                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Voortgang van <?php echo $userdata['voornaam'] . " " . $userdata['tussenvoegsel'] . " " . $userdata['achternaam']; ?> per examen per categorie.</h3>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <?php
                                $data = getExamQuestionResults($_POST['leerlingid']);
                                if (empty($examencijferresultaten)) {
                                    echo $userdata['voornaam'] . " " . $userdata['tussenvoegsel'] . " " . $userdata['achternaam'] . " heeft nog geen resultaten toegevoegd.";
                                } else {
                                    ?>

                                    <table class="table table-bordered table-hover">

                                        <?php
                                        $categorien = checkCategorie();
                                        echo"<tr>";
                                        echo "<th></th>";
                                        foreach ($data as $key => $value) {
                                            echo "<th>" . $key . "</th>";
                                        }
                                        echo"</tr>";
                                        foreach ($categorien as $t) {

                                            echo"<tr>";
                                            $q = $t['categorieomschrijving'];
                                            echo "<th>" . $q . "</th>";
                                            foreach ($data as $key => $value) {
                                                if (array_key_exists($q, $value)) {
                                                    if (isset($voorgaandewaarde)) {
                                                        if ($voorgaandewaarde > $value[$q] OR $value[$q] < 25) {
                                                            echo "<td class='danger'>";
                                                            echo $value[$q] . "%";
                                                            echo "</td>";
                                                        } else {
                                                            echo "<td class='success'>";
                                                            echo $value[$q] . "%";
                                                            echo "</td>";
                                                        }
                                                    } else {
                                                        if ($value[$q] <= 50) {
                                                            echo "<td class='danger'>";
                                                            echo $value[$q] . "%";
                                                            echo "</td>";
                                                        } else {
                                                            echo "<td class='success'>";
                                                            echo $value[$q] . "%";
                                                            echo "</td>";
                                                        }
                                                    }
                                                    $voorgaandewaarde = $value[$q];
                                                } else {
                                                    echo"<td class='active'></td>";
                                                }
                                            }

                                            unset($voorgaandewaarde);
                                            echo"</tr>";
                                        }
                                        ?>
                                    </table>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include(ROOT_PATH . "includes/templates/footer.php"); ?>
