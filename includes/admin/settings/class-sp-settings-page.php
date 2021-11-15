<?php
/**
 * SportsPress Settings Page/Tab
 *
 * @author      ThemeBoy
 * @category    Admin
 * @package     SportsPress/Admin
 * @version     2.7.9
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'SP_Settings_Page' ) ) :

	/**
	 * SP_Settings_Page
	 */
	class SP_Settings_Page {

		protected $id       = '';
		protected $label    = '';
		protected $template = '';
		/**
		 * Add this page to settings
		 */
		public function add_settings_page( $pages ) {
			$pages[ $this->id ] = $this->label;

			return $pages;
		}

		/**
		 * Get settings array
		 *
		 * @return array
		 */
		public function get_settings() {
			return array();
		}

		/**
		 * Templates
		 *
		 * @return array
		 */
		public function templates() {
			if ( ! isset( $this->template ) ) {
				return array();
			}
			$template = $this->template;
			return SP()->templates->$template;
		}

		/**
		 * Output the settings
		 */
		public function output() {
			$settings = $this->get_settings();

			SP_Admin_Settings::output_fields( $settings );
		}

		/**
		 * Save settings
		 */
		public function save() {
			global $current_section;

			$settings = $this->get_settings();
			SP_Admin_Settings::save_fields( $settings );

			if ( $current_section ) {
				do_action( 'sportspress_update_options_' . $this->template . '_' . $current_section );
			}

			$templates = $this->templates();
			if ( ! empty( $templates ) ) {
				update_option( 'sportspress_' . $this->template . '_template_order', sp_array_value( $_POST, 'sportspress_' . $this->template . '_template_order', false, 'key' ) );
			}

			if ( isset( $_POST['sportspress_template_visibility'] ) && is_array( $_POST['sportspress_template_visibility'] ) ) {
				foreach ( $_POST['sportspress_template_visibility'] as $option => $toggled ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
					if ( $toggled ) {
						update_option( $option, 'yes' );
					} else {
						update_option( $option, 'no' );
					}
				}
			}

		}

		/**
		 * Layout settings
		 *
		 * @access public
		 * @return void
		 */
		public function layout_setting() {
			$templates = $this->templates();
			$templates = apply_filters( 'sportspress_' . $this->template . '_templates', $templates );

			$layout = get_option( 'sportspress_' . $this->template . '_template_order' );
			if ( false === $layout ) {
				$layout = array_keys( $templates );
			}

			$templates = array_merge( array_flip( (array) $layout ), $templates );

			$slice = array_search( 'tabs', array_keys( $templates ) );
			if ( $slice ) {
				$templates = array_slice( $templates, 0, $slice );
			}
			?>
		<tr valign="top">
			<th>
				<?php esc_html_e( 'Layout', 'sportspress' ); ?>
			</th>
			<td class="sp-sortable-list-container">
				<p class="description"><?php esc_html_e( 'Drag each item into the order you prefer.', 'sportspress' ); ?></p>

				<ul class="sp-layout sp-sortable-list sp-connected-list ui-sortable">
					<?php
					foreach ( $templates as $template => $details ) {
						if ( ! is_array( $details ) ) {
							continue;
						}
						$option     = sp_array_value( $details, 'option', 'sportspress_' . $this->template . '_show_' . $template );
						$visibility = get_option( $option, sp_array_value( $details, 'default', 'yes' ) );
						?>
						<li>
							<div class="sp-item-bar sp-layout-item-bar">
								<div class="sp-item-handle sp-layout-item-handle ui-sortable-handle">
									<span class="sp-item-title item-title"><?php echo esc_html( sp_array_value( $details, 'title', ucfirst( $template ) ) ); ?></span>
									<input type="hidden" name="sportspress_<?php echo esc_attr( $this->template ); ?>_template_order[]" value="<?php echo esc_attr( $template ); ?>">
								</div>
								
								<input type="hidden" name="sportspress_template_visibility[<?php echo esc_attr( $option ); ?>]" value="0">
								<input class="sp-toggle-switch" type="checkbox" name="sportspress_template_visibility[<?php echo esc_attr( $option ); ?>]" id="<?php echo esc_attr( $option ); ?>" value="1" <?php checked( $visibility, 'yes' ); ?>>
								<label for="sportspress_<?php echo esc_attr( $this->template ); ?>_show_<?php echo esc_attr( $template ); ?>"></label>
							</div>
						</li>
					<?php } ?>
				 </ul>
			</td>
		</tr>
			<?php
		}

		/**
		 * Tabs settings
		 *
		 * @access public
		 * @return void
		 */
		public function tabs_setting() {
			$templates = $this->templates();
			$templates = apply_filters( 'sportspress_' . $this->template . '_templates', $templates );

			$layout = get_option( 'sportspress_' . $this->template . '_template_order' );
			if ( false === $layout ) {
				$layout = array_keys( $templates );
			}

			$templates = array_merge( array_flip( (array) $layout ), $templates );

			$slice = array_search( 'tabs', array_keys( $templates ) );
			if ( $slice ) {
				$templates = array_slice( $templates, $slice );
			} else {
				$templates = array();
			}
			?>
		<tr valign="top">
			<th>
				<?php esc_html_e( 'Tabs', 'sportspress' ); ?>
			</th>
			<td class="sp-sortable-list-container">
				<p class="description"><?php esc_html_e( 'Drag items here to display them as tabs.', 'sportspress' ); ?></p>
				<input type="hidden" name="sportspress_<?php echo esc_attr( $this->template ); ?>_template_order[]" value="tabs">

				<ul class="sp-layout sp-sortable-list sp-connected-list ui-sortable">
					<?php
					foreach ( $templates as $template => $details ) {
						if ( ! is_array( $details ) ) {
							continue;
						}
						$option     = sp_array_value( $details, 'option', 'sportspress_' . $this->template . '_show_' . $template );
						$visibility = get_option( $option, sp_array_value( $details, 'default', 'yes' ) );
						?>
						<li>
							<div class="sp-item-bar sp-layout-item-bar">
								<div class="sp-item-handle sp-layout-item-handle ui-sortable-handle">
									<span class="sp-item-title item-title"><?php echo esc_html( sp_array_value( $details, 'title', ucfirst( $template ) ) ); ?></span>
									<input type="hidden" name="sportspress_<?php echo esc_attr( $this->template ); ?>_template_order[]" value="<?php echo esc_attr( $template ); ?>">
								</div>
								
								<input type="hidden" name="sportspress_template_visibility[<?php echo esc_attr( $option ); ?>]" value="0">
								<input class="sp-toggle-switch" type="checkbox" name="sportspress_template_visibility[<?php echo esc_attr( $option ); ?>]" id="<?php echo esc_attr( $option ); ?>" value="1" <?php checked( $visibility, 'yes' ); ?>>
								<label for="sportspress_<?php echo esc_attr( $this->template ); ?>_show_<?php echo esc_attr( $template ); ?>"></label>
							</div>
						</li>
					<?php } ?>
				 </ul>
			</td>
		</tr>
			<?php
		}
	}

endif;
