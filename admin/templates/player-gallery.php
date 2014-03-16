<?php
if ( !function_exists( 'sportspress_player_gallery' ) ) {
	function sportspress_player_gallery( $id = null, $args = '' ) {

		if ( ! $id )
			$id = get_the_ID();

		$defaults = array(
			'orderby' => 'default',
			'order' => 'ASC',
			'itemtag' => 'dl',
			'icontag' => 'dt',
			'captiontag' => 'dd',
			'columns' => 3,
			'size' => 'thumbnail',
		);

		$r = wp_parse_args( $args, $defaults );

		$itemtag = tag_escape( $r['itemtag'] );
		$captiontag = tag_escape( $r['captiontag'] );
		$icontag = tag_escape( $r['icontag'] );
		$valid_tags = wp_kses_allowed_html( 'post' );
		if ( ! isset( $valid_tags[ $itemtag ] ) )
			$itemtag = 'dl';
		if ( ! isset( $valid_tags[ $captiontag ] ) )
			$captiontag = 'dd';
		if ( ! isset( $valid_tags[ $icontag ] ) )
			$icontag = 'dt';

		$columns = intval( $r['columns'] );
		$itemwidth = $columns > 0 ? floor(100/$columns) : 100;
		$size = $r[ 'size' ];
		$float = is_rtl() ? 'right' : 'left';

		$selector = 'sp-player-gallery-' . $id;

		$data = sportspress_get_player_list_data( $id );

		// The first row should be column labels
		$labels = $data[0];

		// Remove the first row to leave us with the actual data
		unset( $data[0] );

		$statistics = sportspress_array_value( $r, 'statistics', null );

		if ( $r['orderby'] == 'default' ):
			$r['orderby'] = get_post_meta( $id, 'sp_orderby', true );
			$r['order'] = get_post_meta( $id, 'sp_order', true );
		else:
			global $sportspress_statistic_priorities;
			$sportspress_statistic_priorities = array(
				array(
					'statistic' => $r['orderby'],
					'order' => $r['order'],
				),
			);
			uasort( $data, 'sportspress_sort_list_players' );
		endif;

		$i = 0;

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

		foreach( $data as $id => $statistics ):

			$caption = get_the_title( $id );

			$thumbnail = get_the_post_thumbnail( $id, $size );

			if ( $thumbnail ):
				$output .= "<{$itemtag} class='gallery-item'>";
				$output .= "
					<{$icontag} class='gallery-icon portrait'>"
						. '<a href="' . get_permalink( $id ) . '">' . $thumbnail . '</a>'
					. "</{$icontag}>";
				if ( $captiontag && trim($caption) ) {
					$output .= "
						<{$captiontag} class='wp-caption-text gallery-caption'>
						" . wptexturize($caption) . "
						</{$captiontag}>";
				}
				$output .= "</{$itemtag}>";
				if ( $columns > 0 && ++$i % $columns == 0 )
					$output .= '<br style="clear: both" />';

			endif;

		endforeach;

		$output .= "
				<br style='clear: both;' />
			</div>\n";

		return apply_filters( 'sportspress_player_gallery',  $output );

	}
}
