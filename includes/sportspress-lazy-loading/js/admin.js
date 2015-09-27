(function($) {

	// Ajax checklist
	$(".sp-ajax-checklist").siblings(".sp-tab-select").find("select").change(function() {
		$(this).closest(".sp-tab-select").siblings(".sp-ajax-checklist").find("ul").html("<li>" + localized_strings.loading + "</li>");
		$.post( ajaxurl, {
			action:         "sp-get-players",
			team: 			$(this).val(),
			league: 		('yes' == localized_strings.option_filter_by_league) ? $("select[name=\"tax_input[sp_league][]\"]").val() : null,
			season: 		('yes' == localized_strings.option_filter_by_season) ? $("select[name=\"tax_input[sp_season][]\"]").val() : null,
			index: 			$(this).closest(".sp-instance").index(),
			nonce:          $("#sp-get-players-nonce").val()
		}).done(function( response ) {
			index = response.data.index;
			$target = $(".sp-instance").eq(index).find(".sp-ajax-checklist ul");
			if ( response.success ) {
				$target.html("");
				if(response.data.players.length) {
					$target.eq(0).append("<li class=\"sp-select-all-container\"><label class=\"selectit\"><input type=\"checkbox\" class=\"sp-select-all\"><strong>" + localized_strings.select_all + "</strong></li>");
					$(response.data.players).each(function( key, value ) {
						$target.eq(0).append("<li><label class=\"selectit\"><input type=\"checkbox\" value=\"" + value.ID + "\" name=\"sp_player[" + index + "][]\">" + value.post_title + "</li>");
					});
					$target.eq(0).append("<li class=\"sp-ajax-show-all-container\"><a class=\"sp-ajax-show-all\" href=\"#show-all-sp_players\">" + localized_strings.show_all + "</a></li>");
				} else {
					$target.eq(0).html("<li>" + localized_strings.no_results_found + " <a class=\"sp-ajax-show-all\" href=\"#show-all-sp_players\">" + localized_strings.show_all + "</a></li>");
				}
				if(response.data.staff.length) {
					$target.eq(1).append("<li class=\"sp-select-all-container\"><label class=\"selectit\"><input type=\"checkbox\" class=\"sp-select-all\"><strong>" + localized_strings.select_all + "</strong></li>");
					$(response.data.staff).each(function( key, value ) {
						$target.eq(1).append("<li><label class=\"selectit\"><input type=\"checkbox\" value=\"" + value.ID + "\" name=\"sp_staff[" + index + "][]\">" + value.post_title + "</li>");
					});
					$target.eq(1).append("<li class=\"sp-ajax-show-all-container\"><a class=\"sp-ajax-show-all\" href=\"#show-all-sp_staffs\">" + localized_strings.show_all + "</a></li>");
				} else {
					$target.eq(1).html("<li>" + localized_strings.no_results_found + " <a class=\"sp-ajax-show-all\" href=\"#show-all-sp_staffs\">" + localized_strings.show_all + "</a></li>");
				}
			} else {
				$target.html("<li>" + localized_strings.no_results_found + "</li>");
			}
		});
	});

	// Activate Ajax trigger
	$(".sp-ajax-trigger").change(function() {
		$(".sp-ajax-checklist").siblings(".sp-tab-select").find("select").change();
	});

	// Ajax show all filter
	$(".sp-tab-panel").on("click", ".sp-ajax-show-all", function() {
		index = $(this).closest(".sp-instance").index();
		$(this).parent().html(localized_strings.loading);
		$.post( ajaxurl, {
			action:         "sp-get-players",
			index: 			index,
			nonce:          $("#sp-get-players-nonce").val()
		}).done(function( response ) {
			index = response.data.index;
			console.log(index);
			$target = $(".sp-instance").eq(index).find(".sp-ajax-checklist ul");
			$target.find(".sp-ajax-show-all-container").hide();
			if ( response.success ) {
				if(response.data.players.length) {
					$(response.data.players).each(function( key, value ) {
						if($target.find("input[value=" + value.ID + "]").length) return true;
						$target.eq(0).append("<li><label class=\"selectit\"><input type=\"checkbox\" value=\"" + value.ID + "\" name=\"sp_player[" + index + "][]\">" + value.post_title + "</li>");
					});
				} else {
					$target.eq(0).html("<li>" + localized_strings.no_results_found + "</li>");
				}
				if(response.data.staff.length) {
					$(response.data.staff).each(function( key, value ) {
						$target.eq(1).append("<li><label class=\"selectit\"><input type=\"checkbox\" value=\"" + value.ID + "\" name=\"sp_staff[" + index + "][]\">" + value.post_title + "</li>");
					});
				} else {
					$target.eq(1).html("<li>" + localized_strings.no_results_found + "</li>");
				}
			} else {
				$target.html("<li>" + localized_strings.no_results_found + "</li>");
			}
		});
	});
})(jQuery);