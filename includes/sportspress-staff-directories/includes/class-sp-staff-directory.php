<?php
/**
 * Staff Directory Class
 *
 * The SportsPress staff directory class handles individual staff directory data.
 *
 * @class 		SP_Staff_Directory
 * @version		1.4
 * @package		SportsPress_Staff_Directories
 * @category	Class
 * @author 		ThemeBoy
 */
class SP_Staff_Directory {

	/** @var int The post ID. */
	public $ID;

	/** @var object The actual post object. */
	public $post;

	/** @var array The columns array. */
	public $columns;

	/** @var string The value to order staff by. */
	public $orderby;

	/** @var string The staff order. */
	public $order;

	/**
	 * Constructor
	 */
	public function __construct( $post ) {
		if ( $post instanceof WP_Post || $post instanceof SP_Custom_Post ):
			$this->ID   = absint( $post->ID );
			$this->post = $post;
		else:
			$this->ID  = absint( $post );
			$this->post = get_post( $this->ID );
		endif;
		
		$this->columns = $this->__get( 'columns' );
		$this->orderby = $this->__get( 'orderby' );
		$this->order = $this->__get( 'order' );

		if ( is_array( $this->columns) ) $this->columns = array_filter( $this->columns );

		if ( ! $this->orderby )
			$this->orderby = 'menu_order';

		if ( ! $this->order )
			$this->order = 'ASC';
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

	/**
	 * Returns formatted data
	 *
	 * @access public
	 * @param bool $admin
	 * @return array
	 */
	public function data( $admin = false ) {
		global $pagenow;

		$staffs = get_post_meta( $this->ID, 'sp_staffs', true );

		$args = array(
			'post_type' => 'sp_staff',
			'numberposts' => -1,
			'posts_per_page' => -1,
			'orderby' => $this->orderby,
			'order' => $this->order,
			'tax_query' => array(
				'relation' => 'AND'
			),
		);

		if ( $pagenow != 'post-new.php' ):
			if ( $this->ID ):
				$leagues = get_the_terms( $this->ID, 'sp_league' );
				$seasons = get_the_terms( $this->ID, 'sp_season' );
				$roles = get_the_terms( $this->ID, 'sp_role' );
				$team = get_post_meta( $this->ID, 'sp_team', true );

				if ( $leagues ):
					$league_ids = array();
					foreach( $leagues as $league ):
						$league_ids[] = $league->term_id;
					endforeach;
					$args['tax_query'][] = array(
						'taxonomy' => 'sp_league',
						'field' => 'id',
						'terms' => $league_ids
					);
				endif;

				if ( $seasons ):
					$season_ids = array();
					foreach( $seasons as $season ):
						$season_ids[] = $season->term_id;
					endforeach;
					$args['tax_query'][] = array(
						'taxonomy' => 'sp_season',
						'field' => 'id',
						'terms' => $season_ids
					);
				endif;

				if ( $roles ):
					$role_ids = array();
					foreach( $roles as $role ):
						$role_ids[] = $role->term_id;
					endforeach;
					$args['tax_query'][] = array(
						'taxonomy' => 'sp_role',
						'field' => 'id',
						'terms' => $role_ids
					);
				endif;

				if ( $team ):
					$args['meta_query']	= array(
						array(
							'key' => 'sp_team',
							'value' => $team,
						),
					);
				endif;
			endif;
			
			$posts = get_posts( $args );
		else:
			$posts = null;
		endif;

		if ( $posts === null ):
			$data = null;
		else:
			$data = array();
			foreach ( $posts as $post ):
				$staff = array( 'name' => get_the_title( $post->ID ) );
				
				$staff_object = new SP_Staff( $post->ID );
				$role = $staff_object->role();
				if ( ! empty( $role ) ) $staff['role'] = $role->name;
				$phone = get_post_meta( $post->ID, 'sp_phone', true );
				if ( ! empty( $phone ) ) $staff['phone'] = $phone;
				$email = get_post_meta( $post->ID, 'sp_email', true );
				if ( ! empty( $email ) ) $staff['email'] = $email;
				
				$data[ $post->ID ] = $staff;
			endforeach;

			// Sort them by manual order
			if ( is_array( $staffs ) ):
				$prepend = array();
				foreach ( $staffs as $staff ):
					if ( array_key_exists( $staff, $data ) ):
						$prepend[ $staff ] = $data[ $staff ];
						unset( $data[ $staff ] );
					endif;
				endforeach;
				$data = $prepend + $data;
			endif;
		endif;

		$labels = apply_filters( 'sportspress_directory_labels', array(
			'phone' => __( 'Phone', 'sportspress' ),
			'email' => __( 'Email', 'sportspress' ),
		));
		
		if ( $admin ):
			return array( $labels, $this->columns, $data );
		else:
			if ( ! is_array( $this->columns ) )
				$this->columns = array();
			foreach ( $labels as $key => $label ):
				if ( ! in_array( $key, $this->columns ) ):
					unset( $labels[ $key ] );
				endif;
			endforeach;

			$data[0] = array_merge( array( 'role' => __( 'Job', 'sportspress' ), 'name' => __( 'Name', 'sportspress' ) ), $labels );
			return $data;
		endif;
	}
}
