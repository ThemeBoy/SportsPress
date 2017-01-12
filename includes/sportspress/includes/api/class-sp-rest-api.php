<?php
/**
 * REST API Class
 *
 * The SportsPress REST API class handles all API-related hooks.
 *
 * @class 		SP_REST_API
 * @version		2.2
 * @package		SportsPress/Classes
 * @category	Class
 * @package 	SportsPress/API
 * @author 		ThemeBoy
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'SP_REST_API' ) ) :

/**
 * SP_REST_API Class
 */
class SP_REST_API {

	/**
	 * Constructor.
	 */
	public function __construct() {
		// Create REST routes
		add_action( 'rest_api_init', array( $this, 'create_routes' ) );
		add_action( 'rest_api_init', array( $this, 'register_fields' ), 0 );
		
		// Add filter for post meta query
		add_filter( 'rest_query_vars', array( $this, 'meta_query' ) );
		
		// Add filters to query scheduled events
		add_filter( 'rest_sp_event_query', array( $this, 'event_query' ) );
		add_filter( 'rest_query_vars', array( $this, 'query_vars' ) );
	}

	/**
	 * Create REST routes.
	 */
	public static function create_routes() {

		if ( ! class_exists( 'SP_REST_Posts_Controller' ) ) {
			require_once dirname( __FILE__ ) . '/class-sp-rest-posts-controller.php';
		}

		if ( ! class_exists( 'SP_REST_Terms_Controller' ) ) {
			require_once dirname( __FILE__ ) . '/class-sp-rest-terms-controller.php';
		}
		
		do_action( 'sportspress_create_rest_routes' );
	}

