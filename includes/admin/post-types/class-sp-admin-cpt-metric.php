<?php
/**
 * Admin functions for the columns post type
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Post Types
 * @version     0.7
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'SP_Admin_CPT' ) )
	include( 'class-sp-admin-cpt.php' );

if ( ! class_exists( 'SP_Admin_CPT_Metric' ) ) :

/**
 * SP_Admin_CPT_Metric Class
 */
class SP_Admin_CPT_Metric extends SP_Admin_CPT {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->type = 'sp_metric';

		// Admin Columns
		add_filter( 'manage_edit-sp_metric_columns', array( $this, 'edit_columns' ) );
		add_action( 'manage_sp_metric_posts_custom_column', array( $this, 'custom_columns' ), 2, 2 );
		
		// Call SP_Admin_CPT constructor
		parent::__construct();
	}

	/**
	 * Change the columns shown in admin.
	 */
	public function edit_columns( $existing_columns ) {
		$columns = array(
			'cb' => '<input type="checkbox" />',
			'title' => __( 'Label', 'sportspress' ),
			'sp_key' => __( 'Key', 'sportspress' ),
			'sp_position' => __( 'Positions', 'sportspress' ),
		);
		return $columns;
	}

	/**
	 * Define our custom columns shown in admin.
	 * @param  string $column
	 */
	public function custom_columns( $column, $post_id ) {
		switch ( $column ):
			case 'sp_position':
				echo get_the_terms( $post_id, 'sp_position' ) ? the_terms( $post_id, 'sp_position' ) : '&mdash;';
			break;
		endswitch;
	}
}

endif;

return new SP_Admin_CPT_Metric();
