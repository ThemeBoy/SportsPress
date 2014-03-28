<?php
$defaults = array(
	'id' => get_the_ID(),
	'number' => -1,
	'orderby' => 'default',
	'order' => 'ASC',
	'itemtag' => 'dl',
	'icontag' => 'dt',
	'captiontag' => 'dd',
	'columns' => 3,
	'size' => 'thumbnail',
	'show_all_players_link' => false,
);

extract( $defaults, EXTR_SKIP );

$itemtag = tag_escape( $itemtag );
$captiontag = tag_escape( $captiontag );
$icontag = tag_escape( $icontag );
$valid_tags = wp_kses_allowed_html( 'post' );
if ( ! isset( $valid_tags[ $itemtag ] ) )
	$itemtag = 'dl';
if ( ! isset( $valid_tags[ $captiontag ] ) )
	$captiontag = 'dd';
if ( ! isset( $valid_tags[ $icontag ] ) )
	$icontag = 'dt';

$columns = intval( $columns );
$itemwidth = $columns > 0 ? floor(100/$columns) : 100;
$size = $size;
$float = is_rtl() ? 'right' : 'left';

$selector = 'sp-player-gallery-' . $id;

$data = sp_get_player_list_data( $id );

// The first row should be column labels
$labels = $data[0];

// Remove the first row to leave us with the actual data
unset( $data[0] );

if ( $orderby == 'default' ):
	$orderby = get_post_meta( $id, 'sp_orderby', true );
	$order = get_post_meta( $id, 'sp_order', true );
else:
	global $sportspress_performance_priorities;
	$sportspress_performance_priorities = array(
		array(
			'key' => $orderby,
			'order' => $order,
		),
	);
	uasort( $data, 'sp_sort_list_players' );
endif;

$gallery_style = $gallery_div = '';
if ( apply_filters( 'use_default_gallery_style', true ) )
	$gallery_style = "
	<style type='text/css'>
		#{$selector} {
			margin: auto;
		}
		#{$selector} .gallery-item {
			float: {$float};
			margin-top: 10px;
			text-align: center;
			width: {$itemwidth}%;
		}
		#{$selector} img {
			border: 2px solid #cfcfcf;
		}
		#{$selector} .gallery-caption {
			margin-left: 0;
		}
		/* see gallery_shortcode() in wp-includes/media.php */
	</style>";
$size_class = sanitize_html_class( $size );
$gallery_div = "<div id='$selector' class='gallery galleryid-{$id} gallery-columns-{$columns} gallery-size-{$size_class}'>";
$output = apply_filters( 'gallery_style', $gallery_style . "\n\t\t" . $gallery_div );

$i = 0;

if ( is_int( $number ) && $number > 0 )
	$limit = $number;

foreach( $data as $player_id => $performance ):

	$caption = get_the_title( $player_id );
	$player_number = get_post_meta( $player_id, 'sp_number', true );
	if ( $player_number ):
		$caption = '<strong>' . $player_number . '</strong> ' . $caption;
	endif;

	if ( isset( $limit ) && $i >= $limit )
		continue;

	if ( has_post_thumbnail( $player_id ) ):
		$thumbnail = get_the_post_thumbnail( $player_id, $size );

		$output .= "<{$itemtag} class='gallery-item'>";
		$output .= "
			<{$icontag} class='gallery-icon portrait'>"
				. '<a href="' . get_permalink( $player_id ) . '">' . $thumbnail . '</a>'
			. "</{$icontag}>";
		if ( $captiontag && trim( $caption ) ) {
			$output .= '<a href="' . get_permalink( $player_id ) . '">' . "
				<{$captiontag} class='wp-caption-text gallery-caption'>
				" . wptexturize($caption) . "
				</{$captiontag}>" . '</a>';
		}
		$output .= "</{$itemtag}>";
		if ( $columns > 0 && ++$i % $columns == 0 )
			$output .= '<br style="clear: both" />';
	endif;

endforeach;

$output .= "
		<br style='clear: both;' />
	</div>\n";

if ( $show_all_players_link )
	$output .= '<a class="sp-player-list-link sp-view-all-link" href="' . get_permalink( $id ) . '">' . SP()->text->string('View all players', 'player') . '</a>';

echo apply_filters( 'sportspress_player_gallery',  $output );
