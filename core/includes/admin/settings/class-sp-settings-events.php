<?php
/**
 * SportsPress Event Settings
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin
 * @version     1.3
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

		return apply_filters( 'sportspress_event_settings', array(

			array( 'title' => __( 'Event Options', 'sportspress' ), 'type' => 'title','desc' => '', 'id' => 'event_options' ),

			array(
				'title'     => __( 'Link', 'sportspress' ),
				'desc' 		=> __( 'Link events', 'sportspress' ),
				'id' 		=> 'sportspress_link_events',
				'default'	=> 'yes',
				'type' 		=> 'checkbox',
				'checkboxgroup'		=> 'start',
			),

			array(
				'desc' 		=> __( 'Link venues', 'sportspress' ),
				'id' 		=> 'sportspress_link_venues',
				'default'	=> 'no',
				'type' 		=> 'checkbox',
				'checkboxgroup'		=> 'end',
			),
			
			array(
				'title' 	=> __( 'Limit', 'sportspress' ),
				'id' 		=> 'sportspress_event_teams',
				'class' 	=> 'small-text',
				'default'	=> '2',
				'desc' 		=> __( 'teams', 'sportspress' ),
				'type' 		=> 'number',
				'custom_attributes' => array(
					'min' 	=> 1,
					'step' 	=> 1
				),
			),
			
			array( 'type' => 'delimiter' ),

			array(
				'title'     => __( 'Teams', 'sportspress' ),
				'desc' 		=> __( 'Display logos', 'sportspress' ),
				'id' 		=> 'sportspress_event_show_logos',
				'default'	=> 'yes',
				'type' 		=> 'checkbox',
				'checkboxgroup'	=> 'start',
			),

			array(
				'desc' 		=> __( 'Display players', 'sportspress' ),
				'id' 		=> 'sportspress_event_show_players',
				'default'	=> 'yes',
				'type' 		=> 'checkbox',
				'checkboxgroup'	=> '',
			),

			array(
				'desc' 		=> __( 'Display total', 'sportspress' ),
				'id' 		=> 'sportspress_event_show_total',
				'default'	=> 'no',
				'type' 		=> 'checkbox',
				'checkboxgroup'		=> 'end',
			),

			array(
				'title' 	=> __( 'Player Performance', 'sportspress' ),
				'id' 		=> 'sportspress_event_performance_mode',
				'default'	=> 'values',
				'type' 		=> 'radio',
				'options' => array(
					'values'	=> __( 'Values', 'sportspress' ),
					'icons'		=> __( 'Icons', 'sportspress' ),
				),
			),

			array(
				'title'     => __( 'Venue', 'sportspress' ),
				'desc' 		=> __( 'Display maps', 'sportspress' ),
				'id' 		=> 'sportspress_event_show_maps',
				'default'	=> 'yes',
				'type' 		=> 'checkbox',
				'checkboxgroup'	=> 'start',
			),

			array(
				'title'     => __( 'Staff', 'sportspress' ),
				'desc' 		=> __( 'Display staff', 'sportspress' ),
				'id' 		=> 'sportspress_event_show_staff',
				'default'	=> 'yes',
				'type' 		=> 'checkbox',
				'checkboxgroup'	=> 'start',
			),
			
			array(
				'title' 	=> __( 'Full Time', 'sportspress' ),
				'id' 		=> 'sportspress_event_minutes',
				'class' 	=> 'small-text',
				'default'	=> '90',
				'desc' 		=> __( 'mins', 'sportspress' ),
				'type' 		=> 'number',
				'custom_attributes' => array(
					'min' 	=> 0,
					'step' 	=> 1
				),
			),

			array( 'type' => 'sectionend', 'id' => 'event_options' ),

			array( 'title' => __( 'Event List', 'sportspress' ), 'type' => 'title', 'id' => 'event_list_options' ),

			array(
				'title'     => __( 'Pagination', 'sportspress' ),
				'desc' 		=> __( 'Paginate', 'sportspress' ),
				'id' 		=> 'sportspress_event_list_paginated',
				'default'	=> 'yes',
				'type' 		=> 'checkbox',
			),
			
			array(
				'title' 	=> __( 'Limit', 'sportspress' ),
				'id' 		=> 'sportspress_event_list_rows',
				'class' 	=> 'small-text',
				'default'	=> '10',
				'desc' 		=> __( 'events', 'sportspress' ),
				'type' 		=> 'number',
				'custom_attributes' => array(
					'min' 	=> 1,
					'step' 	=> 1
				),
			),

			array( 'type' => 'sectionend', 'id' => 'event_list_options' ),

			array( 'title' => __( 'Event Blocks', 'sportspress' ), 'type' => 'title', 'id' => 'event_blocks_options' ),

			array(
				'title'     => __( 'Details', 'sportspress' ),
				'desc' 		=> __( 'Display league', 'sportspress' ),
				'id' 		=> 'sportspress_event_blocks_show_league',
				'default'	=> 'no',
				'type' 		=> 'checkbox',
				'checkboxgroup'	=> 'start',
			),

			array(
				'desc' 		=> __( 'Display season', 'sportspress' ),
				'id' 		=> 'sportspress_event_blocks_show_season',
				'default'	=> 'no',
				'type' 		=> 'checkbox',
				'checkboxgroup'		=> 'end',
			),

			array(
				'title'     => __( 'Pagination', 'sportspress' ),
				'desc' 		=> __( 'Paginate', 'sportspress' ),
				'id' 		=> 'sportspress_event_blocks_paginated',
				'default'	=> 'yes',
				'type' 		=> 'checkbox',
			),
			
			array(
				'title' 	=> __( 'Limit', 'sportspress' ),
				'id' 		=> 'sportspress_event_blocks_rows',
				'class' 	=> 'small-text',
				'default'	=> '10',
				'desc' 		=> __( 'events', 'sportspress' ),
				'type' 		=> 'number',
				'custom_attributes' => array(
					'min' 	=> 1,
					'step' 	=> 1
				),
			),

			array( 'type' => 'sectionend', 'id' => 'event_list_options' ),

		)); // End event settings
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
		$limit = get_option( 'sportspress_event_teams', 2 );
		$example = str_repeat( __( 'Team', 'sportspress' ) . ' %1$s ', $limit );
		$example = rtrim( $example, ' %1$s ' );
		?>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<?php _e( 'Delimiter', 'sportspress' ); ?>
			</th>
		    <td class="forminp">
				<fieldset class="sp-custom-input-wrapper">
					<legend class="screen-reader-text"><span><?php _e( 'Delimiter', 'sportspress' ); ?></span></legend>
					<?php $delimiters = array( 'vs', 'v', '&mdash;', '/' ); ?>
					<?php foreach ( $delimiters as $delimiter ): ?>
						<label title="<?php echo $delimiter; ?>"><input type="radio" class="preset" name="sportspress_event_teams_delimiter_preset" value="<?php echo $delimiter; ?>" data-example="<?php printf( $example, $delimiter ); ?>" <?php checked( $delimiter, $selection ); ?>> <span><?php printf( $example, $delimiter ); ?></span></label><br>
					<?php endforeach; ?>
					<label><input type="radio" class="preset" name="sportspress_event_teams_delimiter_preset" value="\c\u\s\t\o\m" <?php checked( false, in_array( $selection, $delimiters ) ); ?>> <?php _e( 'Custom:', 'sportspress' ); ?> </label><input type="text" class="small-text value" name="sportspress_event_teams_delimiter" value="<?php echo $selection; ?>" data-example-format="<?php printf( $example, '__val__' ); ?>">
					<span class="example"><?php printf( $example, $selection ); ?></span>
				</fieldset>
			</td>
		</tr>
		<?php
	}
}

endif;

return new SP_Settings_Events();
