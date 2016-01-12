<?php

foreach($leerlingen as $leerling) {
	
	$leerlingdata = getStudentData($leerling['leerling_id']);
	if($leerlingdata['account_activated'] == 1) {
		echo '<tr class="success">';
	}
	else if ($leerlingdata['temp_mail'] == 1) {
		echo '<tr class="warning">';
	}
	else {
		echo '<tr class="active">';	
	}
	foreach($leerling as $key => $value) {
		if($key != 'gebruiker_id') {
			echo '<td>'  
			. $value  
			. '</td>';
		}
	}
	echo 
		'<td>
			<button type="button" class="btn btn-info btn-md" data-toggle="modal" data-target="#' . $leerling["leerling_id"] . '">
				Bewerken
			</button>
		</td>';

	
		echo
			'<td>';
			if($leerlingdata["account_activated"] != 1) {
				echo '
				<button type="button" class="btn btn-info btn-md ajax_mail" id="ajax' . $leerling["leerling_id"] . '">
					Verzend Wachtwoord
				</button>';
			}
		echo '</td>
		</tr>';
	
}