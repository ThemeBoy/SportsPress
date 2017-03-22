<?php
/**
 * SportsPress Admin Sample Data Class.
 *
 * The SportsPress admin sample data class stores demo content.
 *
 * @class 		SP_Admin_Sample_Data
 * @version		2.3
 * @package		SportsPress/Admin
 * @category	Class
 * @author 		ThemeBoy
 */
class SP_Admin_Sample_Data {

	/**
	 * Sample data
	 *
	 * Adds sample SportsPress data
	 *
	 * @access public
	 */
	public static function insert_posts() {

		// Initialize inserted ids array
		$inserted_ids = array(
			'sp_league' => array(),
			'sp_season' => array(),
			'sp_venue' => array(),
			'sp_position' => array(),
			'sp_role' => array(),
			'sp_event' => array(),
			'sp_calendar' => array(),
			'sp_team' => array(),
			'sp_table' => array(),
			'sp_player' => array(),
			'sp_list' => array(),
			'sp_staff' => array(),
		);

		// Terms to insert
		$taxonomies = array();

		// Competitions
		$taxonomies['sp_league'] = array( _x( 'Primary League', 'example', 'sportspress' ), _x( 'Secondary League', 'example', 'sportspress' ) );

		// Seasons
		$current_year = date( 'Y' );
		$current_year = intval( $current_year );
		$taxonomies['sp_season'] = array( $current_year - 1, $current_year, $current_year + 1 );

		// Venues
		$taxonomies['sp_venue'] = array( 
			array(
				'name' => 'Bentleigh',
				'meta' => array(
					'sp_address' => '12A Bolinda Street, Bentleigh VIC 3204, Australia',
					'sp_latitude' => -37.920537,
					'sp_longitude' => 145.043199,
				),
			),
			array(
				'name' => 'Essendon',
				'meta' => array(
					'sp_address' => '74 Napier Street, Essendon VIC 3040, Australia',
					'sp_latitude' => -37.751940,
					'sp_longitude' => 144.919549,
				),
			),
			array(
				'name' => 'Kensington',
				'meta' => array(
					'sp_address' => '50 Altona Street, Kensington, Victoria, Australia',
					'sp_latitude' => -37.797789,
					'sp_longitude' => 144.924709,
				),
			),
		);

		// Jobs
		$taxonomies['sp_role'] = array( 'Coach' );

		/*
		 * Insert terms
		 */
		foreach ( $taxonomies as $taxonomy => $terms ) {
			foreach ( $terms as $term ) {
				// Determine if term is array or name string
				if ( is_array( $term ) ) {
					$name = $term['name'];
				} else {
					$name = $term;
				}

				// Insert term
				$inserted = wp_insert_term( $name, $taxonomy, array( 'description' => $name, 'slug' => sanitize_title( $name ) ) );

				// Add meta to term if is array
				if ( ! is_wp_error( $inserted ) && is_array( $term ) && array_key_exists( 'meta', $term ) ) {
					$t_id = sp_array_value( $inserted, 'term_id', 1 );
					$meta = sp_array_value( $term, 'meta' );
					update_option( "taxonomy_$t_id", $meta );
					
					// Add to inserted ids array
					$inserted_ids[ $taxonomy ][] = $t_id;
				}
			}
		}

		// Create sample content
		$sample_content = _x( 'This is an example %1$s. As a new SportsPress user, you should go to <a href=\"%3$s\">your dashboard</a> to delete this %1$s and create new %2$s for your content. Have fun!', 'example', 'sportspress' );

		// Define teams
		$teams = array(
			array(
				'name' => 'Bluebirds',
				'url' => 'http://tboy.co/bluebirds',
			),
			array(
				'name' => 'Eagles',
				'url' => 'http://tboy.co/eagles',
			),
			array(
				'name' => 'Kangaroos',
				'url' => 'http://tboy.co/kangaroos',
			),
		);

		// Define players
		$players = array(
			'Mario Bellucci',
			'Aiden Leggatt',
			'Seth Clemens',
			'Mitchell Childe',
			'Daniel Benn',
			'Archie Stead',
			'Finn Rosetta',
			'Koby Brough',
			'Blake Bannan',
			'Hugo Stones',
			'Tristian Holme',
			'Mason Ewing',
		);

		// Define staff
		$staff = array(
			'Bobby Brown',
		);

		// Define event videos
		$event_videos = array(
			'https://www.youtube.com/watch?v=xNkf2LYckI0',
			'https://www.youtube.com/watch?v=sIrjQyuwteM',
			'https://www.youtube.com/watch?v=xSGxuTGVQYE',
		);

		// Get countries
		$countries = new SP_Countries();

		/*
		 * Insert teams
		 */
		foreach ( $teams as $index => $team ) {
			$post['post_title'] = $team['name'];
			$post['post_type'] = 'sp_team';
			$post['post_status'] = 'publish';
			$post['post_content'] = sprintf( $sample_content, __( 'Team', 'sportspress' ), __( 'Teams', 'sportspress' ), add_query_arg( 'post_type', 'sp_team', admin_url( 'edit.php' ) ) );

			// Terms
			$post['tax_input'] = array();
			$taxonomies = array( 'sp_league', 'sp_season' );
			foreach ( $taxonomies as $taxonomy ) {
				$post['tax_input'][ $taxonomy ] = get_terms( $taxonomy, array( 'hide_empty' => 0, 'fields' => 'ids' ) );
			};

			$post['tax_input']['sp_venue'] = get_terms( 'sp_venue', array( 'hide_empty' => 0, 'fields' => 'ids', 'orderby' => 'id', 'order' => 'ASC', 'number' => 1, 'offset' => $index ) );

			// Insert post
			$id = wp_insert_post( $post );

			// Add to inserted ids array
			$inserted_ids['sp_team'][] = $id;

			// Flag as sample
			update_post_meta( $id, '_sp_sample', 1 );

			// Update meta
			update_post_meta( $id, 'sp_url', $team['url'] );
		}

		// Get columns
		$columns = array( 'team' );
		$args = array(
			'post_type' => array( 'sp_performance', 'sp_statistic' ),
			'posts_per_page' => 2,
			'orderby' => 'menu_order',
			'order' => 'ASC',
		);
		$vars = get_posts( $args );
		foreach ( $vars as $var ) {
			$columns[] = $var->post_name;
		}

		/*
		 * Insert players
		 */
		foreach ( $players as $index => $name ) {
			$post['post_title'] = $name;
			$post['post_type'] = 'sp_player';
			$post['post_status'] = 'publish';
			$post['post_content'] = sprintf( $sample_content, __( 'Player', 'sportspress' ), __( 'Players', 'sportspress' ), add_query_arg( 'post_type', 'sp_player', admin_url( 'edit.php' ) ) );

			// Terms
			$post['tax_input'] = array();
			$taxonomies = array( 'sp_league', 'sp_season' );
			foreach ( $taxonomies as $taxonomy ) {
				$post['tax_input'][ $taxonomy ] = get_terms( $taxonomy, array( 'hide_empty' => 0, 'fields' => 'ids' ) );
			};
			$taxonomies = array( 'sp_position' );
			foreach ( $taxonomies as $taxonomy ) {
				$terms = get_terms( $taxonomy, array( 'hide_empty' => 0, 'fields' => 'ids', 'orderby' => 'slug', 'number' => 1, 'offset' => $index % 4 ) );
				if ( $terms && ! is_wp_error( $terms ) ) {
					$post['tax_input'][ $taxonomy ] = $terms;
				}
			};

			// Insert post
			$id = wp_insert_post( $post );

			// Add to inserted ids array
			$inserted_ids['sp_player'][] = $id;

			// Flag as sample
			update_post_meta( $id, '_sp_sample', 1 );

			// Calculate meta
			$nationality = array_rand( $countries->countries );
			$team_index = floor( $index / 4 );
			$past_team_index = ( $team_index + 1 ) % 3;
			$current_team = sp_array_value( $inserted_ids['sp_team'], $team_index, 0 );
			$past_team = sp_array_value( $inserted_ids['sp_team'], $past_team_index, 0 );
			$primary_league = reset( $post['tax_input']['sp_league'] );
			$season_teams = $season_stats = array();
			foreach ( $post['tax_input']['sp_season'] as $season_index => $season_id ) {
				$season_stats[ $season_id ] = array();
				if ( $season_index == 0 ) {
					$season_teams[ $season_id ] = $past_team;
				} else {
					$season_teams[ $season_id ] = $current_team;
					if ( $season_index == 1 ) {
						foreach ( $vars as $var ) {
							$season_stats[ $season_id ][ $var->post_name ] = rand( 1, 10 );
						}
					}
				}
			}
			$player_stats = array( $primary_league => $season_stats );
			$player_leagues = array( $primary_league => $season_teams );

			// Update meta
			update_post_meta( $id, 'sp_columns', $columns );
			update_post_meta( $id, 'sp_number', $index + 1 );
			update_post_meta( $id, 'sp_nationality', $nationality );
			update_post_meta( $id, 'sp_current_team', $current_team );
			update_post_meta( $id, 'sp_past_team', $past_team );
			update_post_meta( $id, 'sp_leagues', $player_leagues );
			update_post_meta( $id, 'sp_statistics', $player_stats );
			sp_update_post_meta_recursive( $id, 'sp_team', array( $current_team, $past_team ) );
		}

		// Get columns
		$columns = array();
		$args = array(
			'post_type' => 'sp_performance',
			'posts_per_page' => -1,
			'orderby' => 'menu_order',
			'order' => 'ASC',
		);
		$performance_posts = get_posts( $args );
		foreach ( $performance_posts as $performance_post ) {
			if ( sizeof( $columns ) >= 5 ) continue;
			$columns[] = $performance_post->post_name;
		}
		$args = array(
			'post_type' => 'sp_result',
			'posts_per_page' => -1,
			'orderby' => 'menu_order',
			'order' => 'ASC',
		);
		$result_posts = get_posts( $args );
		$args = array(
			'post_type' => 'sp_outcome',
			'posts_per_page' => -1,
			'orderby' => 'menu_order',
			'order' => 'ASC',
		);
		$outcome_posts = get_posts( $args );

		/*
		 * Insert staff
		 */
		foreach ( $staff as $index => $name ) {
			$post['post_title'] = $name;
			$post['post_type'] = 'sp_staff';
			$post['post_status'] = 'publish';
			$post['post_content'] = sprintf( $sample_content, __( 'Staff', 'sportspress' ), __( 'Staff', 'sportspress' ), add_query_arg( 'post_type', 'sp_staff', admin_url( 'edit.php' ) ) );

			// Terms
			$post['tax_input'] = array();
			$taxonomies = array( 'sp_league', 'sp_season' );
			foreach ( $taxonomies as $taxonomy ) {
				$post['tax_input'][ $taxonomy ] = get_terms( $taxonomy, array( 'hide_empty' => 0, 'fields' => 'ids' ) );
			};
			$taxonomies = array( 'sp_role' );
			foreach ( $taxonomies as $taxonomy ) {
				$terms = get_terms( $taxonomy, array( 'hide_empty' => 0, 'fields' => 'ids', 'orderby' => 'slug', 'number' => 1, 'offset' => $index % 4 ) );
				$post['tax_input'][ $taxonomy ] = $terms;
			};

			// Insert post
			$id = wp_insert_post( $post );

			// Add to inserted ids array
			$inserted_ids['sp_staff'][] = $id;

			// Flag as sample
			update_post_meta( $id, '_sp_sample', 1 );

			// Calculate meta
			$team_index = floor( $index / 4 );
			$past_teams = $inserted_ids['sp_team'];
			$current_team = sp_array_value( $past_teams, $team_index, 0 );
			unset( $past_teams[ $team_index ] );

			// Update meta
			update_post_meta( $id, 'sp_columns', $columns );
			update_post_meta( $id, 'sp_number', $index + 1 );
			update_post_meta( $id, 'sp_nationality', 'aus' );
			update_post_meta( $id, 'sp_current_team', $current_team );
			sp_update_post_meta_recursive( $id, 'sp_past_team', $past_teams );
			sp_update_post_meta_recursive( $id, 'sp_team', $inserted_ids['sp_team'] );
		}

		/*
		 * Insert events
		 */
		for ( $index = 0; $index < 6; $index ++ ) {
			// Determine team index and post status
			$i = $index % 3;
			if ( $index < 3 ) {
				$post_status = 'publish';
				$post_year = $current_year - 1;
				$event_season = get_terms( 'sp_season', array( 'hide_empty' => 0, 'fields' => 'ids', 'orderby' => 'id', 'order' => 'ASC', 'number' => 1 ) );
			} else {
				$post_status = 'future';
				$post_year = $current_year + 1;
				$event_season = get_terms( 'sp_season', array( 'hide_empty' => 0, 'fields' => 'ids', 'orderby' => 'id', 'order' => 'DESC', 'number' => 1 ) );
			}
			// The away team should be the next inserted team, or the first if this is the last event
			if ( $i == 2 ) $away_index = 0;
			else $away_index = $i + 1;
			$post = array(
				'post_title' => $teams[ $i ]['name'] . ' ' . get_option( 'sportspress_event_teams_delimiter', 'vs' ) . ' ' . $teams[ $away_index ]['name'],
				'post_type' => 'sp_event',
				'post_status' => $post_status,
				'post_content' => sprintf( $sample_content, __( 'Event', 'sportspress' ), __( 'Events', 'sportspress' ), add_query_arg( 'post_type', 'sp_event', admin_url( 'edit.php' ) ) ),
				'post_date' => $post_year . '-' . sprintf( '%02d', 3 + $i * 3 ) . '-' . sprintf( '%02d', 5 + $i * 10 ) . ' ' . ( 18 + $i ) . ':00:00',
				'tax_input' => array(
					'sp_league' => get_terms( 'sp_league', array( 'hide_empty' => 0, 'fields' => 'ids', 'orderby' => 'id', 'order' => 'ASC', 'number' => 1 ) ),
					'sp_season' => $event_season,
					'sp_venue' => get_terms( 'sp_venue', array( 'hide_empty' => 0, 'fields' => 'ids', 'orderby' => 'id', 'order' => 'ASC', 'number' => 1, 'offset' => $i ) ),
				),
			);

			// Insert post
			$id = wp_insert_post( $post );

			// Add to inserted ids array
			$inserted_ids['sp_event'][] = $id;

			// Flag as sample
			update_post_meta( $id, '_sp_sample', 1 );

			// Calculate home and away team ids
			$home_team_index = ( $i ) % 3;
			$away_team_index = ( $i + 1 ) % 3;
			$home_team_id = sp_array_value( $inserted_ids['sp_team'], $home_team_index, 0 );
			$away_team_id = sp_array_value( $inserted_ids['sp_team'], $away_team_index, 0 );
			$event_teams = array(
				$home_team_id,
				$away_team_id,
			);

			// Initialize meta
			$event_players = array( 0 );
			$performance = $results = array();

			if ( $home_team_id ) {
				// Add home team player performance
				$performance[ $home_team_id ] = array();
				for ( $j = 0; $j < 4; $j ++ ) {
					$player_id = sp_array_value( $inserted_ids['sp_player'], $home_team_index * 4 + $j );
					$event_players[] = $player_id;
					$player_performance = array();
					foreach ( $performance_posts as $performance_post ) {
						$player_performance[ $performance_post->post_name ] = rand( 0, 1 );
					}
					$performance[ $home_team_id ][ $player_id ] = $player_performance;
				}

				// Add home team results
				$results[ $home_team_id ] = array();
				foreach ( $result_posts as $result_post_index => $result_post ) {
					$results[ $home_team_id ][ $result_post->post_name ] = 1 + $result_post_index;
				}
				$outcome = reset( $outcome_posts );
				if ( is_object( $outcome ) ) $results[ $home_team_id ]['outcome'] = array( $outcome->post_name );
			}

			// Separate teams with zero
			$event_players[] = 0;

			if ( $away_team_id ) {
				$performance[ $away_team_id ] = array();
				for ( $j = 0; $j < 4; $j ++ ) {
					$player_id = sp_array_value( $inserted_ids['sp_player'], $away_team_index * 4 + $j );
					$event_players[] = $player_id;
					$player_performance = array();
					foreach ( $performance_posts as $performance_post ) {
						$player_performance[ $performance_post->post_name ] = rand( 0, 1 );
					}
					$performance[ $away_team_id ][ $player_id ] = $player_performance;
				}

				// Add away team results
				$results[ $away_team_id ] = array();
				foreach ( $result_posts as $result_post ) {
					$results[ $away_team_id ][ $result_post->post_name ] = '0';
				}
				$outcome = next( $outcome_posts );
				if ( is_object( $outcome ) ) $results[ $away_team_id ]['outcome'] = array( $outcome->post_name );
			}

			if ( 'publish' === $post_status ) {
				// Swap results for last event only
				if ( $i == 2 ) {
					$k = array_keys( $results );
					$v = array_values( $results );
					$rv = array_reverse( $v );
					$results = array_combine( $k, $rv );
				}

				// Update future post meta
				update_post_meta( $id, 'sp_players', $performance );
				update_post_meta( $id, 'sp_results', $results );
			}

			// Update general meta
			sp_update_post_meta_recursive( $id, 'sp_team', $event_teams );
			sp_update_post_meta_recursive( $id, 'sp_player', $event_players );
			update_post_meta( $id, 'sp_columns', $columns );
			update_post_meta( $id, 'sp_format', 'league' );
			update_post_meta( $id, 'sp_video', $event_videos[ $i ] );
		}

		/*
		 * Insert calendar
		 */
		$post = array(
			'post_title' => _x( 'Fixtures & Results', 'example', 'sportspress' ),
			'post_type' => 'sp_calendar',
			'post_status' => 'publish',
			'post_content' => sprintf( $sample_content, __( 'Calendar', 'sportspress' ), __( 'Calendars', 'sportspress' ), add_query_arg( 'post_type', 'sp_calendar', admin_url( 'edit.php' ) ) )
		);

		// Insert post
		$id = wp_insert_post( $post );

		// Add to inserted ids array
		$inserted_ids['sp_calendar'][] = $id;

		// Flag as sample
		update_post_meta( $id, '_sp_sample', 1 );

		// Define columns
		$columns = array( 'event', 'time', 'article' );

		// Update meta
		update_post_meta( $id, 'sp_format', 'list' );
		update_post_meta( $id, 'sp_status', 'any' );
		update_post_meta( $id, 'sp_date', 0 );
		update_post_meta( $id, 'sp_team', 0 );
		update_post_meta( $id, 'sp_order', 'ASC' );
		update_post_meta( $id, 'sp_columns', $columns );

		/*
		 * Insert league table
		 */
		$leagues = get_terms( 'sp_league', array( 'hide_empty' => 0, 'orderby' => 'id', 'order' => 'ASC', 'number' => 1 ) );
		$league = reset( $leagues );
		$seasons = get_terms( 'sp_season', array( 'hide_empty' => 0, 'orderby' => 'id', 'order' => 'ASC', 'number' => 1 ) );
		$season = reset( $seasons );
		$post = array(
			'post_title' => $league->name . ' ' . $season->name,
			'post_type' => 'sp_table',
			'post_status' => 'publish',
			'post_content' => sprintf( $sample_content, __( 'League Table', 'sportspress' ), __( 'League Tables', 'sportspress' ), add_query_arg( 'post_type', 'sp_table', admin_url( 'edit.php' ) ) ),
			'tax_input' => array(
				'sp_league' => $league->term_id,
				'sp_season' => $season->term_id,
			),
		);

		// Insert post
		$id = wp_insert_post( $post );

		// Add to inserted ids array
		$inserted_ids['sp_table'][] = $id;

		// Flag as sample
		update_post_meta( $id, '_sp_sample', 1 );

		// Get columns
		$columns = array();
		$args = array(
			'post_type' => 'sp_column',
			'posts_per_page' => 8,
			'orderby' => 'menu_order',
			'order' => 'ASC',
		);
		$column_posts = get_posts( $args );
		foreach ( $column_posts as $column_post ) {
			$columns[] = $column_post->post_name;
		}

		// Update meta
		sp_update_post_meta_recursive( $id, 'sp_team', $inserted_ids['sp_team'] );
		update_post_meta( $id, 'sp_columns', $columns );
		update_post_meta( $id, 'sp_highlight', reset( $inserted_ids['sp_team'] ) );

		/*
		 * Insert player list for each team
		 */
		foreach ( $inserted_ids['sp_team'] as $index => $team_id ) {
			$post = array(
				'post_title' => get_the_title( $team_id ) . ' ' . _x( 'Roster', 'example', 'sportspress' ),
				'post_type' => 'sp_list',
				'post_status' => 'publish',
				'post_content' => sprintf( $sample_content, __( 'Player List', 'sportspress' ), __( 'Player Lists', 'sportspress' ), add_query_arg( 'post_type', 'sp_list', admin_url( 'edit.php' ) ) ),
			);

			// Insert post
			$id = wp_insert_post( $post );

			// Add to inserted ids array
			$inserted_ids['sp_list'][] = $id;

			// Flag as sample
			update_post_meta( $id, '_sp_sample', 1 );

			// Get players from team
			$list_players = array_slice( $inserted_ids['sp_player'], $index * 4, 4 );

			// Get columns
			$columns = array();
			$args = array(
				'post_type' => array( 'sp_metric' ),
				'posts_per_page' => 2,
				'orderby' => 'menu_order',
				'order' => 'ASC',
			);
			$column_posts = get_posts( $args );
			foreach ( $column_posts as $column_post ) {
				$columns[] = $column_post->post_name;
			}

			// Update meta
			update_post_meta( $id, 'sp_format', 'list' );
			sp_update_post_meta_recursive( $id, 'sp_player', $list_players );
			update_post_meta( $id, 'sp_columns', $columns );
			update_post_meta( $id, 'sp_grouping', 'position' );
			update_post_meta( $id, 'sp_orderby', 'name' );
			update_post_meta( $id, 'sp_order', 'ASC' );
			update_post_meta( $id, 'sp_team', $team_id );
		}

		/*
		 * Insert player list for player ranking
		 */
		$post = array(
			'post_title' => _x( 'Player Ranking', 'example', 'sportspress' ),
			'post_type' => 'sp_list',
			'post_status' => 'publish',
			'post_content' => sprintf( $sample_content, __( 'Player List', 'sportspress' ), __( 'Player Lists', 'sportspress' ), add_query_arg( 'post_type', 'sp_list', admin_url( 'edit.php' ) ) ),
		);

		// Insert post
		$id = wp_insert_post( $post );

		// Add to inserted ids array
		$inserted_ids['sp_list'][] = $id;

		// Flag as sample
		update_post_meta( $id, '_sp_sample', 1 );

		// Get columns
		$columns = array( 'team' );
		$performance_post = reset( $performance_posts );
		if ( is_object( $performance_post ) ) $columns[] = $performance_post->post_name;

		// Update meta
		update_post_meta( $id, 'sp_format', 'list' );
		sp_update_post_meta_recursive( $id, 'sp_player', $inserted_ids['sp_player'] );
		update_post_meta( $id, 'sp_columns', $columns );
		update_post_meta( $id, 'sp_grouping', '0' );
		update_post_meta( $id, 'sp_order', 'DESC' );
		if ( is_object( $performance_post ) ) update_post_meta( $id, 'sp_orderby', $performance_post->post_name );

		/*
		 * Update player list and league table per team
		 */
		foreach ( $inserted_ids['sp_team'] as $index => $team_id ) {
			update_post_meta( $team_id, 'sp_list', sp_array_value( $inserted_ids['sp_list'], $index, 0 ) );
			update_post_meta( $team_id, 'sp_table', sp_array_value( $inserted_ids['sp_table'], 0 ) );
		}
	}

	/**
	 * Deletes sample SportsPress data
	 *
	 * @access public
	 */
	public static function delete_posts() {
		$post_types = sp_post_types();
		$args = array(
			'post_type' => $post_types,
			'posts_per_page' => -1,
			'post_status' => array( 'publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit', 'trash' ),
			'meta_query' => array(
				array(
					'key' => '_sp_sample',
					'value' => 1
				)
			),
		);

		// Delete posts
		$old_posts = get_posts( $args );
		foreach( $old_posts as $post ):
			wp_delete_post( $post->ID, true );
		endforeach;
	}
}
