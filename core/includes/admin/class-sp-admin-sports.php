<?php
/**
 * SportsPress Admin Sports Class.
 *
 * The SportsPress admin sports class stores preset sport data.
 *
 * @class 		SP_Admin_Sports
 * @version		1.3
 * @package		SportsPress/Admin
 * @category	Class
 * @author 		ThemeBoy
 */
class SP_Admin_Sports {

	public static $presets = array();
	public static $options = array();

	/**
	 * Include the preset classes
	 */
	public static function get_presets() {
		if ( empty( self::$presets ) ) {
			$presets = array();
			self::$options = array(
				__( 'Traditional Sports', 'sportspress' ) => array(),
				__( 'Esports', 'sportspress' ) => array(),
				__( 'Other', 'sportspress' ) => array( 'custom' => __( 'Custom', 'sportspress' ) ),
			);

			$dir = scandir( SP()->plugin_path() . '/presets' );
			$files = array();
			if ( $dir ) {
				foreach ( $dir as $key => $value ) {
					if ( substr( $value, 0, 1 ) !== '.' && strpos( $value, '.' ) !== false ) {
						$files[] = $value;
					}
				}
			}
			foreach( $files as $file ) {
				$json_data = file_get_contents( SP()->plugin_path() . '/presets/' . $file );
				$data = json_decode( $json_data, true );
				if ( ! is_array( $data ) ) continue;
				$id = preg_replace('/\\.[^.\\s]{3,4}$/', '', $file );
				$presets[ $id ] = $data;
				$name = array_key_exists( 'name', $data ) ? __( $data['name'], 'sportspress' ) : $id;
				self::$options[ __( 'Traditional Sports', 'sportspress' ) ][ $id ] = $name;
			}
			asort( self::$options[ __( 'Traditional Sports', 'sportspress' ) ] );

			$dir = scandir( SP()->plugin_path() . '/presets/esports' );
			$files = array();
			if ( $dir ) {
				foreach ( $dir as $key => $value ) {
					if ( substr( $value, 0, 1 ) !== '.' && strpos( $value, '.' ) !== false ) {
						$files[] = $value;
					}
				}
			}
			foreach( $files as $file ) {
				$json_data = file_get_contents( SP()->plugin_path() . '/presets/esports/' . $file );
				$data = json_decode( $json_data, true );
				if ( ! is_array( $data ) ) continue;
				$id = preg_replace('/\\.[^.\\s]{3,4}$/', '', $file );
				$presets[ $id ] = $data;
				$name = array_key_exists( 'name', $data ) ? __( $data['name'], 'sportspress' ) : $id;
				self::$options[ __( 'Esports', 'sportspress' ) ][ $id ] = $name;
			}
			asort( self::$options[ __( 'Esports', 'sportspress' ) ] );

			self::$presets = apply_filters( 'sportspress_get_presets', $presets );
		}
		return self::$presets;
	}

	public static function get_preset( $id ) {
		$json_data = @file_get_contents( SP()->plugin_path() . '/presets/' . $id . '.json', true );
		
		if ( $json_data ) return json_decode( $json_data, true );
		
		$dir = scandir( SP()->plugin_path() . '/presets' );
		if ( $dir ) {
			foreach ( $dir as $key => $value ) {
				if ( substr( $value, 0, 1 ) !== '.' && strpos( $value, '.' ) === false ) {
					$json_data = @file_get_contents( SP()->plugin_path() . '/presets/' . $value . '/' . $id . '.json', true );
					if ( $json_data ) return json_decode( $json_data, true );
				}
			}
		}
	}

	public static function get_preset_options() {
		$presets = self::get_presets();
		return self::$options;
	}

