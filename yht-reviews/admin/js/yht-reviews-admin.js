function copy_on_click(element) {
	element.select();
	element.setSelectionRange(0, 99999); /*For mobile devices*/

	/* Copy the text inside the text field */
	document.execCommand("copy");
}