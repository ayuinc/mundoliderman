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
function formatLinks() {
  $(".pending-format").each(function(index, element){
       element.innerHTML = urlify(element.innerHTML);
       element.innerHTML = addBr(element.innerHTML);
       $(element).removeClass("pending-format");
  });
}

function urlify(text) {
  var urls = findUrls(text);
  return replaceLinks(text, urls);
}

function addBr(text) {
	return "<p class=\"mb-0\">" + text.split('\n').join('</p><p class=\"mb-0\">') + "</p>";
}

function updateSolvedStatus(postId, solved) {
	var $post = $("#post-" + postId);
	var $btnAviso1 = $post.find(".btn-aviso1");
	var $btnAviso2 = $post.find(".btn-aviso2");
	var $areaComentar = $post.find(".area-respuesta");

	if (solved == 'y') {
		$btnAviso1.removeClass("hidden");
		$btnAviso2.addClass("hidden");
		$areaComentar.addClass("hidden");
	} else {
		$btnAviso1.addClass("hidden");
		$btnAviso2.removeClass("hidden");
		$areaComentar.removeClass("hidden");
	}

	$post.attr("data-solved", solved);

}

function closeMenu() {
	$(".switch").removeClass('on');
	$(".overlay-menu").removeClass("open-menu");
  $(".btn-menu p").eq(0).removeClass("hidden");
  $(".btn-menu p").eq(1).addClass("hidden");
}