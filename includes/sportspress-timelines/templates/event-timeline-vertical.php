<?php
/**
 * Timeline
 *
 * @author 		ThemeBoy
 * @package 	SportsPress_Timelines
 * @version     2.2
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! isset( $id ) )
	$id = get_the_ID();

// Get linear timeline from event
$event = new SP_Event( $id );
$timeline = $event->timeline( false, true );

// Return if timeline is empty
if ( empty( $timeline ) ) return;

// Get players link option
$link_players = get_option( 'sportspress_link_players', 'no' ) == 'yes' ? true : false;

// Get full time of event
$minutes = $event->minutes();

?>
<div class="sp-template sp-template-timeline sp-template-event-timeline">
	<h4 class="sp-table-caption"><?php _e( 'Timeline', 'sportspress' ); ?></h4>
	<div class="sp-table-wrapper">
		<table>
			<tbody>
			<?php $x=0; ?>
			<?php foreach ( $timeline as $minute => $details ) {
				$class = ($x%2 == 0)? 'aliceblue': 'bisque';
				$x++;
						?>
				<?php
				$time = sp_array_value( $details, 'time', false );
				if ( false === $time ) continue;
				if ( $time > 0 ) {

				$icon = sp_array_value( $details, 'icon', '' );
				$side = sp_array_value( $details, 'side', 'home' );
				$key = sp_array_value( $details, 'key', '' );

					if ( $link_players ) {
						$name = '<a href="'.esc_url(get_permalink(sp_array_value( $details, "id",""))).'">'.sp_array_value( $details, "name", __( "Player", "sportspress" ) ).'</a>';
					}else{
						$name = sp_array_value( $details, "name", __( "Player", "sportspress" ) );
					}
					$number = sp_array_value( $details, 'number', '' );
					$key = sp_array_value( $details, 'key', '' );
						if ( $key == 'sub' ) {
							if ( $link_players ) {
								$subname = '<a href="'.esc_url(get_permalink(sp_array_value( $details, "sub",""))).'">'.sp_array_value( $details, "sub_name", "").'</a>';
							}else{
								$subname = sp_array_value( $details, "sub_name", "");
							}
							if( $side == 'home' ) {
							$name = $subname.'<i class="dashicons dashicons-undo" style="color:red;" title="'.__( "Sub OUT", "sportspress" ).'"></i><br/>'.$name.'<i class="dashicons dashicons-redo" style="color:green;" title="'.__( "Sub IN", "sportspress" ).'"></i>';
							}elseif( $side == 'away' ) {
							$name = '<i class="dashicons dashicons-redo" style="color:red;" title="'.__( "Sub OUT", "sportspress" ).'"></i>'.$subname.'<br/><i class="dashicons dashicons-undo" style="color:green;" title="'.__( "Sub IN", "sportspress" ).'"></i>'.$name;}
							}else{
								if( $side == 'home' ) {
									$name = $name.' '.$icon;
								}elseif( $side == 'away' ) {
									$name = $icon.' '.$name;
								}
							}	

					if ( '' !== $number ) $name = $number . '. ' . $name;
					?>
					<?php if( $side=='home' ) { ?> 
					<tr style="background-color:<?php echo $class; ?>"><td class="home_event" style="text-align: right;" width="48%"><?php echo $name; ?></td><td style="vertical-align:middle" class="home_event_minute" width="4%"><?php echo $time; ?>'</td><td class="away_event" width="48%">&nbsp;</td></tr>
					<?php }else{ ?>
					<tr style="background-color:<?php echo $class; ?>"><td class="home_event" width="48%">&nbsp;</td><td style="vertical-align:middle" class="home_event_minute" width="4%"><?php echo $time; ?>'</td><td class="away_event" style="text-align: left;" width="48%"><?php echo $name; ?></td></tr>
				<?php }
				}
				}
			?>
			</tbody>
		</table>
	</div>
</div>
