<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<div id="message" class="error sportspress-message">
	<p><?php _e( '<strong>Your theme does not declare SportsPress support</strong> &#8211; if you encounter layout issues please read our integration guide or choose a SportsPress theme :)', 'sportspress' ); ?></p>
	<p class="submit">
		<a class="button-primary" href="http://sportspresspro.com/docs/theme-integration-guide/"><?php _e( 'Theme Integration Guide', 'sportspress' ); ?></a>
		<a class="button-secondary" href="<?php echo add_query_arg( 'hide_theme_support_notice', 'true' ); ?>"><?php _e( 'Hide this notice', 'sportspress' ); ?></a>
	</p>
</div>