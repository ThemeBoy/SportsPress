<?php
/**
 * SportsPress License Settings
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress_Updater
 * @version     1.9
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'SP_Settings_License' ) ) :

/**
 * SP_Settings_License
 */
class SP_Settings_License extends SP_Settings_Page {

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
		$this->id    = 'license';
		$this->label = __( 'License', 'sportspress' );
		if ( class_exists( 'SportsPress_Multisite' ) ) {
			$this->file = 'RJ';
			$this->title = __( 'League Package', 'sportspress' );
		} elseif ( class_exists( 'SportsPress_Tournaments' ) ) {
			$this->file = 'RL';
			$this->title = __( 'Club Package', 'sportspress' );
		} else {
			$this->file = 'RM';
			$this->title = __( 'Social Package', 'sportspress' );
		}

		if ( current_user_can( 'manage_options' ) ):
			add_filter( 'sportspress_settings_tabs_array', array( $this, 'add_settings_page' ), 100 );
			add_action( 'sportspress_settings_' . $this->id, array( $this, 'output' ) );
			add_action( 'sportspress_admin_field_license_key', array( $this, 'license_key_setting' ) );
			add_action( 'sportspress_settings_save_' . $this->id, array( $this, 'save' ) );
		endif;
	}

	/**
	 * Get settings array
	 *
	 * @return array
	 */
	public function get_settings() {
		$GLOBALS['hide_save_button'] = true;
		return apply_filters( 'sportspress_license_settings', array(

			array( 'title' => $this->title, 'type' => 'title', 'desc' => '', 'id' => 'license_options' ),

			array( 'type' => 'license_key' ),

			array( 'type' => 'sectionend', 'id' => 'license_options' ),

		)); // End license settings
	}


	/**
	 * License key settings
	 *
	 * @access public
	 * @return void
	 */
	public function license_key_setting() {
		$key = get_option( 'sportspress_pro_license_key' );
		$status = get_option( 'sportspress_pro_license_status' );
		?>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="sportspress_pro_license_key"><?php _e( 'License Key', 'sportspress' ); ?>
					<?php if ( $key && $status && 'valid' == $status ) { ?>
						<i class="dashicons dashicons-yes sp-desc-active"></i>
					<?php } else { ?>
						<i class="dashicons dashicons-editor-help sp-desc-tip" title="<?php _e( 'Enter the license key from your purchase receipt.', 'sportspress' ); ?>"></i>
					<?php } ?>
				</label>
			</th>
			<td class="forminp">
				<legend class="screen-reader-text"><span><?php _e( 'License Key', 'sportspress' ); ?></span></legend>
				<?php if ( $key && $status && 'valid' == $status ) { ?>
					<input type="text" name="sportspress_pro_license_key" class="regular-text" value="<?php esc_attr_e( $key ); ?>" readonly="readonly">
					<input type="hidden" name="sportspress_pro_license_key_deactivate" value="1">
	    			<input name="save" class="button button-secondar" type="submit" value="<?php _e( 'Deactivate', 'sportspress' ); ?>" />
				<?php } else { ?>
					<input type="text" name="sportspress_pro_license_key" class="regular-text">
					<input type="hidden" name="sportspress_pro_license_key_activate" value="1">
	    			<input name="save" class="button button-primary" type="submit" value="<?php _e( 'Activate', 'sportspress' ); ?>" />
				<?php } ?>
			</td>
		</tr>
		<?php
	}

	/**
	 * Save settings
	 */
	public function save() {
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
					delete_option( 'sportspress_pro_license_key' );
					update_option( 'sportspress_pro_license_status', 'deactivated' );
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

					update_option( 'sportspress_pro_license_key', $_POST['sportspress_pro_license_key'] );
					update_option( 'sportspress_pro_license_status', 'valid' );
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

return new SP_Settings_License();
