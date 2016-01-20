<?php

//instellingen voor de email module.

require_once(__DIR__ . '/../includes/libs/PHPMailer-master/PHPMailerAutoload.php');

$mail = new PHPMailer;

$mail->isSMTP();
$mail->SMTPAuth = true;

$mail->Host = 'smtp.gmail.com';
$mail->Username = 'kbswindesheim@gmail.com';
$mail->Password = 'windesheim';
$mail->SMTPSecure = 'tls';
$mail->Port = 587;

$mail->From = 'kbswindesheim@gmail.com';
$mail->FromName = 'P. Mourits';
$mail->addReplyTo('mo@jfsg.nl', 'P. Mourits');

$mail->isHTML(true); 