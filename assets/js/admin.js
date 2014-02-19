jQuery(document).ready(function($){

	// Display custom sport name field as needed
	$("body.settings_page_sportspress #sportspress_sport").change(function() {
		$target = $("#sportspress_custom_sport_name");
		if ( $(this).val() == "custom" )
			$target.show();
		else
			$target.hide();
	});

	// Chosen select
	$(".chosen-select").chosen({
		allow_single_deselect: true,
		single_backstroke_delete: false
	});

	// Auto key placeholder
	$("#poststuff #title").on("keyup", function() {
		$("#sp_key").attr("placeholder", $(this).val().replace(/[^a-z]/gi,"").toLowerCase());
	});

	// Activate auto key placeholder
	$("#poststuff #title").keyup();

	// Orderby affects order select in widget options
	$("body.widgets-php").on("change", ".sp-select-orderby", function() {
		$(this).closest(".widget-content").find(".sp-select-order").prop("disabled", $(this).val() == "default");
	});

	// Tab switcher
	$(".sp-tab-panel").siblings(".sp-tab-bar").find("a").click(function() {
		$(this).closest("li").removeClass("wp-tab").addClass("wp-tab-active").siblings().removeClass("wp-tab-active").addClass("wp-tab").closest(".wp-tab-bar").siblings($(this).attr("href")).show().siblings(".wp-tab-panel").hide();
		return false;
	});

	// Tab filter
	$(".sp-tab-panel").siblings(".sp-tab-select").find("select").change(function() {
		var val = $(this).val();
		var filter = ".sp-filter-"+val;
		var $filters = $(this).closest(".sp-tab-select").siblings(".sp-tab-select");
		if($filters.length) {
			$filters.each(function() {
				filter += ".sp-filter-"+$(this).find("select").val();
			});
		}
		$(this).closest(".sp-tab-select").siblings(".sp-tab-panel").find(".sp-post").hide(0, function() {
			$(this).find("input").prop("disabled", true);
			$(this).filter(filter).show(0, function() {
				$(this).find("input").prop("disabled", false);
			});
		});
	});

	// Trigger tab filter
	$(".sp-tab-panel").siblings(".sp-tab-select").find("select").change();

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

	// Name editor
	$(".sp-data-table .sp-edit-name").click(function() {
		$(this).closest(".sp-default-name").hide().siblings(".sp-custom-name").show().find(".sp-custom-name-input").focus();
	});

	// Name editor save
	$(".sp-data-table .sp-custom-name .sp-save").click(function() {
		$val = $(this).siblings(".sp-custom-name-input").val();
		if($val == "") $val = $(this).siblings(".sp-custom-name-input").attr("placeholder");
		$(this).closest(".sp-custom-name").hide().siblings(".sp-default-name").show().find(".sp-default-name-input").html($val);
	});

	// Name editor cancel
	$(".sp-data-table .sp-custom-name .sp-cancel").click(function() {
		$(this).closest(".sp-custom-name").hide().siblings(".sp-default-name").show();
	});

	// Prevent name editor input from submitting form
	$(".sp-data-table .sp-custom-name .sp-custom-name-input").keypress(function(event) {
		if(event.keyCode == 13){
			event.preventDefault();
			$(this).siblings(".sp-save").click();
			return false;
		}
	});

	// Cancel name editor form on escape
	$(".sp-data-table .sp-custom-name .sp-custom-name-input").keyup(function(event) {
		if(event.keyCode == 27){
			event.preventDefault();
			$(this).siblings(".sp-cancel").click();
			return false;
		}
	});

	// Total stats calculator
	$(".sp-data-table .sp-total input").on("updateTotal", function() {
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
		$(this).val(sum);
	});

	// Activate total stats calculator
	if($(".sp-data-table .sp-total").size()) {
		$(".sp-data-table .sp-post td input").on("keyup", function() {
			$(this).closest(".sp-data-table").find(".sp-total td").eq($(this).parent().index()).find("input").trigger("updateTotal");
		});
	}

	// Select all checkboxes
	$(".sp-data-table thead .sp-select-all").change(function() {
		$table = $(this).closest(".sp-data-table");
		$table.find("tbody input[type=checkbox]").prop("checked", $(this).prop("checked"));
	});

	// Check if all checkboxes are checked already
	$(".sp-data-table").on("checkCheck", function() {
		$(this).each(function() {
			$(this).find("thead .sp-select-all").prop("checked", $(this).find("tbody input[type=checkbox]:checked").length == $(this).find("tbody input[type=checkbox]").length);
		});
	});

	// Activate check check when a checkbox is checked
	$(".sp-data-table tbody input[type=checkbox]").change(function() {
		$(this).closest(".sp-data-table").trigger("checkCheck");
	});

	// Trigger check check
	$(".sp-data-table").trigger("checkCheck");

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

		$precisionselector = $(".sp-precision-selector input:first");
		$equationselector = $(".sp-equation-selector select");

		// Precision settings
		if($(this).val() == "decimal" || $(this).val() == "time") {
			$precisionselector.prop( "disabled", false );
		} else {
			$precisionselector.prop( "disabled", true )
		}

		// Equation settings
		if($(this).val() == "custom") {
			$equationselector.prop( "disabled", true );
		} else {
			$equationselector.prop( "disabled", false );
		}

	});

	// Trigger format selector
	$(".sp-format-selector select:first").change();

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

	// Remove slug editor in quick edit for slug-sensitive post types
	$(".inline-edit-sp_result, .inline-edit-sp_outcome, .inline-edit-sp_column, .inline-edit-sp_statistic").find("input[name=post_name]").closest("label").remove();

	// Prevent address input from submitting form
	$(".sp-address").keypress(function(event) {
		return event.keyCode != 13;
	});

});