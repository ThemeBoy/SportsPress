<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div id="message" class="error sportspress-message">
	<p><?php echo wp_kses_post( __( '<strong>Your theme does not declare SportsPress support</strong> &#8211; if you encounter layout issues please read our integration guide or choose a SportsPress theme :)', 'sportspress' ) ); ?></p>
	<p><?php esc_attr_e( 'Have you tried the free Rookie theme yet?', 'sportspress' ); ?></p>
	<p class="submit">
		<a class="button-primary" href="<?php echo esc_url( add_query_arg( array( 'theme' => 'rookie' ), network_admin_url( 'theme-install.php' ) ) ); ?>"><?php esc_attr_e( 'Install Now', 'sportspress' ); ?></a>
		<a class="button-secondary" href="http://tboy.co/integration"><?php esc_attr_e( 'Theme Integration Guide', 'sportspress' ); ?></a>
		<a class="button" href="<?php echo wp_nonce_url( esc_url( add_query_arg( 'hide_theme_support_notice', 'true' ) ) ); ?>"><?php esc_attr_e( 'Hide this notice', 'sportspress' ); ?></a>
	</p>
</div>
