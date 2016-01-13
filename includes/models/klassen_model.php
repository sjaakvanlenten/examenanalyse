<?php

//transacties delete klas!!
function getAantalLeerlingenKlas($klas) {

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
            SELECT leerling_id
            FROM leerling
            WHERE klas_id = ?
            ");
        $stmt->bindParam(1, $klas["klas_id"]);
        $stmt->execute();
    } catch (Exception $e) {
        $_SESSION['message'] = "Er ging wat fout.";
        exit;
    }
    $results = $stmt->rowCount();

    return $results;
}

function getKlas($klas) {

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
    $results = $stmt->rowCount();

    return $results;
}

function getKlassen() {

    require(ROOT_PATH . "includes/database_connect.php");
    try {
        $stmt = $db->prepare("
            SELECT *
            FROM klas
            ");
        $stmt->execute();
    } catch (Exception $e) {
        $_SESSION['message'] = "Er ging wat fout.";
        exit;
    }
    $results = $stmt->fetchall(PDO::FETCH_ASSOC);

    return $results;
}

function addKlas($klas) {

    if (!isset($klas["examenjaar"])) {
        $klas["examenjaar"] = '';
    }
    if (!isset($klas["docent_afk"])) {
        $klas["docent_afk"] = '';
    }
    if (!isset($klas["niveau"])) {
        $klas["niveau"] = '';
    }
    require(ROOT_PATH . "includes/database_connect.php");
    try {
        $stmt = $db->prepare("
            INSERT INTO klas (
                klas,
                examenjaar,
                niveau,
                docent_afk
            )
            VALUES(?,?,?,?) ");
        $stmt->bindParam(1, $klas["klas"]);
        $stmt->bindParam(2, $klas["examenjaar"]);
        $stmt->bindParam(3, $klas["niveau"]);
        $stmt->bindParam(4, $klas["docent_afk"]);
        $stmt->execute();
    } catch (Exception $e) {
        $_SESSION['message'] = "Er ging wat fout.";
        header('Location: ' . $_SERVER['REQUEST_URI']);
        exit;
    }
    $_SESSION["message-success"] = "Klas is toegevoegd!";
}

function updateKlas($klas, $klas_id) {

    require(ROOT_PATH . "includes/database_connect.php");

    try {
        $stmt = $db->prepare("
            UPDATE klas
            SET klas = ?, examenjaar = ?,  niveau = ?, docent_afk = ?
            WHERE klas_id = ?
            ");
        $stmt->bindParam(1, $klas["klas"]);
        $stmt->bindParam(2, $klas["examenjaar"]);
        $stmt->bindParam(3, $klas["niveau"]);
        $stmt->bindParam(4, $klas["docent_afk"]);
        $stmt->bindParam(5, $klas_id);
        $stmt->execute();
    } catch (Exception $e) {
        $_SESSION['message'] = "Er ging wat fout.";
        header('Location: ' . $_SERVER['REQUEST_URI']);
        exit;
    }
    $_SESSION["message-success"] = "Klas gegevens zijn geupdate";
}

function deleteKlas($klas_id) {

    require(ROOT_PATH . "includes/database_connect.php");

    $db->beginTransaction();

    try {
        $stmt = $db->prepare("
            SELECT gebruiker_id
            FROM leerling
            WHERE klas_id = ?
            ");
        $stmt->bindParam(1, $klas_id);
        $stmt->execute();
    } catch (Exception $e) {
        $_SESSION['message'] = "Er ging wat fout.";
        $db->rollBack();
        header('Location: ' . $_SERVER['REQUEST_URI']);
        exit;
    }

    $gebruikers = $stmt->fetchall(PDO::FETCH_ASSOC);

    //************ ALLE LEERLINGEN VAN DEZE KLAS VERWIJDEREN **********//
    foreach ($gebruikers as $gebruiker) {

        try {
            $stmt = $db->prepare("
                DELETE FROM gebruiker
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
                DELETE FROM leerling
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
    }

    try {
        $stmt = $db->prepare("
        DELETE FROM klas
        WHERE klas_id = ?
        ");
        $stmt->bindParam(1, $klas_id);
        $stmt->execute();
    } catch (Exception $e) {
        $_SESSION['message'] = "Er ging wat fout.";
        $db->rollBack();
        header('Location: ' . $_SERVER['REQUEST_URI']);
        exit;
    }

    $db->commit();
    $_SESSION["message-success"] = "Klas verwijdert";
}

function getNiveauFromStudent($gebruiker_id) {
    require(ROOT_PATH . "includes/database_connect.php");
    try {
        $stmt = $db->prepare("
            SELECT K.niveau
            FROM klas K
            JOIN leerling L
            on L.klas_id = K.klas_id
            WHERE L.gebruiker_id = ?
            ");
        $stmt->bindParam(1, $gebruiker_id);
        $stmt->execute();
    } catch (Exception $e) {
        $_SESSION['message'] = "Er ging wat fout.";
        header('Location: ' . $_SERVER['REQUEST_URI']);
        exit;
    }
    $results = $stmt->fetchall(PDO::FETCH_ASSOC);
    foreach ($results as $key => $value) {
        $a = $value['niveau'];
    }
    return $a;
}

function getmultipleKlasfromoneTeacher($gebruiker_id) {
    require(ROOT_PATH . "includes/database_connect.php");
    try {
        $docentafk = $db->prepare("
            SELECT docent_afk
            FROM docent
            WHERE gebruiker_id = ?
            ");
        $docentafk->bindParam(1, $gebruiker_id);
        $docentafk->execute();
        $docentafk = $docentafk->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        $_SESSION['message'] = "Docent afkorting ophalen mislukt";
    }
    try {
        $stmt = $db->prepare("
            SELECT *
            FROM klas
            WHERE docent_afk = ?
            ");
        $stmt->bindParam(1, $docentafk['docent_afk']);
        $stmt->execute();
    } catch (Exception $e) {
        $_SESSION['message'] = "Klassen ophalen mislukt";
    }
    $match = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $match;
}

function getInfooneKlas($klas_id) {
    require(ROOT_PATH . "includes/database_connect.php");
    try {
        $stmt = $db->prepare("
            SELECT *
            FROM klas
            WHERE klas_id = ?
            ");
        $stmt->bindParam(1, $klas_id);
        $stmt->execute();
    } catch (Exception $e) {
        $_SESSION['message'] = "Informatie ophalen mislukt";
    }
    $results = $stmt->fetch(PDO::FETCH_ASSOC);
    return $results;
}

function getStudentNamesfromoneKlas($klas_id) {
    require(ROOT_PATH . "includes/database_connect.php");
    try {
        $stmt = $db->prepare("
                SELECT g.gebruiker_id, g.voornaam, g.tussenvoegsel, g.achternaam
                FROM gebruiker g
                JOIN leerling l ON g.gebruiker_id = l.gebruiker_id
                WHERE l.klas_id = ?
            ");
        $stmt->bindParam(1, $klas_id);
        $stmt->execute();
    } catch (Exception $e) {
        $_SESSION['message'] = "Informatie ophalen mislukt";
    }
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $results;
}
