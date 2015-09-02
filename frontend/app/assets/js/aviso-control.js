$(document).ready(function(){
	
	$(".aviso").click(function(){
		$(this).parent().find(".btn-aviso1").toggleClass("hidden");
		$(this).parent().find(".btn-aviso2").toggleClass("hidden");
	});
	$(".like-container").click(function(e){
		e.preventDefault();
		//$(this).parent().find(".like").toggleClass("active-like");
		//$(this).parent().find(".img-like").toggleClass("hidden");
		$(this).parent().submit();
	});

	$(".like-container").each(function(index, element) {
		var like_status = $(element).parent().find("input[name=post_like_status]").val();
		var like_text = $(element).parent().find(".like");
		var unlike_img = $(element).parent().find(".img-like")[0];
		var like_img = $(element).parent().find(".img-like")[1];
		if (like_status == "y") {
			$(like_text).addClass("active-like");
			$(like_img).removeClass("hidden");
			$(unlike_img).addClass("hidden");
		} else {
			$(like_text).removeClass("active-like");
			$(like_img).addClass("hidden");
			$(unlike_img).removeClass("hidden");
		}
	});

	/*BTN ELIMINAR PUBLICACIÓN*/
	$(".eraser").hover(function(){
		$(this).find(".activo").toggleClass("desaparecer");
		$(this).find(".inactivo").toggleClass("desaparecer");
	});

	/*Btn-premium y destacado*/
	$(".btn-premium").click(function(){
		$(this).toggleClass("text-primary");
		$(this).toggleClass("text-gray");
		$(".img-premium").toggleClass("opacity-active");
		$(".premium-resp").toggleClass("opacity-active");
	});
	$(".btn-destacado").click(function(){
		$(this).toggleClass("text-primary");
		$(this).toggleClass("text-gray");
		$(".img-destacado").toggleClass("opacity-active");
		$(".destacado-resp").toggleClass("opacity-active");
	});

	/*Btn-premium y destacado responsive*/
	var atrrPremium = $(".premium-resp").attr("class");
	var atrrDestacado = $(".destacado-resp").attr("class");
	var opacity = "premium-resp relative hidden-lg hidden-md opacity-active";

	$(".btn-destacado").click(function(){
		if ( (atrrPremium != opacity) && (atrrDestacado != opacity)) {
			console.log("Hola");
			$(".premium-resp").attr("style", "right:0px!important; top:-25px");
			$(".destacado-resp").attr("style", "right:0px!important; top:-25px");
		}
	});
});