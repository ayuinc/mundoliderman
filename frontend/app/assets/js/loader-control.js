$(document).ready(function(){
	$("#loader img").addClass("hidden");
	function loader(name) {
		$(name).click(function(){
		$("#loader img").fadeIn(1000);
		$("#loader img").removeClass("hidden");
		setTimeout (function(){
			$("#loader img").fadeOut(1500);
		}, 2000)
	});	
	}
	loader("button[type='submit']");
	loader(".ver-detalle");	
});