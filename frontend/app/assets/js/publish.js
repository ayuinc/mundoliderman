/*PREVISUALIZACION IMAGEN*/
function setImageFromInputFile(inputImage, imageContainer) {
  if (inputImage.files) {
    var file = inputImage.files[0];
    if (file) {
      var reader = new FileReader();
      reader.readAsDataURL(file);
      reader.onload = function(e) {
        document.getElementById(imageContainer).style.backgroundImage = "url('" + e.target.result + "')";
      }
    };
  };
}