<?php
require_once(__DIR__ . "/../includes/init.php");
$pagename = "resultaten";
checkSession();
checkIfAdmin();
if (isset($_POST['klasid'])) {

} else {
    header('Location: ' . BASE_URL . 'admin/resultaten.php');
    exit;
}
$klasid = $_POST['klasid'];
$klasinfo = getInfooneKlas($klasid);
$leerlingen = getStudentNamesfromoneKlas($klasid);
$categorieen = getCategorie();
?>
<!DOCTYPE html>
    <?php include(ROOT_PATH . "includes/templates/header.php"); ?>
<div class="wrapper">
<?php include(ROOT_PATH . "includes/templates/sidebar-admin.php"); ?>
    <div class="page-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-8">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Resultaten:</h3>
                        </div>
                        <div class="panel-body">
                            Hieronder ziet u per leerlingen de behaalde resultaten bij de verschillende categorieën. Klik op meer informatie achter een leerling om de voortgang te zien van deze leerling.
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Informatie klas <?php echo $klasinfo['klas']; ?>:</h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <tr>
                                    <td>Klas:</td>
                                    <td><?php echo $klasinfo['klas']; ?></td>
                                </tr>
                                <tr>
                                    <td>Examenjaar:</td>
                                    <td><?php echo $klasinfo['examenjaar']; ?></td>
                                </tr>
                                <tr>
                                    <td>Niveau:</td>
                                    <td><?php echo $klasinfo['niveau']; ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="panel panel-default">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <th class="active">
                                    <?php
                                    foreach ($categorieen as $categorie) {
                                        ?>
                                    <td class="active"><b><?php echo $categorie['categorieomschrijving'] ?></b></td>
                                    <?php
                                }
                                ?>
                                <td class="active"></td>
                                </th>
                                <?php
                                foreach ($leerlingen as $leerling) {
                                    ?>
                                    <tr>
                                        <td><?php echo $leerling['voornaam'], " ", $leerling['tussenvoegsel'], " ", $leerling['achternaam'] ?></td>
                                        <?php
                                        foreach ($categorieen as $categorie) {
                                            $result = getScoreStudenteachCategorie($leerling['gebruiker_id'], $categorie['categorie_id']);
                                            ?>

                                            <?php
                                            if ($result != NULL) {
                                                ?>
                                                <td >
                                                    <div class="progress">
                                                        <div class="progress-bar progress-bar-<?php if ($result <= 50) {
                                                    echo "danger";
                                                } elseif ($result <= 75) {
                                                    echo "warning";
                                                } else {
                                                    echo "success";
                                                } ?> progress-bar-striped" role="progressbar" style="width: <?php if (empty($result)) {
                                        echo 0;
                                    } else {
                                        echo $result;
                                    } ?>%">
                                                <?php if (empty($result)) {
                                                    echo 0;
                                                } else {
                                                    echo $result;
                                                } ?>%
                                                        </div>
                                                    </div>
                                                </td>
            <?php
        } else {
            ?>
                                                <td><div style="margin:4px">
                                                        -
                                                    </div></td>
            <?php
        }
    }
    ?>
                                        <td>
                                            <form action="<?php echo BASE_URL; ?>admin/resultaatleerling.php" method="POST">
                                                <input type="hidden" name="leerlingid" value="<?php echo $leerling['gebruiker_id']; ?>">
                                                <input type="submit" name="moreinfo" value="Meer informatie" class="btn btn-default">
                                            </form>
                                        </td>
                                    </tr>
    <?php
}
?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include(ROOT_PATH . "includes/templates/footer.php"); ?>