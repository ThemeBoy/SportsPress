<?php
/**
 * SportsPress Module Settings
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin
 * @version     1.8.7
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
							<img src="<?php echo plugin_dir_url( SP_PLUGIN_FILE ); ?>/assets/images/modules/sportspress-pro-sidebar.png" alt="<?php _e( 'SportsPress Pro', 'sportspress' ); ?>">
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

				<?php if ( current_user_can( 'install_plugins' ) && ! class_exists( 'SportsPress_TV' ) ) { ?>
				<table class="widefat" cellspacing="0">
					<thead>
						<tr><th>
							<img src="<?php echo plugin_dir_url( SP_PLUGIN_FILE ); ?>/assets/images/modules/sportspress-tv-sidebar.png" alt="<?php _e( 'SportsPress TV', 'sportspress' ); ?>">
						</th></tr>
					</thead>
					<tbody>
						<tr><td>
							<p><?php _e( 'Offer premium news & highlights from leading global sports. SportsPress TV will keep your visitors entertained for hours.','sportspress' ); ?></p>
							<p class="sp-module-actions">
								<span><?php _e( 'Free', 'sportspress' ); ?></span>
								<a class="button thickbox" href="<?php echo add_query_arg( array( 'tab' => 'plugin-information', 'plugin' => 'sportspress-tv', 'TB_iframe' => 'true', 'width' => '80%', 'height' => '500' ), network_admin_url( 'plugin-install.php' ) ); ?>"><?php _e( 'Install Now', 'sportspress' ); ?></a>
							</p>
						</td></tr>
					</tbody>
				</table>
				<?php } ?>

				<?php if ( ! class_exists( 'SportsPress_Twitter' ) ) { ?>
				<table class="widefat" cellspacing="0">
					<thead>
						<tr><th>
							<strong><?php _e( 'Twitter Module', 'sportspress' ); ?></strong>
						</th></tr>
					</thead>
					<tbody>
						<tr><td>
							<ol><li><?php echo str_replace(
								array( '[link]', '[/link]' ),
								array( '<a target="_blank" href="http://twitter.com/themeboy">', '</a>' ),
								__( 'Follow [link]@ThemeBoy[/link] on Twitter.','sportspress' )
							); ?></li>
							<li><?php echo str_replace(
								array( '[link]', '[/link]' ),
								array( '<a target="_blank" href="http://tboy.co/tweet">', '</a>' ),
								__( 'Help spread the word by tweeting with [link]#SportsPress[/link] and get the Twitter module for free.','sportspress' )
							); ?></li>
							<li><?php echo str_replace(
								array( '[link]', '[/link]' ),
								array( '<a target="_blank" href="http://tboy.co/twittermodule">', '</a>' ),
								__( '[link]Get the download link[/link].', 'sportspress' )
							); ?></li></ol>
							<p class="sp-module-actions">
								<span><?php _e( 'Free with tweet', 'sportspress' ); ?></span>
								<a class="button" href="http://tboy.co/tweet" target="_blank"><?php _e( 'Tweet', 'sportspress' ); ?></a>
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
										<a class="button" href="http://tboy.co/themes" target="_blank"><?php _e( 'Upgrade', 'sportspress' ); ?></a>
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
									<img src="<?php echo plugin_dir_url( SP_PLUGIN_FILE ); ?>/assets/images/modules/rookie.png" class="sp-theme-screenshot">
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

				<table class="widefat" cellspacing="0">
					<thead>
						<tr><th>
							<strong><?php _e( 'Welcome to SportsPress', 'sportspress' ); ?></strong>
						</th></tr>
					</thead>
					<tbody>
						<tr><td>
							<p><strong><i class="sp-icon-book"></i> <?php _e( 'Documentation', 'sportspress' ); ?></strong></p>
							<ul class="sp-documentation-links">
								<li><a href="http://tboy.co/installation" target="_blank"><?php _e( 'Getting Started', 'sportspress' ); ?></a></li>
								<li><a href="http://tboy.co/manuals" target="_blank"><?php _e( 'Manuals', 'sportspress' ); ?></a></li>
								<li><a href="http://tboy.co/videos" target="_blank"><?php _e( 'Videos', 'sportspress' ); ?></a></li>
								<li><a href="http://tboy.co/developers" target="_blank"><?php _e( 'Developers', 'sportspress' ); ?></a></li>
								<li><a href="http://tboy.co/integration" target="_blank"><?php _e( 'Theme Integration Guide', 'sportspress' ); ?></a></li>
							</ul>
							<p><strong><i class="dashicons dashicons-heart"></i> <?php _e( 'Help', 'sportspress' ); ?></strong></p>
							<ul class="sp-community-links">
								<li><a href="http://tboy.co/forums" target="_blank"><?php _e( 'Support Forums', 'sportspress' ); ?></a></li>
								<li><a href="http://tboy.co/ideas" target="_blank"><?php _e( 'Feature Requests', 'sportspress' ); ?></a></li>
								<?php if ( class_exists( 'SportsPress_Pro' ) ) { ?>
								<li><a href="<?php echo apply_filters( 'sportspress_support_url', 'http://support.themeboy.com/' ); ?>" target="_blank"><?php _e( 'Premium Support', 'sportspress' ); ?></a></li>
								<?php } else { ?>
								<li><a href="<?php echo apply_filters( 'sportspress_pro_url', 'http://tboy.co/pro' ); ?>" target="_blank"><span class="sp-desc-tip" title="<?php _e( 'Upgrade to Pro', 'sportspress' ); ?>"><?php _e( 'Premium Support', 'sportspress' ); ?></span></a></li>
								<?php } ?>
							</ul>
							<p><strong><i class="dashicons dashicons-share"></i> <?php _e( 'Connect', 'sportspress' ); ?></strong></p>
							<ul class="sp-community-links">
								<li><a href="http://tboy.co/twitter" target="_blank"><?php _e( 'Twitter', 'sportspress' ); ?></a></li>
								<li><a href="http://tboy.co/facebook" target="_blank"><?php _e( 'Facebook', 'sportspress' ); ?></a></li>
								<li><a href="http://tboy.co/youtube" target="_blank"><?php _e( 'YouTube', 'sportspress' ); ?></a></li>
								<li><a href="http://tboy.co/gplus" target="_blank"><?php _e( 'Google+', 'sportspress' ); ?></a></li>
							</ul>
						</td></tr>
					</tbody>
				</table>

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
								<?php if ( isset( $module['desc'] ) && ! array_key_exists( 'action', $module ) ) { ?>
									<span class="sp-desc">
										<?php echo $module['desc']; ?>
										<?php if ( array_key_exists( 'link', $module ) && ! array_key_exists( 'action', $module ) ) { ?>
											<a href="<?php echo $module['link']; ?>" target="_blank"><?php echo sp_array_value( $module, 'action', __( 'Learn more', 'sportspress' ) ); ?></a>
										<?php } ?>
									</span>
								<?php } ?>
								<?php if ( array_key_exists( 'link', $module ) && array_key_exists( 'action', $module ) ) { ?>
									<a class="button" href="<?php echo $module['link']; ?>" target="_blank"><?php echo sp_array_value( $module, 'action', __( 'Learn more', 'sportspress' ) ); ?></a>
								<?php } ?>
							</td></tr>
							<?php } else { ?>
							<tr><td>
								<input type="checkbox" name="sportspress_load_<?php echo $id; ?>_module" id="sportspress_load_<?php echo $id; ?>_module" <?php checked( 'yes' == get_option( 'sportspress_load_' . $id . '_module', 'yes' ) ); ?>>
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

			    <input name="save" class="button-primary" type="submit" value="<?php _e( 'Save Changes', 'sportspress' ); ?>" />
			    <?php $GLOBALS['hide_save_button'] = true; ?>
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
