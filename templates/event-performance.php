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

$teams = (array)get_post_meta( $id, 'sp_team', false );
$staff = (array)get_post_meta( $id, 'sp_staff', false );
$stats = (array)get_post_meta( $id, 'sp_players', true );
$performance_labels = sp_get_var_labels( 'sp_performance' );
$link_posts = get_option( 'sportspress_event_link_players', 'yes' ) == 'yes' ? true : false;
$sortable = get_option( 'sportspress_enable_sortable_tables', 'yes' ) == 'yes' ? true : false;
$responsive = get_option( 'sportspress_enable_responsive_tables', 'yes' ) == 'yes' ? true : false;

foreach( $teams as $key => $team_id ):
	if ( ! $team_id ) continue;

	$totals = array();

	// Get results for players in the team
	$players = sp_array_between( (array)get_post_meta( $id, 'sp_player', false ), 0, $key );
	$has_players = sizeof( $players ) > 1;

	$data = sp_array_combine( $players, sp_array_value( $stats, $team_id, array() ) );
	?>
	<h3><?php echo get_the_title( $team_id ); ?></h3>
	<div class="sp-table-wrapper">
		<table class="sp-event-performance sp-data-table <?php if ( $responsive ) { ?> sp-responsive-table<?php } if ( $has_players && $sortable ) { ?> sp-sortable-table<?php } ?>">
			<thead>
				<tr>
					<?php if ( $has_players ): ?>
						<th class="data-number">#</th>
						<th class="data-name"><?php echo SP()->text->string('Player', 'event'); ?></th>
					<?php endif; foreach( $performance_labels as $key => $label ): ?>
						<th class="data-<?php echo $key; ?>"><?php echo $label; ?></th>
					<?php endforeach; ?>
				</tr>
			</thead>
			<?php if ( $has_players ): ?>
				<tbody>
					<?php
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
						endif;

						echo '<td class="data-name">' . $name . '</td>';


						foreach( $performance_labels as $key => $label ):
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
			<?php if ( array_key_exists( 0, $data ) ): ?>
				<<?php echo ( $has_players ? 'tfoot' : 'tbody' ); ?>>
					<tr class="' . ( $i % 2 == 0 ? 'odd' : 'even' ) . '">
						<?php
						if ( $has_players ):
							echo '<td class="data-number">&nbsp;</td>';
							echo '<td class="data-name">' . SP()->text->string('Total', 'event') . '</td>';
						endif;

						$row = $data[0];

						foreach( $performance_labels as $key => $label ):
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
<?php endforeach;
