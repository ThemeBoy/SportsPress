<?php
/**
 * Staff Excerpt
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Templates
 * @version   2.5.5
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if ( get_option( 'sportspress_staff_show_excerpt', 'yes' ) === 'no' ) return;

if ( ! isset( $id ) )
    $id = get_the_ID();

$post = get_post( $id );
$excerpt = $post->post_excerpt;
if ( $excerpt ) {
  ?>
    <p class="sp-excerpt">
      <?php echo $excerpt; ?>
    </p>
  <?php
}