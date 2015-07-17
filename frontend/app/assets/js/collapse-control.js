$(document).ready(function(){
	$('.more').click(function (event){
		event.preventDefault();
		$('.texto-semaforo').toggleClass('hidden');
	});
});