	/**
	 * Register REST fields.
	 */
	public static function register_fields() {
		if ( ! function_exists( 'register_rest_field' ) ) return;
		
		register_rest_field( 'sp_event',
			'teams',
			array(
				'get_callback'    => 'SP_REST_API::get_post_meta_recursive',
				'update_callback' => 'SP_REST_API::update_post_meta_recursive',
				'schema'          => array(
					'description'     => __( 'Teams', 'sportspress' ),
					'type'            => 'array',
					'context'         => array( 'view', 'edit', 'embed' ),
					'arg_options'     => array(
						'sanitize_callback' => 'rest_sanitize_request_arg',
					),
				),
			)
		);

		register_rest_field( 'sp_event',
			'main_results',
			array(
				'get_callback'    => 'SP_REST_API::get_post_data',
				'schema'          => array(
					'description'     => __( 'Main Results', 'sportspress' ),
					'type'            => 'array',
					'context'         => array( 'view', 'embed' ),
					'arg_options'     => array(
						'sanitize_callback' => 'rest_sanitize_request_arg',
					),
				),
			)
		);

		register_rest_field( 'sp_event',
			'outcome',
			array(
				'get_callback'    => 'SP_REST_API::get_post_data',
				'schema'          => array(
					'description'     => __( 'Outcome', 'sportspress' ),
					'type'            => 'array',
					'context'         => array( 'view', 'embed' ),
					'arg_options'     => array(
						'sanitize_callback' => 'rest_sanitize_request_arg',
					),
				),
			)
		);

		register_rest_field( 'sp_event',
			'winner',
			array(
				'get_callback'    => 'SP_REST_API::get_post_data',
				'schema'          => array(
					'description'     => __( 'Winner', 'sportspress' ),
					'type'            => 'integer',
					'context'         => array( 'view', 'embed' ),
					'arg_options'     => array(
						'sanitize_callback' => 'rest_sanitize_request_arg',
					),
				),
			)
		);
		
		register_rest_field( 'sp_event',
			'minutes',
			array(
				'get_callback'    => 'SP_REST_API::get_post_data',
				'update_callback' => 'SP_REST_API::update_post_meta',
				'schema'          => array(
					'description'     => __( 'Full Time', 'sportspress' ),
					'type'            => 'integer',
					'context'         => array( 'view', 'edit', 'embed' ),
					'arg_options'     => array(
						'sanitize_callback' => 'absint',
					),
				),
			)
		);
		
		register_rest_field( 'sp_event',
			'players',
			array(
				'get_callback'    => 'SP_REST_API::get_post_meta_recursive',
				'update_callback' => 'SP_REST_API::update_post_meta_recursive',
				'schema'          => array(
					'description'     => __( 'Players', 'sportspress' ),
					'type'            => 'array',
					'context'         => array( 'view', 'edit' ),
					'arg_options'     => array(
						'sanitize_callback' => 'rest_sanitize_request_arg',
					),
				),
			)
		);
		
		register_rest_field( 'sp_event',
			'offense',
			array(
				'get_callback'    => 'SP_REST_API::get_post_meta_recursive',
				'update_callback' => 'SP_REST_API::update_post_meta_recursive',
				'schema'          => array(
					'description'     => __( 'Offense', 'sportspress' ),
					'type'            => 'array',
					'context'         => array( 'view', 'edit' ),
					'arg_options'     => array(
						'sanitize_callback' => 'rest_sanitize_request_arg',
					),
				),
			)
		);
				
		register_rest_field( 'sp_event',
			'defense',
			array(
				'get_callback'    => 'SP_REST_API::get_post_meta_recursive',
				'update_callback' => 'SP_REST_API::update_post_meta_recursive',
				'schema'          => array(
					'description'     => __( 'Defense', 'sportspress' ),
					'type'            => 'array',
					'context'         => array( 'view', 'edit' ),
					'arg_options'     => array(
						'sanitize_callback' => 'rest_sanitize_request_arg',
					),
				),
			)
		);
		
		register_rest_field( 'sp_event',
			'staff',
			array(
				'get_callback'    => 'SP_REST_API::get_post_meta_recursive',
				'update_callback' => 'SP_REST_API::update_post_meta_recursive',
				'schema'          => array(
					'description'     => __( 'Staff', 'sportspress' ),
					'type'            => 'array',
					'context'         => array( 'view', 'edit' ),
					'arg_options'     => array(
						'sanitize_callback' => 'rest_sanitize_request_arg',
					),
				),
			)
		);
		
		register_rest_field( 'sp_event',
			'results',
			array(
				'get_callback'    => 'SP_REST_API::get_post_data',
				'update_callback' => 'SP_REST_API::update_post_meta_arrays',
				'schema'          => array(
					'description'     => __( 'Results', 'sportspress' ),
					'type'            => 'array',
					'context'         => array( 'view', 'edit' ),
					'arg_options'     => array(
						'sanitize_callback' => 'rest_sanitize_request_arg',
					),
				),
			)
		);

		register_rest_field( 'sp_event',
			'performance',
			array(
				'get_callback'    => 'SP_REST_API::get_post_data',
				'update_callback' => 'SP_REST_API::update_post_meta_arrays_multi',
				'schema'          => array(
					'description'     => __( 'Box Score', 'sportspress' ),
					'type'            => 'array',
					'context'         => array( 'view', 'edit' ),
					'arg_options'     => array(
						'sanitize_callback' => 'rest_sanitize_request_arg',
					),
				),
			)
		);
		
		register_rest_field( 'sp_team',
			'staff',
			array(
				'get_callback'    => 'SP_REST_API::get_post_meta_recursive',
				'update_callback' => 'SP_REST_API::update_post_meta_recursive',
				'schema'          => array(
					'description'     => __( 'Staff', 'sportspress' ),
					'type'            => 'array',
					'context'         => array( 'view', 'edit' ),
					'arg_options'     => array(
						'sanitize_callback' => 'rest_sanitize_request_arg',
					),
				),
			)
		);
		
		register_rest_field( 'sp_team',
			'tables',
			array(
				'get_callback'    => 'SP_REST_API::get_post_meta_recursive',
				'update_callback' => 'SP_REST_API::update_post_meta_recursive',
				'schema'          => array(
					'description'     => __( 'League Tables', 'sportspress' ),
					'type'            => 'array',
					'context'         => array( 'view', 'edit' ),
					'arg_options'     => array(
						'sanitize_callback' => 'rest_sanitize_request_arg',
					),
				),
			)
		);
		
		register_rest_field( 'sp_team',
			'lists',
			array(
				'get_callback'    => 'SP_REST_API::get_post_meta_recursive',
				'update_callback' => 'SP_REST_API::update_post_meta_recursive',
				'schema'          => array(
					'description'     => __( 'Player Lists', 'sportspress' ),
					'type'            => 'array',
					'context'         => array( 'view', 'edit' ),
					'arg_options'     => array(
						'sanitize_callback' => 'rest_sanitize_request_arg',
					),
				),
			)
		);
		
		register_rest_field( 'sp_team',
			'events',
			array(
				'get_callback'    => 'SP_REST_API::get_post_ids_with_meta',
				'schema'          => array(
					'description'     => __( 'Events', 'sportspress' ),
					'type'            => 'array',
					'context'         => array( 'view' ),
					'arg_options'     => array(
						'sanitize_callback' => 'rest_sanitize_request_arg',
					),
				),
			)
		);
		
		register_rest_field( 'sp_team',
			'abbreviation',
			array(
				'get_callback'    => 'SP_REST_API::get_post_meta',
				'update_callback' => 'SP_REST_API::update_post_meta',
				'schema'          => array(
					'description'     => __( 'Abbreviation', 'sportspress' ),
					'type'            => 'string',
					'context'         => array( 'view', 'edit', 'embed' ),
					'arg_options'     => array(
						'sanitize_callback' => 'sanitize_text_field',
					),
				),
			)
		);
		
		register_rest_field( 'sp_team',
			'url',
			array(
				'get_callback'    => 'SP_REST_API::get_post_meta',
				'update_callback' => 'SP_REST_API::update_post_meta',
				'schema'          => array(
					'description'     => __( 'Site URL', 'sportspress' ),
					'type'            => 'string',
					'context'         => array( 'view', 'edit', 'embed' ),
					'arg_options'     => array(
						'sanitize_callback' => 'sanitize_text_field',
					),
				),
			)
		);
		
		register_rest_field( 'sp_player',
			'number',
			array(
				'get_callback'    => 'SP_REST_API::get_post_meta',
				'update_callback' => 'SP_REST_API::update_post_meta',
				'schema'          => array(
					'description'     => __( 'Squad Number', 'sportspress' ),
					'type'            => 'integer',
					'context'         => array( 'view', 'edit', 'embed' ),
					'arg_options'     => array(
						'sanitize_callback' => 'absint',
					),
				),
			)
		);
		
		register_rest_field( 'sp_player',
			'teams',
			array(
				'get_callback'    => 'SP_REST_API::get_post_meta_recursive',
				'update_callback' => 'SP_REST_API::update_post_meta_recursive',
				'schema'          => array(
					'description'     => __( 'Teams', 'sportspress' ),
					'type'            => 'array',
					'context'         => array( 'view', 'edit' ),
					'arg_options'     => array(
						'sanitize_callback' => 'rest_sanitize_request_arg',
					),
				),
			)
		);
		
		register_rest_field( 'sp_player',
			'current_teams',
			array(
				'get_callback'    => 'SP_REST_API::get_post_meta_recursive',
				'update_callback' => 'SP_REST_API::update_post_meta_recursive',
				'schema'          => array(
					'description'     => __( 'Current Teams', 'sportspress' ),
					'type'            => 'array',
					'context'         => array( 'view', 'edit' ),
					'arg_options'     => array(
						'sanitize_callback' => 'rest_sanitize_request_arg',
					),
				),
			)
		);
		
		register_rest_field( 'sp_player',
			'past_teams',
			array(
				'get_callback'    => 'SP_REST_API::get_post_meta_recursive',
				'update_callback' => 'SP_REST_API::update_post_meta_recursive',
				'schema'          => array(
					'description'     => __( 'Past Teams', 'sportspress' ),
					'type'            => 'array',
					'context'         => array( 'view', 'edit' ),
					'arg_options'     => array(
						'sanitize_callback' => 'rest_sanitize_request_arg',
					),
				),
			)
		);
		
		register_rest_field( 'sp_player',
			'nationalities',
			array(
				'get_callback'    => 'SP_REST_API::get_post_data',
				'update_callback' => 'SP_REST_API::update_post_meta_recursive',
				'schema'          => array(
					'description'     => __( 'Nationalities', 'sportspress' ),
					'type'            => 'array',
					'context'         => array( 'view', 'embed' ),
					'arg_options'     => array(
						'sanitize_callback' => 'rest_sanitize_request_arg',
					),
				),
			)
		);
		
		register_rest_field( 'sp_player',
			'metrics',
			array(
				'get_callback'    => 'SP_REST_API::get_post_data',
				'update_callback' => 'SP_REST_API::update_post_meta_array',
				'schema'          => array(
					'description'     => __( 'Metrics', 'sportspress' ),
					'type'            => 'array',
					'context'         => array( 'view', 'edit' ),
					'arg_options'     => array(
						'sanitize_callback' => 'rest_sanitize_request_arg',
					),
				),
			)
		);
		
		register_rest_field( 'sp_player',
			'statistics',
			array(
				'get_callback'    => 'SP_REST_API::get_post_data',
				'update_callback' => 'SP_REST_API::update_post_meta_arrays_multi',
				'schema'          => array(
					'description'     => __( 'Statistics', 'sportspress' ),
					'type'            => 'array',
					'context'         => array( 'view', 'edit' ),
					'arg_options'     => array(
						'sanitize_callback' => 'rest_sanitize_request_arg',
					),
				),
			)
		);
		
		register_rest_field( 'sp_staff',
			'teams',
			array(
				'get_callback'    => 'SP_REST_API::get_post_meta_recursive',
				'update_callback' => 'SP_REST_API::update_post_meta_recursive',
				'schema'          => array(
					'description'     => __( 'Teams', 'sportspress' ),
					'type'            => 'array',
					'context'         => array( 'view', 'edit' ),
					'arg_options'     => array(
						'sanitize_callback' => 'rest_sanitize_request_arg',
					),
				),
			)
		);
		
		register_rest_field( 'sp_staff',
			'current_teams',
			array(
				'get_callback'    => 'SP_REST_API::get_post_meta_recursive',
				'update_callback' => 'SP_REST_API::update_post_meta_recursive',
				'schema'          => array(
					'description'     => __( 'Current Teams', 'sportspress' ),
					'type'            => 'array',
					'context'         => array( 'view', 'edit' ),
					'arg_options'     => array(
						'sanitize_callback' => 'rest_sanitize_request_arg',
					),
				),
			)
		);
		
		register_rest_field( 'sp_staff',
			'past_teams',
			array(
				'get_callback'    => 'SP_REST_API::get_post_meta_recursive',
				'update_callback' => 'SP_REST_API::update_post_meta_recursive',
				'schema'          => array(
					'description'     => __( 'Past Teams', 'sportspress' ),
					'type'            => 'array',
					'context'         => array( 'view', 'edit' ),
					'arg_options'     => array(
						'sanitize_callback' => 'rest_sanitize_request_arg',
					),
				),
			)
		);
		
		register_rest_field( 'sp_staff',
			'nationalities',
			array(
				'get_callback'    => 'SP_REST_API::get_post_data',
				'update_callback' => 'SP_REST_API::update_post_meta_recursive',
				'schema'          => array(
					'description'     => __( 'Nationalities', 'sportspress' ),
					'type'            => 'array',
					'context'         => array( 'view', 'embed' ),
					'arg_options'     => array(
						'sanitize_callback' => 'rest_sanitize_request_arg',
					),
				),
			)
		);
		
		do_action( 'sportspress_register_rest_fields' );
	}

