<?php
/*
Plugin Name: SportsPress Timelines
Plugin URI: http://sportspresspro.com/
Description: Adds timelines to SportsPress events.
Author: ThemeBoy
Author URI: http://themeboy.com
Version: 1.6
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_Timelines' ) ) :

/**
 * Main SportsPress Timelines Class
 *
 * @class SportsPress_Timelines
 * @version	1.6
 */
class SportsPress_Timelines {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();

		// Hooks
		register_activation_hook( __FILE__, array( $this, 'install' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ), 10 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

		if ( defined( 'SP_PRO_PLUGIN_FILE' ) )
			register_activation_hook( SP_PRO_PLUGIN_FILE, array( $this, 'install' ) );
	}

	/**
	 * Define constants.
	*/
	private function define_constants() {
		if ( !defined( 'SP_TIMELINES_VERSION' ) )
			define( 'SP_TIMELINES_VERSION', '1.6' );

		if ( !defined( 'SP_TIMELINES_URL' ) )
			define( 'SP_TIMELINES_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'SP_TIMELINES_DIR' ) )
			define( 'SP_TIMELINES_DIR', plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Add meta box.
	 */
	public function add_meta_boxes() {
		add_meta_box( 'sp_timelinediv', __( 'Timeline', 'sportspress' ), array( $this, 'meta_box' ), 'sp_event', 'normal', 'high' );
	}


	/**
	 * Output the meta box.
	 */
	function meta_box( $post ) {
		$teams = get_post_meta( $post->ID, 'sp_team', false );
		$players = get_post_meta( $post->ID, 'sp_player', false );
		$highlights = get_post_meta( $post->ID, 'sp_highlight', false );

		$highlights = array(
			array(
				'player' => 118,
				'performance' => 'goals',
				'time' => 12,
			),
			array(
				'player' => 181,
				'performance' => 'redcards',
				'time' => 27,
			),
			array(
				'player' => 120,
				'performance' => 'goals',
				'time' => 75,
			),
			array(
				'player' => 99,
				'performance' => 'assists',
				'time' => 75,
			),
			array(
				'player' => 118,
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
						<th scope="col"><?php _e( 'Statistic', 'sportspress' ); ?></th>
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

	function admin_enqueue_scripts() {
		wp_enqueue_style( 'sportspress-timelines-admin', SP_TIMELINES_URL . 'assets/css/admin.css', array( 'sportspress-admin' ), time() );
		wp_enqueue_script( 'sportspress-timelines-admin', SP_TIMELINES_URL .'assets/js/admin.js', array( 'jquery', 'sportspress-admin' ), time(), true );
	}

}

endif;

if ( get_option( 'sportspress_load_timelines_module', 'yes' ) == 'yes' ) {
	new SportsPress_Timelines();
}
