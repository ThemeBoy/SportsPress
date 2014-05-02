<?php
/**
 * SportsPress General Settings
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin
 * @version     0.8
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'SP_Settings_General' ) ) :

/**
 * SP_Admin_Settings_General
 */
class SP_Settings_General extends SP_Settings_Page {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->id    = 'general';
		$this->label = __( 'General', 'sportspress' );

		add_filter( 'sportspress_settings_tabs_array', array( $this, 'add_settings_page' ), 20 );
		add_action( 'sportspress_settings_' . $this->id, array( $this, 'output' ) );
		add_action( 'sportspress_admin_field_country', array( $this, 'country_setting' ) );
		add_action( 'sportspress_settings_save_' . $this->id, array( $this, 'save' ) );

		if ( ( $styles = SP_Frontend_Scripts::get_styles() ) && array_key_exists( 'sportspress-general', $styles ) )
			add_action( 'sportspress_admin_field_frontend_styles', array( $this, 'frontend_styles_setting' ) );
	}

	/**
	 * Get settings array
	 *
	 * @return array
	 */
	public function get_settings() {

		$presets = SP_Admin_Sports::get_preset_options();

		return apply_filters( 'sportspress_general_settings', array(

			array( 'title' => __( 'General Options', 'sportspress' ), 'type' => 'title', 'desc' => '', 'id' => 'general_options' ),
			
			array( 'type' => 'country' ),

			array(
				'title'     => __( 'Sport', 'sportspress' ),
				'id'        => 'sportspress_sport',
				'default'   => 'soccer',
				'type'      => 'select',
				'options'   => $presets,
			),

			array( 'type' => 'sectionend', 'id' => 'general_options' ),

			array( 'title' => __( 'Styles and Scripts', 'sportspress' ), 'type' => 'title', 'desc' => '', 'id' => 'script_styling_options' ),

			array( 'type' 		=> 'frontend_styles' ),

			array(
				'title' 	=> __( 'Custom CSS', 'sportspress' ),
				'id' 		=> 'sportspress_custom_css',
				'css' 		=> 'width:100%; height: 130px;',
				'type' 		=> 'textarea',
			),

			array(
				'title'     => __( 'Scripts', 'sportspress' ),
				'desc' 		=> __( 'Responsive tables', 'sportspress' ),
				'id' 		=> 'sportspress_enable_responsive_tables',
				'default'	=> 'yes',
				'type' 		=> 'checkbox',
				'checkboxgroup'	=> 'start',
				'desc_tip'	=> __( 'This will enable a script allowing the tables to be responsive.', 'sportspress' ),
			),

			array(
				'desc' 		=> __( 'Sortable tables', 'sportspress' ),
				'id' 		=> 'sportspress_enable_sortable_tables',
				'default'	=> 'yes',
				'type' 		=> 'checkbox',
				'checkboxgroup'		=> '',
				'desc_tip'	=> __( 'This will enable a script allowing the tables to be sortable.', 'sportspress' ),
			),

			array(
				'desc' 		=> __( 'Live countdowns', 'sportspress' ),
				'id' 		=> 'sportspress_enable_live_countdowns',
				'default'	=> 'yes',
				'type' 		=> 'checkbox',
				'checkboxgroup'		=> 'end',
				'desc_tip'	=> __( 'This will enable a script allowing the countdowns to be animated.', 'sportspress' ),
			),

			array( 'type' => 'sectionend', 'id' => 'script_styling_options' ),

		)); // End general settings
	}

	/**
	 * Save settings
	 */
	public function save() {
		if ( isset( $_POST['sportspress_sport'] ) && ! empty( $_POST['sportspress_sport'] ) && get_option( 'sportspress_sport', null ) != $_POST['sportspress_sport'] ):
			$sport = $_POST['sportspress_sport'];
			SP_Admin_Sports::apply_preset( $sport );
    		update_option( '_sp_needs_welcome', 0 );
		endif;

		$settings = $this->get_settings();
		SP_Admin_Settings::save_fields( $settings );

		if ( isset( $_POST['sportspress_default_country'] ) )
	    	update_option( 'sportspress_default_country', $_POST['sportspress_default_country'] );

	    update_option( 'sportspress_enable_frontend_css', isset( $_POST['sportspress_enable_frontend_css'] ) ? 'yes' : 'no' );

		if ( isset( $_POST['sportspress_frontend_css_primary'] ) ) {

			// Save settings
			$primary 		= ( ! empty( $_POST['sportspress_frontend_css_primary'] ) ) ? sp_format_hex( $_POST['sportspress_frontend_css_primary'] ) : '';
			$heading 		= ( ! empty( $_POST['sportspress_frontend_css_heading'] ) ) ? sp_format_hex( $_POST['sportspress_frontend_css_heading'] ) : '';
			$text 			= ( ! empty( $_POST['sportspress_frontend_css_text'] ) ) ? sp_format_hex( $_POST['sportspress_frontend_css_text'] ) : '';
			$background 	= ( ! empty( $_POST['sportspress_frontend_css_background'] ) ) ? sp_format_hex( $_POST['sportspress_frontend_css_background'] ) : '';
			$alternate 		= ( ! empty( $_POST['sportspress_frontend_css_alternate'] ) ) ? sp_format_hex( $_POST['sportspress_frontend_css_alternate'] ) : '';

			$colors = array(
				'primary' 		=> $primary,
				'heading' 		=> $heading,
				'text' 			=> $text,
				'background' 	=> $background,
				'alternate' 	=> $alternate
			);

			update_option( 'sportspress_frontend_css_colors', $colors );
		}
	}

	/**
	 * Country settings
	 *
	 * @access public
	 * @return void
	 */
	public function country_setting() {
		$selected = (string) get_option( 'sportspress_default_country', 'AU' );
		$continents = SP()->countries->continents;
    	?>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="sportspress_default_country"><?php _e( 'Base Location', 'sportspress' ); ?></label>
			</th>
            <td class="forminp">
				<legend class="screen-reader-text"><span><?php _e( 'Delimiter', 'sportspress' ); ?></span></legend>
				<select name="sportspress_default_country" data-placeholder="<?php _e( 'Choose a country&hellip;', 'sportspress' ); ?>" title="Country" class="chosen-select<?php if ( is_rtl() ): ?> chosen-rtl<?php endif; ?>">
	        		<?php SP()->countries->country_dropdown_options( $selected ); ?>
	        	</select>
       		</td>
       	</tr>
       	<?php
	}

	/**
	 * Output the frontend styles settings.
	 *
	 * @access public
	 * @return void
	 */
	public function frontend_styles_setting() {
		?><tr valign="top" class="sportspress_frontend_css_colors">
			<th scope="row" class="titledesc">
				<?php _e( 'Frontend Styles', 'sportspress' ); ?>
			</th>
		    <td class="forminp"><?php

				// Get settings
				$colors = array_map( 'esc_attr', (array) get_option( 'sportspress_frontend_css_colors' ) );

				// Defaults
				if ( empty( $colors['primary'] ) ) $colors['primary'] = '#00a69c';
				if ( empty( $colors['heading'] ) ) $colors['heading'] = '#ffffff';
				if ( empty( $colors['text'] ) ) $colors['text'] = '#222222';
				if ( empty( $colors['background'] ) ) $colors['background'] = '#f5f5f5';
	            if ( empty( $colors['alternate'] ) ) $colors['alternate'] = '#f0f0f0';

				// Show inputs
	    		$this->color_picker( __( 'Primary', 'sportspress' ), 'sportspress_frontend_css_primary', $colors['primary'] );
	    		$this->color_picker( __( 'Heading', 'sportspress' ), 'sportspress_frontend_css_heading', $colors['heading'] );
	    		$this->color_picker( __( 'Text', 'sportspress' ), 'sportspress_frontend_css_text', $colors['text'] );
	    		$this->color_picker( __( 'Background', 'sportspress' ), 'sportspress_frontend_css_background', $colors['background'] );
	    		$this->color_picker( __( 'Alternate', 'sportspress' ), 'sportspress_frontend_css_alternate', $colors['alternate'] );

		    ?><br>
			    <label for="sportspress_enable_frontend_css">
					<input name="sportspress_enable_frontend_css" id="sportspress_enable_frontend_css" type="checkbox" value="1" <?php checked( get_option( 'sportspress_enable_frontend_css', 'yes' ), 'yes' ); ?>>
					<?php _e( 'Enable', 'sportspress' ); ?>
				</label>
			</td>
		</tr><?php
	}

	/**
	 * Output a colour picker input box.
	 *
	 * @access public
	 * @param mixed $name
	 * @param mixed $id
	 * @param mixed $value
	 * @param string $desc (default: '')
	 * @return void
	 */
	function color_picker( $name, $id, $value, $desc = '' ) {
		echo '<div class="sp-color-box"><strong>' . esc_html( $name ) . '</strong>
	   		<input name="' . esc_attr( $id ). '" id="' . esc_attr( $id ) . '" type="text" value="' . esc_attr( $value ) . '" class="colorpick" /> <div id="colorPickerDiv_' . esc_attr( $id ) . '" class="colorpickdiv"></div>
	    </div>';
	}
}

endif;

return new SP_Settings_General();
