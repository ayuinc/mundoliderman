function auto_grow(element) {
    element.style.height = "5px";
    element.style.height = (element.scrollHeight)+"px";
}

/*POSTEAR CON ENTER*/
$(document).ready(function(){
	/*ENTER-RESPONSIVE*/
	$(".enter-responsive img").click(function(){
		alert("Publica :D");
	});
});