<?php
function sportspress_gettext( $translated_text, $untranslated_text, $domain ) {
	global $typenow;

	if ( is_admin() ):
		if ( 'sp_team' == $typenow ):
			switch ( $untranslated_text ):
			case 'Enter title here':
				$translated_text = __( 'Team', 'sportspress' );
				break;
			case 'Set featured image':
				$translated_text = __( 'Select Logo', 'sportspress' );
				break;
			case 'Set Featured Image':
				$translated_text = __( 'Select Logo', 'sportspress' );
				break;
			case 'Remove featured image':
				$translated_text = __( 'Remove Logo', 'sportspress' );
				break;
			endswitch;
		elseif ( in_array( $typenow, array( 'sp_event', 'sp_player', 'sp_staff' ) ) ):
			switch ( $untranslated_text ):
			case 'Enter title here':
				$translated_text = __( '(Auto)', 'sportspress' );
				break;
			case 'Set featured image':
				$translated_text = __( 'Select Photo', 'sportspress' );
				break;
			case 'Set Featured Image':
				$translated_text = __( 'Select Photo', 'sportspress' );
				break;
			case 'Remove featured image':
				$translated_text = __( 'Remove Photo', 'sportspress' );
				break;
			endswitch;
		endif;
	else:
    	if ( $untranslated_text == 'Archives' && is_tax( 'sp_venue' ) ):
    		$slug = get_query_var( 'sp_venue' );
		    if ( $slug ):
			    $venue = get_term_by( 'slug', $slug, 'sp_venue' );
				$translated_text = $venue->name;
			endif;
		endif;
	endif;
	
	return $translated_text;
}
add_filter( 'gettext', 'sportspress_gettext', 20, 3 );
