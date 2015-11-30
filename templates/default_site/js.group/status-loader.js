$(function() {
    var offset = 1;
    $(window).scroll(function() {
        if ($(document).height() - ($(window).scrollTop() + $(window).height()) <= 5 ) {
            $.ajax({
                url: '{site_url}wall/status/' + offset,
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
