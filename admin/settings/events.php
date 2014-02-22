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
			<th scope="col"><?php _e( 'Label', 'sportspress' ); ?></th>
			<th scope="col"><?php _e( 'Key', 'sportspress' ); ?></th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th scope="col"><?php _e( 'Label', 'sportspress' ); ?></th>
			<th scope="col"><?php _e( 'Key', 'sportspress' ); ?></th>
		</tr>
	</tfoot>
	<?php $i = 0; foreach ( $data as $row ): ?>
		<tr<?php if ( $i % 2 == 0 ) echo ' class="alternate"'; ?>>
			<td class="row-title"><?php echo $row->post_title; ?></td>
			<td><?php echo $row->post_name; ?>for / <?php echo $row->post_name; ?>against</td>
		</tr>
	<?php $i++; endforeach; ?>
</table>
<div class="tablenav bottom">
	<div class="alignleft actions">
		<a class="button" id="doaction" href="<?php echo admin_url( 'edit.php?post_type=sp_result' ); ?>"><?php _e( 'Edit', 'sportspress' ); ?></a>
		<a class="button" id="doaction2" href="<?php echo admin_url( 'post-new.php?post_type=sp_result' ); ?>"><?php _e( 'Add New', 'sportspress' ); ?></a>
	</div>
	<br class="clear">
</div>

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
			<th scope="col"><?php _e( 'Label', 'sportspress' ); ?></th>
			<th scope="col"><?php _e( 'Key', 'sportspress' ); ?></th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th scope="col"><?php _e( 'Label', 'sportspress' ); ?></th>
			<th scope="col"><?php _e( 'Key', 'sportspress' ); ?></th>
		</tr>
	</tfoot>
	<?php $i = 0; foreach ( $data as $row ): ?>
		<tr<?php if ( $i % 2 == 0 ) echo ' class="alternate"'; ?>>
			<td class="row-title"><?php echo $row->post_title; ?></td>
			<td><?php echo $row->post_name; ?></td>
		</tr>
	<?php $i++; endforeach; ?>
</table>
<div class="tablenav bottom">
	<div class="alignleft actions">
		<a class="button" id="doaction" href="<?php echo admin_url( 'edit.php?post_type=sp_outcome' ); ?>"><?php _e( 'Edit', 'sportspress' ); ?></a>
		<a class="button" id="doaction2" href="<?php echo admin_url( 'post-new.php?post_type=sp_outcome' ); ?>"><?php _e( 'Add New', 'sportspress' ); ?></a>
	</div>
	<br class="clear">
</div>