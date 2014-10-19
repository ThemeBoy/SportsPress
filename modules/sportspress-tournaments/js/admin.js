jQuery(document).ready(function($){

	// Event format affects data
	$(".post-type-sp_event #post-formats-select input.post-format").change(function() {
		layout = $(".post-type-sp_event #post-formats-select input:checked").val();
		if ( layout == "group" ) {
			$(".sp-event-sp_group-field").show().find("select").prop("disabled", false);
			$(".sp-event-sp_league-field").hide().find("select").prop("disabled", true);
			$(".sp-event-sp_season-field").hide().find("select").prop("disabled", true);
		} else if ( layout == "bracket" ) {
			$(".sp-event-sp_group-field").hide().find("select").prop("disabled", true);
			$(".sp-event-sp_league-field").hide().find("select").prop("disabled", true);
			$(".sp-event-sp_season-field").hide().find("select").prop("disabled", true);
		} else {
			$(".sp-event-sp_group-field").hide().find("select").prop("disabled", true);
		}
	});

	// Insert teams on event change
	$(".sp-tournament-bracket-container .sp-event-selector").on("change", function() {
		$self = $(this);
		$self.closest("table").find(".sp-team-display[data-event="+$self.data("event")+"]").each(function( index ) {
			$(this).val($self.find(":selected").data( 1 == index ? "away" : "home" ) );
		});
	});

	// Trigger event change
	$(".sp-tournament-bracket-container .sp-event-selector").trigger("change");
});