<?php
require_once("/../includes/init.php");

if(isset($_POST["leerling"])) {

	$leerling = $_POST["leerling"];
	$leerling = filter_var($leerling, FILTER_SANITIZE_NUMBER_INT);

	$leerling_gegevens = getStudentData($leerling);
	$generated_password = generate_random_password();
	$leerling_gegevens["wachtwoord"] = password_hash($generated_password, PASSWORD_BCRYPT);

	setTemporaryPasswordForMail($leerling_gegevens["wachtwoord"], $leerling_gegevens["gebruiker_id"]);

	$mail_gegevens = [
		"emailadres" => $leerling_gegevens["emailadres"],
		"voornaam" => $leerling_gegevens["voornaam"],
		"tussenvoegsel" => $leerling_gegevens["tussenvoegsel"],
		"achternaam" => $leerling_gegevens["achternaam"],
		"wachtwoord" => $generated_password,
	];

	$mail_content = createTempPasswordMail($mail_gegevens);
	sendMail($mail_content);
	

}