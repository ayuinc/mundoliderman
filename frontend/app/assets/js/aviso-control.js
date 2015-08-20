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
		$(".activo").toggleClass("desaparecer");
		$(".inactivo").toggleClass("desaparecer");
	});
});