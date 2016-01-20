<?php
require_once(__DIR__ . "/../includes/init.php");
session_start();
if (!isset($_SESSION['gebruiker_id'])) {
	if(!isset($_SESSION['account_activated'])) {
		if (!isset($_GET['id'],$_GET['email_code'])) {
		$_SESSION['message'] = 'Toegang geweigerd.';
		header('Location: ' . BASE_URL);
		exit;
		} else {
			$user_id = intval($_GET['id']);
			$email_code = $_GET['email_code'];
			if(!checkEmailCode($user_id,$email_code)) {
				unset($user_id,$email_code);
				$_SESSION['message'] = 'Toegang geweigerd.';
				header('Location: ' . BASE_URL);
				exit;
			}
			$pass = 1;
		}
	}
}
if(!isset($_SESSION['account_activated']) AND !isset($pass)) {
	$_SESSION['message'] = 'Toegang geweigerd.';
	header('Location: ' . BASE_URL);
	exit;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$pass = $_POST['pass'];
	$pass_confirm = $_POST['pass_confirm'];
	
	if(passTest($pass, $pass_confirm) === TRUE) {
		if(isset($_SESSION['gebruiker_id'])) {
			$user_id = $_SESSION['gebruiker_id'];
		}
		$password = password_hash($pass, PASSWORD_BCRYPT);
		//wachtwoord invoeren in de database en activate_account op 1 zetten ( dus geactiveerd )
		updatePassword($password, $user_id);
		unset($_SESSION['account_activated']);
		if(isset($user_id,$email_code)) {
			//nieuwe email code aanmaken en opslaan.
			$email_code = md5($user_id + microtime());
			update_email_code($user_id,$email_code);
			$_SESSION['message-success'] = 'Uw wachtwoord is gewijzigd!';
			header('Location: ' . BASE_URL);
			exit;
		}
		else {
			header('Location: ' . BASE_URL . 'dashboard/');
			exit;
		}		
	}
}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Examen Analyse</title>
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="theme-color" content="#1BBC9B">
		<link rel="stylesheet" href="../assets/css/bootstrap.css" type="text/css" media="all">
		<link rel="stylesheet" href="../assets/css/style.css" type="text/css" media="all">
		<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
	</head>
	<body class="alternative-body">
		<?php include(ROOT_PATH . "includes/partials/message.html.php"); ?>

			
			<div class="container loginmargin">
			<div class="row loginmargin">
				<div class="col-sm-6 col-sm-offset-3 loginblock">
					<h1><center><b>WACHTWOORD INSTELLEN</b></center></h1>
					<form autocomplete="off" method="post" action="">
						<input type="password" class="form-control login-form" name = "pass" placeholder="Nieuw wachtwoord">
						<input type="password" class="form-control login-form" name = "pass_confirm" placeholder="Herhaal wachtwoord">
						<input  class="btn btn-default" type="submit" value="Stel wachtwoord in">
					</form>
				</div>
 
		</div>
		</div>
		<div class="copyrightelement">
			<center>Copyright &copy; 2015. All Rights Reserved | Design by KBS ICTM1a KPM05</center>
		</div>
		<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
		<script src="../assets/js/alert_message.js"></script>
	</body>
</html>