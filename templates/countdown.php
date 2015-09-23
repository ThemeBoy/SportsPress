<?php
/**
 * Countdown
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Templates
 * @version     1.9
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$defaults = array(
	'team' => null,
	'id' => null,
	'title' => null,
	'live' => get_option( 'sportspress_enable_live_countdowns', 'yes' ) == 'yes' ? true : false,
	'link_events' => get_option( 'sportspress_link_events', 'yes' ) == 'yes' ? true : false,
	'link_teams' => get_option( 'sportspress_link_teams', 'no' ) == 'yes' ? true : false,
	'show_logos' => get_option( 'sportspress_countdown_show_logos', 'no' ) == 'yes' ? true : false,
);

if ( isset( $id ) ):
	$post = get_post( $id );
else:
	$args = array();
	if ( isset( $team ) )
		$args = array( array( 'key' => 'sp_team', 'value' => $team ) );
	$post = sp_get_next_event( $args );
endif;

extract( $defaults, EXTR_SKIP );

if ( ! isset( $post ) ) return;

if ( $title )
	echo '<h4 class="sp-table-caption">' . $title . '</h4>';

$title = $post->post_title;
if ( $link_events ) $title = '<a href="' . get_post_permalink( $post->ID, false, true ) . '">' . $title . '</a>';
?>
<div class="sp-template sp-template-countdown">
	<div class="sp-countdown-wrapper">
		<h3 class="event-name">
			<?php
			if ( $show_logos ) {
				$teams = array_unique( get_post_meta( $post->ID, 'sp_team' ) );
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
			<?php echo $title; ?>
		</h3>
		<?php
		if ( isset( $show_venue ) && $show_venue ):
			$venues = get_the_terms( $post->ID, 'sp_venue' );
			if ( $venues ):
				?>
				<h5 class="event-venue"><?php the_terms( $post->ID, 'sp_venue' ); ?></h5>
				<?php
			endif;
		endif;

		if ( isset( $show_league ) && $show_league ):
			$leagues = get_the_terms( $post->ID, 'sp_league' );
			if ( $leagues ):
				foreach( $leagues as $league ):
					$term = get_term( $league->term_id, 'sp_league' );
					?>
					<h5 class="event-league"><?php echo $term->name; ?></h5>
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
			<time datetime="<?php echo $post->post_date; ?>"<?php if ( $live ): ?> data-countdown="<?php echo str_replace( '-', '/', $post->post_date ); ?>"<?php endif; ?>>
				<span><?php echo sprintf( '%02s', $days ); ?> <small><?php _e( 'days', 'sportspress' ); ?></small></span>
				<span><?php echo sprintf( '%02s', $h ); ?> <small><?php _e( 'hrs', 'sportspress' ); ?></small></span>
				<span><?php echo sprintf( '%02s', $i ); ?> <small><?php _e( 'mins', 'sportspress' ); ?></small></span>
				<span><?php echo sprintf( '%02s', $s ); ?> <small><?php _e( 'secs', 'sportspress' ); ?></small></span>
			</time>
		</p>
	</div>
</div>