jQuery(document).ready(function($){
	$(".sp-tournament-container .sp-datepicker").on("change", function() {
		$fields = $(this).siblings("input[type=text]");
		if ( $(this).val() ) {
			$fields.attr("disabled", false);
		} else {
			$fields.attr("disabled", true);
		}
	}).trigger("change");
	
	$(".sp-tournament-container .sp-event .sp-hide").on("click", function() {
		value = $(this).closest(".sp-event").hasClass("sp-event-hidden") ? 0 : 1;
		$(this).siblings(".sp-hidden").val(value);
		$el = $(this).closest(".sp-event");
		$el.toggleClass("sp-event-hidden");
		id = $el.data("event");
		$el.closest(".sp-tournament-container").find("[data-event="+id+"]").toggleClass("sp-team-hidden");
		return false;
	});
});