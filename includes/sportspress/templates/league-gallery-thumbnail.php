<?php
/**
 * League Gallery Thumbnail
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Templates
 * @version     1.9.12
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$defaults = array(
	'id' => null,
	'icontag' => 'dt',
	'caption' => null,
	'size' => 'medium',
	'link_posts' => 'yes' == get_option( 'sportspress_link_teams', 'no' ) ? true : false,
);

extract( $defaults, EXTR_SKIP );

if ( isset( $limit ) && $i >= $limit )
	continue;

if ( has_post_thumbnail( $id ) )
	$thumbnail = get_the_post_thumbnail( $id, $size, array( 'alt' => $caption, 'title' => $caption ) );
else
	$thumbnail = $caption;

if ( $link_posts )
	$thumbnail = '<a href="' . get_permalink( $id ) . '">' . $thumbnail . '</a>';

echo "<{$itemtag} class='gallery-item'>";
echo "<{$icontag} class='gallery-icon portrait'>" . $thumbnail . "</{$icontag}>";
echo "</{$itemtag}>";
