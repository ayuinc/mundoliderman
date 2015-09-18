$(document).on('click', '.delete_post', function(e) {
  e.preventDefault();
  var img = $(this);
  var form = img.parent().parent().parent()[0];
  var post = $(form).parent().parent().parent().parent();
  
  var url = $(form).attr("action");
  var data = $(form).serialize();

  $.post(url, data, function(result) {
    post.fadeOut();
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

  /*PREVISUALIZACION IMAGEN*/
  function setImageFromInputFile(inputImage, imageContainer) {
    if (inputImage.files) {
      var file = inputImage.files[0];
      if (file) {
        var reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = function(e) {
          document.getElementById(imageContainer).style.backgroundImage = "url('" + e.target.result + "')";
        }
      };
    };
  }
  $("#inputFile").change(function() {
    if ($(this).val()) {
      $(".container-photos").addClass("pt-7 pb-21 pl-21");
      $("#photos-post").attr("style", "border: 1px solid #e7e7e7; width:150px; height:150px; background-size:cover; background-position:center; z-index:1");
      setImageFromInputFile(this, "photos-post");
      $("#photos-post").show();
    }
  });
  $(".close-image").on("click", function(){
    $("#photos-post").css('background-image', "url('')");
    $("#inputFile").val('');
    $("#photos-post").hide();
  });
});
