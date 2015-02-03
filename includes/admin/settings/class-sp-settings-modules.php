<?php
/**
 * SportsPress Module Settings
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin
 * @version     1.6
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'SP_Settings_Modules' ) ) :

/**
 * SP_Settings_Modules
 */
class SP_Settings_Modules extends SP_Settings_Page {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->id    = 'modules';
		$this->label = __( 'Modules', 'sportspress' );
		$this->theme = wp_get_theme();

		$this->sections = apply_filters( 'sportspress_module_sections', array(
			'general' => __( 'General', 'sportspress' ),
			'event' => __( 'Events', 'sportspress' ),
			'team' => __( 'Teams', 'sportspress' ),
			'player' => __( 'Players', 'sportspress' ),
			'staff' => __( 'Staff', 'sportspress' ),
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
		<h3><?php _e( 'Modules', 'sportspress' ); ?></h3>

		<div class="sp-modules-wrapper">
			<div class="sp-modules-sidebar">
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
								<a class="button button-primary" href="<?php echo apply_filters( 'sportspress_pro_url', 'http://tboy.co/pricing' ); ?>" target="_blank"><?php _e( 'Upgrade Now', 'sportspress' ); ?></a>
							</p>
						</td></tr>
					</tbody>
				</table>
				<?php } ?>

				<?php if ( ! class_exists( 'SportsPress_TV' ) ) { ?>
				<table class="widefat" cellspacing="0">
					<thead>
						<tr><th>
							<img src="<?php echo plugin_dir_url( SP_PLUGIN_FILE ); ?>/assets/images/modules/sportspress-tv-sidebar.png" alt="<?php _e( 'SportsPress TV', 'sportspress' ); ?>">
						</th></tr>
					</thead>
					<tbody>
						<tr><td>
							<p><?php _e( 'Offer premium news & highlights from leading globals sports. SportsPress TV will keep your visitors entertained for hours.','sportspress' ); ?></p>
							<p class="sp-module-actions">
								<span><?php _e( 'Free', 'sportspress' ); ?></span>
								<a class="button" href="<?php echo add_query_arg( array( 'tab' => 'search', 's' => 'sportspress+tv' ), admin_url( 'plugin-install.php' ) ); ?>"><?php _e( 'Install Now', 'sportspress' ); ?></a>
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
								array( '<a target="_blank" href="http://tboy.co/tweet">', '</a>' ),
								__( 'Help spread the word by tweeting with [link]#SportsPress[/link] and get the Twitter module for free.','sportspress' )
							); ?></li>
							<li><?php echo str_replace(
								array( '[link]', '[/link]' ),
								array( '<a href="mailto:tweets@themeboy.com">', '</a>' ),
								__( '[link]Email us[/link] for the download link.', 'sportspress' )
							); ?></li></ol>
							<p class="sp-module-actions">
								<span><?php _e( 'Free with tweet', 'sportspress' ); ?></span>
								<a class="button" href="http://tboy.co/tweet" target="_blank"><?php _e( 'Tweet', 'sportspress' ); ?></a>
							</p>
						</td></tr>
					</tbody>
				</table>
				<?php } ?>

				<?php if ( ! class_exists( 'SportsPress_Birthdays' ) ) { ?>
				<table class="widefat" cellspacing="0">
					<thead>
						<tr><th>
							<strong><?php _e( 'Birthdays Module', 'sportspress' ); ?></strong>
						</th></tr>
					</thead>
					<tbody>
						<tr><td>
							<ol><li><?php echo str_replace(
								array( '[stars]', '[link]', '[/link]' ),
								array( '<a target="_blank" href="http://tboy.co/review">&#9733;&#9733;&#9733;&#9733;&#9733;</a>', '<a target="_blank" href="http://tboy.co/review">', '</a>' ),
								__( 'Add your [stars] on [link]wordpress.org[/link] and get the Birthdays module for free.','sportspress' )
							); ?></li>
							<li><?php echo str_replace(
								array( '[link]', '[/link]' ),
								array( '<a href="mailto:reviews@themeboy.com">', '</a>' ),
								__( '[link]Email us[/link] for the download link.', 'sportspress' )
							); ?></li></ol>
							<p class="sp-module-actions">
								<span><?php _e( 'Free with review', 'sportspress' ); ?></span>
								<a class="button" href="http://tboy.co/review" target="_blank"><?php _e( 'Post Review', 'sportspress' ); ?></a>
							</p>
						</td></tr>
					</tbody>
				</table>
				<?php } ?>

