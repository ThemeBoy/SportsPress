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
		$leagues = get_the_terms( $post->ID, 'sp_league' );
		$league_num = sizeof( $leagues );

		// Loop through columns for each league
		if ( $leagues ): foreach ( $leagues as $league ):

			$league_id = $league->term_id;
			
			if ( $league_num > 1 ):
				?>
				<p><strong><?php echo $league->name; ?></strong></p>
				<?php
			endif;

			list( $columns, $data, $placeholders, $merged, $leagues_seasons ) = sp_get_team_columns_data( $post->ID, $league_id, true );

			sp_edit_team_columns_table( $league_id, $columns, $data, $placeholders, $merged, $leagues_seasons, ! current_user_can( 'edit_sp_tables' ) );

		endforeach; else:

			printf( __( 'Select %s', 'sportspress' ), __( 'Leagues', 'sportspress' ) );

		endif;
	}

	/**
	 * Save meta box data
	 */
	public static function save( $post_id, $post ) {
		update_post_meta( $post_id, 'sp_leagues_seasons', sp_array_value( $_POST, 'sp_leagues_seasons', array() ) );
		if ( current_user_can( 'edit_sp_tables' ) )
			update_post_meta( $post_id, 'sp_columns', sp_array_value( $_POST, 'sp_columns', array() ) );
	}
}