<?php
/*
Plugin Name: SportsPress Lazy Loading
Plugin URI: http://tboy.co/pro
Description: Load players using Ajax to speed up the event edit screen.
Author: ThemeBoy
Author URI: http://themeboy.com
Version: 1.9
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_Lazy_Loading' ) ) :

/**
 * Main SportsPress Lazy Loading Class
 *
 * @class SportsPress_Lazy_Loading
 * @version	1.9
 */
class SportsPress_Lazy_Loading {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();

		// Hooks
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'wp_ajax_sp-get-players', array( $this, 'get_players' ) );
		add_action( 'sportspress_event_teams_meta_box_checklist', array( $this, 'checklist' ), 10, 5 );
		add_filter( 'sportspress_localized_strings', array( $this, 'strings' ) );
		add_filter( 'sportspress_event_teams_meta_box_default_checklist', '__return_false' );
	}

	/**
	 * Define constants.
	 */
	private function define_constants() {
		if ( !defined( 'SP_LAZY_LOADING_VERSION' ) )
			define( 'SP_LAZY_LOADING_VERSION', '1.9' );

		if ( !defined( 'SP_LAZY_LOADING_URL' ) )
			define( 'SP_LAZY_LOADING_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'SP_LAZY_LOADING_DIR' ) )
			define( 'SP_LAZY_LOADING_DIR', plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Enqueue scripts
	 */
	public function admin_enqueue_scripts() {
		wp_enqueue_script( 'sportspress-lazy-loading-admin', SP_LAZY_LOADING_URL . 'js/admin.js', array( 'jquery', 'sportspress-admin' ), SP_LAZY_LOADING_VERSION, true );
	}

	/**
	 * Get players.
	 */
	public function get_players() {
		check_ajax_referer( 'sp-get-players', 'nonce' );

		$team = sp_array_value( $_POST, 'team' );
		$league = sp_array_value( $_POST, 'league' );
		$season = sp_array_value( $_POST, 'season' );
		$index = sp_array_value( $_POST, 'index', 1 );
		$selected = sp_array_value( $_POST, 'selected', array() );

		$args = array(
			'orderby' => 'menu_order',
		);

		if ( $team ) {
			$args['meta_query'] = array(
				array(
					'key' => 'sp_current_team',
					'value' => sp_array_value( $_POST, 'team' ),
				),
			);
		}

		if ( $league || $season ) {
			$args['tax_query'] = array( 'relation' => 'AND' );

			if ( $league ) {
				$args['tax_query'][] = array(
					'taxonomy' => 'sp_league',
					'field' => 'id',
					'terms' => $league,
				);
			}

			if ( $season ) {
				$args['tax_query'][] = array(
					'taxonomy' => 'sp_season',
					'field' => 'id',
					'terms' => $season,
				);
			}
		}

		$player_args = $args;
		$player_args['meta_key'] = 'sp_number';
		$player_args['orderby'] = 'meta_value_num';
		$player_args['order'] = 'ASC';

		$players = sp_get_posts( 'sp_player', $player_args );
		$staff = sp_get_posts( 'sp_staff', $args );
		$data = array( 'index' => $index );

		foreach ( $players as $key => $value ) {
			$players[ $key ]->post_title = sp_get_player_name_with_number( $value->ID );
		}

		$data['players'] = $players;
		$data['staff'] = $staff;
		wp_send_json_success( $data );
	}

	/**
	 * Ajax checklist.
	 */
	public function checklist( $post_id = null, $post_type = 'post', $display = 'block', $team = null, $index = null ) {
		$selected = sp_array_between( (array)get_post_meta( $post_id, $post_type, false ), 0, $index );

		$leagues = get_the_terms( $post_id, 'sp_league' );
		$seasons = get_the_terms( $post_id, 'sp_season' );

		$args = array(
			'orderby' => 'menu_order',
		);

		if ( 'sp_player' == $post_type ):
			$args['meta_key'] = 'sp_number';
			$args['orderby'] = 'meta_value_num';
			$args['order'] = 'ASC';
		endif;

		$args['meta_query'] = array(
			array(
				'key' => 'sp_current_team',
				'value' => $team,
			),
		);

		if ( $leagues || $seasons ) {
			$args['tax_query'] = array( 'relation' => 'AND' );

			if ( $leagues ) {
				$args['tax_query'][] = array(
					'taxonomy' => 'sp_league',
					'field' => 'id',
					'terms' => wp_list_pluck( $leagues, 'term_id' ),
				);
			}

			if ( $seasons ) {
				$args['tax_query'][] = array(
					'taxonomy' => 'sp_season',
					'field' => 'id',
					'terms' => wp_list_pluck( $seasons, 'term_id' ),
				);
			}
		}

		$posts = sp_get_posts( $post_type, $args );
		$post_ids = wp_list_pluck( $posts, 'ID' );
		$diff = array_diff( $post_ids, $selected );
		$selected = array_flip( $selected );
		?>
		<div id="<?php echo $post_type; ?>-all" class="posttypediv wp-tab-panel sp-tab-panel sp-ajax-checklist sp-select-all-range" style="display: <?php echo $display; ?>;">
			<input type="hidden" value="0" name="<?php echo $post_type; ?><?php if ( isset( $index ) ) echo '[' . $index . ']'; ?>[]" />
			<ul class="categorychecklist form-no-clear">
				<?php if ( is_array( $posts ) && sizeof( $posts ) ) { ?>
					<li class="sp-select-all-container">
						<label class="selectit">
							<input type="checkbox" class="sp-select-all" <?php checked( empty( $diff ) ); ?>>
							<strong><?php _e( 'Select All', 'sportspress' ); ?></strong>
						</label>
					</li>
					<?php foreach ( $posts as $post ) { ?>
						<li>
							<label class="selectit">
								<input type="checkbox" value="<?php echo $post->ID; ?>" name="<?php echo $post_type; ?><?php if ( isset( $index ) ) echo '[' . $index . ']'; ?>[]" <?php checked( array_key_exists( $post->ID, $selected ) ); ?>>
								<?php echo sp_get_player_name_with_number( $post->ID ); ?>
							</label>
						</li>
						<?php unset( $selected[ $post->ID ] ); ?>
					<?php } ?>
					<?php if ( is_array( $selected ) && sizeof( $selected ) ) { foreach ( $selected as $post_id => $post ) { ?>
						<?php if ( ! $post_id ) continue; ?>
						<li>
							<label class="selectit">
								<input type="checkbox" value="<?php echo $post_id; ?>" name="<?php echo $post_type; ?><?php if ( isset( $index ) ) echo '[' . $index . ']'; ?>[]" <?php checked( true ); ?>>
								<?php echo sp_get_player_name_with_number( $post_id ); ?>
							</label>
						</li>
					<?php } } ?>
					<li class="sp-ajax-show-all-container"><a class="sp-ajax-show-all" href="#show-all-<?php echo $post_type; ?>s"><?php _e( 'Show all', 'sportspress' ); ?></a></li>
				<?php } else { ?>
					<li class="sp-ajax-show-all-container"><?php _e( 'No results found.', 'sportspress' ); ?>
					<a class="sp-ajax-show-all" href="#show-all-<?php echo $post_type; ?>s"><?php _e( 'Show all', 'sportspress' ); ?></a></li>
				<?php } ?>
			</ul>
		</div>
		<?php
	}

	/*
	 * Localized strings.
	 */
	public function strings( $strings ) {
		$strings = array_merge( $strings, array(
			'no_results_found' => __( 'No results found.', 'sportspress' ),
			'select_all' => __( 'Select All', 'sportspress' ),
			'show_all' => __( 'Show all', 'sportspress' ),
			'loading' => __( 'Loading&hellip;', 'sportspress' ),
			'option_filter_by_league' => get_option( 'sportspress_event_filter_teams_by_league', 'no' ),
			'option_filter_by_season' => get_option( 'sportspress_event_filter_teams_by_season', 'no' ),
		) ) ;
		return $strings;
	}
}

endif;

if ( get_option( 'sportspress_load_lazy_loading_module', 'yes' ) == 'yes' ) {
	new SportsPress_Lazy_Loading();
}