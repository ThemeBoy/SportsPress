jQuery(document).ready(function($){
	// Split statistics row
	$(".sp-highlight-places").on("click", ".sp-add-row", function() {

	return false;
	});
	
	// Delete added row
	$(".sp-highlight-places").on("click", ".sp-delete-row", function() {
		$self = $(this);
		$self.closest("tr").css("background-color", "#f99").fadeOut(400, function() {
			$(this).remove();
		});
		return false;
	});
});