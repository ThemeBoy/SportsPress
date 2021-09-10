<?php
/**
 * Team Assignments
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @description: Add team assignments support to SportsPress
 * @package 	SportsPress/Admin/Meta_Boxes
 * @version		2.8
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * SP_Meta_Box_Team_Assignments
 */
class SP_Meta_Box_Team_Assignments {

	/**
	 * Output the metabox
	 */
	public static function output( $post ) {

		if ( taxonomy_exists( 'sp_league' ) ):
			$leagues = get_the_terms( $post, 'sp_league' );
			$league_ids = array();
			if ( $leagues ):
				foreach ( $leagues as $league ):
					$league_ids[] = $league->term_id;
				endforeach;
			endif;
			$args = array(
				'taxonomy' => 'sp_league',
				'include' => $league_ids,
			);
			$leagues = get_terms( $args );
		endif;

		if ( taxonomy_exists( 'sp_season' ) ):
			$seasons = get_the_terms( $post, 'sp_season' );
			$season_ids = array();
			if ( $seasons ):
				foreach ( $seasons as $season ):
					$season_ids[] = $season->term_id;
				endforeach;
			endif;
		endif;
	
		$sp_team_assignments = get_post_meta( $post->ID, 'sp_assignments', true );
		?>
		<div class="sp-data-table-container">
			<table class="widefat sp-data-table sp-team-assignments">
				<thead>
					<tr><th><strong><?php _e( 'Leagues', 'sportspress' ); ?></strong></th><th><strong><?php _e( 'Seasons', 'sportspress' ); ?></strong></th></tr>
				</thead>
				<tbody>
				<?php foreach ( $leagues as $league ) { ?>
					<tr>
						<td><?php echo $league->name; ?></td>
						<td><?php
						$args = array(
							'taxonomy' => 'sp_season',
							'name' => 'sp_assignments[' . $league->term_id . '][]',
							'selected' => sp_array_value( $sp_team_assignments, $league->term_id, array() ),
							'included' => $season_ids,
							'values' => 'term_id',
							'placeholder' => sprintf( __( 'Select %s', 'sportspress' ), __( 'Seasons', 'sportspress' ) ),
							'class' => 'widefat',
							'property' => 'multiple',
							'chosen' => true,
						);
						sp_dropdown_taxonomies( $args );
						?>
						</td>
					</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
		<?php

	}

	/**
	 * Save meta box data
	 */
	public static function save( $post_id, $post ) {
		//Reset current assignments
		delete_post_meta( $post_id, 'sp_assignments' );

		$sp_assignments = sp_array_value( $_POST, 'sp_assignments', array() );
		$sp_assignments_serialized = array();
		foreach ( $sp_assignments as $league_id => $season_ids ) {
			foreach ( $season_ids as $season_id ) {
				$sp_assignments_serialized[] = $league_id . '-' . $season_id . '-' . $post_id;
			}
		}
			
		update_post_meta( $post_id, 'sp_assignments', $sp_assignments );
		sp_update_post_meta_recursive( $post_id, 'sp_assignments_serialized', $sp_assignments_serialized );
	}
	
}