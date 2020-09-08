<?php
/**
 * Official exporter - export official from SportsPress.
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Exporters
 * @version		2.8.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<div class="wrap">
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
	<form method="post" id="sp_offical_export_form" action="">
		<ul id="officials-filters" class="export-filters" style="display: block;">
			<?php do_action('sportspress_officials_filters'); ?>
		</ul>
		<?php wp_nonce_field( 'sp-admin-exporters', 'sp_exporter_nonce' ); ?>
		<?php submit_button( __( 'Export', 'sportspress' ) );?>
	</form>
</div>
