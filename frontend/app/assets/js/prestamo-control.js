$(document).ready(function(){
	$("#freeform_selecciona_un_monto").change(function(){
		var value = $("#freeform_selecciona_un_monto").val();
		var secondLine = $(".line").slice(2,3); /*Permite seleccionar le 2do elemento*/
		if(value == "Otro") {
			$(secondLine).attr("style", "display:block!important");
			$("#freeform_introduce_el_monto_solicitado").attr("required","required");
		}else {
			$(secondLine).removeAttr("style", "display:block!important");
			$("#freeform_introduce_el_monto_solicitado").removeAttr("required","required");
		}
	});

	$("#freeform_introduce_el_monto_solicitado").attr("placeholder", "S/.");

	/*Solicitud de prestamo*/
	$(".rechazar").click(function(){
		var target = $(event.target);
		$(target).parents(".btn-group-prestamo").find(".ex1, .check2").addClass("hidden");
		$(target).parents(".btn-group-prestamo").find(".ex2, .check1").removeClass("hidden");
	});
	$(".aceptar").click(function(){
		var target = $(event.target);
		$(target).parents(".btn-group-prestamo").find(".check1, .ex2").addClass("hidden");
		$(target).parents(".btn-group-prestamo").find(".check2, .ex1").removeClass("hidden");
	});
});