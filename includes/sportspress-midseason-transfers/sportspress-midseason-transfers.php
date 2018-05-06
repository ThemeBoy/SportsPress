<?php
/*
Plugin Name: SportsPress Midseason Transfers
Plugin URI: http://tboy.co/pro
Description: Adds Midseason Transfers to SportsPress players.
Author: ThemeBoy
Author URI: http://themeboy.com
Version: 2.6
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_Midseason_Transfers' ) ) :

/**
 * Main SportsPress Midseason Transfers Class
 *
 * @class SportsPress_Midseason_Transfers
 * @version	2.6
 *
 */
class SportsPress_Midseason_Transfers {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();

		// Hooks
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'sportspress_meta_box_player_statistics_table_header_row', array( $this, 'placeholder_cell' ), 10, 2 );
		add_action( 'sportspress_meta_box_player_statistics_table_footer_row', array( $this, 'placeholder_cell' ), 10, 2 );
		add_action( 'sportspress_meta_box_player_statistics_table_row', array( $this, 'row' ), 10, 3 );

		add_filter( 'sportspress_player_data_season_ids', array( $this, 'season_ids' ), 10, 2 );
		add_filter( 'sportspress_meta_box_player_statistics_row_classes', array( $this, 'classes' ), 10, 3 );
		add_filter( 'sportspress_meta_box_player_statistics_season_name', array( $this, 'season_name' ), 10, 4 );
	}

	/**
	 * Define constants
	 */
	private function define_constants() {
		if ( !defined( 'SP_MIDSEASON_TRANSFERS_VERSION' ) )
			define( 'SP_MIDSEASON_TRANSFERS_VERSION', '2.6.0' );

		if ( !defined( 'SP_MIDSEASON_TRANSFERS_URL' ) )
			define( 'SP_MIDSEASON_TRANSFERS_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'SP_MIDSEASON_TRANSFERS_DIR' ) )
			define( 'SP_MIDSEASON_TRANSFERS_DIR', plugin_dir_path( __FILE__ ) );
	}
	
	/**
	 * Enqueue scripts
	 */
	public function admin_enqueue_scripts() {
		$screen = get_current_screen();

		if ( in_array( $screen->id, array( 'sp_player', 'edit-sp_player' ) ) ) {
		    wp_enqueue_script( 'sportspress-midseason-transfers', SP_MIDSEASON_TRANSFERS_URL .'js/admin.js', array( 'jquery' ), SP_MIDSEASON_TRANSFERS_VERSION, true );
			wp_enqueue_style( 'jquery-ui-style' , '//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css' ); 
			wp_enqueue_style( 'sportspress-admin-datepicker-styles', SP()->plugin_url() . '/assets/css/datepicker.css', array( 'jquery-ui-style' ), SP_VERSION );
			wp_enqueue_style( 'sportspress-midseason-transfers-admin', SP_MIDSEASON_TRANSFERS_URL . 'css/admin.css', array(), SP_MIDSEASON_TRANSFERS_VERSION );
		}
	}

	public function placeholder_cell( $player_id = null, $league_id = 0) {
		if ( $league_id > 0 ) { 
			?>
			<td>&nbsp;</td>
			<?php
		}
	}

	public function row( $player_id = null, $league_id = 0, $season_id = 0 ) {
		if ( $league_id > 0 ) { 
			?>
			<td class="sp-actions-column">
				<a href="#" title="<?php _e( 'Delete row', 'sportspress' ); ?>" class="dashicons dashicons-dismiss sp-delete-row"></a>
				<a href="#" title="<?php _e( 'Insert row after', 'sportspress' ); ?>" class="dashicons dashicons-plus-alt sp-add-row" data-league="<?php echo $league_id; ?>" data-season="<?php echo $season_id; ?>"></a>
			</td>
			<?php
		}
	}
	
	/**
	 * Add transfers to season ids
	 */
	public function season_ids( $ids = array(), $stats = array() ) {
		if ( ! is_array( $ids ) ) return array();
		if ( ! is_array( $stats ) ) return $ids;

		$keys = array_keys( $stats );
		$keys = array_reverse( $keys );

		foreach ( $keys as $id ):
			$base = (int) floor( $id );
			if ( $base == $id ) continue;
			if ( ! in_array( $base, $ids ) ) continue;

			$p = array_search( $base, $ids );
			array_splice( $ids, $p + 1, 0, $id );
		endforeach;

		return $ids;
	}
	
	/**
	 * Add classes to meta box rows
	 */
	public function classes( $classes = array(), $league_id = 0, $season_id = 0 ) {
		if ( (int) $season_id === $season_id ) return $classes;
		$classes[] = 'sp-row-added';
		return $classes;
	}
	
	/**
	 * Replace season name with transfer date
	 */
	public function season_name( $name = array(), $league_id = 0, $season_id = 0, $season_stats = array() ) {
		if ( (int) $season_id === $season_id ) return $name;
		$date_from = sp_array_value( $season_stats, 'date_from', false );
		return '<input type="text" class="sp-datepicker" name="sp_statistics[' . $league_id . '][' . $season_id . '][date_from]" value="' . ( $date_from ? $date_from : '' ) . '" size="10">';
	}
}

endif;

if ( get_option( 'sportspress_load_midseason_transfers_module', 'yes' ) == 'yes' ) {
	new SportsPress_Midseason_Transfers();
}
