<?php
/**
 * Team Columns
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Meta Boxes
 * @version     0.7
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * SP_Meta_Box_Team_Columns
 */
class SP_Meta_Box_Team_Columns {

	/**
	 * Output the metabox
	 */
	public static function output( $post ) {
		wp_nonce_field( 'sportspress_save_data', 'sportspress_meta_nonce' );
		$leagues = (array)get_the_terms( $post->ID, 'sp_league' );
		$league_num = sizeof( $leagues );

		// Loop through columns for each league
		foreach ( $leagues as $league ):

			$league_id = $league->term_id;
			
			if ( $league_num > 1 ):
				?>
				<p><strong><?php echo $league->name; ?></strong></p>
				<?php
			endif;

			list( $columns, $data, $placeholders, $merged, $leagues_seasons ) = sportspress_get_team_columns_data( $post->ID, $league_id, true );

			sportspress_edit_team_columns_table( $league_id, $columns, $data, $placeholders, $merged, $leagues_seasons, ! current_user_can( 'edit_sp_tables' ) );

		endforeach;
	}

	/**
	 * Save meta box data
	 */
	public static function save( $post_id, $post ) {
		update_post_meta( $post_id, 'sp_leagues_seasons', sportspress_array_value( $_POST, 'sp_leagues_seasons', array() ) );
		if ( current_user_can( 'edit_sp_tables' ) )
			update_post_meta( $post_id, 'sp_columns', sportspress_array_value( $_POST, 'sp_columns', array() ) );
	}
}