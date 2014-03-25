<?php
if ( ! isset( $id ) )
	$id = get_the_ID();

global $sportspress_options;

$defaults = array(
	'show_nationality_flag' => sportspress_array_value( $sportspress_options, 'player_show_nationality_flag', true ),
);

extract( $defaults, EXTR_SKIP );

$countries = SP()->countries->countries;

$nationality = get_post_meta( $id, 'sp_nationality', true );
$current_team = get_post_meta( $id, 'sp_current_team', true );
$past_teams = get_post_meta( $id, 'sp_past_team', false );
$metrics = sportspress_get_player_metrics_data( $id );

$common = array();
if ( $nationality ):
	$country_name = sportspress_array_value( $countries, $nationality, null );
	$common[ __( 'Nationality', 'sportspress' ) ] = $country_name ? ( $show_nationality_flag ? '<img src="' . plugin_dir_url( SP_PLUGIN_FILE ) . '/assets/images/flags/' . strtolower( $nationality ) . '.png" alt="' . $nationality . '"> ' : '' ) . $country_name : '&mdash;';
endif;

$data = array_merge( $common, $metrics );

if ( $current_team )
	$data[ __( 'Current Team', 'sportspress' ) ] = '<a href="' . get_post_permalink( $current_team ) . '">' . get_the_title( $current_team ) . '</a>';

if ( $past_teams ):
	$teams = array();
	foreach ( $past_teams as $team ):
		$teams[] = '<a href="' . get_post_permalink( $team ) . '">' . get_the_title( $team ) . '</a>';
	endforeach;
	$data[ __( 'Past Teams', 'sportspress' ) ] = implode( ', ', $teams );
endif;

$output = '<div class="sp-list-wrapper">' .
	'<dl class="sp-player-metrics">';

foreach( $data as $label => $value ):

	$output .= '<dt>' . $label . '<dd>' . $value . '</dd>';

endforeach;

$output .= '</dl></div>';

echo apply_filters( 'sportspress_player_metrics',  $output );
