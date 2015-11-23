$(document).ready(function(){
	/*RESPUESTAS Y COMENTARIOS*/
	$(".responder").click(function(event){
		event.preventDefault();
		var target = $(event.target);
		$(target).parents(".conteiner-answer").find(".respuesta").toggleClass("hidden visible-xs");
		$(target).parents(".conteiner-answer").find(".cuadro-respuesta").toggleClass("hidden");
	});
	$(".active-slide").click(function(event){
		event.preventDefault();
		$(".list-slide").toggleClass("active");
	});
	/*FOOTER*/
	$("#up").click(function(event){
		event.preventDefault();
		$("footer > div").toggleClass("active-up");
		$(".audio-video").toggleClass("hidden");
		$(".flecha-up").toggleClass("hidden");
		$(".flecha-down").toggleClass("hidden");
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
/*
function expandComments(element) {
	$(element).parents(".post").find(".area-respuesta").addClass("hidden");
	$(element).parents(".post").find(".scroll-down").toggleClass("hidden");
	$(element).parents(".post").find(".area-respuesta").toggleClass("hidden");	
}

$(".btn-comentar").click(function(event){
	event.preventDefault();
	var target = $(event.target);
	$(target).parents(".post").find(".area-respuesta").addClass("hidden");
	$(target).parents(".post").find(".scroll-down").toggleClass("hidden");
	$(target).parents(".post").find(".area-respuesta").toggleClass("hidden");
});*/

$(document).on('click', '.btn-comentar', function(event){
	event.preventDefault();
	var target = $(event.target);
	$(target).parents(".post").find(".area-respuesta").addClass("hidden");
	$(target).parents(".post").find(".scroll-down").toggleClass("hidden");
	$(target).parents(".post").find(".area-respuesta").toggleClass("hidden");
});