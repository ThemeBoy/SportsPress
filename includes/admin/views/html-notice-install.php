<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<div id="message" class="updated sportspress-message">
	<p><strong><?php _e( 'Welcome to SportsPress', 'sportspress' ); ?></strong> &#8211; <?php _e( "Let's get started with some basic settings.", 'sportspress' ); ?></p>
	<p class="submit">
		<a class="button-primary" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'sp-setup', 'install_sportspress' => 'true' ), 'admin.php' ) ) ); ?>"><?php _e( 'Run the Setup Wizard', 'sportspress' ); ?></a>
		<a class="button-secondary" href="<?php echo add_query_arg('skip_install_sportspress', 'true' ); ?>"><?php _e( 'Hide this notice', 'sportspress' ); ?></a>
	</p>
</div>