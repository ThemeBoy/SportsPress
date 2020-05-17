<?php
/**
 * Countdown
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Templates
 * @version   2.7.1
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$defaults = array(
	'team' => null,
	'calendar' => null,
	'league' => null,
	'season' => null,
	'id' => null,
	'title' => null,
	'live' => get_option( 'sportspress_enable_live_countdowns', 'yes' ) == 'yes' ? true : false,
	'link_events' => get_option( 'sportspress_link_events', 'yes' ) == 'yes' ? true : false,
	'link_teams' => get_option( 'sportspress_link_teams', 'no' ) == 'yes' ? true : false,
	'link_venues' => get_option( 'sportspress_link_venues', 'no' ) == 'yes' ? true : false,
	'show_logos' => get_option( 'sportspress_countdown_show_logos', 'no' ) == 'yes' ? true : false,
	'show_thumbnail' => get_option( 'sportspress_countdown_show_thumbnail', 'no' ) == 'yes' ? true : false,
);

if ( isset( $show_excluded ) && $show_excluded ){
	$excluded_statuses = array();
}else{
	$excluded_statuses = apply_filters( 'sp_countdown_excluded_statuses', array(
		'postponed',
		'cancelled',
	) );
}

if ( isset( $id ) ):
	$post = get_post( $id );
elseif ( $calendar ):
	$calendar = new SP_Calendar( $calendar );
	if ( $team )
		$calendar->team = $team;
	$calendar->status = 'future';
	$calendar->order = 'ASC';
	$data = $calendar->data();

	/**
	 * Exclude postponed or cancelled events.
	 */
	while ( $post = array_shift( $data ) ) {
		$sp_status = get_post_meta($post->ID, 'sp_status', true);
		if( ! in_array( $sp_status, $excluded_statuses ) ) {
			break;
		}
	}
else:
	$args = array();
	if ( isset( $team ) ) {
		$args['meta_query'] = array(
			array(
				'key' => 'sp_team',
				'value' => $team,
			)
		);
	}
	if ( isset( $league ) || isset( $season ) ) {
		$args['tax_query'] = array( 'relation' => 'AND' );
		
		if ( isset( $league ) ) {
			$args['tax_query'][] = array(
				'taxonomy' => 'sp_league',
				'terms' => $league,
			);
		}
		
		if ( isset( $season ) ) {
			$args['tax_query'][] = array(
				'taxonomy' => 'sp_season',
				'terms' => $season,
			);
		}
	}

	/**
	 * Exclude postponed or cancelled events.
	 */
	$args['meta_query'][] = [
		'key' => 'sp_status',
		'compare' => 'NOT IN',
		'value' => $excluded_statuses,
	];

	$post = sp_get_next_event( $args );
endif;

extract( $defaults, EXTR_SKIP );

if ( ! isset( $post ) || ! $post ) return;

if ( $title )
	echo '<h4 class="sp-table-caption">' . $title . '</h4>';

$title = $post->post_title;
if ( $link_events ) $title = '<a href="' . get_post_permalink( $post->ID, false, true ) . '">' . $title . '</a>';
$sp_status = get_post_meta($post->ID, 'sp_status', true);
$statuses = apply_filters( 'sportspress_event_statuses', array(
			'ok' => __( 'On time', 'sportspress' ),
			'tbd' => __( 'TBD', 'sportspress' ),
			'postponed' => __( 'Postponed', 'sportspress' ),
			'cancelled' => __( 'Canceled', 'sportspress' ),
		) );
?>
<div class="sp-template sp-template-countdown">
	<div class="sp-countdown-wrapper">
	<?php 
	if ( $show_thumbnail && has_post_thumbnail( $post ) ) {
	?>
	<div class="event-image sp-event-image">
		<?php echo get_the_post_thumbnail( $post ); ?>
	</div>
	<?php } ?>
		<h3 class="event-name sp-event-name">
			<?php
			if ( $show_logos ) {
				$teams = array_unique( (array) get_post_meta( $post->ID, 'sp_team' ) );
				$i = 0;

				if ( is_array( $teams ) ) {
					foreach ( $teams as $team ) {
						$i++;
						if ( has_post_thumbnail ( $team ) ) {
							if ( $link_teams ) {
								echo '<a class="team-logo logo-' . ( $i % 2 ? 'odd' : 'even' ) . '" href="' . get_post_permalink( $team ) . '" title="' . get_the_title( $team ) . '">' . get_the_post_thumbnail( $team, 'sportspress-fit-icon' ) . '</a>';
							} else {
								echo get_the_post_thumbnail( $team, 'sportspress-fit-icon', array( 'class' => 'team-logo logo-' . ( $i % 2 ? 'odd' : 'even' ) ) );
							}
						}
					}
				}
			}
			?>
			<?php echo $title.' ('.$statuses[ $sp_status ].')'; ?>
		</h3>
		<?php
		if ( isset( $show_date ) && $show_date ):
			?>
			<h5 class="event-venue sp-event-venue event-date sp-event-date">
				<?php
				echo get_the_time( get_option( 'date_format' ), $post );
				?>
			</h5>
			<?php
		endif;
		
		if ( isset( $show_venue ) && $show_venue ):
			$venues = get_the_terms( $post->ID, 'sp_venue' );
			if ( $venues ):
				?>
				<h5 class="event-venue sp-event-venue">
					<?php
					if ( $link_venues ) {
						the_terms( $post->ID, 'sp_venue' );
					} else {
						$venue_names = array();
						foreach ( $venues as $venue ) {
							$venue_names[] = $venue->name;
						}
						echo implode( '/', $venue_names );
					}
					?>
				</h5>
				<?php
			endif;
		endif;

		if ( isset( $show_league ) && $show_league ):
			$leagues = get_the_terms( $post->ID, 'sp_league' );
			if ( $leagues ):
				foreach( $leagues as $league ):
					$term = get_term( $league->term_id, 'sp_league' );
					?>
					<h5 class="event-league sp-event-league"><?php echo $term->name; ?></h5>
					<?php
				endforeach;
			endif;
		endif;

		$now = new DateTime( current_time( 'mysql', 0 ) );
		$date = new DateTime( $post->post_date );
		$interval = date_diff( $now, $date );

		$days = $interval->invert ? 0 : $interval->days;
		$h = $interval->invert ? 0 : $interval->h;
		$i = $interval->invert ? 0 : $interval->i;
		$s = $interval->invert ? 0 : $interval->s;
		?>
		<p class="countdown sp-countdown<?php if ( $days >= 10 ): ?> long-countdown<?php endif; ?>">
			<time datetime="<?php echo $post->post_date; ?>"<?php if ( $live ): ?> data-countdown="<?php echo str_replace( '-', '/', get_gmt_from_date( $post->post_date ) ); ?>"<?php endif; ?>>
				<span><?php echo sprintf( '%02s', $days ); ?> <small><?php _e( 'days', 'sportspress' ); ?></small></span>
				<span><?php echo sprintf( '%02s', $h ); ?> <small><?php _e( 'hrs', 'sportspress' ); ?></small></span>
				<span><?php echo sprintf( '%02s', $i ); ?> <small><?php _e( 'mins', 'sportspress' ); ?></small></span>
				<span><?php echo sprintf( '%02s', $s ); ?> <small><?php _e( 'secs', 'sportspress' ); ?></small></span>
			</time>
		</p>
	</div>
</div>
