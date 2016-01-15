<?php
/*
Plugin Name: SportsPress Updater
Plugin URI: http://tboy.co/pro
Description: Allow SportsPress Pro to be updated directly from the dashboard.
Author: ThemeBoy
Author URI: http://themeboy.com
Version: 1.9.7
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_Updater' ) ) :

/**
 * Main SportsPress Updater Class
 *
 * @class SportsPress_Updater
 * @version	1.9.7
 */
class SportsPress_Updater {

	/**
	 * @var string
	 */
	public $file;

	/**
	 * @var string
	 */
	public $title;

	/**
	 * Constructor
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();

		// Check for updates
		add_action( 'sportspress_pro_loaded', array( $this, 'check_for_updates' ) );

		// Display on settings page
		add_action( 'sportspress_modules_sidebar', array( $this, 'sidebar' ) );
		add_action( 'sportspress_settings_save_modules', array( $this, 'save' ), 5 );
	}

	/**
	 * Define constants
	*/
	private function define_constants() {
		if ( !defined( 'SP_UPDATER_VERSION' ) )
			define( 'SP_UPDATER_VERSION', '1.9.7' );

		if ( !defined( 'SP_UPDATER_URL' ) )
			define( 'SP_UPDATER_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'SP_UPDATER_DIR' ) )
			define( 'SP_UPDATER_DIR', plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Define constants
	*/
	public function detect() {
		if ( class_exists( 'SportsPress_Agency' ) ) {
			$this->file = 'TG';
			$this->title = __( 'Agency License', 'sportspress' );
		} elseif ( class_exists( 'SportsPress_Multisite' ) ) {
			$this->file = 'RJ';
			$this->title = __( 'League License', 'sportspress' );
		} elseif ( class_exists( 'SportsPress_Tournaments' ) ) {
			$this->file = 'RL';
			$this->title = __( 'Club License', 'sportspress' );
		} else {
			$this->file = 'RM';
			$this->title = __( 'Social License', 'sportspress' );
		}
	}

	/**
	 * Get license
	*/
	public function sidebar() {
		if (
			( ! is_multisite() && current_user_can( 'manage_options' ) ) ||
			( is_multisite() && current_user_can( 'manage_network_options' ) )
		) {
			$key = get_site_option( 'sportspress_pro_license_key' );
			$status = get_site_option( 'sportspress_pro_license_status' );
			$this->detect();
		} else {
			return;
		}
		?>
		<table class="widefat" cellspacing="0">
			<thead>
				<tr><th>
					<strong><?php echo $this->title; ?></strong>
				</th></tr>
			</thead>
			<tbody>
				<tr><td>
					<p>
						<strong><?php _e( 'License Key', 'sportspress' ); ?></strong>
						<?php if ( $key && $status && 'valid' == $status ) { ?>
							<i class="dashicons dashicons-yes sp-desc-active"></i>
						<?php } else { ?>
							<i class="dashicons dashicons-editor-help sp-desc-tip" title="<?php _e( 'Enter the license key from your purchase receipt.', 'sportspress' ); ?>"></i>
						<?php } ?>
					</p>
					<?php if ( $key && $status && 'valid' == $status ) { ?>
						<p>
							<input type="text" name="sportspress_pro_license_key" class="widefat" value="<?php esc_attr_e( $key ); ?>" readonly="readonly">
							<input type="hidden" name="sportspress_pro_license_key_deactivate" value="1">
						</p>
						<p>
							<input name="sp_save_license" class="button button-secondary" type="submit" value="<?php esc_attr_e( 'Deactivate', 'sportspress' ); ?>" />
						</p>
						<?php if ( 'RM' == $this->file ) { ?>
						<p class="sp-module-actions">
							<span><?php _e( 'Club License', 'sportspress' ); ?></span>
							<a class="button" href="http://tboy.co/club" target="_blank"><?php _e( 'Upgrade Now', 'sportspress' ); ?></a>
						</p>
						<?php } if ( in_array( $this->file, array( 'RM', 'RL' ) ) ) { ?>
						<p class="sp-module-actions">
							<span><?php _e( 'League License', 'sportspress' ); ?></span>
							<a class="button" href="http://tboy.co/league" target="_blank"><?php _e( 'Upgrade Now', 'sportspress' ); ?></a>
						</p>
						<?php } if ( in_array( $this->file, array( 'RM', 'RL', 'RJ' ) ) ) { ?>
						<p class="sp-module-actions">
							<span><?php _e( 'Agency License', 'sportspress' ); ?></span>
							<a class="button" href="http://tboy.co/agency" target="_blank"><?php _e( 'Upgrade Now', 'sportspress' ); ?></a>
						</p>
						<?php } ?>
					<?php } else { ?>
						<p>
							<input type="text" name="sportspress_pro_license_key" class="widefat">
							<input type="hidden" name="sportspress_pro_license_key_activate" value="1">
						</p>
						<p>
							<input name="sp_save_license" class="button button-primary" type="submit" value="<?php esc_attr_e( 'Activate', 'sportspress' ); ?>" />
						</p>
						<p class="sp-module-actions">
							<span><?php _e( 'Need a license key?', 'sportspress' ); ?></span>
							<a class="button" href="http://tboy.co/pro" target="_blank"><?php _e( 'Purchase', 'sportspress' ); ?></a>
						</p>
					<?php } ?>
				</td></tr>
			</tbody>
		</table>
		<?php
	}

	/**
	 * Check for updates
	 */
	public function check_for_updates() {
		$license_key = get_site_option( 'sportspress_pro_license_key' );
		if ( $license_key ) {
			require_once( 'includes/class-sp-updater.php' );
			new SP_Updater( 'http://wp-updates.com/api/2/plugin', plugin_basename( SP_PRO_PLUGIN_FILE ), $license_key );
		}
	}

	/**
	 * Save license key
	 */
	public function save() {
		if ( ! isset( $_POST['sp_save_license'] ) ) return;

		// Prevent default module saving
		remove_all_actions( 'sportspress_settings_save_modules' );
		unset( $_POST['sportspress_update_modules'] );

		// Detect license type
		$this->detect();

		// Activate or deactivate license
		if ( isset( $_POST['sportspress_pro_license_key_deactivate'] ) ) {
			$key = $_POST['sportspress_pro_license_key'];
			if ( $key ) {
				$url = 'https://app.sellwire.net/api/1/deactivate_license';
				$args = array(
					'license' => $key,
					'file' => $this->file,
				);
				$response = wp_remote_get( add_query_arg( $args, $url ), array( 'timeout' => 15, 'sslverify' => false ) );

				if ( $response && ! is_wp_error( $response ) ) {
					$body = sp_array_value( $response, 'body', '{}' );
					$json = json_decode( $body, true );
				
					if ( array_key_exists( 'error', $json ) ) {
						SP_Admin_Settings::add_error( $json['error'] );
					} elseif ( array_key_exists( 'license', $json ) ) {
						SP_Admin_Settings::add_override( __( 'License deactivated.', 'sportspress' ) );
					}
					delete_site_option( 'sportspress_pro_license_key' );
					update_site_option( 'sportspress_pro_license_status', 'deactivated' );
				} else {
					SP_Admin_Settings::add_error( __( 'Sorry, there has been an error.', 'sportspress' ) );
				}
			}
		} elseif ( isset( $_POST['sportspress_pro_license_key'] ) ) {
			$key = $_POST['sportspress_pro_license_key'];
			if ( ! $key ) {
				SP_Admin_Settings::add_error( __( 'License invalid.', 'sportspress' ) );
				return;
			}

			$url = 'https://app.sellwire.net/api/1/activate_license';
			$args = array(
				'license' => $key,
				'file' => $this->file,
			);
			$response = wp_remote_get( add_query_arg( $args, $url ), array( 'timeout' => 15, 'sslverify' => false ) );

			if ( $response && ! is_wp_error( $response ) ) {
				$body = sp_array_value( $response, 'body', '{}' );
				$json = json_decode( $body, true );
			
				if ( array_key_exists( 'error', $json ) ) {
					SP_Admin_Settings::add_error( $json['error'] );
				} elseif ( array_key_exists( 'license', $json ) ) {
					SP_Admin_Settings::add_override( __( 'License activated.', 'sportspress' ) );

					update_site_option( 'sportspress_pro_license_key', $_POST['sportspress_pro_license_key'] );
					update_site_option( 'sportspress_pro_license_status', 'valid' );
				}
			} else {
				SP_Admin_Settings::add_error( __( 'Sorry, there has been an error.', 'sportspress' ) );
			}
		} else {
			SP_Admin_Settings::add_error( __( 'License invalid.', 'sportspress' ) );
		}
	}
}

endif;

new SportsPress_Updater();
