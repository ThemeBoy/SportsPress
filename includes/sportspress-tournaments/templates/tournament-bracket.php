<?php
/**
 * Tournament
 *
 * @author 		ThemeBoy
 * @package 	SportsPress_Tournaments
 * @version     1.7
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$defaults = array(
	'id' => get_the_ID(),
	'title' => false,
	'show_logos' => get_option( 'sportspress_tournament_show_logos', 'yes' ) == 'yes' ? true : false,
	'show_venue' => get_option( 'sportspress_tournament_show_venue', 'no' ) == 'yes' ? true : false,
	'link_teams' => get_option( 'sportspress_link_teams', 'no' ) == 'yes' ? true : false,
	'link_events' => get_option( 'sportspress_link_events', 'yes' ) == 'yes' ? true : false,
	'responsive' => get_option( 'sportspress_enable_responsive_tables', 'yes' ) == 'yes' ? true : false,
	'layout' => 'bracket',
);

extract( $defaults, EXTR_SKIP );

$post_layout = get_post_meta( $id, 'sp_format', true );

if ( $post_layout )
	$layout = $post_layout;

if ( $title )
	echo '<h4 class="sp-table-caption">' . $title . '</h4>';

if ( $responsive && 'center' == $layout ) {
	?>
	<div class="sp-template sp-template-tournament-bracket sp-mobile">
		<?php
		sp_get_template( 'tournament-bracket-table.php', array(
			'id' => $id,
			'show_logos' => $show_logos,
			'show_venue' => $show_venue,
			'link_teams' => $link_teams,
			'link_events' => $link_events,
			'layout' => 'bracket',
		), '', SP_TOURNAMENTS_DIR . 'templates/' );
		?>
	</div>
	<div class="sp-template sp-template-tournament-bracket sp-desktop">
		<?php
		sp_get_template( 'tournament-bracket-table.php', array(
			'id' => $id,
			'show_logos' => $show_logos,
			'show_venue' => $show_venue,
			'link_teams' => $link_teams,
			'link_events' => $link_events,
			'layout' => $layout,
		), '', SP_TOURNAMENTS_DIR . 'templates/' );
		?>
	</div>
	<?php
} else {
	?>
	<div class="sp-template sp-template-tournament-bracket">
		<?php
		sp_get_template( 'tournament-bracket-table.php', array(
			'id' => $id,
			'show_logos' => $show_logos,
			'show_venue' => $show_venue,
			'link_teams' => $link_teams,
			'link_events' => $link_events,
			'layout' => $layout,
		), '', SP_TOURNAMENTS_DIR . 'templates/' );
		?>
	</div>
	<?php
}