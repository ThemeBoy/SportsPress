<?php
/**
 * Team Gallery
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Templates
 * @version   2.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$html5 = current_theme_supports( 'html5', 'gallery' );
$defaults = array(
	'id' => get_the_ID(),
	'title' => false,
	'number' => -1,
	'orderby' => 'default',
	'itemtag' => 'dl',
	'icontag' => 'dt',
	'captiontag' => 'dd',
	'columns' => 3,
	'size' => 'sportspress-crop-medium',
	'show_all_teams_link' => false,
	'link_posts' => get_option( 'sportspress_link_teams', 'yes' ) == 'yes' ? true : false,
);

extract( $defaults, EXTR_SKIP );

// Determine number of teams to display
if ( -1 === $number ):
	$number = (int) get_post_meta( $id, 'sp_number', true );
	if ( $number <= 0 ) $number = -1;
endif;

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

$selector = 'sp-team-gallery-' . $id;

$table = new SP_League_Table( $id );
$data = $table->data();

// Remove the first row to leave us with the actual data
unset( $data[0] );

if ( $orderby == 'name' ):
	uasort( $data, 'sp_sort_by_name' );
elseif ( $orderby == 'rand' ):
	uasort( $data, 'sp_sort_random' );
endif;

if ( $title )
	echo '<h4 class="sp-table-caption">' . $title . '</h4>';

$gallery_style = $gallery_div = '';
if ( apply_filters( 'use_default_gallery_style', ! $html5 ) )
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
echo apply_filters( 'gallery_style', $gallery_style . "\n\t\t" );
?>
<?php echo $gallery_div; ?>
	<?php
	if ( intval( $number ) > 0 )
		$limit = $number;

	$i = 0;

	$gallery = '';

	foreach( $data as $team_id => $row ):

		if ( isset( $limit ) && $i >= $limit ) continue;

		$caption = get_the_title( $team_id );
		$caption = trim( $caption );

		ob_start();

	    sp_get_template( 'team-gallery-thumbnail.php', array(
	    	'id' => $team_id,
	    	'itemtag' => $itemtag,
	    	'icontag' => $icontag,
	    	'captiontag' => $captiontag,
	    	'caption' => $caption,
	    	'size' => $size,
	    	'link_posts' => $link_posts,
	    ) );

		$gallery .= ob_get_clean();

		$i++;

	endforeach;

	echo '<div class="sp-template sp-template-team-gallery sp-template-gallery">';
	
	echo '<div class="sp-team-gallery-wrapper sp-gallery-wrapper">';
	
	echo $gallery;

	if ( ! $html5 && $columns > 0 && ++$i % $columns == 0 ) {
		echo '<br style="clear: both" />';
	}
	
	echo '</div>';

	if ( $show_all_teams_link ) {
		echo '<div class="sp-team-gallery-link sp-gallery-link sp-view-all-link"><a href="' . get_permalink( $id ) . '">' . __( 'View all teams', 'sportspress' ) . '</a></div>';
	}

	echo '</div>';

	if ( ! $html5 && $columns > 0 && ++$i % $columns == 0 ) {
		echo '<br style="clear: both" />';
	}
		
echo "</div>\n";
?>