<?php
/**
 * Event Teams
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Meta_Boxes
 * @version     1.9
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * SP_Meta_Box_Event_Teams
 */
class SP_Meta_Box_Event_Teams {

	/**
	 * Output the metabox
	 */
	public static function output( $post ) {
		$limit = get_option( 'sportspress_event_teams', 2 );
		$teams = (array) get_post_meta( $post->ID, 'sp_team', false );
		if ( $limit ) {
			for ( $i = 0; $i < $limit; $i ++ ):
				$team = array_shift( $teams );
				?>
				<div class="sp-instance">
					<p class="sp-tab-select sp-title-generator">
					<?php
					$args = array(
						'post_type' => 'sp_team',
						'name' => 'sp_team[]',
						'class' => 'sportspress-pages',
						'show_option_none' => __( '&mdash; None &mdash;', 'sportspress' ),
						'values' => 'ID',
						'selected' => $team,
						'chosen' => true,
						'tax_query' => array(),
					);
					if ( 'yes' == get_option( 'sportspress_event_filter_teams_by_league', 'no' ) ) {
						$league_id = sp_get_the_term_id( $post->ID, 'sp_league', 0 );
						if ( $league_id ) {
							$args['tax_query'][] = array(
								'taxonomy' => 'sp_league',
								'terms' => $league_id,
							);
						}
					}
					if ( 'yes' == get_option( 'sportspress_event_filter_teams_by_season', 'no' ) ) {
						$season_id = sp_get_the_term_id( $post->ID, 'sp_season', 0 );
						if ( $season_id ) {
							$args['tax_query'][] = array(
								'taxonomy' => 'sp_season',
								'terms' => $season_id,
							);
						}
					}
					if ( ! sp_dropdown_pages( $args ) ) {
						unset( $args['tax_query'] );
						sp_dropdown_pages( $args );
					}
					?>
					</p>
					<?php $tabs = apply_filters( 'sportspress_event_team_tabs', array( 'sp_player', 'sp_staff' ) ); ?>
					<?php if ( $tabs ) { ?>
					<ul id="sp_team-tabs" class="wp-tab-bar sp-tab-bar">
						<?php foreach ( $tabs as $index => $post_type ) { $object = get_post_type_object( $post_type ); ?>
						<li class="wp-tab<?php if ( 0 == $index ) { ?>-active<?php } ?>"><a href="#<?php echo $post_type; ?>-all"><?php echo $object->labels->name; ?></a></li>
						<?php } ?>
					</ul>
					<?php
						foreach ( $tabs as $index => $post_type ) {
							do_action( 'sportspress_event_teams_meta_box_checklist', $post->ID, $post_type, ( 0 == $index ? 'block' : 'none' ), $team, $i );
							if ( apply_filters( 'sportspress_event_teams_meta_box_default_checklist', true ) ) {
								sp_post_checklist( $post->ID, $post_type, ( 0 == $index ? 'block' : 'none' ), array( 'sp_league', 'sp_season', 'sp_current_team' ), $i );
							}
						}
					?>
					<?php } ?>
				</div>
				<?php
			endfor;
		} else {
			?>
			<p><strong><?php printf( __( 'Select %s:', 'sportspress' ), __( 'Teams', 'sportspress' ) ); ?></strong></p>
			<?php
			$args = array(
				'post_type' => 'sp_team',
				'name' => 'sp_team[]',
				'selected' => $teams,
				'values' => 'ID',
				'class' => 'widefat',
				'property' => 'multiple',
				'chosen' => true,
				'placeholder' => __( 'None', 'sportspress' ),
			);
			if ( ! sp_dropdown_pages( $args ) ):
				sp_post_adder( 'sp_team', __( 'Add New', 'sportspress' )  );
			endif;
		}
		wp_nonce_field( 'sp-get-players', 'sp-get-players-nonce', false );
	}

	/**
	 * Save meta box data
	 */
	public static function save( $post_id, $post ) {
		sp_update_post_meta_recursive( $post_id, 'sp_team', sp_array_value( $_POST, 'sp_team', array() ) );
		$tabs = apply_filters( 'sportspress_event_team_tabs', array( 'sp_player', 'sp_staff' ) );
		if ( $tabs ) {
			foreach ( $tabs as $post_type ) {
				sp_update_post_meta_recursive( $post_id, $post_type, sp_array_value( $_POST, $post_type, array() ) );
			}
		}
	}
}
