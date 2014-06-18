<?php
/**
 * Event Performance
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Templates
 * @version     0.8
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! isset( $id ) )
	$id = get_the_ID();

$event = new SP_Event( $id );
$data = $event->performance();

// The first row should be column labels
$labels = $data[0];

// Remove the first row to leave us with the actual data
unset( $data[0] );

$data = array_filter( $data );

$teams = (array)get_post_meta( $id, 'sp_team', false );
$status = $event->status();

$link_posts = get_option( 'sportspress_event_link_players', 'yes' ) == 'yes' ? true : false;
$sortable = get_option( 'sportspress_enable_sortable_tables', 'yes' ) == 'yes' ? true : false;
$responsive = get_option( 'sportspress_enable_responsive_tables', 'yes' ) == 'yes' ? true : false;

foreach( $teams as $index => $team_id ):
	if ( ! $team_id ) continue;

	// Get results for players in the team
	$players = sp_array_between( (array)get_post_meta( $id, 'sp_player', false ), 0, $index );
	$has_players = sizeof( $players ) > 1;

	if ( ! $has_players && $status != 'results' ) continue;

	$totals = array();

	$data = sp_array_combine( $players, sp_array_value( $data, $team_id, array() ) );
	?>
	<h3><?php echo get_the_title( $team_id ); ?></h3>
	<?php
	if ( get_option( 'sportspress_event_show_staff', 'yes' ) == 'yes' ):
		sp_get_template( 'event-staff.php', array( 'id' => $id, 'index' => $index ) );
	endif;
	?>
	<div class="sp-table-wrapper sp-scrollable-table-wrapper">
		<table class="sp-event-performance sp-data-table <?php if ( $responsive ) { ?> sp-responsive-table<?php } if ( $has_players && $sortable ) { ?> sp-sortable-table<?php } ?>">
			<thead>
				<tr>
					<?php if ( $has_players ): ?>
						<th class="data-number">#</th>
						<th class="data-name"><?php _e( 'Player', 'sportspress' ); ?></th>
					<?php endif; foreach( $labels as $key => $label ): ?>
						<th class="data-<?php echo $key; ?>"><?php echo $label; ?></th>
					<?php endforeach; ?>
				</tr>
			</thead>
			<?php if ( $has_players ): ?>
				<tbody>
					<?php

					$lineups = array_filter( $data, array( $event, 'lineup_filter' ) );
					$subs = array_filter( $data, array( $event, 'sub_filter' ) );

					$lineup_sub_relation = array();
					foreach ( $subs as $sub_id => $sub ):
						if ( ! $sub_id )
							continue;
						$lineup_sub_relation[ sp_array_value( $sub, 'sub', 0 ) ] = $sub_id;
					endforeach;

					$i = 0;
					foreach( $data as $player_id => $row ):

						if ( ! $player_id )
							continue;

						$name = get_the_title( $player_id );

						if ( ! $name )
							continue;

						echo '<tr class="' . ( $i % 2 == 0 ? 'odd' : 'even' ) . '">';

						$number = get_post_meta( $player_id, 'sp_number', true );

						// Player number
						echo '<td class="data-number">' . $number . '</td>';

						if ( $link_posts ):
							$permalink = get_post_permalink( $player_id );
							$name =  '<a href="' . $permalink . '">' . $name . '</a>';
							if ( isset( $row['status'] ) && $row['status'] == 'sub' ):
								$name = '(' . $name . ')';
							endif;
							if ( array_key_exists( $player_id, $lineup_sub_relation ) ):
								$name .= ' <span class="sub-in" title="' . get_the_title( $lineup_sub_relation[ $player_id ] ) . '">' . get_post_meta( $lineup_sub_relation[ $player_id ], 'sp_number', true ) . '</span>';
							elseif ( isset( $row['sub'] ) && $row['sub'] ):
								$name .= ' <span class="sub-out" title="' . get_the_title( $row[ 'sub' ] ) . '">' . get_post_meta( $row['sub'], 'sp_number', true ) . '</span>';
							endif;
						endif;

						echo '<td class="data-name">' . $name . '</td>';


						foreach( $labels as $key => $label ):
							if ( $key == 'name' )
								continue;
							if ( array_key_exists( $key, $row ) && $row[ $key ] != '' ):
								$value = $row[ $key ];
							else:
								$value = 0;
							endif;
							if ( ! array_key_exists( $key, $totals ) ):
								$totals[ $key ] = 0;
							endif;
							$totals[ $key ] += $value;
							echo '<td class="data-' . $key . '">' . $value . '</td>';
						endforeach;

						echo '</tr>';

						$i++;

					endforeach;
					?>
				</tbody>
			<?php endif; ?>
			<?php if ( $status == 'results' && array_key_exists( 0, $data ) ): ?>
				<<?php echo ( $has_players ? 'tfoot' : 'tbody' ); ?>>
					<tr class="' . ( $i % 2 == 0 ? 'odd' : 'even' ) . '">
						<?php
						if ( $has_players ):
							echo '<td class="data-number">&nbsp;</td>';
							echo '<td class="data-name">' . __( 'Total', 'sportspress' ) . '</td>';
						endif;

						$row = $data[0];

						foreach( $labels as $key => $label ):
							if ( $key == 'name' ):
								continue;
							endif;
							if ( array_key_exists( $key, $row ) && $row[ $key ] != '' ):
								$value = $row[ $key ];
							else:
								$value = sp_array_value( $totals, $key, 0 );
							endif;
							echo '<td class="data-' . $key . '">' . $value . '</td>';
						endforeach;
						?>
					</tr>
				</<?php echo ( $has_players ? 'tfoot' : 'tbody' ); ?>>
			<?php endif; ?>
		</table>
	</div>
	<?php
endforeach;
