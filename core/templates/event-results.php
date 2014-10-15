<?php
/**
 * Event Results
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Templates
 * @version     1.3
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! isset( $id ) )
	$id = get_the_ID();

$event = new SP_Event( $id );
$status = $event->status();

if ( 'results' != $status ) return;

// Get event result data
$data = $event->results();

// The first row should be column labels
$labels = $data[0];

// Remove the first row to leave us with the actual data
unset( $data[0] );

$data = array_filter( $data );

if ( empty( $data ) )
	return false;

$scrollable = get_option( 'sportspress_enable_scrollable_tables', 'yes' ) == 'yes' ? true : false;
$show_outcomes = array_key_exists( 'outcome', $labels );

// Initialize
$output = '';
$table_rows = '';
$i = 0;

foreach( $data as $team_id => $result ):
	if ( $show_outcomes ):
		$outcomes = array();
		$result_outcome = sp_array_value( $result, 'outcome' );
		if ( ! is_array( $result_outcome ) ):
			$outcomes = array( '&mdash;' );
		else:
			foreach( $result_outcome as $outcome ):
				$the_outcome = get_page_by_path( $outcome, OBJECT, 'sp_outcome' );
				if ( is_object( $the_outcome ) ):
					$outcomes[] = $the_outcome->post_title;
				endif;
			endforeach;
		endif;
	endif;

	unset( $result['outcome'] );

	$table_rows .= '<tr class="' . ( $i % 2 == 0 ? 'odd' : 'even' ) . '">';

	$table_rows .= '<td class="data-name">' . get_the_title( $team_id ) . '</td>';

	foreach( $labels as $key => $label ):
		if ( in_array( $key, array( 'name', 'outcome' ) ) )
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
endforeach;

if ( empty( $table_rows ) ):

	return false;

else:

	$output .= '<h4 class="sp-table-caption">' . __( 'Team Results', 'sportspress' ) . '</h4>';

	$output .= '<div class="sp-table-wrapper' . ( $scrollable ? ' sp-scrollable-table-wrapper' : '' ) . '">' .
		'<table class="sp-event-results sp-data-table sp-responsive-table"><thead>' .
		'<th class="data-name">' . __( 'Team', 'sportspress' ) . '</th>';
	foreach( $labels as $key => $label ):
		$output .= '<th class="data-' . $key . '">' . $label . '</th>';
	endforeach;
	$output .= '</tr>' . '</thead>' . '<tbody>';
	$output .= $table_rows;
	$output .= '</tbody>' . '</table>' . '</div>';

endif;
?>
<div class="sp-template sp-template-event-results">
	<?php echo $output; ?>
</div>