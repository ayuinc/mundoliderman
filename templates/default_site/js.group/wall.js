$(document).ready(function() {
  $("#status_form").attr("enctype", "multipart/form-data");
  formatLinks();
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

  $.post(url, data, function (response) {
    var like = JSON.parse(response);
    if (like.result == 'success') {
      send(response);
      btn.parent().find(".like").toggleClass("active-like");
      btn.parent().find(".img-like").toggleClass("hidden");
      $("span[data-like-post-id=" + like.post_id + "]").text(like.total);
    } else if (like.result == 'fail') {
      location.reload(true);
    }
  });
});

$(document).on('keypress', '.write-comment', function(e){
  if(e.which == 13) {
    e.preventDefault();
    var $comment_area = $(this);
    var comment = $comment_area.val();
    if (comment.trim() != "" || comment.trim().length != 0) {
      var form = $comment_area.parent();
      var url = $(form).attr("action");
      var data = $(form).serialize();
      $comment_area.prop("disabled", true);
      $.post(url, data, function(result) {
        var comment_data = JSON.parse(result);
        if (comment_data.result == 'success') {
          send(result);
          $.ajax({
            method: "GET",
            url: url + "wall/new_comment/" + comment_data.comment_id
          })
          .done(function(comment) {
            $("div[data-comment-container-post-id=" + comment_data.post_id +"]").append(comment);
            $("span[data-comment-post-id=" + comment_data.post_id +"]").text(comment_data.total);
            $comment_area.val("");
            $comment_area.prop("disabled", false);
            reset_grow($comment_area[0]);
            formatLinks();
          });
        } else if (comment_data.result == 'fail') {
          location.reload(true);
        }
      });
    }
  }
});


$(document).on('click', '.mobile_comment', function(e) {
  var $form = $(this).parents().eq(1);
  var $comment_data = $form.find("textarea");
  var comment = $comment_data.val();
  if (comment.trim() != "" || comment.trim().length != 0) {
    var url = $form.attr("action");
    var data = $form.serialize();
    $comment_data.prop("disabled", true);
    $.post(url, data, function(response) {
      var comment_data = JSON.parse(response);
      if (comment_data.result == 'success') {
        send(response);
        $.ajax({
          method: "GET",
          url: url + "wall/new_comment/" + comment_data.comment_id
        })
        .done(function(comment) {
          $("div[data-comment-container-post-id=" + comment_data.post_id +"]").append(comment);
          $("span[data-comment-post-id=" + comment_data.post_id +"]").text(comment_data.total);
          $comment_data.val('');
          $comment_data.prop("disabled", false);
          reset_grow($comment_data[0]);
          formatLinks();
        });
      } else if (comment_data.result == 'fail') {
        location.reload(true);
      }
      
    });
  }
});

$(document).on('change', '#inputFile', function() {
  if ($(this).val()) {
    $(".container-photos").addClass("pt-7 pb-21 pl-21");
    $("#photos-post").attr("style", "border: 1px solid #e7e7e7; width:120px; height:120px; background-size:cover; background-position:center; z-index:1");
    $(".close-image").addClass("btn-block");
    $(".close-image").removeClass("close-display");
    setImageFromInputFile(this, "photos-post");
    $("#photos-post").show();
  }
});

$(document).on('click', '.close-image', function(){
  $("#photos-post").css('background-image', "url('')");
  $("#inputFile").val('');
  $("#photos-post").hide();
  $(".close-image").removeClass("btn-block");
  $(".close-image").addClass("close-display");
});

$(document).on('submit', '#status_form', function(e) {
  e.preventDefault();
  $("#submit-post").attr("disabled", true);
  var friends_status = $("#wall_status").val();
  var status_category = $("#status_category").val();
  if (status_category == 0) {
    $('#error-categoria').html('<img src="{site_url}frontend/app/assets/img/categoria2.png">');
    $("#submit-post").removeAttr("disabled");
    return;
  } else {
    $("#error-categoria").empty();
  };
  if(friends_status.trim().length == 0 || friends_status.trim() == "") {
    $('#error-message').html('<img src="{site_url}frontend/app/assets/img/mensaje.png">');
    $("#submit-post").removeAttr("disabled");
    return;
  } else {
    $("#error-message").empty();
  }
  var form = $(this);
  var url = form.attr("action");
  var formData = new FormData(form[0]);
  $.ajax({
    url: url,
    type: 'POST',
    data: formData,
    async: false,
    beforeSend: function() {
      $("#loader").fadeIn();
    },
    success: function(data) {
      var response = JSON.parse(data);
      if (response.result == 'success') {
        send(data);
        $.ajax({
          method: 'GET',
          url: '{site_url}wall/new_post/' + response.post_id + '/{member_group}'
        })
        .done(function(post) {
          $("#wall_status").val('');
          $("#status_category").val('0');
          $("#photos-post").css('background-image', "url('')");
          $("#inputFile").val('');
          $("#photos-post").hide();
          $("#post").prepend(post);
          $("#submit-post").removeAttr("disabled");
          $("#loader").fadeOut();
          reset_grow(document.getElementById("wall_status"));
          formatLinks();
        })
        .fail(function(error) {
          $("#submit-post").removeAttr("disabled");
          $("#loader").fadeOut();
        });
      } else if (response.result == 'fail') {
        location.reload(true);
      }
    },
    error: function(data) {
      $("#submit-post").removeAttr("disabled");
      $("#loader").fadeOut();
    },
    cache: false,
    contentType: false,
    processData: false
  });
});

