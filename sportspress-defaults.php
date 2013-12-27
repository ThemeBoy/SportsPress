<?php
if ( !function_exists( 'sportspress_install' ) ) {
	function sportspress_install() {
	    $installed = get_option( 'sportspress_installed', false );
		if ( ! $installed ):

		    $role = get_role( 'administrator' );

		    // Events
		    $role->add_cap( 'edit_sp_event' );
		    $role->add_cap( 'edit_sp_events' );
		    $role->add_cap( 'edit_others_sp_events' );
		    $role->add_cap( 'delete_sp_events' );
		    $role->add_cap( 'publish_sp_events' );
		    $role->add_cap( 'read_sp_events' );
		    $role->add_cap( 'read_private_sp_events' );

		    // Teams
		    $role->add_cap( 'edit_sp_team' );
		    $role->add_cap( 'edit_sp_teams' );
		    $role->add_cap( 'edit_others_sp_teams' );
		    $role->add_cap( 'delete_sp_teams' );
		    $role->add_cap( 'publish_sp_teams' );
		    $role->add_cap( 'read_sp_teams' );
		    $role->add_cap( 'read_private_sp_teams' );

		    // League Tables
		    $role->add_cap( 'edit_sp_table' );
		    $role->add_cap( 'edit_sp_tables' );
		    $role->add_cap( 'edit_others_sp_tables' );
		    $role->add_cap( 'delete_sp_tables' );
		    $role->add_cap( 'publish_sp_tables' );
		    $role->add_cap( 'read_sp_tables' );
		    $role->add_cap( 'read_private_sp_tables' );

		    // Players
		    $role->add_cap( 'edit_sp_player' );
		    $role->add_cap( 'edit_sp_players' );
		    $role->add_cap( 'edit_others_sp_players' );
		    $role->add_cap( 'delete_sp_players' );
		    $role->add_cap( 'publish_sp_players' );
		    $role->add_cap( 'read_sp_players' );
		    $role->add_cap( 'read_private_sp_players' );

		    // Player Lists
		    $role->add_cap( 'edit_sp_list' );
		    $role->add_cap( 'edit_sp_lists' );
		    $role->add_cap( 'edit_others_sp_lists' );
		    $role->add_cap( 'delete_sp_lists' );
		    $role->add_cap( 'publish_sp_lists' );
		    $role->add_cap( 'read_sp_lists' );
		    $role->add_cap( 'read_private_sp_lists' );

		    // Staff
		    $role->add_cap( 'edit_sp_staff' );
		    $role->add_cap( 'edit_sp_staffs' );
		    $role->add_cap( 'edit_others_sp_staffs' );
		    $role->add_cap( 'delete_sp_staffs' );
		    $role->add_cap( 'publish_sp_staffs' );
		    $role->add_cap( 'read_sp_staffs' );
		    $role->add_cap( 'read_private_sp_staffs' );

		    // Settings
		    $role->add_cap( 'edit_sp_config' );
		    $role->add_cap( 'edit_sp_configs' );
		    $role->add_cap( 'edit_others_sp_configs' );
		    $role->add_cap( 'delete_sp_configs' );
		    $role->add_cap( 'publish_sp_configs' );
		    $role->add_cap( 'read_sp_configs' );
		    $role->add_cap( 'read_private_sp_configs' );

		    // Team Manager
			remove_role( 'sp_team_manager' );
			add_role(
			    'sp_team_manager',
			    __( 'Team Manager', 'sportspress' ),
			    array(
			        'read' => true,
			        'edit_posts' => true,
			        'delete_posts' => true,
			        'read_sp_players' => true,
			        'edit_sp_players' => true,
			        'edit_others_sp_players' => true,
			        'delete_sp_players' => true,
			        'publish_sp_players' => true,
			        'read_sp_staff' => true,
			        'edit_sp_staff' => true,
			        'edit_others_sp_staff' => true,
			        'delete_sp_staff' => true,
			        'publish_sp_staff' => true
			    )
			);

			// Staff
			remove_role( 'sp_staff' );
			add_role(
			    'sp_staff',
			    __( 'Staff', 'sportspress' ),
			    array(
			        'read' => true,
			        'edit_posts' => true,
			        'delete_posts' => true,
			        'read_sp_staff' => true,
			        'edit_sp_staff' => true,
			        'delete_sp_staff' => true
			    )
			);

			// Player
			remove_role( 'sp_player' );
			add_role(
			    'sp_player',
			    __( 'Player', 'sportspress' ),
			    array(
			        'read' => true,
			        'edit_posts' => true,
			        'delete_posts' => true,
			        'read_sp_players' => true,
			        'edit_sp_players' => true,
			        'delete_sp_players' => true
			    )
			);

			$pages = array(

				// Results
				array( 'post_title' => 'Goals', 'post_name' => 'goals', 'post_status' => 'publish', 'post_type' => 'sp_result' ),
				array( 'post_title' => '1st Half', 'post_name' => 'firsthalf', 'post_status' => 'publish', 'post_type' => 'sp_result' ),
				array( 'post_title' => '2nd Half', 'post_name' => 'secondhalf', 'post_status' => 'publish', 'post_type' => 'sp_result' ),

				// Outcomes
				array( 'post_title' => 'Win', 'post_name' => 'win', 'post_status' => 'publish', 'post_type' => 'sp_outcome' ),
				array( 'post_title' => 'Draw', 'post_name' => 'draw', 'post_status' => 'publish', 'post_type' => 'sp_outcome' ),
				array( 'post_title' => 'Loss', 'post_name' => 'loss', 'post_status' => 'publish', 'post_type' => 'sp_outcome' ),

				// Columns
				array( 'post_title' => 'P', 'post_name' => 'p', 'post_status' => 'publish', 'post_type' => 'sp_column', 'meta' => array( 'sp_equation' => '$eventsplayed' ) ),
				array( 'post_title' => 'W', 'post_name' => 'w', 'post_status' => 'publish', 'post_type' => 'sp_column', 'meta' => array( 'sp_equation' => '$win' ) ),
				array( 'post_title' => 'D', 'post_name' => 'd', 'post_status' => 'publish', 'post_type' => 'sp_column', 'meta' => array( 'sp_equation' => '$draw' ) ),
				array( 'post_title' => 'L', 'post_name' => 'l', 'post_status' => 'publish', 'post_type' => 'sp_column', 'meta' => array( 'sp_equation' => '$loss' ) ),
				array( 'post_title' => 'F', 'post_name' => 'f', 'post_status' => 'publish', 'post_type' => 'sp_column', 'meta' => array( 'sp_equation' => '$goalsfor', 'sp_priority' => '3', 'sp_order' => 'DESC' ) ),
				array( 'post_title' => 'A', 'post_name' => 'a', 'post_status' => 'publish', 'post_type' => 'sp_column', 'meta' => array( 'sp_equation' => '$goalsagainst' ) ),
				array( 'post_title' => 'GD', 'post_name' => 'gd', 'post_status' => 'publish', 'post_type' => 'sp_column', 'meta' => array( 'sp_equation' => '$goalsfor - $goalsagainst', 'sp_priority' => '2', 'sp_order' => 'DESC' ) ),
				array( 'post_title' => 'PTS', 'post_name' => 'pts', 'post_status' => 'publish', 'post_type' => 'sp_column', 'meta' => array( 'sp_equation' => '$win * 3 + $draw', 'sp_priority' => '1', 'sp_order' => 'DESC' ) ),

				// Statistics
				array( 'post_title' => 'Appearances', 'post_name' => 'appearances', 'post_status' => 'publish', 'post_type' => 'sp_statistic', 'meta' => array( 'sp_equation' => '$eventsplayed' ) ),
				array( 'post_title' => 'Goals', 'post_name' => 'goals', 'post_status' => 'publish', 'post_type' => 'sp_statistic', 'meta' => array( 'sp_equation' => '' ) ),
				array( 'post_title' => 'Assists', 'post_name' => 'assists', 'post_status' => 'publish', 'post_type' => 'sp_statistic', 'meta' => array( 'sp_equation' => '' ) ),
				array( 'post_title' => 'Yellow Cards', 'post_name' => 'yellowcards', 'post_status' => 'publish', 'post_type' => 'sp_statistic', 'meta' => array( 'sp_equation' => '' ) ),
				array( 'post_title' => 'Red Cards', 'post_name' => 'redcards', 'post_status' => 'publish', 'post_type' => 'sp_statistic', 'meta' => array( 'sp_equation' => '' ) )
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
sportspress_install();
?>