window.addEventListener("load", function() {
	var username = document.getElementById("name");
	if (username != undefined) {
		username.textContent = username.textContent.substr(0, username.textContent.indexOf(" "));
	};
});