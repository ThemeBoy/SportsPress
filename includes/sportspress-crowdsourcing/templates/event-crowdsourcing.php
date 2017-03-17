<?php
/**
 * CrowdSourcing
 *
 * @author 		ThemeBoy
 * @package 	SportsPress_CrowdSourcing
 * @version     2.2
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! isset( $id ) )
	$id = get_the_ID();

// Get linear crowdsourcing from event
$event = new SP_Event( $id );
$crowdsourcing = $event->crowdsourcing( false, true );

// Return if crowdsourcing is empty
if ( empty( $crowdsourcing ) ) return;

// Get team link option
$link_teams = get_option( 'sportspress_link_teams', 'no' ) == 'yes' ? true : false;

// Get full time of event
$minutes = $event->minutes();

// Initialize spacer
$previous = 0;

?>
<div class="sp-template sp-template-crowdsourcing sp-template-event-crowdsourcing">
	<div class="sp-crowdsourcing">
		<hr>
		<?php foreach ( $crowdsourcing as $minute => $details ) { ?>
			<?php
			$time = sp_array_value( $details, 'time', false );
			if ( false === $time ) continue;

			$icon = sp_array_value( $details, 'icon', '' );
			$side = sp_array_value( $details, 'side', 'home' );

			if ( $time < 0 ) {
				$name = sp_array_value( $details, 'name', __( 'Team', 'sportspress' ) );
				?>
				<span class="sp-crowdsourcing-minute sp-crowdsourcing-minute-<?php echo $side; ?>" title="<?php _e( 'Kick Off', 'sportspress' ); ?>" style="left: 0;">
					<?php if ( $icon ) { ?>
						<?php if ( $link_teams ) { ?>
							<?php $team = sp_array_value( $details, 'id' ); ?>
							<a class="sp-crowdsourcing-icon sp-crowdsourcing-icon-<?php echo $side; ?>" title="<?php echo $name; ?>" href="<?php echo get_post_permalink( $team ); ?>"><?php echo $icon; ?></a>
						<?php } else { ?>
							<span class="sp-crowdsourcing-icon sp-crowdsourcing-icon-<?php echo $side; ?>" title="<?php echo $name; ?>"><?php echo $icon; ?></span>
						<?php } ?>
					<?php } ?>
					<span class="sp-crowdsourcing-kickoff sp-crowdsourcing-kickoff-<?php echo $side; ?>"><?php _e( 'KO', 'sportspress' ); ?></span>
				</span>
				<?php
			} else {
				$name = sp_array_value( $details, 'name', __( 'Player', 'sportspress' ) );
				$number = sp_array_value( $details, 'number', '' );

				if ( '' !== $number ) $name = $number . '. ' . $name;

				$offset = floor( $time / ( $minutes + 4 )* 100 );
				if ( $offset - $previous <= 2 ) $offset = $previous + 2;
				$previous = $offset;
				?>
				<span class="sp-crowdsourcing-minute sp-crowdsourcing-minute-<?php echo $side; ?>" title="<?php echo $name; ?>" style="left: <?php echo $offset + 1; ?>%;">
					<span class="sp-crowdsourcing-icon sp-crowdsourcing-icon-<?php echo $side; ?>"><?php echo $icon; ?></span>
					<?php echo $time; ?>
				</span>
				<?php
			}
		}
		?>
		<span class="sp-crowdsourcing-minute" title="<?php _e( 'Full Time', 'sportspress' ); ?>" style="right: 0;">
			<span class="sp-crowdsourcing-fulltime"><?php _e( 'FT', 'sportspress' ); ?></span>
		</span>
	</div>
</div>
