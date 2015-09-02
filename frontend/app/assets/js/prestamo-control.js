$(document).ready(function(){
	$("#freeform_selecciona_un_monto").change(function(){
		var value = $("#freeform_selecciona_un_monto").val();
		var secondLine = $(".line").slice(1,2); /*Permite seleccionar le 2do elemento*/
		if(value == "Otro") {
			$(secondLine).attr("style", "display:block!important");
		}else {
			$(secondLine).removeAttr("style", "display:block!important");
		}
	});

	$("#freeform_introduce_el_monto_solicitado").attr("placeholder", "S/.");
});