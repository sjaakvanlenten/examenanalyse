<div class="modal fade" id="excelModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  	<div class="modal-dialog modal-lg" role="document">
    	<div class="modal-content">
      		<div class="modal-header">
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        		<h4 class="modal-title" id="myModalLabel">Klassen via Excel importeren</h4>
      		</div>
      		<div class="modal-body">
	  			<!-- Nav tabs -->
				<ul class="nav nav-tabs" role="tablist">
				  	<?php
				  		$klas = NULL;
				  		$class = 'active';
				  		foreach($leerlingendata as $leerling)
				  		{
				  			if($leerling["klas"] != $klas) {
				  				$klas = $leerling["klas"];
				  				echo '<li style="font-weight: bold" role="presentation" class="' . $class . '"><a href="#tab' . $klas . '" aria-controls="tab' . $klas . '" role="tab" data-toggle="tab">' . $klas . '</a></li>';						
				  			}
				  			$class = '';
				  		}
				  	?>				    	
				</ul>

			    <!-- Tab panes -->					
			    
			    	<form action="" method="POST" id="excel_form">
						<div class="form-group">
							<div class="tab-content">
						    <?php
						    	$max = count($leerlingendata);
						    	$klas = $leerlingendata[0]["klas"]; 
								$offset = 0;
								$k = 0;
								for($i = 0; $k < $max; $i++) {								
									$offset =+ $k;				
								    echo '<div role="tabpanel" class="tab-pane' . ($i == 0 ? ' active' : '') . '" id="tab'. $klas . '">';
										echo '<table class="table table-condensed table-bordered table-hover">';
											echo '<thead>';
												echo '<tr>';						      		
						      		foreach($excelheaders as $excelheader) {						      			
						      						echo "<th>" . $excelheader . "</th>";
						      		}						      		
		      
											    echo '</tr>';
											echo '</thead>';
										echo '<tbody>';    							    						    						    
									$k= 0;					    							    		
									foreach($leerlingendata as $leerling) {
										if($k < $offset) {
											$doNothing = 0;
										}
										else {    							
											if($leerling["klas"] != $klas) {
												$klas = $leerling["klas"];
												break;
											}
											else {	
												echo '<tr class="inputrow">';
												foreach($leerling as $key => $value) {	    								
													echo '<td><input type="text" class="form-control" name="' . $key . '[]" value="' . $value . '"></td>';
												}		    						
												echo "</tr>";
											}		    							
										}
										$k++;
									}		    						
		    								echo '</tbody>';
		    							echo '</table>';
		    						echo '</div>';								
				 				} 			
				 			?>
					</div>
					</div>  			
				</div>
				<div class="modal-footer">
					<input type="submit" class="btn btn-default" name="submit_excel_data" value="Opslaan">
					<button type="button" class="btn btn-danger" data-dismiss="modal">Sluiten</button>		  
	 			</div>
	 		</form>
		</div>
	</div>
</div>
<!-- Loading Modal -->
<div class="modal fade" id="load" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
      	<div id="loadimg">
        	<img src="../images/loading3.gif" id="loader_img">
    	</div>
        Gegevens opslaan in database...
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
$( "#excel_form" ).submit(function(event){
  $('#load').modal('show');
  $('#excelModal').modal('hide');
  $('body').css( "pointer-events", "none" );
});
</script>

<script type="text/javascript">
	$('#excelModal').modal('show');
</script>