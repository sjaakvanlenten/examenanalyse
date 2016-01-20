<?php

function checkIfExamExists($examenvak, $examenjaar, $tijdvak, $niveau) {
    require(ROOT_PATH . "includes/database_connect.php");
    try {
        $results = $db->prepare("
            SELECT *
            FROM examen
            WHERE examenvak = ? AND examenjaar = ? AND tijdvak = ? AND niveau = ?");
        $results->bindParam(1, $examenvak);
        $results->bindParam(2, $examenjaar);
        $results->bindParam(3, $tijdvak);
        $results->bindParam(4, $niveau);
        $results->execute();
    } catch (Exception $e) {
        $_SESSION['message'] = "Data could not be retrieved from the database.";
        exit;
    }

    $match = $results->fetch(PDO::FETCH_ASSOC);

    if ($match == "") {

        return false;
    } else {
        return $match;
    }
}

//examen toevoegen
function addExam($gegevens) {
    require(ROOT_PATH . "includes/database_connect.php");
    try {
        $stmt = $db->prepare("
            INSERT
            INTO examen (
                examenvak,
                examenjaar,
                tijdvak,
                nterm,
                niveau
            )
            VALUES (?, ?, ?, ?, ?) ");
        $stmt->execute($gegevens);
        $_SESSION['message-success'] = "Examen toegevoegd";
    } catch (Exception $e) {
        $_SESSION['message'] = "Examen kon niet worden toegevoegd.";
        exit;
    }
}

function checkCategorie() {
    require(ROOT_PATH . "includes/database_connect.php");
    try {
        $results = $db->prepare("
            SELECT *
            FROM categorie");
        $results->execute();
    } catch (Exception $e) {
        $_SESSION['message'] = "Data could not be retrieved from the database.";
        exit;
    }

    $match = $results->fetchAll();
    return $match;
}

function checkCategorie_id($categorie_omschrijving) {
    require(ROOT_PATH . "includes/database_connect.php");
    try {
        $results = $db->prepare("
            SELECT categorie_id
            FROM categorie
            WHERE categorieomschrijving = ?");
        $results->bindParam(1, $categorie_omschrijving);
        $results->execute();
    } catch (Exception $e) {
        $_SESSION['message'] = "Data could not be retrieved from the database.";
        exit;
    }

    $match = $results->fetchAll();
    return $match;
}

function addExamQuestion($vraag, $maxscore, $categorie_id, $examen_id) {
    require(ROOT_PATH . "includes/database_connect.php");
    try {

        $stmt = $db->prepare("
            INSERT INTO examenvraag
            (examen_id, examenvraag, maxscore, categorie_id )
            VALUES (?, ?, ?, ?);
            ");
        $stmt->bindParam(1, $examen_id);
        $stmt->bindParam(2, $vraag);
        $stmt->bindParam(3, $maxscore);
        $stmt->bindParam(4, $categorie_id);
        $stmt->execute();
        $_SESSION['message-success'] = "Examenvragen toegevoegd";
    } catch (Exception $e) {
        $_SESSION['message'] = "Examenvraag kon niet worden toegevoegd.";
        exit;
    }
}

//checken of examenvraag bestaat
function checkIfExamQuestionExists($vraag, $examen_id) {
    require(ROOT_PATH . "includes/database_connect.php");
    try {

        $match = $db->prepare("
            SELECT * FROM examenvraag
            WHERE examen_id = ? AND examenvraag = ?
            ");
        $match->bindParam(1, $examen_id);
        $match->bindParam(2, $vraag);
        $match->execute();
    } catch (Exception $e) {
        $_SESSION['message'] = "Geen gegevens uit de database ontvangen.";
        exit;
    }
    $match = $match->fetch(PDO::FETCH_ASSOC);

    if ($match == "") {

        return false;
    } else {
        return $match;
    }
}

function getAllExams() {
    require(ROOT_PATH . "includes/database_connect.php");
    try {
        $match = $db->prepare("
            SELECT * FROM examen
            ORDER BY examenjaar, tijdvak, niveau
            ");
        $match->execute();
    } catch (Exception $e) {
        $_SESSION['message'] = "Geen gegevens uit de database ontvangen.";
        exit;
    }
    $match = $match->fetchAll();
    return $match;
}

function deleteExam($examen) {
    require(ROOT_PATH . "includes/database_connect.php");
    try {

        $stmt = $db->prepare("
            DELETE FROM examen WHERE examen_id = ?");
        $stmt2 = $db->prepare("
            SELECT examenvraag_id FROM examenvraag WHERE examen_id = ?");
        $stmt2->bindParam(1, $examen);
        $stmt2->execute();
        $stmt2 = $stmt2->fetchAll();

        foreach ($stmt2 as $t) {
            $t = $t['examenvraag_id'];
            $stmt3 = $db->prepare("
            DELETE FROM examenvraag WHERE examenvraag_id = ?");
            $stmt3->bindParam(1, $t);
            $stmt3->execute();
        }

        $stmt->bindParam(1, $examen);
        $stmt->execute();
        $_SESSION['message-success'] = 'Examen en alle bijbehorende vragen verwijdert.';
    } catch (Exception $e) {
        $_SESSION['message'] = "Geen gegevens uit de database ontvangen.";
        exit;
    }
}

function updateNterm($nterm, $examen_id) {
    require(ROOT_PATH . "includes/database_connect.php");
    try {
        $stmt = $db->prepare("
                        UPDATE examen SET nterm = ? WHERE examen_id = ?");
        $stmt->bindParam(1, $nterm);
        $stmt->bindParam(2, $examen_id);
        $stmt->execute();
        $_SESSION['message-success'] = "Nterm geüpdatet!";
    } catch (Exception $e) {
        $_SESSION['message'] = "Update mislukt.";
        exit;
    }
}

function updateExamQuestion($maxscore, $categorie, $examenvraag_id) {
    require(ROOT_PATH . "includes/database_connect.php");
    try {
        $update = $db->prepare("
            UPDATE examenvraag SET maxscore = ?, categorie_id = ?
            WHERE examenvraag_id =
            ?");
        $update->bindParam(1, $maxscore);
        $update->bindParam(2, $categorie);
        $update->bindParam(3, $examenvraag_id);
        $update->execute();
        $_SESSION['message-success'] = "Examenvraag geüpdatet!";
    } catch (Exception $e) {
        $_SESSION['message'] = "Update mislukt";
        exit;
    }
}

function selectExamQuestions($examen_id) {
    require(ROOT_PATH . "includes/database_connect.php");
    try {
        $match = $db->prepare("
            SELECT EV.examenvraag, EV.maxscore, C.categorieomschrijving
            FROM examenvraag EV JOIN categorie C
            ON C.categorie_id = EV.categorie_id
            WHERE examen_id = ?
            ");
        $match->bindParam(1, $examen_id);
        $match->execute();
    } catch (Exception $e) {
        $_SESSION['message'] = "Geen gegevens uit de database ontvangen.";
        exit;
    }
    $match = $match->fetchAll();
    return $match;
}

function getAllExamquestionCategories() {
    require(ROOT_PATH . "includes/database_connect.php");
    try {
        $match = $db->prepare("
            SELECT categorie_id, count(categorie_id)
            FROM examenvraag
            GROUP BY categorie_id
            ORDER BY 2 DESC
            ");
        $match->execute();
    } catch (Exception $e) {
        $_SESSION['message'] = "Geen gegevens uit de database ontvangen.";
        exit;
    }
    $match = $match->fetchAll();
    return $match;
}

function getAllExamResultsWithNterm($gebruiker_id) {
    require(ROOT_PATH . "includes/database_connect.php");
    try {
        $match = $db->prepare("
		SELECT
			E.examenvak,
			E.examenjaar,
			E.tijdvak,
			E.nterm,
			R.examen_score,
			SUM(EV.maxscore) AS maxscore
		FROM
			resultaat R
				JOIN
			examen E ON E.examen_id = R.examen_id
				JOIN
			examenvraag EV ON EV.examen_id = R.examen_id
		WHERE
			R.gebruiker_id = ?
		GROUP BY EV.examen_id
		ORDER BY R.timestamp
		");
        $match->bindParam(1, $gebruiker_id);
        $match->execute();
    } catch (Exception $e) {
        $_SESSION['message'] = "Geen gegevens uit de database ontvangen.";
        exit;
    }
    $match = $match->fetchAll();
    return $match;
}

function getExamQuestionResults($gebruiker_id) {
    require(ROOT_PATH . "includes/database_connect.php");
    try {
        $results = $db->prepare("
        SELECT E.examenvak, E.examenjaar,E.tijdvak, ROUND(100 * SUM(S.vraag_score) / SUM(EV.maxscore),0), C.categorieomschrijving, E.examen_id
        FROM categorie C
        JOIN examenvraag EV ON EV.categorie_id = C.categorie_id
         JOIN examen E ON EV.examen_id = E.examen_id
         JOIN resultaat R ON EV.examen_id = R.examen_id
         JOIN score S ON S.examenvraag_id = EV.examenvraag_id

        WHERE
            S.gebruiker_id = ?
		AND
            R.gebruiker_id = ?
        GROUP BY EV.examen_id , EV.categorie_id
        ORDER BY R.timestamp  , EV.categorie_id
        ");
        $results->bindParam(1, $gebruiker_id);
        $results->bindParam(2, $gebruiker_id);
        $results->execute();
    } catch (Exception $e) {
        $_SESSION['message'] = "Data could not be retrieved from the database.";

        exit;
    }
    $results = $results->fetchAll();
    $newResult = [];
    foreach ($results as $row) {
        $newResult[/* $row['examenvak'] . " " . */$row['examenjaar'] . " tijdvak " . $row['tijdvak']][$row[4]] = $row[3];
        $newResult[/* $row['examenvak'] . " " . */$row['examenjaar'] . " tijdvak " . $row['tijdvak']]['examen_id'] = $row[5];
    }
    return $newResult;
}

function getExamQuestionResultsFromExamen($gebruiker_id, $examen_id) {
    require(ROOT_PATH . "includes/database_connect.php");
    try {
        $results = $db->prepare("
        SELECT E.examenvak, E.examenjaar,E.tijdvak, ROUND(100 * SUM(S.vraag_score) / SUM(EV.maxscore),0), C.categorieomschrijving, E.examen_id
        FROM categorie C
        JOIN examenvraag EV ON EV.categorie_id = C.categorie_id
         JOIN examen E ON EV.examen_id = E.examen_id
         JOIN resultaat R ON EV.examen_id = R.examen_id
         JOIN score S ON S.examenvraag_id = EV.examenvraag_id

        WHERE
            S.gebruiker_id = ?
        AND E.examen_id = ?
        GROUP BY EV.examen_id , EV.categorie_id
        ORDER BY R.timestamp , EV.categorie_id;
        ");
        $results->bindParam(1, $gebruiker_id);
        $results->bindParam(2, $examen_id);
        $results->execute();
    } catch (Exception $e) {
        $_SESSION['message'] = "Data could not be retrieved from the database.";
        exit;
    }
    $results = $results->fetchAll();
    $newResult = [];
    foreach ($results as $row) {
        $newResult[$row['examenvak'] . " " . $row['examenjaar'] . " tijdvak " . $row['tijdvak']][$row[4]] = $row[3];
        $newResult[$row['examenvak'] . " " . $row['examenjaar'] . " tijdvak " . $row['tijdvak']]['examen_id'] = $row[5];
    }
    return $newResult;
}

function getAllExamQuestionsWithCategorie($j) {
    require(ROOT_PATH . "includes/database_connect.php");
    try {
        $match = $db->prepare("
            SELECT E.examenvak, E.examenjaar, E.tijdvak, EV.examenvraag
            FROM examenvraag EV JOIN examen E
            on E.examen_id = EV.examen_id
            WHERE categorie_id = ?
            ORDER BY RAND() LIMIT 5
        ");
        $match->bindParam(1, $j);
        $match->execute();
    } catch (Exception $e) {
        $_SESSION['message'] = "Geen gegevens uit de database ontvangen.";
        exit;
    }
    $match = $match->fetchAll();
    return $match;
}

function getAllCreatedExamsWithExamId($j) {
    require(ROOT_PATH . "includes/database_connect.php");
    try {
        $match = $db->prepare("
            SELECT *
            FROM resultaat
            WHERE examen_id = ?
        ");
        $match->bindParam(1, $j);
        $match->execute();
    } catch (Exception $e) {
        $_SESSION['message'] = "Geen gegevens uit de database ontvangen.";
        exit;
    }
    $match = $match->fetchAll();
    return $match;
}

function getCategorie() {
    require(ROOT_PATH . "includes/database_connect.php");
    try {
        $match = $db->prepare("
            SELECT *
            FROM categorie
            ");
        $match->execute();
    } catch (Exception $e) {
        $_SESSION['message'] = "Geen gegevens uit de database ontvangen.";
        exit;
    }
    $match = $match->fetchAll(PDO::FETCH_ASSOC);
    return $match;
}

function updateCategorie($categorie, $categorieomschrijving, $categorie_id) {
    require(ROOT_PATH . "includes/database_connect.php");
    try {
        $stmt = $db->prepare("
            UPDATE categorie
            SET categorieomschrijving = ?, categorieomschrijving_uitgebreid =?
            WHERE categorie_id = ?
            ");
        $stmt->bindParam(1, $categorie);
        $stmt->bindParam(2, $categorieomschrijving);
        $stmt->bindParam(3, $categorie_id);
        $stmt->execute();
        $_SESSION["message-success"] = "Categorie geüpdatet!";
    } catch (Exception $e) {
        $_SESSION['message'] = "Bewerken is mislukt.";
        exit;
    }
}

function addCategorie($categorie, $categorieomschrijving) {
    require(ROOT_PATH . "includes/database_connect.php");
    try {
        $stmt = $db->prepare("
            INSERT INTO categorie
            (categorieomschrijving, categorieomschrijving_uitgebreid)
            VALUES (?, ?);
            ");
        $stmt->bindParam(1, $categorie);
        $stmt->bindParam(2, $categorieomschrijving);
        $stmt->execute();
        $_SESSION["message-success"] = "Categorie toegevoegd!";
    } catch (Exception $e) {
        $_SESSION['message'] = "Toevoegen mislukt";
        exit;
    }
}

function deleteCategorie($categorie_id) {
    require(ROOT_PATH . "includes/database_connect.php");
    try {
        $stmt = $db->prepare("
            DELETE FROM categorie
            WHERE categorie_id = ?;
            ");
        $stmt->bindParam(1, $categorie_id);
        $stmt->execute();
        $_SESSION["message-succes"] = "Categorie verwijdert!";
    } catch (Exception $e) {
        $_SESSION['message'] = "Verwijderen mislukt";
    }
}

function checkifCategoriehasQuestions($categorie_id) {
    require(ROOT_PATH . "includes/database_connect.php");
    try {
        $stmt = $db->prepare("
            SELECT *
            FROM examenvraag
            WHERE categorie_id = ?;
            ");
        $stmt->bindParam(1, $categorie_id);
        $stmt->execute();
    } catch (Exception $e) {
        $_SESSION['message'] = "Controle mislukt";
    }
    $match = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($match == "") {
        return true;
    } else {
        return false;
    }
}

function getScoreKlaseachCategorie($klas_id, $categorie_id) {
    require(ROOT_PATH . "includes/database_connect.php");
    try {
        $stmt = $db->prepare("
            SELECT ROUND(100 * SUM(vraag_score) / SUM(maxscore)) AS 'result'
            FROM leerling l
            JOIN score s ON l.gebruiker_id = s.gebruiker_id
            JOIN examenvraag e ON s.examenvraag_id = e.examenvraag_id
            WHERE l.klas_id = ? AND categorie_id = ?
            ");
        $stmt->bindParam(1, $klas_id);
        $stmt->bindParam(2, $categorie_id);
        $stmt->execute();
    } catch (Exception $e) {
        $_SESSION['message'] = "Score ophalen mislukt";
    }
    $results = $stmt->fetch(PDO::FETCH_ASSOC);
    return $results['result'];
}

function getScoreStudenteachCategorie($gebruiker_id, $categorie_id) {
    require(ROOT_PATH . "includes/database_connect.php");
    try {
        $stmt = $db->prepare("
            SELECT ROUND(100 * SUM(vraag_score) / SUM(maxscore)) AS 'result'
            FROM leerling l
            JOIN score s ON l.gebruiker_id = s.gebruiker_id
            JOIN examenvraag e ON s.examenvraag_id = e.examenvraag_id
            WHERE l.gebruiker_id = ? AND categorie_id = ?
            ");
        $stmt->bindParam(1, $gebruiker_id);
        $stmt->bindParam(2, $categorie_id);
        $stmt->execute();
    } catch (Exception $e) {
        $_SESSION['message'] = "Score ophalen mislukt";
    }
    $results = $stmt->fetch(PDO::FETCH_ASSOC);
    return $results['result'];
}

function getExamen($niveau) {

    require(ROOT_PATH . "includes/database_connect.php");
    try {
        $stmt = $db->prepare("
            SELECT *
            FROM examen
            WHERE niveau = ?
            Order by examenjaar, tijdvak
            ");
        $stmt->bindParam(1, $niveau);
        $stmt->execute();
    } catch (Exception $e) {

        $_SESSION['message'] = "Er ging wat fout.";
        exit;
    }
    $results = $stmt->fetchall(PDO::FETCH_ASSOC);

    return $results;
}

function getExamenvragen($examen_id) {

    require(ROOT_PATH . "includes/database_connect.php");
    try {
        $stmt = $db->prepare("
            SELECT *
            FROM examenvraag
            WHERE examen_id = ?
            ORDER BY examenvraag ASC
            ");
        $stmt->bindParam(1, $examen_id);
        $stmt->execute();
    } catch (Exception $e) {

        $_SESSION['message'] = "Er ging wat fout.";
        exit;
    }
    $results = $stmt->fetchall(PDO::FETCH_ASSOC);

    return $results;
}

function insertScore($gebruiker, $examenvraagid, $vraagscore) {

    require(ROOT_PATH . "includes/database_connect.php");
    try {
        $stmt = $db->prepare("
            INSERT INTO score (gebruiker_id,examenvraag_id,vraag_score)
            VALUES (?,?,?)
            ");
        $stmt->bindParam(1, $gebruiker);
        $stmt->bindParam(2, $examenvraagid);
        $stmt->bindParam(3, $vraagscore);
        $stmt->execute();
    } catch (Exception $e) {
        $_SESSION['message'] = "Er ging wat fout.";
        exit;
    }
    $_SESSION['message-success'] = "Score is ingevoerd!";
}

function updateScore($gebruiker, $examenvraagid, $vraagscore) {

    require(ROOT_PATH . "includes/database_connect.php");
    try {
        $stmt = $db->prepare("
            UPDATE score
            SET vraag_score = ?
            WHERE gebruiker_id = ? AND examenvraag_id = ?
            ");
        $stmt->bindParam(1, $vraagscore);
        $stmt->bindParam(2, $gebruiker);
        $stmt->bindParam(3, $examenvraagid);
        $stmt->execute();
        $_SESSION['message-success'] = "Score is bijgewerkt!";
    } catch (Exception $e) {
        $_SESSION['message'] = "Er ging wat fout.";
        exit;
    }
}

function getScore($gebruiker, $examenvraagid) {

    require(ROOT_PATH . "includes/database_connect.php");
    try {
        $stmt = $db->prepare("
            SELECT gebruiker_id, examenvraag_id, vraag_score
            FROM score
            WHERE gebruiker_id = ? AND examenvraag_id = ?
            ");
        $stmt->bindParam(1, $gebruiker);
        $stmt->bindParam(2, $examenvraagid);
        $stmt->execute();
    } catch (Exception $e) {
        $_SESSION['message'] = "Er ging wat fout.";
        exit;
    }
    $results = $stmt->fetchall(PDO::FETCH_ASSOC);

    return $results;
}

function getPunten($examen_id, $gebruiker_id) {

    require(ROOT_PATH . "includes/database_connect.php");
    try {
        $stmt = $db->prepare("
            SELECT *
            FROM examenvraag
			Join score ON examenvraag.examenvraag_id = score.examenvraag_id
            WHERE examen_id = ?
            AND score.gebruiker_id = ?
            ORDER BY examenvraag ASC
            ");
        $stmt->bindParam(1, $examen_id);
        $stmt->bindParam(2, $gebruiker_id);
        $stmt->execute();
    } catch (Exception $e) {
        $_SESSION['message'] = "Er ging wat fout.";
        exit;
    }
    $results = $stmt->fetchall(PDO::FETCH_ASSOC);

    return $results;
}

function insertScoreTabelResultaat($gebruiker, $totaalscore, $examen_id) {

    require(ROOT_PATH . "includes/database_connect.php");
    try {
        $stmt = $db->prepare("
            INSERT INTO resultaat (gebruiker_id,examen_id,examen_score)
            VALUES (?,?,?)
            ");
        $stmt->bindParam(1, $gebruiker);
        $stmt->bindParam(2, $examen_id);
        $stmt->bindParam(3, $totaalscore);
        $stmt->execute();
        $_SESSION['message-success'] = "Score is bijgewerkt!";
    } catch (Exception $e) {
        $_SESSION['message'] = "Er ging wat fout.";
        exit;
    }
}

function updateScoreTabelResultaat($gebruiker, $totaalscore, $examen_id) {

    require(ROOT_PATH . "includes/database_connect.php");
    try {
        $stmt = $db->prepare("
            UPDATE resultaat
            SET examen_score = ?
            WHERE gebruiker_id = ? AND examen_id = ?
            ");
        $stmt->bindParam(1, $totaalscore);
        $stmt->bindParam(2, $gebruiker);
        $stmt->bindParam(3, $examen_id);
        $stmt->execute();
        $_SESSION['message-success'] = "Score is bijgewerkt!";
    } catch (Exception $e) {
        $_SESSION['message'] = "Er ging wat fout.";
        exit;
    }
}

function checkIfExamResultExists($gebruiker, $examen_id) {

    require(ROOT_PATH . "includes/database_connect.php");
    try {
        $stmt = $db->prepare("
            SELECT *
            FROM resultaat
            WHERE gebruiker_id = ? AND examen_id = ?
            ");
        $stmt->bindParam(1, $gebruiker);
        $stmt->bindParam(2, $examen_id);
        $stmt->execute();
    } catch (Exception $e) {
        $_SESSION['message'] = "Er ging wat fout.";
        exit;
    }
    $results = $stmt->fetchall(PDO::FETCH_ASSOC);
    if (empty($results)) {
        return true;
    } else {
        return false;
    }
}

function countGemaakteExamens() {
    require(ROOT_PATH . "includes/database_connect.php");
    try {
        $stmt = $db->prepare("
            SELECT COUNT(examen_id) AS '0'
            FROM resultaat
            ");
        $stmt->execute();
    } catch (Exception $e) {
        $_SESSION['message'] = "Data could not be retrieved from the database.";
        exit;
    }
    $results = $stmt->fetch(PDO::FETCH_ASSOC);
    return $results['0'];
}
