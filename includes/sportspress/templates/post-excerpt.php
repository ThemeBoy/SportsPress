<?php
/**
 * Post Excerpt
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Templates
 * @version     1.9
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$id = get_the_ID();
$post = get_post( $id );
$excerpt = $post->post_excerpt;
if ( $excerpt ) {
	?>
	<p class="sp-excerpt"><?php echo $excerpt; ?></p>
	<?php
}