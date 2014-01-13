<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<div id="message" class="updated sportspress-message">
	<h3><?php _e( '<strong>Welcome to SportsPress</strong> &#8211; Get Started', 'sportspress' ); ?></h3>
	<p class="submit">
		<a class="button button-primary button-hero" href="<?php echo admin_url('options-general.php?page=sportspress'); ?>"><?php _e( 'Go to SportsPress Settings', 'sportspress' ); ?></a>
		<a class="button button-secondary button-hero" href="<?php echo add_query_arg('sportspress_installed', '1' ); ?>"><?php _e( 'Skip setup', 'sportspress' ); ?></a>
	</p>
</div>