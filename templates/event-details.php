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

$date = get_the_time( get_option('date_format'), $id );
$time = get_the_time( get_option('time_format'), $id );
$leagues = get_the_terms( $id, 'sp_league' );
$seasons = get_the_terms( $id, 'sp_season' );

$data = array( SP()->text->string('Date') => $date, SP()->text->string('Time') => $time );

if ( $leagues ):
	$league = array_pop( $leagues );
	$data[ SP()->text->string('League') ] = $league->name;
endif;

if ( $seasons ):
	$season = array_pop( $seasons );
	$data[ SP()->text->string('Season') ] = $season->name;
endif;
?>
<h3><?php echo SP()->text->string('Details'); ?></h3>
<div class="sp-table-wrapper sp-scrollable-table-wrapper">
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
