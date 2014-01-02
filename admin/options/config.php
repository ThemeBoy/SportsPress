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
<table class="widefat">
	<thead>
		<tr>
			<th><?php _e( 'Label', 'sportspress' ); ?></th>
			<th><?php _e( 'Equation', 'sportspress' ); ?></th>
			<th><?php _e( 'Sort Order', 'sportspress' ); ?></th>
		</tr>
	</thead>
	<?php $i = 0; foreach ( $data as $row ): ?>
		<tr>
			<td class="row-title"><?php echo $row->post_title; ?></td>
			<td>
				<?php
					$equation = get_post_meta ( $row->ID, 'sp_equation', true );
					if ( $equation ):
						echo str_replace(
							array( '$', '+', '-', '*', '/' ),
							array( '', '&plus;', '&minus;', '&times;', '&divide' ),
							$equation
						);
					else:
						echo '—';
					endif;
				?>
			</td>
			<td>
				<?php
					$priority = get_post_meta ( $row->ID, 'sp_priority', true );
					if ( $priority ):
						echo $priority . ' ' . str_replace(
							array( 'DESC', 'ASC' ),
							array( '&darr;', '&uarr;' ),
							get_post_meta ( $row->ID, 'sp_order', true )
						);
					else:
						echo '—';
					endif;
				?>
			</td>
		</tr>
	<?php $i++; endforeach; ?>
	<tfoot>
		<tr>
			<th colspan="3"><a href="<?php echo admin_url( 'edit.php?post_type=sp_column' ); ?>"><?php printf( __( 'Edit %s', 'sportspress' ), __( 'Table Columns', 'sportspress' ) ); ?></a></th>
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
<table class="widefat">
	<thead>
		<tr>
			<th><?php _e( 'Label', 'sportspress' ); ?></th>
			<th><?php _e( 'Equation', 'sportspress' ); ?></th>
			<th><?php _e( 'Sort Order', 'sportspress' ); ?></th>
		</tr>
	</thead>
	<?php $i = 0; foreach ( $data as $row ): ?>
		<tr>
			<td class="row-title"><?php echo $row->post_title; ?></td>
			<td>
				<?php
					$equation = get_post_meta ( $row->ID, 'sp_equation', true );
					if ( $equation ):
						echo str_replace(
							array( '$', '+', '-', '*', '/' ),
							array( '', '&plus;', '&minus;', '&times;', '&divide' ),
							$equation
						);
					else:
						echo '—';
					endif;
				?>
			</td>
			<td>
				<?php
					$priority = get_post_meta ( $row->ID, 'sp_priority', true );
					if ( $priority ):
						echo $priority . ' ' . str_replace(
							array( 'DESC', 'ASC' ),
							array( '&darr;', '&uarr;' ),
							get_post_meta ( $row->ID, 'sp_order', true )
						);
					else:
						echo '—';
					endif;
				?>
			</td>
		</tr>
	<?php $i++; endforeach; ?>
	<tfoot>
		<tr>
			<th colspan="3"><a href="<?php echo admin_url( 'edit.php?post_type=sp_column' ); ?>"><?php printf( __( 'Edit %s', 'sportspress' ), __( 'Statistics', 'sportspress' ) ); ?></a></th>
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
<table class="widefat">
	<thead>
		<tr>
			<th><?php _e( 'Label', 'sportspress' ); ?></th>
			<th><?php _e( 'Key', 'sportspress' ); ?></th>
		</tr>
	</thead>
	<?php $i = 0; foreach ( $data as $row ): ?>
		<tr<?php if ( $i % 2 ) echo ' class="alternate"'; ?>>
			<td class="row-title"><?php echo $row->post_title; ?></td>
			<td><?php echo $row->post_name; ?></td>
		</tr>
	<?php $i++; endforeach; ?>
	<tfoot>
		<tr>
			<th colspan="2"><a href="<?php echo admin_url( 'edit.php?post_type=sp_result' ); ?>"><?php printf( __( 'Edit %s', 'sportspress' ), __( 'Results', 'sportspress' ) ); ?></a></th>
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
<table class="widefat">
	<thead>
		<tr>
			<th><?php _e( 'Label', 'sportspress' ); ?></th>
			<th><?php _e( 'Key', 'sportspress' ); ?></th>
		</tr>
	</thead>
	<?php $i = 0; foreach ( $data as $row ): ?>
		<tr<?php if ( $i % 2 ) echo ' class="alternate"'; ?>>
			<td class="row-title"><?php echo $row->post_title; ?></td>
			<td><?php echo $row->post_name; ?></td>
		</tr>
	<?php $i++; endforeach; ?>
	<tfoot>
		<tr>
			<th colspan="2"><a href="<?php echo admin_url( 'edit.php?post_type=sp_outcome' ); ?>"><?php printf( __( 'Edit %s', 'sportspress' ), __( 'Outcomes', 'sportspress' ) ); ?></a></th>
		</tr>
	</tfoot>
</table>