<?php
/**
 * Event Results
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Templates
 * @version     0.8
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! isset( $id ) )
	$id = get_the_ID();

$defaults = array(
	'show_outcomes' => get_option( 'sportspress_event_show_outcomes', 'yes' ) == 'yes' ? true : false,
);

extract( $defaults, EXTR_SKIP );

$teams = (array)get_post_meta( $id, 'sp_team', false );
$results = array_filter( sp_array_combine( $teams, (array)get_post_meta( $id, 'sp_results', true ) ), 'array_filter' );
$result_labels = sp_get_var_labels( 'sp_result' );

$output = '';

// Initialize and check
$table_rows = '';

$i = 0;

if ( empty( $results ) )
	return false;

foreach( $results as $team_id => $result ):
	if ( count( array_filter( $results ) ) ):

		if ( $show_outcomes ):
			$outcomes = array();
			$result_outcome = $result['outcome'];
			if ( ! is_array( $result_outcome ) ):
				$result_outcome = (array) $result_outcome;
			endif;
			foreach( $result_outcome as $outcome ):
				$the_outcome = get_page_by_path( $outcome, OBJECT, 'sp_outcome' );
				if ( is_object( $the_outcome ) ):
					$outcomes[] = $the_outcome->post_title;
				endif;
			endforeach;
		endif;

		unset( $result['outcome'] );

		$table_rows .= '<tr class="' . ( $i % 2 == 0 ? 'odd' : 'even' ) . '">';

		$table_rows .= '<td class="data-name">' . get_the_title( $team_id ) . '</td>';

		foreach( $result_labels as $key => $label ):
			if ( $key == 'name' )
				continue;
			if ( array_key_exists( $key, $result ) && $result[ $key ] != '' ):
				$value = $result[ $key ];
			else:
				$value = '&mdash;';
			endif;
			$table_rows .= '<td class="data-' . $key . '">' . $value . '</td>';
		endforeach;

		if ( $show_outcomes ):
			$table_rows .= '<td class="data-outcome">' . implode( ', ', $outcomes ) . '</td>';
		endif;

		$table_rows .= '</tr>';

		$i++;

	endif;
endforeach;

if ( empty( $table_rows ) ):

	return false;

else:

	$output .= '<h3>' . __( 'Team Results', 'sportspress' ) . '</h3>';

	$output .= '<div class="sp-table-wrapper sp-scrollable-table-wrapper">' .
		'<table class="sp-event-results sp-data-table sp-responsive-table"><thead>' .
		'<th class="data-name">' . __( 'Team', 'sportspress' ) . '</th>';
	foreach( $result_labels as $key => $label ):
		$output .= '<th class="data-' . $key . '">' . $label . '</th>';
	endforeach;
	if ( $show_outcomes ):
		$output .= '<th class="data-outcome">' . __( 'Outcome', 'sportspress' ) . '</th>';
	endif;
	$output .= '</tr>' . '</thead>' . '<tbody>';
	$output .= $table_rows;
	$output .= '</tbody>' . '</table>' . '</div>';

endif;

echo $output;
