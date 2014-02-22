<?php
$args = array(
	'post_type' => 'sp_column',
	'numberposts' => -1,
	'posts_per_page' => -1,
		'orderby' => 'menu_order',
		'order' => 'ASC'
);
$data = get_posts( $args );
?>
<h3 class="title"><?php _e( 'Columns', 'sportspress' ); ?></h3>
<table class="widefat sp-admin-config-table">
	<thead>
		<tr>
			<th scope="col"><?php _e( 'Label', 'sportspress' ); ?></th>
			<th scope="col"><?php _e( 'Key', 'sportspress' ); ?></th>
			<th scope="col"><?php _e( 'Equation', 'sportspress' ); ?></th>
			<th scope="col"><?php _e( 'Rounding', 'sportspress' ); ?></th>
			<th scope="col"><?php _e( 'Sort Order', 'sportspress' ); ?></th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th scope="col"><?php _e( 'Label', 'sportspress' ); ?></th>
			<th scope="col"><?php _e( 'Key', 'sportspress' ); ?></th>
			<th scope="col"><?php _e( 'Equation', 'sportspress' ); ?></th>
			<th scope="col"><?php _e( 'Rounding', 'sportspress' ); ?></th>
			<th scope="col"><?php _e( 'Sort Order', 'sportspress' ); ?></th>
		</tr>
	</tfoot>
	<?php $i = 0; foreach ( $data as $row ): ?>
		<tr<?php if ( $i % 2 == 0 ) echo ' class="alternate"'; ?>>
			<td class="row-title"><?php echo $row->post_title; ?></td>
			<td><?php echo $row->post_name; ?></td>
			<td><?php echo sportspress_get_post_equation( $row->ID, $row->post_name ); ?></td>
			<td><?php echo sportspress_get_post_precision( $row->ID ); ?></td>
			<td><?php echo sportspress_get_post_order( $row->ID ); ?></td>
		</tr>
	<?php $i++; endforeach; ?>
</table>
<div class="tablenav bottom">
	<div class="alignleft actions">
		<a class="button" id="doaction" href="<?php echo admin_url( 'edit.php?post_type=sp_column' ); ?>"><?php _e( 'Edit', 'sportspress' ); ?></a>
		<a class="button" id="doaction2" href="<?php echo admin_url( 'post-new.php?post_type=sp_column' ); ?>"><?php _e( 'Add New', 'sportspress' ); ?></a>
	</div>
	<br class="clear">
</div>