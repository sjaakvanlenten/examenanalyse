$('.ajax_mail').on('click', function() {
	var path = location.pathname;
	var dir = path.substring(path.indexOf('/', 1)+1, path.lastIndexOf('/'));
	var leerling_id = this.id;
	$('#' + leerling_id).replaceWith('<img src="' + dir + '../images/small_loader.GIF" id="' + leerling_id + '">');
	$.post( dir + "../includes/ajaxverwerk.php", { leerling: leerling_id } )
	  .done(function() {
	  	$('#' + leerling_id).replaceWith('<img src="' + dir + '../images/check.png">');
  });
});