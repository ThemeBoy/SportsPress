<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<div id="message" class="updated sportspress-message">
	<h3><?php _e( 'Welcome to SportsPress!', 'sportspress' ); ?></h3>
	<p class="submit">
		<a class="button button-primary button-hero" href="<?php echo admin_url('options-general.php?page=sportspress'); ?>"><?php _e( 'Settings', 'sportspress' ); ?></a>
		<a class="button button-secondary button-hero" href="<?php echo add_query_arg('skip_install_sportspress', 'true' ); ?>"><?php _e( 'Skip setup', 'sportspress' ); ?></a>
	</p>
</div>