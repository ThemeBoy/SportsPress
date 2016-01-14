<?php
/**
 * Player Photo
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Templates
 * @version     1.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if ( get_option( 'sportspress_player_show_photo', 'yes' ) === 'no' ) return;

if ( ! isset( $id ) )
	$id = get_the_ID();

if ( has_post_thumbnail( $id ) ):
	?>
	<div class="sp-template sp-template-player-photo sp-template-photo sp-player-photo">
		<?php echo get_the_post_thumbnail( $id, 'sportspress-fit-medium' ); ?>
	</div>
	<?php
endif;