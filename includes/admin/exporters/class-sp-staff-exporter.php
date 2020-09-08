<?php
/**
 * Staff exporter - export staff from SportsPress.
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
	<form method="post" id="sp_staff_export_form" action="">
		<ul id="staff-filters" class="export-filters" style="display: block;">
			<li>
				<label><span class="label-responsive"><?php _e( 'Job', 'sportspress' ); ?></span>
					<?php
					$args = array(
						'taxonomy' => 'sp_role',
						'name' => 'sp_role',
						'values' => 'slug',
						'show_option_none' => __( '&mdash; Not set &mdash;', 'sportspress' ),
					);
					if ( ! sp_dropdown_taxonomies( $args ) ):
						echo '<p>' . __( 'None', 'sportspress' ) . '</p>';
						sp_taxonomy_adder( 'sp_role', 'sp_staff', __( 'Add New', 'sportspress' ) );
					endif;
					?>
				</label>
			</li>
			<li>
				<label><span class="label-responsive"><?php _e( 'League', 'sportspress' ); ?></span>
					<?php
					$args = array(
						'taxonomy' => 'sp_league',
						'name' => 'sp_league',
						'values' => 'slug',
						'show_option_none' => __( '&mdash; Not set &mdash;', 'sportspress' ),
					);
					if ( ! sp_dropdown_taxonomies( $args ) ):
						echo '<p>' . __( 'None', 'sportspress' ) . '</p>';
						sp_taxonomy_adder( 'sp_league', 'sp_team', __( 'Add New', 'sportspress' ) );
					endif;
					?>
				</label>
			</li>
			<li>
				<label><span class="label-responsive"><?php _e( 'Season', 'sportspress' ); ?></span>
				<?php
					$args = array(
						'taxonomy' => 'sp_season',
						'name' => 'sp_season',
						'values' => 'slug',
						'show_option_none' => __( '&mdash; Not set &mdash;', 'sportspress' ),
					);
					if ( ! sp_dropdown_taxonomies( $args ) ):
						echo '<p>' . __( 'None', 'sportspress' ) . '</p>';
						sp_taxonomy_adder( 'sp_season', 'sp_team', __( 'Add New', 'sportspress' ) );
					endif;
					?>
				</label>
			</li>
			<?php do_action('sportspress_staff_filters'); ?>
		</ul>
		<?php wp_nonce_field( 'sp-admin-exporters', 'sp_exporter_nonce' ); ?>
		<?php submit_button( __( 'Export', 'sportspress' ) );?>
	</form>
</div>

