<?php
add_shortcode( 'sp_table', 'sp_table_shortcode' );
function sp_table_shortcode( $atts, $content = null, $code = "" ) {
	global $sp_table_stats_labels;
	extract( shortcode_atts( array(
		'limit' => 0,
		'div' => 0
	), $atts ) );

	// Get all teams in the division
	$args = array(
		'post_type' => 'sp_team',
		'numberposts' => -1,
		'posts_per_page' => -1,
		'tax_query' => array()
	);
	if ( $div ) {
		$args['tax_query'][] = array(
			'taxonomy' => 'sp_div',
			'terms' => $div,
			'field' => 'term_id'
		);
	}
	$teams = get_posts( $args );

	// Check if there are any teams
	$size = sizeof( $teams );
	if ( $size == 0 )
		return false;

	// Generate table
	$output = '<table class="sp_table">
		<thead>
			<tr>
				<th class="pos">' . __( 'Position', 'sportspress' ) . '</th>';
		foreach( $stats as $stat ) {
			$output .= '<th class="' . $stat . '">' . $sp_table_stats_labels[$stat] . '</th>';
		}
		$output .=
				'</tr>
			</thead>
		<tbody>';
		// insert rows
		$rownum = 0;
		foreach ( $teams as $club ) {
			$rownum ++;
			$club_stats = $club->tb_stats;
			$output .=
			'<tr class="' . ( $center == $club->ID ? 'highlighted ' : '' ) . ( $rownum % 2 == 0 ? 'even' : 'odd' ) . ( $rownum == $limit ? ' last' : '' ) . '">';
			$output .= '<td class="club"><span class="pos">' . $club->place . '</span> ' . ( $club_links ? '<a class="tb-club-link" href="' . get_permalink( $club->ID ) . '">' : '' ) . get_the_post_thumbnail( $club->ID, 'crest-small', array( 'title' => $club->post_title, 'class' => 'crest' ) ) . ' <span class="name">' . $club->post_title . ( $club_links ? '</a>' : '' ) . '</span></td>';
			foreach( $stats as $stat ) {
				$output .= '<td class="' . $stat . '">' . $club_stats[$stat] . '</td>';
			}
		}
		$output.=
		'</tbody>
		</table>';
	if ( isset( $linkpage ) )
		$output .= '<a href="' . get_page_link( $linkpage ) . '" class="tb_view_all">' . $linktext . '</a>';
	$output .= '</section>';
	return $output;
}
?>