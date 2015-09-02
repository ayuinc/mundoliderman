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
	$(".reconocer").click(function() {
		var id = $(this).attr("id");
		var img = $(this).attr("data-img");
		var img_resp = $(this).attr("data-img-resp");
		$(this).toggleClass("text-primary");
		$(this).toggleClass("text-gray");
		$("." + img).toggleClass("opacity-active");
		$("." + img_resp).toggleClass("opacity-active");
		var btn_premium = "btn-premium";
		var btn_destacado = "btn-destacado";
		var img_premium = $("#" + btn_premium).attr("data-img");
		if (id == btn_premium) {
			var dest_img = $("#" + btn_destacado).attr("data-img-resp")
			if (!$("." + dest_img).hasClass("opacity-active") && !$("." + img_resp).hasClass("opacity-active")) {
				$("." + img_resp).attr("style", "right: 0px !important; top:-25px");
				$("." + dest_img).attr("style", "right: 0px !important; top:-25px");
				$("." + img_premium).attr("style", "right: 55px !important;");
			} else {
				$("." + img_resp).attr("style", "right: -22px !important; top:-25px");
				$("." + dest_img).attr("style", "right: 22px !important; top:-25px");
				$("." + img_premium).attr("style", "right: -0px !important;");
			}
		} else {
			var prem_img = $("#" + btn_premium).attr("data-img-resp")
			if (!$("." + prem_img).hasClass("opacity-active") && !$("." + img_resp).hasClass("opacity-active")) {
				$("." + prem_img).attr("style", "right: 0px !important; top:-25px");
				$("." + img_resp).attr("style", "right: 0px !important; top:-25px");
				$("." + img_premium).attr("style", "right: 55px !important;");
			} else {
				$("." + prem_img).attr("style", "right: -22px !important; top:-25px");
				$("." + dest_img).attr("style", "right: 22px !important; top:-25px");
				$("." + img_premium).attr("style", "right: 0px !important;");
			}
		}
	});
});