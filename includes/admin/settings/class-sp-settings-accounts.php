<?php
/**
 * SportsPress Account Settings
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin
 * @version     0.7
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'SP_Settings_Accounts' ) ) :

/**
 * SP_Settings_Accounts
 */
class SP_Settings_Accounts extends SP_Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id    = 'account';
		$this->label = __( 'Accounts', 'sportspress' );

		add_filter( 'sportspress_settings_tabs_array', array( $this, 'add_settings_page' ), 20 );
		add_action( 'sportspress_settings_' . $this->id, array( $this, 'output' ) );
		add_action( 'sportspress_settings_save_' . $this->id, array( $this, 'save' ) );
	}

	/**
	 * Get settings array
	 *
	 * @return array
	 */
	public function get_settings() {

		return apply_filters( 'sportspress_' . $this->id . '_settings', array(

			array( 'title' => __( 'Account Pages', 'sportspress' ), 'type' => 'title', 'desc' => __( 'These pages need to be set so that SportsPress knows where to send users to access account related functionality.', 'sportspress' ), 'id' => 'account_page_options' ),

			array(
				'title' => __( 'My Account Page', 'sportspress' ),
				'desc' 		=> __( 'Page contents:', 'sportspress' ) . ' [' . apply_filters( 'sportspress_my_account_shortcode_tag', 'sportspress_my_account' ) . ']',
				'id' 		=> 'sportspress_myaccount_page_id',
				'type' 		=> 'single_select_page',
				'default'	=> '',
				'class'		=> 'chosen_select_nostd',
				'css' 		=> 'min-width:300px;',
				'desc_tip'	=> true,
			),

			array( 'type' => 'sectionend', 'id' => 'account_page_options' ),

			array( 'title' => __( 'My Account Endpoints', 'sportspress' ), 'type' => 'title', 'desc' => __( 'Endpoints are appended to your page URLs to handle specific actions on the accounts pages. They should be unique.', 'sportspress' ), 'id' => 'account_endpoint_options' ),

			array(
				'title' => __( 'View Order', 'sportspress' ),
				'desc' 		=> __( 'Endpoint for the My Account &rarr; View Order page', 'sportspress' ),
				'id' 		=> 'sportspress_myaccount_view_order_endpoint',
				'type' 		=> 'text',
				'default'	=> 'view-order',
				'desc_tip'	=> true,
			),

			array(
				'title' => __( 'Edit Account', 'sportspress' ),
				'desc' 		=> __( 'Endpoint for the My Account &rarr; Edit Account page', 'sportspress' ),
				'id' 		=> 'sportspress_myaccount_edit_account_endpoint',
				'type' 		=> 'text',
				'default'	=> 'edit-account',
				'desc_tip'	=> true,
			),

			array(
				'title' => __( 'Edit Address', 'sportspress' ),
				'desc' 		=> __( 'Endpoint for the My Account &rarr; Edit Address page', 'sportspress' ),
				'id' 		=> 'sportspress_myaccount_edit_address_endpoint',
				'type' 		=> 'text',
				'default'	=> 'edit-address',
				'desc_tip'	=> true,
			),

			array(
				'title' => __( 'Lost Password', 'sportspress' ),
				'desc' 		=> __( 'Endpoint for the My Account &rarr; Lost Password page', 'sportspress' ),
				'id' 		=> 'sportspress_myaccount_lost_password_endpoint',
				'type' 		=> 'text',
				'default'	=> 'lost-password',
				'desc_tip'	=> true,
			),

			array(
				'title' => __( 'Logout', 'sportspress' ),
				'desc' 		=> __( 'Endpoint for the triggering logout. You can add this to your menus via a custom link: yoursite.com/?customer-logout=true', 'sportspress' ),
				'id' 		=> 'sportspress_logout_endpoint',
				'type' 		=> 'text',
				'default'	=> 'customer-logout',
				'desc_tip'	=> true,
			),

			array( 'type' => 'sectionend', 'id' => 'account_endpoint_options' ),

			array(	'title' => __( 'Registration Options', 'sportspress' ), 'type' => 'title', 'id' => 'account_registration_options' ),

			array(
				'title'         => __( 'Enable Registration', 'sportspress' ),
				'desc'          => __( 'Enable registration on the "Checkout" page', 'sportspress' ),
				'id'            => 'sportspress_enable_signup_and_login_from_checkout',
				'default'       => 'yes',
				'type'          => 'checkbox',
				'checkboxgroup' => 'start',
				'autoload'      => false
			),

			array(
				'desc'          => __( 'Enable registration on the "My Account" page', 'sportspress' ),
				'id'            => 'sportspress_enable_myaccount_registration',
				'default'       => 'no',
				'type'          => 'checkbox',
				'checkboxgroup' => 'end',
				'autoload'      => false
			),

			array(
				'desc'          => __( 'Display returning customer login reminder on the "Checkout" page', 'sportspress' ),
				'id'            => 'sportspress_enable_checkout_login_reminder',
				'default'       => 'yes',
				'type'          => 'checkbox',
				'checkboxgroup' => 'start',
				'autoload'      => false
			),

			array(
				'title'         => __( 'Account Creation', 'sportspress' ),
				'desc'          => __( 'Automatically generate username from customer email', 'sportspress' ),
				'id'            => 'sportspress_registration_generate_username',
				'default'       => 'yes',
				'type'          => 'checkbox',
				'checkboxgroup' => 'start',
				'autoload'      => false
			),

			array(
				'desc'          => __( 'Automatically generate customer password', 'sportspress' ),
				'id'            => 'sportspress_registration_generate_password',
				'default'       => 'no',
				'type'          => 'checkbox',
				'checkboxgroup' => 'end',
				'autoload'      => false
			),

			array( 'type' => 'sectionend', 'id' => 'account_registration_options'),

		)); // End pages settings
	}
}

endif;

return new SP_Settings_Accounts();