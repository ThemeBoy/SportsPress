jQuery(document).ready(function($){

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
		$(this).closest('.sp-team-color-box, td').find('.iris-picker').show();
	});

	$('body').click(function() {
		$('.iris-picker').hide();
	});

	$('.sp-team-color-box, .colorpick').click(function(event){
	    event.stopPropagation();
	});

});