jQuery(document).ready(function($){
	$(".sp-crowdsourcing-table").on("click", ".sp-row-actions a", function(event) {
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
		$(this).after("<input type=\"hidden\" name=\"sp_crowdsourcing_remove["+user_id+"][]\" value=\""+player_id+"\">");
		$siblings = $row.siblings(".sp-row-unapproved");
		if (!$siblings.length) {
			$el = $(this).closest(".sp-crowdsourcing-user-container");
		} else {
			$el = $row.removeClass("sp-row-unapproved");
		}
		$el.fadeOut(function() {
			$(".sp-crowdsourcing-save").fadeIn();
		});
		event.preventDefault();
		return false;
	});
});