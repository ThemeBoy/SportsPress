<?php
/**
 * User Results
 *
 * @author 		ThemeBoy
 * @package 	SportsPress_User_Results
 * @version     2.3
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! isset( $id ) )
	$id = get_the_ID();

// Get teams from event
$teams = (array) get_post_meta( $id, 'sp_team', false );

// Return if there are no teams
if ( empty( $teams ) ) return;

// Get current user
$user = wp_get_current_user();

// Get user ID
$user_id = $user->ID;

// Get existing submissions
$meta = (array) get_post_meta( $id, 'sp_user_results', true );

// Get user roles
$user_roles = (array) $user->roles;

// Filter out teams that belong to other users
if ( current_user_can( 'manage_sportspress' ) || in_array( 'sp_event_manager', $user_roles ) ) {
	// Admin, League Manager, or Event Manager
	if ( in_array( 'sp_league_manager', $user_roles ) ) {
		if ( 'no' === get_option( 'sportspress_user_results_league_manager_status', 'yes' ) ) return;
	} elseif ( in_array( 'sp_event_manager', $user_roles ) ) {
		if ( 'no' === get_option( 'sportspress_user_results_event_manager_status', 'yes' ) ) return;
	}
} elseif ( in_array( 'sp_team_manager', $user_roles ) ) {
	// Team Manager
	if ( 'no' === get_option( 'sportspress_user_results_team_manager_status', 'yes' ) ) return;
	foreach ( $teams as $i => $team ) {
		if ( $team && get_post_field( 'post_author', $team ) != $user_id ) {
			unset( $teams[ $i ] );
		}
	}
} elseif ( in_array( 'sp_staff', $user_roles ) ) {
	// Staff
	if ( 'no' === get_option( 'sportspress_user_results_staff_status', 'yes' ) ) return;
	$staff = (array) get_post_meta( $id, 'sp_staff', false );
	$i = -1;
	$teams = array();
	foreach ( $staff as $member ) {
		if ( 0 == $member ) {
			$i++;
		} elseif ( get_post_field( 'post_author', $member ) != $user_id ) {
			unset( $teams[ $i ] );
		}
	}
} else {
	// No access
	$teams = array();
	return;
}

// Filter out blanks
$teams = array_filter( $teams );

// Filter out duplicates
$teams = array_unique( $teams );

// Return if no teams are left
if ( ! sizeof( $teams ) ) return;

// Save results
if ( isset( $_POST['sp_user_results'] ) && wp_verify_nonce( $_POST['sp_user_results'], 'submit_results' ) ) {
	if ( isset( $_POST['sp_results'] ) ) {
		$results = (array) $_POST['sp_results'];

		foreach ( $results as $team => $stats ) {
			$stats = array_filter( $stats, 'sp_filter_non_empty' );
			if ( empty( $stats ) ) {
				unset( $results[ $team ] );
			} else {
				$meta[ $user_id ][ $team ] = array_merge( sp_array_value( sp_array_value( $meta, $user_id, array() ), $team, array() ), $stats );
			}
		}

		if ( ! empty( $results ) ) {
			update_post_meta( $id, 'sp_user_results', $meta );

			echo '<div class="sp-template sp-template-message sp-template-message-thank-you"><p class="sp-message sp-message-thanks">' . __( 'Thank you!', 'sportspress'  ) . '</p></div>';
		}
	}
}

// Get user submitted results
$user_results = sp_array_value( $meta, $user_id, array() );

// Get event results data
$event = new SP_Event( $id );
list( $labels, $usecolumns, $results ) = $event->results( true );
?>
<form method="post">
	<div class="sp-template sp-template-user-results sp-template-event-user-results">
		<h4 class="sp-table-caption"><?php empty( $user_results ) ? _e( 'Submit Your Results', 'sportspress' ) :  _e( 'Update Your Results', 'sportspress' ); ?></h4>
		<div class="sp-table-wrapper">
			<table class="sp-event-user-results sp-data-table">
				<thead>
					<tr>
						<th class="data-name">
							<?php _e( 'Team', 'sportspress' ); ?>
						</th>
						<?php foreach ( $labels as $key => $label ): ?>
							<th class="data-<?php echo $key; ?>"><?php echo $label; ?></th>
						<?php endforeach; ?>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( $teams as $team ) { ?>
						<tr>
							<td class="data-name">
								<?php echo get_the_title( $team ); ?>
							</td>
							<?php foreach ( $labels as $key => $label ): ?>
								<?php $placeholder = sp_array_value( sp_array_value( $user_results, $team, array() ), $key, '' ); ?>
								<td class="data-<?php echo $key; ?>">
									<input type="text" name="sp_results[<?php echo $team; ?>][<?php echo $key; ?>]" placeholder="<?php echo $placeholder; ?>">
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
		<?php wp_nonce_field( 'submit_results', 'sp_user_results' ); ?>
	</p>
</form>