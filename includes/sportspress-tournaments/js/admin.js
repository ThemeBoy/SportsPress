jQuery(document).ready(function($){
	$(".sp-tournament-container .sp-datepicker").on("change", function() {
		$fields = $(this).siblings("input");
		if ( $(this).val() ) {
			$fields.attr("disabled", false);
		} else {
			$fields.attr("disabled", true);
		}
	}).trigger("change");
});