	/**
	 * Get the value of a single SportsPress meta field.
	 *
	 * @param array $object Details of current post.
	 * @param string $field_name Name of field.
	 * @param WP_REST_Request $request Current request
	 *
	 * @return mixed
	 */
	public static function get_post_meta( $object, $field_name, $request ) {
		$meta = get_post_meta( $object['id'], self::meta_key( $field_name ), true );
		
		if ( ctype_digit( $meta ) ) {
			$meta = intval( $meta );
		}
		
		return $meta;
	}

	/**
	 * Handler for updating custom field data.
	 *
	 * @param mixed $value The value of the field
	 * @param object $object The object from the response
	 * @param string $field_name Name of field
	 *
	 * @return bool|int
	 */
	public static function update_post_meta( $value, $object, $field_name ) {
		return update_post_meta( $object->ID, self::meta_key( $field_name ), strip_tags( $value ) );
	}

	/**
	 * Handler for updating array values by merging with the existing array.
	 *
	 * @param mixed $value The value of the field
	 * @param object $object The object from the response
	 * @param string $field_name Name of field
	 *
	 * @return bool|int
	 */
	public static function update_post_meta_array( $value, $object, $field_name ) {
		if ( ! is_array( $value ) ) return false;
		
		$type = $object->post_type;
		
		$meta = get_post_meta( $object->ID, self::meta_key( $field_name, $type ), true );
		
		if ( ! is_array( $meta ) ) $meta = array();
		
		$meta = array_merge( $meta, $value );
		
		return update_post_meta( $object->ID, self::meta_key( $field_name, $type ), $meta );
	}