				<table class="widefat" cellspacing="0">
					<thead>
						<tr><th>
							<strong><?php _e( 'Need Help?', 'sportspress' ); ?></strong>
						</th></tr>
					</thead>
					<tbody>
						<tr><td>
							<p><strong><i class="sp-icon-book"></i> <?php _e( 'Documentation', 'sportspress' ); ?></strong></p>
							<ul class="sp-documentation-links">
								<li><a href="http://tboy.co/installation" target="_blank"><?php _e( 'Getting Started', 'sportspress' ); ?></a></li>
								<li><a href="http://tboy.co/roles" target="_blank"><?php _e( 'Roles and Capabilities', 'sportspress' ); ?></a></li>
								<li><a href="http://tboy.co/integration" target="_blank"><?php _e( 'Theme Integration Guide', 'sportspress' ); ?></a></li>
							</ul>
							<p><strong><i class="dashicons dashicons-groups"></i> <?php _e( 'Support', 'sportspress' ); ?></strong></p>
							<ul class="sp-community-links">
								<li><a href="http://tboy.co/forums" target="_blank"><?php _e( 'Plugin Forums', 'sportspress' ); ?></a></li>
								<?php if ( class_exists( 'SportsPress_Pro' ) ) { ?>
								<li><a href="<?php echo apply_filters( 'sportspress_support_url', 'http://sportspresspro.com/support/' ); ?>" target="_blank"><?php _e( 'Premium Support', 'sportspress' ); ?></a></li>
								<?php } else { ?>
								<li><a href="<?php echo apply_filters( 'sportspress_pro_url', 'http://tboy.co/pricing/' ); ?>" target="_blank"><span class="sp-desc-tip" title="<?php _e( 'Upgrade to Pro', 'sportspress' ); ?>"><?php _e( 'Premium Support', 'sportspress' ); ?></span></a></li>
								<?php } ?>
							</ul>
						</td></tr>
					</tbody>
				</table>

				<?php $theme = wp_get_theme(); ?>
				<?php if ( ! current_theme_supports( 'sportspress' ) ) { ?>
					<table class="widefat" cellspacing="0">
						<thead>
							<tr><th>
								<strong><?php _e( 'Current Theme', 'sportspress' ); ?></strong>
							</th></tr>
						</thead>
						<tbody>
							<tr><td>
								<img src="<?php echo $theme->get_screenshot(); ?>" class="sp-theme-screenshot">
								<p><?php _e( '<strong>Your theme does not declare SportsPress support</strong> &#8211; if you encounter layout issues please read our integration guide or choose a SportsPress theme :)', 'sportspress' ); ?></p>
								<p class="sp-module-actions">
									<a class="button" href="http://tboy.co/themes" target="_blank"><?php _e( 'Need a Better Theme?', 'sportspress' ); ?></a>
								</p>
							</td></tr>
						</tbody>
					</table>
				<?php } ?>
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
								<span<?php if ( array_key_exists( 'tip', $module ) ) { ?> class="sp-desc-tip" title="<?php echo $module['tip']; ?>"<?php } ?>>
									<i class="<?php echo sp_array_value( $module, 'icon', 'dashicons dashicons-admin-generic' ); ?>"></i>
									<?php echo sp_array_value( $module, 'label', $id ); ?>
								</span>
								<?php if ( array_key_exists( 'link', $module ) ) { ?>
								<a class="button<?php if ( ! array_key_exists( 'action', $module ) ) { ?> button-primary<?php } ?>" href="<?php echo $module['link']; ?>" target="_blank"><?php echo sp_array_value( $module, 'action', __( 'Learn More', 'sportspress' ) ); ?></a>
								<?php } ?>
							</td></tr>
							<?php } else { ?>
							<tr><td>
								<input type="checkbox" name="sportspress_load_<?php echo $id; ?>_module" id="sportspress_load_<?php echo $id; ?>_module" <?php checked( 'yes' == get_option( 'sportspress_load_' . $id . '_module', 'yes' ) ); ?>>
								<label for="sportspress_load_<?php echo $id; ?>_module">
									<i class="<?php echo sp_array_value( $module, 'icon', 'dashicons dashicons-admin-generic' ); ?>"></i>
									<?php echo sp_array_value( $module, 'label', $id ); ?>
								</label>
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
		<?php } ?>

		<?php
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
