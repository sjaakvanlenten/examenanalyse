<?php
$data = getExamQuestionResults($_SESSION['gebruiker_id']);

if(empty($examencijferresultaten)){
    echo"Er zijn nog geen resultaten ingevoerd, klik <a class='button' href='examenresultatentoevoegen.php'>hier</a> om resultaten toe te voegen.";
} else {
?>

<div class="table-responsive">
    <table class="table">

        <?php
        $categorien = checkCategorie();
        echo"<tr>";
        echo "<th></th>";
        foreach ($data as $key => $value) {
            echo "<th><a href='resultaatvanexamen.php?examen=".$value['examen_id']."'><button type='button' class='btn btn-default btn-md' >" . $key . "</button></a></th>";
        }
        echo"</tr>";
        foreach ($categorien as $t) {

            echo"<tr>";
            $q = $t['categorieomschrijving'];
            echo "<th>" . $q . "</th>";
            foreach ($data as $key => $value) {
                if (array_key_exists($q, $value)) {
					if(isset($voorgaandewaarde)){
					if($voorgaandewaarde >  $value[$q] OR $value[$q] < 25){
						echo "<td class='danger'>";
						echo $value[$q] . "%";
						echo "</td>";
					} else {
						echo "<td class='success'>";
						echo $value[$q] . "%";
						echo "</td>";
					}
					}else{
                        if($value[$q] <= 50){
						echo "<td class='danger'>";
						echo $value[$q] . "%";
						echo "</td>";
                        } else {
                            echo "<td class='success'>";
                            echo $value[$q] . "%";
                            echo "</td>";
                        }
					}
					$voorgaandewaarde = $value[$q];
                } else {
                    echo"<td class='active'></td>";
                }
            }

			unset($voorgaandewaarde);
            echo"</tr>";
        }
        ?>
    </table>
</div>
<?php
}
?>