<?php
function sportspress_player_settings_nationality_callback() {
	$options = get_option( 'sportspress' );

	$show_nationality_flag = sportspress_array_value( $options, 'player_show_nationality_flag', true );
	?>
	<fieldset>
		<label for="sportspress_player_show_nationality_flag">
			<input id="sportspress_player_show_nationality_flag_default" name="sportspress[player_show_nationality_flag]" type="hidden" value="0">
			<input id="sportspress_player_show_nationality_flag" name="sportspress[player_show_nationality_flag]" type="checkbox" value="1" <?php checked( $show_nationality_flag ); ?>>
			<?php _e( 'Display flag', 'sportspress' ); ?>
		</label>
	</fieldset>
	<?php
}

function sportspress_player_settings_metrics_callback() {
	$args = array(
		'post_type' => 'sp_metric',
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
					<th scope="col"><?php _e( 'Positions', 'sportspress' ); ?></th>
					<th scope="col">&nbsp;</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th scope="col"><?php _e( 'Label', 'sportspress' ); ?></th>
					<th scope="col"><?php _e( 'Positions', 'sportspress' ); ?></th>
					<th scope="col">&nbsp;</th>
				</tr>
			</tfoot>
			<?php $i = 0; foreach ( $data as $row ): ?>
				<tr<?php if ( $i % 2 == 0 ) echo ' class="alternate"'; ?>>
					<td class="row-title"><?php echo $row->post_title; ?></td>
					<td><?php echo get_the_terms ( $row->ID, 'sp_position' ) ? the_terms( $row->ID, 'sp_position' ) : '&mdash;'; ?></td>
					<td>&nbsp;</td>
				</tr>
			<?php $i++; endforeach; ?>
		</table>
		<div class="tablenav bottom">
			<div class="alignleft actions">
				<a class="button" id="doaction" href="<?php echo admin_url( 'edit.php?post_type=sp_metric' ); ?>"><?php _e( 'Edit', 'sportspress' ); ?></a>
				<a class="button" id="doaction2" href="<?php echo admin_url( 'post-new.php?post_type=sp_metric' ); ?>"><?php _e( 'Add New', 'sportspress' ); ?></a>
			</div>
			<br class="clear">
		</div>
	</fieldset>
	<?
}

function sportspress_player_settings_statistics_callback() {
	$args = array(
		'post_type' => 'sp_statistic',
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
					<th scope="col"><?php _e( 'Positions', 'sportspress' ); ?></th>
					<th scope="col"><?php _e( 'Calculate', 'sportspress' ); ?></th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th scope="col"><?php _e( 'Label', 'sportspress' ); ?></th>
					<th scope="col"><?php _e( 'Positions', 'sportspress' ); ?></th>
					<th scope="col"><?php _e( 'Calculate', 'sportspress' ); ?></th>
				</tr>
			</tfoot>
			<?php $i = 0; foreach ( $data as $row ): ?>
				<tr<?php if ( $i % 2 == 0 ) echo ' class="alternate"'; ?>>
					<td class="row-title"><?php echo $row->post_title; ?></td>
					<td><?php echo get_the_terms ( $row->ID, 'sp_position' ) ? the_terms( $row->ID, 'sp_position' ) : '&mdash;'; ?></td>
					<td><?php echo sportspress_get_post_calculate( $row->ID ); ?></td>
				</tr>
			<?php $i++; endforeach; ?>
		</table>
		<div class="tablenav bottom">
			<div class="alignleft actions">
				<a class="button" id="doaction" href="<?php echo admin_url( 'edit.php?post_type=sp_statistic' ); ?>"><?php _e( 'Edit', 'sportspress' ); ?></a>
				<a class="button" id="doaction2" href="<?php echo admin_url( 'post-new.php?post_type=sp_statistic' ); ?>"><?php _e( 'Add New', 'sportspress' ); ?></a>
			</div>
			<br class="clear">
		</div>
	</fieldset>
	<?php
}

function sportspress_player_settings_init() {
	register_setting(
		'sportspress_players',
		'sportspress',
		'sportspress_options_validate'
	);
	
	add_settings_section(
		'players',
		'',
		'',
		'sportspress_players'
	);
	
	add_settings_field(	
		'nationality',
		__( 'Nationality', 'sportspress' ),
		'sportspress_player_settings_nationality_callback',	
		'sportspress_players',
		'players'
	);
	
	add_settings_field(	
		'metrics',
		__( 'Metrics', 'sportspress' ),
		'sportspress_player_settings_metrics_callback',	
		'sportspress_players',
		'players'
	);
	
	add_settings_field(	
		'statistics',
		__( 'Statistics', 'sportspress' ),
		'sportspress_player_settings_statistics_callback',	
		'sportspress_players',
		'players'
	);
}
add_action( 'admin_init', 'sportspress_player_settings_init', 1 );
