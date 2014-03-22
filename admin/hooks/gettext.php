<?php
function sportspress_gettext( $translated_text, $untranslated_text, $domain ) {
	if ( $domain != 'sportspress' )
		return $translated_text;

	global $typenow, $sportspress_options;

	if ( is_admin() ):
		if ( in_array( $typenow, array( 'sp_team' ) ) ):
			switch ( $untranslated_text ):
			case 'Set featured image':
				$translated_text = __( 'Select Logo', 'sportspress' );
				break;
			case 'Featured Image':
				$translated_text = __( 'Logo', 'sportspress' );
				break;
			case 'Set Featured Image':
				$translated_text = __( 'Select Logo', 'sportspress' );
				break;
			case 'Remove featured image':
				$translated_text = __( 'Remove Logo', 'sportspress' );
				break;
			case 'Author':
				$translated_text = __( 'User', 'sportspress' );
				break;
			endswitch;
		elseif ( in_array( $typenow, array( 'sp_event', 'sp_player', 'sp_staff' ) ) ):
			switch ( $untranslated_text ):
			case 'Enter title here':
				$translated_text = __( '(Auto)', 'sportspress' );
				break;
			case 'Publish <b>immediately</b>':
				$translated_text = __( 'Date/Time:', 'sportspress' ) . ' <b>' . __( 'Now', 'sportspress' ) . '</b>';
				break;
			case 'Author':
				$translated_text = __( 'User', 'sportspress' );
				break;
			endswitch;
		endif;

		if ( in_array( $typenow, array( 'sp_team' ) ) ):
			switch ( $untranslated_text ):
			case 'Enter title here':
				$translated_text = __( 'Team', 'sportspress' );
				break;
			endswitch;
		endif;
		
		if ( in_array( $typenow, array( 'sp_player', 'sp_staff' ) ) ):
			switch ( $untranslated_text ):
			case 'Featured Image':
				$translated_text = __( 'Photo', 'sportspress' );
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
		if ( isset( $sportspress_options['text'] ) ):
			foreach( $sportspress_options['text'] as $key => $value ):
				if ( $translated_text == $key ):
					$translated_text = $value;
				endif;
			endforeach;
		endif;
	endif;
	
	return $translated_text;
}
add_filter( 'gettext', 'sportspress_gettext', 20, 3 );
