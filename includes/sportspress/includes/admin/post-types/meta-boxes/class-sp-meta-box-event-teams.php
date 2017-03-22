<?php
/**
 * Event Teams
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Meta_Boxes
 * @version     2.3
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
		$post_type = sp_get_post_mode_type( $post->ID );
		if ( $limit && 'sp_player' !== $post_type ) {
			for ( $i = 0; $i < $limit; $i ++ ):
				$team = array_shift( $teams );
				?>
				<div class="sp-instance">
					<p class="sp-tab-select sp-title-generator">
					<?php
					$args = array(
						'post_type' => $post_type,
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
					<?php
					$tabs = array();
					$sections = get_option( 'sportspress_event_performance_sections', -1 );
					if ( 0 == $sections ) {
						$tabs['sp_offense'] = array(
							'label' => __( 'Offense', 'sportspress' ),
							'post_type' => 'sp_player',
						);
						$tabs['sp_defense'] = array(
							'label' => __( 'Defense', 'sportspress' ),
							'post_type' => 'sp_player',
						);
					} elseif ( 1 == $sections ) {
						$tabs['sp_defense'] = array(
							'label' => __( 'Defense', 'sportspress' ),
							'post_type' => 'sp_player',
						);
						$tabs['sp_offense'] = array(
							'label' => __( 'Offense', 'sportspress' ),
							'post_type' => 'sp_player',
						);
					} else {
						$tabs['sp_player'] = array(
							'label' => __( 'Players', 'sportspress' ),
							'post_type' => 'sp_player',
						);
					}
					$tabs['sp_staff'] = array(
						'label' => __( 'Staff', 'sportspress' ),
						'post_type' => 'sp_staff',
					);
					?>
					<?php if ( $tabs ) { ?>
					<ul id="sp_team-tabs" class="sp-tab-bar category-tabs">
						<?php
							$j = 0;
							foreach ( $tabs as $slug => $tab ) {
								?>
								<li class="<?php if ( 0 == $j ) { ?>tabs<?php } ?>"><a href="#<?php echo $slug; ?>-all"><?php echo $tab['label']; ?></a></li>
								<?php
								$j++;
							}
						?>
					</ul>
					<?php
						$j = 0;
						foreach ( $tabs as $slug => $tab ) {
							do_action( 'sportspress_event_teams_meta_box_checklist', $post->ID, $tab['post_type'], ( 0 == $j ? 'block' : 'none' ), $team, $i, $slug );
							$j++;
						}
					?>
					<?php } ?>
				</div>
				<?php
			endfor;
		} else {
			?>
			<p><strong><?php printf( __( 'Select %s:', 'sportspress' ), sp_get_post_mode_label( $post->ID ) ); ?></strong></p>
			<?php
			$args = array(
				'post_type' => $post_type,
				'name' => 'sp_team[]',
				'selected' => $teams,
				'values' => 'ID',
				'class' => 'widefat',
				'property' => 'multiple',
				'chosen' => true,
				'placeholder' => __( 'None', 'sportspress' ),
			);
			if ( ! sp_dropdown_pages( $args ) ):
				sp_post_adder( $post_type, __( 'Add New', 'sportspress' )  );
			endif;
		}
		wp_nonce_field( 'sp-get-players', 'sp-get-players-nonce', false );
	}

	/**
	 * Save meta box data
	 */
	public static function save( $post_id, $post ) {
		$teams = sp_array_value( $_POST, 'sp_team', array() );

		sp_update_post_meta_recursive( $post_id, 'sp_team', $teams );

		$post_type = sp_get_post_mode_type( $post->ID );

		if ( 'sp_player' === $post_type ) {
			$players = array();
			foreach ( $teams as $player ) {
				$players[] = array( 0, $player );
			}
			sp_update_post_meta_recursive( $post_id, 'sp_player', $players );
		} else {
			$tabs = array();
			$sections = get_option( 'sportspress_event_performance_sections', -1 );
			if ( -1 == $sections ) {
				sp_update_post_meta_recursive( $post_id, 'sp_player', sp_array_value( $_POST, 'sp_player', array() ) );
			} else {
				$players = array_merge( sp_array_value( $_POST, 'sp_offense', array() ), sp_array_value( $_POST, 'sp_defense', array() ) );
				sp_update_post_meta_recursive( $post_id, 'sp_offense', sp_array_value( $_POST, 'sp_offense', array() ) );
				sp_update_post_meta_recursive( $post_id, 'sp_defense', sp_array_value( $_POST, 'sp_defense', array() ) );
				sp_update_post_meta_recursive( $post_id, 'sp_player', $players );
			}
			sp_update_post_meta_recursive( $post_id, 'sp_staff', sp_array_value( $_POST, 'sp_staff', array() ) );
		}
	}
}
