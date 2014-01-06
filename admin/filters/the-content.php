<?php
function sportspress_the_content( $content ) {
    if ( is_singular( 'sp_team' ) && in_the_loop() ):
    
    elseif ( is_singular( 'sp_table' ) && in_the_loop() ):

    	global $post;

        // Display league table
        $content .= '<p>' . sp_get_table_html( $post->ID  ) . '</p>';
    
    elseif ( is_singular( 'sp_list' ) && in_the_loop() ):

    	global $post;

        // Display player list
        $content .= '<p>' . sp_get_list_html( $post->ID  ) . '</p>';

    endif;

    return $content;
}
add_filter('the_content', 'sportspress_the_content');
?>