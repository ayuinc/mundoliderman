$(document).ready(function(){
	$('.btn-close').click(function(e){
		e.preventDefault();
		$('.detalle').toggleClass('hidden');
	});
});