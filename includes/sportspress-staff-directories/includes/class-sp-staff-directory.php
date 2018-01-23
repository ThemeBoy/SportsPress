<?php
/**
 * Staff Directory Class
 *
 * The SportsPress staff directory class handles individual staff directory data.
 *
 * @class 		SP_Staff_Directory
 * @version   2.5.1
 * @package		SportsPress_Staff_Directories
 * @category	Class
 * @author 		ThemeBoy
 */
class SP_Staff_Directory extends SP_Custom_Post {

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
		parent::__construct( $post );
		$this->columns = $this->__get( 'columns' );
		$this->orderby = $this->__get( 'orderby' );
		$this->order = $this->__get( 'order' );

		if ( is_array( $this->columns) ) {
			$this->columns = array_filter( $this->columns );
		} else {
			$this->columns = array( 'phone', 'email' );
		}

		if ( ! $this->orderby )
			$this->orderby = 'menu_order';

		if ( ! $this->order )
			$this->order = 'ASC';
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
				$era = get_post_meta( $this->ID, 'sp_era', true );

				if ( ! isset( $league_ids ) ) $league_ids = array();
				if ( $leagues ):
					foreach( $leagues as $league ):
						$league_ids[] = $league->term_id;
					endforeach;
				endif;
				$league_ids = sp_add_auto_term( $league_ids, $this->ID, 'sp_league' );
				if ( ! empty( $league_ids ) ):
					$args['tax_query'][] = array(
						'taxonomy' => 'sp_league',
						'field' => 'term_id',
						'terms' => $league_ids
					);
				endif;

				if ( ! isset( $season_ids ) ) $season_ids = array();
				if ( $seasons ):
					foreach( $seasons as $season ):
						$season_ids[] = $season->term_id;
					endforeach;
				endif;
				$season_ids = sp_add_auto_term( $season_ids, $this->ID, 'sp_season' );
				if ( ! empty( $season_ids ) ):
					$args['tax_query'][] = array(
						'taxonomy' => 'sp_season',
						'field' => 'term_id',
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
						'field' => 'term_id',
						'terms' => $role_ids
					);
				endif;

				if ( $team ):
					$team_key = 'sp_team';
					switch ( $era ):
						case 'current':
							$team_key = 'sp_current_team';
							break;
						case 'past':
							$team_key = 'sp_past_team';
							break;
					endswitch;
					$args['meta_query'] = array(
						array(
							'key' => $team_key,
							'value' => $team
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

				$staff_roles = $staff_object->roles();
				if ( ! empty( $staff_roles ) ):
					$staff_roles = wp_list_pluck( $staff_roles, 'name' );
					$staff['role'] = implode( '<span class="sp-staff-role-delimiter">/</span>', $staff_roles );
				endif;

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
			'role' => __( 'Job', 'sportspress' ),
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

			$data[0] = array_merge( array( 'name' => __( 'Name', 'sportspress' ) ), $labels );
			return $data;
		endif;
	}
}
