<?php
require_once(__DIR__ . "/includes/init.php");
session_start();

//Als gebruiker al is ingelogd , weer terugsturen naar het dashboard
if (isset($_SESSION['gebruiker_id'])) {
    if (checkRole($_SESSION['gebruiker_id']) == 3) {
        header('Location: ' . BASE_URL . 'admin/');
        exit;
    } else {
        header('Location: ' . BASE_URL . 'dashboard/');
        exit;
    }
}

//gegevens opvragen
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //checken als er gegevens ingevoerd zijn
    if (isset($_POST['user'], $_POST['password'])) {
        //checken of er geen lege waarden zijn ingevoerd
        if ($_POST['user'] == "Gebruikersnaam" OR $_POST['password'] == "Wachtwoord") {
            $_SESSION['message'] = 'Je moet eerst een gebruikersnaam en een wachtwoord invoeren voordat je kan inloggen!';
        } else {
            // overbodige ingevoerde spaties weghalen met functie trim
            $gebruiker = trim($_POST['user']);
            $wachtwoord = trim($_POST['password']);
            $gebruiker = filter_var($gebruiker, FILTER_VALIDATE_EMAIL);
            if (!$gebruiker) {
                $_SESSION['message'] = 'Voer een geldig e-mailadres in.';
            } else {
                $user_data = Authenticate($gebruiker);
                if ($gebruiker !== $user_data['emailadres']) {
                    $_SESSION['message'] = 'Gebruiker niet gevonden';
                }
                //naam gevonden, nu controleren of wachtwoord overeenkomt. zoja doorsturen.
                else {
                    //checken of gebruiker niet geblokkeerd is
                    if (checkIfAccountIsBlocked($gebruiker)) {
                        $_SESSION['message'] = 'Het account wat hoort bij ' . $gebruiker . ' is geblokkeerd! Probeer over enkele minuten weer.';
                        header('Location: ' . BASE_URL);
                        exit;
                    }
                    $match = password_verify($wachtwoord, $user_data["wachtwoord"]);
                    if ($match === FALSE) {
                        //teller toevoegen als deze niet bestaat zodat een gebruiker geblokkeerd wordt als hij tevaak met zelfde emailadres verkeerd inlogd.
                        if (!isset($_SESSION['blocked_couter' . $gebruiker])) {
                            $_SESSION['blocked_couter' . $gebruiker] = 0;
                        }
                        $_SESSION['blocked_couter' . $gebruiker] ++;
                        $aantalpogingen = 4 - $_SESSION['blocked_couter' . $gebruiker];
                        $_SESSION['message'] = 'Wachtwoord onjuist. U heeft nog ' . $aantalpogingen . ' pogingen om in te loggen met dit email adres.';
                        if ($_SESSION['blocked_couter' . $gebruiker] >= 4) {
                            //gebruiker blocken
                            blockUser($gebruiker);
                            $_SESSION['blocked_couter' . $gebruiker] = 0;
                            $_SESSION['message'] = 'Het account wat hoort bij' . $gebruiker . 'is nu geblokkeerd voor 10 minuten!';
                        }

                        header('Location: ' . BASE_URL);
                        exit;
                    } else if ($user_data['account_activated'] == 0) {
                        $_SESSION['account_activated'] = $user_data["account_activated"];
                        $_SESSION['gebruiker_id'] = $user_data["gebruiker_id"];
                        $_SESSION['timeout'] = time();
                        header('Location: ' . BASE_URL . 'password/');
                        exit;
                    }
                    $_SESSION['gebruiker_id'] = $user_data["gebruiker_id"];
                    $_SESSION['timeout'] = time();
                    if (checkRole($_SESSION['gebruiker_id']) == 3) {
                        $_SESSION['message-success'] = 'U bent nu ingelogd';
                        header('Location: ' . BASE_URL . 'admin/');
                        exit;
                    } else {
                        $_SESSION['message-success'] = 'U bent nu ingelogd';
                        header('Location: ' . BASE_URL . 'dashboard/');
                        exit;
                    }
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Examenanalyse</title>
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="theme-color" content="#1BBC9B">
        <link rel="stylesheet" href="assets/css/bootstrap.css" type="text/css" media="all">
        <link rel="stylesheet" href="assets/css/style.css" type="text/css" media="all">
        <script src="assets/js/pace.min.js"></script>
        <link href="assets/css/loadbar.css" rel="stylesheet" />
    </head>
    <body class="alternative-body">
<?php include(ROOT_PATH . "includes/partials/message.html.php"); ?>
        <div class="container loginmargin">
            <div class="row loginmargin">
                <div class="col-sm-6 col-sm-offset-3 loginblock">
                    <h1><center><b>Examenanalyse</b></center></h1>
                    <form method="post" action="">
                        <input type="text" class="form-control login-form" name="user" value="<?php if (isset($_POST['user'])) {
                                echo $_POST['user'];
                            } ?>" placeholder="<?php if (isset($_POST['user'])) {
                                echo $_POST['user'];
                            } else {
                                echo"Gebruikersnaam";
                            } ?>"/>
                        <input type="password" class="form-control login-form" name="password" placeholder="Wachtwoord"/>
                        <p class="help-block"><a href="wachtwoord_vergeten.php">Wachtwoord vergeten?</a></p>
                        <input type="submit" class="btn btn-default" value="Inloggen" />
                    </form>
                </div>
            </div>
        </div>
        <div class="copyrightelement">
            <center>© 2015 - 2016 Examenanalyse v1.0 BETA | Design by KBS ICTM1a KPM05</center>
        </div>


        <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
        <script src="<?php echo BASE_URL; ?>assets/js/alert_message.js"></script>

    </body>
</html>
