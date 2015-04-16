<?php
/**
 * Admin functions for the statistics post type
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Post_Types
 * @version     1.8
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'SP_Admin_CPT' ) )
	include( 'class-sp-admin-cpt.php' );

if ( ! class_exists( 'SP_Admin_CPT_Statistic' ) ) :

/**
 * SP_Admin_CPT_Statistic Class
 */
class SP_Admin_CPT_Statistic extends SP_Admin_CPT {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->type = 'sp_statistic';

		// Admin Columns
		add_filter( 'manage_edit-sp_statistic_columns', array( $this, 'edit_columns' ) );
		add_action( 'manage_sp_statistic_posts_custom_column', array( $this, 'custom_columns' ), 2, 2 );
		
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
			'sp_equation' => __( 'Equation', 'sportspress' ),
			'sp_precision' => __( 'Decimal Places', 'sportspress' ),
			'sp_description' => __( 'Description', 'sportspress' ),
		);
		return apply_filters( 'sportspress_statistic_admin_columns', $columns );
	}

	/**
	 * Define our custom columns shown in admin.
	 * @param  string $column
	 */
	public function custom_columns( $column, $post_id ) {
		switch ( $column ):
			case 'sp_key':
				global $post;
				echo $post->post_name;
				break;
			case 'sp_equation':
				echo sp_get_post_equation( $post_id );
				break;
			case 'sp_precision':
				echo sp_get_post_precision( $post_id );
				break;
			case 'sp_description':
				global $post;
				echo '<span class="description">' . $post->post_excerpt . '</span>';
				break;
		endswitch;
	}
}

endif;

return new SP_Admin_CPT_Statistic();
