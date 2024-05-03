<?php
/**
 * Admin functions for the staff post type
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

if ( ! class_exists( 'SP_Admin_CPT_Staff' ) ) :

	/**
	 * SP_Admin_CPT_Staff Class
	 */
	class SP_Admin_CPT_Staff extends SP_Admin_CPT {

		/**
		 * Constructor
		 */
		public function __construct() {
			$this->type = 'sp_staff';

			// Post title fields
			add_filter( 'enter_title_here', array( $this, 'enter_title_here' ), 1, 2 );

			// Admin Columns
			add_filter( 'manage_edit-sp_staff_columns', array( $this, 'edit_columns' ) );
			add_action( 'manage_sp_staff_posts_custom_column', array( $this, 'custom_columns' ), 2, 2 );

			// Filtering
			add_action( 'restrict_manage_posts', array( $this, 'filters' ) );
			add_filter( 'parse_query', array( $this, 'filters_query' ) );

			// Call SP_Admin_CPT constructor
			parent::__construct();
		}

		/**
		 * Change title boxes in admin.
		 *
		 * @param  string $text
		 * @param  object $post
		 * @return string
		 */
		public function enter_title_here( $text, $post ) {
			if ( $post->post_type == 'sp_staff' ) {
				return esc_attr__( 'Name', 'sportspress' );
			}

			return $text;
		}

		/**
		 * Change the columns shown in admin.
		 */
		public function edit_columns( $existing_columns ) {
			unset( $existing_columns['author'], $existing_columns['date'] );
			$columns = array_merge(
				array(
					'cb'        => '<input type="checkbox" />',
					'title'     => null,
					'sp_role'   => esc_attr__( 'Job', 'sportspress' ),
					'sp_team'   => esc_attr__( 'Teams', 'sportspress' ),
					'sp_league' => esc_attr__( 'Leagues', 'sportspress' ),
					'sp_season' => esc_attr__( 'Seasons', 'sportspress' ),
				),
				$existing_columns,
				array(
					'title' => esc_attr__( 'Name', 'sportspress' ),
				)
			);
			return apply_filters( 'sportspress_staff_admin_columns', $columns );
		}

		/**
		 * Define our custom columns shown in admin.
		 *
		 * @param  string $column
		 */
		public function custom_columns( $column, $post_id ) {
			switch ( $column ) :
				case 'sp_role':
					$terms = get_the_terms( $post_id, 'sp_role' );
					echo ( $terms && ! is_wp_error( $terms ) ) ? wp_kses_post( implode( ', ', wp_list_pluck( $terms, 'name' ) ) ) : '&mdash;';
					break;
				case 'sp_team':
					$teams = (array) get_post_meta( $post_id, 'sp_team', false );
					$teams = array_filter( $teams );
					if ( empty( $teams ) ) :
						echo '&mdash;';
					else :
						$current_teams = get_post_meta( $post_id, 'sp_current_team', false );
						foreach ( $teams as $team_id ) :
							if ( ! $team_id ) {
								continue;
							}
							$team = get_post( $team_id );
							if ( $team ) :
								echo esc_html( $team->post_title );
								if ( in_array( $team_id, $current_teams ) ) :
									echo '<span class="dashicons dashicons-yes" title="' . esc_attr__( 'Current Team', 'sportspress' ) . '"></span>';
								endif;
								echo '<br>';
							endif;
						endforeach;
					endif;
					break;
				case 'sp_league':
					$terms = get_the_terms( $post_id, 'sp_league' );
					echo ( $terms && ! is_wp_error( $terms ) ) ? wp_kses_post( implode( ', ', wp_list_pluck( $terms, 'name' ) ) ) : '&mdash;';
					break;
				case 'sp_season':
					$terms = get_the_terms( $post_id, 'sp_season' );
					echo ( $terms && ! is_wp_error( $terms ) ) ? wp_kses_post( implode( ', ', wp_list_pluck( $terms, 'name' ) ) ) : '&mdash;';
					break;
			endswitch;
		}

		/**
		 * Show a category filter box
		 */
		public function filters() {
			global $typenow, $wp_query;

			if ( $typenow != 'sp_staff' ) {
				return;
			}

			$selected = isset( $_REQUEST['team'] ) ? sanitize_key( $_REQUEST['team'] ) : null;
			$args     = array(
				'post_type'        => 'sp_team',
				'name'             => 'team',
				'show_option_none' => esc_attr__( 'Show all teams', 'sportspress' ),
				'selected'         => $selected,
				'values'           => 'ID',
			);
			wp_dropdown_pages( $args ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

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
		}

		/**
		 * Filter in admin based on options
		 *
		 * @param mixed $query
		 */
		public function filters_query( $query ) {
			global $typenow, $wp_query;

			if ( $typenow == 'sp_staff' ) {

				if ( ! empty( $_GET['team'] ) ) {
					$query->query_vars['meta_value'] = sanitize_key( $_GET['team'] );
					$query->query_vars['meta_key']   = 'sp_team';
				}
			}
		}
	}

endif;

return new SP_Admin_CPT_Staff();
