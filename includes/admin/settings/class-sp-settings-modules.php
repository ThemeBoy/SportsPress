<?php
/**
 * SportsPress Module Settings
 *
 * @author      ThemeBoy
 * @category    Admin
 * @package     SportsPress/Admin
 * @version     2.7.9
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

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
			$this->label = esc_attr__( 'Modules', 'sportspress' );

			$this->sections = apply_filters(
				'sportspress_module_sections',
				array(
					'general'      => esc_attr__( 'General', 'sportspress' ),
					'event'        => esc_attr__( 'Events', 'sportspress' ),
					'team'         => esc_attr__( 'Teams', 'sportspress' ),
					'player_staff' => esc_attr__( 'Players', 'sportspress' ) . ' &amp; ' . esc_attr__( 'Staff', 'sportspress' ),
					'admin'        => esc_attr__( 'Dashboard', 'sportspress' ),
					'other'        => esc_attr__( 'Other', 'sportspress' ),
				)
			);

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
							<a href="<?php echo esc_url( apply_filters( 'sportspress_pro_url', 'http://tboy.co/pro' ) ); ?>" target="_blank"><img src="<?php echo esc_url( plugin_dir_url( SP_PLUGIN_FILE ) ); ?>/assets/images/modules/sportspress-pro.png" alt="<?php esc_html_e( 'SportsPress Pro', 'sportspress' ); ?>" width="174"></a>
						</th></tr>
					</thead>
					<tbody>
						<tr><td>
							<p><?php esc_html_e( 'Get SportsPress Pro to get access to all modules. You can upgrade any time without losing any of your data.', 'sportspress' ); ?></p>
							<p class="sp-module-actions">
								<span><?php esc_html_e( 'Premium', 'sportspress' ); ?></span>
								<a class="button button-primary" href="<?php echo esc_url( apply_filters( 'sportspress_pro_url', 'http://tboy.co/pro' ) ); ?>" target="_blank"><?php esc_html_e( 'Upgrade Now', 'sportspress' ); ?></a>
							</p>
						</td></tr>
					</tbody>
				</table>
				<?php } ?>

				<?php if ( ! class_exists( 'SportsPress_Twitter' ) || ! class_exists( 'SportsPress_Facebook' ) ) { ?>
				<table class="widefat" cellspacing="0">
					<thead>
						<tr><th>
							<strong><?php esc_html_e( 'Get Freebies', 'sportspress' ); ?></strong>
						</th></tr>
					</thead>
					<tbody>
						<tr><td>
							<p><?php esc_html_e( 'Instant access to exclusive SportsPress extensions and free downloads.', 'sportspress' ); ?></p>
							<p class="sp-module-actions">
								<span><?php esc_html_e( 'Create a free account', 'sportspress' ); ?></span>
								<a class="button" href="http://tboy.co/account" target="_blank"><?php esc_html_e( 'Sign Up', 'sportspress' ); ?></a>
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
									<strong><?php esc_html_e( 'Current Theme', 'sportspress' ); ?></strong>
								</th></tr>
							</thead>
							<tbody>
								<tr><td>
									<img src="<?php echo esc_url( $theme->get_screenshot() ); ?>" class="sp-theme-screenshot">
									<p><?php esc_html_e( 'Rookie is a free starter theme for SportsPress designed by ThemeBoy.', 'sportspress' ); ?></p>
									<p class="sp-module-actions">
										<span><?php esc_html_e( 'Need a better theme?', 'sportspress' ); ?></span>
										<a class="button" href="<?php echo esc_url( apply_filters( 'sportspress_pro_url', 'http://tboy.co/themes' ) ); ?>" target="_blank"><?php esc_html_e( 'Upgrade', 'sportspress' ); ?></a>
									</p>
								</td></tr>
							</tbody>
						</table>
					<?php } elseif ( ! current_theme_supports( 'sportspress' ) ) { ?>
						<table class="widefat" cellspacing="0">
							<thead>
								<tr><th>
									<strong><?php esc_html_e( 'Free SportsPress Theme', 'sportspress' ); ?></strong>
								</th></tr>
							</thead>
							<tbody>
								<tr><td>
									<img src="<?php echo esc_url( plugin_dir_url( SP_PLUGIN_FILE ) ); ?>/assets/images/welcome/rookie.png" class="sp-theme-screenshot">
									<p><?php esc_html_e( 'Rookie is a free starter theme for SportsPress designed by ThemeBoy.', 'sportspress' ); ?></p>
									<p class="sp-module-actions">
										<span><?php esc_html_e( 'Free', 'sportspress' ); ?></span>
										<a class="button" href="<?php echo esc_url( add_query_arg( array( 'theme' => 'rookie' ), network_admin_url( 'theme-install.php' ) ) ); ?>"><?php esc_html_e( 'Install Now', 'sportspress' ); ?></a>
									</p>
								</td></tr>
							</tbody>
						</table>
					<?php } ?>
				<?php } ?>

				<?php
				$categories = array(
					'documentation' => array(
						'icon'  => 'sp-icon-book',
						'label' => esc_attr__( 'Documentation', 'sportspress' ),
						'links' => array(
							'http://tboy.co/installation' => esc_attr__( 'Getting Started', 'sportspress' ),
							'http://tboy.co/manuals'      => esc_attr__( 'Manuals', 'sportspress' ),
							'http://tboy.co/videos'       => esc_attr__( 'Videos', 'sportspress' ),
						),
					),
					'help'          => array(
						'icon'  => 'dashicons dashicons-heart',
						'label' => esc_attr__( 'Help', 'sportspress' ),
						'links' => array(
							'http://tboy.co/forums' => esc_attr__( 'Support Forums', 'sportspress' ),
							'http://tboy.co/ideas'  => esc_attr__( 'Feature Requests', 'sportspress' ),
						),
					),
					'social'        => array(
						'icon'  => 'dashicons dashicons-share',
						'label' => esc_attr__( 'Connect', 'sportspress' ),
						'links' => array(
							'http://tboy.co/twitter'  => esc_attr__( 'Twitter', 'sportspress' ),
							'http://tboy.co/facebook' => esc_attr__( 'Facebook', 'sportspress' ),
							'http://tboy.co/youtube'  => esc_attr__( 'YouTube', 'sportspress' ),
							'http://tboy.co/gplus'    => esc_attr__( 'Google+', 'sportspress' ),
						),
					),
					'developers'    => array(
						'icon'  => 'dashicons dashicons-editor-code',
						'label' => esc_attr__( 'Developers', 'sportspress' ),
						'links' => array(
							'http://tboy.co/developers' => esc_attr__( 'Reference', 'sportspress' ),
							'http://tboy.co/slack'      => esc_attr__( 'Slack', 'sportspress' ),
							'http://tboy.co/github'     => esc_attr__( 'GitHub', 'sportspress' ),
						),
					),
				);

				if ( class_exists( 'SportsPress_Pro' ) ) {
					$categories['help']['links']['http://support.themeboy.com/'] = esc_attr__( 'Premium Support', 'sportspress' );
				} else {
					$categories['help']['links'][ apply_filters( 'sportspress_pro_url', 'http://tboy.co/pro' ) ] = '<span class="sp-desc-tip" title="' . esc_attr__( 'Upgrade to Pro', 'sportspress' ) . '">' . esc_attr__( 'Premium Support', 'sportspress' ) . '</span>';
				}

				$categories = apply_filters( 'sportspress_modules_welcome_links', $categories );

				if ( sizeof( $categories ) ) {
					?>
					<table class="widefat" cellspacing="0">
						<thead>
							<tr><th>
								<strong><?php esc_html_e( 'Welcome to SportsPress', 'sportspress' ); ?></strong>
							</th></tr>
						</thead>
						<tbody>
							<tr><td>
							<?php foreach ( $categories as $slug => $category ) { ?>
									<p><strong><i class="<?php echo esc_attr( $category['icon'] ); ?>"></i> <?php echo esc_html( $category['label'] ); ?></strong></p>
									<ul class="sp-<?php echo esc_attr( $slug ); ?>-links">
										<?php foreach ( $category['links'] as $url => $text ) { ?>
											<li><a href="<?php echo esc_url( $url ); ?>" target="_blank"><?php echo wp_kses_post( $text ); ?></a></li>
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
							<?php echo esc_html( sp_array_value( $this->sections, $section, esc_attr__( 'Modules', 'sportspress' ) ) ); ?>
						</th></tr>
					</thead>
					<tbody>
						<?php foreach ( $modules as $id => $module ) { ?>
							<?php if ( isset( $module['class'] ) && ! class_exists( $module['class'] ) ) { ?>
							<tr class="sp-module-unavailable"><td>
								<input type="checkbox" disabled="disabled">
								<span class="sp-desc-tip" title="<?php echo esc_attr( sp_array_value( $module, 'tip', esc_attr__( 'Upgrade to Pro', 'sportspress' ) ) ); ?>">
									<i class="<?php echo esc_attr( sp_array_value( $module, 'icon', 'dashicons dashicons-admin-generic' ) ); ?>"></i>
									<?php echo esc_html( sp_array_value( $module, 'label', $id ) ); ?>
								</span>
								<?php if ( isset( $module['desc'] ) ) { ?>
									<span class="sp-desc">
										<?php echo wp_kses_post( $module['desc'] ); ?>
										<?php if ( array_key_exists( 'link', $module ) ) { ?>
											<a href="<?php echo esc_url( apply_filters( 'sportspress_pro_url', $module['link'] ) ); ?>" target="_blank"><?php echo esc_html( sp_array_value( $module, 'action', esc_attr__( 'Learn more', 'sportspress' ) ) ); ?></a>
										<?php } ?>
									</span>
								<?php } ?>
							</td></tr>
							<?php } else { ?>
							<tr><td>
								<input type="checkbox" name="sportspress_load_<?php echo esc_attr( $id ); ?>_module" id="sportspress_load_<?php echo esc_attr( $id ); ?>_module" <?php checked( 'yes' == get_option( 'sportspress_load_' . $id . '_module', sp_array_value( $module, 'default', 'yes' ) ) ); ?>>
								<label for="sportspress_load_<?php echo esc_attr( $id ); ?>_module">
									<i class="<?php echo esc_attr( sp_array_value( $module, 'icon', 'dashicons dashicons-admin-generic' ) ); ?>"></i>
									<?php echo esc_html( sp_array_value( $module, 'label', $id ) ); ?>
								</label>
								<?php if ( isset( $module['desc'] ) ) { ?>
									<span class="sp-desc"><?php echo wp_kses_post( $module['desc'] ); ?></span>
								<?php } ?>
							</td></tr>
							<?php } ?>
						<?php } ?>
					</tbody>
				</table>
				<?php } ?>

				<p class="submit">
					<input name="save" class="button button-primary" type="submit" value="<?php esc_attr_e( 'Save Changes', 'sportspress' ); ?>" />
					<?php $GLOBALS['hide_save_button'] = true; ?>
				</p>
			</div>
		</div>

		<input type="hidden" name="sportspress_update_modules" value="1">

			<?php if ( isset( $_POST['sportspress_update_modules'] ) ) { ?>
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
