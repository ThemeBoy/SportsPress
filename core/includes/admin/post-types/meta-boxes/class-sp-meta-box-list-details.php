<?php
/**
 * List Details
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Meta_Boxes
 * @version     0.7
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * SP_Meta_Box_List_Details
 */
class SP_Meta_Box_List_Details {

	/**
	 * Output the metabox
	 */
	public static function output( $post ) {
		$league_id = sp_get_the_term_id( $post->ID, 'sp_league', 0 );
		$season_id = sp_get_the_term_id( $post->ID, 'sp_season', 0 );
		$team_id = get_post_meta( $post->ID, 'sp_team', true );
		$grouping = get_post_meta( $post->ID, 'sp_grouping', true );
		$orderby = get_post_meta( $post->ID, 'sp_orderby', true );
		$order = get_post_meta( $post->ID, 'sp_order', true );
		?>
		<div>
			<p><strong><?php _e( 'League', 'sportspress' ); ?></strong></p>
			<p class="sp-tab-select">
				<?php
				$args = array(
					'taxonomy' => 'sp_league',
					'name' => 'sp_league',
					'show_option_all' => __( 'All', 'sportspress' ),
					'selected' => $league_id,
					'values' => 'term_id',
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
					'taxonomy' => 'sp_season',
					'name' => 'sp_season',
					'show_option_all' => __( 'All', 'sportspress' ),
					'selected' => $season_id,
					'values' => 'term_id',
				);
				if ( ! sp_dropdown_taxonomies( $args ) ):
					sp_taxonomy_adder( 'sp_season', 'sp_team', __( 'Add New', 'sportspress' )  );
				endif;
				?>
			</p>
			<p><strong><?php _e( 'Team', 'sportspress' ); ?></strong></p>
			<p class="sp-tab-select">
				<?php
				$args = array(
					'post_type' => 'sp_team',
					'name' => 'sp_team',
					'show_option_all' => __( 'All', 'sportspress' ),
					'selected' => $team_id,
					'values' => 'ID',
				);
				if ( ! sp_dropdown_pages( $args ) ):
					sp_post_adder( 'sp_team', __( 'Add New', 'sportspress' ) );
				endif;
				?>
			</p>
			<p><strong><?php _e( 'Grouping', 'sportspress' ); ?></strong></p>
			<p>
			<select name="sp_grouping">
				<option value="0"><?php _e( 'None', 'sportspress' ); ?></option>
				<option value="position" <?php selected( $grouping, 'position' ); ?>><?php _e( 'Position', 'sportspress' ); ?></option>
			</select>
			</p>
			<p><strong><?php _e( 'Sort by', 'sportspress' ); ?></strong></p>
			<p>
			<?php
			$args = array(
				'prepend_options' => array(
					'number' => __( 'Number', 'sportspress' ),
					'name' => __( 'Name', 'sportspress' ),
				),
				'post_type' => array( 'sp_performance', 'sp_metric', 'sp_statistic' ),
				'name' => 'sp_orderby',
				'selected' => $orderby,
				'values' => 'slug',
			);
			sp_dropdown_pages( $args );
			?>
			</p>
			<p><strong><?php _e( 'Sort Order', 'sportspress' ); ?></strong></p>
			<p>
				<select name="sp_order">
					<option value="ASC" <?php selected( 'ASC', $order ); ?>><?php _e( 'Ascending', 'sportspress' ); ?></option>
					<option value="DESC" <?php selected( 'DESC', $order ); ?>><?php _e( 'Descending', 'sportspress' ); ?></option>
				</select>
			</p>
			<p><strong><?php _e( 'Players', 'sportspress' ); ?></strong></p>
			<?php
			sp_post_checklist( $post->ID, 'sp_player', 'block', array( 'sp_league', 'sp_season', 'sp_current_team' ) );
			sp_post_adder( 'sp_player', __( 'Add New', 'sportspress' ) );
			?>
		</div>
		<?php
	}

	/**
	 * Save meta box data
	 */
	public static function save( $post_id, $post ) {
		update_post_meta( $post_id, 'sp_team', sp_array_value( $_POST, 'sp_team', array() ) );
		wp_set_post_terms( $post_id, sp_array_value( $_POST, 'sp_league', 0 ), 'sp_league' );
		wp_set_post_terms( $post_id, sp_array_value( $_POST, 'sp_season', 0 ), 'sp_season' );
		update_post_meta( $post_id, 'sp_grouping', sp_array_value( $_POST, 'sp_grouping', array() ) );
		update_post_meta( $post_id, 'sp_orderby', sp_array_value( $_POST, 'sp_orderby', array() ) );
		update_post_meta( $post_id, 'sp_order', sp_array_value( $_POST, 'sp_order', array() ) );
		sp_update_post_meta_recursive( $post_id, 'sp_player', sp_array_value( $_POST, 'sp_player', array() ) );
	}
}