	/**
	 * Apply preset
	 *
	 * @access public
	 * @return void
	 */
	public static function apply_preset( $id ) {
		if ( 'custom' == $id ) {
			$preset = array();
		} else {
			$preset = self::get_preset( $id );
		}

		// Positions
		$positions = sp_array_value( $preset, 'positions', array() );
		foreach ( $positions as $index => $term ) {
			$slug = $index . '-' . sanitize_title( $term );
			wp_insert_term( $term, 'sp_position', array( 'description' => $term, 'slug' => $slug ) );
		}

		// Outcomes
		$post_type = 'sp_outcome';
		$outcomes = sp_array_value( $preset, 'outcomes', array() );
		self::delete_preset_posts( $post_type );
		foreach ( $outcomes as $index => $outcome ) {
			$post = self::get_post_array( $outcome, $post_type );
			if ( empty( $post ) ) continue;
			$id = self::insert_preset_post( $post, $index );
			update_post_meta( $id, 'sp_abbreviation', sp_array_value( $outcome, 'abbreviation', null ) );
		}

		// Results
		$post_type = 'sp_result';
		$results = sp_array_value( $preset, 'results', array() );
		self::delete_preset_posts( $post_type );
		$primary_result = 0;
		foreach ( $results as $index => $result ) {
			$post = self::get_post_array( $result, $post_type );
			if ( empty( $post ) ) continue;
			$id = self::insert_preset_post( $post, $index );
			if ( is_array( $result ) && array_key_exists( 'primary', $result ) ) $primary_result = $post['post_name'];
		}

		// Make sure statistics have greater menu order than performance
		$i = 0;

		// Performance
		$post_type = 'sp_performance';
		$performances = sp_array_value( $preset, 'performance', array() );
		self::delete_preset_posts( $post_type );
		foreach ( $performances as $index => $performance ) {
			$post = self::get_post_array( $performance, $post_type );
			if ( empty( $post ) ) continue;
			$id = self::insert_preset_post( $post, $index );
			$i ++;
		}

		// Columns
		$post_type = 'sp_column';
		$columns = sp_array_value( $preset, 'columns', array() );
		self::delete_preset_posts( $post_type );
		foreach ( $columns as $index => $column ) {
			$post = self::get_post_array( $column, $post_type );
			if ( empty( $post ) ) continue;
			$id = self::insert_preset_post( $post, $index );
			update_post_meta( $id, 'sp_equation', sp_array_value( $column, 'equation', null ) );
			update_post_meta( $id, 'sp_precision', sp_array_value( $column, 'precision', 0 ) );
			update_post_meta( $id, 'sp_priority', sp_array_value( $column, 'priority', null ) );
			update_post_meta( $id, 'sp_order', sp_array_value( $column, 'order', 'DESC' ) );
		}

		// Metrics
		$post_type = 'sp_metric';
		$metrics = sp_array_value( $preset, 'metrics', array() );
		self::delete_preset_posts( $post_type );
		foreach ( $metrics as $index => $metric ) {
			$post = self::get_post_array( $metric, $post_type );
			if ( empty( $post ) ) continue;
			$id = self::insert_preset_post( $post, $index );
		}

		// Statistics
		$post_type = 'sp_statistic';
		$statistics = sp_array_value( $preset, 'statistics', array() );
		self::delete_preset_posts( $post_type );
		foreach ( $statistics as $index => $statistic ) {
			$post = self::get_post_array( $statistic, $post_type );
			if ( empty( $post ) ) continue;
			$id = self::insert_preset_post( $post, $i + $index );
			update_post_meta( $id, 'sp_equation', sp_array_value( $statistic, 'equation', null ) );
			update_post_meta( $id, 'sp_precision', sp_array_value( $statistic, 'precision', 0 ) );
		}
		update_option( 'sportspress_primary_result', $primary_result );

		self::delete_sample_data();
		self::add_sample_data();
	}

	public static function delete_preset_posts( $post_type = null ) {
		$args = array(
			'post_type' => $post_type,
			'posts_per_page' => -1,
			'post_status' => array( 'publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit', 'trash' ),
			'meta_query' => array(
				array(
					'key' => '_sp_preset',
					'value' => 1
				)
			)
		);

		// Delete posts
		$old_posts = get_posts( $args );
		foreach( $old_posts as $post ):
			wp_delete_post( $post->ID, true );
		endforeach;
	}

