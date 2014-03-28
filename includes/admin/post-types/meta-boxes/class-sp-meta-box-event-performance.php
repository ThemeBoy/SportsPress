<?php
/**
 * Event Performance
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Meta Boxes
 * @version     0.7
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * SP_Meta_Box_Event_Performance
 */
class SP_Meta_Box_Event_Performance {

	/**
	 * Output the metabox
	 */
	public static function output( $post ) {
		$teams = (array)get_post_meta( $post->ID, 'sp_team', false );
		$stats = (array)get_post_meta( $post->ID, 'sp_players', true );

		// Get columns from performance variables
		$columns = sp_get_var_labels( 'sp_performance' );

		foreach ( $teams as $key => $team_id ):
			if ( ! $team_id ) continue;

			// Get results for players in the team
			$players = sp_array_between( (array)get_post_meta( $post->ID, 'sp_player', false ), 0, $key );
			$data = sp_array_combine( $players, sp_array_value( $stats, $team_id, array() ) );

			?>
			<div>
				<p><strong><?php echo get_the_title( $team_id ); ?></strong></p>
				<?php sp_edit_event_players_table( $columns, $data, $team_id ); ?>
			</div>
			<?php

		endforeach;
	}

	/**
	 * Save meta box data
	 */
	public static function save( $post_id, $post ) {
		update_post_meta( $post_id, 'sp_players', sp_array_value( $_POST, 'sp_players', array() ) );
	}
}