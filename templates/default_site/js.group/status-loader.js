$(function() {
    var offset = 1;
    var old_offset = 0;
    $(window).scroll(function() {
        var url = '{site_url}wall/status/' + offset;
        var member_id = $("#status_member_id").val();
        if (member_id) { url += '/' + member_id; }
        if ($(document).height() - ($(window).scrollTop() + $(window).height()) <= 5 ) {
            $.ajax({
                url: url,
                method: 'get',
                beforeSend: function() {
                    $(".loading-status").fadeIn();
                },
                success: function(response) {
                    var offset_requested = parseInt(this.url.substr(this.url.length - 1, 1));
                    if (old_offset != offset_requested) {
                        if (response.length > 0) {
                            $("#post").append(response);
                            old_offset = offset;
                            offset += 1;
                        }
$(".scroll-down").removeClass("hidden");
                    }
                    $(".loading-status").fadeOut();
                },
                error: function(error) {
                    console.log(error);
                    $(".loading-status").fadeOut();
                    processing = false;
                }
            });
        }
    });
});
