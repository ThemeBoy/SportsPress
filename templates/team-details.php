<?php
/**
 * Team Details
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Templates
 * @version     1.8
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if ( get_option( 'sportspress_team_show_details', 'no' ) === 'no' ) return;

if ( ! isset( $id ) )
	$id = get_the_ID();

$data = array();

$terms = get_the_terms( $id, 'sp_league' );
if ( $terms ):
	$leagues = array();
	foreach ( $terms as $term ):
		$leagues[] = $term->name;
	endforeach;
	$data[ __( 'Competitions', 'sportspress' ) ] = implode( ', ', $leagues );
endif;

$terms = get_the_terms( $id, 'sp_season' );
if ( $terms ):
	$seasons = array();
	foreach ( $terms as $term ):
		$seasons[] = $term->name;
	endforeach;
	$data[ __( 'Seasons', 'sportspress' ) ] = implode( ', ', $seasons );
endif;

$terms = get_the_terms( $id, 'sp_venue' );
if ( $terms ):
	if ( get_option( 'sportspress_team_link_venues', 'no' ) === 'yes' ):
		$data[ __( 'Home', 'sportspress' ) ] = get_the_term_list( $id, 'sp_venue' );
	else:
		$venues = array();
		foreach ( $terms as $term ):
			$venues[] = $term->name;
		endforeach;
		$data[ __( 'Home', 'sportspress' ) ] = implode( ', ', $venues );
	endif;
endif;

$output = '<div class="sp-list-wrapper">' .
	'<dl class="sp-team-details">';

foreach( $data as $label => $value ):

	$output .= '<dt>' . $label . '</dt><dd>' . $value . '</dd>';

endforeach;

$output .= '</dl></div>';
?>
<div class="sp-template sp-template-team-details sp-template-details">
	<?php echo $output; ?>
</div>