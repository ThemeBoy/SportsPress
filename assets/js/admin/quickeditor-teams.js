(function($) {

	// we create a copy of the WP inline edit post function
	var $wp_inline_edit = inlineEditPost.edit;

	// and then we overwrite the function with our own code
	inlineEditPost.edit = function( id ) {

		// "call" the original WP edit function
		// we don't want to leave WordPress hanging
		$wp_inline_edit.apply( this, arguments );

		// now we take care of our business

		// get the post ID
		var $post_id = 0;
		if ( typeof( id ) == 'object' ) {
			$post_id = parseInt( this.getId( id ) );
		}

		if ( $post_id > 0 ) {
			// define the edit row
			var $edit_row = $( '#edit-' + $post_id );
			var $post_row = $( '#post-' + $post_id );

			// get the data
			var $competitions = String( $( '.column-sp_competition', $post_row ).find( '.sp-team-competitions' ).data( 'competitions' ) ).split(',');

			// populate the data
			$( ':input[name="sp_competition[]"]', $edit_row ).each(function() {
				$(this).prop("checked", ($.inArray($(this).val(), $competitions ) != -1));
			});
		}
	};

	$( document ).on( 'click', '#bulk_edit', function() {
		// define the bulk edit row
		var $bulk_row = $( '#bulk-edit' );

		// get the selected post ids that are being edited
		var $post_ids = new Array();
		$bulk_row.find( '#bulk-titles' ).children().each( function() {
			$post_ids.push( $( this ).attr( 'id' ).replace( /^(ttle)/i, '' ) );
		});

		// get the data
		var $competitions = [];
		$bulk_row.find( 'input[name="sp_competition[]"]:checked' ).each(function() {
			$competitions.push( $(this).val() );
		});

		// save the data
		$.ajax({
			url: ajaxurl, // this is a variable that WordPress has already defined for us
			type: 'POST',
			async: false,
			cache: false,
			data: {
				action: 'save_bulk_edit_sp_team',
				post_ids: $post_ids,
				competitions: $competitions,
				nonce: $("#sp_team_edit_nonce").val()
			}
		});
	});

})(jQuery);