$(document).ready(function(){
	$('.btn-close').click(function(e){
		e.preventDefault();
		$('.btn-close').toggleClass('hidden');
		$('.detalle').toggleClass('hidden');
	});

});