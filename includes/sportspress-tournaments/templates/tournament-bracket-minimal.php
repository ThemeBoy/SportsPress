<?php
/**
 * Tournament Bracket Minimal
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Tournaments
 * @version     2.1.7
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$defaults = array(
	'id' => get_the_ID(),
	'show_logos' => get_option( 'sportspress_tournament_show_logos', 'yes' ) == 'yes' ? true : false,
	'show_venue' => get_option( 'sportspress_tournament_show_venue', 'no' ) == 'yes' ? true : false,
	'link_teams' => get_option( 'sportspress_link_teams', 'no' ) == 'yes' ? true : false,
	'link_events' => get_option( 'sportspress_link_events', 'yes' ) == 'yes' ? true : false,
	'abbreviate_teams' => get_option( 'sportspress_abbreviate_teams', 'yes' ) === 'yes' ? true : false,
	'scrollable' => get_option( 'sportspress_enable_scrollable_tables', 'yes' ) == 'yes' ? true : false,
	'type' => 'single',
);

extract( $defaults, EXTR_SKIP );

$tournament = new SP_Tournament( $id );
list( $teams, $results ) = $tournament->minimal( $type );
?>
<div id="sp-tournament-bracket-minimal-<?php echo $id; ?>"></div>
<script type="text/javascript">
(function($) {
    $('#sp-tournament-bracket-minimal-<?php echo $id; ?>').bracket({
    	centerConnectors: true,
		skipConsolationRound: true,
		dir: '<?php echo ( is_rtl() ? 'rl' : 'lr' ); ?>',
		onMatchClick: function(link) {
			if (link) window.location.href = link;
		},
		init: {
		  teams: <?php echo json_encode( $teams, JSON_PRETTY_PRINT ); ?>,
		  results: <?php echo json_encode( $results, JSON_PRETTY_PRINT ); ?>
		}
	});
})(jQuery);
</script>