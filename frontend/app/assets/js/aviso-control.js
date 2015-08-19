$(document).ready(function(){
	/*RESUELTO Y NO RESUELTO*/
	$(".aviso").click(function(){
		$(this).parent().find(".btn-aviso1").toggleClass("hidden");
		$(this).parent().find(".btn-aviso2").toggleClass("hidden");
	});
	$(".like-container").click(function(e){
		e.preventDefault();
		$(this).parent().find(".like").toggleClass("active-like");
		$(this).parent().find(".img-like").toggleClass("hidden");
	});

	/*BTN ELIMINAR PUBLICACIÓN*/
	$(".eraser").hover(function(){
		$(".activo").toggleClass("desaparecer");
		$(".inactivo").toggleClass("desaparecer");
	});
	// $(".eraser").mouseleave(function(){
	// 	$(".activo").removeClass("desaparecer");
	// 	$(".inactivo").addClass("desaparecer");
	// });
});