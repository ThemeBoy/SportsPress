<?php

if ( class_exists( 'WP_REST_Posts_Controller' ) ) {
	class SP_REST_Posts_Controller extends WP_REST_Posts_Controller {
		public function __construct( $post_type ) {
			parent::__construct( $post_type );
			$this->namespace = 'sportspress/v2';
		}

        /**
         * Prepare objects query.
         *
         * @since  2.5.5
         * @param  WP_REST_Request $request Full details about the request.
         * @return array
         */
        protected function prepare_objects_query( $request ) {
            $args                        = array();
            $args['offset']              = $request['offset'];
            $args['order']               = $request['order'];
            $args['orderby']             = $request['orderby'];
            $args['paged']               = $request['page'];
            $args['post__in']            = $request['include'];
            $args['post__not_in']        = $request['exclude'];
            $args['posts_per_page']      = $request['per_page'];
            $args['name']                = $request['slug'];
            $args['post_parent__in']     = $request['parent'];
            $args['post_parent__not_in'] = $request['parent_exclude'];
            $args['s']                   = $request['search'];

            if ( 'date' === $args['orderby'] ) {
                $args['orderby'] = 'date ID';
            }

            $args['date_query'] = array();
            // Set before into date query. Date query must be specified as an array of an array.
            if ( isset( $request['before'] ) ) {
                $args['date_query'][0]['before'] = $request['before'];
            }

            // Set after into date query. Date query must be specified as an array of an array.
            if ( isset( $request['after'] ) ) {
                $args['date_query'][0]['after'] = $request['after'];
            }

            // Force the post_type argument, since it's not a user input variable.
            $args['post_type'] = $this->post_type;

            /**
             * Filter the query arguments for a request.
             *
             * Enables adding extra arguments or setting defaults for a post
             * collection request.
             *
             * @param array           $args    Key value array of query var to query value.
             * @param WP_REST_Request $request The request used.
             */
            $args = apply_filters( "sportspress_rest_{$this->post_type}_object_query", $args, $request );

            return $this->prepare_items_query( $args, $request );
        }
	}
}
