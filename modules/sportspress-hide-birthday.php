<?php
/*
Plugin Name: SportsPress Hide Birthday per Player/Staff
Plugin URI: http://themeboy.com/
Description: Add an option to hide birthday/age of a player/staff.
Author: ThemeBoy
Author URI: http://themeboy.com/
Version: 2.6.21
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_Hide_Birthday' ) ) :

/**
 * Main SportsPress Hide Birthday Class
 *
 * @class SportsPress_Hide_Birthday
 * @version	2.6.21
 */
class SportsPress_Hide_Birthday {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();

		add_action( 'post_submitbox_misc_actions', array( $this, 'section' ) );
		add_action( 'sportspress_process_sp_player_meta', array( $this, 'save' ), 10, 1 );
		add_action( 'sportspress_process_sp_staff_meta', array( $this, 'save' ), 10, 1 );
	}

	/**
	 * Define constants.
	*/
	private function define_constants() {
		if ( !defined( 'SP_HIDE_BIRTHDAY_VERSION' ) )
			define( 'SP_HIDE_BIRTHDAY_VERSION', '2.6.21' );

		if ( !defined( 'SP_HIDE_BIRTHDAY_URL' ) )
			define( 'SP_HIDE_BIRTHDAY_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'SP_HIDE_BIRTHDAY_DIR' ) )
			define( 'SP_HIDE_BIRTHDAY_DIR', plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Add hide birthday checkbox to submit box.
	 */
	public function section() {
		if ( !in_array( get_post_type(), array( 'sp_player', 'sp_staff' ) ) ) return;
		$hide_birthday = get_post_meta( get_the_ID(), 'sp_hide_birthday', true );
		?>
		<div class="misc-pub-section sp-pub-player-show-birthday">
			<input type="checkbox" name="sp_hide_birthday" class="post-birthday" id="post-birthday" value="true" <?php checked( 'true', $hide_birthday ); ?>> <label for="post-birthday" class="post-birthday"><?php _e( 'Hide Birthday/Age?', 'sportspress' ); ?></label>
		</div>
		<?php
	}
	
	/**
	 * Save show birthday option.
	 */
	public function save( $post_id ) {
		update_post_meta( $post_id, 'sp_hide_birthday', sp_array_value( $_POST, 'sp_hide_birthday', 'false' ) );
	}
}

endif;

new SportsPress_Hide_Birthday();