	public static function get_post_array( $post = array(), $post_type = null ) {
		$post_array = array();
		if ( is_string( $post ) ) {
			$post_array['post_title'] = $post;
			$post_array['post_name'] = sp_get_eos_safe_slug( $post_array['post_title'] );
		} elseif ( is_array( $post ) ) {
			if ( ! array_key_exists( 'name', $post ) ) $post_array = array();
			$post_array['post_title'] = $post['name'];
			$post_array['post_name'] = sp_array_value( $post, 'id', sp_get_eos_safe_slug( $post_array['post_title'] ) );
		}

		// Return empty array if post with same slug already exists
		if ( get_page_by_path( $post_array['post_name'], OBJECT, $post_type ) ) return array();

		// Set post type
		$post_array['post_type'] = $post_type;

		// Add post excerpt
		$post_array['post_excerpt'] = sp_array_value( $post, 'description', $post_array['post_title'] );

		return $post_array;
	}

	public static function insert_preset_post( $post, $index = 0 ) {
		// Increment menu order by 10 and publish post
		$post['menu_order'] = $index * 10 + 10;
		$post['post_status'] = 'publish';
		$id = wp_insert_post( $post );

		// Flag as preset
		update_post_meta( $id, '_sp_preset', 1 );

		return $id;
	}

