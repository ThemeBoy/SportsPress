<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<div id="message" class="error sportspress-message">
	<p><?php _e( '<strong>Your theme does not declare SportsPress support</strong> &#8211; if you encounter layout issues please read our integration guide or choose a SportsPress theme :)', 'sportspress' ); ?></p>
	<p class="submit">
		<a class="button-primary" href="http://docs.themeboy.com/sportspress/compatibility/"><?php _e( 'Theme Integration Guide', 'sportspress' ); ?></a>
		<a class="button-secondary" href="<?php echo add_query_arg( 'hide_sportspress_theme_support_check', 'true' ); ?>"><?php _e( 'Hide this notice', 'sportspress' ); ?></a>
	</p>
</div>