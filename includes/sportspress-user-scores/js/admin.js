jQuery(document).ready(function($){
	$(".sp-user-results-table").on("click", ".sp-row-actions a", function(event) {
		$row = $(this).closest(".sp-row");
		user_id = $row.data("sp-user");
		team_id = $row.data("sp-team");
		if ( "approve" == $(this).data("sp-action") ) {
			$row.find(".sp-row-stat").each(function() {
				value = $(this).data("sp-value");
				if ("" === value) return true;
				key = $(this).data("sp-key");
				$(".sp-results-table").find("[data-team="+team_id+"] .sp-team-"+key+"-input").val(value);
			});
			$target = $(".sp-results-table").find("[data-team="+team_id+"]");
			bg = $target.css("background-color");
			$target.css("background-color", "#CCEEBB").animate({
				"backgroundColor": bg
			}, 400);
			$row.css("background-color", "#CCEEBB");
		} else if ( "reject" == $(this).data("sp-action") ) {
			$row.css("background-color", "#FFEBE8");
		}
		$(this).after("<input type=\"hidden\" name=\"sp_user_results_remove["+user_id+"][]\" value=\""+team_id+"\">");
		$siblings = $row.siblings(".sp-row-unapproved");
		if (!$siblings.length) {
			$el = $(this).closest(".sp-user-results-user-container");
		} else {
			$el = $row.removeClass("sp-row-unapproved");
		}
		$el.fadeOut(function() {
			$(".sp-user-results-save").fadeIn();
		});
		event.preventDefault();
		return false;
	});

	$(".sp-user-scores-table").on("click", ".sp-row-actions a", function(event) {
		$row = $(this).closest(".sp-row");
		user_id = $row.data("sp-user");
		player_id = $row.data("sp-player");
		if ( "approve" == $(this).data("sp-action") ) {
			$row.find(".sp-row-stat").each(function() {
				value = $(this).data("sp-value");
				if ("" === value) return true;
				key = $(this).data("sp-key");
				$(".sp-performance-table").find("[data-player="+player_id+"] .sp-player-"+key+"-input").val(value);
			});
			$target = $(".sp-performance-table").find("[data-player="+player_id+"]");
			bg = $target.css("background-color");
			$target.css("background-color", "#CCEEBB").animate({
				"backgroundColor": bg
			}, 400);
			$row.css("background-color", "#CCEEBB");
		} else if ( "reject" == $(this).data("sp-action") ) {
			$row.css("background-color", "#FFEBE8");
		}
		$(this).after("<input type=\"hidden\" name=\"sp_user_scores_remove["+user_id+"][]\" value=\""+player_id+"\">");
		$siblings = $row.siblings(".sp-row-unapproved");
		if (!$siblings.length) {
			$el = $(this).closest(".sp-user-scores-user-container");
		} else {
			$el = $row.removeClass("sp-row-unapproved");
		}
		$el.fadeOut(function() {
			$(".sp-user-scores-save").fadeIn();
		});
		event.preventDefault();
		return false;
	});
});