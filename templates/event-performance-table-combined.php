<?php
/**
 * Event Performance Table Combined
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
?>
<div class="sp-template sp-template-event-performance sp-template-event-performance-combined sp-template-event-performance-<?php echo $mode; ?><?php if ( isset( $class ) ) { echo ' ' . $class; } ?>">
	<?php if ( $caption ): ?>
		<h4 class="sp-table-caption"><?php echo $caption; ?></h4>
	<?php endif; ?>
	<div class="sp-table-wrapper">
		<table class="sp-event-performance sp-data-table<?php if ( $scrollable ) { ?> sp-scrollable-table<?php } ?><?php if ( $sortable ) { ?> sp-sortable-table<?php } ?>">
			<thead>
				<tr>
					<?php if ( isset( $labels['number'] ) ): ?>
						<th class="data-number">#</th>
					<?php endif; ?>
					<th class="data-name"><?php _e( 'Player', 'sportspress' ); ?></th>
					<?php if ( $mode == 'values' ): foreach( $labels as $key => $label ): ?>
						<?php if ( 'number' == $key ) continue; ?>
						<th class="data-<?php echo $key; ?>"><?php echo $label; ?></th>
					<?php endforeach; else: ?>
						<th class="sp-performance-icons">&nbsp;</th>
					<?php endif; ?>
				</tr>
			</thead>
			<tbody>
				<?php
				if ( $show_players ) {

					$data = apply_filters( 'sportspress_event_performance_players', $data, $data, array(), $mode );

					$i = 0;
					foreach ( $data as $player_id => $row ):

						if ( ! $player_id )
							continue;

						$name = get_the_title( $player_id );

						if ( ! $name )
							continue;

						echo '<tr class="' . sp_array_value( $row, 'status', 'lineup' ) . ' ' . ( $i % 2 == 0 ? 'odd' : 'even' ) . '">';

						if ( isset( $labels['number'] ) ):
							$number = sp_array_value( $row, 'number', '&nbsp;' );

							// Player number
							echo '<td class="data-number">' . $number . '</td>';
						endif;

						if ( $link_posts ):
							$permalink = get_post_permalink( $player_id );
							$name =  '<a href="' . $permalink . '">' . $name . '</a>';
						endif;

						echo '<td class="data-name">' . $name . '</td>';
						
						if ( $mode == 'icons' ) echo '<td class="sp-performance-icons">';

						foreach ( $labels as $key => $label ):
							if ( in_array( $key, array( 'number', 'name' ) ) )
								continue;
							$value = '&mdash;';
							if ( $key == 'position' ):
								if ( array_key_exists( $key, $row ) && $row[ $key ] != '' ):
									$positions = array();
									$position_ids = (array) $row[ $key ];
									foreach ( $position_ids as $position_id ) {
										$player_position = get_term_by( 'id', $position_id, 'sp_position' );
										if ( $player_position ) $positions[] = $player_position->name;
									}
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

				}
				?>
				</tbody>
				<?php if ( apply_filters( 'sportspress_event_performance_show_footer', $show_total ) ): ?>
					<tfoot>
						<?php do_action( 'sportspress_event_performance_table_footer', $data, $labels, $position, $performance_ids ); ?>
						<?php if ( $show_total && ( ! $primary || sizeof( array_intersect_key( $totals, array_flip( (array) $primary ) ) ) ) ): ?>
							<tr class="' . ( $i % 2 == 0 ? 'odd' : 'even' ) . '">
								<?php
								if ( isset( $labels['number'] ) ):
									echo '<td class="data-number">&nbsp;</td>';
								endif;
								echo '<td class="data-name">' . __( 'Total', 'sportspress' ) . '</td>';

								$row = sp_array_value( $data, 1, array() );

								if ( $mode == 'icons' ) echo '<td class="sp-performance-icons">';

								foreach ( $labels as $key => $label ):
									if ( in_array( $key, array( 'number', 'name' ) ) )
										continue;
									if ( $key == 'position' ):
										$value = '&mdash;';
									elseif ( $primary && $key !== $primary ):
										$value = '&nbsp;';
									elseif ( array_key_exists( $key, $row ) && $row[ $key ] != '' ):
										$value = $row[ $key ];
									else:
										$value = sp_array_value( $totals, $key, 0 );
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
						<?php endif; ?>
					</tfoot>
				<?php endif; ?>
		</table>
	</div>
</div>