<?php
require_once('/../config/config.php');
require_once(ROOT_PATH . "includes/init.php");
$pagename = "dashboard";
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
                <?php
                if (checkRole($_SESSION['gebruiker_id']) == 1) {
                    ?>
                    <div class="col-sm-12">
                        <div class="panel panel-default">
                            <div class="panel-heading"><h3 class="panel-title">Voortgang weergegeven in een cijfer van de gemaakte examens.</h3></div>
                            <div class="panel-body">
                                <?php
                                include(ROOT_PATH . "includes/partials/cijfersgemaakteexamens.html.php");
                                ?>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                ?>
                <?php
                if (checkRole($_SESSION['gebruiker_id']) == 1) {
                    ?>
                    <div class="col-sm-4" >
                        <div class="panel panel-default" style="min-height:526px;">
                            <div class="panel-heading"><h3 class="panel-title">Welkom <?php $data = getUserData($_SESSION['gebruiker_id']);
                echo $data['voornaam'] . " " . $data['tussenvoegsel'] . " " . $data['achternaam']; ?></h3></div>
                            <div class="panel-body">
                                Beste leerling,<br><br>
                                Deze applicatie gaat jou helpen bij het halen van jou examen Nederlands! Door gebruik te maken deze applicatie zul je niet alleen doelgerichter leren, maar ook minder tijd kwijt zijn aan de voorbereiding van het examen. Nadat je in de klas alle onderdelen die op het examen Nederlands hebt behandeld ga je natuurlijk oefenexamens maken. Deze applicatie biedt jou de mogelijkheid om per examen per vraag de score in te voeren. De applicatie gaat voor jou een overzicht maken die per categorie aangeeft hoeveel jij nog moet oefenen. Ook krijg je adviezen welke examenvragen je nog een keer zou kunnen oefenen om beter te worden in een bepaalde categorie. We hopen dat je wat aan deze appliatie zal hebben.
                                <br><br> Veel succes bij het halen van je examens!
                            </div>
                        </div>
                    </div>
                    <?php
                } else {
                    ?>
                    <div class="col-sm-4" >
                        <div class="panel panel-default" style="min-height:526px;">
                            <div class="panel-heading"><h3 class="panel-title">Welkom <?php $data = getUserData($_SESSION['gebruiker_id']);
                    echo $data['voornaam'] . " " . $data['tussenvoegsel'] . " " . $data['achternaam']; ?></h3></div>
                            <div class="panel-body">
                                Beste leraar,<br><br>
                                Hier kunt u de voortgang inzien van de leerlingen die in uw klassen zijn gezet. U kunt hiervoor op 'Resultaten' klikken.
                                <br><br>Heeft u of een van uw leerlingen een bug gevonden in de applicatie? Geef dit dan alstublieft door aan Dhr. Mourits
                            </div>
                        </div>
                    </div>
                    <?php
                }
                ?>
                <div class="col-sm-8" >
                    <div class="panel panel-default">
                        <div class="panel-heading"><h3 class="panel-title">Categorieverdeling in alle examens</h3></div>
                        <div class="panel-body">
                            <?php
                            include(ROOT_PATH . "includes/partials/categorieverdeling.html.php");
                            ?>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>
<?php include(ROOT_PATH . "includes/templates/footer.php"); ?>
