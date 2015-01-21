<?php
/**
 * Table Details
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Meta_Boxes
 * @version     1.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * SP_Meta_Box_Table_Details
 */
class SP_Meta_Box_Table_Details {

	/**
	 * Output the metabox
	 */
	public static function output( $post ) {
		wp_nonce_field( 'sportspress_save_data', 'sportspress_meta_nonce' );
		$league_id = sp_get_the_term_id( $post->ID, 'sp_league', 0 );
		$season_id = sp_get_the_term_id( $post->ID, 'sp_season', 0 );
		$select = get_post_meta( $post->ID, 'sp_select', true );
		if ( ! $select ) {
			global $pagenow;
			$select = ( 'post-new.php' ? 'auto' : 'manual' );
		}
		?>
		<div>
			<p><strong><?php _e( 'Competition', 'sportspress' ); ?></strong></p>
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
			<p><strong><?php _e( 'Teams', 'sportspress' ); ?></strong></p>
			<p class="sp-select-setting">
				<select name="sp_select">
					<option value="auto" <?php selected( 'auto', $select ); ?>><?php _e( 'Auto', 'sportspress' ); ?></option>
					<option value="manual" <?php selected( 'manual', $select ); ?>><?php _e( 'Manual', 'sportspress' ); ?></option>
				</select>
			</p>
			<?php
			sp_post_checklist( $post->ID, 'sp_team', ( 'auto' == $select ? 'none' : 'block' ), array( 'sp_league', 'sp_season' ) );
			sp_post_adder( 'sp_team', __( 'Add New', 'sportspress' ) );
			?>
		</div>
		<?php
	}

	/**
	 * Save meta box data
	 */
	public static function save( $post_id, $post ) {
		wp_set_post_terms( $post_id, sp_array_value( $_POST, 'sp_league', 0 ), 'sp_league' );
		wp_set_post_terms( $post_id, sp_array_value( $_POST, 'sp_season', 0 ), 'sp_season' );
		update_post_meta( $post_id, 'sp_select', sp_array_value( $_POST, 'sp_select', array() ) );
		sp_update_post_meta_recursive( $post_id, 'sp_team', sp_array_value( $_POST, 'sp_team', array() ) );
	}
}