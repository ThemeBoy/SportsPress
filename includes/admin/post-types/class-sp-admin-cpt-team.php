<?php
/**
 * Admin functions for the teams post type
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Post_Types
 * @version		2.6
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'SP_Admin_CPT' ) )
	include( 'class-sp-admin-cpt.php' );

if ( ! class_exists( 'SP_Admin_CPT_Team' ) ) :

/**
 * SP_Admin_CPT_Team Class
 */
class SP_Admin_CPT_Team extends SP_Admin_CPT {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->type = 'sp_team';

		// Post title fields
		add_filter( 'enter_title_here', array( $this, 'enter_title_here' ), 1, 2 );

		// Admin Columns
		add_filter( 'manage_edit-sp_team_columns', array( $this, 'edit_columns' ) );
		add_action( 'manage_sp_team_posts_custom_column', array( $this, 'custom_columns' ), 2, 2 );

		// Filtering
		add_action( 'restrict_manage_posts', array( $this, 'filters' ) );
		
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
		if ( $post->post_type == 'sp_team' )
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
			'sp_icon' => '<span class="dashicons sp-icon-shield sp-tip" title="' . __( 'Logo', 'sportspress' ) . '"></span>',
			'title' => null,
			'sp_short_name' => __( 'Short Name', 'sportspress' ),
			'sp_abbreviation' => __( 'Abbreviation', 'sportspress' ),
			'sp_league' => __( 'Leagues', 'sportspress' ),
			'sp_season' => __( 'Seasons', 'sportspress' ),
		), $existing_columns, array(
			'title' => __( 'Team', 'sportspress' ),
		) );
		return apply_filters( 'sportspress_team_admin_columns', $columns );
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
			case 'sp_short_name':
				$short_name = get_post_meta ( $post_id, 'sp_short_name', true );
				echo $short_name ? esc_html( $short_name ) : '&mdash;';
				break;
			case 'sp_abbreviation':
				$abbreviation = get_post_meta ( $post_id, 'sp_abbreviation', true );
				echo $abbreviation ? esc_html( $abbreviation ) : '&mdash;';
				break;
			case 'sp_league':
				echo get_the_terms ( $post_id, 'sp_league' ) ? the_terms( $post_id, 'sp_league' ) : '&mdash;';
				break;
			case 'sp_season':
				echo get_the_terms ( $post_id, 'sp_season' ) ? the_terms( $post_id, 'sp_season' ) : '&mdash;';
				break;
		endswitch;
	}

	/**
	 * Show a category filter box
	 */
	public function filters() {
		global $typenow, $wp_query;

	    if ( $typenow != 'sp_team' )
	    	return;

		$selected = isset( $_REQUEST['sp_league'] ) ? $_REQUEST['sp_league'] : null;
		$args = array(
			'show_option_all' =>  __( 'Show all leagues', 'sportspress' ),
			'taxonomy' => 'sp_league',
			'name' => 'sp_league',
			'selected' => $selected
		);
		sp_dropdown_taxonomies( $args );

		$selected = isset( $_REQUEST['sp_season'] ) ? $_REQUEST['sp_season'] : null;
		$args = array(
			'show_option_all' =>  __( 'Show all seasons', 'sportspress' ),
			'taxonomy' => 'sp_season',
			'name' => 'sp_season',
			'selected' => $selected
		);
		sp_dropdown_taxonomies( $args );
	}
}

endif;

return new SP_Admin_CPT_Team();
