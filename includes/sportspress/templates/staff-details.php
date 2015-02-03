<?php
/**
 * Staff Details
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Templates
 * @version     1.6
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if ( get_option( 'sportspress_staff_show_details', 'yes' ) === 'no' ) return;

if ( ! isset( $id ) )
	$id = get_the_ID();

$defaults = array(
	'show_nationality_flags' => get_option( 'sportspress_staff_show_flags', 'yes' ) == 'yes' ? true : false,
);

extract( $defaults, EXTR_SKIP );

$countries = SP()->countries->countries;

$staff = new SP_Staff( $id );

$nationality = $staff->nationality;
$current_team = $staff->current_team;
$past_teams = $staff->past_teams();

$data = array();
if ( $nationality ):
	if ( 2 == strlen( $nationality ) ):
		$legacy = SP()->countries->legacy;
		$nationality = strtolower( $nationality );
		$nationality = sp_array_value( $legacy, $nationality, null );
	endif;
	$country_name = sp_array_value( $countries, $nationality, null );
	$data[ __( 'Nationality', 'sportspress' ) ] = $country_name ? ( $show_nationality_flags ? '<img src="' . plugin_dir_url( SP_PLUGIN_FILE ) . 'assets/images/flags/' . strtolower( $nationality ) . '.png" alt="' . $nationality . '"> ' : '' ) . $country_name : '&mdash;';
endif;

if ( $current_team )
	$data[ __( 'Current Team', 'sportspress' ) ] = '<a href="' . get_post_permalink( $current_team ) . '">' . get_the_title( $current_team ) . '</a>';

if ( $past_teams ):
	$teams = array();
	foreach ( $past_teams as $team ):
		$teams[] = '<a href="' . get_post_permalink( $team ) . '">' . get_the_title( $team ) . '</a>';
	endforeach;
	$data[ __( 'Past Teams', 'sportspress' ) ] = implode( ', ', $teams );
endif;

$data = apply_filters( 'sportspress_staff_details', $data, $id );

$output = '<div class="sp-list-wrapper">' .
	'<dl class="sp-staff-details">';

foreach( $data as $label => $value ):

	$output .= '<dt>' . $label . '</dt><dd>' . $value . '</dd>';

endforeach;

$output .= '</dl></div>';
?>
<div class="sp-template sp-template-staff-details sp-template-details">
	<?php echo $output; ?>
</div>