<?php
/**
 * Event Venue
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Templates
 * @version     2.7.1
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if ( get_option( 'sportspress_event_show_venue', 'yes' ) === 'no' ) return;

if ( ! isset( $id ) )
	$id = get_the_ID();

$venues = get_the_terms( $id, 'sp_venue' );

if ( ! $venues )
	return;

$show_maps = get_option( 'sportspress_event_show_maps', 'yes' ) == 'yes' ? true : false;
$link_venues = get_option( 'sportspress_link_venues', 'no' ) == 'yes' ? true : false;

foreach( $venues as $venue ):
	$t_id = $venue->term_id;
	$meta = get_option( "taxonomy_$t_id" );

	$name = $venue->name;
	if ( $link_venues )
		$name = '<a href="' . get_term_link( $t_id, 'sp_venue' ) . '">' . $name . '</a>';

	$address = sp_array_value( $meta, 'sp_address', null );
	$address = urlencode( $address );
	?>
	<div class="sp-template sp-template-event-venue">
		<h4 class="sp-table-caption"><?php _e( 'Venue', 'sportspress' ); ?></h4>
		<table class="sp-data-table sp-event-venue">
			<thead>
				<tr>
					<th><?php echo $name; ?></th>
				</tr>
			</thead>
			<?php if ( $show_maps ): ?>
				<tbody>
					<tr class="sp-event-venue-map-row">
						<td><?php sp_get_template( 'venue-map.php', array( 'meta' => $meta ) ); ?></td>
					</tr>
					<?php if ( $address != null ) { ?>
						<tr class="sp-event-venue-address-row">
							<td><?php echo urldecode( $address ); ?></td>
						</tr>
					<?php } ?>
				</tbody>
			<?php endif; ?>
		</table>
	</div>
	<?php
endforeach;
?>
