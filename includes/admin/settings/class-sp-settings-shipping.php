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

if ( ! class_exists( 'SP_Settings_Shipping' ) ) :

/**
 * SP_Settings_Shipping
 */
class SP_Settings_Shipping extends SP_Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id    = 'shipping';
		$this->label = __( 'Shipping', 'sportspress' );

		add_filter( 'sportspress_settings_tabs_array', array( $this, 'add_settings_page' ), 20 );
		add_action( 'sportspress_sections_' . $this->id, array( $this, 'output_sections' ) );
		add_action( 'sportspress_settings_' . $this->id, array( $this, 'output' ) );
		add_action( 'sportspress_admin_field_shipping_methods', array( $this, 'shipping_methods_setting' ) );
		add_action( 'sportspress_settings_save_' . $this->id, array( $this, 'save' ) );
	}

	/**
	 * Get sections
	 *
	 * @return array
	 */
	public function get_sections() {
		$sections = array(
			''         => __( 'Shipping Options', 'sportspress' )
		);

		// Load shipping methods so we can show any global options they may have
		$shipping_methods = SP()->shipping->load_shipping_methods();

		foreach ( $shipping_methods as $method ) {

			if ( ! $method->has_settings() ) continue;

			$title = empty( $method->method_title ) ? ucfirst( $method->id ) : $method->method_title;

			$sections[ strtolower( get_class( $method ) ) ] = esc_html( $title );
		}

		return $sections;
	}

	/**
	 * Get settings array
	 *
	 * @return array
	 */
	public function get_settings() {
		return apply_filters('sportspress_shipping_settings', array(

			array( 'title' => __( 'Shipping Options', 'sportspress' ), 'type' => 'title', 'id' => 'shipping_options' ),

			array(
				'title' 		=> __( 'Shipping Calculations', 'sportspress' ),
				'desc' 		=> __( 'Enable shipping', 'sportspress' ),
				'id' 		=> 'sportspress_calc_shipping',
				'default'	=> 'yes',
				'type' 		=> 'checkbox',
				'checkboxgroup'		=> 'start'
			),

			array(
				'desc' 		=> __( 'Enable the shipping calculator on the cart page', 'sportspress' ),
				'id' 		=> 'sportspress_enable_shipping_calc',
				'default'	=> 'yes',
				'type' 		=> 'checkbox',
				'checkboxgroup'		=> '',
				'autoload'      => false
			),

			array(
				'desc' 		=> __( 'Hide shipping costs until an address is entered', 'sportspress' ),
				'id' 		=> 'sportspress_shipping_cost_requires_address',
				'default'	=> 'no',
				'type' 		=> 'checkbox',
				'checkboxgroup'		=> 'end',
				'autoload'      => false
			),

			array(
				'title' 	=> __( 'Shipping Display Mode', 'sportspress' ),
				'desc' 		=> __( 'This controls how multiple shipping methods are displayed on the frontend.', 'sportspress' ),
				'id' 		=> 'sportspress_shipping_method_format',
				'default'	=> '',
				'type' 		=> 'radio',
				'options' => array(
					''  			=> __( 'Display shipping methods with "radio" buttons', 'sportspress' ),
					'select'		=> __( 'Display shipping methods in a dropdown', 'sportspress' ),
				),
				'desc_tip'	=>  true,
				'autoload'      => false
			),

			array(
				'title'           => __( 'Shipping Destination', 'sportspress' ),
				'desc'            => __( 'Ship to billing address by default', 'sportspress' ),
				'id'              => 'sportspress_ship_to_billing',
				'default'         => 'yes',
				'type'            => 'checkbox',
				'checkboxgroup'   => 'start',
				'autoload'        => false,
				'show_if_checked' => 'option',
			),

			array(
				'desc'            => __( 'Only ship to the users billing address', 'sportspress' ),
				'id'              => 'sportspress_ship_to_billing_address_only',
				'default'         => 'no',
				'type'            => 'checkbox',
				'checkboxgroup'   => 'end',
				'autoload'        => false,
				'show_if_checked' => 'yes',
			),

			array(
				'title' => __( 'Restrict shipping to Location(s)', 'sportspress' ),
				'desc' 		=> sprintf( __( 'Choose which countries you want to ship to, or choose to ship to all <a href="%s">locations you sell to</a>.', 'sportspress' ), admin_url( 'admin.php?page=sp-settings&tab=general' ) ),
				'id' 		=> 'sportspress_ship_to_countries',
				'default'	=> '',
				'type' 		=> 'select',
				'class'		=> 'chosen_select',
				'desc_tip'	=> false,
				'options' => array(
					''         => __( 'Ship to all countries you sell to', 'sportspress' ),
					'all'      => __( 'Ship to all countries', 'sportspress' ),
					'specific' => __( 'Ship to specific countries only', 'sportspress' )
				)
			),

			array(
				'title' => __( 'Specific Countries', 'sportspress' ),
				'desc' 		=> '',
				'id' 		=> 'sportspress_specific_ship_to_countries',
				'css' 		=> '',
				'default'	=> '',
				'type' 		=> 'multi_select_countries'
			),

			array(
				'type' 		=> 'shipping_methods',
			),

			array( 'type' => 'sectionend', 'id' => 'shipping_options' ),

		)); // End shipping settings
	}

	/**
	 * Output the settings
	 */
	public function output() {
		global $current_section;

		// Load shipping methods so we can show any global options they may have
		$shipping_methods = SP()->shipping->load_shipping_methods();

		if ( $current_section ) {
 			foreach ( $shipping_methods as $method ) {
				if ( strtolower( get_class( $method ) ) == strtolower( $current_section ) && $method->has_settings() ) {
					$method->admin_options();
					break;
				}
			}
 		} else {
			$settings = $this->get_settings();

			SP_Admin_Settings::output_fields( $settings );
		}
	}

	/**
	 * Output shipping method settings.
	 *
	 * @access public
	 * @return void
	 */
	public function shipping_methods_setting() {
		$default_shipping_method = esc_attr( get_option('sportspress_default_shipping_method') );
		?>
		<tr valign="top">
			<th scope="row" class="titledesc"><?php _e( 'Shipping Methods', 'sportspress' ) ?></th>
		    <td class="forminp">
				<table class="sp_shipping widefat" cellspacing="0">
					<thead>
						<tr>
							<th class="default"><?php _e( 'Default', 'sportspress' ); ?></th>
							<th class="name"><?php _e( 'Name', 'sportspress' ); ?></th>
							<th class="id"><?php _e( 'ID', 'sportspress' ); ?></th>
							<th class="status"><?php _e( 'Status', 'sportspress' ); ?></th>
							<th class="settings">&nbsp;</th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th width="1%" class="default">
								<input type="radio" name="default_shipping_method" value="" <?php checked( $default_shipping_method, '' ); ?> />
							</th>
							<th><?php _e( 'No default', 'sportspress' ); ?></th>
							<th colspan="3"><span class="description"><?php _e( 'Drag and drop the above shipping methods to control their display order.', 'sportspress' ); ?></span></th>
						</tr>
					</tfoot>
					<tbody>
				    	<?php
				    	foreach ( SP()->shipping->load_shipping_methods() as $key => $method ) {
					    	echo '<tr>
					    		<td width="1%" class="default">
					    			<input type="radio" name="default_shipping_method" value="' . esc_attr( $method->id ) . '" ' . checked( $default_shipping_method, $method->id, false ) . ' />
					    			<input type="hidden" name="method_order[]" value="' . esc_attr( $method->id ) . '" />
					    		</td>
				    			<td class="name">
				    				' . $method->get_title() . '
				    			</td>
				    			<td class="id">
				    				' . $method->id . '
				    			</td>
				    			<td class="status">';

				    		if ( $method->enabled == 'yes' )
						        echo '<span class="status-enabled tips" data-tip="' . __ ( 'Enabled', 'sportspress' ) . '">' . __ ( 'Enabled', 'sportspress' ) . '</span>';
						   	else
						   		echo '-';

				    		echo '</td>
				    			<td class="settings">';

				    		if ( $method->has_settings ) {
				    			echo '<a class="button" href="' . admin_url( 'admin.php?page=sp-settings&tab=shipping&section=' . strtolower( get_class( $method ) ) ) . '">' . __( 'Settings', 'sportspress' ) . '</a>';
				    		}

				    		echo '</td>
				    		</tr>';
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
			SP()->shipping->process_admin_options();

		} elseif ( class_exists( $current_section ) ) {

			$current_section_class = new $current_section();

			do_action( 'sportspress_update_options_' . $this->id . '_' . $current_section_class->id );
		}
	}
}

endif;

return new SP_Settings_Shipping();
