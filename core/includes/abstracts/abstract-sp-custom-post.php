<?php
/**
 * Abstract Custom Post Class
 *
 * The SportsPress custom post class handles individual post data.
 *
 * @class 		SP_Custom_Post
 * @version		0.8
 * @package		SportsPress/Abstracts
 * @category	Abstract Class
 * @author 		ThemeBoy
 */
abstract class SP_Custom_Post {

	/** @var int The post ID. */
	public $ID;

	/** @var object The actual post object. */
	public $post;

	/**
	 * __construct function.
	 *
	 * @access public
	 * @param mixed $post
	 */
	public function __construct( $post ) {
		if ( $post instanceof WP_Post || $post instanceof SP_Custom_Post ):
			$this->ID   = absint( $post->ID );
			$this->post = $post;
		else:
			$this->ID  = absint( $post );
			$this->post = get_post( $this->ID );
		endif;
	}

	/**
	 * __isset function.
	 *
	 * @access public
	 * @param mixed $key
	 * @return bool
	 */
	public function __isset( $key ) {
		return metadata_exists( 'post', $this->ID, 'sp_' . $key );
	}

	/**
	 * __get function.
	 *
	 * @access public
	 * @param mixed $key
	 * @return bool
	 */
	public function __get( $key ) {
		if ( ! isset( $key ) ):
			return $this->post;
		else:
			$value = get_post_meta( $this->ID, 'sp_' . $key, true );
		endif;

		return $value;
	}

	/**
	 * Get the post data.
	 *
	 * @access public
	 * @return object
	 */
	public function get_post_data() {
		return $this->post;
	}
}
