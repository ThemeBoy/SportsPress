<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<div id="message" class="updated sportspress-message wc-connect">
	<p><?php _e( '<strong>Your theme has bundled outdated copies of SportsPress template files</strong> &#8211; if you encounter functionality issues on the frontend this could the reason. Ensure you update or remove them (in general we recommend only bundling the template files you actually need to customize). See the system report for full details.', 'sportspress' ); ?></p>
	<p class="submit"><a class="button-primary" href="<?php echo esc_url( add_query_arg( array( 'page' => 'sportspress', 'tab' => 'status' ), admin_url( 'admin.php' ) ) ); ?>"><?php _e( 'System Status', 'sportspress' ); ?></a> <a class="skip button" href="<?php echo esc_url( add_query_arg( 'hide_template_files_notice', 'true' ) ); ?>"><?php _e( 'Hide this notice', 'sportspress' ); ?></a></p>
</div>