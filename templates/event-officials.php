<?php
/**
 * Event Officials
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Templates
 * @version     1.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! isset( $id ) )
	$id = get_the_ID();

$officials = (array) get_post_meta( $id, 'sp_officials', true );
$officials = array_filter( $officials );

if ( empty( $officials ) ) return;

$duties = get_terms( array(
  'taxonomy' => 'sp_duty',
  'hide_empty' => false,
) );

if ( empty( $duties ) ) return;

$scrollable = get_option( 'sportspress_enable_scrollable_tables', 'yes' ) == 'yes' ? true : false;
$link_officials = get_option( 'sportspress_link_officials', 'no' ) == 'yes' ? true : false;

$rows = '';
$i = 0;

foreach ( $duties as $duty ) {
	$officials_on_duty = sp_array_value( $officials, $duty->term_id, array() );

	if ( empty( $officials_on_duty ) ) continue;

	foreach ( $officials_on_duty as $official ) {
		$rows .= '<tr class="' . ( $i % 2 == 0 ? 'odd' : 'even' ) . '">';

		$name = get_the_title( $official );

		if ( $link_officials && sp_post_exists( $official ) ) {
			$name = '<a href="' . get_post_permalink( $official ) . '">' . $name . '</a>';
		}

		$rows .= '<th class="data-name">' . $name . '</th>';

		$rows .= '<td class="data-duty">' . $duty->name . '</td>';

		$rows .= '</tr>';

		$i++;
	}
}

if ( empty( $rows ) ) return;
?>

<div class="sp-template sp-template-event-officials">
	<h4 class="sp-table-caption"><?php _e( 'Officials', 'sportspress' ); ?></h4>

	<div class="sp-table-wrapper">
		<table class="sp-event-officials sp-data-table<?php echo $scrollable ? ' sp-scrollable-table' : ''; ?>">
			<tbody>
				<?php echo $rows; ?>
			</tbody>
		</table>
	</div>
</div>
