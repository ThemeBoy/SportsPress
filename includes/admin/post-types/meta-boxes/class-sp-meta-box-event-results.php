<?php
/**
 * Event Results
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Meta Boxes
 * @version     0.7
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * SP_Meta_Box_Event_Results
 */
class SP_Meta_Box_Event_Results {

	/**
	 * Output the metabox
	 */
	public static function output( $post ) {
		$teams = (array)get_post_meta( $post->ID, 'sp_team', false );

		$results = (array)get_post_meta( $post->ID, 'sp_results', true );

		// Get columns from result variables
		$columns = sportspress_get_var_labels( 'sp_result' );

		// Get results for all teams
		$data = sportspress_array_combine( $teams, $results );

		?>
		<div>
			<?php sportspress_edit_event_results_table( $columns, $data ); ?>
		</div>
		<?php
	}

	/**
	 * Save meta box data
	 */
	public static function save( $post_id, $post ) {
		$results = (array)sportspress_array_value( $_POST, 'sp_results', array() );
		update_post_meta( $post_id, 'sp_results', $results );
	}
}