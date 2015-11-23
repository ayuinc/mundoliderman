$(function() {
    var offset = 0;
    $(window).scroll(function() {
        if ($(document).height() - ($(window).scrollTop() + $(window).height()) <= 5 ) {
            $.ajax({
                url: '{site_url}wall/status/' + (offset + 20),
                method: 'get',
                success: function(response) {
                    if (response.length > 0) {
                        $("#post").append(response);
                        offset = offset + 20;
                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }
    });
});