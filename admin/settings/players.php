<?php
$args = array(
	'post_type' => 'sp_metric',
	'numberposts' => -1,
	'posts_per_page' => -1,
		'orderby' => 'menu_order',
		'order' => 'ASC'
);
$data = get_posts( $args );
?>
<h3 class="title"><?php _e( 'Metrics', 'sportspress' ); ?></h3>
<table class="widefat sp-admin-config-table">
	<thead>
		<tr>
			<th><?php _e( 'Label', 'sportspress' ); ?></th>
			<th><?php _e( 'Positions', 'sportspress' ); ?></th>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<?php $i = 0; foreach ( $data as $row ): ?>
		<tr<?php if ( $i % 2 == 0 ) echo ' class="alternate"'; ?>>
			<td class="row-title"><?php echo $row->post_title; ?></td>
			<td><?php echo get_the_terms ( $row->ID, 'sp_position' ) ? the_terms( $row->ID, 'sp_position' ) : sprintf( __( 'All %s', 'sportspress' ), __( 'positions', 'sportspress' ) ); ?></td>
			<td>&nbsp;</td>
		</tr>
	<?php $i++; endforeach; ?>
	<tfoot>
		<tr>
			<th colspan="4"><a href="<?php echo admin_url( 'edit.php?post_type=sp_metric' ); ?>"><?php printf( __( 'Edit %s', 'sportspress' ), __( 'Metrics', 'sportspress' ) ); ?></a></th>
		</tr>
	</tfoot>
</table>
<?php
$args = array(
	'post_type' => 'sp_statistic',
	'numberposts' => -1,
	'posts_per_page' => -1,
	'orderby' => 'menu_order',
	'order' => 'ASC'
);
$data = get_posts( $args );
?>
<h3 class="title"><?php _e( 'Statistics', 'sportspress' ); ?></h3>
<table class="widefat sp-admin-config-table">
	<thead>
		<tr>
			<th><?php _e( 'Label', 'sportspress' ); ?></th>
			<th><?php _e( 'Positions', 'sportspress' ); ?></th>
			<th><?php _e( 'Calculate', 'sportspress' ); ?></th>
		</tr>
	</thead>
	<?php $i = 0; foreach ( $data as $row ): ?>
		<tr<?php if ( $i % 2 == 0 ) echo ' class="alternate"'; ?>>
			<td class="row-title"><?php echo $row->post_title; ?></td>
			<td><?php echo get_the_terms ( $row->ID, 'sp_position' ) ? the_terms( $row->ID, 'sp_position' ) : sprintf( __( 'All %s', 'sportspress' ), __( 'positions', 'sportspress' ) ); ?></td>
			<td><?php echo sportspress_get_post_calculate( $row->ID ); ?></td>
		</tr>
	<?php $i++; endforeach; ?>
	<tfoot>
		<tr>
			<th colspan="4"><a href="<?php echo admin_url( 'edit.php?post_type=sp_statistic' ); ?>"><?php printf( __( 'Edit %s', 'sportspress' ), __( 'Statistics', 'sportspress' ) ); ?></a></th>
		</tr>
	</tfoot>
</table>