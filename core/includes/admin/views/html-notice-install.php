<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<div id="message" class="updated sportspress-message">
	<p><strong><?php printf( __( 'Welcome to SportsPress %s', 'sportspress' ), SP()->version ); ?></strong></p>
	<p class="submit">
		<a class="button-primary" href="<?php echo admin_url( add_query_arg( array( 'page' => 'sp-about', 'install_sportspress' => 'true' ), 'index.php' ) ); ?>"><?php _e( "Get Started", 'sportspress' ); ?></a>
		<a class="button-secondary" href="<?php echo add_query_arg('skip_install_sportspress', 'true' ); ?>"><?php _e( 'Hide this notice', 'sportspress' ); ?></a>
	</p>
</div>