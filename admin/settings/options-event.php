<?php
function sportspress_results_callback() {
	$options = get_option( 'sportspress' );

	$main_result = sportspress_array_value( $options, 'main_result', 0 );

	$args = array(
		'post_type' => 'sp_result',
		'numberposts' => -1,
		'posts_per_page' => -1,
			'orderby' => 'menu_order',
			'order' => 'ASC'
	);
	$data = get_posts( $args );

	$default = end( $data );
	reset( $data );
	?>
	<fieldset>
		<table class="widefat sp-admin-config-table">
			<thead>
				<tr>
					<th scope="col"><?php _e( 'Primary', 'sportspress' ); ?></th>
					<th scope="col"><?php _e( 'Label', 'sportspress' ); ?></th>
					<th scope="col"><?php _e( 'Key', 'sportspress' ); ?></th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th class="radio"><input type="radio" id="sportspress_main_result_0" name="sportspress[main_result]" value="0" <?php checked( $main_result, 0 ); ?>></th>
					<th colspan="2"><label for="sportspress_main_result_0"><?php printf( __( 'Default (%s)', 'sportspress' ), $default->post_title ); ?></label></th>
				</tr>
			</tfoot>
			<?php $i = 0; foreach ( $data as $row ): ?>
				<tr<?php if ( $i % 2 == 0 ) echo ' class="alternate"'; ?>>
					<td class="radio"><input type="radio" id="sportspress_main_result_<?php echo $row->post_name; ?>" name="sportspress[main_result]" value="<?php echo $row->post_name; ?>" <?php checked( $main_result, $row->post_name ); ?>></td>
					<td class="row-title"><label for="sportspress_main_result_<?php echo $row->post_name; ?>"><?php echo $row->post_title; ?></label></td>
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
	</fieldset>
	<?php
}

function sportspress_outcomes_callback() {
	$options = get_option( 'sportspress' );

	$args = array(
		'post_type' => 'sp_outcome',
		'numberposts' => -1,
		'posts_per_page' => -1,
			'orderby' => 'menu_order',
			'order' => 'ASC'
	);
	$data = get_posts( $args );
	?>
	<fieldset>
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
	</fieldset>
	<?php
}

function sportspress_event_settings_init() {
	register_setting(
		'sportspress_events',
		'sportspress',
		'sportspress_options_validate'
	);
	
	add_settings_section(
		'events',
		'',
		'',
		'sportspress_events'
	);
	
	add_settings_field(	
		'results',
		__( 'Results', 'sportspress' ),
		'sportspress_results_callback',	
		'sportspress_events',
		'events'
	);
	
	add_settings_field(	
		'outcomes',
		__( 'Outcomes', 'sportspress' ),
		'sportspress_outcomes_callback',	
		'sportspress_events',
		'events'
	);
}
add_action( 'admin_init', 'sportspress_event_settings_init', 1 );
