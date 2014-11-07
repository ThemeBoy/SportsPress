jQuery(document).ready(function($){

	// Staff directory layout affects data
	$(".post-type-sp_directory #post-formats-select input.post-format").change(function() {
		layout = $(".post-type-sp_directory #post-formats-select input:checked").val();
		$(".sp-staff-directory-table tr").each(function() {
			if ( layout == "list" ) {
				$(this).find("th input[type=checkbox]").show();
			} else {
				$(this).find("th input[type=checkbox]").hide();
			}
		});
	});

	// Trigger staff directory layout change
	$(".post-type-sp_directory #post-formats-select input.post-format").trigger("change");

});