	/**
	 * Handler for updating array values by merging with the existing multidimentional array.
	 *
	 * @param mixed $value The value of the field
	 * @param object $object The object from the response
	 * @param string $field_name Name of field
	 *
	 * @return bool|int
	 */
	public static function update_post_meta_arrays( $value, $object, $field_name ) {
		if ( ! is_array( $value ) ) return false;
		
		$type = $object->post_type;
		
		$meta = get_post_meta( $object->ID, self::meta_key( $field_name, $type ), true );
		
		if ( ! is_array( $meta ) ) $meta = array();
		
		foreach ( $value as $index => $array ) {
			if ( ! is_array( $array ) ) continue;
			
			if ( ! isset( $meta[ $index ] ) || ! is_array( $meta[ $index ] ) ) {
				$meta[ $index ] = array();
			}
			
			$meta[ $index ] = array_merge( $meta[ $index ], $array );
		}
		
		return update_post_meta( $object->ID, self::meta_key( $field_name, $type ), $meta );
	}

	/**
	 * Handler for updating array values by merging with existing multidimensional arrays.
	 *
	 * @param mixed $value The value of the field
	 * @param object $object The object from the response
	 * @param string $field_name Name of field
	 *
	 * @return bool|int
	 */
	public static function update_post_meta_arrays_multi( $value, $object, $field_name ) {
		if ( ! is_array( $value ) ) return false;
		
		$type = $object->post_type;
		
		$meta = get_post_meta( $object->ID, self::meta_key( $field_name, $type ), true );
		
		if ( ! is_array( $meta ) ) $meta = array();
		
		foreach ( $value as $key => $arrays ) {
			if ( ! is_array( $arrays ) ) continue;
			
			if ( ! isset( $meta[ $key ] ) || ! is_array( $meta[ $key ] ) ) {
				$meta[ $key ] = array();
			}
			
			foreach ( $arrays as $index => $array ) {
				if ( ! is_array( $array ) ) continue;
				
				if ( ! isset( $meta[ $key ][ $index ] ) || ! is_array( $meta[ $key ][ $index ] ) ) {
					$meta[ $key ][ $index ] = array();
				}
				
				$meta[ $key ][ $index ] = array_merge( $meta[ $key ][ $index ], $array );
			}
		}
		
		return update_post_meta( $object->ID, self::meta_key( $field_name, $type ), $meta );
	}

