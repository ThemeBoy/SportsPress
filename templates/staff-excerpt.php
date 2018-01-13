<?php
/**
 * Staff Excerpt
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Templates
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if ( get_option( 'sportspress_staff_show_excerpt', 'yes' ) === 'no' ) return;

if ( ! isset( $id ) )
    $id = get_the_ID();

$post = get_post( $id );
$excerpt = $post->post_excerpt;
if ( $excerpt ) {
    ?>
    <div class="sp-template sp-section-staff-excerpt sp-template-excerpt">
        <p class="sp-excerpt"><?php echo $excerpt; ?></p>
    </div>
    <?php
}