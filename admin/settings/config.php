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
<h3 class="title"><?php _e( 'Table Columns', 'sportspress' ); ?></h3>
<table class="widefat sp-config-table">
	<thead>
		<tr>
			<th><?php _e( 'Label', 'sportspress' ); ?></th>
			<th><?php _e( 'Key', 'sportspress' ); ?></th>
			<th><?php _e( 'Format', 'sportspress' ); ?></th>
			<th><?php _e( 'Equation', 'sportspress' ); ?></th>
			<th><?php _e( 'Sort Order', 'sportspress' ); ?></th>
		</tr>
	</thead>
	<?php $i = 0; foreach ( $data as $row ): ?>
		<tr<?php if ( $i % 2 == 0 ) echo ' class="alternate"'; ?>>
			<td class="row-title"><?php echo $row->post_title; ?></td>
			<td><?php echo $row->post_name; ?></td>
			<td><?php echo sportspress_get_post_format( $row->ID ); ?></td>
			<td><?php echo sportspress_get_post_equation( $row->ID ); ?></td>
			<td><?php echo sportspress_get_post_order( $row->ID ); ?></td>
		</tr>
	<?php $i++; endforeach; ?>
	<tfoot>
		<tr>
			<th colspan="5"><a href="<?php echo admin_url( 'edit.php?post_type=sp_column' ); ?>"><?php printf( __( 'Edit %s', 'sportspress' ), __( 'Table Columns', 'sportspress' ) ); ?></a></th>
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
<table class="widefat sp-config-table">
	<thead>
		<tr>
			<th><?php _e( 'Label', 'sportspress' ); ?></th>
			<th><?php _e( 'Key', 'sportspress' ); ?></th>
			<th><?php _e( 'Format', 'sportspress' ); ?></th>
			<th><?php _e( 'Equation', 'sportspress' ); ?></th>
			<th><?php _e( 'Sort Order', 'sportspress' ); ?></th>
		</tr>
	</thead>
	<?php $i = 0; foreach ( $data as $row ): ?>
		<tr<?php if ( $i % 2 == 0 ) echo ' class="alternate"'; ?>>
			<td class="row-title"><?php echo $row->post_title; ?></td>
			<td><?php echo $row->post_name; ?></td>
			<td><?php echo sportspress_get_post_format( $row->ID ); ?></td>
			<td><?php echo sportspress_get_post_equation( $row->ID ); ?></td>
			<td><?php echo sportspress_get_post_order( $row->ID ); ?></td>
		</tr>
	<?php $i++; endforeach; ?>
	<tfoot>
		<tr>
			<th colspan="5"><a href="<?php echo admin_url( 'edit.php?post_type=sp_statistic' ); ?>"><?php printf( __( 'Edit %s', 'sportspress' ), __( 'Statistics', 'sportspress' ) ); ?></a></th>
		</tr>
	</tfoot>
</table>

<?php
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
<table class="widefat sp-config-table">
	<thead>
		<tr>
			<th><?php _e( 'Label', 'sportspress' ); ?></th>
			<th><?php _e( 'Key', 'sportspress' ); ?></th>
			<th><?php _e( 'Format', 'sportspress' ); ?></th>
			<th><?php _e( 'Equation', 'sportspress' ); ?></th>
			<th><?php _e( 'Sort Order', 'sportspress' ); ?></th>
		</tr>
	</thead>
	<?php $i = 0; foreach ( $data as $row ): ?>
		<tr<?php if ( $i % 2 == 0 ) echo ' class="alternate"'; ?>>
			<td class="row-title"><?php echo $row->post_title; ?></td>
			<td><?php echo $row->post_name; ?></td>
			<td><?php echo sportspress_get_post_format( $row->ID ); ?></td>
			<td>—</td>
			<td>—</td>
		</tr>
	<?php $i++; endforeach; ?>
	<tfoot>
		<tr>
			<th colspan="5"><a href="<?php echo admin_url( 'edit.php?post_type=sp_result' ); ?>"><?php printf( __( 'Edit %s', 'sportspress' ), __( 'Results', 'sportspress' ) ); ?></a></th>
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
<table class="widefat sp-config-table">
	<thead>
		<tr>
			<th><?php _e( 'Label', 'sportspress' ); ?></th>
			<th><?php _e( 'Key', 'sportspress' ); ?></th>
			<th><?php _e( 'Format', 'sportspress' ); ?></th>
			<th><?php _e( 'Equation', 'sportspress' ); ?></th>
			<th><?php _e( 'Sort Order', 'sportspress' ); ?></th>
		</tr>
	</thead>
	<?php $i = 0; foreach ( $data as $row ): ?>
		<tr<?php if ( $i % 2 == 0 ) echo ' class="alternate"'; ?>>
			<td class="row-title"><?php echo $row->post_title; ?></td>
			<td><?php echo $row->post_name; ?></td>
			<td>—</td>
			<td>—</td>
			<td>—</td>
		</tr>
	<?php $i++; endforeach; ?>
	<tfoot>
		<tr>
			<th colspan="5"><a href="<?php echo admin_url( 'edit.php?post_type=sp_outcome' ); ?>"><?php printf( __( 'Edit %s', 'sportspress' ), __( 'Outcomes', 'sportspress' ) ); ?></a></th>
		</tr>
	</tfoot>
</table>