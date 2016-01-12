<?php 

foreach($klassenlijst as $klas) {
	echo "<tr>";
	foreach($klas as $key => $value) {
		if($key != 'klas_id') {
			echo '<td' . ($key == 'klas' ? ' style="font-weight: bold;">' : '>') 
 
			. $value  
			
			. '</td>';
		}
	}
	echo 
		'<td>
			<a href="' . BASE_URL . 'admin/leerlingenlijst.php?klas=' . $klas["klas"] . '"><button type="button" class="btn btn-default btn-md">Klas weergeven</button></a>
		</td>
		<td>
			<button type="button" class="btn btn-info btn-md" data-toggle="modal" data-target="#' . $klas["klas"] . '">
				Bewerken
			</button>
		</td>

	</tr>';
}