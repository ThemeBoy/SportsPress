<?php
if ( ! isset( $id ) )
	$id = get_the_ID();

$venues = get_the_terms( $id, 'sp_venue' );

if ( ! $venues )
	return;

$show_maps = get_option( 'sportspress_event_show_maps', 'yes' ) == 'yes' ? true : false;
$link_venues = get_option( 'sportspress_event_link_venues', 'no' ) == 'yes' ? true : false;

foreach( $venues as $venue ):
	$t_id = $venue->term_id;
	$term_meta = get_option( "taxonomy_$t_id" );

	$name = $venue->name;
	if ( $link_venues )
		$name = '<a href="' . get_term_link( $t_id, 'sp_venue' ) . '">' . $name . '</a>';

	$address = sp_array_value( $term_meta, 'sp_address', '' );
	$latitude = sp_array_value( $term_meta, 'sp_latitude', 0 );
	$longitude = sp_array_value( $term_meta, 'sp_longitude', 0 );
	?>
	<h3><?php echo SP()->text->string('Venue', 'event'); ?></h3>
	<table class="sp-data-table sp-event-venue">
		<thead>
			<tr>
				<th><?php echo $name; ?></th>
			</tr>
		</thead>
		<?php if ( $address != null || ( $show_maps && $latitude != null && $longitude != null ) ): ?>
			<tbody>
				<tr>
					<td><?php echo $address; ?></td>
				</tr>
				<?php if ( $show_maps && $latitude != null && $longitude != null ): ?>
					<tr>
						<td><div class="sp-google-map" data-address="<?php echo $address; ?>" data-latitude="<?php echo $latitude; ?>" data-longitude="<?php echo $longitude; ?>"></div></td>
					</tr>
				<?php endif; ?>
			</tbody>
		<?php endif; ?>
	</table>
	<?php
endforeach;
