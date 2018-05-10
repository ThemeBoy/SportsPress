<?php

if ( class_exists( 'WP_REST_Posts_Controller' ) ) {
	class SP_REST_Posts_Controller extends WP_REST_Posts_Controller {
		public function __construct( $post_type ) {
			parent::__construct( $post_type );
			$this->namespace = 'sportspress/v2';
		}
	}
}
