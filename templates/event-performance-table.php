<?php
/**
 * Event Performance Table
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Templates
 * @version		2.6.20
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Initialize totals
$totals = array();

// Set null
if ( ! isset( $section ) ) $section = -1;
if ( ! isset( $section_label ) ) $section_label = null;
if ( ! isset( $class ) ) $class = null;

// Initialize arrays
if ( ! isset( $lineups ) ) $lineups = array();
if ( ! isset( $subs ) ) $subs = array();

$responsive = get_option( 'sportspress_enable_responsive_tables', 'no' ) == 'yes' ? true : false;
//Create a unique identifier based on the current time in microseconds
$identifier = uniqid( 'performance_' );
// If responsive tables are enabled then load the inline css code
if ( true == $responsive && $mode == 'values' ){
	//sportspress_responsive_tables_css( $identifier );
}
$i = 0;
?>
<div class="sp-template sp-template-event-performance sp-template-event-performance-<?php echo $mode; ?><?php if ( isset( $class ) ) { echo ' ' . $class; } ?>">
	<?php if ( $caption ): ?>
		<h4 class="sp-table-caption"><?php echo $caption; ?></h4>
	<?php endif; ?>
	<div class="sp-table-wrapper">
		<table class="sp-event-performance sp-data-table<?php if ( $mode == 'values' ) { ?><?php if ( $scrollable ) { ?> sp-scrollable-table<?php }if ( $responsive ) { echo ' sp-responsive-table '.$identifier; } if ( $sortable ) { ?> sp-sortable-table<?php } ?><?php } ?>">
			<thead>
				<tr>
					<?php if ( $mode == 'values' ): ?>
						<?php if ( $show_players ): ?>
							<?php if ( apply_filters( 'sportspress_event_performance_show_numbers', $show_numbers, $section ) ) { ?>
								<th class="data-number">#</th>
							<?php } ?>
							<th class="data-name">
								<?php if ( isset( $section_label ) ) { ?>
									<?php echo $section_label; ?>
								<?php } else { ?>
									<?php _e( 'Player', 'sportspress' ); ?>
								<?php } ?>
							</th>
						<?php endif; ?>
						<?php foreach ( $labels as $key => $label ): ?>
							<th class="data-<?php echo $key; ?>"><?php echo $label; ?></th>
						<?php endforeach; ?>
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
						$i = sp_array_value( $sub, 'sub', 0 );
						$lineup_sub_relation[ $i ] = $sub_id;
					endforeach;

					$data = apply_filters( 'sportspress_event_performance_players', $data, $lineups, $subs, $mode );

					$stars_type = get_option( 'sportspress_event_performance_stars_type', 0 );

					foreach ( $data as $player_id => $row ):

						if ( ! $player_id )
							continue;

						$name = get_the_title( $player_id );

						if ( ! $name )
							continue;

						echo '<tr class="' . sp_array_value( $row, 'status', 'lineup' ) . ' ' . ( $i % 2 == 0 ? 'odd' : 'even' ) . '">';

						if ( apply_filters( 'sportspress_event_performance_show_numbers', $show_numbers, $section ) ) {
							$number = sp_array_value( $row, 'number', '&nbsp;' );

							// Player number
							echo '<td class="data-number" data-label="#">' . $number . '</td>';
						}

						if ( $link_posts ):
							$permalink = get_post_permalink( $player_id );
							$name =  '<a href="' . $permalink . '">' . $name . '</a>';
						endif;

						if ( $stars_type ):
							$player_stars = sp_array_value( $stars, $player_id, 0 );
							if ( $player_stars ):
								switch ( $stars_type ):
									case 1:
										$name .= ' <span class="sp-event-stars"><i class="sp-event-star dashicons dashicons-star-filled" title="' . __( 'Player of the Match', 'sportspress' ) . '"></i></span>';
										break;
									case 2:
										$name .= ' <span class="sp-event-stars">' . str_repeat( '<i class="sp-event-star dashicons dashicons-star-filled" title="' . __( 'Stars', 'sportspress' ) . '"></i>', $player_stars ) . '</span>';
										break;
									case 3:
										$name .= ' <span class="sp-event-stars"><i class="sp-event-star sp-event-star-' . $player_stars . '  dashicons dashicons-star-filled" title="' . __( 'Stars', 'sportspress' ) . '"></i><span class="sp-event-star-number">' . $player_stars . '</span></span>';
										break;
								endswitch;
							endif;
						endif;

						if ( array_key_exists( $player_id, $lineup_sub_relation ) ):
							$name .= ' <span class="sub-in" title="' . get_the_title( $lineup_sub_relation[ $player_id ] ) . '">' . sp_array_value( sp_array_value( $data, $lineup_sub_relation[ $player_id ], array() ), 'number', null ) . '</span>';
						elseif ( isset( $row['sub'] ) && $row['sub'] ):
							$subbed = (int) $row['sub'];
							$name .= ' <span class="sub-out" title="' . get_the_title( $row[ 'sub' ] ) . '">' . sp_array_value( sp_array_value( $data, $subbed, array() ), 'number', null ) . '</span>';
						endif;

						$content = '';
						$position = null;

						foreach ( $labels as $key => $label ):
							if ( 'name' == $key )
								continue;

							$format = sp_array_value( $formats, $key, 'number' );
							$placeholder = sp_get_format_placeholder( $format );

							if ( ! array_key_exists( $key, $totals ) ):
								$totals[ $key ] = $placeholder;
							endif;

							if ( 'time' === $format ):
								$totals[ $key ] = '&nbsp;';
							endif;
							
							$value = '-';
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
								
								$positions = array_unique( $positions );

								if ( sizeof( $positions ) ):
									$value = $position = implode( ', ', $positions );
								endif;
							else:
								if ( array_key_exists( $key, $row ) && $row[ $key ] !== '' ):
									if ( 'checkbox' === $format ):
										$value = '<span class="sp-checkbox">' . $row[ $key ] . '</span>';
									else:
										$value = $row[ $key ];
									endif;
								else:
									$value = $placeholder;
								endif;
								
								if ( 'number' === $format ):
									$add = apply_filters( 'sportspress_event_performance_add_value', floatval( $value ), $key );
									$totals[ $key ] += $add;
								endif;
							endif;

							if ( $mode == 'values' ):
								$content .= '<td class="data-' . $key . '" data-label="'.$labels[$key].'">' . $value . '</td>';
							elseif ( intval( $value ) && $mode == 'icons' ):
								$performance_id = sp_array_value( $performance_ids, $key, null );
								$icons = '';
								if ( $performance_id && has_post_thumbnail( $performance_id ) ):
									$icons = str_repeat( get_the_post_thumbnail( $performance_id, 'sportspress-fit-mini', array( 'title' => sp_get_singular_name( $performance_id ) ) ) . ' ', intval($value) );
								endif;
								$content .= apply_filters( 'sportspress_event_performance_icons', $icons, $performance_id, $value );
							endif;
						endforeach;

						if ( isset( $position ) && $mode == 'icons' ):
							$name .= ' <small class="sp-player-position">' . $position . '</small>';
						endif;

						echo '<td class="data-name" data-label="' . ( isset( $section_label ) ? $section_label : __( 'Player', 'sportspress' ) ) .'">' . $name . '</td>';

						if ( $mode == 'icons' ):
							echo '<td class="sp-performance-icons">' . $content . '</td>';
						else:
							echo $content;
						endif;

						echo '</tr>';

						$i++;

					endforeach;

					foreach ( $labels as $key => $label ):
						$format = sp_array_value( $formats, $key, 'number' );
						if ( 'equation' === $format ):
							$post = get_page_by_path( $key, OBJECT, 'sp_performance' );
							if ( $post ) $totals[ $key ] = sp_solve( get_post_meta( $post->ID, 'sp_equation', true ), $totals, get_post_meta( $post->ID, 'sp_precision', true ) );
						endif;
					endforeach;
					?>
				</tbody>
			<?php endif; ?>
			<?php if ( apply_filters( 'sportspress_event_performance_show_footer', $show_total ) ): ?>
				<<?php echo ( $show_players ? 'tfoot' : 'tbody' ); ?>>
					<?php
					do_action( 'sportspress_event_performance_table_footer', $data, $labels, $section, $performance_ids );
					if ( $show_total && ( ! $primary || sizeof( array_intersect_key( $totals, array_flip( (array) $primary ) ) ) ) ) {
						?>
						<tr class="sp-total-row <?php echo ( $i % 2 == 0 ? 'odd' : 'even' ); ?>">
							<?php
							if ( $show_players ):
								if ( apply_filters( 'sportspress_event_performance_show_numbers', $show_numbers, $section ) ) {
									echo '<td class="data-number" data-label="&nbsp;">&nbsp;</td>';
								}
								if ( $mode == 'values' ):
									echo '<td class="data-name" data-label="&nbsp;">' . __( 'Total', 'sportspress' ) . '</td>';
								endif;
							endif;

							$row = sp_array_value( $data, 0, array() );

							if ( $mode == 'icons' ) echo '<td class="sp-performance-icons" colspan="2">';

							foreach ( $labels as $key => $label ):
								$format = sp_array_value( $formats, $key, 'number' );
								if ( 'name' == $key )
									continue;
								if ( $key == 'position' ):
									$value = '&nbsp;';
								else:
									if ( $primary && $key !== $primary ):
										$value = '&nbsp;';
									elseif ( 'equation' !== $format && array_key_exists( $key, $row ) && $row[ $key ] != '' ):
										$value = $row[ $key ];
									else:
										$value = apply_filters( 'sportspress_event_performance_table_total_value', sp_array_value( $totals, $key, 0 ), $data, $key );
									endif;
								endif;

								if ( $mode == 'values' ):
									if ($key == 'position'){
										echo '<td class="data-' . $key . '" data-label="&nbsp;">' . $value . '</td>';
									}else{
										echo '<td class="data-' . $key . '" data-label="'.$labels[$key].'">' . $value . '</td>';
									}
								elseif ( intval( $value ) && $mode == 'icons' ):
									$performance_id = sp_array_value( $performance_ids, $key, null );
									$icons = '';
									if ( $performance_id && has_post_thumbnail( $performance_id ) ):
										$icons = get_the_post_thumbnail( $performance_id, 'sportspress-fit-mini', array( 'title' => sp_get_singular_name( $performance_id ) ) );
									endif;
									echo apply_filters( 'sportspress_event_performance_icons', $icons, $performance_id, 1 ) . $value . ' ';
								endif;
							endforeach;

							if ( $mode == 'icons' ) echo '</td>';
							?>
						</tr>
					<?php } ?>
				</<?php echo ( $show_players ? 'tfoot' : 'tbody' ); ?>>
			<?php endif; ?>
		</table>
		<?php
			if ( isset( $show_staff ) ) {
				echo sp_get_template( 'event-staff.php', array( 'id' => $id, 'index' => $index ) );
			}
		?>
	</div>
	<?php do_action( 'sportspress_after_event_performance_table', $data, $lineups, $subs, $class ); ?>
</div>