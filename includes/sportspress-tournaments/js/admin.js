jQuery(document).ready(function($){

	// Insert teams on event change
	$(".sp-tournament-container .sp-event-selector").on("change", function() {
		$self = $(this);
		$self.closest("table").find(".sp-team-display[data-event="+$self.data("event")+"]").each(function( index ) {
			$(this).val($self.find(":selected").data( 1 == index ? "away" : "home" ) );
		});
	});

	// Trigger event change
	$(".sp-tournament-container .sp-event-selector").trigger("change");
});