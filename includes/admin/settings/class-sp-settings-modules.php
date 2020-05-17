<?php
/**
 * SportsPress Module Settings
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin
 * @version		2.6
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'SP_Settings_Modules' ) ) :

/**
 * SP_Settings_Modules
 */
class SP_Settings_Modules extends SP_Settings_Page {

	/**
	 * @var array
	 */
	public $sections = array();

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->id    = 'modules';
		$this->label = __( 'Modules', 'sportspress' );

		$this->sections = apply_filters( 'sportspress_module_sections', array(
			'general' => __( 'General', 'sportspress' ),
			'event' => __( 'Events', 'sportspress' ),
			'team' => __( 'Teams', 'sportspress' ),
			'player_staff' => __( 'Players', 'sportspress' ) . ' &amp; ' . __( 'Staff', 'sportspress' ),
			'admin' => __( 'Dashboard', 'sportspress' ),
			'other' => __( 'Other', 'sportspress' ),
		));

		add_filter( 'sportspress_settings_tabs_array', array( $this, 'add_settings_page' ), 20 );
		add_action( 'sportspress_settings_' . $this->id, array( $this, 'output' ) );
		add_action( 'sportspress_settings_save_' . $this->id, array( $this, 'save' ) );
	}

	/**
	 * Output modules
	 *
	 * @access public
	 * @return void
	 */
	public function output() {
		?>
		<div class="sp-modules-wrapper">
			<div class="sp-modules-sidebar">
				<?php do_action( 'sportspress_modules_sidebar' ); ?>

				<?php if ( ! class_exists( 'SportsPress_Pro' ) ) { ?>
				<table class="widefat" cellspacing="0">
					<thead>
						<tr><th>
							<a href="<?php echo apply_filters( 'sportspress_pro_url', 'http://tboy.co/pro' ); ?>" target="_blank"><img src="<?php echo plugin_dir_url( SP_PLUGIN_FILE ); ?>/assets/images/modules/sportspress-pro.png" alt="<?php _e( 'SportsPress Pro', 'sportspress' ); ?>" width="174"></a>
						</th></tr>
					</thead>
					<tbody>
						<tr><td>
							<p><?php _e( 'Get SportsPress Pro to get access to all modules. You can upgrade any time without losing any of your data.','sportspress' ); ?></p>
							<p class="sp-module-actions">
								<span><?php _e( 'Premium', 'sportspress' ); ?></span>
								<a class="button button-primary" href="<?php echo apply_filters( 'sportspress_pro_url', 'http://tboy.co/pro' ); ?>" target="_blank"><?php _e( 'Upgrade Now', 'sportspress' ); ?></a>
							</p>
						</td></tr>
					</tbody>
				</table>
				<?php } ?>

				<?php if ( ! class_exists( 'SportsPress_Twitter' ) || ! class_exists( 'SportsPress_Facebook' ) ) { ?>
				<table class="widefat" cellspacing="0">
					<thead>
						<tr><th>
							<strong><?php _e( 'Get Freebies', 'sportspress' ); ?></strong>
						</th></tr>
					</thead>
					<tbody>
						<tr><td>
							<p><?php _e( 'Instant access to exclusive SportsPress extensions and free downloads.', 'sportspress' ); ?></p>
							<p class="sp-module-actions">
								<span><?php _e( 'Create a free account', 'sportspress' ); ?></span>
								<a class="button" href="http://tboy.co/account" target="_blank"><?php _e( 'Sign Up', 'sportspress' ); ?></a>
							</p>
						</td></tr>
					</tbody>
				</table>
				<?php } ?>

				<?php if ( current_user_can( 'install_themes' ) ) { ?>
					<?php $theme = wp_get_theme(); ?>
					<?php if ( 'rookie' == $theme->stylesheet ) { ?>
						<table class="widefat" cellspacing="0">
							<thead>
								<tr><th>
									<strong><?php _e( 'Current Theme', 'sportspress' ); ?></strong>
								</th></tr>
							</thead>
							<tbody>
								<tr><td>
									<img src="<?php echo $theme->get_screenshot(); ?>" class="sp-theme-screenshot">
									<p><?php _e( 'Rookie is a free starter theme for SportsPress designed by ThemeBoy.', 'sportspress' ); ?></p>
									<p class="sp-module-actions">
										<span><?php _e( 'Need a better theme?', 'sportspress' ); ?></span>
										<a class="button" href="<?php echo apply_filters( 'sportspress_pro_url', 'http://tboy.co/themes' ); ?>" target="_blank"><?php _e( 'Upgrade', 'sportspress' ); ?></a>
									</p>
								</td></tr>
							</tbody>
						</table>
					<?php } elseif ( ! current_theme_supports( 'sportspress' ) ) { ?>
						<table class="widefat" cellspacing="0">
							<thead>
								<tr><th>
									<strong><?php _e( 'Free SportsPress Theme', 'sportspress' ); ?></strong>
								</th></tr>
							</thead>
							<tbody>
								<tr><td>
									<img src="<?php echo plugin_dir_url( SP_PLUGIN_FILE ); ?>/assets/images/welcome/rookie.png" class="sp-theme-screenshot">
									<p><?php _e( 'Rookie is a free starter theme for SportsPress designed by ThemeBoy.', 'sportspress' ); ?></p>
									<p class="sp-module-actions">
										<span><?php _e( 'Free', 'sportspress' ); ?></span>
										<a class="button" href="<?php echo add_query_arg( array( 'theme' => 'rookie' ), network_admin_url( 'theme-install.php' ) ); ?>"><?php _e( 'Install Now', 'sportspress' ); ?></a>
									</p>
								</td></tr>
							</tbody>
						</table>
					<?php } ?>
				<?php } ?>

				<?php
				$categories = array(
					'documentation' => array(
						'icon' => 'sp-icon-book',
						'label' => __( 'Documentation', 'sportspress' ),
						'links' => array(
							'http://tboy.co/installation' => __( 'Getting Started', 'sportspress' ),
							'http://tboy.co/manuals' => __( 'Manuals', 'sportspress' ),
							'http://tboy.co/videos' => __( 'Videos', 'sportspress' ),
						),
					),
					'help' => array(
						'icon' => 'dashicons dashicons-heart',
						'label' => __( 'Help', 'sportspress' ),
						'links' => array(
							'http://tboy.co/forums' => __( 'Support Forums', 'sportspress' ),
							'http://tboy.co/ideas' => __( 'Feature Requests', 'sportspress' ),
						),
					),
					'social' => array(
						'icon' => 'dashicons dashicons-share',
						'label' => __( 'Connect', 'sportspress' ),
						'links' => array(
							'http://tboy.co/twitter' => __( 'Twitter', 'sportspress' ),
							'http://tboy.co/facebook' => __( 'Facebook', 'sportspress' ),
							'http://tboy.co/youtube' => __( 'YouTube', 'sportspress' ),
							'http://tboy.co/gplus' => __( 'Google+', 'sportspress' ),
						),
					),
					'developers' => array(
						'icon' => 'dashicons dashicons-editor-code',
						'label' => __( 'Developers', 'sportspress' ),
						'links' => array(
							'http://tboy.co/developers' => __( 'Reference', 'sportspress' ),
							'http://tboy.co/slack' => __( 'Slack', 'sportspress' ),
							'http://tboy.co/github' => __( 'GitHub', 'sportspress' ),
						),
					),
				);
				
				if ( class_exists( 'SportsPress_Pro' ) ) {
					$categories['help']['links']['http://support.themeboy.com/'] = __( 'Premium Support', 'sportspress' );
				} else {
					$categories['help']['links'][ apply_filters( 'sportspress_pro_url', 'http://tboy.co/pro' ) ] = '<span class="sp-desc-tip" title="' . __( 'Upgrade to Pro', 'sportspress' ) . '">' . __( 'Premium Support', 'sportspress' ) . '</span>';
				}
				
				$categories = apply_filters( 'sportspress_modules_welcome_links', $categories );
				
				if ( sizeof( $categories ) ) {
					?>
					<table class="widefat" cellspacing="0">
						<thead>
							<tr><th>
								<strong><?php _e( 'Welcome to SportsPress', 'sportspress' ); ?></strong>
							</th></tr>
						</thead>
						<tbody>
							<tr><td>
								<?php foreach ( $categories as $slug => $category ) { ?>
									<p><strong><i class="<?php echo $category['icon']; ?>"></i> <?php echo $category['label']; ?></strong></p>
									<ul class="sp-<?php echo $slug; ?>-links">
										<?php foreach ( $category['links'] as $url => $text ) { ?>
											<li><a href="<?php echo $url; ?>" target="_blank"><?php echo $text; ?></a></li>
										<?php } ?>
									</ul>
								<?php } ?>
							</td></tr>
						</tbody>
					</table>
				<?php } ?>

				<?php do_action( 'sportspress_modules_after_sidebar' ); ?>
			</div>

			<div class="sp-modules-main">
				<?php foreach ( SP()->modules->data as $section => $modules ) { ?>
				<table class="sp-modules-table widefat" cellspacing="0">
					<thead>
						<tr><th>
							<?php echo sp_array_value( $this->sections, $section, __( 'Modules', 'sportspress' ) ); ?>
						</th></tr>
					</thead>
					<tbody>
						<?php foreach ( $modules as $id => $module ) { ?>
							<?php if ( isset( $module['class'] ) && ! class_exists( $module['class'] ) ) { ?>
							<tr class="sp-module-unavailable"><td>
								<input type="checkbox" disabled="disabled">
								<span class="sp-desc-tip" title="<?php echo sp_array_value( $module, 'tip', __( 'Upgrade to Pro', 'sportspress' ) ); ?>">
									<i class="<?php echo sp_array_value( $module, 'icon', 'dashicons dashicons-admin-generic' ); ?>"></i>
									<?php echo sp_array_value( $module, 'label', $id ); ?>
								</span>
								<?php if ( isset( $module['desc'] ) ) { ?>
									<span class="sp-desc">
										<?php echo $module['desc']; ?>
										<?php if ( array_key_exists( 'link', $module ) ) { ?>
											<a href="<?php echo apply_filters( 'sportspress_pro_url', $module['link'] ); ?>" target="_blank"><?php echo sp_array_value( $module, 'action', __( 'Learn more', 'sportspress' ) ); ?></a>
										<?php } ?>
									</span>
								<?php } ?>
							</td></tr>
							<?php } else { ?>
							<tr><td>
								<input type="checkbox" name="sportspress_load_<?php echo $id; ?>_module" id="sportspress_load_<?php echo $id; ?>_module" <?php checked( 'yes' == get_option( 'sportspress_load_' . $id . '_module', sp_array_value( $module, 'default', 'yes' ) ) ); ?>>
								<label for="sportspress_load_<?php echo $id; ?>_module">
									<i class="<?php echo sp_array_value( $module, 'icon', 'dashicons dashicons-admin-generic' ); ?>"></i>
									<?php echo sp_array_value( $module, 'label', $id ); ?>
								</label>
								<?php if ( isset( $module['desc'] ) ) { ?>
									<span class="sp-desc"><?php echo $module['desc']; ?></span>
								<?php } ?>
							</td></tr>
							<?php } ?>
						<?php } ?>
					</tbody>
				</table>
				<?php } ?>

				<p class="submit">
			    	<input name="save" class="button button-primary" type="submit" value="<?php _e( 'Save Changes', 'sportspress' ); ?>" />
			    	<?php $GLOBALS['hide_save_button'] = true; ?>
			    </p>
			</div>
		</div>

		<input type="hidden" name="sportspress_update_modules" value="1">

		<?php if ( isset( $_POST[ 'sportspress_update_modules' ] ) ) { ?>
		<script type="text/javascript">
		window.onload = function() {
			window.location = window.location.href;
		}
		</script>
		<?php
		}
		flush_rewrite_rules();
	}

	/**
	 * Save settings
	 */
	public function save() {
		foreach ( SP()->modules->data as $sections => $modules ) {
			foreach ( $modules as $id => $module ) {
				$name = 'sportspress_load_' . $id . '_module';
				update_option( $name, isset( $_POST[ $name ] ) ? 'yes' : 'no' );
			}
		}
		flush_rewrite_rules();
	}
}

endif;

return new SP_Settings_Modules();
