<?php
/*
Plugin Name: SportsPress Birthdays
Plugin URI: http://themeboy.com/
Description: Add birthdays to players and staff.
Author: ThemeBoy
Author URI: http://themeboy.com/
Version: 2.7.1
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_Birthdays' ) ) :

/**
 * Main SportsPress Birthdays Class
 *
 * @class SportsPress_Birthdays
 * @version	2.7.1
 */
class SportsPress_Birthdays {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();

		add_filter( 'gettext', array( $this, 'gettext' ), 20, 3 );
		add_filter( 'sportspress_text', array( $this, 'add_text_options' ) );
		add_filter( 'sportspress_player_options', array( $this, 'add_player_options' ) );
		add_filter( 'sportspress_staff_options', array( $this, 'add_staff_options' ) );
		add_filter( 'sportspress_player_details', array( $this, 'add_player_details' ), 20, 2 );
		add_filter( 'sportspress_staff_details', array( $this, 'add_staff_details' ), 20, 2 );

		add_action( 'sportspress_widgets', array( $this, 'widgets' ) );
		add_action( 'sportspress_list_general_columns', array( $this, 'columns' ), 10, 1 ); 
	}

	/**
	 * Define constants.
	*/
	private function define_constants() {
		if ( !defined( 'SP_BIRTHDAYS_VERSION' ) )
			define( 'SP_BIRTHDAYS_VERSION', '2.7.1' );

		if ( !defined( 'SP_BIRTHDAYS_URL' ) )
			define( 'SP_BIRTHDAYS_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'SP_BIRTHDAYS_DIR' ) )
			define( 'SP_BIRTHDAYS_DIR', plugin_dir_path( __FILE__ ) );
	}

	/** 
	 * Text filter.
	 */
	public function gettext( $translated_text, $untranslated_text, $domain ) {
		if ( ! is_admin() ) return $translated_text;

		global $typenow;
		
		if ( 'default' == $domain && in_array( $typenow, array( 'sp_player', 'sp_staff', 'sp_official' ) ) ):
			switch ( $untranslated_text ):
				case 'Scheduled for: <b>%1$s</b>':
				case 'Published on: <b>%1$s</b>':
				case 'Schedule for: <b>%1$s</b>':
				case 'Publish on: <b>%1$s</b>':
					return __( 'Birthday: <b>%1$s</b>', 'sportspress' );
				case 'Publish <b>immediately</b>':
					return __( 'Birthday', 'sportspress' );
				case 'M j, Y @ G:i':
					return 'M j, Y';
				case '%1$s %2$s, %3$s @ %4$s : %5$s':
					$hour = '<input type="hidden" id="hh" name="hh" value="00" readonly />';
					$minute = '<input type="hidden" id="mn" name="mn" value="00" readonly />';
					return '%1$s %2$s, %3$s' . $hour . $minute;
			endswitch;
		endif;
		
		return $translated_text;
	}

	/**
	 * Add text options 
	 */
	public function add_text_options( $options = array() ) {
		return array_merge( $options, array(
			__( 'Age', 'sportspress' ),
			__( 'Birthday', 'sportspress' ),
		) );
	}

	/**
	 * Add options to player settings page.
	 *
	 * @return array
	 */
	public function add_player_options( $options ) {
		$options = array_merge( $options, array(
			array(
				'title'     => __( 'Birthday', 'sportspress' ),
				'desc' 		=> __( 'Display birthday', 'sportspress' ),
				'id' 		=> 'sportspress_player_show_birthday',
				'default'	=> 'no',
				'type' 		=> 'checkbox',
				'checkboxgroup'		=> 'start',
			),

			array(
				'desc' 		=> __( 'Display age', 'sportspress' ),
				'id' 		=> 'sportspress_player_show_age',
				'default'	=> 'no',
				'type' 		=> 'checkbox',
				'checkboxgroup'		=> 'end',
			),
		) );

		return $options;
	}

	/**
	 * Add options to staff settings page.
	 *
	 * @return array
	 */
	public function add_staff_options( $options ) {
		$options = array_merge( $options, array(
			array(
				'title'     => __( 'Birthday', 'sportspress' ),
				'desc' 		=> __( 'Display birthday', 'sportspress' ),
				'id' 		=> 'sportspress_staff_show_birthday',
				'default'	=> 'no',
				'type' 		=> 'checkbox',
				'checkboxgroup'		=> 'start',
			),

			array(
				'desc' 		=> __( 'Display age', 'sportspress' ),
				'id' 		=> 'sportspress_staff_show_age',
				'default'	=> 'no',
				'type' 		=> 'checkbox',
				'checkboxgroup'		=> 'end',
			),
		) );

		return $options;
	}

	/**
	 * Add data to player details template.
	 *
	 * @return array
	 */
	public function add_player_details( $data, $post_id ) {
		if ( 'yes' == get_option( 'sportspress_player_show_birthday', 'no' ) ) {
			$data[ __( 'Birthday', 'sportspress' ) ] = get_the_date( get_option( 'date_format' ), $post_id );
		}

		if ( 'yes' == get_option( 'sportspress_player_show_age', 'no' ) ) {
			$data[ __( 'Age', 'sportspress' ) ] = $this->get_age( get_the_date( 'm-d-Y', $post_id ) );
		}

		return $data;
	}

	/**
	 * Add data to staff details template.
	 *
	 * @return array
	 */
	public function add_staff_details( $data, $post_id ) {
		if ( 'yes' == get_option( 'sportspress_staff_show_birthday', 'no' ) ) {
			$data[ __( 'Birthday', 'sportspress' ) ] = get_the_date( get_option( 'date_format' ), $post_id );
		}

		if ( 'yes' == get_option( 'sportspress_staff_show_age', 'no' ) ) {
			$data[ __( 'Age', 'sportspress' ) ] = $this->get_age( get_the_date( 'm-d-Y', $post_id ) );
		}

		return $data;
	}

	/**
	 * Register widgets
	 */
	public static function widgets() {
		include_once( SP()->plugin_path() . '/includes/widgets/class-sp-widget-birthdays.php' );
	}
	
	/**
	 * Add more General Columns at Player Lists
	 */
	public static function columns( $selected ) {
		?>
		<li>
			<label class="selectit">
				<input value="dob" type="checkbox" name="sp_columns[]" id="sp_columns_dob" <?php checked( in_array( 'dob', $selected ) ); ?>>
				<?php _e( 'Date of Birth', 'sportspress' ); ?>
			</label>
		</li>
		<li>
			<label class="selectit">
				<input value="age" type="checkbox" name="sp_columns[]" id="sp_columns_age" <?php checked( in_array( 'age', $selected ) ); ?>>
				<?php _e( 'Age', 'sportspress' ); ?>
			</label>
		</li>
		<?php
	}

	/**
	 * Get age from date.
 	 * Adapted from http://stackoverflow.com/questions/3776682/php-calculate-age.
	 *
	 * @return int
	 */
	public static function get_age( $date ) {
		$date = explode( '-', $date );
		$age = ( date( 'md', date( 'U', mktime( 0, 0, 0, $date[0], $date[1], $date[2] ) ) ) > date('md')
			? ( ( date( 'Y' ) - $date[2] ) - 1 )
			: ( date( 'Y' ) - $date[2] ) );
		return $age;
	}
}

endif;

new SportsPress_Birthdays();
