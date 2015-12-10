$(function() {
    var offset = 1;
    $(window).scroll(function() {
        var url = '{site_url}wall/status/' + offset;
        var member_id = $("#status_member_id").val();
        if (member_id) { url += '/' + member_id; }
        if ($(document).height() >= ($(window).scrollTop() + $(window).height() + 1)) {
            $.ajax({
                url: url,
                method: 'get',
                beforeSend: function() {
                    $(".loading-status").fadeIn();
                },
                success: function(response) {
                    if (response.length > 0) {
                        $("#post").append(response);
                        offset += 1;
	                }
                    $(".loading-status").fadeOut();
                },
                error: function(error) {
                    console.log(error);
                    $(".loading-status").fadeOut();
                }
            });
        }
    });
});
