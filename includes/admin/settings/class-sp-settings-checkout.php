<?php
/**
 * SportsPress Shipping Settings
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin
 * @version     0.7
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'SP_Settings_Payment_Gateways' ) ) :

/**
 * SP_Settings_Payment_Gateways
 */
class SP_Settings_Payment_Gateways extends SP_Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id    = 'checkout';
		$this->label = _x( 'Checkout', 'Settings tab label', 'sportspress' );

		add_filter( 'sportspress_settings_tabs_array', array( $this, 'add_settings_page' ), 20 );
		add_action( 'sportspress_sections_' . $this->id, array( $this, 'output_sections' ) );
		add_action( 'sportspress_settings_' . $this->id, array( $this, 'output' ) );
		add_action( 'sportspress_admin_field_payment_gateways', array( $this, 'payment_gateways_setting' ) );
		add_action( 'sportspress_settings_save_' . $this->id, array( $this, 'save' ) );
	}

	/**
	 * Get sections
	 *
	 * @return array
	 */
	public function get_sections() {
		$sections = array(
			''         => __( 'Checkout Options', 'sportspress' )
		);

		// Load shipping methods so we can show any global options they may have
		$payment_gateways = SP()->payment_gateways->payment_gateways();

		foreach ( $payment_gateways as $gateway ) {

			$title = empty( $gateway->method_title ) ? ucfirst( $gateway->id ) : $gateway->method_title;

			$sections[ strtolower( get_class( $gateway ) ) ] = esc_html( $title );
		}

		return $sections;
	}

	/**
	 * Get settings array
	 *
	 * @return array
	 */
	public function get_settings() {
		return apply_filters( 'sportspress_payment_gateways_settings', array(

			array(	'title' => __( 'Checkout Process', 'sportspress' ), 'type' => 'title', 'id' => 'checkout_process_options' ),

			array(
				'title' => __( 'Coupons', 'sportspress' ),
				'desc'          => __( 'Enable the use of coupons', 'sportspress' ),
				'id'            => 'sportspress_enable_coupons',
				'default'       => 'yes',
				'type'          => 'checkbox',
				'desc_tip'		=>  __( 'Coupons can be applied from the cart and checkout pages.', 'sportspress' ),
				'autoload'      => false
			),

			array(
				'title'     => _x( 'Checkout', 'Settings group label', 'sportspress' ),
				'desc' 		=> __( 'Enable guest checkout', 'sportspress' ),
				'desc_tip'	=>  __( 'Allows customers to checkout without creating an account.', 'sportspress' ),
				'id' 		=> 'sportspress_enable_guest_checkout',
				'default'	=> 'yes',
				'type' 		=> 'checkbox',
				'checkboxgroup'	=> 'start',
				'autoload'  => false
			),

			array(
				'desc' 		=> __( 'Force secure checkout', 'sportspress' ),
				'id' 		=> 'sportspress_force_ssl_checkout',
				'default'	=> 'no',
				'type' 		=> 'checkbox',
				'checkboxgroup'		=> '',
				'show_if_checked' => 'option',
				'desc_tip'	=>  __( 'Force SSL (HTTPS) on the checkout pages (an SSL Certificate is required).', 'sportspress' ),
			),

			array(
				'desc' 		=> __( 'Un-force HTTPS when leaving the checkout', 'sportspress' ),
				'id' 		=> 'sportspress_unforce_ssl_checkout',
				'default'	=> 'no',
				'type' 		=> 'checkbox',
				'checkboxgroup'		=> 'end',
				'show_if_checked' => 'yes',
			),

			array( 'type' => 'sectionend', 'id' => 'checkout_process_options'),

			array(	'title' => __( 'Checkout Pages', 'sportspress' ), 'desc' => __( 'These pages need to be set so that SportsPress knows where to send users to checkout.', 'sportspress' ), 'type' => 'title', 'id' => 'checkout_page_options' ),

			array(
				'title' => __( 'Cart Page', 'sportspress' ),
				'desc' 		=> __( 'Page contents:', 'sportspress' ) . ' [' . apply_filters( 'sportspress_cart_shortcode_tag', 'sportspress_cart' ) . ']',
				'id' 		=> 'sportspress_cart_page_id',
				'type' 		=> 'single_select_page',
				'default'	=> '',
				'class'		=> 'chosen_select_nostd',
				'css' 		=> 'min-width:300px;',
				'desc_tip'	=> true,
			),

			array(
				'title' => __( 'Checkout Page', 'sportspress' ),
				'desc' 		=> __( 'Page contents:', 'sportspress' ) . ' [' . apply_filters( 'sportspress_checkout_shortcode_tag', 'sportspress_checkout' ) . ']',
				'id' 		=> 'sportspress_checkout_page_id',
				'type' 		=> 'single_select_page',
				'default'	=> '',
				'class'		=> 'chosen_select_nostd',
				'css' 		=> 'min-width:300px;',
				'desc_tip'	=> true,
			),

			array(
				'title' => __( 'Terms and Conditions', 'sportspress' ),
				'desc' 		=> __( 'If you define a "Terms" page the customer will be asked if they accept them when checking out.', 'sportspress' ),
				'id' 		=> 'sportspress_terms_page_id',
				'default'	=> '',
				'class'		=> 'chosen_select_nostd',
				'css' 		=> 'min-width:300px;',
				'type' 		=> 'single_select_page',
				'desc_tip'	=> true,
				'autoload'  => false
			),

			array( 'type' => 'sectionend', 'id' => 'checkout_page_options' ),

			array( 'title' => __( 'Checkout Endpoints', 'sportspress' ), 'type' => 'title', 'desc' => __( 'Endpoints are appended to your page URLs to handle specific actions during the checkout process. They should be unique.', 'sportspress' ), 'id' => 'account_endpoint_options' ),

			array(
				'title' => __( 'Pay', 'sportspress' ),
				'desc' 		=> __( 'Endpoint for the Checkout &rarr; Pay page', 'sportspress' ),
				'id' 		=> 'sportspress_checkout_pay_endpoint',
				'type' 		=> 'text',
				'default'	=> 'order-pay',
				'desc_tip'	=> true,
			),

			array(
				'title' => __( 'Order Received', 'sportspress' ),
				'desc' 		=> __( 'Endpoint for the Checkout &rarr; Pay page', 'sportspress' ),
				'id' 		=> 'sportspress_checkout_order_received_endpoint',
				'type' 		=> 'text',
				'default'	=> 'order-received',
				'desc_tip'	=> true,
			),

			array(
				'title'    => __( 'Add Payment Method', 'sportspress' ),
				'desc'     => __( 'Endpoint for the Checkout &rarr; Add Payment Method page', 'sportspress' ),
				'id'       => 'sportspress_myaccount_add_payment_method_endpoint',
				'type'     => 'text',
				'default'  => 'add-payment-method',
				'desc_tip' => true,
			),

			array( 'type' => 'sectionend', 'id' => 'checkout_endpoint_options' ),

			array( 'title' => __( 'Payment Gateways', 'sportspress' ),  'desc' => __( 'Installed gateways are listed below. Drag and drop gateways to control their display order on the frontend.', 'sportspress' ), 'type' => 'title', 'id' => 'payment_gateways_options' ),

			array( 'type' => 'payment_gateways' ),

			array( 'type' => 'sectionend', 'id' => 'payment_gateways_options' ),

		)); // End payment_gateway settings
	}

	/**
	 * Output the settings
	 */
	public function output() {
		global $current_section;

		// Load shipping methods so we can show any global options they may have
		$payment_gateways = SP()->payment_gateways->payment_gateways();

		if ( $current_section ) {
 			foreach ( $payment_gateways as $gateway ) {
				if ( strtolower( get_class( $gateway ) ) == strtolower( $current_section ) ) {
					$gateway->admin_options();
					break;
				}
			}
 		} else {
			$settings = $this->get_settings();

			SP_Admin_Settings::output_fields( $settings );
		}
	}

	/**
	 * Output payment gateway settings.
	 *
	 * @access public
	 * @return void
	 */
	public function payment_gateways_setting() {
		?>
		<tr valign="top">
			<th scope="row" class="titledesc"><?php _e( 'Gateway Display', 'sportspress' ) ?></th>
		    <td class="forminp">
				<table class="sp_gateways widefat" cellspacing="0">
					<thead>
						<tr>
							<?php
								$columns = apply_filters( 'sportspress_payment_gateways_setting_columns', array(
									'default'  => __( 'Default', 'sportspress' ),
									'name'     => __( 'Gateway', 'sportspress' ),
									'id'       => __( 'Gateway ID', 'sportspress' ),
									'status'   => __( 'Status', 'sportspress' ),
									'settings' => ''
								) );

								foreach ( $columns as $key => $column ) {
									echo '<th class="' . esc_attr( $key ) . '">' . esc_html( $column ) . '</th>';
								}
							?>
						</tr>
					</thead>
					<tbody>
			        	<?php
			        	$default_gateway = get_option( 'sportspress_default_gateway' );

			        	foreach ( SP()->payment_gateways->payment_gateways() as $gateway ) {

			        		echo '<tr>';

			        		foreach ( $columns as $key => $column ) {
								switch ( $key ) {
									case 'default' :
										echo '<td width="1%" class="default">
					        				<input type="radio" name="default_gateway" value="' . esc_attr( $gateway->id ) . '" ' . checked( $default_gateway, esc_attr( $gateway->id ), false ) . ' />
					        				<input type="hidden" name="gateway_order[]" value="' . esc_attr( $gateway->id ) . '" />
					        			</td>';
									break;
									case 'name' :
										echo '<td class="name">
					        				' . $gateway->get_title() . '
					        			</td>';
									break;
									case 'id' :
										echo '<td class="id">
					        				' . esc_html( $gateway->id ) . '
					        			</td>';
									break;
									case 'status' :
										echo '<td class="status">';

						        		if ( $gateway->enabled == 'yes' )
						        			echo '<span class="status-enabled tips" data-tip="' . __ ( 'Enabled', 'sportspress' ) . '">' . __ ( 'Enabled', 'sportspress' ) . '</span>';
						        		else
						        			echo '-';

						        		echo '</td>';
									break;
									case 'settings' :
										echo '<td class="settings">
					        				<a class="button" href="' . admin_url( 'admin.php?page=sp-settings&tab=checkout&section=' . strtolower( get_class( $gateway ) ) ) . '">' . __( 'Settings', 'sportspress' ) . '</a>
					        			</td>';
									break;
									default :
										do_action( 'sportspress_payment_gateways_setting_column_' . $key, $gateway );
									break;
								}
							}

							echo '</tr>';
			        	}
			        	?>
					</tbody>
				</table>
			</td>
		</tr>
		<?php
	}

	/**
	 * Save settings
	 */
	public function save() {
		global $current_section;

		if ( ! $current_section ) {

			$settings = $this->get_settings();

			SP_Admin_Settings::save_fields( $settings );
			SP()->payment_gateways->process_admin_options();

		} elseif ( class_exists( $current_section ) ) {

			$current_section_class = new $current_section();

			do_action( 'sportspress_update_options_payment_gateways_' . $current_section_class->id );

			SP()->payment_gateways()->init();
		}
	}
}

endif;

return new SP_Settings_Payment_Gateways();