$(document).ready(function(){
	/*RESPUESTAS Y COMENTARIOS*/
	$(".comment").click(function(event){
		event.preventDefault();
		$(".scroll-down").toggleClass("hidden");
		$(".area-respuesta").addClass("hidden");
	});
	$(".btn-comentar").click(function(event){
		event.preventDefault();
		$(".area-respuesta").addClass("hidden");
		$(".scroll-down").toggleClass("hidden");
		$(".area-respuesta").toggleClass("hidden");
	});
	$(".responder").click(function(event){
		event.preventDefault();
		$(".respuesta").toggleClass("hidden");
	});
	$(".active-slide").click(function(event){
		event.preventDefault();
		$(".list-slide").toggleClass("active");
	});
	/*FOOTER*/
	$("#up").click(function(event){
		event.preventDefault();
		$("footer > div").toggleClass("active-up");
	});
});