	/**
	 * Get an array of SportsPress meta field values.
	 *
	 * @param array $object Details of current post.
	 * @param string $field_name Name of field.
	 * @param WP_REST_Request $request Current request
	 *
	 * @return mixed
	 */
	public static function get_post_meta_recursive( $object, $field_name, $request ) {
		$meta = get_post_meta( $object['id'], self::meta_key( $field_name ), false );
		
		return array_map( 'intval', $meta );
	}

	/**
	 * Handler for updating multiple custom field values.
	 *
	 * @param array $values The values of the field
	 * @param object $object The object from the response
	 * @param string $field_name Name of field
	 *
	 * @return bool|int
	 */
	public static function update_post_meta_recursive( $values, $object, $field_name ) {
		delete_post_meta( $object->ID, self::meta_key( $field_name ) );

		$response = true;
		foreach ( $values as $value ) {
			$response = add_post_meta( $object->ID, self::meta_key( $field_name ), $value );
		}

		return $response;
	}

	/**
	 * Get an array of SportsPress meta field values and split into separate arrays based on placeholder zeroes.
	 *
	 * @param array $object Details of current post.
	 * @param string $field_name Name of field.
	 * @param WP_REST_Request $request Current request
	 *
	 * @return mixed
	 */
	public static function get_post_meta_recursive_split( $object, $field_name, $request ) {
		$array = self::get_post_meta_recursive( $object, $field_name, $request );
		
		$meta = array();
		$i = 0;
		foreach ( $array as $value ) {
			if ( $value ) {
				$meta[ $i ][] = $value;
			} else {
				$i ++;
			}
		}

		return $meta;
	}
	
