$(function() {
    var offset = 1;
    var old_offset = 0;
    var $post = $("#post");
    var $new_post = $("#new_post");
    var lock = false;
    $(window).scroll(function() {
        if (!lock && $(document).height() - ($(window).scrollTop() + $(window).height()) <= 5 ) {
            lock = true;
            var offset = $post.children('.post').length + $new_post.children('.post').length
            var url = '{site_url}wall/status/' + offset;
            var member_id = $("#status_member_id").val();
            if (member_id) { url += '/' + member_id; }
            $.ajax({
                url: url,
                method: 'get',
                beforeSend: function() {
                    $(".loading-status").fadeIn();
                },
                success: function(response) {
                    
                    if (response.length > 0) {
                        $("#post").append(response);
                        old_offset = offset;
                    }

                    formatLinks();
                    $(".loading-status").fadeOut();
                    lock = false;
                },
                error: function(error) {
                    console.log(error);
                    $(".loading-status").fadeOut();
                    processing = false;
                    lock = false;
                }
            });
        }
    });
});
