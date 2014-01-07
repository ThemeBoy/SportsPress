<?php
function sportspress_the_content( $content ) {
    if ( is_singular( 'sp_team' ) && in_the_loop() ):
    
    elseif ( is_singular( 'sp_table' ) && in_the_loop() ):

    	global $post;

        // Display league table
        $content .= '<p>' . sportspress_league_table( $post->ID  ) . '</p>';
    
    elseif ( is_singular( 'sp_list' ) && in_the_loop() ):

    	global $post;

        // Display player list
        $content .= '<p>' . sportspress_player_list( $post->ID  ) . '</p>';

    endif;

    return $content;
}
add_filter('the_content', 'sportspress_the_content');
