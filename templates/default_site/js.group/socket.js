var Server;
var CHATEADORA = 6;

var pageTitle = document.title;
function addNotificationsHeader(num) {
  document.title = "(" + num + ") " + pageTitle;
}

function removeNotificationsHeader() {
  document.title = pageTitle;
}

function log( text ) {
  console.log(text);
}

function send( text ) {
  Server.send( 'message', text );
}

$(function() {
  Server = new FancyWebSocket('{exp:socket:socket_url}');

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
            var notInWall = location.pathname.indexOf('perfil') >= 0 || location.pathname.indexOf('servicios') >= 0;
            var member_group = {member_group};

            if (notInWall && member_group !== CHATEADORA) return;

            $.ajax({
                method: 'GET',
                url: '{site_url}wall/new_post/' + response.post_id + '/{member_group}'
            })
            .done(function(post) {
                $("#new_post").prepend(post);
                var count = $("#new_post").find(".post-1").length;
                $("#new_post_count").text(count);
                addNotificationsHeader(count);
                
                if (notInWall) {
                  $("#alert-publication").on('click', function(e) {
                    location.href = '{site_url}';
                  });
                }

                $("#alert-publication").fadeIn().delay(5000);
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
            });
            break;
        case 'like':
            $("span[data-like-post-id=" + response.post_id + "]").text(response.total);
            break;
    }
}
});

  Server.connect();
});