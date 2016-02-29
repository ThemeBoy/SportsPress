<?php

class SP_REST_Teams_Controller extends WP_REST_Posts_Controller {

	/**
	 * @var string
	 */
	public $namespace = 'sportspress/v2';

	/**
	 * @var string
	 */
	public $route = 'teams';

	public function __construct() {
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}

	/**
	 * Register the routes for the objects of the controller.
	 */
	public function register_routes() {
		register_rest_route( $this->namespace, '/' . $this->route, array(
			array(
				'methods'		 => WP_REST_Server::READABLE,
				'callback'		=> array( $this, 'get_items' ),
				'permission_callback' => array( $this, 'get_items_permissions_check' ),
				'args'			=> array(),
			),
			/*
			array(
				'methods'		 => WP_REST_Server::CREATABLE,
				'callback'		=> array( $this, 'create_item' ),
				'permission_callback' => array( $this, 'create_item_permissions_check' ),
				'args'			=> $this->get_endpoint_args_for_item_schema( true ),
			),
			*/
		) );
		
		register_rest_route( $this->namespace, '/' . $this->route . '/(?P<id>[\d]+)', array(
			array(
				'methods'		 => WP_REST_Server::READABLE,
				'callback'		=> array( $this, 'get_item' ),
				'permission_callback' => array( $this, 'get_item_permissions_check' ),
				'args'			=> array(
					'context'		  => array(
						'default'	  => 'view',
					),
				),
			),
			/*
			array(
				'methods'		 => WP_REST_Server::EDITABLE,
				'callback'		=> array( $this, 'update_item' ),
				'permission_callback' => array( $this, 'update_item_permissions_check' ),
				'args'			=> $this->get_endpoint_args_for_item_schema( false ),
			),
			array(
				'methods'  => WP_REST_Server::DELETABLE,
				'callback' => array( $this, 'delete_item' ),
				'permission_callback' => array( $this, 'delete_item_permissions_check' ),
				'args'	 => array(
					'force'	=> array(
						'default'	  => false,
					),
				),
			),
			*/
		) );
		register_rest_route( $this->namespace, '/' . $this->route . '/schema', array(
			'methods'		 => WP_REST_Server::READABLE,
			'callback'		=> array( $this, 'get_public_item_schema' ),
		) );
	}

	/**
	 * Get a collection of items
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 * @return WP_Error|WP_REST_Response
	 */
	public function get_items( $request ) {
		$args = array(
			'post_type' => 'sp_team',
			'posts_per_page' => 500,
		);
		$items = get_posts( $args );
		$data = array();
		foreach( $items as $item ) {
			$itemdata = $this->prepare_item_for_response( $item, $request );
			$data[] = $this->prepare_response_for_collection( $itemdata );
		}

		return new WP_REST_Response( $data, 200 );
	}

	/**
	 * Get one item from the collection
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 * @return WP_Error|WP_REST_Response
	 */
	public function get_item( $request ) {
		//get parameters from request
		$params = $request->get_params();
		$item = get_post( $params['id'] );//do a query, call another class, etc
		$data = $this->prepare_item_for_response( $item, $request );

		//return a response or error based on some conditional
		if ( 1 == 1 ) {
			return new WP_REST_Response( $data, 200 );
		}else{
			return new WP_Error( 'code', __( 'message', 'text-domain' ) );
		}
	}

	/**
	 * Create one item from the collection
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 * @return WP_Error|WP_REST_Request
	 */
	public function create_item( $request ) {

		$item = $this->prepare_item_for_database( $request );

		if ( function_exists( 'slug_some_function_to_create_item')  ) {
			$data = slug_some_function_to_create_item( $item );
			if ( is_array( $data ) ) {
				return new WP_REST_Response( $data, 200 );
			}
		}

		return new WP_Error( 'cant-create', __( 'message', 'text-domain'), array( 'status' => 500 ) );


	}

