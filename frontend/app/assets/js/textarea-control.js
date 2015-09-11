function auto_grow(element) {
    element.style.height = "5px";
    element.style.height = (element.scrollHeight)+"px";
}

/*POSTEAR CON ENTER*/
$(document).ready(function(){
	$(".write-comment").keypress(function(e){
		if(e.which == 13) {
			e.preventDefault();
			var comment_area = $(this);
			var form = comment_area.parent();
			var url = $(form).attr("action");
			var data = $(form).serialize();

			$.post(url, data, function(result) {
				var comment_data = JSON.parse(result);
				$.ajax({
					method: "GET",
					url: url + "wall/new_comment/" + comment_data.comment_id
				})
				.done(function(comment) {
					$("div[data-comment-container-post-id=" + comment_data.post_id +"]").append(comment);
					$("span[data-comment-post-id=" + comment_data.post_id +"]").text(comment_data.total);
					comment_area.val("");
				});
			});
		}
	});

	/*ENTER-RESPONSIVE*/
	$(".enter-responsive img").click(function(){
		alert("Publica :D");
	});
});