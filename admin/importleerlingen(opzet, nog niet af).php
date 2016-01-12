<html>
    <head>
        <title>Tentamen Webprogrammeren</title>
        <link rel="stylesheet" type="text/css" href="style.css">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['importeer'])) {
                $filename = mktime() . '_' . $_FILES['csvfile']['name'];
                $path = $_SERVER['DOCUMENT_ROOT'] . '/test/' . $filename;
                if (move_uploaded_file($_FILES['csvfile']['tmp_name'], $path)) {
                    if (($handle = fopen($_SERVER['DOCUMENT_ROOT'] . "/test/" . $filename, "r")) !== FALSE) {
                        $alldata = array();
                        while (($data = fgetcsv($handle, 10000, ";")) !== FALSE) {
                            if ($data[1] != "" AND $data[2] != "" AND $data[3] != ""AND $data[4] != "" AND $data[5] != "") {
                                $alldata[] = $data;
                            }
                        }
                        fclose($handle);
                    }
                }
                echo"<pre>";
                print_r($alldata);
            }
        }
        ?>
        <form enctype="multipart/form-data" action="" method='POST'>
            <input type='file' name='csvfile' accept=".csv"><br>
            <input type='submit' name='importeer' value='Importeer'>
        </form>
    </body>
</html>
