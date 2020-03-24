<?php
/*
Plugin Name: SportsPress Icons
Plugin URI: http://themeboy.com/
Description: Add vector performance icons to SportsPress.
Author: ThemeBoy
Author URI: http://themeboy.com/
Version: 2.7
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_Icons' ) ) :

/**
 * Main SportsPress Icons Class
 *
 * @class SportsPress_Icons
 * @version	2.7
 */
class SportsPress_Icons {

	/**
	 * @var array
	 */
	public $icons = array();

	/**
	 * Constructor
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();

		// Define icons
		$this->get_icons();

		add_filter( 'sportspress_enqueue_styles', array( $this, 'add_styles' ) );
		add_filter( 'sportspress_performance_icon', array( $this, 'icon' ), 10, 2 );
		add_filter( 'sportspress_event_performance_icons', array( $this, 'replace_icons' ), 10, 3 );
		add_filter( 'admin_post_thumbnail_html', array( $this, 'sp_admin_post_thumbnail_html' ), 10, 2 );
		add_action( 'sportspress_process_sp_performance_meta', array( $this, 'save' ), 10, 2 );
		add_action( 'sportspress_process_sp_statistic_meta', array( $this, 'save' ), 10, 2 );
	}

	/**
	 * Define constants.
	*/
	private function define_constants() {
		if ( !defined( 'SP_ICONS_VERSION' ) )
			define( 'SP_ICONS_VERSION', '2.7' );

		if ( !defined( 'SP_ICONS_URL' ) )
			define( 'SP_ICONS_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'SP_ICONS_DIR' ) )
			define( 'SP_ICONS_DIR', plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Add stylesheet.
	*/
	public static function add_styles( $styles = array() ) {
		$styles['sportspress-icons'] = array(
			'src'     => str_replace( array( 'http:', 'https:' ), '', SP()->plugin_url() ) . '/assets/css/icons.css',
			'deps'    => '',
			'version' => SP_ICONS_VERSION,
			'media'   => 'all'
		);
		return $styles;
	}

	/**
	 * Define icons.
	*/
	private function get_icons() {
		$this->icons = apply_filters( 'sportspress_icons', array(
			'soccerball',
			'soccerball-alt',
			'baseball',
			'baseball-alt',
			'basketball',
			'golfball',
			'cricketball',
			'bowling',
			'ice-hockey',
			'football',
			'poolball',
			'table-tennis',
			'tennis',
			'racing-flag',
			'shoe',
			'card',
			'league',
			'shield',
			'tshirt',
			'whistle',
			'time',
			'friendly',
			'sub',
			'update',
			'undo',
			'redo',
			'marker',
			'no',
			'heart',
			'star-filled',
		) );
	}

	/**
	 * Display vector icon.
	*/
	public function icon( $icon = '', $id = 0 ) {
		if ( ! $id ) return $icon;
		$meta = get_post_meta( $id, 'sp_icon', true );
		if ( null !== $meta && in_array( $meta, $this->icons ) ) {
			$color = get_post_meta( $id, 'sp_color', true );
			$icon = '<i class="sp-icon-' . $meta . '" style="color:' . $color . ' !important"></i>';
		}
		return $icon;
	}

	/**
	 * Replace icons with vectors when available.
	*/
	public function replace_icons( $icons = '', $id = 0, $value = 0 ) {
		if ( ! $id || ! $value ) return $icons;
		$icon = get_post_meta( $id, 'sp_icon', true );
		if ( null !== $icon && in_array( $icon, $this->icons ) ) {
			$title = sp_get_singular_name( $id );
			$color = get_post_meta( $id, 'sp_color', true );
			preg_match( '#\((.*?)\)#', $value, $match );
			if ( ! empty( $match ) && isset( $match[1] ) ) {
				$icons = '<i class="sp-icon-' . $icon . '" title="' . $title . '" style="color:' . $color . ' !important"></i> ' . $match[1] . '<br>';
			} else {
				$icons = str_repeat( '<i class="sp-icon-' . $icon . '" title="' . $title . '" style="color:' . $color . ' !important"></i> ', intval( $value ) );
			}
		}
		return $icons;
	}

	/**
	 * Post thumbnail HTML.
	*/
	public function sp_admin_post_thumbnail_html( $content = '', $id = 0 ) {
		// Bypass if no ID
		if ( ! $id ) return $content;

		// Bypass if not performance or statistic post type
		$post_type = get_post_type( $id );
		if ( 'sp_performance' !== $post_type && 'sp_statistic' !== $post_type ) return $content;

		// Detect if image uploaded
		$is_uploaded = isset( $_POST['thumbnail_id'] );

		// Enqueue scripts
		wp_enqueue_script( 'sp_iconpicker', SP()->plugin_url() . '/assets/js/admin/iconpicker.js', array( 'jquery', 'wp-color-picker', 'iris' ), SP_ICONS_VERSION, true );

		// Get selected icon
		$has_icon = has_post_thumbnail( $id );
		if ( $has_icon ) $selected = null;
		else $selected = $is_uploaded ? null : get_post_meta( $id, 'sp_icon', true );

		// Generate icon selector
		$icons = '';
		foreach ( $this->icons as $icon ) {
			$icons .= '<label class="button"><input name="sp_icon" type="radio" value="' . $icon . '" ' . checked( $selected, $icon, false ) . '></input><i class="sp-icon-' . $icon . '"></i></label>';
		}

		$icons .= '<label class="button"><input name="sp_icon" type="radio" value="" ' . checked( $selected, null, false ) . '></input>' . __( 'Image', 'sportspress' ) . '</label>';

		// Get color value
		$value = get_post_meta( $id, 'sp_color', true );
		if ( empty( $value ) ) $value = '111111';

		$color = '<div class="sp-color-box-for-icon"><input name="sp_color" id="sp_color" type="text" value="' . esc_attr( $value ) . '" size="7" class="colorpick" /> <div id="colorPickerDiv" class="colorpickdiv"></div></div>';

		$content = '<p><strong>' . __( 'Select Icon', 'sportspress' ) . '</strong></p>
			<p class="sp-icons">' . $icons . '</p>
			<div class="sp-para sp-custom-colors' . ( null == $selected ? ' hidden' : '' ) . '"><label data-sp-colors="' . $value . '"><strong>' . __( 'Customize', 'sportspress' ) . '</strong><br></label>' . $color . '</div>
			<div class="sp-custom-thumbnail' . ( null == $selected ? '' : ' hidden' ) . '">' . $content . '</div>';
		return $content;
	}

	/**
	 * Save meta boxes data
	 */
	public static function save( $post_id, $post ) {
		update_post_meta( $post_id, 'sp_icon', sp_array_value( $_POST, 'sp_icon', null ) );
		update_post_meta( $post_id, 'sp_color', sp_array_value( $_POST, 'sp_color', null ) );
		if ( null != sp_array_value( $_POST, 'sp_icon', null ) ) {
			delete_post_thumbnail( $post );
		}
	}
}

endif;

new SportsPress_Icons();