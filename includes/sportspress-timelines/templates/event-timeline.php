<?php
/**
 * Timeline
 *
 * @author 		ThemeBoy
 * @package 	SportsPress_Timelines
 * @version     2.1.7
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! isset( $id ) )
	$id = get_the_ID();

$event = new SP_Event( $id );
$timeline = $event->timeline( false, true );
if ( empty( $timeline ) ) return;

$link_posts = get_option( 'sportspress_link_players', 'yes' ) == 'yes' ? true : false;

$minutes = $event->minutes();

$previous = 0;

?>
<div class="sp-template sp-template-timeline sp-template-event-timeline">
	<div class="sp-timeline">
		<hr>
		<?php foreach ( $timeline as $minute => $details ) { ?>
			<?php
			$time = sp_array_value( $details, 'time', false );
			if ( false === $time ) continue;

			$name = sp_array_value( $details, 'name', __( 'Player', 'sportspress' ) );
			$number = sp_array_value( $details, 'number', '' );
			$icon = sp_array_value( $details, 'icon', '' );
			$side = sp_array_value( $details, 'side', 'home' );

			if ( '' !== $number ) $name = $number . '. ' . $name;

			$permalink = get_post_permalink( $details['id'] );

			$offset = floor( $time / ( $minutes + 2 )* 100 );
			if ( $offset - $previous <= 2 ) $offset = $previous + 2;
			$previous = $offset;
			?>
			<span class="sp-timeline-minute sp-timeline-minute-<?php echo $side; ?>" title="<?php echo $name; ?>" style="left: <?php echo $offset; ?>%;">
				<span class="sp-timeline-icon sp-timeline-icon-<?php echo $side; ?>"><?php echo $icon; ?></span>
				<?php echo $time; ?>
			</span>
		<?php } ?>
	</div>
</div>
