$(document).on('click', 'a.smooth', function(e) {
	e.preventDefault();
    var $link = $(this);
    var anchor  = $link.attr('href');
    $('html, body').stop().animate({
        scrollTop: $(anchor).position().top
    }, 1000);
});

/*Banner alerta de publicaciones*/
var positionAlert;
$(document).ready(function(){
	positionAlert = $("#alert-publication").offset().top - 82;	
  var scroll = $(this).scrollTop();
  console.log("Scroll: "+scroll);
  console.log("positionAlert: "+positionAlert);
  showAlert(scroll, positionAlert);

  $(".close-alert").on("click", function(){
    $("#alert-publication").fadeOut();  
  });

  $('a.scroll').click(function(e){
    e.preventDefault();
    $("#alert-publication").dequeue();
    $('html, body').stop().animate({scrollTop: $($(this).attr('href')).offset().top}, 1000);
    var new_posts = $("#new_post").html();
    $("#post").prepend(new_posts);
    $("#new_post").html("");
  }); 
});

$(document).scroll(function() {    
	var scroll = $(this).scrollTop();
	console.log("Scroll: "+scroll);
	console.log("positionAlert: "+positionAlert);
  showAlert(scroll, positionAlert);
});

function showAlert(scroll, alertPosition) {
  if (scroll >= alertPosition) {
      $("#alert-publication").addClass("fixed");
      $("#alert-publication").addClass("top-banner");
      $("#alert-publication").removeClass("relative");
  } else {
      $("#alert-publication").removeClass("fixed");
      $("#alert-publication").removeClass("top-banner");
      $("#alert-publication").addClass("relative");
  }
}