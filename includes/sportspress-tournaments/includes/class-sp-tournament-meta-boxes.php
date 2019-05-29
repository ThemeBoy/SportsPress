<?php
/**
 * Tournament Meta Boxes
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress_Tournaments
 * @version   2.6.15
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
		add_meta_box( 'sp_modediv', __( 'Mode', 'sportspress' ), array( $this, 'mode' ), 'sp_tournament', 'side', 'default' );
		add_meta_box( 'sp_detailsdiv', __( 'Details', 'sportspress' ), array( $this, 'details' ), 'sp_tournament', 'side', 'default' );
		add_meta_box( 'sp_datadiv', __( 'Bracket', 'sportspress' ), array( $this, 'data' ), 'sp_tournament', 'normal', 'high' );
		add_meta_box( 'sp_winnersdiv', __( 'Winner Bracket', 'sportspress' ), array( $this, 'winners' ), 'sp_tournament', 'normal', 'high' );
		add_meta_box( 'sp_losersdiv', __( 'Loser Bracket', 'sportspress' ), array( $this, 'losers' ), 'sp_tournament', 'normal', 'high' );
		add_meta_box( 'sp_finalsdiv', __( 'Final Bracket', 'sportspress' ), array( $this, 'finals' ), 'sp_tournament', 'normal', 'high' );
		add_meta_box( 'sp_tablesdiv', __( 'Groups', 'sportspress' ), array( $this, 'tables' ), 'sp_tournament', 'normal', 'high' );
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
		$type = get_post_meta( $post->ID, 'sp_type', true );
		if ( $type === '' ) $type = 'single';
	}

	/**
	 * Output the mode metabox
	 */
	public static function mode( $post ) {
    $the_mode = sp_get_post_mode( $post->ID );
    ?>
    <div id="post-formats-select">
      <?php foreach ( array( 'team' => __( 'Team vs team', 'sportspress' ), 'player' => __( 'Player vs player', 'sportspress' ) ) as $key => $mode ): ?>
        <input type="radio" name="sp_mode" class="post-format" id="post-format-<?php echo $key; ?>" value="<?php echo $key; ?>" <?php checked( $the_mode, $key ); ?>> <label for="post-format-<?php echo $key; ?>" class="post-format-icon post-format-<?php echo $key; ?>"><?php echo $mode; ?></label><br>
      <?php endforeach; ?>
    </div>
    <?php
	}

	/**
	 * Output the details metabox
	 */
	public static function details( $post ) {
		wp_nonce_field( 'sportspress_save_data', 'sportspress_meta_nonce' );
		$caption = get_post_meta( $post->ID, 'sp_caption', true );
		$limit = apply_filters( 'sp_tournament_rounds_limit', 6 );
		$taxonomies = get_object_taxonomies( 'sp_tournament' );
		$post_type = sp_get_post_mode_type( $post->ID );
		$rounds = get_post_meta( $post->ID, 'sp_rounds', true );
		if ( $rounds === '' ) $rounds = 3;
		$type = get_post_meta( $post->ID, 'sp_type', true );
		if ( $type === '' ) $type = 'single';
		$winner = get_post_meta( $post->ID, 'sp_winner', true );
		?>
		<div>
			<p><strong><?php _e( 'Heading', 'sportspress' ); ?></strong></p>
			<p><input type="text" id="sp_caption" name="sp_caption" value="<?php echo esc_attr( $caption ); ?>"></p>
			<?php
			foreach ( $taxonomies as $taxonomy ) {
				sp_taxonomy_field( $taxonomy, $post, true );
			}
			?>
			<p><strong><?php _e( 'Format', 'sportspress' ); ?></strong></p>
			<p>
				<select name="sp_type" id="sp_type" class="postform">
					<option value="single" <?php selected( 'single', $type ); ?>><?php _e( 'Single Elimination', 'sportspress' ); ?></option>
					<option value="double" <?php selected( 'double', $type ); ?>><?php _e( 'Double Elimination', 'sportspress' ); ?></option>
				</select>
			</p>
			<p><strong><?php _e( 'Teams', 'sportspress' ); ?></strong></p>
			<p>
				<select name="sp_rounds" id="sp_rounds" class="postform">
					<?php for ( $i = 2; $i <= $limit; $i++ ) {?>
					<option value="<?php echo $i; ?>" <?php selected( $rounds, $i ); ?>><?php echo pow( 2, $i - 1 ) + 1; ?>&ndash;<?php echo pow( 2, $i ); ?> <?php _e( 'teams', 'sportspress' ); ?></option>
					<?php } ?>
				</select>
			</p>
			<p><strong><?php _e( 'Winner', 'sportspress' ); ?></strong></p>
			<p>
				<?php
				$args = array(
					'show_option_none' => __( '&mdash; Not set &mdash;', 'sportspress' ),
					'post_type' => $post_type,
					'name' => 'sp_winner',
					'selected' => $winner,
					'values' => 'ID'
				);
				sp_dropdown_pages( $args );
				?>
			</p>
		</div>
		<?php
		// Remove extra meta boxes
		switch ( $type ) {
			case 'double':
				remove_meta_box( 'sp_datadiv', 'sp_tournament', 'normal' );
				break;
			default:
				remove_meta_box( 'sp_winnersdiv', 'sp_tournament', 'normal' );
				remove_meta_box( 'sp_losersdiv', 'sp_tournament', 'normal' );
				remove_meta_box( 'sp_finalsdiv', 'sp_tournament', 'normal' );
		}
	}

	/**
	 * Output the data metabox
	 */
	public static function data( $post ) {
		$type = get_post_meta( $post->ID, 'sp_type', true );
		if ( '' === $type ) $type = 'single';
		if ( 'single' !== $type ) return;
		
		$tournament = new SP_Tournament( $post );
		list( $labels, $data, $rounds, $rows ) = $tournament->data( 'bracket', true );
		self::table( $labels, $data, $rounds, $rows, $post->ID );
	}

	/**
	 * Output the winners metabox
	 */
	public static function winners( $post ) {
		$type = get_post_meta( $post->ID, 'sp_type', true );
		if ( 'double' !== $type ) return;

		$tournament = new SP_Tournament( $post );
		list( $labels, $data, $rounds, $rows ) = $tournament->data( 'bracket', true, 'winners' );
		self::table( $labels, $data, $rounds, $rows, $post->ID );
	}

	/**
	 * Output the losers metabox
	 */
	public static function losers( $post ) {
		$type = get_post_meta( $post->ID, 'sp_type', true );
		if ( 'double' !== $type ) return;

		$tournament = new SP_Tournament( $post );
		list( $labels, $data, $rounds, $rows ) = $tournament->data( 'bracket', true, 'losers' );
		self::table( $labels, $data, $rounds, $rows, $post->ID, 'losers' );
	}

	/**
	 * Output the finals metabox
	 */
	public static function finals( $post ) {
		$type = get_post_meta( $post->ID, 'sp_type', true );
		if ( 'double' !== $type ) return;

		$tournament = new SP_Tournament( $post );
		list( $labels, $data, $rounds, $rows ) = $tournament->data( 'bracket', true, 'finals' );
		self::table( $labels, $data, $rounds, $rows, $post->ID, 'finals' );
	}

	/**
	 * Output the tables metabox
	 */
	public static function tables( $post ) {
		$tables = get_posts( array(
			'post_type' => 'sp_table',
			'posts_per_page' => -1,
			'orderby' => 'menu_order',
			'order' => 'ASC',
			'meta_query' => array(
				array(
					'key' => 'sp_tournament',
					'value' => $post->ID,
				),
			),
		) );

		if ( ! empty( $tables ) ) {
			$table_ids = wp_list_pluck( $tables, 'ID' );
			$meta_box = new SP_Meta_Box_Table_Data();
			foreach ( $table_ids as $table_id ) {
				$table = new SP_League_Table( $table_id );
				list( $columns, $usecolumns, $data, $placeholders, $merged ) = $table->data( true );
				$meta_box->table( $table->ID, $columns, $usecolumns, $data, $placeholders, array(), array(), true );
			}
		}

		sp_post_adder( 'sp_table', __( 'Add New', 'sportspress' ), array( 'sp_tournament' => $post->ID ) );
	}

	/**
	 * Save meta boxes data
	 */
	public static function save( $post_id ) {
		global $wpdb;

		// Format
		update_post_meta( $post_id, 'sp_format', sp_array_value( $_POST, 'sp_format', 'bracket' ) );

		// Mode
		update_post_meta( $post_id, 'sp_mode', sp_array_value( $_POST, 'sp_mode', 'team' ) );

		// Heading
		update_post_meta( $post_id, 'sp_caption', sp_array_value( $_POST, 'sp_caption', '' ) );
		
		// Get type
		$type = sp_array_value( $_POST, 'sp_type', 'single' );

		// Rounds
		$limit = apply_filters( 'sp_tournament_rounds_limit', 6 );
		$rounds = sp_array_value( $_POST, 'sp_rounds', 1 );

		if ( $rounds < 1 ) $rounds = 1;
		elseif ( $rounds > $limit ) $rounds = $limit;

		update_post_meta( $post_id, 'sp_rounds', $rounds );

		// Type
		update_post_meta( $post_id, 'sp_type', $type );

		// Winner
		update_post_meta( $post_id, 'sp_winner', sp_array_value( $_POST, 'sp_winner' ) );

		// Labels
		update_post_meta( $post_id, 'sp_labels', sp_array_value( $_POST, 'sp_labels', array() ) );

		if ( 'double' === $type ) {
			update_post_meta( $post_id, 'sp_loser_labels', sp_array_value( $_POST, 'sp_loser_labels', array() ) );
			update_post_meta( $post_id, 'sp_final_labels', sp_array_value( $_POST, 'sp_final_labels', array() ) );
		}

		// Get main result option
		$main_result = sp_get_main_result_option();

		// Get terms
		$tax_input = sp_array_value( $_POST, 'tax_input', array() );
		$leagues = array_map( 'intval', sp_array_value( $tax_input, 'sp_league', array() ) );
		$seasons = array_map( 'intval', sp_array_value( $tax_input, 'sp_season', array() ) );
		
		// Reverse teams option
		$reverse_teams = get_option( 'sportspress_event_reverse_teams', 'no' ) === 'yes' ? true : false;

		// Events
		$keys = array( 'sp_event' );
		if ( 'double' === $type ) {
			$keys[] = 'sp_loser';
			$keys[] = 'sp_final';
		}
		foreach ( $keys as $key ) {
			$events = sp_array_value( $_POST, $key, array() );
			ksort( $events );
			$event_ids = array();
			foreach ( $events as $i => $event ) {
				// Get details
				$id = sp_array_value( $event, 'id', 0 );
				$teams = sp_array_value( $event, 'teams', array() );
				$results = sp_array_value( $event, 'results', array() );
				$date = sp_array_value( $event, 'date', '' );
				$h = sp_array_value( $event, 'hh', '' );
				$m = sp_array_value( $event, 'mm', '00' );

				// Reverse teams if needed
				if ( $reverse_teams ) {
					$teams = array_reverse( $teams );
					$events[ $i ][ 'teams' ] = $teams;
				}

				// Update or add new event
				if ( strlen( $date ) ) {
					// Add time to date if given
					if ( strlen( $h ) ):
						$h = substr( str_pad( $h, 2, '0', STR_PAD_LEFT ), 0, 2 );
						$m = substr( str_pad( $m, 2, '0', STR_PAD_LEFT ), 0, 2 );
						$time = $h . ':' . $m;
						$date .= ' ' . trim( $time );
					endif;
				}

				// Generate title
				$team_names = array();
				foreach ( $teams as $team ) {
					if ( ! $team ) continue;
					$team_names[] = get_the_title( $team );
				}
				$new_title = implode( ' ' . get_option( 'sportspress_event_teams_delimiter', 'vs' ) . ' ', $team_names );

				if ( ! strlen( $date ) && ! strlen( $new_title ) ) continue;

				// Update or add new event
				if ( $id ) {
					$title = get_the_title( $id );
					if ( ! strlen( $title ) ) {
						$wpdb->update( $wpdb->posts, array( 'post_date' => $date, 'post_title' => $new_title ), array( 'ID' => $id ) );
					} else {
						$wpdb->update( $wpdb->posts, array( 'post_date' => $date ), array( 'ID' => $id ) );
					}

				} else {
					$args = array( 'post_type' => 'sp_event', 'post_title' => ( $new_title ? $new_title : __( 'Event', 'sportspress' ) ), 'post_status' => 'publish' );
					if ( strlen( $date ) ) $args['post_date'] = $date;

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
				if ( sizeof( $results ) ) {
					if ( sizeof( $teams ) ) {
						$results = array_combine( $teams, $results );
						if ( $results ) {
							if ( $reverse_teams ) {
								$results = array_combine( array_keys( $results ), array_reverse( array_values( $results ) ) );
							}
							sp_update_main_results( $id, $results );
						}
						update_post_meta( $id, 'sp_status', 'ok' );
					}
				} elseif ( ! strlen( $date ) ) {
					update_post_meta( $id, 'sp_status', 'tbd' );
				}

				// Update event format
				update_post_meta( $id, 'sp_format', 'tournament' );

				// Add to event IDs
				$event_ids[] = $id;
			}
			update_post_meta( $post_id, $key . 's', $events );
			sp_update_post_meta_recursive( $post_id, $key, $event_ids );
		}
	}

	/**
	 * Admin edit table
	 */
	public static function table( $labels = array(), $data = null, $rounds = 3, $rows = 23, $post_id = null, $type = 'single' ) {
		$post_type = sp_get_post_mode_type( $post_id );

		// Reverse teams option
		$reverse_teams = get_option( 'sportspress_event_reverse_teams', 'no' ) === 'yes' ? true : false;

		$args = array(
			'post_type' => $post_type,
			'posts_per_page' => -1,
			'orderby' => 'title',
			'order' => 'ASC',
			'tax_query' => array(),
		);
		
		// Get post meta key for raw data based on type
		switch ( $type ) {
			case 'losers':
				$key = 'sp_loser';
				$label_key = 'sp_loser_labels';
				break;
			case 'finals':
				$key = 'sp_final';
				$label_key = 'sp_final_labels';
				break;
			default:
				$key = 'sp_event';
				$label_key = 'sp_labels';
		}
		
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
				'field' => 'term_id',
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
				'field' => 'term_id',
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
							<input type="text" class="widefat" name="<?php echo $label_key; ?>[]" value="<?php echo esc_attr( sp_array_value( $labels, $round, '' ) ); ?>" placeholder="<?php printf( esc_attr__( 'Round %s', 'sportspress' ), $round + 1 ); ?>">
						</th>
					<?php endfor; ?>
				</tr>
			</thead>
			<tbody>
				<?php $last_forced = 0; ?>
				<?php for ( $row = 0; $row < $rows; $row++ ) { ?>
					<tr>
						<?php
						for ( $round = 0; $round < $rounds; $round++ ) {
							$cell = sp_array_value( sp_array_value( $data, $row, array() ), $round, null );
							if ( $cell === null ) continue;

							$index = sp_array_value( $cell, 'index' );
							$hidden = sp_array_value( $cell, 'hidden', 0 );
							$forced = sp_array_value( $cell, 'forced', 0 );

							if ( sp_array_value( $cell, 'type', null ) === 'event' ) {
								$event = sp_array_value( $cell, 'id', 0 );
								if ( $event ) {
									$results = sp_get_main_results( $event );
								} else {
									$results = null;
								}
								?>
								<td rowspan="<?php echo sp_array_value( $cell, 'rows', 1 ); ?>" class="sp-event<?php if ( 0 === $round ) { ?> sp-first-round<?php } if ( $rounds - 1 === $round || $forced ) { ?> sp-last-round<?php } ?><?php if ( $hidden ) { ?> sp-event-hidden<?php } ?>" data-event="<?php echo $index; ?>">
									<?php if ( $hidden && $forced ) { ?><div class="hidden"><?php } ?>
									<input type="hidden" name="<?php echo $key; ?>[<?php echo $index; ?>][id]" value="<?php echo $event ? $event : 0; ?>">
									<label><?php _e( 'Date', 'sportspress' ); ?>:</label>
									<?php if ( $event ) { ?>
										<a title="<?php _e( 'Edit Event', 'sportspress' ); ?>" class="sp-edit sp-desc-tip dashicons dashicons-edit" href="<?php echo get_edit_post_link( $event, '' ); ?>" target="_blank"></a>
									<?php } ?>
									<a title="<?php _e( 'Hide Event' ); ?>" class="sp-hide sp-desc-tip dashicons dashicons-hidden" href="#"></a>
									<input type="hidden" class="sp-hidden" name="<?php echo $key; ?>[<?php echo $index; ?>][hidden]" value="<?php echo $hidden ? 1 : 0; ?>">
									<?php if ( $event && 'tbd' == get_post_meta( $event, 'sp_status', true ) ) { ?>
										<?php _e( 'TBD', 'sportspress' ); ?>
										<input type="hidden" name="<?php echo $key; ?>[<?php echo $index; ?>][date]" value="<?php if ( $event ) echo sp_get_time( $event, 'Y-m-d' ); ?>"><hr>
										<input type="hidden" name="<?php echo $key; ?>[<?php echo $index; ?>][hh]" value="<?php if ( $event ) echo sp_get_time( $event, 'H' ); ?>">
										<input type="hidden" name="<?php echo $key; ?>[<?php echo $index; ?>][mm]" value="<?php if ( $event ) echo sp_get_time( $event, 'i' ); ?>">
									<?php } else { ?>
										<input type="text" class="sp-datepicker" name="<?php echo $key; ?>[<?php echo $index; ?>][date]" value="<?php if ( $event ) echo sp_get_time( $event, 'Y-m-d' ); ?>" size="10" autocomplete="off"><hr>
										<label><?php _e( 'Time', 'sportspress' ); ?>:</label>
										<input type="text" size="2" maxlength="2" name="<?php echo $key; ?>[<?php echo $index; ?>][hh]" autocomplete="off" value="<?php if ( $event ) echo sp_get_time( $event, 'H' ); ?>">
										:
										<input type="text" size="2" maxlength="2" name="<?php echo $key; ?>[<?php echo $index; ?>][mm]" autocomplete="off" value="<?php if ( $event ) echo sp_get_time( $event, 'i' ); ?>"><hr>
									<?php } ?>
									<label><?php _e( 'Results', 'sportspress' ); ?>:</label>
									<?php if ( $reverse_teams ) { ?>
										<input type="text" size="2" name="<?php echo $key; ?>[<?php echo $index; ?>][results][]" value="<?php echo sp_array_value( $results, 1 ); ?>" autocomplete="off">
										-
										<input type="text" size="2" name="<?php echo $key; ?>[<?php echo $index; ?>][results][]" value="<?php echo sp_array_value( $results, 0 ); ?>" autocomplete="off">
									<?php } else { ?>
										<input type="text" size="2" name="<?php echo $key; ?>[<?php echo $index; ?>][results][]" value="<?php echo sp_array_value( $results, 0 ); ?>" autocomplete="off">
										-
										<input type="text" size="2" name="<?php echo $key; ?>[<?php echo $index; ?>][results][]" value="<?php echo sp_array_value( $results, 1 ); ?>" autocomplete="off">
									<?php } ?>
									<?php if ( $hidden && $forced ) { ?></div><?php } ?>
								</td>
								<?php
							} elseif ( sp_array_value( $cell, 'type', null ) === 'team' ) {
								$select = sp_array_value( $cell, 'select', false );
								$team = sp_array_value( $cell, 'id', 0 );
								echo '<td rowspan="' . sp_array_value( $cell, 'rows', 1 ) . '" class="sp-team' . ( $round === 0 || $last_forced ? ' sp-first-round' : '' ) . ( $round === $rounds - 1 ? ' sp-last-round' : '' ) . ( $hidden ? ' sp-team-hidden' : '' ) . '" data-event="' . $index . '">';
									if ( $hidden && $forced ) {
										echo '<div class="hidden">';
									}
									if ( $select ) {
										self::dropdown( $teams, $index, $team, $key );
									} else {
										echo '<input type="hidden" name="' . $key . '[' . $index . '][teams][]" value="' . $team . '">';
										echo '<input type="text" readonly="readonly" class="widefat sp-team-display" value="' . get_the_title( $team ) . '">';
									}
									if ( $hidden && $forced ) {
										echo '</div>';
									}
								echo '</td>';
							} else {
								echo '<td rowspan="' . sp_array_value( $cell, 'rows', 1 ) . '">&nbsp;</td>';
							}
						}
						?>
					</tr>
				<?php $last_forced = $forced; ?>
				<?php } ?>
			</tbody>
		</table>
		<?php
	}

	public static function dropdown( $teams = array(), $index = null, $selected = 0, $key = 'sp_event' ) {
		echo '<select class="postform sp-team-selector" name="' . $key . '[' . $index . '][teams][]">';
			echo '<option value="0">' . sprintf( __( 'Select %s', 'sportspress' ), __( 'Team', 'sportspress' ) ) . '</option>';
			foreach ( $teams as $team ):
				echo '<option value="' . $team->ID . '" ' . selected( $selected, $team->ID ) . '>' . $team->post_title . '</option>';
			endforeach;
		echo '</select>';
	}
}

new SP_Tournament_Meta_Boxes();