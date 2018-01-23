<?php
/**
 * Staff Content
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Templates
 * @version   2.5.5
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if ( get_option( 'sportspress_staff_show_content', 'yes' ) === 'no' ) return;

if ( ! isset( $id ) )
    $id = get_the_ID();

$post = get_post( $id );
$content = $post->post_content;
if ( $content ) {
  ?>
    <div class="sp-post-content">
      <?php echo apply_filters( 'the_content', $content ); ?>
    </div>
  <?php
}