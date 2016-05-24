$(document).ready(function(){
	$('.btn-close.cerrar').click(function(e){
		e.preventDefault();
		$('.btn-close.ver').removeClass('hidden');
		$('.btn-close.cerrar').addClass('hidden');
		$('.detalle').addClass('hidden');
	});

	$('.btn-close.ver').click(function(e){
		e.preventDefault();
		$('.btn-close.ver').addClass('hidden');
		$('.btn-close.cerrar').removeClass('hidden');
		$('.detalle').removeClass('hidden');
	});

});