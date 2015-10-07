<?php
/**
 * Admin functions for the sponsors post type
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress Sponsors
 * @version     1.9.10
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'SP_Admin_CPT' ) )
	include( SP()->plugin_path() . '/admin/class-sp-admin-cpt.php' );

if ( ! class_exists( 'SP_Admin_CPT_Sponsor' ) ) :

/**
 * SP_Admin_CPT_Sponsor Class
 */
class SP_Admin_CPT_Sponsor extends SP_Admin_CPT {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->type = 'sp_sponsor';

		// Post title fields
		add_filter( 'enter_title_here', array( $this, 'enter_title_here' ), 1, 2 );

		// Admin Columns
		add_filter( 'manage_edit-sp_sponsor_columns', array( $this, 'edit_columns' ) );
		add_action( 'manage_sp_sponsor_posts_custom_column', array( $this, 'custom_columns' ), 2, 2 );
		add_filter( 'manage_edit-sp_sponsor_sortable_columns', array( $this, 'custom_columns_sort' ) );

		// Highlight menu
		add_action( 'admin_head', array( $this, 'menu_highlight' ) );
		
		// Call SP_Admin_CPT constructor
		parent::__construct();
	}

	/**
	 * Change title boxes in admin.
	 * @param  string $text
	 * @param  object $post
	 * @return string
	 */
	public function enter_title_here( $text, $post ) {
		if ( $post->post_type == 'sp_sponsor' )
			return __( 'Name', 'sportspress' );

		return $text;
	}

	/**
	 * Change the columns shown in admin.
	 */
	public function edit_columns( $existing_columns ) {
		unset( $existing_columns['author'], $existing_columns['date'] );
		$columns = array_merge( array(
			'cb' => '<input type="checkbox" />',
			'sp_icon' => null,
			'title' => null,
			'sp_url' => __( 'URL', 'sportspress' ),
			'sp_impressions' => __( 'Impressions', 'sportspress' ),
			'sp_clicks' => __( 'Clicks', 'sportspress' ),
		), $existing_columns, array(
			'sp_icon' => '<span class="dashicons sp-icon-megaphone sp-tip" title="' . __( 'Logo', 'sportspress' ) . '"></span>',
			'title' => __( 'Sponsor', 'sportspress' ),
		) );
		return apply_filters( 'sportspress_sponsor_admin_columns', $columns );
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
			case 'sp_url':
	        	echo strip_tags( sp_get_url( $post_id ), '<a>' );
				break;
			case 'sp_impressions':
	        	echo sp_get_post_impressions( $post_id );
				break;
			case 'sp_clicks':
	        	echo sp_get_post_clicks( $post_id );
				break;
		endswitch;
	}

	/**
	 * Make columns sortable
	 *
	 * https://gist.github.com/906872
	 *
	 * @access public
	 * @param mixed $columns
	 * @return array
	 */
	public function custom_columns_sort( $columns ) {
		$custom = array(
			'sp_impressions'	=> 'sp_impressions',
			'sp_clicks'			=> 'sp_clicks',
		);
		return wp_parse_args( $custom, $columns );
	}

	/**
	 * Highlights the correct top level admin menu item for post type add screens.
	 *
	 * @access public
	 * @return void
	 */
	public function menu_highlight() {
		global $typenow, $submenu_file;
		if ( 'sp_sponsor' == $typenow )
			$submenu_file = 'edit.php?post_type=sp_sponsor';
	}
}

endif;

return new SP_Admin_CPT_Sponsor();
