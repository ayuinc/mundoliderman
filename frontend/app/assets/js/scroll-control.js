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
		$(".respuesta").toggleClass("hidden visible-xs");
		$(".cuadro-respuesta").toggleClass("hidden");
	});
	$(".active-slide").click(function(event){
		event.preventDefault();
		$(".list-slide").toggleClass("active");
	});
	/*FOOTER*/
	$("#up").click(function(event){
		event.preventDefault();
		$("footer > div").toggleClass("active-up");
			/*FRAME VIDEO*/
		// $(".video object").attr("height", "285px");
	});

	/*radio urbe*/ 
	$(".a-audio").click(function(event){
		event.preventDefault();
		$("span").removeClass("text-fuccia");
		$(".btn-audio span").toggleClass("text-fuccia");
		$(".audio").removeClass("hidden");
		$(".video").addClass("hidden");
	});
	$(".a-video").click(function(event){
		event.preventDefault();
		$("span").removeClass("text-fuccia");
		$(".btn-video span").toggleClass("text-fuccia");
		$(".audio").addClass("hidden");
		$(".video").removeClass("hidden");
	});


});

