<?php
/**
 * Player Statistics
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Meta Boxes
 * @version     0.8
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * SP_Meta_Box_Player_Statistics
 */
class SP_Meta_Box_Player_Statistics {

	/**
	 * Output the metabox
	 */
	public static function output( $post ) {
		$leagues = get_the_terms( $post->ID, 'sp_league' );

		$league_num = sizeof( $leagues );

		// Loop through statistics for each league
		if ( $leagues ): foreach ( $leagues as $league ):
			
			if ( $league_num > 1 ):
				?>
				<p><strong><?php echo $league->name; ?></strong></p>
				<?php
			endif;

			list( $columns, $data, $placeholders, $merged, $seasons_teams ) = sp_get_player_statistics_data( $post->ID, $league->term_id, true );

			sp_edit_player_statistics_table( $post->ID, $league->term_id, $columns, $data, $placeholders, $merged, $seasons_teams, ! current_user_can( 'edit_sp_teams' ) );

		endforeach; else:

			printf( __( 'Select %s', 'sportspress' ), __( 'Leagues', 'sportspress' ) );

		endif;
	}

	/**
	 * Save meta box data
	 */
	public static function save( $post_id, $post ) {
		update_post_meta( $post_id, 'sp_leagues', sp_array_value( $_POST, 'sp_leagues', array() ) );
		if ( current_user_can( 'edit_sp_teams' ) )
			update_post_meta( $post_id, 'sp_statistics', sp_array_value( $_POST, 'sp_statistics', array() ) );
	}
}