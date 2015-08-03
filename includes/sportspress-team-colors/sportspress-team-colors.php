<?php
/*
Plugin Name: SportsPress Team Colors
Plugin URI: http://tboy.co/pro
Description: Add team colors to SportsPress.
Author: ThemeBoy
Author URI: http://themeboy.com
Version: 1.7
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_Team_Colors' ) ) :

/**
 * Main SportsPress Team Colors Class
 *
 * @class SportsPress_Team_Colors
 * @version	1.7
 */
class SportsPress_Team_Colors {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();

		// Hooks
		add_filter( 'option_themeboy', array( $this, 'css' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'sportspress_process_sp_team_meta', array( $this, 'meta_box_save' ), 15, 2 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_styles' ) );
		add_action( 'manage_sp_team_posts_custom_column', array( $this, 'custom_columns' ), 5, 2 );
	}

	/**
	 * Define constants
	*/
	private function define_constants() {
		if ( !defined( 'SP_TEAM_COLORS_VERSION' ) )
			define( 'SP_TEAM_COLORS_VERSION', '1.7' );

		if ( !defined( 'SP_TEAM_COLORS_URL' ) )
			define( 'SP_TEAM_COLORS_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'SP_TEAM_COLORS_DIR' ) )
			define( 'SP_TEAM_COLORS_DIR', plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Filter frontend CSS colors.
	 *
	 * @access public
	 * @return void
	 */
	public function css( $colors = array() ) {
		global $post;

		if ( is_singular( 'sp_team' ) ) {
			$team = $post->ID;
		} elseif ( is_singular( array( 'sp_event', 'sp_calendar', 'sp_player', 'sp_list', 'sp_staff' ) ) ) {
			$team = get_post_meta( $post->ID, 'sp_team', true );
		} else {
			return $colors;
		}
		$team_colors = (array) get_post_meta( $team, 'sp_colors', true );

		return array_merge( (array) $colors, $team_colors );
	}

	/**
	 * Add Meta boxes
	 */
	public function add_meta_boxes() {
		add_meta_box( 'sp_colorssdiv', __( 'Team Colors', 'sportspress' ), array( $this, 'meta_box_output' ), 'sp_team', 'normal', 'high' );
	}

	/**
	 * Output the metabox
	 */
	public function meta_box_output( $post ) {
    	wp_enqueue_script( 'sp_team_colors_admin', SP_TEAM_COLORS_URL . '/js/admin.js', array( 'jquery', 'wp-color-picker', 'iris' ), SP_TEAM_COLORS_VERSION, true );

		// Global settings
		$options = array_map( 'esc_attr', (array) get_option( 'themeboy', array() ) );
		if ( empty( $options ) ) $options = array_map( 'esc_attr', (array) get_option( 'sportspress_frontend_css_colors', array() ) );

		// Defaults
		$options = array_intersect_key( $options, array(
			'primary' => '#2b353e',
			'background' => '#f4f4f4',
			'text' => '#222222',
			'heading' => '#ffffff',
			'link' => '#00a69c',
		) );

        // Team settings
		$colors = (array) get_post_meta( $post->ID, 'sp_colors', true );

		foreach( $options as $name => $color ) {
			if ( array_key_exists( $name, $colors ) ) {
				$enabled = true;
				$value = $colors[ $name ];
			} else {
				$enabled = false;
				$value = $color;
			}
			$this->color_picker( __( ucwords( $name ), 'sportspress' ), $name, $value, $enabled );
		}
		?>
		<div class="clear"></div>
		<?php
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
	public static function color_picker( $name, $id, $value, $enabled = false ) {
		?>
		<div class="sp-team-color-box">
			<label class="selectit">
				<input name="sp_color_enable[<?php echo esc_attr( $id ); ?>]" id="sp_color_enable_<?php echo esc_attr( $id ); ?>" type="checkbox" value="1" <?php checked( $enabled ); ?>>
				<strong><?php echo esc_html( $name ); ?></strong>
			</label>
	   		<input name="sp_color[<?php echo esc_attr( $id ); ?>]" id="sp_color_<?php echo esc_attr( $id ); ?>" type="text" value="<?php echo esc_attr( $value ); ?>" class="colorpick" /> <div id="colorPickerDiv_<?php echo esc_attr( $id ); ?>" class="colorpickdiv"></div>
	    </div>
		<?php
	}

	/**
	 * Save meta box data
	 */
	public static function meta_box_save( $post_id, $post ) {
		$meta = array();
		if ( isset( $_POST['sp_color'] ) && isset( $_POST['sp_color_enable'] ) ) {
			$enabled = (array) $_POST['sp_color_enable'];
			$colors = (array) $_POST['sp_color'];
			foreach ( $enabled as $key => $value ) {
				if ( array_key_exists( $key, $colors ) ) {
					$meta[ $key ] = $colors[ $key ];
				}
			}
		}
		update_post_meta( $post_id, 'sp_colors', $meta );
	}

	/**
	 * Save meta box data
	 */
	public static function save( $post_id, $post ) {
		$colors = array();

		update_post_meta( $post_id, 'sp_colors', $colors );
	}

	/**
	 * Enqueue styles
	 */
	public function admin_styles() {
		$screen = get_current_screen();

		if ( in_array( $screen->id, sp_get_screen_ids() ) ) {
			wp_enqueue_style( 'sportspress-team-colors-admin', SP_TEAM_COLORS_URL . 'css/admin.css', array(), SP_TEAM_COLORS_VERSION );
		}
	}

	/**
	 * Define our custom columns shown in admin.
	 * @param  string $column
	 */
	public function custom_columns( $column, $post_id ) {
		switch ( $column ) {
			case 'sp_icon':
				$colors = get_post_meta( $post_id, 'sp_colors', true );
				if ( ! $colors || ! count( $colors ) ) break;
				$colors = array_unique( $colors );
				$width = floor( 30 / count( $colors ) );
				echo '<ul class="color-blocks">';
				foreach( $colors as $color ) {
					$this->color_block( $color, $width );
				}
				echo '</ul>';
				break;
		}
	}

	/**
	 * Display a color block.
	 * @param  string $color
	 */
	public function color_block( $color, $width ) {
		echo '<li title="' . $color . '" style="background-color: ' . $color . '; width:' . $width . 'px;">' . $color . '</li>';
	}
}

endif;

if ( get_option( 'sportspress_load_team_colors_module', 'yes' ) == 'yes' ) {
	new SportsPress_Team_Colors();
}
