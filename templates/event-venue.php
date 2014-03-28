<?php
if ( ! isset( $id ) )
	$id = get_the_ID();

$venues = get_the_terms( $id, 'sp_venue' );

$show_map = get_option( 'sportspress_event_show_map', 'yes' ) == 'yes' ? true : false;

if ( ! $venues )
	return $output;

foreach( $venues as $venue ):
	$t_id = $venue->term_id;
	$term_meta = get_option( "taxonomy_$t_id" );

	$address = sp_array_value( $term_meta, 'sp_address', '' );
	$latitude = sp_array_value( $term_meta, 'sp_latitude', 0 );
	$longitude = sp_array_value( $term_meta, 'sp_longitude', 0 );
	?>
	<h3><?php echo SP()->text->string('Venue', 'event'); ?></h3>
	<table class="sp-data-table sp-event-venue">
		<thead>
			<tr>
				<th><a href="<?php echo get_term_link( $t_id, 'sp_venue' ); ?>"><?php echo $venue->name; ?></a></th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td><?php echo $address; ?></td>
			</tr>
			<?php if ( $show_map && $latitude != null && $longitude != null ): ?>
				<tr>
					<td><div class="sp-google-map" data-address="<?php echo $address; ?>" data-latitude="<?php echo $latitude; ?>" data-longitude="<?php echo $longitude; ?>"></div></td>
				</tr>
			<?php endif; ?>
		</tbody>
	</table>
	<?php
endforeach;
