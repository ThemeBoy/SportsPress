<?php
/**
 * SportsPress Configure Settings
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin
 * @version     0.7
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'SP_Settings_Config' ) ) :

/**
 * SP_Settings_Configure
 */
class SP_Settings_Config extends SP_Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id    = 'config';
		$this->label = __( 'Configure', 'sportspress' );

		add_filter( 'sportspress_settings_tabs_array', array( $this, 'add_settings_page' ), 20 );
		add_action( 'sportspress_settings_' . $this->id, array( $this, 'output' ) );
		add_action( 'sportspress_admin_field_results', array( $this, 'results_setting' ) );
		add_action( 'sportspress_admin_field_outcomes', array( $this, 'outcomes_setting' ) );
		add_action( 'sportspress_admin_field_columns', array( $this, 'columns_setting' ) );
		add_action( 'sportspress_admin_field_metrics', array( $this, 'metrics_setting' ) );
		add_action( 'sportspress_admin_field_performance', array( $this, 'performance_setting' ) );
		add_action( 'sportspress_settings_save_' . $this->id, array( $this, 'save' ) );
	}

	/**
	 * Get settings array
	 *
	 * @return array
	 */
	public function get_settings() {
		return apply_filters('sportspress_event_settings', array(

			array( 'title' => __( 'Configure SportsPress', 'sportspress' ), 'type' => 'title','desc' => '', 'id' => 'config_options' ),

			array(
				'title'     => __( 'Sport', 'sportspress' ),
				'id'        => 'sportspress_sport',
				'default'   => 'soccer',
				'type'      => 'select',
				'options'   => SP()->sports->options,
			),

			array( 'type' => 'results' ),

			array( 'type' => 'outcomes' ),

			array( 'type' => 'columns' ),

			array( 'type' => 'metrics' ),

			array( 'type' => 'performance' ),

			array( 'type' => 'statistics' ),

			array( 'type' => 'sectionend', 'id' => 'config_options' ),

		)); // End event settings
	}

	/**
	 * Output the settings
	 */
	public function output() {
		global $current_section;

		$tax_classes = array_filter( array_map( 'trim', explode( "\n", get_option('sportspress_tax_classes' ) ) ) );

		if ( $current_section == 'standard' || in_array( $current_section, array_map( 'sanitize_title', $tax_classes ) ) ) {
 			$this->output_tax_rates();
 		} else {
			$settings = $this->get_settings();

			SP_Admin_Settings::output_fields( $settings );
		}
	}

	/**
	 * Save settings
	 */
	public function save() {
		global $current_section, $wpdb;

		if ( ! $current_section ) {

			$settings = $this->get_settings();
			SP_Admin_Settings::save_fields( $settings );

		} else {

			$this->save_tax_rates();

		}

		$wpdb->query( "DELETE FROM `$wpdb->options` WHERE `option_name` LIKE ('_transient_sp_tax_rates_%') OR `option_name` LIKE ('_transient_timeout_sp_tax_rates_%')" );
	}

	/**
	 * Output results settings.
	 *
	 * @access public
	 * @return void
	 */
	public function results_setting() {
		$main_result = get_option( 'sportspress_main_result', 0 );

		$args = array(
			'post_type' => 'sp_result',
			'numberposts' => -1,
			'posts_per_page' => -1,
				'orderby' => 'menu_order',
				'order' => 'ASC'
		);
		$data = get_posts( $args );
		?>
		<tr valign="top">
			<th scope="row" class="titledesc"><?php _e( 'Results', 'sportspress' ) ?></th>
		    <td class="forminp">
				<table class="widefat sp-admin-config-table">
					<thead>
						<tr>
							<th scope="col"><?php _e( 'Primary', 'sportspress' ); ?></th>
							<th scope="col"><?php _e( 'Label', 'sportspress' ); ?></th>
							<th scope="col"><?php _e( 'Key', 'sportspress' ); ?></th>
							<th scope="col" class="edit"></th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th class="radio"><input type="radio" id="sportspress_main_result_0" name="main_result" value="0" <?php checked( $main_result, 0 ); ?>></th>
							<th colspan="3"><label for="main_result_0">
								<?php
								if ( sizeof( $data ) > 0 ):
									$default = end( $data );
									reset( $data );
									printf( __( 'Default (%s)', 'sportspress' ), $default->post_title );
								else:
									_e( 'Default', 'sportspress' );
								endif;
								?>
							</label></th>
						</tr>
					</tfoot>
					<?php $i = 0; foreach ( $data as $row ): ?>
						<tr<?php if ( $i % 2 == 0 ) echo ' class="alternate"'; ?>>
							<td class="radio"><input type="radio" id="main_result_<?php echo $row->post_name; ?>" name="main_result" value="<?php echo $row->post_name; ?>" <?php checked( $main_result, $row->post_name ); ?>></td>
							<td class="row-title"><label for="sportspress_main_result_<?php echo $row->post_name; ?>"><?php echo $row->post_title; ?></label></td>
							<td><?php echo $row->post_name; ?>for / <?php echo $row->post_name; ?>against</td>
							<td class="edit"><a class="button" href="<?php echo get_edit_post_link( $row->ID ); ?>"><?php _e( 'Edit', 'sportspress' ); ?></s></td>
						</tr>
					<?php $i++; endforeach; ?>
				</table>
				<div class="tablenav bottom">
					<div class="alignleft actions">
						<a class="button" id="doaction" href="<?php echo admin_url( 'edit.php?post_type=sp_result' ); ?>"><?php _e( 'View All', 'sportspress' ); ?></a>
						<a class="button" id="doaction2" href="<?php echo admin_url( 'post-new.php?post_type=sp_result' ); ?>"><?php _e( 'Add New', 'sportspress' ); ?></a>
					</div>
					<br class="clear">
				</div>
			</td>
		</tr>
		<?php
	}

	/**
	 * Output outcomes settings.
	 *
	 * @access public
	 * @return void
	 */
	public function outcomes_setting() {
		$args = array(
			'post_type' => 'sp_outcome',
			'numberposts' => -1,
			'posts_per_page' => -1,
				'orderby' => 'menu_order',
				'order' => 'ASC'
		);
		$data = get_posts( $args );
		?>
		<tr valign="top">
			<th scope="row" class="titledesc"><?php _e( 'Outcomes', 'sportspress' ) ?></th>
		    <td class="forminp">
				<table class="widefat sp-admin-config-table">
					<thead>
						<tr>
							<th scope="col"><?php _e( 'Label', 'sportspress' ); ?></th>
							<th scope="col"><?php _e( 'Key', 'sportspress' ); ?></th>
							<th scope="col" class="edit"></th>
						</tr>
					</thead>
					<?php $i = 0; foreach ( $data as $row ): ?>
						<tr<?php if ( $i % 2 == 0 ) echo ' class="alternate"'; ?>>
							<td class="row-title"><?php echo $row->post_title; ?></td>
							<td><?php echo $row->post_name; ?></td>
							<td class="edit"><a class="button" href="<?php echo get_edit_post_link( $row->ID ); ?>"><?php _e( 'Edit', 'sportspress' ); ?></s></td>
						</tr>
					<?php $i++; endforeach; ?>
				</table>
				<div class="tablenav bottom">
					<div class="alignleft actions">
						<a class="button" id="doaction" href="<?php echo admin_url( 'edit.php?post_type=sp_outcome' ); ?>"><?php _e( 'View All', 'sportspress' ); ?></a>
						<a class="button" id="doaction2" href="<?php echo admin_url( 'post-new.php?post_type=sp_outcome' ); ?>"><?php _e( 'Add New', 'sportspress' ); ?></a>
					</div>
					<br class="clear">
				</div>
			</td>
		</tr>
		<?php
	}

	/**
	 * Output columns settings.
	 *
	 * @access public
	 * @return void
	 */
	public function columns_setting() {
		$args = array(
			'post_type' => 'sp_column',
			'numberposts' => -1,
			'posts_per_page' => -1,
				'orderby' => 'menu_order',
				'order' => 'ASC'
		);
		$data = get_posts( $args );
		?>
		<tr valign="top">
			<th scope="row" class="titledesc"><?php _e( 'Columns', 'sportspress' ) ?></th>
		    <td class="forminp">
				<table class="widefat sp-admin-config-table">
					<thead>
						<tr>
							<th scope="col"><?php _e( 'Label', 'sportspress' ); ?></th>
							<th scope="col"><?php _e( 'Key', 'sportspress' ); ?></th>
							<th scope="col"><?php _e( 'Equation', 'sportspress' ); ?></th>
							<th scope="col"><?php _e( 'Rounding', 'sportspress' ); ?></th>
							<th scope="col"><?php _e( 'Sort Order', 'sportspress' ); ?></th>
							<th scope="col" class="edit"></th>
						</tr>
					</thead>
					<?php $i = 0; foreach ( $data as $row ): ?>
						<tr<?php if ( $i % 2 == 0 ) echo ' class="alternate"'; ?>>
							<td class="row-title"><?php echo $row->post_title; ?></td>
							<td><?php echo $row->post_name; ?></td>
							<td><?php echo sportspress_get_post_equation( $row->ID, $row->post_name ); ?></td>
							<td><?php echo sportspress_get_post_precision( $row->ID ); ?></td>
							<td><?php echo sportspress_get_post_order( $row->ID ); ?></td>
							<td class="edit"><a class="button" href="<?php echo get_edit_post_link( $row->ID ); ?>"><?php _e( 'Edit', 'sportspress' ); ?></s></td>
						</tr>
					<?php $i++; endforeach; ?>
				</table>
				<div class="tablenav bottom">
					<div class="alignleft actions">
						<a class="button" id="doaction" href="<?php echo admin_url( 'edit.php?post_type=sp_column' ); ?>"><?php _e( 'View All', 'sportspress' ); ?></a>
						<a class="button" id="doaction2" href="<?php echo admin_url( 'post-new.php?post_type=sp_column' ); ?>"><?php _e( 'Add New', 'sportspress' ); ?></a>
					</div>
					<br class="clear">
				</div>
			</fieldset>
			</td>
		</tr>
		<?php
	}

	/**
	 * Output metrics settings.
	 *
	 * @access public
	 * @return void
	 */
	public function metrics_setting() {
		$args = array(
			'post_type' => 'sp_metric',
			'numberposts' => -1,
			'posts_per_page' => -1,
				'orderby' => 'menu_order',
				'order' => 'ASC'
		);
		$data = get_posts( $args );
		?>
		<tr valign="top">
			<th scope="row" class="titledesc"><?php _e( 'Metrics', 'sportspress' ) ?></th>
		    <td class="forminp">
				<table class="widefat sp-admin-config-table">
					<thead>
						<tr>
							<th scope="col"><?php _e( 'Label', 'sportspress' ); ?></th>
							<th scope="col"><?php _e( 'Positions', 'sportspress' ); ?></th>
							<th scope="col">&nbsp;</th>
							<th scope="col" class="edit"></th>
						</tr>
					</thead>
					<?php $i = 0; foreach ( $data as $row ): ?>
						<tr<?php if ( $i % 2 == 0 ) echo ' class="alternate"'; ?>>
							<td class="row-title"><?php echo $row->post_title; ?></td>
							<td><?php echo get_the_terms ( $row->ID, 'sp_position' ) ? the_terms( $row->ID, 'sp_position' ) : '&mdash;'; ?></td>
							<td>&nbsp;</td>
							<td class="edit"><a class="button" href="<?php echo get_edit_post_link( $row->ID ); ?>"><?php _e( 'Edit', 'sportspress' ); ?></s></td>
						</tr>
					<?php $i++; endforeach; ?>
				</table>
				<div class="tablenav bottom">
					<div class="alignleft actions">
						<a class="button" id="doaction" href="<?php echo admin_url( 'edit.php?post_type=sp_metric' ); ?>"><?php _e( 'Edit', 'sportspress' ); ?></a>
						<a class="button" id="doaction2" href="<?php echo admin_url( 'post-new.php?post_type=sp_metric' ); ?>"><?php _e( 'Add New', 'sportspress' ); ?></a>
					</div>
					<br class="clear">
				</div>
			</td>
		</tr>
		<?php
	}

	/**
	 * Output performance settings.
	 *
	 * @access public
	 * @return void
	 */
	public function performance_setting() {
		$args = array(
			'post_type' => 'sp_performance',
			'numberposts' => -1,
			'posts_per_page' => -1,
			'orderby' => 'menu_order',
			'order' => 'ASC'
		);
		$data = get_posts( $args );
		?>
		<tr valign="top">
			<th scope="row" class="titledesc"><?php _e( 'Performance', 'sportspress' ) ?></th>
		    <td class="forminp">
				<table class="widefat sp-admin-config-table">
					<thead>
						<tr>
							<th scope="col"><?php _e( 'Label', 'sportspress' ); ?></th>
							<th scope="col"><?php _e( 'Positions', 'sportspress' ); ?></th>
							<th scope="col"><?php _e( 'Calculate', 'sportspress' ); ?></th>
							<th scope="col" class="edit"></th>
						</tr>
					</thead>
					<?php $i = 0; foreach ( $data as $row ): ?>
						<tr<?php if ( $i % 2 == 0 ) echo ' class="alternate"'; ?>>
							<td class="row-title"><?php echo $row->post_title; ?></td>
							<td><?php echo get_the_terms ( $row->ID, 'sp_position' ) ? the_terms( $row->ID, 'sp_position' ) : '&mdash;'; ?></td>
							<td><?php echo sportspress_get_post_calculate( $row->ID ); ?></td>
							<td class="edit"><a class="button" href="<?php echo get_edit_post_link( $row->ID ); ?>"><?php _e( 'Edit', 'sportspress' ); ?></s></td>
						</tr>
					<?php $i++; endforeach; ?>
				</table>
				<div class="tablenav bottom">
					<div class="alignleft actions">
						<a class="button" id="doaction" href="<?php echo admin_url( 'edit.php?post_type=sp_performance' ); ?>"><?php _e( 'View All', 'sportspress' ); ?></a>
						<a class="button" id="doaction2" href="<?php echo admin_url( 'post-new.php?post_type=sp_performance' ); ?>"><?php _e( 'Add New', 'sportspress' ); ?></a>
					</div>
					<br class="clear">
				</div>
			</td>
		</tr>
		<?php
	}
}

endif;

return new SP_Settings_Config();