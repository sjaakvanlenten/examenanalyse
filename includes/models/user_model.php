<?php

// checken of naam overeenkomt met een naam uit database
function addUser($gegevens, $db) {


    //checkt of tussenvoegsel leeg is gelaten, zoja dan wordt NULL ingevoerd.
    if ($gegevens["tussenvoegsel"] == "") {
        $gegevens["tussenvoegsel"] = NULL;
    }

    try {
        $stmt = $db->prepare("
            INSERT
            INTO gebruiker (
                voornaam,
                tussenvoegsel,
                achternaam,
                emailadres,
                email_code,
                wachtwoord,
                account_activated,
                role
            )
            VALUES (:voornaam, :tussenvoegsel, :achternaam, :emailadres, :email_code, :wachtwoord, :account_activated, :role) ");
        $stmt->execute(array(
            ':voornaam' => $gegevens["voornaam"],
            ':tussenvoegsel' => $gegevens["tussenvoegsel"],
            ':achternaam' => $gegevens["achternaam"],
            ':emailadres' => $gegevens["emailadres"],
            ':email_code' => $gegevens["email_code"],
            ':wachtwoord' => $gegevens["wachtwoord"],
            ':account_activated' => $gegevens["account_activated"],
            ':role' => $gegevens["role"],
        ));
    } catch (Exception $e) {
        $_SESSION['message'] = "Er ging wat fout.";
        $db->rollBack();
        header('Location: ' . $_SERVER['REQUEST_URI']);
        exit;
    }
}

//leraar toevoegen
function addTeacher($gegevens) {
    require(ROOT_PATH . "includes/database_connect.php");

    $db->beginTransaction();
    addUser($gegevens, $db);
    //check gebruiker_id voor het toevoegen van afkorting in tabel docent.
    try {
        $checkGebruikerId = $db->prepare("
            SELECT gebruiker_id
            FROM gebruiker
            WHERE emailadres = ?");
        $checkGebruikerId->bindParam(1, $gegevens["emailadres"]);
        $checkGebruikerId->execute();
    } catch (Exception $e) {
        $_SESSION['message'] = "Email adres kon niet worden gecontroleerd.";
        $db->rollBack();
        header('Location: ' . $_SERVER['REQUEST_URI']);
        exit;
    }

    $checkGebruikerId = $checkGebruikerId->fetch(PDO::FETCH_ASSOC);
    $gebruiker_id = $checkGebruikerId['gebruiker_id'];
    // $gebruiker_id bevat id van de leraar zodat de afkorting kan worden toegevoegd.

    try {
        $addAfkorting = $db->prepare("
            INSERT INTO docent (
                gebruiker_id,
                docent_afk
            )
            VALUES (?, ?) ");
        $addAfkorting->bindParam(1, $gebruiker_id);
        $addAfkorting->bindParam(2, $gegevens["docent_afkorting"]);
        $addAfkorting->execute();
        $_SESSION['message-success'] = "Docent is toegevoegd!";
    } catch (Exception $e) {
        $_SESSION['message'] = "Docent kon niet worden toegevoegd aan de database.";
        $db->rollBack();
        header('Location: ' . $_SERVER['REQUEST_URI']);
        exit;
    }
    $db->commit();
}

function Authenticate($user) {

    require(ROOT_PATH . "includes/database_connect.php");
    try {
        $results = $db->prepare("
            SELECT *
            FROM gebruiker
            WHERE gebruiker_id = ? OR emailadres = ?");
        $results->bindParam(1, $user);
        $results->bindParam(2, $user);
        $results->execute();
    } catch (Exception $e) {
        $_SESSION['message'] = "Data could not be retrieved from the database.";
        exit;
    }

    $match = $results->fetch(PDO::FETCH_ASSOC);
    return $match;
}

//checken of emailadres in gebruik is
function checkIfUserExists($email, $id = NULL) {
    require(ROOT_PATH . "includes/database_connect.php");

    if (isset($id)) {
        try {
            $results = $db->prepare("
                SELECT *
                FROM gebruiker G
                INNER JOIN leerling L
                ON G.gebruiker_id=L.gebruiker_id
                WHERE G.emailadres = ? OR L.leerling_id = ?
                ");
            $results->bindParam(1, $email);
            $results->bindParam(2, $id);
            $results->execute();
        } catch (Exception $e) {
            $_SESSION['message'] = "Er ging wat fout, probeer het nog eens.";
            exit;
        }
    } else {
        try {
            $results = $db->prepare("
                SELECT *
                FROM gebruiker
                WHERE emailadres = ?
                ");
            $results->bindParam(1, $email);
            $results->execute();
        } catch (Exception $e) {
            $_SESSION['message'] = "Er ging wat fout, probeer het nog eens.";
            exit;
        }
    }

    $match = $results->fetch(PDO::FETCH_ASSOC);

    if ($match == "") {

        return false;
    } else {
        return $match;
    }
}

//nog niet af
function getUserData($user) {

    require(ROOT_PATH . "includes/database_connect.php");
    try {
        $results = $db->prepare("
            SELECT *
            FROM gebruiker
            WHERE gebruiker_id = ?");
        $results->bindParam(1, $user);
        $results->execute();
    } catch (Exception $e) {
        $_SESSION['message'] = "Data could not be retrieved from the database.";
        exit;
    }

    $match = $results->fetch(PDO::FETCH_ASSOC);

    return $match;
}

function setTemporaryPasswordForMail($password, $user) {
    $temp_mail = 1;
    require(ROOT_PATH . "includes/database_connect.php");
    try {
        $results = $db->prepare("
            UPDATE gebruiker
            SET wachtwoord = ?, temp_mail = ?
            WHERE gebruiker_id = ?");
        $results->bindParam(1, $password);
        $results->bindParam(2, $temp_mail);
        $results->bindParam(3, $user);
        $results->execute();
    } catch (Exception $e) {
        $_SESSION['message'] = "Er ging wat fout.";
        exit;
    }
}

function updatePassword($password, $user) {
    require(ROOT_PATH . "includes/database_connect.php");
    $activate = 1;
    try {
        $results = $db->prepare("
            UPDATE gebruiker
            SET wachtwoord = ?,account_activated = ?
            WHERE gebruiker_id = ?");
        $results->bindParam(1, $password);
        $results->bindValue(2, $activate);
        $results->bindParam(3, $user);
        $results->execute();
    } catch (Exception $e) {
        $_SESSION['message'] = "Data could not be retrieved from the database.";
        exit;
    }
}

function checkEmailCode($user, $email_code) {

    require(ROOT_PATH . "includes/database_connect.php");
    try {
        $results = $db->prepare("
            SELECT *
            FROM gebruiker
            WHERE gebruiker_id = ? AND email_code = ?");
        $results->bindParam(1, $user);
        $results->bindParam(2, $email_code);
        $results->execute();
    } catch (Exception $e) {
        $_SESSION['message'] = "Data could not be retrieved from the database.";
        exit;
    }

    $match = $results->rowCount();

    if ($match < 1) {
        return FALSE;
    } else {
        return $match;
    }
}

function update_email_code($user, $email_code) {

    require(ROOT_PATH . "includes/database_connect.php");

    try {
        $stmt = $db->prepare("
            UPDATE gebruiker
            SET email_code = ?
            WHERE gebruiker_id = ?");
        $stmt->bindParam(1, $email_code);
        $stmt->bindParam(2, $user);
        $stmt->execute();
    } catch (Exception $e) {
        $_SESSION['message'] = "Data could not be retrieved from the database.";
        exit;
    }
    $_SESSION['message'] = "gelukt";
}

function checkRole($userid) {
    require(ROOT_PATH . "includes/database_connect.php");
    try {
        $checkRole = $db->prepare("
            SELECT role
            FROM gebruiker
            WHERE gebruiker_id = ?");
        $checkRole->bindParam(1, $userid);
        $checkRole->execute();
    } catch (Exception $e) {
        $_SESSION['message'] = "Rol niet gevonden.";
        exit;
    }
    $checkRole = $checkRole->fetch(PDO::FETCH_ASSOC);
    $checkRole = $checkRole["role"];
    return $checkRole;
}

//student toevoegen
function addStudent($leerling_gegevens) {
    require(ROOT_PATH . "includes/database_connect.php");

    $db->beginTransaction();
    addUser($leerling_gegevens, $db);
    //vind gebruikers_id doormiddel van emailadres.
    try {
        $checkGebruikerId = $db->prepare("
            SELECT gebruiker_id
            FROM gebruiker
            WHERE emailadres = ?");
        $checkGebruikerId->bindParam(1, $leerling_gegevens["emailadres"]);
        $checkGebruikerId->execute();
    } catch (Exception $e) {
        $_SESSION['message'] = "Email adres kon niet worden gecontroleerd.";
        $db->rollBack();
        header('Location: ' . $_SERVER['REQUEST_URI']);
        exit;
    }

    $checkGebruikerId = $checkGebruikerId->fetch(PDO::FETCH_ASSOC);
    $gebruiker_id = $checkGebruikerId['gebruiker_id'];

    //vind klas doormiddel van klas_id.
    try {
        $checkKlasId = $db->prepare("
            SELECT klas_id
            FROM klas
            WHERE klas = ?");
        $checkKlasId->bindParam(1, $leerling_gegevens["klas"]);
        $checkKlasId->execute();
    } catch (Exception $e) {
        $_SESSION['message'] = "Klas id kan niet worden gecontroleerd.";
        $db->rollBack();
        header('Location: ' . $_SERVER['REQUEST_URI']);
        exit;
    }

    $checkKlasId = $checkKlasId->fetch(PDO::FETCH_ASSOC);
    $klas_id = $checkKlasId['klas_id'];

    // $gebruiker_id bevat id van de leraar zodat de afkorting kan worden toegevoegd.

    try {
        $addLeerling_Id = $db->prepare("
            INSERT INTO leerling (
                gebruiker_id,
                leerling_id,
                klas_id
            )
            VALUES (?, ?, ?) ");
        $addLeerling_Id->bindParam(1, $gebruiker_id);
        $addLeerling_Id->bindParam(2, $leerling_gegevens["leerling_id"]);
        $addLeerling_Id->bindParam(3, $klas_id);
        $addLeerling_Id->execute();
        $_SESSION['message-success'] = "Leerling is toegevoegd!";
    } catch (Exception $e) {
        $_SESSION['message'] = "Leerling kon niet worden toegevoegd aan de database.";
        $db->rollBack();
        header('Location: ' . $_SERVER['REQUEST_URI']);
        exit;
    }
    $db->commit();
}

function getLeerlingenKlas($klas) {

    require(ROOT_PATH . "includes/database_connect.php");
    try {
        $stmt = $db->prepare("
            SELECT klas_id
            FROM klas
            WHERE klas = ?
            ");
        $stmt->bindParam(1, $klas);
        $stmt->execute();
    } catch (Exception $e) {
        $_SESSION['message'] = "Er ging wat fout.";
        exit;
    }
    $klas = $stmt->fetch(PDO::FETCH_ASSOC);

    try {
        $stmt = $db->prepare("
            SELECT L.leerling_id,G.voornaam,G.tussenvoegsel,G.achternaam,G.emailadres,G.gebruiker_id
            FROM leerling L
            INNER JOIN gebruiker G
            ON L.gebruiker_id=G.gebruiker_id
            WHERE L.klas_id = ?
            ORDER BY G.achternaam ASC
            ");
        $stmt->bindParam(1, $klas["klas_id"]);
        $stmt->execute();
    } catch (Exception $e) {
        $_SESSION['message'] = "Er ging wat fout.";
        exit;
    }

    $results = $stmt->fetchall(PDO::FETCH_ASSOC);
    return $results;
}

function updateStudent($gegevens, $gebruiker_id) {

    require(ROOT_PATH . "includes/database_connect.php");

    $db->beginTransaction();

    try {
        $stmt = $db->prepare("
            UPDATE gebruiker
            SET voornaam = ?,tussenvoegsel = ?, achternaam = ?, emailadres = ?
            WHERE gebruiker_id = ?
            ");
        $stmt->bindParam(1, $gegevens["voornaam"]);
        $stmt->bindParam(2, $gegevens["tussenvoegsel"]);
        $stmt->bindParam(3, $gegevens["achternaam"]);
        $stmt->bindParam(4, $gegevens["emailadres"]);
        $stmt->bindParam(5, $gebruiker_id);
        $stmt->execute();
    } catch (Exception $e) {
        $_SESSION['message'] = "Er ging wat fout.";
        $db->rollBack();
        header('Location: ' . $_SERVER['REQUEST_URI']);
        exit;
    }

    try {
        $stmt = $db->prepare("
            UPDATE leerling
            SET leerling_id= ?
            WHERE gebruiker_id = ?
            ");
        $stmt->bindParam(1, $gegevens["leerling_id"]);
        $stmt->bindParam(2, $gebruiker_id);
        $stmt->execute();
    } catch (Exception $e) {
        $_SESSION['message'] = "Er ging wat fout.";
        $db->rollBack();
        header('Location: ' . $_SERVER['REQUEST_URI']);
        exit;
    }

    $db->commit();
    $_SESSION["message-success"] = "Leerling gegevens zijn geupdate";
}

function deleteStudent($gebruiker_id) {

    require(ROOT_PATH . "includes/database_connect.php");

    $db->beginTransaction();

    try {
        $stmt = $db->prepare("
            DELETE FROM gebruiker
            WHERE gebruiker_id = ?
            ");
        $stmt->bindParam(1, $gebruiker_id);
        $stmt->execute();
    } catch (Exception $e) {
        $_SESSION['message'] = "Er ging wat fout.";
        $db->rollBack();
        header('Location: ' . $_SERVER['REQUEST_URI']);
        exit;
    }

    try {
        $stmt = $db->prepare("
            DELETE FROM leerling
            WHERE gebruiker_id = ?
            ");
        $stmt->bindParam(1, $gebruiker_id);
        $stmt->execute();
    } catch (Exception $e) {
        $_SESSION['message'] = "Er ging wat fout.";
        $db->rollBack();
        header('Location: ' . $_SERVER['REQUEST_URI']);
        exit;
    }

    try {
        $stmt = $db->prepare("
            DELETE FROM score
            WHERE gebruiker_id = ?
            ");
        $stmt->bindParam(1, $gebruiker["gebruiker_id"]);
        $stmt->execute();
    } catch (Exception $e) {
        $_SESSION['message'] = "Er ging wat fout.";
        $db->rollBack();
        header('Location: ' . $_SERVER['REQUEST_URI']);
        exit;
    }

    try {
        $stmt = $db->prepare("
            DELETE FROM resultaat
            WHERE gebruiker_id = ?
            ");
        $stmt->bindParam(1, $gebruiker["gebruiker_id"]);
        $stmt->execute();
    } catch (Exception $e) {
        $_SESSION['message'] = "Er ging wat fout.";
        $db->rollBack();
        header('Location: ' . $_SERVER['REQUEST_URI']);
        exit;
    }

    $db->commit();
    $_SESSION["message-success"] = "Leerling verwijdert";
}

function viewTeacher() {
    require(ROOT_PATH . "includes/database_connect.php");

    try {
        $haalgebruikersop = $db->prepare("
            SELECT docent.gebruiker_id, voornaam,  tussenvoegsel, achternaam, docent_afk, emailadres, account_activated
            FROM docent Join gebruiker ON docent.gebruiker_id = gebruiker.gebruiker_id
            WHERE role = 2
            ");
        $haalgebruikersop->execute();
    } catch (Exception $e) {
        echo $error_message = "Gebruikers kunnen niet worden gevonden";
        exit;
    }
    $haalgebruikersop = $haalgebruikersop->fetchAll();
    return $haalgebruikersop;
}

//Leraar verwijderen

function deleteTeacher($sleutel) {
    require(ROOT_PATH . "includes/database_connect.php");

    try {
        $verwijderleraar = $db->prepare("
            DELETE FROM docent
            Where gebruiker_id = ?
            ");
        $verwijderleraar->bindParam(1, $sleutel);
        $verwijdergebruiker = $db->prepare("
            DELETE FROM gebruiker
            WHERE gebruiker_id = ?
            ");
        $verwijdergebruiker->bindParam(1, $sleutel);
        $verwijderleraar->execute();
        $verwijdergebruiker->execute();
        $_SESSION["message-success"] = "Docent verwijdert";
    } catch (Exception $e) {
        echo $error_message = "Gebruikers kunnen niet worden gevonden";
        exit;
    }
    return $verwijderleraar;
}

//Leeraar bewerken

function updateTeacher($gebruiker_id, $voornaam, $tussenvoegsel, $achternaam, $emailadres, $docent_afk) {
    require(ROOT_PATH . "includes/database_connect.php");

    try {
        $leeraarBewerkenTabelGebruiker = $db->prepare("
            Update gebruiker
            set voornaam = ?,
            tussenvoegsel = ?,
            achternaam = ?,
            emailadres = ?
            Where gebruiker_id = ?
            ");
        $leeraarBewerkenTabelDocent = $db->prepare("
            Update docent
            set docent_afk = ?
            Where docent.gebruiker_id = ?
            ");
        $leeraarBewerkenTabelGebruiker->bindParam(1, $voornaam);
        $leeraarBewerkenTabelGebruiker->bindParam(2, $tussenvoegsel);
        $leeraarBewerkenTabelGebruiker->bindParam(3, $achternaam);
        $leeraarBewerkenTabelGebruiker->bindParam(4, $emailadres);
        $leeraarBewerkenTabelGebruiker->bindParam(5, $gebruiker_id);
        $leeraarBewerkenTabelDocent->bindParam(1, $docent_afk);
        $leeraarBewerkenTabelDocent->bindParam(2, $gebruiker_id);
        $leeraarBewerkenTabelGebruiker->execute();
        $leeraarBewerkenTabelDocent->execute();
        $_SESSION["message-success"] = "Docent geupdated";
    } catch (Exception $e) {
        echo $error_message = "Gebruikers kunnen niet worden gevonden";
        exit;
    }
}

function getStudentData($user) {

    require(ROOT_PATH . "includes/database_connect.php");
    try {
        $results = $db->prepare("
            SELECT gebruiker_id
            FROM leerling
            WHERE leerling_id = ?");
        $results->bindParam(1, $user);
        $results->execute();
    } catch (Exception $e) {
        $_SESSION['message'] = "Data could not be retrieved from the database.";
        exit;
    }

    $match = $results->fetch(PDO::FETCH_ASSOC);

    try {
        $results = $db->prepare("
            SELECT *
            FROM gebruiker
            WHERE gebruiker_id = ?");
        $results->bindParam(1, $match["gebruiker_id"]);
        $results->execute();
    } catch (Exception $e) {
        $_SESSION['message'] = "Data could not be retrieved from the database.";
        exit;
    }
    $match = $results->fetch(PDO::FETCH_ASSOC);

    return $match;
}

function blockUser($emailadres) {
    require(ROOT_PATH . "includes/database_connect.php");
    $tijd = time() + (10 * 60);

    try {
        $results = $db->prepare("
			UPDATE gebruiker
			SET blocked = ?
			WHERE emailadres = ?");
        $results->bindParam(1, $tijd);
        $results->bindParam(2, $emailadres);
        $results->execute();
    } catch (Exception $e) {
        $_SESSION['message'] = "Data could not be retrieved from the database.";
        exit;
    }
}

function checkIfAccountIsBlocked($emailadres) {
    require(ROOT_PATH . "includes/database_connect.php");
    $tijd = time();
    try {
        $results = $db->prepare("
			SELECT blocked FROM gebruiker WHERE emailadres = ?");
        $results->bindParam(1, $emailadres);
        $results->execute();
    } catch (Exception $e) {
        $_SESSION['message'] = "Data could not be retrieved from the database.";
        exit;
    }
    $results = $results->fetch(PDO::FETCH_ASSOC);
    $checkIfBlocked = $results["blocked"];
    if ($tijd < $checkIfBlocked && $checkIfBlocked != NULL) {
        return true;
    } else {
        return false;
    }
}

function countAllusersbyRole($userrole) {
    require(ROOT_PATH . "includes/database_connect.php");
    try {
        $stmt = $db->prepare("
            SELECT COUNT(*) AS '0'
            FROM gebruiker
            WHERE role = ?
            ");
        $stmt->bindParam(1, $userrole);
        $stmt->execute();
    } catch (Exception $e) {
        $_SESSION['message'] = "Data could not be retrieved from the database.";
        exit;
    }
    $results = $stmt->fetch(PDO::FETCH_ASSOC);
    return $results['0'];
}
