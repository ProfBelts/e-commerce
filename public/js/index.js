$(document).ready(function () {
	// Hide default spinner buttons
	$("#spinner").css("-webkit-appearance", "none");
	$("#spinner").css("-moz-appearance", "textfield");

	// Handle increase and decrease using mousewheel
	$("#spinner").on("mousewheel", function (e) {
		e.preventDefault();
		if (e.originalEvent.deltaY > 0) {
			$(this).val(Math.min(parseInt($(this).val()) + 1, 99));
		} else {
			$(this).val(Math.max(parseInt($(this).val()) - 1, 1));
		}
	});
});
