<?php
/**
 * SportsPress License Settings
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin
 * @version     2.1
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'SP_Settings_Licenses' ) ) :

/**
 * SP_Settings_Licenses
 */
class SP_Settings_Licenses extends SP_Settings_Page {

	/**
	 * @var array
	 */
	public $licenses = array();

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->id    = 'licenses';
		$this->label = __( 'Licenses', 'sportspress' );

		$this->licenses = apply_filters( 'sportspress_licenses', array(
			'pro' => array(
				'name' 	=> 'SportsPress Pro',
				'url' 	=> 'https://account.themeboy.com',
			),
		));
		
		if ( sizeof ( $this->licenses ) <= 1 )
			return;

		add_filter( 'sportspress_settings_tabs_array', array( $this, 'add_settings_page' ), 20 );
		add_action( 'sportspress_settings_' . $this->id, array( $this, 'output' ) );
		add_action( 'sportspress_settings_save_' . $this->id, array( $this, 'save' ) );
	}

	/**
	 * Output licenses
	 *
	 * @access public
	 * @return void
	 */
	public function output() {
		?>
		<?php wp_nonce_field( 'sp_license_nonce', 'sp_license_nonce' ); ?>
		<div class="sp-modules-wrapper">
			<div class="sp-modules-main">
				<?php
				foreach ( $this->licenses as $id => $license ) {
					$key 	= get_site_option( 'sportspress_' . $id . '_license_key' );
					$key 	= trim( $key );
					$status = get_site_option( 'sportspress_' . $id . '_license_status', false );
					?>
					<div class="sp-settings-section sp-settings-section-license_options">
						<h3><?php echo $license['name']; ?></h3>
						<table class="form-table sp-licenses-table">
							<tbody>
								<tr>
									<th scope="row" class="titledesc">
										<?php _e( 'License Key', 'sportspress' ); ?>
										<?php if ( $key && $status && 'valid' == $status ) { ?>
											<i class="dashicons dashicons-yes sp-desc-active"></i>
										<?php } else { ?>
											<i class="dashicons dashicons-editor-help sp-desc-tip" title="<?php _e( 'Enter the license key from your purchase receipt.', 'sportspress' ); ?>"></i>
										<?php } ?>
									</th>
									<td>
										<?php if ( false !== $status && 'valid' == $status ) { ?>
											<p>
												<input type="text" name="sp_license_key_<?php echo $id; ?>" size="40" value="<?php esc_attr_e( $key ); ?>" readonly="readonly">
												<input name="sp_license_deactivate_<?php echo $id; ?>" class="button button-secondary button-small" type="submit" value="<?php esc_attr_e( 'Deactivate', 'sportspress' ); ?>" />
											</p>
										<?php } else { ?>
											<p>
												<input type="text" name="sp_license_key_<?php echo $id; ?>" size="40">
												<input name="sp_license_activate_<?php echo $id; ?>" class="button button-primary button-small" type="submit" value="<?php esc_attr_e( 'Activate', 'sportspress' ); ?>" />
											</p>
										<?php } ?>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				<?php } ?>
			</div>
		</div>
		<?php

		$GLOBALS['hide_save_button'] = true;
	}

	/**
	 * Save license
	 */
	public function save() {
		// run a quick security check
	 	if ( ! check_admin_referer( 'sp_license_nonce', 'sp_license_nonce' ) ) {
			return;
		}
		
		foreach ( $_POST as $name => $value ) {
			if ( 'sp_license_activate_' === substr( $name, 0, 20 ) ) {
				$this->activate( substr( $name, 20 ) );
			} elseif ( 'sp_license_deactivate_' === substr( $name, 0, 22 ) ) {
				$this->deactivate( substr( $name, 22 ) );
			}
		}
	}

	/**
	 * Activate license
	 */
	public function activate( $id ) {

		// return if a license key isn't set
		if ( ! isset( $_POST[ 'sp_license_key_' . $id ] ) )
			return;

		// retrieve the license key
		$license = trim( $_POST[ 'sp_license_key_' . $id ] );
		
		// get the name of the product
		$name = $this->licenses[ $id ]['name'];

		// data to send in our API request
		$api_params = array(
			'edd_action'=> 'activate_license',
			'license' 	=> $license,
			'item_name' => urlencode( $name ), // the name of our product in EDD
			'url'       => home_url()
		);

		// Call the custom API.
		$response = wp_remote_post( $this->licenses[ $id ]['url'], array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

		// Make sure the response came back okay
		if ( is_wp_error( $response ) ) {
			SP_Admin_Settings::add_error( __( 'Sorry, there has been an error.', 'sportspress' ) );
			return false;
		}

		// Decode the license data
		$license_data = json_decode( wp_remote_retrieve_body( $response ) );

		// Update license status
		update_site_option( 'sportspress_' . $id . '_license_status', $license_data->license );
		
		// Update License or display error
		if ( 'valid' == $license_data->license ) {
			update_site_option( 'sportspress_' . $id . '_license_key', $license );
			SP_Admin_Settings::add_override( __( 'License activated.', 'sportspress' ) );
		} else {
			SP_Admin_Settings::add_error( __( 'License invalid.', 'sportspress' ) );
		}
	}

	/**
	 * Deactivate license
	 */
	public function deactivate( $id ) {

		// return if a license key isn't set
		if ( ! isset( $_POST[ 'sp_license_key_' . $id ] ) )
			return;

		// retrieve the license key
		$license = trim( $_POST[ 'sp_license_key_' . $id ] );
		
		// get the name of the product
		$name = $this->licenses[ $id ]['name'];

		// data to send in our API request
		$api_params = array(
			'edd_action'=> 'deactivate_license',
			'license' 	=> $license,
			'item_name' => urlencode( $name ), // the name of our product in EDD
			'url'       => home_url()
		);

		// Call the custom API.
		$response = wp_remote_post( $this->licenses[ $id ]['url'], array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

		// make sure the response came back okay
		if ( is_wp_error( $response ) ) {
			SP_Admin_Settings::add_error( __( 'Sorry, there has been an error.', 'sportspress' ) );
			return false;
		}

		// decode the license data
		$license_data = json_decode( wp_remote_retrieve_body( $response ) );

		// $license_data->license will be either "deactivated" or "failed"
		if ( $license_data->license == 'deactivated' ) {
			delete_site_option( 'sportspress_' . $id . '_license_status' );
			SP_Admin_Settings::add_override( __( 'License deactivated.', 'sportspress' ) );
		} else {
			SP_Admin_Settings::add_error( __( 'Sorry, there has been an error.', 'sportspress' ) );
		}
	}
}

endif;

return new SP_Settings_Licenses();
