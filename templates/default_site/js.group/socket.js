var Server;

function log( text ) {
  console.log(text);
}

function send( text ) {
  Server.send( 'message', text );
}

$(function() {
  Server = new FancyWebSocket('ws://52.20.3.133:9300');

  //Let the user know we're connected
  Server.bind('open', function() {
    log( "Connected." );
});

  //OH NOES! Disconnection occurred.
  Server.bind('close', function( data ) {
    log( "Disconnected." );
});

  //Log any messages sent from server
  Server.bind('message', function( payload ) {
    var response = JSON.parse(payload);
    if (response.result == 'success') {
      switch(response.action) {
        case 'status': 
            $.ajax({
                method: 'GET',
                url: '{site_url}wall/new_post/' + response.post_id + '/{member_group}'
            })
            .done(function(post) {
                $("#new_post").prepend(post);
                $("#new_post_count").text($("#new_post").find(".post-1").length);
                $("#alert-publication").fadeIn().delay(5000).fadeOut();
            });
            break;
        case 'comment':
            $.ajax({
                method: "GET",
                url: "{site_url}wall/new_comment/" + response.comment_id
            })
            .done(function(comment) {
                $("div[data-comment-container-post-id=" + response.post_id +"]").append(comment);
                $("span[data-comment-post-id=" + response.post_id +"]").text(response.total);
                comment_area.val("");
            });
            break;
    }
}
});

  Server.connect();
});