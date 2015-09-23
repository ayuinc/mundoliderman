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
});

$(document).scroll(function() {    
	var scroll = $(this).scrollTop();
	console.log("Scroll: "+scroll);
	console.log("positionAlert: "+positionAlert);


  if (scroll >= positionAlert) {
      $("#alert-publication").addClass("fixed");
      $("#alert-publication").addClass("top-banner");
      $("#alert-publication").removeClass("relative");
  } else {
      $("#alert-publication").removeClass("fixed");
      $("#alert-publication").removeClass("top-banner");
      $("#alert-publication").addClass("relative");
  }

  $(".close-alert").on("click", function(){
    $(".alert-container").addClass("hidden");  
  });
  $('a.scroll').click(function(e){
    e.preventDefault();
    $(this).addClass("alert-hidden");
    setTimeout(function(){
      $(".alert-container").attr("style", "display:none");}, 200);
      $('html, body').stop().animate({scrollTop: $($(this).attr('href')).offset().top}, 1000);
  }); 
});