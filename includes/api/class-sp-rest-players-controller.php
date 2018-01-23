<?php
/**
 * REST API Players controller
 *
 * Handles requests to the /players endpoint.
 *
 * Adapted from code in WooCommerce (Copyright (c) 2017, Automattic).
 *
 * @class 		SP_REST_Players_Controller
 * @version     2.5.5
 * @package		SportsPress/API
 * @category	API
 * @author 		WooThemes
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class SP_REST_Players_Controller extends SP_REST_Posts_Controller {

    /**
     * Route base.
     *
     * @var string
     */
    protected $rest_base = 'players';

    /**
     * Post type.
     *
     * @var string
     */
    protected $post_type = 'sp_player';

    public function __construct() {
        parent::__construct( $this->post_type );
        $this->namespace = 'sportspress/v2';
    }

    /**
     * Register the routes for players.
     */
    public function register_routes() {
        register_rest_route( $this->namespace, '/' . $this->rest_base, array(
            array(
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => array( $this, 'get_items' ),
                'args'                => $this->get_collection_params(),
            )
        ) );
    }

    /**
     * Get a collection of posts.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function get_items( $request ) {
        $query_args    = $this->prepare_objects_query( $request );
        $query_results = $this->get_objects( $query_args );

        $objects = array();
        foreach ( $query_results['objects'] as $object ) {
            $data = $this->prepare_item_for_response( $object, $request );
            $objects[] = $this->prepare_response_for_collection( $data );
        }

        $page      = (int) $query_args['paged'];
        $max_pages = $query_results['pages'];

        $response = rest_ensure_response( $objects );
        $response->header( 'X-WP-Total', $query_results['total'] );
        $response->header( 'X-WP-TotalPages', (int) $max_pages );

        $base = add_query_arg( $request->get_query_params(), rest_url( sprintf( '/%s/%s', $this->namespace, $this->rest_base ) ) );

        if ( $page > 1 ) {
            $prev_page = $page - 1;
            if ( $prev_page > $max_pages ) {
                $prev_page = $max_pages;
            }
            $prev_link = add_query_arg( 'page', $prev_page, $base );
            $response->link_header( 'prev', $prev_link );
        }
        if ( $max_pages > $page ) {
            $next_page = $page + 1;
            $next_link = add_query_arg( 'page', $next_page, $base );
            $response->link_header( 'next', $next_link );
        }

        return $response;
    }

    /**
     * Prepare objects query.
     *
     * @since  2.5
     * @param  WP_REST_Request $request Full details about the request.
     * @return array
     */
    protected function prepare_objects_query( $request ) {
        $args = parent::prepare_objects_query( $request );

        //Filter players by league
        if ( ! empty( $request['league'] ) ) {
            $args['tax_query'][] = array(
                'taxonomy' => 'sp_league',
                'field'    => 'name',
                'terms'    => $request['league'],
            );
        }

        //Filter players by season
        if ( ! empty( $request['season'] ) ) {
            $args['tax_query'][] = array(
                'taxonomy' => 'sp_season',
                'field'    => 'name',
                'terms'    => $request['season'],
            );
        }

        // Filter players by team id
        if ( ! empty( $request['team_id'] ) ) {
            $args['meta_query'][] =  array(
                'key'   => 'sp_team',
                'value' => $request['team_id']
            );
        }

        // Filter players by current team id
        if ( ! empty( $request['current_team_id'] ) ) {
            $args['meta_query'][] = array(
                'key'   => 'sp_current_team',
                'value' => $request['current_team_id']
            );
        }

        // Filter players by past team id
        if ( ! empty( $request['past_team_id'] ) ) {
            $args['meta_query'][] = array(
                'key'   => 'sp_past_team',
                'value' => $request['past_team_id']
            );
        }

        return $this->prepare_items_query( $args, $request );
    }

    /**
     * Get objects.
     *
     * @since  3.0.0
     * @param  array $query_args Query args.
     * @return array
     */
    protected function get_objects( $query_args ) {
        $query  = new WP_Query();
        $result = $query->query( $query_args );

        $total_posts = $query->found_posts;
        if ( $total_posts < 1 ) {
            // Out-of-bounds, run the query again without LIMIT for total count.
            unset( $query_args['paged'] );
            $count_query = new WP_Query();
            $count_query->query( $query_args );
            $total_posts = $count_query->found_posts;
        }

        return array(
            'objects' => $result,
            'total'   => (int) $total_posts,
            'pages'   => (int) ceil( $total_posts / (int) $query->query_vars['posts_per_page'] ),
        );
    }

}
