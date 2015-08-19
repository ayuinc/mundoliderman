function auto_grow(element) {
    element.style.height = "5px";
    element.style.height = (element.scrollHeight)+"px";
}

/*POSTEAR CON ENTER*/
$(document).ready(function(){
	$(".write-comment").keypress(function(event){
		if(event.which == 13) {
			event.preventDefault();
			alert("Postea");
		}
	});
});