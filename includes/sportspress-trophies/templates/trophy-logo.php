<?php
/**
 * Trophy Logo
 *
 * @author 		ThemeBoy
 * @package 	SportsPress Trophies
 * @version     2.8
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if ( get_option( 'sportspress_trophy_show_logo', 'yes' ) === 'no' ) return;

if ( ! isset( $id ) )
	$id = get_the_ID();

if ( has_post_thumbnail( $id ) ):
	?>
	<div class="sp-template sp-template-trophy-photo sp-template-photo sp-trophy-photo">
		<?php echo get_the_post_thumbnail( $id, 'sportspress-fit-medium' ); ?>
	</div>
	<?php
endif;