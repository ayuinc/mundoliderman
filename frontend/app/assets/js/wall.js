$(document).ready(function() {
  $("#status_form").attr("enctype", "multipart/form-data");
});

$(document).on('click', '.delete_post', function(e) {
  e.preventDefault();
  var img = $(this);
  var form = img.parents()[1];
  var post = $(form).parents()[4];
  
  var url = $(form).attr("action");
  var data = $(form).serialize();

  $.post(url, data, function(result) {
    $(post).fadeOut();
  });
});

$(document).on('click', '.delete-comment', function(e) {
  e.preventDefault();
  var span = $(this);
  var form = span.parent();
  var comment = $(span).parent().parent().parent();

  var url = $(form).attr("action");
  var data = $(form).serialize();

  $.post(url, data, function(result) {
    var comment_data = JSON.parse(result);
    comment.fadeOut();
    $("span[data-comment-post-id=" + comment_data.post_id +"]").text(comment_data.total);
  });
});

/*BTN ELIMINAR PUBLICACIÓN*/
$(document).on('mouseenter', '.eraser', function(){
  $(this).find(".activo").toggleClass("desaparecer");
  $(this).find(".inactivo").toggleClass("desaparecer");
});

$(document).on('mouseleave', '.eraser', function(){
  $(this).find(".activo").toggleClass("desaparecer");
  $(this).find(".inactivo").toggleClass("desaparecer");
});

$(document).on('click', '.like-container', function(e){
  e.preventDefault();
  var btn = $(this);
  var form = btn.parent();
  var url = form.attr("action");
  var data = form.serialize();

  $.post(url, data, function (result) {
    var like = JSON.parse(result);
    btn.parent().find(".like").toggleClass("active-like");
    btn.parent().find(".img-like").toggleClass("hidden");
    $("span[data-like-post-id=" + like.post_id + "]").text(like.total);
  });
});

$(document).on('keypress', '.write-comment', function(e){
  if(e.which == 13) {
    e.preventDefault();
    var comment_area = $(this);
    var form = comment_area.parent();
    var url = $(form).attr("action");
    var data = $(form).serialize();

    $.post(url, data, function(result) {
      var comment_data = JSON.parse(result);
      send(comment_data);
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

$(document).on('click', '.comment', function(e){
  e.preventDefault();
  var $scroll_down = $(this).parents().eq(3).find(".scroll-down");
  var $area_respuesta = $(this).parents().eq(3).find(".area-respuesta");
  $scroll_down.toggleClass("hidden");
  $area_respuesta.addClass("hidden");
});