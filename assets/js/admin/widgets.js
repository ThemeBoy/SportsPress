jQuery(document).ready(function($){

	// Orderby affects order select in widget options
	$("body").on("change", ".sp-select-orderby", function() {
		$(this).closest(".widget-content").find(".sp-select-order").prop("disabled", $(this).val() == "default");
	});

	// Calendar affects view all link checkbox in widget options
	$("body").on("change", ".sp-event-calendar-select", function() {
		$el = $(this).closest(".widget-content").find(".sp-event-calendar-show-all-toggle");
		if($(this).val() == 0)
			$el.hide();
		else
			$el.show();
	});

	// Show or hide datepicker
	$("body").on("change", ".sp-date-selector select", function() {
		if ( $(this).val() == "range" ) {
			$(this).closest(".sp-date-selector").find(".sp-date-range").show();
		} else {
			$(this).closest(".sp-date-selector").find(".sp-date-range").hide();
		}
	});
	$(".sp-date-selector select").trigger("change");
});