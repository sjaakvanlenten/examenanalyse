<?php
require_once(__DIR__ . "/../includes/init.php");

$pagename = "docenten";
checkSession();
checkIfAdmin();

if (isset($_GET['verwijderdocent'])) {
    $verwijder = $_GET['verwijderdocent'];
    deleteTeacher($verwijder);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    //****************  DOCENT TOEVOEGEN ******************//

    if (isset($_POST['submit_docent'])) {
        if (isset($_POST['voornaam'], $_POST['achternaam'], $_POST['afkorting'], $_POST['emailadres'])) {
            //checken of er geen lege waarden zijn ingevoerd
            if ($_POST['voornaam'] == "" OR $_POST['achternaam'] == "" OR $_POST['afkorting'] == "" OR $_POST['emailadres'] == "") {
                $_SESSION['message'] = "Je moet alle gegevens invullen!";
            } else {
                // overbodige ingevoerde spaties weghalen met functie trim
                $voornaam = filter_var(trim($_POST['voornaam']), FILTER_SANITIZE_STRING);

                $achternaam = filter_var(trim($_POST['achternaam']), FILTER_SANITIZE_STRING);
                $tussenvoegsel = filter_var($_POST['tussenvoegsel'], FILTER_SANITIZE_STRING); //tussenvoegsel mag spatie bevatten
                $docent_afkorting = filter_var(trim($_POST['afkorting']), FILTER_SANITIZE_STRING);
                $emailadres = filter_var(trim($_POST['emailadres']), FILTER_VALIDATE_EMAIL);
                if (!$emailadres) {
                    $_SESSION['message'] = 'Voer een geldig e-mailadres in.';
                } else {
                    $role = 2; // is leraar
                    $account_activated = 0; //account is nog niet geactiveerd, dit wordt pas gedaan als gebruiker eerste keer inlogt.
                    $generated_password = generate_random_password();
                    $wachtwoord = password_hash($generated_password, PASSWORD_BCRYPT);
                    $email_code = md5($voornaam + microtime());

                    $gegevens = [
                        "voornaam" => $voornaam,
                        "tussenvoegsel" => $tussenvoegsel,
                        "achternaam" => $achternaam,
                        "emailadres" => $emailadres,
                        "email_code" => $email_code,                      
                        "wachtwoord" => $wachtwoord,
                        "account_activated" => $account_activated,
                        "role" => $role,
                        "docent_afkorting" => $docent_afkorting,
                    ];

                    //checken of email en afkorting uniek zijn
                    if (checkIfUserExists($gegevens["emailadres"]) === FALSE) {
                        //leraar toevoegen
                        addTeacher($gegevens);
                        //wachtwoord mailen naar gebruiker
                        $mail_gegevens = [
                            "emailadres" => $gegevens["emailadres"],
                            "voornaam" => $gegevens["voornaam"],
                            "tussenvoegsel" => $gegevens["tussenvoegsel"],
                            "achternaam" => $gegevens["achternaam"],
                            "wachtwoord" => $generated_password,
                        ];
                        $mail_content = createTempPasswordMail($mail_gegevens);
                        sendMail($mail_content);
                    } else {
                        //email adres is al in gebruik.
                        $_SESSION['message'] = "Email adres is al in gebruik";
                    }
                }
            }
        }
    } else {
        // voor als een docent bewerkt wordt
        $gebruiker_id = filter_var(trim($_POST['gebruiker_id']), FILTER_SANITIZE_STRING);
        $voornaam = filter_var(trim($_POST['voornaam']), FILTER_SANITIZE_STRING);
        $achternaam = filter_var(trim($_POST['achternaam']), FILTER_SANITIZE_STRING);
        $tussenvoegsel = filter_var($_POST['tussenvoegsel'], FILTER_SANITIZE_STRING); //tussenvoegsel mag spatie bevatten
        $docent_afkorting = filter_var(trim($_POST['afkorting']), FILTER_SANITIZE_STRING);
        $emailadres = filter_var(trim($_POST['emailadres']), FILTER_VALIDATE_EMAIL);
        if (!$emailadres) {
            $_SESSION['message'] = 'Voer een geldig e-mailadres in.';
        } else {
            $role = 2; // is leraar
            $account_activated = 0; //account is nog niet geactiveerd, dit wordt pas gedaan als gebruiker eerste keer inlogt.
            $generated_password = generate_random_password();
            $wachtwoord = password_hash($generated_password, PASSWORD_BCRYPT);
            $email_code = md5($voornaam + microtime());

            //returned $generated_password

            $gegevens = [
                "gebruiker_id" => $gebruiker_id,
                "voornaam" => $voornaam,
                "tussenvoegsel" => $tussenvoegsel,
                "achternaam" => $achternaam,
                "emailadres" => $emailadres,
                "docent_afkorting" => $docent_afkorting
            ];
            //gegevens updaten:
            updateTeacher($gegevens["gebruiker_id"], $gegevens["voornaam"], $gegevens["tussenvoegsel"], $gegevens["achternaam"], $gegevens["emailadres"], $gegevens["docent_afkorting"]);
        }
    }
}
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
                            <h3 class="panel-title">Overzicht docenten</h3>
                        </div>
                        <div class="panel-body">
                            <?php
                            $leraargegevens = viewTeacher();
                            if (empty($leraargegevens)) {
                                echo"Het lijkt er op dat er nog geen docenten bestaan, klik op \"Toevoegen\" om een docent toe te voegen";
                            } else {
                                echo"Hieronder ziet u een overzicht van alle docenten. Hier kunt u deze bewerken of verwijderen. Ook kunt u een nieuwe docenten toevoegen door op de knop \"Toevoegen \" te klikken";
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="panel panel-default">
                        <?php
                        $leraargegevens = viewTeacher();
                        $t = 0;
                        $x = count($leraargegevens);
                        foreach ($leraargegevens as $leraargegeven) {
                            if ($t == 0) {
                                ?>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <tr>
                                            <td class="active"><b>Voornaam</b></td>
                                            <td class="active"><b>Tussenvoegsel</b></td>
                                            <td class="active"><b>Achternaam</b></td>
                                            <td class="active"><b>Afkorting</b></td>
                                            <td class="active"><b>Email adres</b></td>
                                            <td class="active"></td>
                                        </tr>
                                        <?php
                                    }
                                    $t++;
                                    ?>
                                    <tr>
                                        <td><?php echo $leraargegeven['voornaam']; ?></td>
                                        <td><?php
                                            $tussenvoegsel = $leraargegeven['tussenvoegsel'];
                                            if ($tussenvoegsel != NULL) {
                                                echo $tussenvoegsel;
                                            } else {
                                                echo "-";
                                            }
                                            ?></td>
                                        <td><?php echo $leraargegeven['achternaam']; ?></td>
                                        <td><?php echo $leraargegeven['docent_afk']; ?></td>
                                        <td><?php echo $leraargegeven['emailadres']; ?></td>
                                        <td>
                                            <button type="button" class="btn btn-default" data-toggle="modal" data-target="#<?php echo $leraargegeven['gebruiker_id']; ?>">Bewerken</button>
                                        </td>
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
                            <button type="button" class="btn btn-default" data-toggle="modal" data-target="#docenttoevoegen">Toevoegen</button>
                        </div>
                        <?php include(ROOT_PATH . "includes/partials/modals/docent_bewerk_modal.html.php"); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include(ROOT_PATH . "includes/templates/footer.php"); ?>
