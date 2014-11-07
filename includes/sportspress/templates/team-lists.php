<?php
/**
 * Team Player Lists
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Templates
 * @version     1.1
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! isset( $id ) )
	$id = get_the_ID();

$team = new SP_Team( $id );
$lists = $team->lists();

foreach ( $lists as $list ):
	$id = $list->ID;
	$grouping = get_post_meta( $id, 'sp_grouping', true );

	if ( $grouping == 0 && sizeof( $lists ) > 1 ):
		?>
		<h4 class="sp-table-caption"><?php echo $list->post_title; ?></h4>
		<?php
	endif;

	$format = get_post_meta( $id, 'sp_format', true );
	if ( array_key_exists( $format, SP()->formats->list ) )
		sp_get_template( 'player-' . $format . '.php', array( 'id' => $id ) );
	else
		sp_get_template( 'player-list.php', array( 'id' => $id ) );
endforeach;
