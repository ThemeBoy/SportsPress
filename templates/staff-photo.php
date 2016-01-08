<?php
/**
 * Staff Photo
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Templates
 * @version     1.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if ( get_option( 'sportspress_staff_show_photo', 'yes' ) === 'no' ) return;

if ( ! isset( $id ) )
	$id = get_the_ID();

if ( has_post_thumbnail( $id ) ):
	?>
	<div class="sp-template sp-template-staff-photo sp-template-photo sp-staff-photo">
		<?php echo get_the_post_thumbnail( $id, 'sportspress-fit-medium' ); ?>
	</div>
	<?php
endif;