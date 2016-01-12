

<!-- Leerling toevoegen Modal -->
<div class="modal fade" id="leerling-toevoegen" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  	<div class="modal-dialog modal-lg" role="document">
    	<div class="modal-content">
      		<div class="modal-header">
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        		<h4 class="modal-title" id="myModalLabel">Leerling Toevoegen aan <?php echo $klas ?></h4>
      		</div>
      		<div class="modal-body">
        		<form action="" method="POST">
		        	<div class="form-group leerling">
			        	<table class="table table-condensed table-bordered table-hover">
						    <thead>
						    	<tr>
							        <th>Voornaam</th>
							        <th>Tussenvoegsel</th>
							        <th>Achternaam</th>
							        <th>Leerlingnummer</th>
							        <th>Emailadres</th>
						    	</tr>
						    </thead>
						    <tbody>					    	
						    	<tr class="inputrow">
						            <td><input type="text" class="form-control leerling" name="voornaam[]"></td>
						            <td><input type="text" class="form-control leerling" name="tussenvoegsel[]"></td>
						            <td><input type="text" class="form-control leerling" name="achternaam[]"></td>
						            <td><input type="text" class="form-control leerling" name="leerling_id[]"></td>
						            <td><input type="text" class="form-control leerling" name="emailadres[]"></td>	
			            		</tr>
							</tbody>
						</table>
					</div>							        
      		</div>
      		<div class="modal-footer">
      			<button style = "float:left;" type="button" class="btn btn-danger" data-dismiss="modal">Sluiten</button>
      			<input type ="button" class="btn btn-danger" id="delete_leerling" onclick="deleteleerlingRow()" value="Rij verwijderen"/>
      			<input type ="button" class="btn btn-default" id="add_leerling" onclick="insertLeerlingRow()" value="Rij toevoegen"/>
				<input type="submit" class="btn btn-default" name="submit_add_leerling" value="Opslaan en verzenden">        								      
      		</div>
      		</form>
    	</div>
  	</div>
</div>