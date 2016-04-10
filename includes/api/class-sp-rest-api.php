<?php
/**
 * REST API Class
 *
 * The SportsPress REST API class handles all API-related hooks.
 *
 * @class 		SP_REST_API
 * @version		1.9.19
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
		
		// Add extra league arguments
		add_filter( 'sportspress_register_taxonomy_league', array( $this, 'add_args' ), 11 );
		add_filter( 'sportspress_register_taxonomy_league', array( $this, 'add_league_args' ), 11 );
		
		// Add extra season arguments
		add_filter( 'sportspress_register_taxonomy_season', array( $this, 'add_args' ), 11 );
		add_filter( 'sportspress_register_taxonomy_season', array( $this, 'add_season_args' ), 11 );
		
		// Add extra venue arguments
		add_filter( 'sportspress_register_taxonomy_venue', array( $this, 'add_args' ), 11 );
		add_filter( 'sportspress_register_taxonomy_venue', array( $this, 'add_venue_args' ), 11 );
		
		// Add extra position arguments
		add_filter( 'sportspress_register_taxonomy_position', array( $this, 'add_args' ), 11 );
		add_filter( 'sportspress_register_taxonomy_position', array( $this, 'add_position_args' ), 11 );
		
		// Add extra role arguments
		add_filter( 'sportspress_register_taxonomy_role', array( $this, 'add_args' ), 11 );
		add_filter( 'sportspress_register_taxonomy_role', array( $this, 'add_role_args' ), 11 );
		
		// Add extra event arguments
		add_filter( 'sportspress_register_post_type_event', array( $this, 'add_args' ), 11 );
		add_filter( 'sportspress_register_post_type_event', array( $this, 'add_event_args' ), 11 );
		
		// Add extra team arguments
		add_filter( 'sportspress_register_post_type_team', array( $this, 'add_args' ), 11 );
		add_filter( 'sportspress_register_post_type_team', array( $this, 'add_team_args' ), 11 );
		
		// Add extra player arguments
		add_filter( 'sportspress_register_post_type_player', array( $this, 'add_args' ), 11 );
		add_filter( 'sportspress_register_post_type_player', array( $this, 'add_player_args' ), 11 );
		
		// Add extra staff arguments
		add_filter( 'sportspress_register_post_type_staff', array( $this, 'add_args' ), 11 );
		add_filter( 'sportspress_register_post_type_staff', array( $this, 'add_staff_args' ), 11 );
	}

	/**
	 * Create REST routes.
	 */
	public static function create_routes() {
		if ( ! class_exists( 'SP_REST_Posts_Controller' ) ) {
			require_once dirname( __FILE__ ) . '/class-sp-rest-posts-controller.php';
		}

		$controller = new SP_REST_Posts_Controller( 'sp_event' );
		$controller->register_routes();

		$controller = new SP_REST_Posts_Controller( 'sp_team' );
		$controller->register_routes();

		$controller = new SP_REST_Posts_Controller( 'sp_player' );
		$controller->register_routes();

		$controller = new SP_REST_Posts_Controller( 'sp_staff' );
		$controller->register_routes();
	}

	/**
	 * Register REST fields.
	 */
	public static function register_fields() {
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
					'type'            => 'array',
					'context'         => array( 'view', 'embed' ),
					'arg_options'     => array(
						'sanitize_callback' => 'rest_sanitize_request_arg',
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
					'context'         => array( 'view', 'edit', 'embed' ),
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
					'context'         => array( 'view', 'edit', 'embed' ),
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
				'update_callback' => 'SP_REST_API::update_post_meta',
				'schema'          => array(
					'description'     => __( 'Results', 'sportspress' ),
					'type'            => 'array',
					'context'         => array( 'view', 'edit', 'embed' ),
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
				'update_callback' => 'SP_REST_API::update_post_meta',
				'schema'          => array(
					'description'     => __( 'Box Score', 'sportspress' ),
					'type'            => 'array',
					'context'         => array( 'view', 'edit', 'embed' ),
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
					'context'         => array( 'view', 'edit', 'embed' ),
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
					'context'         => array( 'view', 'edit', 'embed' ),
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
					'context'         => array( 'view', 'edit', 'embed' ),
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
					'context'         => array( 'view', 'embed' ),
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
			'teams',
			array(
				'get_callback'    => 'SP_REST_API::get_post_meta_recursive',
				'schema'          => array(
					'description'     => __( 'Teams', 'sportspress' ),
					'type'            => 'array',
					'context'         => array( 'view', 'embed' ),
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
				'schema'          => array(
					'description'     => __( 'Current Teams', 'sportspress' ),
					'type'            => 'array',
					'context'         => array( 'view', 'embed' ),
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
				'schema'          => array(
					'description'     => __( 'Past Teams', 'sportspress' ),
					'type'            => 'array',
					'context'         => array( 'view', 'embed' ),
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
				'update_callback' => 'SP_REST_API::update_post_meta',
				'schema'          => array(
					'description'     => __( 'Metrics', 'sportspress' ),
					'type'            => 'array',
					'context'         => array( 'view', 'edit', 'embed' ),
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
				'update_callback' => 'SP_REST_API::update_post_meta',
				'schema'          => array(
					'description'     => __( 'Statistics', 'sportspress' ),
					'type'            => 'array',
					'context'         => array( 'view', 'edit', 'embed' ),
					'arg_options'     => array(
						'sanitize_callback' => 'rest_sanitize_request_arg',
					),
				),
			)
		);
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
		
		if ( ctype_digit( $value ) ) {
			$value = intval( $value );
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
		if ( ! $value || ! is_string( $value ) ) {
			return;
		}

		return update_post_meta( $object->ID, self::meta_key( $field_name ), strip_tags( $value ) );
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
		
		return array_map( 'absint', $meta );
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
				'key' => $object->type,
				'value' => $object->id,
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
	public static function meta_key( $field_name ) {
		$names = array(
			'current_teams' => 'sp_current_team',
			'events' => 'sp_event',
			'lists' => 'sp_list',
			'past_teams' => 'sp_past_team',
			'performance' => 'sp_players',
			'players' => 'sp_player',
			'tables' => 'sp_table',
			'teams' => 'sp_team',
		);
		
		if ( array_key_exists( $field_name, $names ) ) {
			$field_name = $names[ $field_name ];
		} else {
			$field_name = 'sp_' . $field_name;
		}
		
		return $field_name;
	}

	/**
	 * Convert string to integer if it contains only digits
	 */
	public static function string_to_int( &$value ) {
		if ( ctype_digit( $value ) ) {
			$value = intval( $value );
		}
	}

	/**
	 * Add extra arguments
	 */
	public static function add_args( $args = array() ) {
		$args['show_in_rest'] = true;
		$args['rest_controller_class'] = 'SP_REST_Posts_Controller';
		return $args;
	}

	/**
	 * Add extra league arguments
	 */
	public static function add_league_args( $args = array() ) {
		$args['rest_base'] = 'leagues';
		return $args;
	}

	/**
	 * Add extra season arguments
	 */
	public static function add_season_args( $args = array() ) {
		$args['rest_base'] = 'seasons';
		return $args;
	}

	/**
	 * Add extra venue arguments
	 */
	public static function add_venue_args( $args = array() ) {
		$args['rest_base'] = 'venues';
		return $args;
	}

	/**
	 * Add extra position arguments
	 */
	public static function add_position_args( $args = array() ) {
		$args['rest_base'] = 'positions';
		return $args;
	}

	/**
	 * Add extra role arguments
	 */
	public static function add_role_args( $args = array() ) {
		$args['rest_base'] = 'roles';
		return $args;
	}

	/**
	 * Add extra event arguments
	 */
	public static function add_event_args( $args = array() ) {
		$args['rest_base'] = 'events';
		return $args;
	}

	/**
	 * Add extra team arguments
	 */
	public static function add_team_args( $args = array() ) {
		$args['rest_base'] = 'teams';
		return $args;
	}

	/**
	 * Add extra player arguments
	 */
	public static function add_player_args( $args = array() ) {
		$args['rest_base'] = 'players';
		return $args;
	}

	/**
	 * Add extra staff arguments
	 */
	public static function add_staff_args( $args = array() ) {
		$args['rest_base'] = 'staff';
		return $args;
	}
}

endif;

return new SP_REST_API();
