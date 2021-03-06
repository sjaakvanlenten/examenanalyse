<?php
//Verschillende functies die van belang zijn voor het admin beheer

//random wachtwoord genereren
function generate_random_password() 
{
	$charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()';
	$generated_password = substr(str_shuffle($charset), 0, 8);
	return $generated_password;
}

function createTempPasswordMail($gegevens) {
    require(__DIR__ . '/templates/emailheader.php');
    require(__DIR__ . '/templates/emailfooter.php');
        $mail_content = array(
            "address" => $gegevens["emailadres"],
            "name"    => "",
            "subject" => "Registratie",
            "body"    => $header."Beste " . $gegevens["voornaam"] . " " . (empty($gegevens["tussenvoegsel"]) ? $gegevens["tussenvoegsel"]: $gegevens["tussenvoegsel"] . ' ') . $gegevens["achternaam"] . ",<br></br><br></br>" . 
            "Er is een account voor jou aangemaakt om in te loggen op examenanalyse.jfsgsites.nl.<br><br> Hierbij je tijdelijke wachtwoord: <br></br>" . $gegevens["wachtwoord"] . "</b><br>Log in op de site om een wachtwoord in te stellen".$footer,
            "altbody" => "wachtwoordregistratie",
        );
        return $mail_content;
    }

//wachtwoord mailen naar gebruiker
function sendMail($mail_content) 
{
	require(__DIR__ . '/../config/mail_config.php');

	$mail->addAddress($mail_content["address"], $mail_content["name"]);
	$mail->Subject = $mail_content["subject"];
	$mail->Body = $mail_content["body"];
	$mail->AltBody = $mail_content["altbody"];

	if (!$mail->send()){ // email verzenden
		$_SESSION['message'] = 'Mailer Error: ' . $mail->ErrorInfo;
	}
}

function importLeerlingenExcelFile($file) 
{
    //---------------------------------GEGEVENS BESTAND IN VARIABLES ZETTEN-------------
    $filetmp = $file["tmp_name"];
    $filename = $file["name"];
    $filetype = $file["type"];
    $filepath = ROOT_PATH . "uploaded_files/" . $filename;
    //----------------------------------VALIDATIE EXCEL BESTAND-------------------------
    $finfo = finfo_open(); 
    $fileinfo = finfo_file($finfo, $filetmp, FILEINFO_MIME_TYPE);

    if ($fileinfo == ("application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") ||
        $fileinfo == ("image/png"))
    { //---------------------------------EXCEL BESTAND UPLOADEN-------------------------
        move_uploaded_file($filetmp, $filepath);
    }
    else {
        $_SESSION['message'] = "Upload een geldig bestand";
    }
    finfo_close($finfo);
    return $filename;   
}

function importLeerlingenWithExcel($upload)  
{
    require_once(ROOT_PATH . "includes/libs/PHPExcel_1.8.0/Classes/PHPExcel/IOFactory.php");

    $inputFileName = ROOT_PATH . 'uploaded_files/' . $upload;

    //  Read Excel workbook
    try {
        $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        $objReader->setReadDataOnly(true);
        $objPHPExcel = $objReader->load($inputFileName);
    } catch(Exception $e) {
        die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
    }

    //  Get worksheet dimensions
    $sheet = $objPHPExcel->getSheet(); 
    $maxCell = $sheet->getHighestRowAndColumn();
    $data = $sheet->rangeToArray('A1:' . $maxCell['column'] . $maxCell['row']);
    $data = array_map('array_filter', $data);
    $data = array_filter($data);
    
    return $data;
}

function passwordResetMail($user_data,$url_code) {
    require(__DIR__ . '/templates/emailheader.php');
    require(__DIR__ . '/templates/emailfooter.php');
        $mail_content = array(
            "address" => $user_data['emailadres'],
            "name"    => "",
            "subject" => "Wachtwoord wijzigen",
            "body"    => $header.'Beste ' . $user_data['voornaam'] . ' ' 
            . (empty($user_data['tussenvoegsel']) ? $user_data['tussenvoegsel']: $user_data['tussenvoegsel'] . ' ') 
            . $user_data['achternaam'] . ',<br>' 
            . '<br></br>Hierbij verzend ik de link om uw wachtwoord opnieuw in te stellen:<br></br> 
            <a href="http://examenanalyse.jfsgsites.nl/password/' . $url_code . '">examenanalyse.jfsgsites.nl/password/' . $url_code . '</a>'.$footer,
            "altbody" => "wachtwoordregistratie",
        );
        return $mail_content;
    }