	/**
	 * Sample data
	 *
	 * Adds sample SportsPress data
	 *
	 * @access public
	 */
	public static function add_sample_data() {
		// Terms to insert
		$taxonomies = array();

		// Leagues
		$taxonomies['sp_league'] = array( _x( 'Primary League', 'sample data', 'sportspress' ), _x( 'Secondary League', 'sample data', 'sportspress' ) );

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

		// Roles
		$taxonomies['sp_role'] = array( __( 'Coach', 'sportspress' ), __( 'Manager', 'sportspress' ), __( 'Trainer', 'sportspress' ) );

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
				}
			}
		}

		// Initialize inserted ids array
		$inserted_ids = array(
			'events' => array(),
			'calendars' => array(),
			'teams' => array(),
			'tables' => array(),
			'players' => array(),
			'lists' => array(),
			'staff' => array(),
		);

		// Create sample content
		$sample_content = _x( 'This is an example %1$s. As a new SportsPress user, you should go to <a href=\"%2$s\">your dashboard</a> to delete this %1$s and create new %3$s for your content. Have fun!', 'sample data', 'sportspress' );

		// Define teams
		$teams = array(
			array(
				'name' => 'Bentleigh Bluebirds',
				'abbreviation' => 'BENT',
				'url' => 'http://tboy.co/bluebirds',
			),
			array(
				'name' => 'Essendon Eagles',
				'abbreviation' => 'ESS',
				'url' => 'http://tboy.co/eagles',
			),
			array(
				'name' => 'Kensington Kangaroos',
				'abbreviation' => 'KENS',
				'url' => 'http://tboy.co/kangaroos',
			),
		);

		// Insert teams
		foreach ( $teams as $team ) {
			$post['post_title'] = $team['name'];
			$post['post_type'] = 'sp_team';
			$post['post_status'] = 'publish';
			$post['post_content'] = sprintf( $sample_content, __( 'Team', 'sportspress' ), admin_url(), __( 'Teams', 'sportspress' ) );

			// Terms
			$post['tax_input'] = array();
			$taxonomies = array( 'sp_league', 'sp_season' );
			foreach ( $taxonomies as $taxonomy ) {
				$post['tax_input'][ $taxonomy ] = get_terms( $taxonomy, array( 'hide_empty' => 0, 'fields' => 'ids' ) );
			};

			// Insert post
			$id = wp_insert_post( $post );

			// Add to inserted ids array
			$inserted_ids['teams'][] = $id;

			// Flag as sample
			update_post_meta( $id, '_sp_sample', 1 );

			// Update meta
			update_post_meta( $id, 'sp_abbreviation', $team['abbreviation'] );
			update_post_meta( $id, 'sp_url', $team['url'] );
		}

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

		// Get countries
		$countries = new SP_Countries();

		// Get columns
		$columns = array();
		$args = array(
			'post_type' => array( 'sp_performance', 'sp_statistic' ),
			'posts_per_page' => 6,
			'orderby' => 'menu_order',
			'order' => 'ASC',
		);
		$vars = get_posts( $args );
		foreach ( $vars as $var ) {
			$columns[] = $var->post_name;
		}

		// Insert players
		foreach ( $players as $index => $name ) {
			$post['post_title'] = $name;
			$post['post_type'] = 'sp_player';
			$post['post_status'] = 'publish';
			$post['post_content'] = sprintf( $sample_content, __( 'Player', 'sportspress' ), admin_url(), __( 'Players', 'sportspress' ) );

			// Terms
			$post['tax_input'] = array();
			$taxonomies = array( 'sp_league', 'sp_season' );
			foreach ( $taxonomies as $taxonomy ) {
				$post['tax_input'][ $taxonomy ] = get_terms( $taxonomy, array( 'hide_empty' => 0, 'fields' => 'ids' ) );
			};
			$taxonomies = array( 'sp_position' );
			foreach ( $taxonomies as $taxonomy ) {
				$terms = get_terms( $taxonomy, array( 'hide_empty' => 0, 'fields' => 'ids', 'orderby' => 'slug', 'number' => 1, 'offset' => $index % 4 ) );
				$post['tax_input'][ $taxonomy ] = $terms;
			};

			// Insert post
			$id = wp_insert_post( $post );

			// Add to inserted ids array
			$inserted_ids['players'][] = $id;

			// Flag as sample
			update_post_meta( $id, '_sp_sample', 1 );

			// Calculate meta
			$nationality = array_rand( $countries->countries );
			$team_index = floor( $index / 4 );
			$teams = $inserted_ids['teams'];
			$current_team = sp_array_value( $teams, $team_index );
			$past_teams = $teams;
			unset( $past_teams[ $team_index ] );

			// Update meta
			update_post_meta( $id, 'sp_columns', $columns );
			update_post_meta( $id, 'sp_number', $index + 1 );
			update_post_meta( $id, 'sp_nationality', $nationality );
			sp_update_post_meta_recursive( $id, 'sp_team', $teams );
			update_post_meta( $id, 'sp_current_team', $current_team );
			sp_update_post_meta_recursive( $id, 'sp_past_team', $past_teams );
		}
	}

	/**
	 * Deletes sample SportsPress data
	 *
	 * @access public
	 */
	public static function delete_sample_data() {
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

	/**
	 * Sport preset names for localization
	 * @return null
	 */
	public static function sport_preset_names() {
		__( 'Baseball', 'sportspress' );
		__( 'Basketball', 'sportspress' );
		__( 'Cricket', 'sportspress' );
		__( 'Darts', 'sportspress' );
		__( 'American Football', 'sportspress' );
		__( 'Australian Rules Football', 'sportspress' );
		__( 'Handball', 'sportspress' );
		__( 'Ice Hockey', 'sportspress' );
		__( 'Netball', 'sportspress' );
		__( 'Rugby League', 'sportspress' );
		__( 'Rugby Union', 'sportspress' );
		__( 'Snooker', 'sportspress' );
		__( 'Soccer (Association Football)', 'sportspress' );
		__( 'Squash', 'sportspress' );
		__( 'Table Tennis', 'sportspress' );
		__( 'Tennis', 'sportspress' );
		__( 'Volleyball', 'sportspress' );
		__( 'Water Polo', 'sportspress' );
		__( 'Dota 2', 'sportspress' );
		__( 'League of Legends', 'sportspress' );
	}
}
