<?php
class SportsPressTeamSettingsPage {
	public function __construct() {
		global $sportspress_options;
		$this->options =& $sportspress_options;
		add_action( 'admin_init', array( $this, 'page_init' ), 1 );
	}

	function page_init() {
		register_setting(
			'sportspress_teams',
			'sportspress',
			'sportspress_options_validate'
		);
		
		add_settings_section(
			'table',
			__( 'League Table Options', 'sportspress' ),
			'',
			'sportspress_teams'
		);
		
		add_settings_field(	
			'teams',
			__( 'Teams', 'sportspress' ),
			array( $this, 'tables_callback' ),
			'sportspress_teams',
			'table'
		);
		
		add_settings_field(	
			'columns',
			__( 'Columns', 'sportspress' ),
			array( $this, 'columns_callback' ),
			'sportspress_teams',
			'table'
		);
	}

	function tables_callback() {
		$show_team_logo = sportspress_array_value( $this->options, 'league_table_show_team_logo', false );
		$link_posts = sportspress_array_value( $this->options, 'league_table_link_posts', false );
		?>
		<fieldset>
			<label for="sportspress_league_table_show_team_logo">
				<input id="sportspress_league_table_show_team_logo_default" name="sportspress[league_table_show_team_logo]" type="hidden" value="0">
				<input id="sportspress_league_table_show_team_logo" name="sportspress[league_table_show_team_logo]" type="checkbox" value="1" <?php checked( $show_team_logo ); ?>>
				<?php _e( 'Display logos', 'sportspress' ); ?>
			</label>
		</fieldset>
		<fieldset>
			<label for="sportspress_league_table_link_posts">
				<input id="sportspress_league_table_link_posts_default" name="sportspress[league_table_link_posts]" type="hidden" value="0">
				<input id="sportspress_league_table_link_posts" name="sportspress[league_table_link_posts]" type="checkbox" value="1" <?php checked( $link_posts ); ?>>
				<?php _e( 'Display teams as links', 'sportspress' ); ?>
			</label>
		</fieldset>
		<?php
	}

	function columns_callback() {
		$args = array(
			'post_type' => 'sp_column',
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
						<th scope="col"><?php _e( 'Equation', 'sportspress' ); ?></th>
						<th scope="col"><?php _e( 'Rounding', 'sportspress' ); ?></th>
						<th scope="col"><?php _e( 'Sort Order', 'sportspress' ); ?></th>
					</tr>
				</thead>
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
		</fieldset>
		<?php
	}
}

if ( is_admin() )
	$sportspress_team_settings_page = new SportsPressTeamSettingsPage();
