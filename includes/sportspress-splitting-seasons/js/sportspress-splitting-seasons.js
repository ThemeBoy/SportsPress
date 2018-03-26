jQuery(document).ready(function($){
	// Split Statistic row
	$(".sp-data-table").on("click", ".sp-add-row", function() {
		var league_id = $(this).data('league_id');
		var season_id = $(this).data('season_id');
		$self = $(this);
		$table = $self.closest(".sp-data-table");
			$tr = $self.closest("tr");
			$row = $table.find(".empty-row.screen-reader-text").clone();
			$row.addClass("splitted-row");
			$row.removeClass("empty-row screen-reader-text");
			//enable jquery date
			$row.find(".date").addClass("sp-datepicker3");
			//add the required parameter
			$row.find(".date").prop('required',true);;
			//hide add sign
			$row.closest("tr").find(".sp-add-row").css( "display", "none" );
			//display delete sign
			$row.closest("tr").find(".sp-delete-row").css( "display", "block" );
			//add league_id and season_id variables
			$row.closest("tr").find('#leagueHidden').val(league_id);
			$row.closest("tr").find('#seasonHidden').val(season_id);
			//add the new raw first
			$row.insertAfter($tr);
			//change the team_id on changing
			$('select[id=additional_team]').on('change', function() {
				$(this).closest("tr").find('#teamHidden').val( this.value );
			});
			$(".sp-datepicker3").datepicker({
				dateFormat : "yy-mm-dd"
			});
		return false;
	});
	
	// Delete Splitted row
	$(".sp-data-table").on("click", ".sp-delete-row", function() {
		$self = $(this);
		$self.closest("tr").css("background-color", "#f99").fadeOut(400, function() {
			$table = $self.closest(".sp-data-table");
			$(this).remove();
			//$table.trigger("updatePostCount");
		});
		return false;
	});
});