<?php
/**
 * Event Details
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Meta Boxes
 * @version     0.7
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * SP_Meta_Box_Event_Details
 */
class SP_Meta_Box_Event_Details {

	/**
	 * Output the metabox
	 */
	public static function output( $post ) {
		$type = sp_get_the_term_id( $post->ID, 'sp_type', null );
		$league_id = sp_get_the_term_id( $post->ID, 'sp_league', 0 );
		$season_id = sp_get_the_term_id( $post->ID, 'sp_season', 0 );
		$venue_id = sp_get_the_term_id( $post->ID, 'sp_venue', 0 );
		?>
		<div class="sp-event-league-field">
			<p><strong><?php _e( 'League', 'sportspress' ); ?></strong></p>
			<p>
				<?php
				$args = array(
					'taxonomy' => 'sp_league',
					'name' => 'sp_league',
					'selected' => $league_id,
					'values' => 'term_id',
					'show_option_none' => __( '-- Not set --', 'sportspress' ),
				);
				if ( ! sp_dropdown_taxonomies( $args ) ):
					sp_taxonomy_adder( 'sp_league', 'sp_team', __( 'Add New', 'sportspress' ) );
				endif;
				?>
			</p>
		</div>
		<div class="sp-event-season-field">
			<p><strong><?php _e( 'Season', 'sportspress' ); ?></strong></p>
			<p>
				<?php
				$args = array(
					'taxonomy' => 'sp_season',
					'name' => 'sp_season',
					'selected' => $season_id,
					'values' => 'term_id',
					'show_option_none' => __( '-- Not set --', 'sportspress' ),
				);
				if ( ! sp_dropdown_taxonomies( $args ) ):
					sp_taxonomy_adder( 'sp_season', 'sp_team', __( 'Add New', 'sportspress' )  );
				endif;
				?>
			</p>
		</div>
		<div class="sp-event-venue-field">
			<p><strong><?php _e( 'Venue', 'sportspress' ); ?></strong></p>
			<p>
				<?php
				$args = array(
					'taxonomy' => 'sp_venue',
					'name' => 'sp_venue',
					'selected' => $venue_id,
					'values' => 'term_id',
					'show_option_none' => __( '-- Not set --', 'sportspress' ),
				);
				if ( ! sp_dropdown_taxonomies( $args ) ):
					sp_taxonomy_adder( 'sp_venue', 'sp_event', __( 'Add New', 'sportspress' )  );
				endif;
				?>
			</p>
		</div>
		<?php
	}

	/**
	 * Save meta box data
	 */
	public static function save( $post_id, $post ) {
		wp_set_post_terms( $post_id, sp_array_value( $_POST, 'sp_league', 0 ), 'sp_league' );
		wp_set_post_terms( $post_id, sp_array_value( $_POST, 'sp_season', 0 ), 'sp_season' );
		wp_set_post_terms( $post_id, sp_array_value( $_POST, 'sp_venue', 0 ), 'sp_venue' );
	}
}