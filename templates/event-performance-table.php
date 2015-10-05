<?php
/**
 * Event Performance Table
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Templates
 * @version     1.9
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Initialize totals
$totals = array();

// Set null
if ( ! isset( $position ) ) $position = null;
if ( ! isset( $class ) ) $class = null;

// Initialize arrays
if ( ! isset( $lineups ) ) $lineups = array();
if ( ! isset( $subs ) ) $subs = array();
?>
<div class="sp-template sp-template-event-performance sp-template-event-performance-<?php echo $mode; ?><?php if ( isset( $class ) ) { echo ' ' . $class; } ?>">
	<?php if ( $caption ): ?>
		<h4 class="sp-table-caption"><?php echo $caption; ?></h4>
	<?php endif; ?>
	<div class="sp-table-wrapper">
		<table class="sp-event-performance sp-data-table<?php if ( $scrollable ) { ?> sp-scrollable-table<?php } ?><?php if ( $sortable ) { ?> sp-sortable-table<?php } ?>">
			<thead>
				<tr>
					<?php if ( $show_players ): ?>
						<?php if ( $show_numbers ) { ?>
							<th class="data-number">#</th>
						<?php } ?>
						<th class="data-name">
							<?php if ( isset( $position ) ) { ?>
								<?php echo $position; ?>
							<?php } else { ?>
								<?php _e( 'Player', 'sportspress' ); ?>
							<?php } ?>
						</th>
					<?php endif; ?>
					<?php if ( $mode == 'values' ): foreach ( $labels as $key => $label ): ?>
						<?php if ( isset( $position ) && 'position' == $key ) continue; ?>
						<th class="data-<?php echo $key; ?>"><?php echo $label; ?></th>
					<?php endforeach; else: ?>
						<th class="sp-performance-icons">&nbsp;</th>
					<?php endif; ?>
				</tr>
			</thead>
			<?php if ( $show_players ): ?>
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

					$data = apply_filters( 'sportspress_event_performance_players', $data, $lineups, $subs, $mode );

					$i = 0;
					foreach ( $data as $player_id => $row ):

						if ( ! $player_id )
							continue;

						$name = get_the_title( $player_id );

						if ( ! $name )
							continue;

						echo '<tr class="' . sp_array_value( $row, 'status', 'lineup' ) . ' ' . ( $i % 2 == 0 ? 'odd' : 'even' ) . '">';

						if ( $show_numbers ) {
							$number = sp_array_value( $row, 'number', '&nbsp;' );

							// Player number
							echo '<td class="data-number">' . $number . '</td>';
						}

						if ( $link_posts ):
							$permalink = get_post_permalink( $player_id );
							$name =  '<a href="' . $permalink . '">' . $name . '</a>';
						endif;

						if ( array_key_exists( $player_id, $lineup_sub_relation ) ):
							$name .= ' <span class="sub-in" title="' . get_the_title( $lineup_sub_relation[ $player_id ] ) . '">' . sp_array_value( sp_array_value( $data, $lineup_sub_relation[ $player_id ], array() ), 'number', null ) . '</span>';
						elseif ( isset( $row['sub'] ) && $row['sub'] ):
							$name .= ' <span class="sub-out" title="' . get_the_title( $row[ 'sub' ] ) . '">' . sp_array_value( sp_array_value( $data, $row['sub'], array() ), 'number', null ) . '</span>';
						endif;

						echo '<td class="data-name">' . $name . '</td>';
						
						if ( $mode == 'icons' ) echo '<td class="sp-performance-icons">';

						foreach ( $labels as $key => $label ):
							if ( 'name' == $key )
								continue;
							if ( isset( $position ) && 'position' == $key )
								continue;
							
							$value = '&mdash;';
							if ( $key == 'position' ):
								$positions = array();
								if ( array_key_exists( $key, $row ) && $row[ $key ] != '' ):
									$position_ids = (array) $row[ $key ];
								else:
									$position_ids = (array) sp_get_the_term_id( $player_id, 'sp_position' );
								endif;

								foreach ( $position_ids as $position_id ) {
									$player_position = get_term_by( 'id', $position_id, 'sp_position' );
									if ( $player_position ) $positions[] = $player_position->name;
								}

								if ( sizeof( $positions ) ):
									$value = implode( ', ', $positions );
								endif;
							else:
								if ( array_key_exists( $key, $row ) && $row[ $key ] != '' ):
									$value = $row[ $key ];
								else:
									$value = 0;
								endif;
							endif;
							if ( ! array_key_exists( $key, $totals ) ):
								$totals[ $key ] = 0;
							endif;
							$totals[ $key ] += $value;

							if ( $mode == 'values' ):
								echo '<td class="data-' . $key . '">' . $value . '</td>';
							elseif ( intval( $value ) && $mode == 'icons' ):
								$performance_id = sp_array_value( $performance_ids, $key, null );
								$icons = '';
								if ( $performance_id && has_post_thumbnail( $performance_id ) ):
									$icons = str_repeat( get_the_post_thumbnail( $performance_id, 'sportspress-fit-mini' ) . ' ', $value );
								endif;
								echo apply_filters( 'sportspress_event_performance_icons', $icons, $performance_id, $value );
							endif;
						endforeach;
						
						if ( $mode == 'icons' ) echo '</td>';

						echo '</tr>';

						$i++;

					endforeach;
					?>
				</tbody>
			<?php endif; ?>
			<?php if ( apply_filters( 'sportspress_event_performance_show_footer', $show_total ) ): ?>
				<<?php echo ( $show_players ? 'tfoot' : 'tbody' ); ?>>
					<?php
					do_action( 'sportspress_event_performance_table_footer', $data, $labels, $position, $performance_ids );
					if ( $show_total && ( ! $primary || sizeof( array_intersect_key( $totals, array_flip( (array) $primary ) ) ) ) ) {
						?>
						<tr class="sp-total-row <?php echo ( $i % 2 == 0 ? 'odd' : 'even' ); ?>">
							<?php
							if ( $show_players ):
								if ( $show_numbers ) {
									echo '<td class="data-number">&nbsp;</td>';
								}
								echo '<td class="data-name">' . __( 'Total', 'sportspress' ) . '</td>';
							endif;

							$row = sp_array_value( $data, 0, array() );

							if ( $mode == 'icons' ) echo '<td class="sp-performance-icons">';

							foreach ( $labels as $key => $label ):
								if ( 'name' == $key )
									continue;
								if ( isset( $position ) && 'position' == $key )
									continue;
								if ( $key == 'position' ):
									$value = '&nbsp;';
								else:
									if ( $primary && $key !== $primary ):
										$value = '&nbsp;';
									elseif ( array_key_exists( $key, $row ) && $row[ $key ] != '' ):
										$value = $row[ $key ];
									else:
										$value = apply_filters( 'sportspress_event_performance_table_total_value', sp_array_value( $totals, $key, 0 ), $data, $key );
									endif;
								endif;

								if ( $mode == 'values' ):
									echo '<td class="data-' . $key . '">' . $value . '</td>';
								elseif ( intval( $value ) && $mode == 'icons' ):
									$performance_id = sp_array_value( $performance_ids, $key, null );
									$icons = '';
									if ( $performance_id && has_post_thumbnail( $performance_id ) ):
										$icons = str_repeat( get_the_post_thumbnail( $performance_id, 'sportspress-fit-mini' ) . ' ', $value );
									endif;
									echo apply_filters( 'sportspress_event_performance_icons', $icons, $performance_id, $value );
								endif;
							endforeach;

							if ( $mode == 'icons' ) echo '</td>';
							?>
						</tr>
					<?php } ?>
				</<?php echo ( $show_players ? 'tfoot' : 'tbody' ); ?>>
			<?php endif; ?>
		</table>
	</div>
</div>

<?php do_action( 'sportspress_after_event_performance_table', $data, $lineups, $subs, $class ); ?>