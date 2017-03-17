jQuery(document).ready(function($){
	$(".sp-crowdsourcing-table").on("click", ".sp-row-actions a", function(event) {
		$row = $(this).closest(".sp-row");
		if ( "approve" == $(this).data("sp-action") ) {
			player_id = $row.data("sp-player");
			$row.find(".sp-row-stat").each(function() {
				key = $(this).data("sp-key");
				value = $(this).data("sp-value");

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
		$siblings = $row.siblings(".sp-row-unapproved");
		if (!$siblings.length) {
			$el = $(this).closest(".sp-crowdsourcing-user-container");
		} else {
			$el = $row.removeClass("sp-row-unapproved");
		}
		$el.fadeOut(function() {
			$(".sp-crowdsourcing-save").fadeIn();
			$(this).remove();
		});
		event.preventDefault();
		return false;
	});
});