	/**
	 * Get a list of posts with a meta value of the given field name.
	 *
	 * @param array $object Details of current post.
	 * @param string $field_name Name of field.
	 * @param WP_REST_Request $request Current request
	 *
	 * @return mixed
	 */
	public static function get_post_ids_with_meta( $object, $field_name, $request ) {
		$meta_key = self::meta_key( $field_name );
		
		$query_args = array(
			'post_type' => $meta_key,
			'posts_per_page' => 2000,
			'meta_query' => array(
				'key' => $object['type'],
				'value' => $object['id'],
				'compare' => 'IN',
			),
		);
		
		if ( 'sp_event' === $meta_key ) {
			$query_args['orderby'] = 'date';
			$query_args['order'] = 'DESC';
			$query_args['post_status'] = array( 'publish', 'future' );
		} else {
			$query_args['orderby'] = 'title';
			$query_args['order'] = 'ASC';
			$query_args['post_status'] = 'publish';
		}
			
		$posts_query = new WP_Query();
		$query_result = $posts_query->query( $query_args );
		
		return wp_list_pluck( $query_result, 'ID' );
	}

	/**
	 * Get custom SportsPress data based on post type and field name.
	 *
	 * @param array $object Details of current post.
	 * @param string $field_name Name of field.
	 * @param WP_REST_Request $request Current request
	 *
	 * @return mixed
	 */
	public static function get_post_data( $object, $field_name, $request ) {
		$type = $object['type'];
		
		$post = new $type( $object['id'] );
		
		return $post->$field_name();
	}

	/**
	 * Get meta key of a field
	 */
	public static function meta_key( $field_name, $type = null ) {
		$names = array(
			'current_teams' => 'sp_current_team',
			'events' => 'sp_event',
			'lists' => 'sp_list',
			'nationalities' => 'sp_nationality',
			'past_teams' => 'sp_past_team',
			'performance' => 'sp_players',
			'players' => 'sp_player',
			'offense' => 'sp_offense',
			'defense' => 'sp_defense',
			'table' => 'sp_teams',
			'tables' => 'sp_table',
			'teams' => 'sp_team',
		);
		
		if ( isset( $type ) ) {
			switch ( $type ) {
				case 'sp_table':
					$names['data'] = 'sp_teams';
					break;
				case 'sp_list':
					$names['data'] = 'sp_players';
					break;
			}
		}
		
		$names = apply_filters( 'sportspress_rest_meta_keys', $names, $type );
		
		if ( array_key_exists( $field_name, $names ) ) {
			$field_name = $names[ $field_name ];
		} else {
			$field_name = 'sp_' . $field_name;
		}
		
		return $field_name;
	}
	
	/**
	 * Enable meta query vars
	 */
	public static function meta_query( $valid_vars ) {
		$valid_vars = array_merge( $valid_vars, array( 'meta_key', 'meta_value', 'meta_query' ) );
		return $valid_vars;
	}
	
	/**
	 * Add scheduled events to query
	 */
	public static function event_query( $args ) {
		$args['post_status'] = array( 'publish', 'future' );
		return $args;
	}
	
	/**
	 * Enable post status in events query
	 */
	public static function query_vars( $vars ) {
		global $wp;
		$vars[] = 'post_status';
		return $vars;
	}
}

endif;

return new SP_REST_API();
