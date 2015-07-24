$(document).ready(function(){
	$(".contenedor-menu-2 li a").click(function(e){
		e.preventDefault();
		$(".contenedor-menu-2 li a").removeClass("bold");
		$(this).addClass("bold");
	});
});