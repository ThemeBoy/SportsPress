<?php

if ( class_exists( 'WP_REST_Posts_Controller' ) ) {
	class SP_REST_Posts_Controller extends WP_REST_Posts_Controller {
		public function __construct( $post_type ) {
			parent::__construct( $post_type );
			$this->namespace = 'sportspress/v2';
		}

    public function check_read_permission( $post ) {
      if ( 'sp_event' === $post->post_type ) {
        if ( in_array( $post->post_status, array( 'publish', 'future' ) ) || current_user_can( 'read_post', $post->ID ) ) {
          return true;
        }
      } else {
        return WP_REST_Posts_Controller::check_read_permission( $post );
      }
    }
  }
}
