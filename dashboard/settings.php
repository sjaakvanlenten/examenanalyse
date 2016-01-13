<?php
require_once(__DIR__ . "/../includes/init.php");
checkSession();
$user_data = getUserData($_SESSION['gebruiker_id']);
//voor wachtwoord wijzigen
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
                <div class="col-sm-8">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Gebruikersgegevens</h3>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table gebruiker-gegevens">
                                    <tr>
                                        <th>
                                            Voornaam
                                        </th>
                                        <td>
                                            <?php echo $voornaam = $user_data['voornaam']; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            Tussenvoegsel
                                        </th>
                                        <td>
                                            <?php echo $tussenvoegsel = $user_data['tussenvoegsel']; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            Achternaam
                                        </th>
                                        <td>
                                            <?php echo $achternaam = $user_data['achternaam']; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            Emailadres
                                        </th>
                                        <td>
                                            <?php echo $emailadres = $user_data['emailadres']; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            Wachtwoord
                                        </th>
                                        <td>
                                            <?php echo "• • • • • • • •" ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="border: 0;">                                                      <!-- Laat de popup zien als je op de knop klikt -->
                                            <button type="button" class="btn btn-default btn-md" data-toggle="modal" data-target="#wachtwoordwijzigen">Wachtwoord Wijzigen</button>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="contentblock">

        <!-- Popup -->
        <div class="modal fade" id="wachtwoordwijzigen" role="dialog">
            <div class="modal-dialog modal-md">
                <!-- Popup content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Wachtwoord wijzigen</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <form action="" method="post">
                                <table class="table table-condensed wijzigwachtwoord">
                                    <tr class="inputrow">
                                        <th>
                                            <label for="name">Huidig wachtwoord</label>
                                        </th>
                                    </tr>
                                    <tr class="inputrow">
                                        <td>
                                            <input type="password" class="form-control" name="huidig" id="huidig">
                                        </td>
                                    </tr>
                                    <tr class="inputrow">
                                        <th>
                                            <label for="nieuw">Nieuw wachtwoord</label>
                                        </th>
                                    </tr>
                                    <tr class="inputrow">
                                        <td>
                                            <input type="password" class="form-control" name="nieuw" id="nieuw">
                                        </td>
                                    </tr>
                                    <tr class="inputrow">
                                        <th>
                                            <label for="nieuwheraal">Herhaal nieuw wachtwoord</label>
                                        </th>
                                    </tr>
                                    <tr class="inputrow">
                                        <td>
                                            <input type="password" class="form-control" name="nieuwheraal" id="nieuwheraal">
                                        </td>
                                    </tr>
                                </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="submit" class="btn btn-default" name="wijzigen" value="Opslaan">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Sluiten</button>
                    </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
<?php include(ROOT_PATH . "includes/templates/footer.php"); ?>
