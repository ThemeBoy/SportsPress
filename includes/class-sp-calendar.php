<?php
/**
 * Calendar Class
 *
 * The SportsPress calendar class handles individual calendar data.
 * Props @_drg_ for adjustments to range and timezone handling.
 * https://wordpress.org/support/topic/suggestion-for-schedule-list-range-option/
 * https://wordpress.org/support/topic/timezone-issues-with-schedule-calendar-list/
 *
 * @class 		SP_Calendar
 * @version   2.5.5
 * @package		SportsPress/Classes
 * @category	Class
 * @author 		ThemeBoy
 */

class SP_Calendar extends SP_Secondary_Post {

	/** @var string The events status. */
	public $status;

	/** @var string The events order. */
	public $order;

	/** @var string The events orderby. */
	public $orderby;

	/** @var string The match day. */
	public $day;

	/** @var int The league ID. */
	public $league;

	/** @var int The season ID. */
	public $season;

	/** @var int The venue ID. */
	public $venue;

	/** @var int The team ID. */
	public $team;

	/** @var int The player ID. */
	public $player;

	/** @var int Number of events. */
	public $number;
	
	/** @var int The event ID. */
	public $event;

	/**
	 * __construct function.
	 *
	 * @access public
	 * @param mixed $post
	 */
	public function __construct( $post ) {
		if ( $post instanceof WP_Post || $post instanceof SP_Secondary_Post ):
			$this->ID   = absint( $post->ID );
			$this->post = $post;
		else:
			$this->ID  = absint( $post );
			$this->post = get_post( $this->ID );
		endif;

		$this->status = $this->__get( 'status' );
		$this->date = $this->__get( 'date' );
		$this->orderby = $this->__get( 'orderby' );
		$this->order = $this->__get( 'order' );
		$this->number = $this->__get( 'number' );

		if ( ! $this->status )
			$this->status = 'any';

		if ( ! $this->date )
			$this->date = 0;

		if ( ! $this->order )
			$this->order = 'ASC';

		if ( ! $this->orderby )
			$this->orderby = 'post_date';

		if ( 'range' == $this->date ) {

			$this->relative = get_post_meta( $this->ID, 'sp_date_relative', true );

			if ( $this->relative ) {

				$this->past = get_post_meta( $this->ID, 'sp_date_past', true );
				$this->future = get_post_meta( $this->ID, 'sp_date_future', true );

			} else {

				$this->from = get_post_meta( $this->ID, 'sp_date_from', true );
				$this->to = get_post_meta( $this->ID, 'sp_date_to', true );

			}

		}

		if ( ! $this->day )
			$this->day = get_post_meta( $this->ID, 'sp_day', true );

		if ( ! $this->number )
			$this->number = -1;
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
			'posts_per_page' => $this->number,
			'orderby' => $this->orderby,
			'order' => $this->order,
			'post_status' => $this->status,
			'meta_query' => array(
				'relation' => 'AND'
			),
			'tax_query' => array(
				'relation' => 'AND'
			),
		);

		if ( $this->date !== 0 ):
			switch ( $this->date ):
				case '-day':
					$date = new DateTime( date_i18n('Y-m-d') );
			    $date->modify( '-1 day' );
					$args['year'] = $date->format('Y');
					$args['day'] = $date->format('j');
					$args['monthnum'] = $date->format('n');
					break;
				case 'day':
					$args['year'] = date_i18n('Y');
					$args['day'] = date_i18n('j');
					$args['monthnum'] = date_i18n('n');
					break;
				case '+day':
					$date = new DateTime( date_i18n('Y-m-d') );
			    $date->modify( '+1 day' );
					$args['year'] = $date->format('Y');
					$args['day'] = $date->format('j');
					$args['monthnum'] = $date->format('n');
					break;
				case '-w':
					$date = new DateTime( date_i18n('Y-m-d') );
			    $date->modify( '-1 week' );
					$args['year'] = $date->format('Y');
					$args['w'] = $date->format('W');
					break;
				case 'w':
					$args['year'] = date_i18n('Y');
					$args['w'] = date_i18n('W');
					break;
				case '+w':
					$date = new DateTime( date_i18n('Y-m-d') );
			    $date->modify( '+1 week' );
					$args['year'] = $date->format('Y');
					$args['w'] = $date->format('W');
					break;
				case 'range':
					if ( $this->relative ):
						add_filter( 'posts_where', array( $this, 'relative' ) );
					else:
						add_filter( 'posts_where', array( $this, 'range' ) );
					endif;
					break;
			endswitch;
		endif;

