<?php
/**
 * Calendar Class
 *
 * The SportsPress calendar class handles individual calendar data.
 *
 * @class 		SP_Calendar
 * @version     1.9
 * @package		SportsPress/Classes
 * @category	Class
 * @author 		ThemeBoy
 */

class SP_Calendar extends SP_Custom_Post {

	/** @var string The events status. */
	public $status;

	/** @var string The date filter for events. */
	public $date;

	/** @var string The events order. */
	public $order;

	/** @var string The date to range from. */
	public $from;

	/** @var string The date to range to. */
	public $to;

	/** @var int The competition ID. */
	public $league;

	/** @var int The season ID. */
	public $season;

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

		$this->status = $this->__get( 'status' );
		$this->date = $this->__get( 'date' );
		$this->order = $this->__get( 'order' );

		if ( ! $this->status )
			$this->status = 'any';

		if ( ! $this->date )
			$this->date = 0;

		if ( ! $this->order )
			$this->order = 'ASC';

		if ( ! $this->from )
			$this->from = get_post_meta( $this->ID, 'sp_date_from', true );

		if ( ! $this->to )
			$this->to = get_post_meta( $this->ID, 'sp_date_to', true );
	}

	/**
	 * Returns formatted data
	 *
	 * @access public
	 * @return array
	 */
	public function data() {
		global $pagenow;

		$args = array(
			'post_type' => 'sp_event',
			'numberposts' => -1,
			'posts_per_page' => -1,
			'orderby' => 'date',
			'order' => $this->order,
			'post_status' => $this->status,
			'tax_query' => array(
				'relation' => 'AND'
			),
		);

		if ( $this->date !== 0 ):
			if ( $this->date == 'w' ):
				$args['year'] = date('Y');
				$args['w'] = date('W');
			elseif ( $this->date == 'day' ):
				$args['year'] = date('Y');
				$args['day'] = date('j');
				$args['monthnum'] = date('n');
			elseif ( $this->date == 'range' ):
				add_filter( 'posts_where', array( $this, 'range' ) );
			endif;
		endif;

		if ( $this->league ):
			$league_ids = array( $this->league );
		endif;

		if ( $this->season ):
			$season_ids = array( $this->season );
		endif;

		if ( $pagenow != 'post-new.php' ):
			if ( $this->ID ):
				$leagues = get_the_terms( $this->ID, 'sp_league' );
				$seasons = get_the_terms( $this->ID, 'sp_season' );
				$venues = get_the_terms( $this->ID, 'sp_venue' );
				$teams = array_filter( get_post_meta( $this->ID, 'sp_team', false ) );
				$table = get_post_meta( $this->ID, 'sp_table', true );

				if ( ! isset( $league_ids ) && $leagues ):
					$league_ids = array();
					foreach( $leagues as $league ):
						$league_ids[] = $league->term_id;
					endforeach;
				endif;

				if ( isset( $league_ids ) ) {
					$args['tax_query'][] = array(
						'taxonomy' => 'sp_league',
						'field' => 'id',
						'terms' => $league_ids
					);
				}

				if ( ! isset( $season_ids ) && $seasons ):
					$season_ids = array();
					foreach( $seasons as $season ):
						$season_ids[] = $season->term_id;
					endforeach;
				endif;

				if ( isset( $season_ids ) ) {
					$args['tax_query'][] = array(
						'taxonomy' => 'sp_season',
						'field' => 'id',
						'terms' => $season_ids
					);
				}

				if ( $venues ):
					$venue_ids = array();
					foreach( $venues as $venue ):
						$venue_ids[] = $venue->term_id;
					endforeach;
					$args['tax_query'][] = array(
						'taxonomy' => 'sp_venue',
						'field' => 'id',
						'terms' => $venue_ids
					);
				endif;

				if ( ! empty( $teams ) ):
					$args['meta_query']	= array(
						array(
							'key' => 'sp_team',
							'value' => $teams,
							'compare' => 'IN',
						),
					);
				endif;

			endif;
			
			$events = get_posts( $args );

		else:
			$events = null;
		endif;

		remove_filter( 'posts_where', array( $this, 'range' ) );

		return $events;
	}

	public function range( $where = '' ) {
		$to = new DateTime( $this->to );
		$to->modify( '+1 day' );
		$where .= " AND post_date BETWEEN '" . $this->from . "' AND '" . $to->format( 'Y-m-d' ) . "'";
		return $where;
	}
}
