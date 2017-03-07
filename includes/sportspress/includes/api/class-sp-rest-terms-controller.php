<?php

if ( class_exists( 'WP_REST_Terms_Controller' ) ) {
	class SP_REST_Terms_Controller extends WP_REST_Terms_Controller {
		public function __construct( $taxonomy ) {
			parent::__construct( $taxonomy );
			$this->namespace = 'sportspress/v2';
		}
	}
}
