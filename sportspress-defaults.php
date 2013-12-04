<?php
if ( !function_exists( 'sp_install_defaults' ) ) {
	function sp_install_defaults() {
	    $installed = get_option( 'sportspress_installed', false );
		if ( ! $installed ):

			$pages = array(

				// Results
				array( 'post_title' => 'Goals', 'post_name' => 'goals', 'post_status' => 'publish', 'post_type' => 'sp_result' ),
				array( 'post_title' => '1st Half', 'post_name' => 'firsthalf', 'post_status' => 'publish', 'post_type' => 'sp_result' ),
				array( 'post_title' => '2nd Half', 'post_name' => 'secondhalf', 'post_status' => 'publish', 'post_type' => 'sp_result' ),

				// Outcomes
				array( 'post_title' => 'Win', 'post_name' => 'win', 'post_status' => 'publish', 'post_type' => 'sp_outcome' ),
				array( 'post_title' => 'Draw', 'post_name' => 'draw', 'post_status' => 'publish', 'post_type' => 'sp_outcome' ),
				array( 'post_title' => 'Loss', 'post_name' => 'loss', 'post_status' => 'publish', 'post_type' => 'sp_outcome' ),

				// Statistics
				array( 'post_title' => 'P', 'post_name' => 'p', 'post_status' => 'publish', 'post_type' => 'sp_stat', 'meta' => array( 'sp_equation' => '$eventsplayed' ) ),
				array( 'post_title' => 'W', 'post_name' => 'w', 'post_status' => 'publish', 'post_type' => 'sp_stat', 'meta' => array( 'sp_equation' => '$win' ) ),
				array( 'post_title' => 'D', 'post_name' => 'd', 'post_status' => 'publish', 'post_type' => 'sp_stat', 'meta' => array( 'sp_equation' => '$draw' ) ),
				array( 'post_title' => 'L', 'post_name' => 'l', 'post_status' => 'publish', 'post_type' => 'sp_stat', 'meta' => array( 'sp_equation' => '$loss' ) ),
				array( 'post_title' => 'F', 'post_name' => 'f', 'post_status' => 'publish', 'post_type' => 'sp_stat', 'meta' => array( 'sp_equation' => '$goalsfor', 'sp_priority' => '3', 'sp_order' => 'DESC' ) ),
				array( 'post_title' => 'A', 'post_name' => 'a', 'post_status' => 'publish', 'post_type' => 'sp_stat', 'meta' => array( 'sp_equation' => '$goalsagainst' ) ),
				array( 'post_title' => 'GD', 'post_name' => 'gd', 'post_status' => 'publish', 'post_type' => 'sp_stat', 'meta' => array( 'sp_equation' => '$goalsfor - $goalsagainst', 'sp_priority' => '2', 'sp_order' => 'DESC' ) ),
				array( 'post_title' => 'PTS', 'post_name' => 'pts', 'post_status' => 'publish', 'post_type' => 'sp_stat', 'meta' => array( 'sp_equation' => '$win x 3 + $draw', 'sp_priority' => '1', 'sp_order' => 'DESC' ) ),

				// Metrics
				array( 'post_title' => 'Appearances', 'post_name' => 'appearances', 'post_status' => 'publish', 'post_type' => 'sp_metric', 'meta' => array( 'sp_equation' => '$eventsplayed' ) ),
				array( 'post_title' => 'Goals', 'post_name' => 'goals', 'post_status' => 'publish', 'post_type' => 'sp_metric', 'meta' => array( 'sp_equation' => '' ) ),
				array( 'post_title' => 'Assists', 'post_name' => 'assists', 'post_status' => 'publish', 'post_type' => 'sp_metric', 'meta' => array( 'sp_equation' => '' ) ),
				array( 'post_title' => 'Yellow Cards', 'post_name' => 'yellowcards', 'post_status' => 'publish', 'post_type' => 'sp_metric', 'meta' => array( 'sp_equation' => '' ) ),
				array( 'post_title' => 'Red Cards', 'post_name' => 'redcards', 'post_status' => 'publish', 'post_type' => 'sp_metric', 'meta' => array( 'sp_equation' => '' ) )
			);

			$i = 1;
			foreach( $pages as $args ):
				if ( ! get_page_by_path( $args['post_name'], OBJECT, $args['post_type'] ) ):
					$args['menu_order'] = $i;
					$id = wp_insert_post( $args );
					if ( array_key_exists( 'meta', $args ) ):
						foreach ( $args['meta'] as $key => $value ):
							update_post_meta( $id, $key, $value );
						endforeach;
					endif;
					$i++;
				endif;
			endforeach;

			update_option( 'sportspress_installed', 1 );
		endif;
    }
}
sp_install_defaults();
?>