	/**
	 * Update one item from the collection
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 * @return WP_Error|WP_REST_Request
	 */
	public function update_item( $request ) {
		$item = $this->prepare_item_for_database( $request );

		if ( function_exists( 'slug_some_function_to_update_item')  ) {
			$data = slug_some_function_to_update_item( $item );
			if ( is_array( $data ) ) {
				return new WP_REST_Response( $data, 200 );
			}
		}

		return new WP_Error( 'cant-update', __( 'message', 'text-domain'), array( 'status' => 500 ) );

	}

	/**
	 * Delete one item from the collection
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 * @return WP_Error|WP_REST_Request
	 */
	public function delete_item( $request ) {
		$item = $this->prepare_item_for_database( $request );

		if ( function_exists( 'slug_some_function_to_delete_item')  ) {
			$deleted = slug_some_function_to_delete_item( $item );
			if (  $deleted  ) {
				return new WP_REST_Response( true, 200 );
			}
		}

		return new WP_Error( 'cant-delete', __( 'message', 'text-domain'), array( 'status' => 500 ) );
	}

	/**
	 * Check if a given request has access to get items
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 * @return WP_Error|bool
	 */
	public function get_items_permissions_check( $request ) {
		return true;
		//return current_user_can( 'edit_something' );
	}

	/**
	 * Check if a given request has access to get a specific item
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 * @return WP_Error|bool
	 */
	public function get_item_permissions_check( $request ) {
		return $this->get_items_permissions_check( $request );
	}

	/**
	 * Check if a given request has access to create items
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 * @return WP_Error|bool
	 */
	public function create_item_permissions_check( $request ) {
		return current_user_can( 'edit_something' );
	}

	/**
	 * Check if a given request has access to update a specific item
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 * @return WP_Error|bool
	 */
	public function update_item_permissions_check( $request ) {
		return $this->create_item_permissions_check( $request );
	}

	/**
	 * Check if a given request has access to delete a specific item
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 * @return WP_Error|bool
	 */
	public function delete_item_permissions_check( $request ) {
		return $this->create_item_permissions_check( $request );
	}

	/**
	 * Prepare the item for create or update operation
	 *
	 * @param WP_REST_Request $request Request object
	 * @return WP_Error|object $prepared_item
	 */
	protected function prepare_item_for_database( $request ) {
		return array();
	}

	/**
	 * Prepare the item for the REST response
	 *
	 * @param mixed $item WordPress representation of the item.
	 * @param WP_REST_Request $request Request object.
	 * @return mixed
	 */
	public function prepare_item_for_response( $post, $request ) {
		$data = array(
			'id' 				=> $post->ID,
			'guid'		 		=> array(
				'raw'	  		=> $post->guid,
				'rendered' 		=> apply_filters( 'get_the_guid', $post->guid ),
			),
			'slug' 				=> $post->post_name,
			'link'		 		=> get_permalink( $post->ID ),
			'title' 			=> array(
				'raw' 			=> $post->post_title,
				'rendered' 		=> get_the_title( $post->ID ),
			),
			'content' 			=> array(
				'raw'	  		=> $post->post_content,
				'rendered' 		=> apply_filters( 'the_content', $post->post_content ),
			),
			'featured_media' 	=> (int) get_post_thumbnail_id( $post->ID ),
			'abbreviation' 		=> sp_get_abbreviation( $post->ID ),
			'leagues' 			=> sp_get_leagues( $post->ID ),
			'seasons' 			=> sp_get_seasons( $post->ID ),
			'venues' 			=> sp_get_venues( $post->ID ),
		);
		
		return $data;
	}

	/**
	 * Get the query params for collections
	 *
	 * @return array
	 */
	public function get_collection_params() {
		return array(
			'page'				   => array(
				'description'		=> 'Current page of the collection.',
				'type'			   => 'integer',
				'default'			=> 1,
				'sanitize_callback'  => 'absint',
			),
			'per_page'			   => array(
				'description'		=> 'Maximum number of items to be returned in result set.',
				'type'			   => 'integer',
				'default'			=> 10,
				'sanitize_callback'  => 'absint',
			),
			'search'				 => array(
				'description'		=> 'Limit results to those matching a string.',
				'type'			   => 'string',
				'sanitize_callback'  => 'sanitize_text_field',
			),
		);
	}
}

new SP_REST_Teams_Controller();
