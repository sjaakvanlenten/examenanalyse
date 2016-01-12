<!--EXAMEN TOEVOEGEN-->
	<div class="modal fade" id="examentoevoegen" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Voeg een examen toe</h4>
				</div>
				<div class="table-responsive" id="POItablediv">
					<form id="docenttoevoegen" method="post" action="">
						<table class="table table-bordered table-hover">
							<tr>
								<td class="active"><b>Vak:</b></td>
								<td class="active"><b>Jaar:</b></td>
								<td class="active"><b>Tijdvak:</b></td>
								<td class="active"><b>Niveau:</b></td>
								<td class="active"><b>N-Term:</b></td>
							</tr>
							<tr>
								<td>
									<select class="form-control" name="vak">
										<option  value="Nederlands">Nederlands</option>
									</select>
								</td>
								<td>
									<input class="form-control" type="number" size="4" name="jaar" value="<?php echo date("Y"); ?>">
								</td>
								<td>
									<select class="form-control" name="tijdvak">
										<option  value="1">1</option>
										<option  value="2">2</option>
									</select>
								</td>
								<td>
									<select class="form-control" name="niveau">
										<option  value="havo">havo</option>
										<option  value="vwo">vwo</option>
										<option  value="vmbo tl">vmbo tl</option>
										<option  value="vmbo kl">vmbo kl</option>
									</select>
								</td>
								<td>
									<select class="form-control" name="nterm">
										<?php
										$nterm = $examengegeven['nterm'];
										for ($q = 0; $q <= 20; $q++) {
											$n = $q / 10;
											if ($n == 1) {
												echo'<option selected value="' . $n . '">' . $n . '</option>';
											} else {
												echo'<option value="' . $n . '">' . $n . '</option>';
											}
										}
										?>
									</select>
								</td>
							</tr>
						</table>
						<br /> <!--OPTIONEEL UITLEG TOEVOEGEN-->
						<table class="table table-bordered table-hover">
							<tbody id="vraagtoevoegen">
								<tr>
									<td class="active"><b>Vraag:</b></td>
									<td class="active"><b>Maximale score:</b></td>
									<td class="active"><b>Categorie:</b></td>
								</tr>
								<tr>
									<td><input class="form-control" value="1" size=1 type="text" name="vraag0" readonly></td>
									<td><input class="form-control" min="0" type="number" name="maxscore0"/></td>
									<td>
										<select class="form-control" name="categorie0" >
											<option>...</option>
											<?php
											$test = checkCategorie();
											foreach ($test as $t) {
												$t = $t['categorieomschrijving'];
												echo "<option>" . $t . "</option>";
											}
											?>
										</select>
									</td>
								</tr>
							</tbody>
						</table>
					</form>
				</div>
				<div class="modal-footer">
					<input form="docenttoevoegen" type="submit" class="btn btn-default" name="submit_examen" value="Opslaan en verzenden">
					<input type="button" class="btn btn-default" id="addmorePOIbutton" value="Voeg rij toe" onclick="insRow('')"/>
					<input type="button" class="btn btn-danger" id="delPOIbutton" value="Verwijder rij" onclick="deleteRow('')"/>
					<button type="button" class="btn btn-danger" data-dismiss="modal">Sluiten</button>
				</div>
			</div>
		</div>
	</div>

	<?php
		foreach ($examengegevens as $examengegeven){
	?>
	<!--EXAMEN BEWERKEN-->
	<div class="modal fade" id="<?php echo $examengegeven['examen_id']; ?>" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Bewerk examen  <?php echo $examengegeven['niveau'] . " " . $examengegeven['examenvak'] . " " . $examengegeven['examenjaar'] . " tijdvak " . $examengegeven['tijdvak']; ?></h4>
				</div>
				<div class="table-responsive">
					<form id="form<?php echo $examengegeven['examen_id']; ?>" action="" method="POST">
						<table class="table table-bordered table-hover">
							<tr>
								<td class="active"><b>Vak:</b></td>
								<td class="active"><b>Jaar:</b></td>
								<td class="active"><b>Tijdvak:</b></td>
								<td class="active"><b>Niveau:</b></td>
								<td class="active"><b>N-Term:</b></td>
							</tr>
							<tr>
								<td>
									<select class="form-control" name="vak" readonly>
										<option value="<?php echo $examengegeven['examenvak']; ?>"><?php echo $examengegeven['examenvak']; ?></option>
									</select>
								</td>
								<td>
									<input class="form-control" type="number" size="4" name="jaar" value="<?php echo $examengegeven['examenjaar']; ?>" readonly>
								</td>
								<td>
									<select class="form-control" name="tijdvak" readonly>
										<option  value="<?php echo $examengegeven['tijdvak']; ?>"><?php echo $examengegeven['tijdvak']; ?></option>
									</select>
								</td>
								<td>
									<select class="form-control" name="niveau" readonly>
										<option selected value="<?php echo $examengegeven['niveau']; ?>"><?php echo $examengegeven['niveau']; ?></option>
									</select>
								</td>
								<td>
									<select class="form-control" name="nterm">
										<?php
										$nterm = $examengegeven['nterm'];
										for ($q = 0; $q <= 20.1; $q++) {
											$n = $q / 10;
											if ($nterm == $n) {
												echo'<option selected value="' . $n . '">' . $n . '</option>';
											} else {
												echo'<option value="' . $n . '">' . $n . '</option>';
											}
										}
										?>
									</select>
								</td>
						</table>
						<br /> <!--OPTIONEEL UITLEG TOEVOEGEN-->
						<table class="table table-bordered table-hover">
							<tbody id="vraagtoevoegen<?php echo $examengegeven['examen_id']; ?>">
								<tr>
									<td class="active"><b>Vraag:</b></td>
									<td class="active"><b>Maximale score:</b></td>
									<td class="active"><b>Categorie:</b></td>
								</tr>
								<?php
									$examenvragen = selectExamQuestions($examengegeven['examen_id']);
									$getal = 0;
									foreach ($examenvragen as $a) {
								?>
								<tr>
									<td><input class="form-control" value="<?php echo$a['examenvraag']; ?>"  type="text" name="vraag<?php echo $getal; ?>"readonly></td>
									<td><input class="form-control" value="<?php echo$a['maxscore']; ?>"  type="text" name="maxscore<?php echo $getal; ?>"></td>
									<td>
										<select class="form-control" name="categorie<?php echo $getal; ?>">
											<option><?php echo $a['categorieomschrijving']; ?></option>
											<?php
												$test = checkCategorie();
												foreach ($test as $t) {
													$t = $t['categorieomschrijving'];
													echo "<option>" . $t . "</option>";
											}
											?>
										</select>
									</td>
								</tr>
								<?php
									$getal++;
								}
								?>
							</tbody>
						</table>
					</form>
				</div>
				<div class="modal-footer">
					<button form="form<?php echo $examengegeven['examen_id']; ?>" type="submit" class="btn btn-default" >Opslaan</button><!--[if IE]></form><![endif]-->
					<input type="button" class="btn btn-default" id="addmorePOIbutton" value="Voeg rij toe" onclick="insRow(<?php echo $examengegeven['examen_id'] ?>)"/>
					<input type="button" class="btn btn-danger" id="delPOIbutton" value="Verwijder rij" onclick="deleteRow(<?php echo $examengegeven['examen_id'] ?>)"/>
					<button type="button" class="btn btn-danger" data-dismiss="modal" data-toggle="modal" data-target="#verwijder<?php echo $examengegeven['examen_id']; ?>">Verwijder examen</button>
					<button type="button" class="btn btn-danger" data-dismiss="modal">Sluiten</button>
				</div>
			</div>
		</div>
	</div>
	<!--EXAMEN VERWIJDEREN-->
	<div id="verwijder<?php echo $examengegeven['examen_id']; ?>" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Weet u zeker dat u Examen <?php echo $examengegeven['niveau'] . " " . $examengegeven['examenvak'] . " " . $examengegeven['examenjaar'] . " tijdvak " . $examengegeven['tijdvak']; ?> wilt verwijderen?</h4>
				</div>
				<div class="modal-footer">
					<a href="?verwijderexamen=<?php echo $examengegeven['examen_id'] ?>"><button style="float:left;" type="submit" class="btn btn-danger">Ja</button></a>
					<button type="button" class="btn btn-default" data-dismiss="modal">Nee</button>
				</div>
			</div>

		</div>
	</div>
	<?php
		}
	?>