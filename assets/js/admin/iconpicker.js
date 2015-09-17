jQuery(document).ready(function($){

	// Icon picker
	$('.sp-icons input').on('change', function() {
		if ('' == $(this).val()) {
			$('.sp-custom-colors').hide();
			$('.sp-custom-thumbnail').show();
		} else {
			$('.sp-custom-thumbnail').hide();
			$('.sp-custom-colors').show();
		}
	});

	// Color picker
	$('.colorpick').iris( {
		change: function(event, ui){
			$(this).css( { backgroundColor: ui.color.toString() } );
		},
		hide: true,
		border: true
	} ).each( function() {
		$(this).css( { backgroundColor: $(this).val() } );
	})
	.click(function(){
		$('.iris-picker').hide();
		$(this).closest('.sp-icon-color-box, td').find('.iris-picker').show();
	});

	$('body').click(function() {
		$('.iris-picker').hide();
	});

	$('.sp-icon-color-box, .colorpick').click(function(event){
	    event.stopPropagation();
	});

});