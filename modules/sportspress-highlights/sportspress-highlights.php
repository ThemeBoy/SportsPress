<?php
/*
Plugin Name: SportsPress Highlights
Plugin URI: http://sportspresspro.com/
Description: Adds highlights to SportsPress events.
Author: ThemeBoy
Author URI: http://sportspresspro.com
Version: 1.0
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Main SportsPress Highlights Class
 *
 * @class SportsPress_Highlights
 * @version	1.0
 */
class SportsPress_Highlights {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();

		// Hooks
		add_action( 'init', array( $this, 'init' ), 11 );
	}

	/**
	 * Define constants
	*/
	private function define_constants() {
		if ( !defined( 'SP_HIGHLIGHTS_VERSION' ) )
			define( 'SP_HIGHLIGHTS_VERSION', '1.0' );

		if ( !defined( 'SP_HIGHLIGHTS_URL' ) )
			define( 'SP_HIGHLIGHTS_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'SP_HIGHLIGHTS_DIR' ) )
			define( 'SP_HIGHLIGHTS_DIR', plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Init plugin when WordPress Initialises.
	 */
	public function init() {
		// Set up localisation
		$this->load_plugin_textdomain();
	}

	/**
	 * Load Localisation files.
	 *
	 * Note: the first-loaded translation file overrides any following ones if the same translation is present
	 */
	public function load_plugin_textdomain() {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'sportspress' );
		
		// Global + Frontend Locale
		load_plugin_textdomain( 'sportspress', false, plugin_basename( dirname( __FILE__ ) . "/languages" ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
	}

	function admin_enqueue_scripts() {
		wp_enqueue_style( 'sportspress-highlights-admin', SP_HIGHLIGHTS_URL . 'assets/css/admin.css', array( 'sportspress-admin' ), time() );
		wp_enqueue_script( 'sportspress-highlights-admin', SP_HIGHLIGHTS_URL .'assets/js/admin.js', array( 'jquery', 'sportspress-admin' ), time(), true );
	}

	function add_meta_boxes() {
		add_meta_box( 'sp_highlightssdiv', __( 'Highlights', 'sportspress' ), array( $this, 'meta_box' ), 'sp_event', 'normal', 'high' );
	}

	function meta_box( $post ) {
		$teams = get_post_meta( $post->ID, 'sp_team', false );
		$players = get_post_meta( $post->ID, 'sp_player', false );
		$highlights = get_post_meta( $post->ID, 'sp_highlight', false );

		$highlights = array(
			array(
				'player' => 88,
				'performance' => 'goals',
				'time' => 12,
			),
			array(
				'player' => 91,
				'performance' => 'redcards',
				'time' => 27,
			),
			array(
				'player' => 92,
				'performance' => 'goals',
				'time' => 75,
			),
			array(
				'player' => 93,
				'performance' => 'assists',
				'time' => 75,
			),
			array(
				'player' => 94,
				'performance' => 'yellowcards',
				'time' => 86,
			),
		);

		// Get player options
		$args = array(
			'post_type' => 'sp_player',
			'posts_per_page' => -1,
		    'include' => $players,
		);
		$player_options = get_posts( $args );

		// Get performance options
		$args = array(
			'post_type' => 'sp_performance',
			'posts_per_page' => -1,
		);
		$performance_options = get_posts( $args );
		?>
		<div class="sp-data-table-container">
			<table class="widefat sp-data-table sp-highlight-table">
				<thead>
					<tr>
						<th scope="col"><?php _e( 'Player', 'sportspress' ); ?></th>
						<th scope="col"><?php _e( 'Performance', 'sportspress' ); ?></th>
						<th scope="col" class="time-column"><?php _e( 'Time', 'sportspress' ); ?></th>
						<th scope="col"><?php _e( 'Notes', 'sportspress' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php if ( is_array( $highlights ) ): $i = 0; foreach ( $highlights as $highlight ): ?>
					<tr class="sp-row sp-post<?php if ( $i % 2 == 0 ) echo ' alternate'; ?>">
						<td>
							<?php
							$selected = sportspress_array_value( $highlight, 'player', null );
							?>
							<select name="highlight[<?php echo $i ?>][player]" class="sp-player-id-select chosen-select<?php if ( is_rtl() ): ?> chosen-rtl<?php endif; ?>" data-placeholder="<?php _e( '&mdash; Select &mdash;', 'sportspress' ) ?>">
								<option value=""></option>
								<?php $j = 0; foreach ( $teams as $team ): $team_players = array_filter( sportspress_array_between( (array)$players, 0, $j ) );?>
								<optgroup label="<?php echo get_the_title( $team ); ?>">
									<?php foreach ( $team_players as $player ): ?>
									<option value="<?php echo $player; ?>" <?php selected( $selected, $player, true ) ?>><?php echo get_the_title( $player ); ?></option>
									<?php endforeach; ?>
								</optgroup>
								<?php $j++; endforeach; ?>
							</select>
						</td>
						<td>
							<?php
							$value = sportspress_array_value( $highlight, 'performance', null );
							$args = array(
								'post_type' => 'sp_performance',
								'name' => 'sp_highlight[' . $i . '][performance]',
								'show_option_none' => true,
								'option_none_value' => '',
								'selected' => $value,
								'chosen' => true,
								'class' => 'sp-player-performance-select',
								'placeholder' => __( '&mdash; Select &mdash;', 'sportspress' ),
							);
							sportspress_dropdown_pages( $args );
							?>
						</td>
						<td class="time-column">
							<?php $value = sportspress_array_value( $highlight, 'time', 0 ); ?>
							<input name="sp_highlight[<?php echo $i; ?>][time]" class="sp-time" size="4" type="text" value="<?php echo $value; ?>" />
							<input class="sp-time-range" type="range" max="90" tabindex="-1" value="<?php echo $value; ?>" />
						</td>
						<td><input type="text"></td>
					</tr>
					<?php $i++; endforeach; endif; ?>
				</tbody>
			</table>
			<div class="tablenav bottom">
				<div class="alignleft actions">
					<a class="button" class=""><?php _e( 'Add Highlight', 'sportspress' ); ?></a>
				</div>
				<br class="clear">
			</div>
		</div>
		<?php
	}
}

new SportsPress_Highlights();
