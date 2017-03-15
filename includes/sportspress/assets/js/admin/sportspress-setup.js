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
});