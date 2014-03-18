<?php
class SportsPressPlayerSettingsPage {
	public function __construct() {
		global $sportspress_options;
		$this->options =& $sportspress_options;
		add_action( 'admin_init', array( $this, 'page_init' ), 1 );
	}

	function page_init() {
		register_setting(
			'sportspress_players',
			'sportspress',
			'sportspress_options_validate'
		);
		
		add_settings_section(
			'player',
			__( 'Player Options', 'sportspress' ),
			'',
			'sportspress_players'
		);
		
		add_settings_section(
			'list',
			__( 'Player List Options', 'sportspress' ),
			'',
			'sportspress_players'
		);
		
		add_settings_field(	
			'nationality',
			__( 'Nationality', 'sportspress' ),
			array( $this, 'nationality_callback' ),
			'sportspress_players',
			'player'
		);
		
		add_settings_field(	
			'list',
			__( 'List', 'sportspress' ),
			array( $this, 'list_callback' ),
			'sportspress_players',
			'list'
		);
		
		add_settings_field(	
			'gallery',
			__( 'Gallery', 'sportspress' ),
			array( $this, 'gallery_callback' ),
			'sportspress_players',
			'list'
		);
		
		add_settings_field(	
			'metrics',
			__( 'Metrics', 'sportspress' ),
			array( $this, 'metrics_callback' ),
			'sportspress_players',
			'list'
		);
		
		add_settings_field(	
			'statistics',
			__( 'Statistics', 'sportspress' ),
			array( $this, 'statistics_callback' ),
			'sportspress_players',
			'list'
		);
	}

	function nationality_callback() {
		$show_nationality_flag = sportspress_array_value( $this->options, 'player_show_nationality_flag', true );
		?>
		<fieldset>
			<label for="sportspress_player_show_nationality_flag">
				<input id="sportspress_player_show_nationality_flag_default" name="sportspress[player_show_nationality_flag]" type="hidden" value="0">
				<input id="sportspress_player_show_nationality_flag" name="sportspress[player_show_nationality_flag]" type="checkbox" value="1" <?php checked( $show_nationality_flag ); ?>>
				<?php _e( 'Display national flags', 'sportspress' ); ?>
			</label>
		</fieldset>
		<?php
	}

	function list_callback() {
		$link_posts = sportspress_array_value( $this->options, 'player_list_link_posts', true );
		?>
		<fieldset>
			<label for="sportspress_player_list_link_posts">
				<input id="sportspress_player_list_link_posts_default" name="sportspress[player_list_link_posts]" type="hidden" value="0">
				<input id="sportspress_player_list_link_posts" name="sportspress[player_list_link_posts]" type="checkbox" value="1" <?php checked( $link_posts ); ?>>
				<?php _e( 'Display players as links', 'sportspress' ); ?>
			</label>
		</fieldset>
		<?php
	}

	function gallery_callback() {
		$show_names_on_hover = sportspress_array_value( $this->options, 'player_gallery_show_names_on_hover', true );
		?>
		<fieldset>
			<label for="sportspress_player_gallery_show_names_on_hover">
				<input id="sportspress_player_gallery_show_names_on_hover_default" name="sportspress[player_gallery_show_names_on_hover]" type="hidden" value="0">
				<input id="sportspress_player_gallery_show_names_on_hover" name="sportspress[player_gallery_show_names_on_hover]" type="checkbox" value="1" <?php checked( $show_names_on_hover ); ?>>
				<?php _e( 'Display player names on hover', 'sportspress' ); ?>
			</label>
		</fieldset>
		<?php
	}

	function metrics_callback() {
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

	function statistics_callback() {
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
}

if ( is_admin() )
	$sportspress_player_settings_page = new SportsPressPlayerSettingsPage();
