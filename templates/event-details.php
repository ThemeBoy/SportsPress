<?php
/**
 * Event Details
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Templates
 * @version     0.8
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! isset( $id ) )
	$id = get_the_ID();

$scrollable = get_option( 'sportspress_enable_scrollable_tables', 'yes' ) == 'yes' ? true : false;
$date = get_the_time( get_option('date_format'), $id );
$time = get_the_time( get_option('time_format'), $id );
$leagues = get_the_terms( $id, 'sp_league' );
$seasons = get_the_terms( $id, 'sp_season' );

$data = array( __( 'Date', 'sportspress' ) => $date, __( 'Time', 'sportspress' ) => $time );

if ( $leagues ):
	$league = array_pop( $leagues );
	$data[ __( 'League', 'sportspress' ) ] = $league->name;
endif;

if ( $seasons ):
	$season = array_pop( $seasons );
	$data[ __( 'Season', 'sportspress' ) ] = $season->name;
endif;
?>
<h4 class="sp-table-caption"><?php _e( 'Details', 'sportspress' ); ?></h4>
<div class="sp-table-wrapper<?php if ( $scrollable ) { ?> sp-scrollable-table-wrapper<?php } ?>">
	<table class="sp-event-details sp-data-table">
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
