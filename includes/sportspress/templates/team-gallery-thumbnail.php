<?php
/**
 * Team Gallery Thumbnail
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Templates
 * @version   2.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$defaults = array(
	'id' => null,
	'icontag' => 'dt',
	'captiontag' => 'dd',
	'caption' => null,
	'size' => 'sportspress-crop-medium',
	'link_posts' => get_option( 'sportspress_link_teams', 'yes' ) == 'yes' ? true : false,
);

extract( $defaults, EXTR_SKIP );

// Add caption tag if has caption
if ( $captiontag && $caption )
	$caption = '<' . $captiontag . ' class="wp-caption-text gallery-caption small-3 columns">' . wptexturize( $caption ) . '</' . $captiontag . '>';

if ( $link_posts )
	$caption = '<a href="' . get_permalink( $id ) . '">' . $caption . '</a>';

if ( has_post_thumbnail( $id ) )
	$thumbnail = get_the_post_thumbnail( $id, $size );
else
	$thumbnail = '<img width="150" height="150" src="//www.gravatar.com/avatar/?s=150&d=blank&f=y" class="attachment-thumbnail wp-post-image">';

echo "<{$itemtag} class='gallery-item'>";
echo "
	<{$icontag} class='gallery-icon portrait'>"
		. '<a href="' . get_permalink( $id ) . '">' . $thumbnail . '</a>'
	. "</{$icontag}>";
echo $caption;
echo "</{$itemtag}>";
