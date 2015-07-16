$(".switch").click(function() {
  $(this).toggleClass("on");
  $(".overlay-menu").toggleClass("open-menu");
  $(".btn-menu p").toggleClass("hidden");
});