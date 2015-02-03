jQuery(document).ready(function($){

	// Data table time range selector
	$(".sp-data-table .sp-time, .sp-data-table .sp-time-range").change(function() {
		$(this).siblings().val($(this).val());
	});

	// Highlights table to update player performance
	$(".sp-highlight-table select").change(function() {
		$(this).closest("tr").find(".sp-player-performance-select option").each(function() {
			performance = $(this).val();
			$(this).closest("tr").find(".sp-player-id-select option").each(function() {
				player = $(this).val();
				$rows = $(this).closest(".sp-highlight-table").find("tr").filter(function() {
					return $(this).find(".sp-player-id-select").val() == player && $(this).find(".sp-player-performance-select").val() == performance;
				});
				$(".sp-performance-table tr[data-player='"+player+"'] .sp-player-"+performance+"-input").attr("placeholder", $rows.length).trigger("keyup");
			});
		});
	});

});