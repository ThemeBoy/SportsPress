<h3 class="title"><?php _e( 'Event Settings', 'sportspress' ); ?></h3>
<?php
settings_fields( 'sportspress_events' );
do_settings_sections( 'sportspress_events' );
submit_button();

$args = array(
	'post_type' => 'sp_result',
	'numberposts' => -1,
	'posts_per_page' => -1,
		'orderby' => 'menu_order',
		'order' => 'ASC'
);
$data = get_posts( $args );
?>
<h3 class="title"><?php _e( 'Results', 'sportspress' ); ?></h3>
<table class="widefat sp-admin-config-table">
	<thead>
		<tr>
			<th><?php _e( 'Label', 'sportspress' ); ?></th>
			<th><?php _e( 'Key', 'sportspress' ); ?></th>
		</tr>
	</thead>
	<?php $i = 0; foreach ( $data as $row ): ?>
		<tr<?php if ( $i % 2 == 0 ) echo ' class="alternate"'; ?>>
			<td class="row-title"><?php echo $row->post_title; ?></td>
			<td><?php echo $row->post_name; ?>for / <?php echo $row->post_name; ?>against</td>
		</tr>
	<?php $i++; endforeach; ?>
	<tfoot>
		<tr>
			<th colspan="2"><a href="<?php echo admin_url( 'edit.php?post_type=sp_result' ); ?>"><?php _e( 'Edit Results', 'sportspress' ); ?></a></th>
		</tr>
	</tfoot>
</table>

<?php
$args = array(
	'post_type' => 'sp_outcome',
	'numberposts' => -1,
	'posts_per_page' => -1,
		'orderby' => 'menu_order',
		'order' => 'ASC'
);
$data = get_posts( $args );
?>
<h3 class="title"><?php _e( 'Outcomes', 'sportspress' ); ?></h3>
<table class="widefat sp-admin-config-table">
	<thead>
		<tr>
			<th><?php _e( 'Label', 'sportspress' ); ?></th>
			<th><?php _e( 'Key', 'sportspress' ); ?></th>
		</tr>
	</thead>
	<?php $i = 0; foreach ( $data as $row ): ?>
		<tr<?php if ( $i % 2 == 0 ) echo ' class="alternate"'; ?>>
			<td class="row-title"><?php echo $row->post_title; ?></td>
			<td><?php echo $row->post_name; ?></td>
		</tr>
	<?php $i++; endforeach; ?>
	<tfoot>
		<tr>
			<th colspan="2"><a href="<?php echo admin_url( 'edit.php?post_type=sp_outcome' ); ?>"><?php _e( 'Edit Outcomes', 'sportspress' ); ?></a></th>
		</tr>
	</tfoot>
</table>