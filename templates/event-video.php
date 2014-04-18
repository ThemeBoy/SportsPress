<?php
/**
 * Event Video
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Templates
 * @version     0.8
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! isset( $id ) )
	$id = get_the_ID();

$video_url = get_post_meta( $id, 'sp_video', true );
if ( $video_url ):
    global $wp_embed;
    echo $wp_embed->autoembed( $video_url );
endif;
