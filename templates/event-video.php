<?php
if ( ! isset( $id ) )
	$id = get_the_ID();

$video_url = get_post_meta( $id, 'sp_video', true );
if ( $video_url ):
    global $wp_embed;
    echo $wp_embed->autoembed( $video_url );
endif;
