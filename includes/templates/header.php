<?php
header('Content-Type: text/html; charset=utf-8');
$data = getUserData($_SESSION['gebruiker_id']);
$gebruikersnaam = $data['voornaam']." ".$data['tussenvoegsel']." ".$data['achternaam'];
include(ROOT_PATH . "includes/partials/modals/about_modal.html.php"); 
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Examenanalyse</title>
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta http-equiv="Content-Type" content="text/html" charset="utf-8">
		<meta name="theme-color" content="#1BBC9B">
		<link rel="stylesheet" href="../assets/css/bootstrap.css" type="text/css" media="all">
		<link rel="stylesheet" href="../assets/css/style.css" type="text/css" media="all">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
        <script src="../assets/js/pace.min.js"></script>
        <link href="../assets/css/loadbar.css" rel="stylesheet" />
        <link rel="apple-touch-icon" sizes="57x57" href="../images/favicon/apple-icon-57x57.png">
		<link rel="apple-touch-icon" sizes="60x60" href="../images/favicon/apple-icon-60x60.png">
		<link rel="apple-touch-icon" sizes="72x72" href="../images/favicon/apple-icon-72x72.png">
		<link rel="apple-touch-icon" sizes="76x76" href="../images/favicon/apple-icon-76x76.png">
		<link rel="apple-touch-icon" sizes="114x114" href="../images/favicon/apple-icon-114x114.png">
		<link rel="apple-touch-icon" sizes="120x120" href="../images/favicon/apple-icon-120x120.png">
		<link rel="apple-touch-icon" sizes="144x144" href="../images/favicon/apple-icon-144x144.png">
		<link rel="apple-touch-icon" sizes="152x152" href="../images/favicon/apple-icon-152x152.png">
		<link rel="apple-touch-icon" sizes="180x180" href="../images/favicon/apple-icon-180x180.png">
		<link rel="icon" type="image/png" sizes="192x192"  href="../images/favicon/android-icon-192x192.png">
		<link rel="icon" type="image/png" sizes="32x32" href="../images/favicon/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="96x96" href="../images/favicon/favicon-96x96.png">
		<link rel="icon" type="image/png" sizes="16x16" href="../images/favicon/favicon-16x16.png">
		<link rel="manifest" href="../images/favicon/manifest.json">
		<meta name="msapplication-TileColor" content="#1BBC9B">
		<meta name="msapplication-TileImage" content="../images/favicon/ms-icon-144x144.png">
		<link rel="icon" href="../images/favicon/favicon.ico" type="image/x-icon" />
	</head>
	<body>
		<?php include(ROOT_PATH . "includes/partials/message.html.php"); ?>
		<div class="container-header">
			<div class="row">
				<div class="col-lg-8 col-md-7 col-sm-6 header">
					<img src="../images/dashboard/logo.png" alt="logo">
					<h3><b>Examenanalyse</b></h3>
				</div>
				<div class="col-lg-4 col-md-5 col-sm-6 header usermenu">
					<img src="../images/dashboard/maleicon.png" alt="usericon">
					<h3><?php $data = getUserData($_SESSION['gebruiker_id']);echo $data['voornaam']." ".$data['tussenvoegsel']." ".$data['achternaam'];?></h3>
					<a href="../includes/logout.php">
						<img src="../images/dashboard/logout.png" alt="logout">
					</a>
				</div>
			</div>
		</div>
	

