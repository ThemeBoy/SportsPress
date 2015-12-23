<?php
/**
 * League Gallery
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Templates
 * @version     1.9.12
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$html5 = current_theme_supports( 'html5', 'gallery' );
$defaults = array(
	'id' => get_the_ID(),
	'title' => false,
	'number' => -1,
	'itemtag' => 'dl',
	'icontag' => 'dt',
	'columns' => 3,
	'size' => 'medium',
	'show_full_table_link' => false,
	'link_posts' => 'yes' == get_option( 'sportspress_link_teams', 'no' ) ? true : false,
);

extract( $defaults, EXTR_SKIP );

$itemtag = tag_escape( $itemtag );
$icontag = tag_escape( $icontag );
$valid_tags = wp_kses_allowed_html( 'post' );
if ( ! isset( $valid_tags[ $itemtag ] ) )
	$itemtag = 'dl';
if ( ! isset( $valid_tags[ $icontag ] ) )
	$icontag = 'dt';

$columns = intval( $columns );
$itemwidth = $columns > 0 ? floor(100/$columns) : 100;
$size = $size;
$float = is_rtl() ? 'right' : 'left';

$selector = 'sp-league-gallery-' . $id;

$table = new SP_League_Table( $id );
$data = $table->data();

// Remove the first row to leave us with the actual data
unset( $data[0] );

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
echo $gallery_div;
if ( intval( $number ) > 0 )
	$limit = $number;

$i = 0;
?>
<div class="sp-template sp-template-league-gallery sp-template-gallery">
	<div class="sp-league-gallery-wrapper">
		<?php
		foreach ( $data as $team_id => $stats ):

			if ( isset( $limit ) && $i >= $limit ) continue;

			$caption = get_the_title( $team_id );
			$caption = trim( $caption );

		    sp_get_template( 'league-gallery-thumbnail.php', array(
		    	'id' => $team_id,
		    	'itemtag' => $itemtag,
		    	'icontag' => $icontag,
		    	'caption' => sp_array_value( $stats, 'name', null ),
		    	'size' => $size,
		    	'link_posts' => $link_posts,
		    ) );

			$i++;

			if ( ! $html5 && $columns > 0 && ++$i % $columns == 0 ) {
				echo '<br style="clear: both" />';
			}

			if ( $show_full_table_link ) {
				echo '<div class="sp-league-gallery-link sp-gallery-link sp-view-all-link"><a href="' . get_permalink( $id ) . '">' . __( 'View all teams', 'sportspress' ) . '</a></div>';
			}

		endforeach;

		if ( ! $html5 && $columns > 0 && ++$i % $columns == 0 ) {
			echo '<br style="clear: both" />';
		}
		?>
	</div>
</div>
<?php	
echo "</div>\n";
