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
				$translated_text = sprintf( __( 'Select %s', 'sportspress' ), __( 'Logo', 'sportspress' ) );
				break;
			case 'Set Featured Image':
				$translated_text = sprintf( __( 'Select %s', 'sportspress' ), __( 'Logo', 'sportspress' ) );
				break;
			case 'Remove featured image':
				$translated_text = sprintf( __( 'Remove %s', 'sportspress' ), __( 'Logo', 'sportspress' ) );
				break;
			endswitch;
		elseif ( in_array( $typenow, array( 'sp_event', 'sp_player', 'sp_staff' ) ) ):
			switch ( $untranslated_text ):
			case 'Enter title here':
				$translated_text = __( 'Name', 'sportspress' );
				break;
			case 'Set featured image':
				$translated_text = sprintf( __( 'Select %s', 'sportspress' ), __( 'Photo', 'sportspress' ) );
				break;
			case 'Set Featured Image':
				$translated_text = sprintf( __( 'Select %s', 'sportspress' ), __( 'Photo', 'sportspress' ) );
				break;
			case 'Remove featured image':
				$translated_text = sprintf( __( 'Remove %s', 'sportspress' ), __( 'Photo', 'sportspress' ) );
				break;
			case 'Scheduled for: <b>%1$s</b>':
				$translated_text = __( 'Date/Time: <b>%1$s</b>', 'sportspress' );
				break;
			case 'Published on: <b>%1$s</b>':
				$translated_text = __( 'Date/Time: <b>%1$s</b>', 'sportspress' );
				break;
			case 'Publish <b>immediately</b>':
				$translated_text = __( 'Date/Time: <b>%1$s</b>', 'sportspress' );
				break;
			endswitch;
		endif;
	endif;
	
	return $translated_text;
}
add_filter( 'gettext', 'sportspress_gettext', 20, 3 );
