function solve(post) {
	var form = $(post).parent();
	var url = form.attr("action");
	var data = form.serialize();

	$.post(url, data, function(result) {
		var solved_data = JSON.parse(result);
		if (solved_data.solved == 'y') {
			$(".btn-aviso1[data-solve-post-id=" + solved_data.post_id + "]").removeClass("hidden");
			$(".btn-aviso2[data-solve-post-id=" + solved_data.post_id + "]").addClass("hidden");
		} else {
			$(".btn-aviso1[data-solve-post-id=" + solved_data.post_id + "]").addClass("hidden");
			$(".btn-aviso2[data-solve-post-id=" + solved_data.post_id + "]").removeClass("hidden");			
		}
	});
	return false;
}

$(document).ready(function(){

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

	/*Btn-premium y destacado*/
	$(".reconocer").click(function(e) {
		e.preventDefault();
		var element = $(this);
		var form = element.parent();
		var url = form.attr("action");
		var data = form.serialize();

		$.post(url, data, function(result) {
			set_achievement(element);
		});
	});

	$(".reconocer").each(function(index, element) {
		console.log($(element));
		var form = $(element).parent();
		var premium_status = form.find("input[name=premium_status]").val();
		if (premium_status == 'y') {
			set_achievement(element);
		};
	});

	function set_achievement(element) {
		var id = $(element).attr("id");
		var img = $(element).attr("data-img");
		var img_resp = $(element).attr("data-img-resp");
		$(element).toggleClass("text-primary");
		$(element).toggleClass("text-gray");
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
	}
});