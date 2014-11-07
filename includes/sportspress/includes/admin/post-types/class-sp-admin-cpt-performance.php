<?php
/**
 * Admin functions for the performance post type
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Post_Types
 * @version     1.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'SP_Admin_CPT' ) )
	include( 'class-sp-admin-cpt.php' );

if ( ! class_exists( 'SP_Admin_CPT_Performance' ) ) :

/**
 * SP_Admin_CPT_Performance Class
 */
class SP_Admin_CPT_Performance extends SP_Admin_CPT {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->type = 'sp_performance';

		// Admin Columns
		add_filter( 'manage_edit-sp_performance_columns', array( $this, 'edit_columns' ) );
		add_action( 'manage_sp_performance_posts_custom_column', array( $this, 'custom_columns' ), 2, 2 );
		
		// Call SP_Admin_CPT constructor
		parent::__construct();
	}

	/**
	 * Change the columns shown in admin.
	 */
	public function edit_columns( $existing_columns ) {
		$columns = array(
			'cb' => '<input type="checkbox" />',
			'sp_icon' => __( 'Icon', 'sportspress' ),
			'title' => __( 'Label', 'sportspress' ),
			'sp_key' => __( 'Variable', 'sportspress' ),
			'sp_description' => __( 'Description', 'sportspress' ),
		);
		return apply_filters( 'sportspress_performance_admin_columns', $columns );
	}

	/**
	 * Define our custom columns shown in admin.
	 * @param  string $column
	 */
	public function custom_columns( $column, $post_id ) {
		switch ( $column ):
			case 'sp_icon':
				echo has_post_thumbnail( $post_id ) ? edit_post_link( get_the_post_thumbnail( $post_id, 'sportspress-fit-mini' ), '', '', $post_id ) : '';
				break;
			case 'sp_key':
				global $post;
				echo $post->post_name;
				break;
			case 'sp_description':
				global $post;
				echo '<span class="description">' . $post->post_excerpt . '</span>';
				break;
		endswitch;
	}
}

endif;

return new SP_Admin_CPT_Performance();
