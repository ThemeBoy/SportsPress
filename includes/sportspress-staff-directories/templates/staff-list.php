<?php
/**
 * Staff List
 *
 * @author 		ThemeBoy
 * @package 	SportsPress_Staff_Directories
 * @version     1.6
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$defaults = array(
	'id' => get_the_ID(),
	'title' => false,
	'number' => -1,
	'columns' => null,
	'show_all_staff_link' => false,
	'link_posts' => get_option( 'sportspress_link_staff', 'yes' ) == 'yes' ? true : false,
	'link_phone' => get_option( 'sportspress_staff_link_phone', 'yes' ) == 'yes' ? true : false,
	'link_email' => get_option( 'sportspress_staff_link_email', 'yes' ) == 'yes' ? true : false,
	'sortable' => get_option( 'sportspress_enable_sortable_tables', 'yes' ) == 'yes' ? true : false,
	'scrollable' => get_option( 'sportspress_enable_scrollable_tables', 'yes' ) == 'yes' ? true : false,
	'paginated' => get_option( 'sportspress_directory_paginated', 'yes' ) == 'yes' ? true : false,
	'rows' => get_option( 'sportspress_directory_rows', 10 ),
);

extract( $defaults, EXTR_SKIP );

$directory = new SP_Staff_Directory( $id );
if ( isset( $columns ) && null !== $columns ):
	$directory->columns = $columns;
endif;
$data = $directory->data();

// The first row should be column labels
$labels = $data[0];

// Remove the first row to leave us with the actual data
unset( $data[0] );

$output = '';

$output .= '<div class="sp-table-wrapper">' .
	'<table class="sp-staff-directory sp-data-table' . ( $sortable ? ' sp-sortable-table' : '' ) . ( $scrollable ? ' sp-scrollable-table' : '' ) . ( $paginated ? ' sp-paginated-table' : '' ) . '" data-sp-rows="' . $rows . '">' . '<thead>' . '<tr>';

foreach( $labels as $key => $label ):
	if ( ! is_array( $columns ) || $key == 'name' || in_array( $key, $columns ) )
	$output .= '<th class="data-' . $key . '">'. $label . '</th>';
endforeach;

$output .= '</tr>' . '</thead>' . '<tbody>';

$i = 0;

if ( intval( $number ) > 0 )
	$limit = $number;

foreach( $data as $staff_id => $row ):

	if ( isset( $limit ) && $i >= $limit ) continue;

	$name = sp_array_value( $row, 'name', null );
	if ( ! $name ) continue;

	$output .= '<tr class="' . ( $i % 2 == 0 ? 'odd' : 'even' ) . '">';

	if ( $link_posts ):
		$permalink = get_post_permalink( $staff_id );
		$name = '<a href="' . $permalink . '">' . $name . '</a>';
	endif;

	if ( ! is_array( $columns ) || in_array( 'role', $columns ) )
		$output .= '<td class="data-role">' . sp_array_value( $row, 'role', '&mdash;' ) . '</td>';

	$output .= '<td class="data-name">' . $name . '</td>';
	
	if ( array_key_exists( 'team', $labels ) ):
		$team = sp_array_value( $row, 'team', get_post_meta( $id, 'sp_team', true ) );
		$team_name = get_the_title( $team );
		if ( $link_teams ):
			$team_name = '<a href="' . get_post_permalink( $team ) . '">' . $team_name . '</a>';
		endif;
		$output .= '<td class="data-team">' . $team_name . '</td>';
	endif;

	foreach( $labels as $key => $label ):
		if ( in_array( $key, array( 'name', 'role' ) ) )
			continue;
		if ( ! is_array( $columns ) || in_array( $key, $columns ) ):
			$value = sp_array_value( $row, $key, '&mdash;' );
			if ( $key == 'phone' && $value !== '&mdash;' && $link_phone ) {
				$value = '<a href="tel:' . $value . '">' . $value . '</a>';
			} elseif ( $key == 'email' && $value !== '&mdash;' && $link_email ) {
				$value = '<a href="mailto:' . $value . '">' . $value . '</a>';
			}
			$output .= '<td class="data-' . $key . '">' . $value . '</td>';
		endif;
	endforeach;

	$output .= '</tr>';

	$i++;

endforeach;

$output .= '</tbody>' . '</table>' . '</div>';

if ( $show_all_staff_link )
	$output .= '<div class="sp-staff-directory-link sp-view-all-link"><a href="' . get_permalink( $id ) . '">' . __( 'View all staff', 'sportspress' ) . '</a></div>';

if ( $title )
	echo '<h4 class="sp-table-caption">' . $title . '</h4>';
?>
<div class="sp-template sp-template-staff-list">
	<?php echo $output; ?>
</div>