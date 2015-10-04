<?php
/**
 * Tournament Meta Boxes
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress_Tournaments
 * @version     1.9.7
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * SP_Tournament_Meta_Boxes
 */
class SP_Tournament_Meta_Boxes {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'remove_meta_boxes' ), 10 );
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ), 30 );
		add_action( 'sportspress_process_sp_tournament_meta', array( $this, 'save' ) );
	}

	/**
	 * Add Meta boxes
	 */
	public function add_meta_boxes() {
		add_meta_box( 'sp_shortcodediv', __( 'Shortcode', 'sportspress' ), array( $this, 'shortcode' ), 'sp_tournament', 'side', 'default' );
		add_meta_box( 'sp_formatdiv', __( 'Layout', 'sportspress' ), array( $this, 'format' ), 'sp_tournament', 'side', 'default' );
		add_meta_box( 'sp_detailsdiv', __( 'Details', 'sportspress' ), array( $this, 'details' ), 'sp_tournament', 'side', 'default' );
		add_meta_box( 'sp_datadiv', __( 'Tournament', 'sportspress' ), array( $this, 'data' ), 'sp_tournament', 'normal', 'high' );
		add_meta_box( 'sp_editordiv', __( 'Description', 'sportspress' ), array( $this, 'editor' ), 'sp_tournament', 'normal', 'low' );
	}

	/**
	 * Remove default meta boxes
	 */
	public function remove_meta_boxes() {
		remove_meta_box( 'sp_seasondiv', 'sp_tournament', 'side' );
		remove_meta_box( 'sp_leaguediv', 'sp_tournament', 'side' );
	}

	/**
	 * Output the shortcode metabox
	 */
	public static function shortcode( $post ) {
		?>
		<p class="howto">
			<?php _e( 'Copy this code and paste it into your post, page or text widget content.', 'sportspress' ); ?>
		</p>
		<p>
			<strong><?php _e( 'Bracket', 'sportspress' ); ?></strong>
		</p>
		<p><input type="text" value="<?php sp_shortcode_template( 'tournament_bracket', $post->ID ); ?>" readonly="readonly" class="code widefat"></p>
		<p>
			<strong><?php _e( 'Winner', 'sportspress' ); ?></strong>
		</p>
		<p><input type="text" value="<?php sp_shortcode_template( 'tournament_winner', $post->ID ); ?>" readonly="readonly" class="code widefat"></p>
		<?php
	}

	/**
	 * Output the format metabox
	 */
	public static function format( $post ) {
		$the_format = get_post_meta( $post->ID, 'sp_format', true );
		?>
		<div id="post-formats-select">
			<?php foreach ( SP()->formats->tournament as $key => $format ): ?>
				<input type="radio" name="sp_format" class="post-format" id="post-format-<?php echo $key; ?>" value="<?php echo $key; ?>" <?php checked( true, ( $key == 'bracket' && ! $the_format ) || $the_format == $key ); ?>> <label for="post-format-<?php echo $key; ?>" class="post-format-icon post-format-<?php echo $key; ?>"><?php echo $format; ?></label><br>
			<?php endforeach; ?>
		</div>
		<?php
	}

	/**
	 * Output the details metabox
	 */
	public static function details( $post ) {
		wp_nonce_field( 'sportspress_save_data', 'sportspress_meta_nonce' );
		$limit = get_option( 'sportspress_tournament_rounds', '6' );
		$taxonomies = get_object_taxonomies( 'sp_tournament' );
		$rounds = get_post_meta( $post->ID, 'sp_rounds', true );
		if ( $rounds === '' ) $rounds = 3;
		$winner = get_post_meta( $post->ID, 'sp_winner', true );
		?>
		<div>
			<?php
			foreach ( $taxonomies as $taxonomy ) {
				sp_taxonomy_field( $taxonomy, $post, true );
			}
			?>
			<p><strong><?php _e( 'Rounds', 'sportspress' ); ?></strong></p>
			<p><input name="sp_rounds" type="number" min="1" max="<?php echo esc_attr( $limit ); ?>" value="<?php echo $rounds; ?>" placeholder="0" class="small-text sp-autosave"></p>
			<p><strong><?php _e( 'Winner', 'sportspress' ); ?></strong></p>
			<p>
				<?php
				$args = array(
					'show_option_none' => __( '&mdash; Not set &mdash;', 'sportspress' ),
					'post_type' => 'sp_team',
					'name' => 'sp_winner',
					'selected' => $winner,
					'values' => 'ID'
				);
				sp_dropdown_pages( $args );
				?>
			</p>
		</div>
		<?php
	}

	/**
	 * Output the data metabox
	 */
	public static function data( $post ) {
		$tournament = new SP_Tournament( $post );
		list( $labels, $data, $rounds, $rows ) = $tournament->data( 'bracket', true );
		self::table( $labels, $data, $rounds, $rows, $post->ID );
	}

	/**
	 * Output the editor metabox
	 */
	public static function editor( $post ) {
		wp_editor( $post->post_content, 'content' );
	}

	/**
	 * Save meta boxes data
	 */
	public static function save( $post_id ) {
		// Format
		update_post_meta( $post_id, 'sp_format', sp_array_value( $_POST, 'sp_format', 'bracket' ) );

		// Rounds
		$limit = intval( get_option( 'sportspress_tournament_rounds', '6' ) );
		$rounds = sp_array_value( $_POST, 'sp_rounds', 1 );
		if ( $rounds < 1 ) $rounds = 1;
		elseif ( $rounds > $limit ) $rounds = $limit;
		update_post_meta( $post_id, 'sp_rounds', $rounds );

		// Winner
		update_post_meta( $post_id, 'sp_winner', sp_array_value( $_POST, 'sp_winner' ) );

		// Labels
		update_post_meta( $post_id, 'sp_labels', sp_array_value( $_POST, 'sp_labels', array() ) );

		// Get main result option
		$main_result = sp_get_main_result_option();

		// Get terms
		$tax_input = sp_array_value( $_POST, 'tax_input', array() );
		$leagues = array_map( 'intval', sp_array_value( $tax_input, 'sp_league', array() ) );
		$seasons = array_map( 'intval', sp_array_value( $tax_input, 'sp_season', array() ) );

		// Events
		$events = sp_array_value( $_POST, 'sp_event', array() );
		ksort( $events );
		$event_ids = array();
		foreach ( $events as $event ) {
			// Get details
			$id = sp_array_value( $event, 'id', 0 );
			$teams = sp_array_value( $event, 'teams', array() );
			$results = sp_array_value( $event, 'results', array() );
			$date = sp_array_value( $event, 'date', '' );
			$h = sp_array_value( $event, 'hh', '' );
			$m = sp_array_value( $event, 'mm', '00' );

			// Update or add new event
			if ( strlen( $date ) ) {
				// Add time to date if given
				if ( strlen( $h ) ):
					$h = substr( str_pad( $h, 2, '0', STR_PAD_LEFT ), 0, 2 );
					$m = substr( str_pad( $m, 2, '0', STR_PAD_LEFT ), 0, 2 );
					$time = $h . ':' . $m;
					$date .= ' ' . trim( $time );
				endif;

				// Generate title
				$team_names = array();
				foreach ( $teams as $team ) {
					if ( ! $team ) continue;
					$team_names[] = get_the_title( $team );
				}
				$new_title = implode( ' ' . get_option( 'sportspress_event_teams_delimiter', 'vs' ) . ' ', $team_names );

				// Update or add new event
				if ( $id ) {
					$post = array(
						'ID' => $id,
						'post_date' => $date,
					);

					// Update title if not set
					$title = get_the_title( $id );
					if ( ! strlen( $title ) ) {
						$post['post_title'] = $new_title;
						wp_update_post( $post );
					}
				} else {
					$args = array( 'post_type' => 'sp_event', 'post_title' => $new_title, 'post_status' => 'publish', 'post_date' => $date );
					$id = wp_insert_post( $args );

					// Update league
					if ( array_filter( $leagues ) ):
						wp_set_object_terms( $id, $leagues, 'sp_league', false );
					endif;

					// Update season
					if ( array_filter( $seasons ) ):
						wp_set_object_terms( $id, $seasons, 'sp_season', false );
					endif;
				}

				// Update teams
				delete_post_meta( $id, 'sp_team' );
				foreach ( $teams as $team ) {
					add_post_meta( $id, 'sp_team', $team );
				}

				// Update results
				if ( sizeof( $results ) && sizeof( $teams ) ) {
					$results = array_combine( $teams, $results );
					if ( $results ) {
						sp_update_main_results( $id, $results );
					}
				}

				// Update event format
				update_post_meta( $id, 'sp_format', 'tournament' );
			}

			// Add to event IDs
			$event_ids[] = $id;
		}
		update_post_meta( $post_id, 'sp_events', $events );
		sp_update_post_meta_recursive( $post_id, 'sp_event', $event_ids );
	}

	/**
	 * Admin edit table
	 */
	public static function table( $labels = array(), $data = null, $rounds = 3, $rows = 23, $post_id = null ) {
		$args = array(
			'post_type' => 'sp_team',
			'posts_per_page' => -1,
			'tax_query' => array(),
		);
		
		// Filter by league if selected
		$leagues = get_the_terms( $post_id, 'sp_league', 0 );
		if ( $leagues && ! is_wp_error( $leagues ) ) {
			// Get league IDs
			$league_ids = array();
			foreach ( $leagues as $league ) {
				$league_ids[] = $league->term_id;
			}

			$args['tax_query'][] = array(
				'taxonomy' => 'sp_league',
				'field' => 'id',
				'terms' => $league_ids,
			);
		}
		
		// Filter by season if selected
		$seasons = get_the_terms( $post_id, 'sp_season', 0 );
		if ( $seasons && ! is_wp_error( $seasons ) ) {
			// Get seasons IDs
			$season_ids = array();
			foreach ( $seasons as $seasons ) {
				$season_ids[] = $seasons->term_id;
			}

			$args['tax_query'][] = array(
				'taxonomy' => 'sp_season',
				'field' => 'id',
				'terms' => $season_ids,
			);
		}

		// Get teams
		$teams = get_posts( $args );
		?>
		<table class="widefat sp-tournament-container">
			<thead>
				<tr>
					<?php for ( $round = 0; $round < $rounds; $round++ ): ?>
						<th>
							<input type="text" class="widefat" name="sp_labels[]" value="<?php echo esc_attr( sp_array_value( $labels, $round, '' ) ); ?>" placeholder="<?php printf( esc_attr__( 'Round %s', 'sportspress' ), $round + 1 ); ?>">
						</th>
					<?php endfor; ?>
				</tr>
			</thead>
			<tbody>
				<?php for ( $row = 0; $row < $rows; $row++ ) { ?>
					<tr>
						<?php
						for ( $round = 0; $round < $rounds; $round++ ) {
							$cell = sp_array_value( sp_array_value( $data, $row, array() ), $round, null );
							if ( $cell === null ) continue;

							$index = sp_array_value( $cell, 'index' );

							if ( sp_array_value( $cell, 'type', null ) === 'event' ) {
								$event = sp_array_value( $cell, 'id', 0 );
								if ( $event ) {
									$results = sp_get_main_results( $event );
								} else {
									$results = null;
								}
								?>
								<td rowspan="<?php echo sp_array_value( $cell, 'rows', 1 ); ?>" class="sp-event<?php if ( 0 === $round ) { ?> sp-first-round<?php } if ( $rounds - 1 === $round ) { ?> sp-last-round<?php } ?>">
									<input type="hidden" name="sp_event[<?php echo $index; ?>][id]" value="<?php echo $event ? $event : 0; ?>">
									<label><?php _e( 'Date', 'sportspress' ); ?>:</label>
									<?php if ( $event ) { ?>
										<a title="<?php _e( 'Edit Event', 'sportspress' ); ?>" class="sp-edit sp-desc-tip dashicons dashicons-edit" href="<?php echo get_edit_post_link( $event, '' ); ?>" target="_blank"></a>
									<?php } ?>
									<input type="text" class="sp-datepicker" name="sp_event[<?php echo $index; ?>][date]" value="<?php if ( $event ) echo sp_get_time( $event, 'Y-m-d' ); ?>" size="10" autocomplete="off"><hr>
									<label><?php _e( 'Time', 'sportspress' ); ?>:</label>
									<input type="text" size="2" maxlength="2" name="sp_event[<?php echo $index; ?>][hh]" autocomplete="off" value="<?php if ( $event ) echo sp_get_time( $event, 'H' ); ?>">
									:
									<input type="text" size="2" maxlength="2" name="sp_event[<?php echo $index; ?>][mm]" autocomplete="off" value="<?php if ( $event ) echo sp_get_time( $event, 'i' ); ?>"><hr>
									<label><?php _e( 'Results', 'sportspress' ); ?>:</label>
									<input type="text" size="2" name="sp_event[<?php echo $index; ?>][results][]" value="<?php echo sp_array_value( $results, 0 ); ?>" autocomplete="off">
									-
									<input type="text" size="2" name="sp_event[<?php echo $index; ?>][results][]" value="<?php echo sp_array_value( $results, 1 ); ?>" autocomplete="off">
								</td>
								<?php
							} elseif ( sp_array_value( $cell, 'type', null ) === 'team' ) {
								$select = sp_array_value( $cell, 'select', false );
								$team = sp_array_value( $cell, 'id', 0 );
								echo '<td rowspan="' . sp_array_value( $cell, 'rows', 1 ) . '" class="sp-team' . ( $round === 0 ? ' sp-first-round' : '' ) . ( $round === $rounds - 1 ? ' sp-last-round' : '' ) . '">';
									if ( $select ) {
										self::dropdown( $teams, $index, $team );
									} else {
										echo '<input type="hidden" name="sp_event[' . $index . '][teams][]" value="' . $team . '">';
										echo '<input type="text" readonly="readonly" class="widefat sp-team-display" value="' . get_the_title( $team ) . '">';
									}
								echo '</td>';
							} else {
								echo '<td rowspan="' . sp_array_value( $cell, 'rows', 1 ) . '">&nbsp;</td>';
							}
						}
						?>
					</tr>
				<?php } ?>
			</tbody>
		</table>
		<?php
	}

	public static function dropdown( $teams = array(), $index = null, $selected = 0 ) {
		echo '<select class="postform sp-team-selector" name="sp_event[' . $index . '][teams][]">';
			echo '<option value="0">' . sprintf( __( 'Select %s', 'sportspress' ), __( 'Team', 'sportspress' ) ) . '</option>';
			foreach ( $teams as $team ):
				echo '<option value="' . $team->ID . '" ' . selected( $selected, $team->ID ) . '>' . $team->post_title . '</option>';
			endforeach;
		echo '</select>';
	}
}

new SP_Tournament_Meta_Boxes();