<?php
/**
 * Calendar Data
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Meta_Boxes
 * @version     0.7
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * SP_Meta_Box_Calendar_Details
 */
class SP_Meta_Box_Calendar_Details {

	/**
	 * Output the metabox
	 */
	public static function output( $post ) {
		$status = get_post_meta( $post->ID, 'sp_status', true );
		$date = get_post_meta( $post->ID, 'sp_date', true );
		$league_id = sp_get_the_term_id( $post->ID, 'sp_league', 0 );
		$season_id = sp_get_the_term_id( $post->ID, 'sp_season', 0 );
		$venue_id = sp_get_the_term_id( $post->ID, 'sp_venue', 0 );
		$team_id = get_post_meta( $post->ID, 'sp_team', true );
		$order = get_post_meta( $post->ID, 'sp_order', true );
		?>
		<div>
			<p><strong><?php _e( 'Status', 'sportspress' ); ?></strong></p>
			<p>
				<?php
				$args = array(
					'name' => 'sp_status',
					'id' => 'sp_status',
					'selected' => $status,
				);
				sp_dropdown_statuses( $args );
				?>
			</p>
			<p><strong><?php _e( 'Date', 'sportspress' ); ?></strong></p>
			<p>
				<?php
				$args = array(
					'name' => 'sp_date',
					'id' => 'sp_date',
					'selected' => $date,
				);
				sp_dropdown_dates( $args );
				?>
			</p>
			<p><strong><?php _e( 'League', 'sportspress' ); ?></strong></p>
			<p>
				<?php
				$args = array(
					'show_option_all' => __( 'All', 'sportspress' ),
					'taxonomy' => 'sp_league',
					'name' => 'sp_league',
					'selected' => $league_id,
					'values' => 'term_id'
				);
				if ( ! sp_dropdown_taxonomies( $args ) ):
					sp_taxonomy_adder( 'sp_league', 'sp_team', __( 'Add New', 'sportspress' )  );
				endif;
				?>
			</p>
			<p><strong><?php _e( 'Season', 'sportspress' ); ?></strong></p>
			<p class="sp-tab-select">
				<?php
				$args = array(
					'show_option_all' => __( 'All', 'sportspress' ),
					'taxonomy' => 'sp_season',
					'name' => 'sp_season',
					'selected' => $season_id,
					'values' => 'term_id'
				);
				if ( ! sp_dropdown_taxonomies( $args ) ):
					sp_taxonomy_adder( 'sp_season', 'sp_team', __( 'Add New', 'sportspress' )  );
				endif;
				?>
			</p>
			<p><strong><?php _e( 'Venue', 'sportspress' ); ?></strong></p>
			<p>
				<?php
				$args = array(
					'show_option_all' => __( 'All', 'sportspress' ),
					'taxonomy' => 'sp_venue',
					'name' => 'sp_venue',
					'selected' => $venue_id,
					'values' => 'term_id'
				);
				if ( ! sp_dropdown_taxonomies( $args ) ):
					sp_taxonomy_adder( 'sp_season', 'sp_team', __( 'Add New', 'sportspress' )  );
				endif;
				?>
			</p>
			<p><strong><?php _e( 'Team', 'sportspress' ); ?></strong></p>
			<p>
				<?php
				$args = array(
					'show_option_all' => __( 'All', 'sportspress' ),
					'post_type' => 'sp_team',
					'name' => 'sp_team',
					'selected' => $team_id,
					'values' => 'ID'
				);
				if ( ! sp_dropdown_pages( $args ) ):
					sp_post_adder( 'sp_team', __( 'Add New', 'sportspress' )  );
				endif;
				?>
			</p>
			<p><strong><?php _e( 'Sort Order', 'sportspress' ); ?></strong></p>
			<p>
				<select name="sp_order">
					<option value="ASC" <?php selected( 'ASC', $order ); ?>><?php _e( 'Ascending', 'sportspress' ); ?></option>
					<option value="DESC" <?php selected( 'DESC', $order ); ?>><?php _e( 'Descending', 'sportspress' ); ?></option>
				</select>
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
		update_post_meta( $post_id, 'sp_status', sp_array_value( $_POST, 'sp_status', 0 ) );
		update_post_meta( $post_id, 'sp_date', sp_array_value( $_POST, 'sp_date', 0 ) );
		update_post_meta( $post_id, 'sp_team', sp_array_value( $_POST, 'sp_team', 0 ) );
		update_post_meta( $post_id, 'sp_order', sp_array_value( $_POST, 'sp_order', array() ) );
	}
}