		if ( $this->league ):
			$league_ids = array( $this->league );
		endif;

		if ( $this->season ):
			$season_ids = array( $this->season );
		endif;

		if ( $this->venue ):
			$venue_ids = array( $this->venue );
		endif;

		if ( $this->team ):
			$args['meta_query'][] = array(
				'key' => 'sp_team',
				'value' => array( $this->team ),
				'compare' => 'IN',
			);
		endif;

		if ( $this->player ):
			$args['meta_query'][] = array(
				'key' => 'sp_player',
				'value' => array( $this->player ),
				'compare' => 'IN',
			);
		endif;

		if ( $this->day ):
			$args['meta_query'][] = array(
				'key' => 'sp_day',
				'value' => $this->day,
			);
		endif;

		if ( 'day' == $this->orderby ):
			$args['orderby'] = 'meta_value_num';
			$args['meta_key'] = 'sp_day';
		endif;

		if ( $pagenow != 'post-new.php' ):
			if ( $this->ID ):
				$leagues = get_the_terms( $this->ID, 'sp_league' );
				$seasons = get_the_terms( $this->ID, 'sp_season' );
				$venues = get_the_terms( $this->ID, 'sp_venue' );
				$teams = array_filter( get_post_meta( $this->ID, 'sp_team', false ) );
				$table = get_post_meta( $this->ID, 'sp_table', true );

				if ( ! isset( $league_ids ) ) $league_ids = array();
				if ( empty( $league_ids ) && $leagues ):
					foreach( $leagues as $league ):
						$league_ids[] = $league->term_id;
					endforeach;
				endif;
				$league_ids = sp_add_auto_term( $league_ids, $this->ID, 'sp_league' );
				if ( empty( $league_ids ) ) unset( $league_ids );
				
				if ( ! isset( $season_ids ) ) $season_ids = array();
				if ( empty( $season_ids ) && $seasons ):
					foreach( $seasons as $season ):
						$season_ids[] = $season->term_id;
					endforeach;
				endif;
				$season_ids = sp_add_auto_term( $season_ids, $this->ID, 'sp_season' );
				if ( empty( $season_ids ) ) unset( $season_ids );

				if ( ! isset( $venue_ids ) && $venues ):
					$venue_ids = array();
					foreach( $venues as $venue ):
						$venue_ids[] = $venue->term_id;
					endforeach;
				endif;
			endif;

			if ( isset( $league_ids ) ) {
				$args['tax_query'][] = array(
					'taxonomy' => 'sp_league',
					'field' => 'term_id',
					'terms' => $league_ids
				);
			}

			if ( isset( $season_ids ) ) {
				$args['tax_query'][] = array(
					'taxonomy' => 'sp_season',
					'field' => 'term_id',
					'terms' => $season_ids
				);
			}

			if ( isset( $venue_ids ) ) {
				$args['tax_query'][] = array(
					'taxonomy' => 'sp_venue',
					'field' => 'term_id',
					'terms' => $venue_ids
				);
			}

			if ( ! empty( $teams ) ) {
				$args['meta_query']	= array(
					array(
						'key' => 'sp_team',
						'value' => $teams,
						'compare' => 'IN',
					),
				);
			}
		
			if ( $this->event) {
				$args['p'] = $this->event;
			}

			if ( 'auto' === $this->date && 'any' === $this->status ) {
				$args['post_status'] = 'publish';
				$args['order'] = 'DESC';
				$args['posts_per_page'] = ceil( $this->number / 2 );
				$results = get_posts( $args );
				$results = array_reverse( $results, true );

				$args['post_status'] = 'future';
				$args['order'] = 'ASC';
				$args['posts_per_page'] = floor( $this->number / 2 );
				$fixtures = get_posts( $args );

				$events = array_merge_recursive( $results, $fixtures );
			} else {
				$events = get_posts( $args );
			}

		else:
			$events = null;
		endif;

		// Remove any calendar selection filters
		remove_filter( 'posts_where', array( $this, 'range' ) );
		remove_filter( 'posts_where', array( $this, 'relative' ) );

		return $events;
	}
}
