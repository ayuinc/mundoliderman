$(document).ready(function(){
	// $(".comment").click(function(event){
	// 	event.preventDefault();
	// 	$(".scroll-down").toggleClass("hidden");
	// });
	// $(".responder").click(function(event){
	// 	event.preventDefault();
	// 	$(".respuesta").toggleClass("hidden");
	// });
	// $(".active-slide").click(function(event){
	// 	event.preventDefault();
	// 	$(".list-slide").toggleClass("active");
	// });
	$("#up").click(function(event){
		event.preventDefault();
		$("footer").toggleClass("active-up");
	});
});
