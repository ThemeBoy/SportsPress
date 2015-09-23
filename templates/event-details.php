<?php
/**
 * Event Details
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Templates
 * @version     1.9
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if ( get_option( 'sportspress_event_show_details', 'yes' ) === 'no' ) return;

if ( ! isset( $id ) )
	$id = get_the_ID();

$scrollable = get_option( 'sportspress_enable_scrollable_tables', 'yes' ) == 'yes' ? true : false;
$date = get_the_time( get_option('date_format'), $id );
$time = get_the_time( get_option('time_format'), $id );

$data = array( __( 'Date', 'sportspress' ) => $date, __( 'Time', 'sportspress' ) => $time );

$taxonomies = apply_filters( 'sportspress_event_taxonomies', array( 'sp_league' => null, 'sp_season' => null ) );

foreach ( $taxonomies as $taxonomy => $post_type ):
	$terms = get_the_terms( $id, $taxonomy );
	if ( $terms ):
		$obj = get_taxonomy( $taxonomy );
		$term = array_shift( $terms );
		$data[ $obj->labels->singular_name ] = $term->name;
	endif;
endforeach;

$data = apply_filters( 'sportspress_event_details', $data, $id );
?>
<div class="sp-template sp-template-event-details">
	<h4 class="sp-table-caption"><?php _e( 'Details', 'sportspress' ); ?></h4>
	<div class="sp-table-wrapper">
		<table class="sp-event-details sp-data-table<?php if ( $scrollable ) { ?> sp-scrollable-table<?php } ?>">
			<thead>
				<tr>
					<?php $i = 0; foreach( $data as $label => $value ):	?>
						<th><?php echo $label; ?></th>
					<?php $i++; endforeach; ?>
				</tr>
			</thead>
			<tbody>
				<tr class="odd">
					<?php $i = 0; foreach( $data as $value ):	?>
						<td><?php echo $value; ?></td>
					<?php $i++; endforeach; ?>
				</tr>
			</tbody>
		</table>
	</div>
</div>