function auto_grow(element) {
	if (!element.hasAttribute('flagHeight')) element.setAttribute('flagHeight', element.clientHeight);
    element.style.height = "5px";
    element.style.height = (element.scrollHeight)+"px";
}

function reset_grow(element) {
	if (element.hasAttribute('flagHeight')) {
		element.style.height = element.getAttribute('flagHeight') + "px";
	};
}