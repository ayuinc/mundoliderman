$(document).ready(function(){
	$('.more').click(function (event){
		event.preventDefault();
		$('.texto-semaforo').toggleClass('hidden');
	});
	$(".active-slide").click(function(){
		$(this).find("img").toggleClass("rotate-180");
	});
});
