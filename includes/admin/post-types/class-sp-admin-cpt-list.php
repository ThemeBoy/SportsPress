<?php
/**
 * Admin functions for the player lists post type
 *
 * @author      ThemeBoy
 * @category    Admin
 * @package     SportsPress/Admin/Post_Types
 * @version     2.7.9
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'SP_Admin_CPT' ) ) {
	require 'class-sp-admin-cpt.php';
}

if ( ! class_exists( 'SP_Admin_CPT_List' ) ) :

	/**
	 * SP_Admin_CPT_List Class
	 */
	class SP_Admin_CPT_List extends SP_Admin_CPT {

		/**
		 * Constructor
		 */
		public function __construct() {
			$this->type = 'sp_list';

			// Admin Columns
			add_filter( 'manage_edit-sp_list_columns', array( $this, 'edit_columns' ) );
			add_action( 'manage_sp_list_posts_custom_column', array( $this, 'custom_columns' ), 2, 2 );

			// Filtering
			add_action( 'restrict_manage_posts', array( $this, 'filters' ) );
			add_filter( 'parse_query', array( $this, 'filters_query' ) );

			// Call SP_Admin_CPT constructor
			parent::__construct();
		}

		/**
		 * Change the columns shown in admin.
		 */
		public function edit_columns( $existing_columns ) {
			unset( $existing_columns['author'], $existing_columns['date'] );
			$columns = array_merge(
				array(
					'cb'        => '<input type="checkbox" />',
					'title'     => esc_attr__( 'Title', 'sportspress' ),
					'sp_league' => esc_attr__( 'League', 'sportspress' ),
					'sp_season' => esc_attr__( 'Season', 'sportspress' ),
					'sp_team'   => esc_attr__( 'Team', 'sportspress' ),
					'sp_player' => esc_attr__( 'Players', 'sportspress' ),
					'sp_layout' => esc_attr__( 'Layout', 'sportspress' ),
				),
				$existing_columns
			);
			return apply_filters( 'sportspress_list_admin_columns', $columns );
		}

		/**
		 * Define our custom columns shown in admin.
		 *
		 * @param  string $column
		 */
		public function custom_columns( $column, $post_id ) {
			switch ( $column ) :
				case 'sp_player':
					$select = get_post_meta( $post_id, 'sp_select', true );
					if ( 'manual' == $select ) :
						$players = array_filter( get_post_meta( $post_id, 'sp_player' ) );
						echo esc_html( sizeof( $players ) );
					else :
						esc_html_e( 'Auto', 'sportspress' );
					endif;
					break;
				case 'sp_league':
					echo get_the_terms( $post_id, 'sp_league' ) ? wp_kses_post( the_terms( $post_id, 'sp_league' ) ) : esc_html__( 'All', 'sportspress' );
					break;
				case 'sp_season':
					echo get_the_terms( $post_id, 'sp_season' ) ? wp_kses_post( the_terms( $post_id, 'sp_season' ) ) : esc_html__( 'All', 'sportspress' );
					break;
				case 'sp_team':
					$teams = (array) get_post_meta( $post_id, 'sp_team', false );
					$teams = array_filter( $teams );
					if ( empty( $teams ) ) :
						echo esc_html__( 'All', 'sportspress' );
					else :
						foreach ( $teams as $team_id ) :
							if ( ! $team_id ) {
								continue;
							}
							$team = get_post( $team_id );
							if ( $team ) {
								echo esc_html( $team->post_title ) . '<br>';
							}
						endforeach;
					endif;
					break;
				case 'sp_layout':
					echo esc_html( sp_array_value( SP()->formats->list, get_post_meta( $post_id, 'sp_format', true ), '&mdash;' ) );
					break;
			endswitch;
		}

		/**
		 * Show a category filter box
		 */
		public function filters() {
			global $typenow, $wp_query;

			if ( $typenow != 'sp_list' ) {
				return;
			}

			$selected = isset( $_REQUEST['sp_league'] ) ? sanitize_key( $_REQUEST['sp_league'] ) : null;
			$args     = array(
				'show_option_all' => esc_attr__( 'Show all leagues', 'sportspress' ),
				'taxonomy'        => 'sp_league',
				'name'            => 'sp_league',
				'selected'        => $selected,
			);
			sp_dropdown_taxonomies( $args );

			$selected = isset( $_REQUEST['sp_season'] ) ? sanitize_key( $_REQUEST['sp_season'] ) : null;
			$args     = array(
				'show_option_all' => esc_attr__( 'Show all seasons', 'sportspress' ),
				'taxonomy'        => 'sp_season',
				'name'            => 'sp_season',
				'selected'        => $selected,
			);
			sp_dropdown_taxonomies( $args );

			$selected = isset( $_REQUEST['team'] ) ? sanitize_key( $_REQUEST['team'] ) : null;
			$args     = array(
				'post_type'        => 'sp_team',
				'name'             => 'team',
				'show_option_none' => esc_attr__( 'Show all teams', 'sportspress' ),
				'selected'         => $selected,
				'values'           => 'ID',
			);
			wp_dropdown_pages( $args ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		/**
		 * Filter in admin based on options
		 *
		 * @param mixed $query
		 */
		public function filters_query( $query ) {
			global $typenow, $wp_query;

			if ( $typenow == 'sp_list' ) {

				if ( ! empty( $_GET['team'] ) ) {
					$query->query_vars['meta_value'] = sanitize_key( $_GET['team'] );
					$query->query_vars['meta_key']   = 'sp_team';
				}
			}
		}
	}

endif;

return new SP_Admin_CPT_List();
