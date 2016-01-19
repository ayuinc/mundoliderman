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
function findUrls( text ) {
	var source = (text || '').toString();
	var urlArray = [];
	var url;
	var matchArray;

	// Regular expression to find FTP, HTTP(S) and email URLs.
	var regexToken = /[-a-zA-Z0-9@:%_\+.~#?&//=]{2,256}\.[a-z]{2,4}\b(\/[-a-zA-Z0-9@:%_\+.~#?&//=]*)?/gi;

	// Iterate through any URLs in the text.
	while( (matchArray = regexToken.exec( source )) !== null )
	{
	  var token = matchArray[0];
	  urlArray.push( token );
	}

	return urlArray;
}
function replaceLinks( text, urls ) {
	if (urls.length == 0) return text;
	var text_formated = '';
	urls.forEach(function(url) {
	  text_formated = text.replace(url, '<a href="' + (url.indexOf('http') ? '//' + url : url) + '" target="_blank" class="post-link" >' + url + '</a>');
	});
	return text_formated;
}