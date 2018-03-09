<?php
/**
 * Tweets
 *
 * @author 		ThemeBoy
 * @package 	SportsPress_Player_Transfers
 * @version     2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! isset( $id ) )
	$id = get_the_ID();

$scrollable = get_option( 'sportspress_enable_scrollable_tables', 'yes' ) == 'yes' ? true : false;

$player_transfers = get_post_meta($id, 'sp_transfers', true);
?>

<h4 class="sp-table-caption"><?php the_title(); ?> - Career Transfers</h4>
<div class="sp-table-wrapper">
	<table class="sp-player-statistics sp-data-table<?php if ( $scrollable) { echo ' sp-scrollable-table'; }?>">
		<thead>
			<tr>
				<th class="data-Season">Season</th>
				<th class="data-League">League</th>
				<th class="data-Team">Team</th>
				<th class="data-Date From">Date From</th>
				<th class="data-Date To">Date To</th>
			</tr>
		</thead>
		<tbody>
		<?php
		foreach ( $player_transfers as $transfer ) {
			echo '<tr>';
			echo '<td>'.get_term( $transfer['season'] )->name.'</td>';
			echo '<td>'.get_term( $transfer['league'] )->name.'</td>';
			echo '<td>'.get_the_title( $transfer['team'] ).'</td>';
			echo '<td>'.date_i18n( get_option( 'date_format' ), strtotime( $transfer['date_from'] ) ).'</td>';
			echo '<td>'.date_i18n( get_option( 'date_format' ), strtotime( $transfer['date_to'] ) ).'</td>';
			echo '<tr>';
		}
		?>
		</tbody>
	</table>
</div>
