<!--DOCENT TOEVOEGEN-->
	<div class="modal fade" id="docenttoevoegen" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Voeg een docent toe</h4>
				</div>
				<div class="table-responsive" id="POItablediv">
					<form id="addteacher" method="post" action="">
						<table class="table table-bordered table-hover">
							<tr><td><b>Voornaam:</b></td> <td><input type="text" class="form-control" name="voornaam"></td></tr>
							<tr><td><b>Tussenvoegsel:</b></td> <td><input type="text" class="form-control" name="tussenvoegsel"></td></tr>
							<tr><td><b>Achternaam:</b></td> <td><input type="text" class="form-control" name="achternaam"></td></tr>
							<tr><td><b>Afkorting:</b></td> <td><input type="text" class="form-control" name="afkorting"></td></tr>
							<tr><td><b>Emailadres:</b></td> <td><input type="text" class="form-control" name="emailadres"></td></tr>
						</table>
					
				</div>
				<div class="modal-footer">
					<input form="addteacher" type="submit" class="btn btn-default" name="submit_docent" value="Opslaan en verzenden"></form>
					<button type="button" class="btn btn-danger" data-dismiss="modal">Sluiten</button>
				</div>
			</div>
		</div>
	</div>

	<?php
		foreach ($leraargegevens as $leraargegeven) {
	?>
	<!--DOCENT BEWERKEN-->
	<div class="modal fade" id="<?php echo $leraargegeven['gebruiker_id']; ?>" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Bewerk <?php echo $leraargegeven['voornaam'] . " " . $leraargegeven['tussenvoegsel'] . " " . $leraargegeven['achternaam']; ?></h4>
				</div>
					<div class="table-responsive">
						<form id="form<?php echo $leraargegeven['gebruiker_id']; ?>" action="" method="POST">
							<table class="table table-bordered table-hover">
								<tbody>
									<input style="display:none;" type="text" name="gebruiker_id" value="<?php echo $leraargegeven['gebruiker_id']; ?>">
									<tr>
										<td><b>Voornaam:</b></td>
										<td>
											<input type="text" class="form-control" name="voornaam" value="<?php echo $leraargegeven['voornaam']; ?>">
										</td>
									</tr>
									<tr>
										<td><b>Tussenvoegsel:</b></td>
										<td>
											<input type="text" class="form-control" name="tussenvoegsel" value="<?php echo $leraargegeven['tussenvoegsel']; ?>">
										</td>
									</tr>
									<tr>
										<td><b>Achternaam:</b></td>
										<td>
											<input type="text" class="form-control" name="achternaam" value="<?php echo $leraargegeven['achternaam']; ?>">
										</td>
									</tr>
									<tr>
										<td><b>Afkorting:</b></td>
										<td>
											<input type="text" class="form-control" name="afkorting" value="<?php echo $leraargegeven['docent_afk']; ?>">
										</td>
									</tr>
									<tr>
										<td><b>Email adres:</b></td>
										<td>
											<input type="text" class="form-control" name="emailadres" value="<?php echo $leraargegeven['emailadres']; ?>">
										</td>
									</tr>
								</tbody>
							</table>
						
					</div>
				<div class="modal-footer">
					<button form="form<?php echo $leraargegeven['gebruiker_id']; ?>" type="submit" class="btn btn-default" >Opslaan</button></form>
					<button type="button" class="btn btn-danger" data-dismiss="modal" data-toggle="modal" data-target="#verwijder<?php echo $leraargegeven['gebruiker_id']; ?>">Verwijder docent</button>
					<button type="button" class="btn btn-danger" data-dismiss="modal">Sluiten</button>
				</div>
			</div>
		</div>
	</div>
	<!--DOCENT VERWIJDEREN-->
	<div id="verwijder<?php echo $leraargegeven['gebruiker_id']; ?>" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Weet u zeker dat u docent: <?php echo $leraargegeven['voornaam'] . " " . $leraargegeven['tussenvoegsel'] . " " . $leraargegeven['achternaam']; ?> wilt verwijderen?</h4>
				</div>
				<div class="modal-footer">
					<a href="?verwijderdocent=<?php echo $leraargegeven['gebruiker_id'] ?>"><button style="float:left;" type="submit" class="btn btn-danger">Ja</button></a>
					<button type="button" class="btn btn-default" data-dismiss="modal">Nee</button>
				</div>
			</div>
		</div>
	</div>

	<?php
		}
	?>