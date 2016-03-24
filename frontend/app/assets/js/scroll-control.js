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


$(document).on('click', '.btn-comentar', function(event){
	event.preventDefault();
	var target = $(event.target);
	var $postClicked = $(target).parents(".post").eq(0);
	var $areaComentarios = $postClicked.find(".comentar");

	if ($areaComentarios.hasClass('hidden')) {
		$areaComentarios.removeClass("hidden");
	}

	if ($postClicked.attr("data-solved") !== "y") {
		var $areaRespuesta = $areaComentarios.find(".area-respuesta");
		$areaRespuesta.removeClass("hidden");
		$areaRespuesta.find(".write-comment").focus();
	}

});

$(document).on('click', '.comment', function(e){
  e.preventDefault();
  var $postClicked = $(this).parents(".post").eq(0);
  var $areaComentarios = $postClicked.find(".comentar");
  if ($postClicked.attr("data-solved") !== "y") {
    var $areaRespuesta = $postClicked.find(".area-respuesta");
    $areaRespuesta.removeClass("hidden");
  }
  $areaComentarios.toggleClass("hidden");
});