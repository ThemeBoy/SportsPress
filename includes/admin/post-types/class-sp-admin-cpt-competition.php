<?php
/**
 * Admin functions for the league tables post type
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Post_Types
 * @version		2.5
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'SP_Admin_CPT' ) )
	include( 'class-sp-admin-cpt.php' );

if ( ! class_exists( 'SP_Admin_CPT_Competition' ) ) :

/**
 * SP_Admin_CPT_Competition Class
 */
class SP_Admin_CPT_Competition extends SP_Admin_CPT {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->type = 'sp_competition';

		// Post title fields
		add_filter( 'enter_title_here', array( $this, 'enter_title_here' ), 1, 2 );
		
		// Empty data filter
		add_filter( 'wp_insert_post_empty_content', array( $this, 'wp_insert_post_empty_content' ), 99, 2 );
		
		// Before data updates
		add_filter( 'wp_insert_post_data', array( $this, 'wp_insert_post_data' ), 99, 2 );
		
		// Admin Columns
		add_filter( 'manage_edit-sp_competition_columns', array( $this, 'edit_columns' ) );
		add_action( 'manage_sp_competition_posts_custom_column', array( $this, 'custom_columns' ), 2, 2 );

		// Filtering
		add_action( 'restrict_manage_posts', array( $this, 'filters' ) );
		
		// Call SP_Admin_CPT constructor
		parent::__construct();
	}

		/**
	 * Change title boxes in admin. *WIP*
	 * @param  string $text
	 * @param  object $post
	 * @return string
	 */
	public function enter_title_here( $text, $post ) {
		if ( $post->post_type == 'sp_competition' )
			return __( '(Auto)', 'sportspress' );

		return $text;
	}
	
	/**
	 * Mark as not empty when saving competition if league and season are selected for auto title. *WIP*
	 *
	 * @param array $maybe_empty
	 * @param array $postarr
	 * @return bool
	 */
	public function wp_insert_post_empty_content( $maybe_empty, $postarr ) {
		if ( $maybe_empty && 'sp_competition' === sp_array_value( $postarr, 'post_type' ) ):
			$leagues = sp_array_value( sp_array_value( $postarr, 'tax_input', array() ), 'sp_league', array() );
			$leagues = array_filter( $leagues );
			$seasons = sp_array_value( sp_array_value( $postarr, 'tax_input', array() ), 'sp_season', array() );
			$seasons = array_filter( $seasons );
			if ( sizeof( $leagues ) ||  sizeof( $seasons ) ) return false;
		endif;

		return $maybe_empty;
	}
	
		/**
	 * Auto-generate an competition title based on the league and season if left blank. *WIP*
	 *
	 * @param array $data
	 * @param array $postarr
	 * @return array
	 */
	public function wp_insert_post_data( $data, $postarr ) {
		if ( $data['post_type'] == 'sp_competition' && $data['post_title'] == '' ):

			$parts = array();

			$leagues = sp_array_value( sp_array_value( $postarr, 'tax_input', array() ), 'sp_league', array() );
			$leagues = array_filter( $leagues );
			if ( sizeof( $leagues ) ):
				$league_id = reset( $leagues );
				$league = get_term_by( 'id', $league_id, 'sp_league' );
				$parts[] = $league->name;
			endif;

			$seasons = sp_array_value( sp_array_value( $postarr, 'tax_input', array() ), 'sp_season', array() );
			$seasons = array_filter( $seasons );
			if ( sizeof( $seasons ) ):
				$season_id = reset( $seasons );
				$season = get_term_by( 'id', $season_id, 'sp_season' );
				$parts[] = $season->name;
			endif;
			
			$data['post_title'] = implode( $parts, ' ' );

		endif;

		return $data;
	}
	
	/**
	 * Change the columns shown in admin.
	 */
	public function edit_columns( $existing_columns ) {
		//unset( $existing_columns['date'] );
		unset( $existing_columns['author'] );
		$columns = array_merge( array(
			'cb' => '<input type="checkbox" />',
			'title' => __( 'Title', 'sportspress' ),
			'sp_league' => __( 'League', 'sportspress' ),
			'sp_season' => __( 'Season', 'sportspress' ),
		), $existing_columns );
		return apply_filters( 'sportspress_table_admin_columns', $columns );
	}

	/**
	 * Define our custom columns shown in admin.
	 * @param  string $column
	 */
	public function custom_columns( $column, $post_id ) {
		switch ( $column ):
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

	    if ( $typenow != 'sp_competition' )
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

return new SP_Admin_CPT_Competition();
