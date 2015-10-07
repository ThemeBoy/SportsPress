<?php
/**
 * Tweets
 *
 * @author 		ThemeBoy
 * @package 	SportsPress_Twitter
 * @version     1.9.10
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! isset( $id ) )
	$id = get_the_ID();

$username = get_post_meta( $id, 'sp_twitter', true );
$css_enabled = get_option( 'sportspress_enable_frontend_css', 'no' );
$colors = array_map( 'esc_attr', (array) get_option( 'themeboy', array() ) );
if ( empty( $colors ) ) $colors = array_map( 'esc_attr', (array) get_option( 'sportspress_frontend_css_colors', array() ) );
$limit = get_option( 'sportspress_twitter_limit', '3' );
$theme = get_option( 'sportspress_twitter_theme', 'light' );

if ( $username ) {
	?>
	<div class="sp-template sp-template-tweets">
		<h4 class="sp-table-caption"><?php _e( 'Tweets', 'sportspress' ); ?></h4>
		<a class="twitter-timeline"
			href="https://twitter.com/<?php echo $username; ?>"
			data-widget-id="345224689221771264"
			data-screen-name="<?php echo $username; ?>"
			data-chrome="noheader"
			<?php if ( $css_enabled && array_key_exists( 'link', $colors ) ) { ?>
			data-link-color="<?php echo $colors['link']; ?>"
			<?php } ?>
			data-theme="<?php echo $theme; ?>"
			data-tweet-limit="<?php echo $limit; ?>">
		</a>
		<script>window.twttr=(function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],t=window.twttr||{};if(d.getElementById(id))return;js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);t._e=[];t.ready=function(f){t._e.push(f);};return t;}(document,"script","twitter-wjs"));</script>
	</div>
	<?php
}
