<?php
/**
 * Countdown
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Templates
 * @version     0.8
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$defaults = array(
	'id' => null,
	'live' => get_option( 'sportspress_enable_live_countdowns', 'yes' ) == 'yes' ? true : false,
);

if ( isset( $id ) ):
	$post = get_post( $id );
else:
	$args = array();
	if ( isset( $team ) )
		$args = array( 'key' => 'sp_team', 'value' => $team );
	$post = sp_get_next_event( $args );
endif;

extract( $defaults, EXTR_SKIP );

if ( ! isset( $post ) ) return;
?>
<div id="sp-countdown-wrapper">
	<h3 class="event-name"><a href="<?php echo get_permalink( $post->ID ); ?>"><?php echo $post->post_title; ?></a></h3>
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
	<p class="countdown sp-countdown<?php if ( $days >= 10 ): ?> long-countdown<?php endif; ?>"><time datetime="<?php echo $post->post_date; ?>"<?php if ( $live ): ?> data-countdown="<?php echo str_replace( '-', '/', $post->post_date ); ?>"<?php endif; ?>>
		<span><?php echo sprintf( '%02s', $days ); ?> <small><?php echo __( 'days', 'sportspress' ); ?></small></span>
		<span><?php echo sprintf( '%02s', $h ); ?> <small><?php echo __( 'hrs', 'sportspress' ); ?></small></span>
		<span><?php echo sprintf( '%02s', $i ); ?> <small><?php echo __( 'mins', 'sportspress' ); ?></small></span>
		<span><?php echo sprintf( '%02s', $s ); ?> <small><?php echo __( 'secs', 'sportspress' ); ?></small></span>
	</time></p>
</div>
