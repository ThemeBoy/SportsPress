<?php
/**
 * SportsPress Event Settings
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin
 * @version     0.7
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'SP_Settings_Events' ) ) :

/**
 * SP_Settings_Events
 */
class SP_Settings_Events extends SP_Settings_Page {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->id    = 'events';
		$this->label = __( 'Events', 'sportspress' );

		add_filter( 'sportspress_settings_tabs_array', array( $this, 'add_settings_page' ), 20 );
		add_action( 'sportspress_settings_' . $this->id, array( $this, 'output' ) );
		add_action( 'sportspress_admin_field_delimiter', array( $this, 'delimiter_setting' ) );
		add_action( 'sportspress_settings_save_' . $this->id, array( $this, 'save' ) );
	}

	/**
	 * Get settings array
	 *
	 * @return array
	 */
	public function get_settings() {

		$settings = array(

			array(	'title' => __( 'Event Options', 'sportspress' ), 'type' => 'title','desc' => '', 'id' => 'event_options' ),
			
			array( 'type' => 'delimiter' ),

			array(
				'title'     => __( 'Venue', 'sportspress' ),
				'desc' 		=> __( 'Display maps', 'sportspress' ),
				'id' 		=> 'sportspress_event_show_maps',
				'default'	=> 'yes',
				'type' 		=> 'checkbox',
				'checkboxgroup'	=> 'start',
			),

			array(
				'desc' 		=> __( 'Link venues', 'sportspress' ),
				'id' 		=> 'sportspress_event_link_venues',
				'default'	=> 'no',
				'type' 		=> 'checkbox',
				'checkboxgroup'		=> 'end',
			),

			array(
				'title'     => __( 'Player Performance', 'sportspress' ),
				'desc' 		=> __( 'Link players', 'sportspress' ),
				'id' 		=> 'sportspress_event_link_players',
				'default'	=> 'yes',
				'type' 		=> 'checkbox',
			),

			array( 'type' => 'sectionend', 'id' => 'event_options' ),

			array( 'title' => __( 'Calendars', 'sportspress' ), 'type' => 'title', 'id' => 'calendar_options' ),

			array(
				'title'     => __( 'Pagination', 'sportspress' ),
				'desc' 		=> __( 'Paginate', 'sportspress' ),
				'id' 		=> 'sportspress_calendar_paginated',
				'default'	=> 'yes',
				'type' 		=> 'checkbox',
			),
			
			array(
				'title' 	=> __( 'Limit', 'sportspress' ),
				'id' 		=> 'sportspress_calendar_rows',
				'class' 	=> 'small-text',
				'default'	=> '10',
				'desc' 		=> __( 'events', 'sportspress' ),
				'type' 		=> 'number',
				'custom_attributes' => array(
					'min' 	=> 1,
					'step' 	=> 1
				),
			),

			array( 'type' => 'sectionend', 'id' => 'calendar_options' ),

			array( 'title' => __( 'Text', 'sportspress' ), 'type' => 'title', 'desc' => __( 'The following options affect how words are displayed on the frontend.', 'sportspress' ), 'id' => 'text_options' ),

		);

		$strings = sp_get_text_options();

		foreach ( sp_array_value( $strings, 'event', array() ) as $key => $value ):
			$settings[] = array(
				'title'   => $value,
				'id'      => 'sportspress_event_' . $key . '_text',
				'default' => '',
				'placeholder' => $value,
				'type'    => 'text',
			);
		endforeach;

		$settings[] = array( 'type' => 'sectionend', 'id' => 'text_options' );

		return apply_filters( 'sportspress_event_settings', $settings ); // End event settings
	}

	/**
	 * Save settings
	 */
	public function save() {
		$settings = $this->get_settings();
		SP_Admin_Settings::save_fields( $settings );
		
		if ( isset( $_POST['sportspress_event_teams_delimiter'] ) )
	    	update_option( 'sportspress_event_teams_delimiter', $_POST['sportspress_event_teams_delimiter'] );
	}

	/**
	 * Delimiter settings
	 *
	 * @access public
	 * @return void
	 */
	public function delimiter_setting() {
		$selection = get_option( 'sportspress_event_teams_delimiter', 'vs' );

		?>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<?php _e( 'Delimiter', 'sportspress' ); ?>
			</th>
		    <td class="forminp">
				<fieldset class="sp-custom-input-wrapper">
					<legend class="screen-reader-text"><span><?php _e( 'Delimiter', 'sportspress' ); ?></span></legend>
					<?php $delimiters = array( 'vs', 'v', '—', '/' ); ?>
					<?php foreach ( $delimiters as $delimiter ): ?>
						<label title="<?php echo $delimiter; ?>"><input type="radio" class="preset" name="sportspress_event_teams_delimiter_preset" value="<?php echo $delimiter; ?>" data-example="<?php _e( 'Team', 'sportspress' ); ?> <?php echo $delimiter; ?> <?php _e( 'Team', 'sportspress' ); ?>" <?php checked( $delimiter, $selection ); ?>> <span><?php _e( 'Team', 'sportspress' ); ?> <?php echo $delimiter; ?> <?php _e( 'Team', 'sportspress' ); ?></span></label><br>
					<?php endforeach; ?>
					<label><input type="radio" class="preset" name="sportspress_event_teams_delimiter_preset" value="\c\u\s\t\o\m" <?php checked( false, in_array( $selection, $delimiters ) ); ?>> <?php _e( 'Custom:', 'sportspress' ); ?> </label><input type="text" class="small-text value" name="sportspress_event_teams_delimiter" value="<?php echo $selection; ?>" data-example-format="<?php _e( 'Team', 'sportspress' ); ?> __val__ <?php _e( 'Team', 'sportspress' ); ?>">
					<span class="example"><?php _e( 'Team', 'sportspress' ); ?> <?php echo $selection; ?> <?php _e( 'Team', 'sportspress' ); ?></span>
				</fieldset>
			</td>
		</tr>
		<?php
	}
}

endif;

return new SP_Settings_Events();
