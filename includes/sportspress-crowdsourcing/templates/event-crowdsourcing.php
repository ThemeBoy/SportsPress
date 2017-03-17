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

// Get players from event
$players = (array) get_post_meta( $id, 'sp_player', false );

// Return if there are no players
if ( empty( $players ) ) return;

// Get current user ID
$user_id = get_current_user_id();

if ( isset( $_POST['sp_crowdsourcing'] ) && wp_verify_nonce( $_POST['sp_crowdsourcing'], 'submit_score' ) ) {
	if ( isset( $_POST['sp_scores'] ) ) {
		$scores = (array) $_POST['sp_scores'];

		$meta = (array) get_post_meta( $id, 'sp_crowdsourcing', true );

		foreach ( $scores as $player => $stats ) {
			$stats = array_filter( $stats );
			if ( empty( $stats ) ) continue;

			$stats['_timestamp'] = date('Y-m-d H:i:s');
			$stats['_player_id'] = $player;
			$meta[ $user_id ][] = $stats;
		}

		update_post_meta( $id, 'sp_crowdsourcing', $meta );

		echo '<div class="sp-template sp-template-thank-you"><p class="sp-thank-you">' . __( 'Thank you!', 'sportspress'  ) . '</p></div>';
		return;
	}
}

// Get options
$scrollable = get_option( 'sportspress_enable_scrollable_tables', 'yes' ) == 'yes' ? true : false;

// Filter out players that belong to other users
if ( ! current_user_can( 'edit_sp_staffs' ) ) {
	foreach ( $players as $i => $player ) {
		if ( get_post_field( 'post_author', $player ) != $user_id ) {
			unset( $players[ $i ] );
		}
	}
} elseif ( ! current_user_can( 'edit_others_sp_events' ) ) {
	$staff = (array) get_post_meta( $id, 'sp_staff', false );
	$i = -1;
	$staff_players = array();
	foreach ( $staff as $member ) {
		if ( 0 == $member ) {
			$i++;
		} elseif ( get_post_field( 'post_author', $member ) == $user_id ) {
			$staff_players = array_merge( $staff_players, (array) sp_array_between( $players, 0, $i ) );
		}
	}

	$players = $staff_players;
}

// Filter out blanks
$players = array_filter( $players );

// Filter out duplicates
$players = array_unique( $players );

// Return if no players are left
if ( ! sizeof( $players ) ) return;

// Get event performance data
$event = new SP_Event( $id );
list( $labels, $columns, $stats, $teams, $formats, $order, $timed ) = $event->performance( true );
?>
<form method="post">
	<div class="sp-template sp-template-crowdsourcing sp-template-event-crowdsourcing">
		<h4 class="sp-table-caption"><?php _e( 'Submit Your Scores', 'sportspress' ); ?></h4>
		<div class="sp-table-wrapper">
			<table class="sp-event-crowdsourcing sp-data-table<?php if ( $scrollable ) { ?> sp-scrollable-table<?php } ?>">
				<thead>
					<tr>
						<th class="data-name">
							<?php _e( 'Player', 'sportspress' ); ?>
						</th>
						<?php foreach ( $labels as $key => $label ): ?>
							<th class="data-<?php echo $key; ?>"><?php echo $label; ?></th>
						<?php endforeach; ?>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( $players as $player ) { ?>
						<tr>
							<td class="data-name">
								<?php echo get_the_title( $player ); ?>
							</td>
							<?php foreach ( $labels as $key => $label ): ?>
								<td class="data-<?php echo $key; ?>">
									<input type="text" name="sp_scores[<?php echo $player; ?>][<?php echo $key; ?>]">
								</td>
							<?php endforeach; ?>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
	<p class="form-submit">
		<input name="submit" type="submit" id="submit" class="submit" value="<?php _e( 'Submit', 'sportspress' ); ?>">
		<?php wp_nonce_field( 'submit_score', 'sp_crowdsourcing' ); ?>
	</p>
</form>