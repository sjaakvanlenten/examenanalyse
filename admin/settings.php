<?php
require_once(__DIR__ . "/../includes/init.php");

checkSession();

$user_data = getUserData($_SESSION['gebruiker_id']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //checken als er gegevens ingevoerd zijn
    if (isset($_POST['wijzigen'])) {
        $match = password_verify($_POST["huidig"], $user_data["wachtwoord"]);
        if ($match === FALSE) {
            $_SESSION["message"] = "Wachtwoord onjuist";
        } else {
            $nieuw = $_POST["nieuw"];
            $nieuwheraal = $_POST["nieuwheraal"];
            if (passTest($nieuw, $nieuwheraal) === TRUE) {
                $user_id = $_SESSION['gebruiker_id'];
                $nieuw = password_hash($nieuw, PASSWORD_BCRYPT);
                updatePassword($nieuw, $user_id);
                $_SESSION['message-success'] = 'Uw wachtwoord is gewijzigd!';
            }
        }
    }
}

$pagename = "settings";
?>

<?php include(ROOT_PATH . "includes/templates/header.php"); ?>
<div class="wrapper">
    <?php include(ROOT_PATH . "includes/templates/sidebar-admin.php"); ?>
    <div class="page-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Gebruikersgegevens</h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <tr>
                                    <td>Voornaam</td>
                                    <td><?php echo $voornaam = $user_data['voornaam']; ?></td>
                                </tr>
                                <tr>
                                    <td>Tussenvoegsel</td>
                                    <td><?php echo $tussenvoegsel = $user_data['tussenvoegsel']; ?></td>
                                </tr>
                                <tr>
                                    <td>Achternaam</td>
                                    <td><?php echo $achternaam = $user_data['achternaam']; ?></td>
                                </tr>
                                <tr>
                                    <td>Emailadres</td>
                                    <td><?php echo $emailadres = $user_data['emailadres']; ?></td>
                                </tr>
                                <tr>
                                    <td>Wachtwoord</td>
                                    <td><?php echo "• • • • • • • •" ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="panel-footer">
                            <button type="button" class="btn btn-default" data-toggle="modal" data-target="#wachtwoordwijzigen">Wachtwoord Wijzigen</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="contentblock">

        <!-- Popup -->
        <div class="modal fade" id="wachtwoordwijzigen" role="dialog">
            <div class="modal-dialog">
                <!-- Popup content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Wachtwoord wijzigen</h4>
                    </div>
                    <div class="table-responsive">
                        <div class="form-group">
                            <form action="" method="post">
                                <table class="table table-bordered table-hover">
                                    <tr>
                                        <td class="active">
                                            <label for="huidig">Huidig wachtwoord</label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="password" class="form-control" name="huidig" id="huidig">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="active">
                                            <label for="nieuw">Nieuw wachtwoord</label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="password" class="form-control" name="nieuw" id="nieuw">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="active">
                                            <label for="nieuwheraal">Herhaal nieuw wachtwoord</label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="password" class="form-control" name="nieuwheraal" id="nieuwheraal">
                                        </td>
                                    </tr>
                                </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="submit" class="btn btn-default" style="float:left;" name="wijzigen" value="Opslaan">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Sluiten</button>
                    </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
<?php include(ROOT_PATH . "includes/templates/footer.php"); ?>
