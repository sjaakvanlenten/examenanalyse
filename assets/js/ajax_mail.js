$('.ajax_mail').on('click', function() {
	var leerling_id = this.id;
	$('#' + leerling_id).replaceWith('<img src="../images/small_loader.gif" id="' + leerling_id + '">');
	$.post( "../includes/ajaxverwerk.php", { leerling: leerling_id } )
	  .done(function() {
	  	$('#' + leerling_id).replaceWith('<img src="../images/check.png">');
  });
});