$(document).ready(function() {
			var name = $("#name");
			// var numericReg = \s;
			var RegExPattern = \s;

			function validar(e) {
				// var inputVal = String.fromCharCode(e.keyCode);
				// var mensaje = document.getElementById("mensaje");
				// if(!numericReg.test(inputVal)) {
				// 	mensajeUp.val(" ");
				//     e.preventDefault();
			 //    }
		  //   else {
		  //   	mensajeUp.innerText = "";;
		  //   	return;
		  //   }
		    if ((campo.value.match(RegExPattern)) && (campo.value!='')) {
        alert('Password Correcta'); 
		    } else {
		        alert(errorMessage);
		        campo.focus();
		    }
			}
			// $('#day').keydown(function(e) {
			// 	if ( 12 < mensajeUp.val()) {
		 //    	mensajeUp.val("12");
		 //    }
			//   validar(e);
			// });
		});

// function validatePass(campo) {
//     var RegExPattern = \s;
//     var errorMessage = 'Password Incorrecta.';
//     if ((campo.value.match(RegExPattern)) && (campo.value!='')) {
//         alert('Password Correcta'); 
//     } else {
//         alert(errorMessage);
//         campo.focus();
//     } 
// }