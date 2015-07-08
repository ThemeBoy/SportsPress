<?php
/**
 * Admin functions for the tournaments post type
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress_Tournaments
 * @version     1.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'SP_Admin_CPT' ) )
	include( SP()->plugin_path() . '/includes/admin/post-types/class-sp-admin-cpt.php' );

if ( ! class_exists( 'SP_Admin_CPT_Tournament' ) ) :

/**
 * SP_Admin_CPT_Tournament Class
 */
class SP_Admin_CPT_Tournament extends SP_Admin_CPT {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->type = 'sp_tournament';

		// Admin Columns
		add_filter( 'manage_edit-sp_tournament_columns', array( $this, 'edit_columns' ) );
		add_action( 'manage_sp_tournament_posts_custom_column', array( $this, 'custom_columns' ), 2, 2 );
		
		// Call SP_Admin_CPT constructor
		parent::__construct();
	}

	/**
	 * Change the columns shown in admin.
	 */
	public function edit_columns( $existing_columns ) {
		unset( $existing_columns['author'], $existing_columns['date'] );
		$columns = array_merge( array(
			'cb' => '<input type="checkbox" />',
			'title' => __( 'Title', 'sportspress' ),
			'sp_league' => __( 'Competition', 'sportspress' ),
			'sp_season' => __( 'Season', 'sportspress' ),
			'sp_rounds' => __( 'Rounds', 'sportspress' ),
			'sp_layout' => __( 'Layout', 'sportspress' ),
		), $existing_columns );
		return apply_filters( 'sportspress_tournament_admin_columns', $columns );
	}

	/**
	 * Define our custom columns shown in admin.
	 * @param  string $column
	 */
	public function custom_columns( $column, $post_id ) {
		switch ( $column ):
			case 'sp_league':
				echo get_the_terms ( $post_id, 'sp_league' ) ? the_terms( $post_id, 'sp_league' ) : __( 'All', 'sportspress' );
				break;
			case 'sp_season':
				echo get_the_terms ( $post_id, 'sp_season' ) ? the_terms( $post_id, 'sp_season' ) : __( 'All', 'sportspress' );
				break;
			case 'sp_rounds':
				echo get_post_meta ( $post_id, 'sp_rounds', true );
				break;
			case 'sp_layout':
				echo sp_array_value( SP()->formats->tournament, get_post_meta( $post_id, 'sp_format', true ), '&mdash;' );
				break;
		endswitch;
	}
}

endif;

return new SP_Admin_CPT_Tournament();
