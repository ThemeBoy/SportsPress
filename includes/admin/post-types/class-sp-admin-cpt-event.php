<?php
/**
 * Admin functions for the events post type
 *
 * @author      ThemeBoy
 * @category    Admin
 * @package     SportsPress/Admin/Post_Types
 * @version     2.7.23
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'SP_Admin_CPT' ) ) {
	require 'class-sp-admin-cpt.php';
}

if ( ! class_exists( 'SP_Admin_CPT_Event' ) ) :

	/**
	 * SP_Admin_CPT_Event Class
	 */
	class SP_Admin_CPT_Event extends SP_Admin_CPT {

		/**
		 * Constructor
		 */
		public function __construct() {
			$this->type = 'sp_event';

			// Post title fields
			add_filter( 'enter_title_here', array( $this, 'enter_title_here' ), 1, 2 );

			// Empty data filter
			add_filter( 'wp_insert_post_empty_content', array( $this, 'wp_insert_post_empty_content' ), 99, 2 );

			// Before data updates
			add_filter( 'wp_insert_post_data', array( $this, 'wp_insert_post_data' ), 99, 2 );

			// Admin Columns
			add_filter( 'manage_edit-sp_event_columns', array( $this, 'edit_columns' ) );
			add_filter( 'manage_edit-sp_event_sortable_columns', array( $this, 'sortable_columns' ) );
			add_action( 'pre_get_posts', array( $this, 'orderby_columns' ) );
			add_action( 'manage_sp_event_posts_custom_column', array( $this, 'custom_columns' ), 2, 2 );

			// Filtering
			add_action( 'restrict_manage_posts', array( $this, 'filters' ) );
			add_filter( 'parse_query', array( $this, 'filters_query' ) );

			// Post states
			add_filter( 'display_post_states', array( $this, 'post_states' ), 10, 2 );

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
			if ( $post->post_type == 'sp_event' ) {
				return esc_attr__( '(Auto)', 'sportspress' );
			}

			return $text;
		}

		/**
		 * Mark as not empty when saving event if teams are selected for auto title.
		 *
		 * @param array $maybe_empty
		 * @param array $postarr
		 * @return bool
		 */
		public function wp_insert_post_empty_content( $maybe_empty, $postarr ) {
			if ( $maybe_empty && 'sp_event' === sp_array_value( $postarr, 'post_type' ) ) :
				$teams = sp_array_value( $postarr, 'sp_team', array() );
				$teams = array_filter( $teams );
				if ( sizeof( $teams ) ) {
					return false;
				}
			endif;

			return $maybe_empty;
		}

		/**
		 * Auto-generate an event title based on the team playing if left blank.
		 *
		 * @param array $data
		 * @param array $postarr
		 * @return array
		 */
		public function wp_insert_post_data( $data, $postarr ) {
			if ( $data['post_type'] == 'sp_event' && $data['post_title'] == '' ) :

				$teams = sp_array_value( $postarr, 'sp_team', array() );
				$teams = array_filter( $teams );

				$team_names = array();
				foreach ( $teams as $team ) :
					while ( is_array( $team ) ) {
						$team = array_shift( array_filter( $team ) );
					}
					if ( $team > 0 ) {
						$team_names[] = sp_team_short_name( $team );
					}
				endforeach;

				$team_names = array_unique( $team_names );

				$reverse_teams = get_option( 'sportspress_event_reverse_teams', 'no' ) === 'yes' ? true : false;
				if ( $reverse_teams ) {
					$team_names = array_reverse( $team_names );
				}

				$data['post_title'] = implode( ' ' . get_option( 'sportspress_event_teams_delimiter', 'vs' ) . ' ', $team_names );

			endif;

			return $data;
		}

		/**
		 * Change the columns shown in admin.
		 */
		public function edit_columns( $existing_columns ) {
			unset( $existing_columns['author'], $existing_columns['comments'] );
			$columns = array_merge(
				array(
					'cb'        => '<input type="checkbox" />',
					'sp_format' => '<span class="dashicons sp-icon-calendar sp-tip" title="' . esc_attr__( 'Format', 'sportspress' ) . '"></span>',
					'title'     => null,
					'date'      => esc_attr__( 'Date', 'sportspress' ),
					'sp_time'   => esc_attr__( 'Time', 'sportspress' ),
					'sp_team'   => esc_attr__( 'Teams', 'sportspress' ),
					'sp_league' => esc_attr__( 'League', 'sportspress' ),
					'sp_season' => esc_attr__( 'Season', 'sportspress' ),
					'sp_venue'  => esc_attr__( 'Venue', 'sportspress' ),
					'sp_day'    => esc_attr__( 'Match Day', 'sportspress' ),
				),
				$existing_columns,
				array(
					'title' => esc_attr__( 'Event', 'sportspress' ),
				)
			);
			return apply_filters( 'sportspress_event_admin_columns', $columns );
		}

		/**
		 * Change the sortable columns in admin.
		 */
		public function sortable_columns( $columns ) {
			$columns['sp_day'] = 'sp_day';
			return $columns;
		}

		/**
		 * Define the sortable columns in admin.
		 */
		public function orderby_columns( $query ) {
			if ( ! is_admin() ) {
				return;
			}

			$orderby = $query->get( 'orderby' );

			if ( 'sp_day' == $orderby ) {
				$query->set( 'meta_key', 'sp_day' );
				$query->set( 'orderby', 'meta_value_num' );
			}
		}

		/**
		 * Define our custom columns shown in admin.
		 *
		 * @param  string $column
		 */
		public function custom_columns( $column, $post_id ) {
			switch ( $column ) :
				case 'sp_format':
					$format        = get_post_meta( $post_id, 'sp_format', true );
					$formats       = new SP_Formats();
					$event_formats = $formats->event;
					if ( array_key_exists( $format, $event_formats ) ) :
						echo '<span class="dashicons sp-icon-' . esc_attr( $format ) . ' sp-tip" title="' . esc_attr( $event_formats[ $format ] ) . '"></span>';
					endif;
					break;
				case 'sp_time':
					echo wp_kses_post( apply_filters( 'sportspress_event_time_admin', get_post_time( 'H:i', false, $post_id, true ) ) );
					break;
				case 'sp_team':
					$teams         = (array) get_post_meta( $post_id, 'sp_team', false );
					$teams         = array_filter( $teams );
					$teams         = array_unique( $teams );
					$reverse_teams = get_option( 'sportspress_event_reverse_teams', 'no' ) === 'yes' ? true : false;
					if ( $reverse_teams ) {
						$teams = array_reverse( $teams, true );
					}
					if ( empty( $teams ) ) :
						echo '&mdash;';
					else :
						$results     = get_post_meta( $post_id, 'sp_results', true );
						$main_result = get_option( 'sportspress_primary_result', null );
						echo '<input type="hidden" name="sp_post_id" value="' . esc_attr( $post_id ) . '">';
						echo '<div class="sp-results">';
						foreach ( $teams as $team_id ) :
							if ( ! $team_id ) {
								continue;
							}
							$team = get_post( $team_id );

							if ( $team ) :
								$team_results = sportspress_array_value( $results, $team_id, null );

								if ( $main_result ) :
									$team_result = sportspress_array_value( $team_results, $main_result, null );
								else :
									if ( is_array( $team_results ) ) :
										end( $team_results );
										$team_result = prev( $team_results );
										$main_result = key( $team_results );
									else :
										$team_result = null;
									endif;
								endif;

								if ( is_array( $team_results ) ) :
									unset( $team_results['outcome'] );
									$team_results = array_filter( $team_results, 'sp_filter_non_empty' );
									$team_results = implode( ' | ', $team_results );
								endif;

								echo '<a class="sp-result sp-tip" tabindex="10" title="' . esc_attr( $team_results ) . '" data-team="' . esc_attr( $team_id ) . '" href="#">' . ( esc_attr( $team_result ) == '' ? '-' : wp_kses_post( apply_filters( 'sportspress_event_team_result_admin', $team_result, $post_id, $team_id ) ) ) . '</a>';
								echo '<input type="text" tabindex="10" class="sp-edit-result hidden small-text" data-team="' . esc_attr( $team_id ) . '" data-key="' . esc_attr( $main_result ) . '" value="' . esc_attr( $team_result ) . '"> ';
								echo esc_html( $team->post_title );
								echo '<br>';
							endif;
							endforeach;
						echo '</div>';
						if ( current_user_can( 'edit_others_sp_events' ) ) {
							?>
						<div class="row-actions sp-row-actions"><span class="inline hide-if-no-js"><a href="#" class="sp-edit-results"><?php esc_html_e( 'Edit Results', 'sportspress' ); ?></a></span></div>
						<p class="inline-edit-save sp-inline-edit-save hidden">
							<a href="#inline-edit" class="button-secondary cancel alignleft"><?php esc_html_e( 'Cancel' ); ?></a>
							<a href="#inline-edit" class="button-primary save alignright"><?php esc_html_e( 'Update' ); ?></a>
						</p>
							<?php
						}
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
				case 'sp_venue':
					$terms = get_the_terms( $post_id, 'sp_venue' );
					echo ( $terms && ! is_wp_error( $terms ) ) ? wp_kses_post( implode( ', ', wp_list_pluck( $terms, 'name' ) ) ) : '&mdash;';
					break;
				case 'sp_day':
					$day = get_post_meta( $post_id, 'sp_day', true );
					if ( '' === $day ) {
						$day = esc_attr__( 'Default', 'sportspress' );
					}
					echo esc_html( $day );
					break;
			endswitch;
		}

		/**
		 * Show a category filter box
		 */
		public function filters() {
			global $typenow, $wp_query;

			if ( $typenow != 'sp_event' ) {
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
			
			$selected = isset( $_REQUEST['sp_venue'] ) ? sanitize_key( $_REQUEST['sp_venue'] ) : null;
			$args     = array(
				'show_option_all' => esc_attr__( 'Show all grounds', 'sportspress' ),
				'taxonomy'        => 'sp_venue',
				'name'            => 'sp_venue',
				'selected'        => $selected,
			);
			sp_dropdown_taxonomies( $args );

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

			$selected = isset( $_REQUEST['match_day'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['match_day'] ) ) : null;
			echo '<input name="match_day" type="text" class="sp-tablenav-input" placeholder="' . esc_attr__( 'Match Day', 'sportspress' ) . '" value="' . esc_attr( $selected ) . '">';

			if ( current_user_can( 'edit_others_sp_events' ) ) {
				wp_nonce_field( 'sp-save-inline-results', 'sp-inline-nonce', false );
			}
		}

		/**
		 * Filter in admin based on options
		 *
		 * @param mixed $query
		 */
		public function filters_query( $query ) {
			global $typenow, $wp_query;

			if ( $typenow == 'sp_event' ) {
				  // Avoid overriding relation operator if already set
				if ( ! isset( $query->query_vars['meta_query']['relation'] ) ) {
					$query->query_vars['meta_query']['relation'] = 'AND';
				}

				if ( ! empty( $_GET['team'] ) ) {
					$query->query_vars['meta_query'][] = array(
						'key'   => 'sp_team',
						'value' => sanitize_key( $_GET['team'] ),
					);
				}

				if ( ! empty( $_GET['match_day'] ) ) {
					$query->query_vars['meta_query'][] = array(
						'key'   => 'sp_day',
						'value' => sanitize_text_field( wp_unslash( $_GET['match_day'] ) ),
					);
				}
			}
		}

		/**
		 * Replace displayed post state for events
		 *
		 * @param array  $post_states
		 * @param object $post
		 */
		public function post_states( $post_states, $post ) {
			$status = get_post_meta( $post->ID, 'sp_status', true );

			if ( 'postponed' == $status ) {
				$post_states = array( esc_attr__( 'Postponed', 'sportspress' ) );
			} elseif ( 'cancelled' == $status ) {
				$post_states = array( esc_attr__( 'Canceled', 'sportspress' ) );
			} elseif ( 'tbd' == $status ) {
				$post_states = array( esc_attr__( 'TBD', 'sportspress' ) );
			}

			return $post_states;
		}
	}

endif;

return new SP_Admin_CPT_Event();
