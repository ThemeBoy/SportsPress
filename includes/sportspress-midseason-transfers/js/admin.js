jQuery(document).ready(function($){
	// Split statistics row
	$(".sp-player-statistics-table").on("click", ".sp-add-row", function() {

		// Get league and season ID
		var league = $(this).data('league');
		var season = $(this).data('season');

		// Get table row and clone
		$tr = $(this).closest(".sp-row");
		$row = $tr.clone();
		$rows = $tr.closest(".sp-player-statistics-table").find(`.sp-row[data-league='${league}'][data-season='${season}']`);

		// Increment the season ID in the new input name
		$row.find("select").attr("name", `sp_leagues[${league}][${season}.${$rows.length}]`);
		$row.find("input").each(function() {
			column = $(this).data("column");
			$(this).attr("name", `sp_statistics[${league}][${season}.${$rows.length}][${column}]`);
		});

		// Replace season name with datepicker
		$row.find("label").replaceWith(`<input type="text" class="sp-datepicker" name="sp_statistics[${league}][${season}.${$rows.length}][date_from]" value="" size="10" placeholder="${date_from_string}">`);

		// Add added class to row
		$row.addClass("sp-row-added");

		// Insert new row after original
		$row.insertAfter($rows.last());

		// Activate datepicker
		$(".sp-datepicker").datepicker({
			dateFormat : "yy-mm-dd"
		});
		return false;
	});
	
	// Delete added row
	$(".sp-player-statistics-table").on("click", ".sp-delete-row", function() {
		$self = $(this);
		$self.closest("tr").css("background-color", "#f99").fadeOut(400, function() {
			$(this).remove();
		});
		return false;
	});
});