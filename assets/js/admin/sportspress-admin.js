jQuery(document).ready(function($){

	// Tiptip
	$(".sp-tip").tipTip({
		delay: 200,
		fadeIn: 100,
		fadeOut: 100
	});
	$(".sp-desc-tip").tipTip({
		delay: 200,
		fadeIn: 100,
		fadeOut: 100,
		defaultPosition: 'right'
	});

	// Chosen select
	$(".chosen-select, #poststuff #post_author_override").chosen({
		allow_single_deselect: true,
		search_contains: true,
		single_backstroke_delete: false,
		disable_search_threshold: 10,
		placeholder_text_multiple: localized_strings.none
	});

	// Auto key placeholder
	$("#poststuff #title").on("keyup", function() {
		val = $(this).val()
		lc = val.replace(/[^a-z]/gi,"").toLowerCase();
		$("#sp_key").attr("placeholder", lc);
		$("#sp_default_key").val(lc);
		$("#sp_singular").attr("placeholder", val);
	});

	// Activate auto key placeholder
	$("#poststuff #title").keyup();

	// Radio input toggle
	$(".sp-radio-toggle").click(function() {
		if($(this).data("sp-checked")) {
			$(this).attr("checked", false );
			$(this).data("sp-checked", false );
		} else {
			$(this).data("sp-checked", true );
		}
	});

	// Table switcher
	$(".sp-table-panel").siblings(".sp-table-bar").find("a").click(function() {
		$(this).closest("li").find("a").addClass("current").closest("li").siblings().find("a").removeClass("current").closest(".sp-table-bar").siblings($(this).attr("href")).show().siblings(".sp-table-panel").hide();
		return false;
	});

	// Tab switcher
	$(".sp-tab-panel").siblings(".sp-tab-bar").find("a").click(function() {
		$(this).closest("li").addClass("tabs").siblings().removeClass("tabs").closest(".sp-tab-bar").siblings($(this).attr("href")).show().trigger('checkCheck').siblings(".sp-tab-panel").hide();
		return false;
	});

	// Tab filter
	$(".sp-tab-filter-panel").siblings(".sp-tab-select").find("select").change(function() {
		var val = $(this).val();
		var filter = ".sp-filter-"+val;
		var $filters = $(this).closest(".sp-tab-select").siblings(".sp-tab-select");
		if($filters.length) {
			$filters.each(function() {
				filterval = $(this).find("select").val();
				if(filterval !== undefined)
					filter += ".sp-filter-"+filterval;
			});
		}
		$panel = $(this).closest(".sp-tab-select").siblings(".sp-tab-panel");
		$panel.each(function() {
			$(this).find(".sp-post").hide(0, function() {
				$(this).find("input").prop("disabled", true);
				$(this).filter(filter).show(0, function() {
					$(this).find("input").prop("disabled", false);
				});
			});
			if($(this).find(".sp-post:visible").length > 0) {
				$(this).find(".sp-select-all-container").show();
				$(this).find(".sp-show-all-container").show();
				$(this).find(".sp-not-found-container").hide();
			} else {
				$(this).find(".sp-select-all-container").hide();
				$(this).find(".sp-show-all-container").hide();
				$(this).find(".sp-not-found-container").show();
			}
		});
	});

	// Trigger tab filter
	$(".sp-tab-filter-panel").siblings(".sp-tab-select").find("select").change();

	// Dropdown filter
	$(".sp-dropdown-target").siblings(".sp-dropdown-filter").find("select").change(function() {
		var val = $(this).val();
		var filter = ".sp-filter-"+val;
		var $filters = $(this).closest(".sp-dropdown-filter").siblings(".sp-dropdown-filter");
		if($filters.length) {
			$filters.each(function() {
				filterval = $(this).find("select").val();
				if(filterval !== undefined)
					filter += ".sp-filter-"+filterval;
			});
		}
		$target = $(this).closest(".sp-dropdown-filter").siblings(".sp-dropdown-target").find("select");
		$target.find(".sp-post").prop("disabled", true).each(function() {
			$(this).filter(filter).prop("disabled", false);
		});
	});

	// Trigger dropdown filter
	$(".sp-dropdown-target").siblings(".sp-dropdown-filter").find("select").change();

	// Filter show all action links
	$(".sp-tab-panel").find(".sp-post input:checked").each(function() {
		$(this).prop("disabled", false).closest("li").show().siblings(".sp-not-found-container").hide().siblings(".sp-show-all-container").show();
	});

	// Show all filter
	$(".sp-tab-panel").on("click", ".sp-show-all", function() {
		$(this).closest("li").hide().siblings(".sp-post, .sp-select-all-container").show().find("input").prop("disabled", false);
	});

	// Self-cloning
	$(".sp-clone:last").find("select").change(function() {
		$(this).closest(".sp-clone").siblings().find("select").change(function() {
			if($(this).val() == "0") $(this).closest(".sp-clone").remove();
		}).find("option:first").text(localized_strings.remove_text);
		if($(this).val() != "0") {
			$original = $(this).closest(".sp-clone");
			$original.before($original.clone().find("select").attr("name", $original.attr("data-clone-name") + "[]").val($(this).val()).closest(".sp-clone")).attr("data-clone-num", parseInt($original.attr("data-clone-num")) + 1).find("select").val("0").change();
		}
	});

	// Activate self-cloning
	$(".sp-clone:last").find("select").change();

	// Custom value editor
	$(".sp-data-table .sp-default-value").click(function() {
		$(this).hide().siblings(".sp-custom-value").show().find(".sp-custom-value-input").focus();
	});

	// Define custom value editor saving
	$(".sp-data-table .sp-custom-value .sp-custom-value-input").on("saveInput", function() {
		$val = $(this).val();
		if($val == "") $val = $(this).attr("placeholder");
		$(this).closest(".sp-custom-value").hide().siblings(".sp-default-value").show().find(".sp-default-value-input").html($val);
	});

	// Define custom value editor cancellation
	$(".sp-data-table .sp-custom-value .sp-custom-value-input").on("cancelInput", function() {
		$val = $(this).closest(".sp-custom-value").siblings(".sp-default-value").find(".sp-default-value-input").html();
		if($val == $(this).attr("placeholder")) $(this).val("");
		else $(this).val($val);
		$(this).closest(".sp-custom-value").hide().siblings(".sp-default-value").show();
	});

	// Custom value editor save
	$(".sp-data-table .sp-custom-value .sp-save").click(function() {
		$(this).siblings(".sp-custom-value-input").trigger("saveInput");
	});

	// Custom value editor cancel
	$(".sp-data-table .sp-custom-value .sp-cancel").click(function() {
		$(this).siblings(".sp-custom-value-input").trigger("cancelInput");
	});

	// Prevent custom value editor input from submitting form
	$(".sp-data-table .sp-custom-value .sp-custom-value-input").keypress(function(event) {
		if(event.keyCode == 13){
			event.preventDefault();
			$(this).trigger("saveInput");
			return false;
		}
	});

	// Cancel custom value editor form on escape
	$(".sp-data-table .sp-custom-value .sp-custom-value-input").keyup(function(event) {
		if(event.keyCode == 27){
			event.preventDefault();
			$(this).trigger("cancelInput");
			return false;
		}
	});

	// Data table adjustments
	$(".sp-table-adjustments input").change(function() {
		matrix = $(this).attr("data-matrix");
		$el = $(this).closest(".sp-table-adjustments").siblings(".sp-table-values").find("input[data-matrix="+matrix+"]");
		placeholder = $el.attr("data-placeholder");
		current_adjustment = parseFloat($el.attr("data-adjustment"));
		adjustment = parseFloat($(this).val());
		if(! isNaN(adjustment) && adjustment != 0) {
			placeholder = parseFloat(placeholder);
			if(isNaN(placeholder)) placeholder = 0;
			if(isNaN(current_adjustment)) current_adjustment = 0;
			placeholder += adjustment - current_adjustment;
		}
		$el.attr("placeholder", placeholder);
	}).change();

	// Data table keyboard navigation
	$(".sp-data-table tbody tr td input:text").keydown(function(event) {
		if(! $(this).parent().hasClass("chosen-search") && [37,38,39,40].indexOf(event.keyCode) > -1){
			$el = $(this).closest("td");
			var col = $el.parent().children().index($el)+1;
			var row = $el.parent().parent().children().index($el.parent())+1;
			if(event.keyCode == 37){
				if ( $(this).caret().start != 0 )
					return true;
				col -= 1;
			}
			if(event.keyCode == 38){
				row -= 1;
			}
			if(event.keyCode == 39){
				if ( $(this).caret().start != $(this).val().length )
					return true;
				col += 1;
			}
			if(event.keyCode == 40){
				row += 1;
			}
			$el.closest("tbody").find("tr:nth-child("+row+") td:nth-child("+col+") input:text").first().focus();
		}
	});

	// Prevent data table from submitting form
	$(".sp-data-table tbody tr td input:text").keypress(function(event) {
		if(! $(this).parent().hasClass("chosen-search") && event.keyCode == 13){
			event.preventDefault();
			$el = $(this).closest("td");
			var col = $el.parent().children().index($el)+1;
			var row = $el.parent().parent().children().index($el.parent())+2;
			$el.closest("tbody").find("tr:nth-child("+row+") td:nth-child("+col+") input:text").focus();
			return false;
		}
	});

	// Total stats calculator
	$(".sp-data-table .sp-total input[data-sp-format=number]").on("updateTotal", function() {
		index = $(this).parent().index();
		var sum = 0;
		$(this).closest(".sp-data-table").find(".sp-post").each(function() {
			val = $(this).find("td").eq(index).find("input").val();
			if(val == "") {
				val = $(this).find("td").eq(index).find("input").attr("placeholder");
			}
			if($.isNumeric(val)) {
				sum += parseInt(val, 10);
			}
		});
		$(this).attr("placeholder", sum);
	});

	// Activate total stats calculator
	if($(".sp-data-table .sp-total").size()) {
		$(".sp-data-table .sp-post td input").on("keyup", function() {
			$(this).closest(".sp-data-table").find(".sp-total td").eq($(this).parent().index()).find("input").trigger("updateTotal");
		});
	}

	// Trigger total stats calculator
	$(".sp-data-table .sp-total input").trigger("updateTotal");

	// Sync inputs
	$(".sp-sync-input").on("keyup", function() {
		name = $(this).attr("name");
		$el = $("input[name='"+name+"']");
		if ( $el.length > 1 ) {
			val = $(this).val();
			$el.val(val);
		}
	});

	// Sync selects
	$(".sp-sync-select").on("change", function() {
		name = $(this).attr("name");
		$el = $("select[name='"+name+"']")
		if ( $el.length > 1 ) {
			val = $(this).val();
			$el.val(val);
		}
	});

	// Select all checkboxes
	$(".sp-select-all-range").on("change", ".sp-select-all", function() {
		$range = $(this).closest(".sp-select-all-range");
		$range.find("input[type=checkbox]").prop("checked", $(this).prop("checked"));
	});

	// Check if all checkboxes are checked already
	$(".sp-select-all-range").on("checkCheck", function() {
		$(this).each(function() {
			$(this).find(".sp-select-all").prop("checked", $(this).find("input[type=checkbox]:checked:not(.sp-select-all)").length != 0 && $(this).find("input[type=checkbox]:checked:not(.sp-select-all)").length == $(this).find("input[type=checkbox]:visible:not(.sp-select-all)").length);
		});
	});

	// Activate check check when a checkbox is checked
	$(".sp-select-all-range input[type=checkbox]:not(.sp-select-all)").change(function() {
		$(this).closest(".sp-select-all-range").trigger("checkCheck");
	});

	// Activate check check on page load
	$(".sp-select-all-range").trigger("checkCheck");

	// Trigger check check
	$(".sp-data-table").trigger("checkCheck");

	// Sortable tables
	$(".sp-sortable-table tbody").sortable({
		handle: ".icon",
		axis: "y"
	});
	
	// Sortable lists
    $( ".sp-sortable-list" ).sortable({
    	handle: ".sp-item-handle",
		placeholder: "sp-item-placeholder",
		connectWith: ".sp-connected-list"
    });

	// Autosave
	$(".sp-autosave").change(function() {
		$(this).attr("readonly", true).closest("form").submit();
	});

	// Video embed
	$(".sp-add-video").click(function() {
		$(this).closest("fieldset").hide().siblings(".sp-video-field").show();
		return false;
	});

	// Removing video embed
	$(".sp-remove-video").click(function() {
		$(this).closest("fieldset").hide().siblings(".sp-video-adder").show().siblings(".sp-video-field").find("input").val(null);
		return false;
	});

	// Equation selector
	$(".sp-equation-selector select:last").change(function() {
		$(this).siblings().change(function() {
			if($(this).val() == "") $(this).remove();
		}).find("option:first").text(localized_strings.remove_text);
		if($(this).val() != "") {
			$(this).before($(this).clone().val($(this).val())).val("").change();
		}
	});

	// Trigger equation selector
	$(".sp-equation-selector select:last").change().siblings().change();

	// Order selector
	$(".sp-order-selector select:first").change(function() {
		if($(this).val() == "0") {
			$(this).siblings().prop( "disabled", true );
		} else {
			$(this).siblings().prop( "disabled", false )
		}
	});

	// Trigger order selector
	$(".sp-order-selector select:first").change();

	// Format selector
	$(".sp-format-selector select:first").change(function() {

		$precisiondiv = $("#sp_precisiondiv");
		$precisioninput = $("#sp_precision");
		$timeddiv = $("#sp_timeddiv");
		$equationdiv = $("#sp_equationdiv");

		// Equation settings
		if ($(this).val() == "equation") {
			$equationdiv.show();
			$precisiondiv.show();
			$timeddiv.hide();
			$precisioninput.prop( "disabled", false );
		} else if ($(this).val() == "number") {
			$equationdiv.hide();
			$precisiondiv.hide();
			$timeddiv.show();
			$precisioninput.prop( "disabled", true );
		} else {
			$equationdiv.hide();
			$precisiondiv.hide();
			$timeddiv.hide();
			$precisioninput.prop( "disabled", true );
		}

	});

	// Trigger format selector
	$(".sp-format-selector select:first").change();

	// Team era selector
	$(".sp-team-era-selector select:first-child").change(function() {

		$subselector = $(this).siblings();

		// Sub settings
		if($(this).val() == 0) {
			$subselector.hide();
		} else {
			$subselector.show();
		}

	});

	// Trigger team era selector
	$(".sp-team-era-selector select:first-child").change();

	// Status selector
	$(".sp-status-selector select:first-child").change(function() {

		$subselector = $(this).siblings();

		// Sub settings
		if($(this).val() == "sub") {
			$subselector.show();
		} else {
			$subselector.hide();
		}

	});

	// Trigger status selector
	$(".sp-status-selector select:first-child").change();

	// Preset field modifier
	$(".sp-custom-input-wrapper .preset").click(function() {
		val = $(this).val();
		if(val == "\\c\\u\\s\\t\\o\\m") return true;
		example = $(this).attr("data-example");
		$(this).closest(".sp-custom-input-wrapper").find(".value").val(val).siblings(".example").html(example);
	});

	// Select custom preset when field is brought to focus
	$(".sp-custom-input-wrapper .value").focus(function() {
		$(this).siblings("label").find(".preset").prop("checked", true);
	});

	// Adjust example field when custom preset is entered
	$(".sp-custom-input-wrapper .value").on("keyup", function() {
		val = $(this).val();
		if ( val === undefined ) return true;
		format = $(this).attr("data-example-format");
		example = format.replace("__val__", val);
		$(this).siblings(".example").html(example);
	});

	// Prevent address input from submitting form
	$(".sp-address").keypress(function(event) {
		return event.keyCode != 13;
	});

	// Dashboard countdown
	$("#sportspress_dashboard_status .sp_status_list li.countdown").each(function() {
		var $this = $(this), finalDate = $(this).data('countdown');
		$this.countdown(finalDate, function(event) {
			$this.find('strong').html(event.strftime("%D "+localized_strings.days+" %H:%M:%S"));
		});
	});

	// Event format affects data
	$(".post-type-sp_event #post-formats-select input.post-format").change(function() {
		layout = $(".post-type-sp_event #post-formats-select input:checked").val();
		if ( layout == "friendly" ) {
			$(".sp_event-sp_league-field").hide().find("select").prop("disabled", true);
			$(".sp_event-sp_season-field").show().find("select").prop("disabled", false);
		} else {
			$(".sp_event-sp_league-field").show().find("select").prop("disabled", false);
			$(".sp_event-sp_season-field").show().find("select").prop("disabled", false);
		}
	});

	// Trigger event format change
	$(".post-type-sp_event #post-formats-select input.post-format").trigger("change");

	// Calendar layout affects data
	$(".post-type-sp_calendar #post-formats-select input.post-format").change(function() {
		layout = $(".post-type-sp_calendar #post-formats-select input:checked").val();
		$(".sp-calendar-table tr").each(function() {
			if ( layout == "list" ) {
				$(this).find("th input[type=checkbox]").show();
				$(this).find("th select").prop("disabled", false);
			} else {
				$(this).find("th input[type=checkbox]").hide();
				$(this).find("th select").prop('selectedIndex', 0).prop("disabled", true);
			}
		});
	});

	// Trigger calendar layout change
	$(".post-type-sp_calendar #post-formats-select input.post-format").trigger("change");

	// Player list layout affects data
	$(".post-type-sp_list #post-formats-select input.post-format").change(function() {
		layout = $(".post-type-sp_list #post-formats-select input:checked").val();
		$(".sp-player-list-table tr").each(function() {
			if ( layout == "list" ) {
				$(this).find("th input[type=checkbox]").show();
			} else {
				$(this).find("th input[type=checkbox]").hide();
			}
		});
	});

	// Trigger player list layout change
	$(".post-type-sp_list #post-formats-select input.post-format").trigger("change");

	// Configure primary result option (Ajax)
	$(".sp-admin-config-table").on("click", ".sp-primary-result-option", function() {
		$.post( ajaxurl, {
			action:         "sp-save-primary-result",
			primary_result: $(this).val(),
			nonce:          $("#sp-primary-result-nonce").val()
		});
	});

	// Configure primary performance option (Ajax)
	$(".sp-admin-config-table").on("click", ".sp-primary-performance-option", function() {
		$.post( ajaxurl, {
			action:              "sp-save-primary-performance",
			primary_performance: $(this).val(),
			nonce:               $("#sp-primary-performance-nonce").val()
		});
	});

	// Update importer post count
	$(".sp-import-table").on("updatePostCount", function() {
		$(".sp-post-count").text(localized_strings.displaying_posts.replace("%s", 1).replace(/%s/g, count = $(this).find("tbody tr").length));
	});

	// Delete importer row
	$(".sp-import-table").on("click", ".sp-delete-row", function() {
		$self = $(this);
		$self.closest("tr").css("background-color", "#f99").fadeOut(400, function() {
			$table = $self.closest(".sp-import-table");
			$(this).remove();
			$table.trigger("updatePostCount");
		});
		return false;
	});

	// Add importer row
	$(".sp-import-table").on("click", ".sp-add-row", function() {
		$self = $(this);
		$table = $self.closest(".sp-import-table");
		if ( $self.hasClass("sp-add-first") ) {
			$tr = $table.find("tbody tr:first-child");
			$row = $tr.clone();
			$row.insertBefore($tr).find("input").val("");
		} else {
			$tr = $self.closest("tr");
			$row = $tr.clone();
			$tr.find("input").val("");
			$row.insertBefore($tr);
		}
		$table.trigger("updatePostCount");
		return false;
	});

	// Enable or disable importer inputs based on column label
	$(".sp-import-table").on("change", "select", function() {
		$self = $(this);
		$table = $self.closest(".sp-import-table");
		index = parseInt($self.data("index"));
		if ( $self.val() == 0 ) {
			$table.find("tbody tr td:nth-child("+parseInt(index+1)+") input").prop("disabled", true);
		} else {
			$table.find("tbody tr td:nth-child("+parseInt(index+1)+") input").prop("disabled", false);
			$self.closest("th").siblings().find("select").each(function() {
				if ( $(this).val() == $self.val() ) $(this).val("0").trigger("change");
			});
		}
	});

	// Datepicker
	$(".sp-datepicker").datepicker({
		dateFormat : "yy-mm-dd"
	});
	$(".sp-datepicker-from").datepicker({
		dateFormat : "yy-mm-dd",
		onClose: function( selectedDate ) {
			$(this).closest(".sp-date-selector").find(".sp-datepicker-to").datepicker("option", "minDate", selectedDate);
		}
	});
	$(".sp-datepicker-to").datepicker({
		dateFormat : "yy-mm-dd",
		onClose: function( selectedDate ) {
			$(this).closest(".sp-date-selector").find(".sp-datepicker-from").datepicker("option", "maxDate", selectedDate);
		}
	});

	// Show or hide datepicker
	$(".sp-date-selector select").change(function() {
		if ( $(this).val() == "range" ) {
			$(this).closest(".sp-date-selector").find(".sp-date-range").show();
		} else {
			$(this).closest(".sp-date-selector").find(".sp-date-range").hide();
		}
	});
	$(".sp-date-selector select").trigger("change");

	// Apply color scheme
	$(".sp-color-option").on("click", function() {
		colors = $(this).find("label").data("sp-colors").split(",");
		$(".sp-custom-colors").find(".sp-color-box").each(function(index) {
			$(this).find("input").val("#"+colors[index]).css("background-color", "#"+colors[index]);
		});;
	});

	// Edit inline results
	$("#the-list").on("click, focus", ".sp-result, .sp-edit-results", function(){
		team = $(this).data("team");
		$column = $(this).closest(".column-sp_team");
		$column.find(".sp-result, .sp-row-actions").hide();
		$column.find(".sp-edit-result, .sp-inline-edit-save").show();
		if ( team != undefined ) {
			$column.find(".sp-edit-result[data-team='"+team+"']").select();
		}
		return false;
	});

	// Cancel inline results
	$("#the-list").on("click", ".sp-inline-edit-save .cancel", function(){
		$column = $(this).closest(".column-sp_team");
		$column.find(".sp-edit-result, .sp-inline-edit-save").hide();
		$column.find(".sp-result, .sp-row-actions").show();
		return false;
	});

	// Save inline results
	$("#the-list").on("click", ".sp-inline-edit-save .save", function(){
		$column = $(this).closest(".column-sp_team");
		results = {};
		$column.find(".sp-edit-result").each(function() {
			id = $(this).data("team");
			result = $(this).val();
			results[id] = result;
		});
		$.post( ajaxurl, {
			action:         "sp-save-inline-results",
			post_id: 		$column.find("input[name='sp_post_id']").val(),
			results: 		results,
			nonce:          $("#sp-inline-nonce").val()
		}, function(response) {
			$column.find(".sp-edit-result").each(function() {
				val = $(this).val();
				$column.find(".sp-result[data-team='"+$(this).data("team")+"']").html(val==''?'-':val);
			});
			$column.find(".sp-edit-result, .sp-inline-edit-save").hide();
			$column.find(".sp-result, .sp-row-actions").show();
			return false;
		});
	});

	// Override inline form submission
	$("#the-list").on("keypress", ".sp-edit-result", function(e) {
		if ( e.which == 13 ) {
			$(this).closest(".column-sp_team").find(".sp-inline-edit-save .save").trigger("click");
			return false;
		}
	});

	// Fitvids
	$(".sp-fitvids").fitVids();

	// Display configure sport button
	$(".sp-select-sport").change(function() {
		$(".sp-configure-sport").hide();
	});

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
				i = 0;
				if(-1 == response.data.sections) {
					if(response.data.players.length) {
						$target.eq(0).append("<li class=\"sp-select-all-container\"><label class=\"selectit\"><input type=\"checkbox\" class=\"sp-select-all\"><strong>" + localized_strings.select_all + "</strong></li>");
						$(response.data.players).each(function( key, value ) {
							$target.eq(0).append("<li><label class=\"selectit\"><input type=\"checkbox\" value=\"" + value.ID + "\" name=\"sp_player[" + index + "][]\">" + value.post_title + "</li>");
						});
						$target.eq(0).append("<li class=\"sp-ajax-show-all-container\"><a class=\"sp-ajax-show-all\" href=\"#show-all-sp_players\">" + localized_strings.show_all + "</a></li>");
					} else {
						$target.eq(0).html("<li>" + localized_strings.no_results_found + " <a class=\"sp-ajax-show-all\" href=\"#show-all-sp_players\">" + localized_strings.show_all + "</a></li>");
					}
				} else {
					if ( 1 == response.data.sections ) {
						defense = i;
						offense = i+1;
					} else {
						offense = i;
						defense = i+1;
					}
					if(response.data.players.length) {
						$target.eq(offense).append("<li class=\"sp-select-all-container\"><label class=\"selectit\"><input type=\"checkbox\" class=\"sp-select-all\"><strong>" + localized_strings.select_all + "</strong></li>");
						$target.eq(defense).append("<li class=\"sp-select-all-container\"><label class=\"selectit\"><input type=\"checkbox\" class=\"sp-select-all\"><strong>" + localized_strings.select_all + "</strong></li>");
						$(response.data.players).each(function( key, value ) {
							$target.eq(offense).append("<li><label class=\"selectit\"><input type=\"checkbox\" value=\"" + value.ID + "\" name=\"sp_offense[" + index + "][]\">" + value.post_title + "</li>");
							$target.eq(defense).append("<li><label class=\"selectit\"><input type=\"checkbox\" value=\"" + value.ID + "\" name=\"sp_defense[" + index + "][]\">" + value.post_title + "</li>");
						});
						$target.eq(offense).append("<li class=\"sp-ajax-show-all-container\"><a class=\"sp-ajax-show-all\" href=\"#show-all-sp_offense\">" + localized_strings.show_all + "</a></li>");
						$target.eq(defense).append("<li class=\"sp-ajax-show-all-container\"><a class=\"sp-ajax-show-all\" href=\"#show-all-sp_defense\">" + localized_strings.show_all + "</a></li>");
					} else {
						$target.eq(offense).html("<li>" + localized_strings.no_results_found + " <a class=\"sp-ajax-show-all\" href=\"#show-all-sp_offense\">" + localized_strings.show_all + "</a></li>");
						$target.eq(defense).html("<li>" + localized_strings.no_results_found + " <a class=\"sp-ajax-show-all\" href=\"#show-all-sp_defense\">" + localized_strings.show_all + "</a></li>");
					}
					i++;
				}
				i++;
				if(response.data.staff.length) {
					$target.eq(i).append("<li class=\"sp-select-all-container\"><label class=\"selectit\"><input type=\"checkbox\" class=\"sp-select-all\"><strong>" + localized_strings.select_all + "</strong></li>");
					$(response.data.staff).each(function( key, value ) {
						$target.eq(i).append("<li><label class=\"selectit\"><input type=\"checkbox\" value=\"" + value.ID + "\" name=\"sp_staff[" + index + "][]\">" + value.post_title + "</li>");
					});
					$target.eq(i).append("<li class=\"sp-ajax-show-all-container\"><a class=\"sp-ajax-show-all\" href=\"#show-all-sp_staffs\">" + localized_strings.show_all + "</a></li>");
				} else {
					$target.eq(i).html("<li>" + localized_strings.no_results_found + " <a class=\"sp-ajax-show-all\" href=\"#show-all-sp_staffs\">" + localized_strings.show_all + "</a></li>");
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
				i = 0;
				console.log(response.data.sections);
				if ( -1 == response.data.sections ) {
					if(response.data.players.length) {
						$(response.data.players).each(function( key, value ) {
							if($target.eq(i).find("input[value=" + value.ID + "]").length) return true;
							$target.eq(i).append("<li><label class=\"selectit\"><input type=\"checkbox\" value=\"" + value.ID + "\" name=\"sp_player[" + index + "][]\"> " + value.post_title + "</li>");
						});
					} else {
						$target.eq(i).html("<li>" + localized_strings.no_results_found + "</li>");
					}
				} else {
					if(response.data.players.length) {
						if ( 1 == response.data.sections ) {
							defense = i;
							offense = i+1;
						} else {
							offense = i;
							defense = i+1;
						}
						$(response.data.players).each(function( key, value ) {
							$target.eq(offense).append("<li><label class=\"selectit\"><input type=\"checkbox\" value=\"" + value.ID + "\" name=\"sp_offense[" + index + "][]\"> " + value.post_title + "</li>");
							$target.eq(defense).append("<li><label class=\"selectit\"><input type=\"checkbox\" value=\"" + value.ID + "\" name=\"sp_defense[" + index + "][]\"> " + value.post_title + "</li>");
						});
					} else {
						$target.eq(offense).html("<li>" + localized_strings.no_results_found + "</li>");
						$target.eq(defense).html("<li>" + localized_strings.no_results_found + "</li>");
					}
					i++;
				}
				i++;
				if(response.data.staff.length) {
					$(response.data.staff).each(function( key, value ) {
						$target.eq(i).append("<li><label class=\"selectit\"><input type=\"checkbox\" value=\"" + value.ID + "\" name=\"sp_staff[" + index + "][]\"> " + value.post_title + "</li>");
					});
				} else {
					$target.eq(i).html("<li>" + localized_strings.no_results_found + "</li>");
				}
			} else {
				$target.html("<li>" + localized_strings.no_results_found + "</li>");
			}
		});
	});

	// Event status selector
	$('.sp-edit-event-status').click(function(e) {
		e.preventDefault();
		$select = $(this).siblings('.sp-event-status-select');
		if ( $select.is(':hidden') ) {
			$select.slideDown( 'fast', function() {
				$select.find( 'input[type="radio"]' ).first().focus();
			} );
			$(this).hide();
		}
	});

	$('.sp-save-event-status').click(function(e) {
		e.preventDefault();
		$select = $(this).closest('.sp-event-status-select');
		$input = $select.find('input[name=sp_status]:checked');
		val = $input.val();
		label = $input.data('sp-event-status');
		$select.slideUp('fast').siblings('.sp-edit-event-status').show().siblings('.sp-event-status').find('.sp-event-status-display').data('sp-event-status', val).html(label);
	});

	$('.sp-cancel-event-status').click(function(e) {
		e.preventDefault();
		$select = $(this).closest('.sp-event-status-select');
		val = $select.siblings('.sp-event-status').find('.sp-event-status-display').data('sp-event-status');
		$select.find('input[value='+val+']').attr('checked', true);
		$select.slideUp('fast').siblings('.sp-edit-event-status').show();
	});

	// Box score time converter
	$('.sp-convert-time-input').change(function() {
		var s = 0;
		var val = $(this).val();
		if (val === '') {
			$(this).siblings('.sp-convert-time-output').val('');
			return;
		}
		var a = val.split(':').reverse();
		$.each(a, function( index, value ) {
			s += parseInt(value) * Math.pow(60, index);
		});
		$(this).siblings('.sp-convert-time-output').val(s);
	});

	// Trigger box score time converter
	$('.sp-convert-time-input').change();
});