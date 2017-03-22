<?php
/*
Plugin Name: SportsPress Lazy Loading
Plugin URI: http://tboy.co/pro
Description: Load players using Ajax to speed up the event edit screen.
Author: ThemeBoy
Author URI: http://themeboy.com
Version: 2.3
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_Lazy_Loading' ) ) :

/**
 * Main SportsPress Lazy Loading Class
 *
 * @class SportsPress_Lazy_Loading
 * @version	2.3
 */
class SportsPress_Lazy_Loading {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'wp_ajax_sp-get-players', array( $this, 'get_players' ) );
		add_action( 'sportspress_event_teams_meta_box_checklist', array( $this, 'checklist' ), 10, 6 );
		add_filter( 'sportspress_localized_strings', array( $this, 'strings' ) );
	}

	/**
	 * Get players.
	 */
	public function get_players() {
		check_ajax_referer( 'sp-get-players', 'nonce' );

		$team = sp_array_value( $_POST, 'team' );
		
		if ( 'yes' == get_option( 'sportspress_event_filter_teams_by_league', 'no' ) ) {
			$league = sp_array_value( $_POST, 'league' );
		} else {
			$league = false;
		}
		
		if ( 'yes' == get_option( 'sportspress_event_filter_teams_by_season', 'no' ) ) {
			$season = sp_array_value( $_POST, 'season' );
		} else {
			$season = false;
		}
		
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
					'field' => 'term_id',
					'terms' => $league,
				);
			}

			if ( $season ) {
				$args['tax_query'][] = array(
					'taxonomy' => 'sp_season',
					'field' => 'term_id',
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
		$data['sections'] = get_option( 'sportspress_event_performance_sections', -1 );
		
		wp_send_json_success( $data );
	}

	/**
	 * Ajax checklist.
	 */
	public function checklist( $post_id = null, $post_type = 'post', $display = 'block', $team = null, $index = null, $slug = null ) {
		if ( ! isset( $slug ) ):
			$slug = $post_type;
		endif;
		
		$selected = (array)get_post_meta( $post_id, $slug, false );
		if ( sizeof( $selected ) ) {
			$selected = sp_array_between( $selected, 0, $index );
		} else {
			$selected = sp_array_between( (array)get_post_meta( $post_id, $post_type, false ), 0, $index );
		}
		
		if ( 'yes' == get_option( 'sportspress_event_filter_teams_by_league', 'no' ) ) {
			$leagues = get_the_terms( $post_id, 'sp_league' );
		} else {
			$leagues = false;
		}
		
		if ( 'yes' == get_option( 'sportspress_event_filter_teams_by_season', 'no' ) ) {
			$seasons = get_the_terms( $post_id, 'sp_season' );
		} else {
			$seasons = false;
		}

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
					'field' => 'term_id',
					'terms' => wp_list_pluck( $leagues, 'term_id' ),
				);
			}

			if ( $seasons ) {
				$args['tax_query'][] = array(
					'taxonomy' => 'sp_season',
					'field' => 'term_id',
					'terms' => wp_list_pluck( $seasons, 'term_id' ),
				);
			}
		}

		$posts = sp_get_posts( $post_type, $args );
		$post_ids = wp_list_pluck( $posts, 'ID' );
		$diff = array_diff( $post_ids, $selected );
		$borrowed = array_diff( $selected, $post_ids );
		$selected = array_flip( $selected );

		if ( sizeof( $borrowed ) ) {
			$args = array( 'post__in' => $borrowed );
			$borrowed_posts = sp_get_posts( $post_type, $args );
			if ( is_array( $borrowed_posts ) ) {
				$posts += $borrowed_posts;
			}
		}
		?>
		<div id="<?php echo $slug; ?>-all" class="posttypediv tabs-panel wp-tab-panel sp-tab-panel sp-ajax-checklist sp-select-all-range" style="display: <?php echo $display; ?>;">
			<input type="hidden" value="0" name="<?php echo $slug; ?><?php if ( isset( $index ) ) echo '[' . $index . ']'; ?>[]" />
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
								<input type="checkbox" value="<?php echo $post->ID; ?>" name="<?php echo $slug; ?><?php if ( isset( $index ) ) echo '[' . $index . ']'; ?>[]" <?php checked( array_key_exists( $post->ID, $selected ) ); ?>>
								<?php echo sp_get_player_name_with_number( $post->ID ); ?>
							</label>
						</li>
						<?php unset( $selected[ $post->ID ] ); ?>
					<?php } ?>
					<?php if ( is_array( $selected ) && sizeof( $selected ) ) { foreach ( $selected as $post_id => $post ) { ?>
						<?php if ( ! $post_id ) continue; ?>
						<li>
							<label class="selectit">
								<input type="checkbox" value="<?php echo $post_id; ?>" name="<?php echo $slug; ?><?php if ( isset( $index ) ) echo '[' . $index . ']'; ?>[]" <?php checked( true ); ?>>
								<?php echo sp_get_player_name_with_number( $post_id ); ?>
							</label>
						</li>
					<?php } } ?>
					<li class="sp-ajax-show-all-container"><a class="sp-ajax-show-all" href="#show-all-<?php echo $slug; ?>s"><?php _e( 'Show all', 'sportspress' ); ?></a></li>
				<?php } else { ?>
					<li class="sp-ajax-show-all-container"><?php _e( 'No results found.', 'sportspress' ); ?>
					<a class="sp-ajax-show-all" href="#show-all-<?php echo $slug; ?>s"><?php _e( 'Show all', 'sportspress' ); ?></a></li>
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

new SportsPress_Lazy_Loading();