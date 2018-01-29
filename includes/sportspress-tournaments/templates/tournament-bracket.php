<?php
/**
 * Tournament
 *
 * @author 		ThemeBoy
 * @package 	SportsPress_Tournaments
 * @version     2.3
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$defaults = array(
	'id' => get_the_ID(),
	'title' => false,
	'show_logos' => get_option( 'sportspress_tournament_show_logos', 'yes' ) == 'yes' ? true : false,
	'show_venue' => get_option( 'sportspress_tournament_show_venue', 'no' ) == 'yes' ? true : false,
	'link_teams' => null,
	'link_events' => get_option( 'sportspress_link_events', 'yes' ) == 'yes' ? true : false,
	'responsive' => get_option( 'sportspress_enable_responsive_tables', 'no' ) == 'yes' ? true : false,
	'layout' => 'bracket',
);

extract( $defaults, EXTR_SKIP );

if ( ! isset( $link_teams ) ) {
	if ( 'player' === sp_get_post_mode( $id ) ) {
		$link_teams = get_option( 'sportspress_link_players', 'yes' ) == 'yes' ? true : false;
	} else {
		$link_teams = get_option( 'sportspress_link_teams', 'no' ) == 'yes' ? true : false;
	}
}

$post_layout = get_post_meta( $id, 'sp_format', true );
$tournament_type = get_post_meta( $id, 'sp_type', true );

if ( $post_layout )
	$layout = $post_layout;

if ( false === $title && $id ):
	$caption = get_post_meta( $id, 'sp_caption', true );
	if ( $caption )
		$title = $caption;
endif;

if ( 'double' === $tournament_type ) {
	$types = array(
		'winners' => __( "Winner Bracket", 'sportspress' ),
		'losers' => __( "Loser Bracket", 'sportspress' ),
		'finals' => __( "Final Bracket", 'sportspress' ),
	);
} else {
	$types = array(
		'single' => $title,
	);
}

foreach ( $types as $type => $name ) {
	if ( $name ) {
		echo '<h4 class="sp-table-caption">' . $name . '</h4>';
	}

	if ( $responsive && 'center' == $layout ) {
		?>
		<div class="sp-template sp-template-tournament-bracket sp-tournament-bracket-<?php echo $tournament_type; ?> sp-mobile">
			<?php
			sp_get_template( 'tournament-bracket-table.php', array(
				'id' => $id,
				'show_logos' => $show_logos,
				'show_venue' => $show_venue,
				'link_teams' => $link_teams,
				'link_events' => $link_events,
				'layout' => 'bracket',
				'type' => $type,
			), '', SP_TOURNAMENTS_DIR . 'templates/' );
			?>
		</div>
		<div class="sp-template sp-template-tournament-bracket sp-tournament-bracket-<?php echo $tournament_type; ?> sp-desktop">
			<?php
			sp_get_template( 'tournament-bracket-table.php', array(
				'id' => $id,
				'show_logos' => $show_logos,
				'show_venue' => $show_venue,
				'link_teams' => $link_teams,
				'link_events' => $link_events,
				'layout' => $layout,
				'type' => $type,
			), '', SP_TOURNAMENTS_DIR . 'templates/' );
			?>
		</div>
		<?php
	} else {
		?>
		<div class="sp-template sp-template-tournament-bracket sp-tournament-bracket-<?php echo $tournament_type; ?>">
			<?php
			sp_get_template( 'tournament-bracket-table.php', array(
				'id' => $id,
				'show_logos' => $show_logos,
				'show_venue' => $show_venue,
				'link_teams' => $link_teams,
				'link_events' => $link_events,
				'layout' => $layout,
				'type' => $type,
			), '', SP_TOURNAMENTS_DIR . 'templates/' );
			?>
		</div>
		<?php
	}
}