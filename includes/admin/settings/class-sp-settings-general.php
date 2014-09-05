<?php
/**
 * SportsPress General Settings
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin
 * @version     1.3
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
		add_action( 'sportspress_admin_field_timezone', array( $this, 'timezone_setting' ) );
		add_action( 'sportspress_admin_field_frontend_styles', array( $this, 'frontend_styles_setting' ) );
		add_action( 'sportspress_settings_save_' . $this->id, array( $this, 'save' ) );
	}

	/**
	 * Get settings array
	 *
	 * @return array
	 */
	public function get_settings() {

		$presets = SP_Admin_Sports::get_preset_options();

		$settings = array(
			array( 'title' => __( 'General Options', 'sportspress' ), 'type' => 'title', 'desc' => '', 'id' => 'general_options' ),
			
			array( 'type' => 'timezone' ),

			array(
				'title'     => __( 'Sport', 'sportspress' ),
				'id'        => 'sportspress_sport',
				'default'   => 'soccer',
				'type'      => 'groupselect',
				'options'   => $presets,
			),

			array(
				'title'     => __( 'Google Maps', 'sportspress' ),
				'id'        => 'sportspress_map_type',
				'default'   => 'ROADMAP',
				'type'      => 'select',
				'options'   => array(
					'ROADMAP' => __( 'Default', 'sportspress' ),
					'SATELLITE' => __( 'Satellite', 'sportspress' ),
					'HYBRID' => __( 'Hybrid', 'sportspress' ),
					'TERRAIN' => __( 'Terrain', 'sportspress' ),
				),
			),

			array( 'type' => 'sectionend', 'id' => 'general_options' ),

			array( 'title' => __( 'Styles and Scripts', 'sportspress' ), 'type' => 'title', 'desc' => '', 'id' => 'script_styling_options' ),

			array( 'type' 		=> 'frontend_styles' ),
		);

		if ( ( $styles = SP_Frontend_Scripts::get_styles() ) && array_key_exists( 'sportspress-general', $styles ) ):
			$settings = array_merge( $settings, array(
				array(
					'title' 	=> __( 'Align', 'sportspress' ),
					'id' 		=> 'sportspress_table_text_align',
					'default'	=> 'default',
					'type' 		=> 'radio',
					'options' => array(
						'default'	=> __( 'Default', 'sportspress' ),
						'left'		=> __( 'Left', 'sportspress' ),
						'center'	=> __( 'Center', 'sportspress' ),
						'right'		=> __( 'Right', 'sportspress' ),
					),
				),
				
				array(
					'title' 	=> __( 'Padding', 'sportspress' ),
					'id' 		=> 'sportspress_table_padding',
					'class' 	=> 'small-text',
					'default'	=> null,
					'placeholder' => __( 'Auto', 'sportspress' ),
					'desc' 		=> 'px',
					'type' 		=> 'number',
					'custom_attributes' => array(
						'step' 	=> 1
					),
				),
			));
		endif;

		$settings = array_merge( $settings, array(
			array(
				'title' 	=> __( 'Custom CSS', 'sportspress' ),
				'id' 		=> 'sportspress_custom_css',
				'css' 		=> 'width:100%; height: 130px;',
				'type' 		=> 'textarea',
			),

			array(
				'title'     => __( 'Scripts', 'sportspress' ),
				'desc' 		=> __( 'Live countdowns', 'sportspress' ),
				'id' 		=> 'sportspress_enable_live_countdowns',
				'default'	=> 'yes',
				'type' 		=> 'checkbox',
				'checkboxgroup'	=> 'start',
				'desc_tip'	=> __( 'This will enable a script allowing the countdowns to be animated.', 'sportspress' ),
			),

			array(
				'desc' 		=> __( 'Shortcode menu', 'sportspress' ),
				'id' 		=> 'sportspress_rich_editing',
				'default'	=> 'yes',
				'type' 		=> 'checkbox',
				'checkboxgroup'		=> 'end',
				'desc_tip'	=> __( 'This will enable a shortcode menu to be displayed in the visual editor.', 'sportspress' ),
			),

			array(
				'title'     => __( 'Tables', 'sportspress' ),
				'desc' 		=> __( 'Responsive', 'sportspress' ),
				'id' 		=> 'sportspress_enable_responsive_tables',
				'default'	=> 'yes',
				'type' 		=> 'checkbox',
				'checkboxgroup'	=> 'start',
			),

			array(
				'desc' 		=> __( 'Sortable', 'sportspress' ),
				'id' 		=> 'sportspress_enable_sortable_tables',
				'default'	=> 'yes',
				'type' 		=> 'checkbox',
				'checkboxgroup'		=> '',
			),

			array(
				'desc' 		=> __( 'Scrollable', 'sportspress' ),
				'id' 		=> 'sportspress_enable_scrollable_tables',
				'default'	=> 'yes',
				'type' 		=> 'checkbox',
				'checkboxgroup'		=> 'end',
			),
			
			array(
				'title' 	=> __( 'Header Offset', 'sportspress' ),
				'id' 		=> 'sportspress_header_offset',
				'class' 	=> 'small-text',
				'default'	=> null,
				'placeholder' => __( 'Auto', 'sportspress' ),
				'desc' 		=> 'px',
				'type' 		=> 'number',
				'custom_attributes' => array(
					'step' 	=> 1
				),
			),

			array( 'type' => 'sectionend', 'id' => 'script_styling_options' ),
		));
		
		return apply_filters( 'sportspress_general_settings', $settings ); // End general settings
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

		if ( isset( $_POST['timezone_string'] ) )
	    	update_option( 'timezone_string', $_POST['timezone_string'] );

	    update_option( 'sportspress_enable_frontend_css', isset( $_POST['sportspress_enable_frontend_css'] ) ? 'yes' : 'no' );

		if ( isset( $_POST['sportspress_frontend_css_primary'] ) ) {

			// Save settings
			$primary 		= ( ! empty( $_POST['sportspress_frontend_css_primary'] ) ) ? sp_format_hex( $_POST['sportspress_frontend_css_primary'] ) : '';
			$background 	= ( ! empty( $_POST['sportspress_frontend_css_background'] ) ) ? sp_format_hex( $_POST['sportspress_frontend_css_background'] ) : '';
			$text 			= ( ! empty( $_POST['sportspress_frontend_css_text'] ) ) ? sp_format_hex( $_POST['sportspress_frontend_css_text'] ) : '';
			$heading 		= ( ! empty( $_POST['sportspress_frontend_css_heading'] ) ) ? sp_format_hex( $_POST['sportspress_frontend_css_heading'] ) : '';
			$link 			= ( ! empty( $_POST['sportspress_frontend_css_link'] ) ) ? sp_format_hex( $_POST['sportspress_frontend_css_link'] ) : '';

			$colors = array(
				'primary' 		=> $primary,
				'background' 	=> $background,
				'text' 			=> $text,
				'heading' 		=> $heading,
				'link' 			=> $link,
			);

			update_option( 'sportspress_frontend_css_colors', $colors );
		}
	}

	/**
	 * Timezone settings
	 *
	 * @access public
	 * @return void
	 */
	public function timezone_setting() {
		$current_offset = get_option('gmt_offset');
		$tzstring = get_option('timezone_string');

		$check_zone_info = true;

		// Remove old Etc mappings. Fallback to gmt_offset.
		if ( false !== strpos($tzstring,'Etc/GMT') )
			$tzstring = '';

		if ( empty($tzstring) ) { // Create a UTC+- zone if no timezone string exists
			$check_zone_info = false;
			if ( 0 == $current_offset )
				$tzstring = 'UTC+0';
			elseif ($current_offset < 0)
				$tzstring = 'UTC' . $current_offset;
			else
				$tzstring = 'UTC+' . $current_offset;
		}
		$class = 'chosen-select' . ( is_rtl() ? ' chosen-rtl' : '' );
    	?>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="timezone_string"><?php _e( 'Timezone', 'sportspress' ); ?></label>
			</th>
            <td class="forminp">
				<legend class="screen-reader-text"><span><?php _e( 'Timezone', 'sportspress' ); ?></span></legend>
				<select id="timezone_string" name="timezone_string" class="<?php echo $class; ?>">
					<?php echo wp_timezone_choice($tzstring); ?>
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
				$colors = array_map( 'esc_attr', (array) get_option( 'sportspress_frontend_css_colors', array() ) );

				// Defaults
				if ( empty( $colors['primary'] ) ) $colors['primary'] = '#00a69c';
				if ( empty( $colors['background'] ) ) $colors['background'] = '#f4f4f4';
				if ( empty( $colors['text'] ) ) $colors['text'] = '#363f48';
				if ( empty( $colors['heading'] ) ) $colors['heading'] = '#ffffff';
	            if ( empty( $colors['link'] ) ) $colors['link'] = '#00a69c';

				// Show inputs
	    		$this->color_picker( __( 'Primary', 'sportspress' ), 'sportspress_frontend_css_primary', $colors['primary'] );
	    		$this->color_picker( __( 'Background', 'sportspress' ), 'sportspress_frontend_css_background', $colors['background'] );
	    		$this->color_picker( __( 'Text', 'sportspress' ), 'sportspress_frontend_css_text', $colors['text'] );
	    		$this->color_picker( __( 'Heading', 'sportspress' ), 'sportspress_frontend_css_heading', $colors['heading'] );
	    		$this->color_picker( __( 'Link', 'sportspress' ), 'sportspress_frontend_css_link', $colors['link'] );

				if ( ( $styles = SP_Frontend_Scripts::get_styles() ) && array_key_exists( 'sportspress-general', $styles ) ):
				    ?><br>
				    <label for="sportspress_enable_frontend_css">
						<input name="sportspress_enable_frontend_css" id="sportspress_enable_frontend_css" type="checkbox" value="1" <?php checked( get_option( 'sportspress_enable_frontend_css', 'yes' ), 'yes' ); ?>>
						<?php _e( 'Enable', 'sportspress' ); ?>
					</label>
				<?php endif; ?>
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
	 * @return void
	 */
	function color_picker( $name, $id, $value ) {
		echo '<div class="sp-color-box"><strong>' . esc_html( $name ) . '</strong>
	   		<input name="' . esc_attr( $id ). '" id="' . esc_attr( $id ) . '" type="text" value="' . esc_attr( $value ) . '" class="colorpick" /> <div id="colorPickerDiv_' . esc_attr( $id ) . '" class="colorpickdiv"></div>
	    </div>';
	}
}

endif;

return new SP_Settings_General();
