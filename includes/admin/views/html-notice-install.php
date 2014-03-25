<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<div id="message" class="updated sportspress-message">
	<p><?php _e( '<strong>Welcome to SportsPress</strong> &#8211; Get Started', 'sportspress' ); ?></p>
	<p class="submit">
		<a class="button-primary" href="<?php echo admin_url('options-general.php?page=sportspress'); ?>"><?php _e( 'Go to SportsPress Settings', 'sportspress' ); ?></a>
		<a class="button-secondary" href="<?php echo add_query_arg('sportspress_installed', '1' ); ?>"><?php _e( 'Skip setup', 'sportspress' ); ?></a>
	</p>
</div>