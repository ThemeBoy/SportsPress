<?php
/**
 * SportsPress Admin Sports Class.
 *
 * The SportsPress admin sports class stores preset sport data.
 *
 * @class 		SP_Admin_Sports
 * @version		2.2
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
				'team-sports' => array(),
				'racket-sports' => array(),
				'water-sports' => array(),
				'target-sports' => array(),
				'esports' => array(),
				'other' => array(),
			);

			foreach ( self::$options as $slug => $options ) {
				$dir = scandir( SP()->plugin_path() . '/presets/' . $slug );
				$files = array();
				if ( $dir ) {
					foreach ( $dir as $key => $value ) {
						if ( substr( $value, 0, 1 ) !== '.' && strpos( $value, '.' ) !== false ) {
							$files[] = $value;
						}
					}
				}
				foreach( $files as $file ) {
					$json_data = file_get_contents( SP()->plugin_path() . '/presets/' . $slug . '/' . $file );
					$data = json_decode( $json_data, true );
					if ( ! is_array( $data ) ) continue;
					$id = preg_replace('/\\.[^.\\s]{3,4}$/', '', $file );
					$presets[ $id ] = $data;
					$name = array_key_exists( 'name', $data ) ? __( $data['name'], 'sportspress' ) : $id;

					// Conditionally append filename in parentheses for clarity
					if ( false === strpos( str_replace( ' ', '', strtolower( $data['name'] ) ), str_replace( '-', '', $id ) ) ) {
						if ( 4 < strlen( $id ) ) {
							$name .= ' (' . ucfirst( $id ) . ')';
						} else {
							$name .= ' (' . strtoupper( $id ) . ')';
						}
					}

					self::$options[ $slug ][ $id ] = $name;
				}
				asort( self::$options[ $slug ] );
			}

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
		$preset = self::get_preset( $id );

		// Positions
		$positions = sp_array_value( $preset, 'positions', array() );
		$i = 0;
		foreach ( $positions as $position ) {
			if ( is_string( $position ) ) {
				$name = $position;
				$sections = array( 0, 1 );
			} else {
				$name = sp_array_value( $position, 'name', __( 'Position', 'sportspress' ) );
				$sections = sp_array_value( $position, 'sections', array( 0, 1 ) );
			}
			$slug = $i . '-' . sanitize_title( $name );
			$term = wp_insert_term( $name, 'sp_position', array( 'slug' => $slug ) );
			if ( is_wp_error( $term ) ) continue;
			$t_id = $term['term_id'];
			$term_meta = get_option( "taxonomy_$t_id" );
			$term_meta['sp_sections'] = $sections;
			update_option( "taxonomy_$t_id", $term_meta );
			$i++;
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
			update_post_meta( $id, 'sp_condition', sp_array_value( $outcome, 'condition', null ) );
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
			update_post_meta( $id, 'sp_equation', sp_array_value( $result, 'equation', null ) );
		}

		// Make sure statistics and metrics have greater menu order than performance
		$i = 0;

		// Performance
		$post_type = 'sp_performance';
		$performances = sp_array_value( $preset, 'performance', array() );
		self::delete_preset_posts( $post_type );
		foreach ( $performances as $index => $performance ) {
			$post = self::get_post_array( $performance, $post_type );
			if ( empty( $post ) ) continue;
			$id = self::insert_preset_post( $post, $index );
			if ( isset( $performance['position'] ) ) {
				wp_set_object_terms( $id, $performance['position'], 'sp_position', false );
			}
			update_post_meta( $id, 'sp_singular', sp_array_value( $performance, 'singular', null ) );
			update_post_meta( $id, 'sp_icon', sp_array_value( $performance, 'icon', null ) );
			update_post_meta( $id, 'sp_color', sp_array_value( $performance, 'color', null ) );
			update_post_meta( $id, 'sp_section', sp_array_value( $performance, 'section', -1 ) );
			update_post_meta( $id, 'sp_format', sp_array_value( $performance, 'format', 'number' ) );
			update_post_meta( $id, 'sp_equation', sp_array_value( $performance, 'equation', null ) );
			update_post_meta( $id, 'sp_precision', sp_array_value( $performance, 'precision', 0 ) );
			update_post_meta( $id, 'sp_timed', sp_array_value( $performance, 'timed', null ) );
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
			$id = self::insert_preset_post( $post, $i + $index );
			$i ++;
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


		// Options
		$options = sp_array_value( $preset, 'options', array() );
		foreach ( $options as $option => $value ) {
			update_option( 'sportspress_' . $option, $value );
		}

		// Primary Result
		update_option( 'sportspress_primary_result', $primary_result );
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
		if ( is_array( $post ) && array_key_exists( 'description', $post ) ) {
			$post_array['post_excerpt'] = $post['description'];
		}

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
	 * Sport category names
	 * @return null
	 */
	public static function sport_category_names() {
		return apply_filters( 'sportspress_sport_categories', array(
			'team-sports' => __( 'Team Sports', 'sportspress' ),
			'racket-sports' => __( 'Racket Sports', 'sportspress' ),
			'water-sports' => __( 'Water Sports', 'sportspress' ),
			'target-sports' => __( 'Target Sports', 'sportspress' ),
			'esports' => __( 'Esports', 'sportspress' ),
			'other' => __( 'Other', 'sportspress' ),
		) );
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
		__( 'Floorball', 'sportspress' );
		__( 'Association Football', 'sportspress' );
		__( 'American Football', 'sportspress' );
		__( 'Australian Rules Football', 'sportspress' );
		__( 'Handball', 'sportspress' );
		__( 'Ice Hockey', 'sportspress' );
		__( 'Lacrosse', 'sportspress' );
		__( 'Netball', 'sportspress' );
		__( 'Rugby League', 'sportspress' );
		__( 'Rugby Union', 'sportspress' );
		__( 'Snooker', 'sportspress' );
		__( 'Softball', 'sportspress' );
		__( 'Squash', 'sportspress' );
		__( 'Table Tennis', 'sportspress' );
		__( 'Tennis', 'sportspress' );
		__( 'Volleyball', 'sportspress' );
		__( 'Water Polo', 'sportspress' );
		__( 'Dota 2', 'sportspress' );
		__( 'League of Legends', 'sportspress' );
		__( 'Counter-Strike: Global Offensive', 'sportspress' );
	}
}
