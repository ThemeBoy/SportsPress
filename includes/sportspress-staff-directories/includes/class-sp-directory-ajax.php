<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * SportsPress Staff Directory AJAX
 *
 * AJAX Event Handler
 *
 * @class 		SP_Directory_AJAX
 * @version		1.6
 * @package		SportsPress_Staff_Directories
 * @category	Class
 * @author 		ThemeBoy
 */

class SP_Directory_AJAX {

	/**
	 * Hook into ajax events
	 */
	public function __construct() {

		// sportspress_EVENT => nopriv
		$ajax_events = array(
			'staff_list_shortcode' => false,
			'staff_gallery_shortcode' => false,
		);

		foreach ( $ajax_events as $ajax_event => $nopriv ) {
			add_action( 'wp_ajax_sportspress_' . $ajax_event, array( $this, $ajax_event ) );

			if ( $nopriv ) {
				add_action( 'wp_ajax_nopriv_sportspress_' . $ajax_event, array( $this, $ajax_event ) );
			}
		}
	}

	/**
	 * AJAX staff_list shortcode
	 */
	public function staff_list_shortcode() {
		?>
		<div class="wrap sp-thickbox-content" id="sp-thickbox-staff_list">
			<p>
				<label>
					<?php _e( 'Title:', 'sportspress' ); ?>
					<input class="regular-text" type="text" name="title">
				</label>
			</p>
			<p>
				<label>
					<?php printf( __( 'Select %s:', 'sportspress' ), __( 'Directory', 'sportspress' ) ); ?>
					<?php
					$args = array(
						'post_type' => 'sp_directory',
						'name' => 'id',
						'values' => 'ID',
					);
					sp_dropdown_pages( $args );
					?>
				</label>
			</p>
			<p>
				<label>
					<?php _e( 'Number of staff to show:', 'sportspress' ); ?>
					<input type="text" size="3" name="number" id="number" value="5">
				</label>
			</p>
			<p>
				<label>
					<input type="checkbox" name="show_all_staff_link" id="show_all_staff_link">
					<?php _e( 'Display link to view all staff', 'sportspress' ); ?>
				</label>
			</p>
			<?php do_action( 'sportspress_ajax_shortcode_form', 'staff-list' ); ?>
			<p class="submit">
				<input type="button" class="button-primary" value="<?php _e( 'Insert Shortcode', 'sportspress' ); ?>" onclick="insertSportsPress('staff_list');" />
				<a class="button-secondary" onclick="tb_remove();" title="<?php _e( 'Cancel', 'sportspress' ); ?>"><?php _e( 'Cancel', 'sportspress' ); ?></a>
			</p>
		</div>
		<?php
		self::scripts();
		die();
	}

	/**
	 * AJAX staff_gallery shortcode
	 */
	public function staff_gallery_shortcode() {
		?>
		<div class="wrap sp-thickbox-content" id="sp-thickbox-staff_gallery">
			<p>
				<label>
					<?php _e( 'Title:', 'sportspress' ); ?>
					<input class="regular-text" type="text" name="title">
				</label>
			</p>
			<p>
				<label>
					<?php printf( __( 'Select %s:', 'sportspress' ), __( 'Directory', 'sportspress' ) ); ?>
					<?php
					$args = array(
						'post_type' => 'sp_directory',
						'name' => 'id',
						'values' => 'ID',
					);
					sp_dropdown_pages( $args );
					?>
				</label>
			</p>
			<p>
				<label>
					<?php _e( 'Number of staff to show:', 'sportspress' ); ?>
					<input type="text" size="3" name="number" id="number" value="5">
				</label>
			</p>
			<p>
				<label>
					<input type="checkbox" name="show_all_staff_link" id="show_all_staff_link">
					<?php _e( 'Display link to view all staff', 'sportspress' ); ?>
				</label>
			</p>
			<?php do_action( 'sportspress_ajax_shortcode_form', 'staff-gallery' ); ?>
			<p class="submit">
				<input type="button" class="button-primary" value="<?php _e( 'Insert Shortcode', 'sportspress' ); ?>" onclick="insertSportsPress('staff_gallery');" />
				<a class="button-secondary" onclick="tb_remove();" title="<?php _e( 'Cancel', 'sportspress' ); ?>"><?php _e( 'Cancel', 'sportspress' ); ?></a>
			</p>
		</div>
		<?php
		self::scripts();
		die();
	}

	public function scripts() {
		?>
		<script type="text/javascript">
            function insertSportsPress( type ) {
                var $div = jQuery('.sp-thickbox-content');

                // Initialize shortcode arguments
                var args = {};

                // Add ID if available and not 0
                id = $div.find('[name=id]').val();
                if ( id != 0 ) args.id = id;

                // Extract args based on type
                if ( 'staff_list' == type ) {
                    args.title = $div.find('[name=title]').val();
                    args.number = $div.find('[name=number]').val();
                    args.show_all_staff_link = $div.find('[name=show_all_staff_link]:checked').length;
                } else if ( 'staff_gallery' == type ) {
                    args.title = $div.find('[name=title]').val();
                    args.number = $div.find('[name=number]').val();
                    args.show_all_staff_link = $div.find('[name=show_all_staff_link]:checked').length;
                }

                <?php do_action( 'sportspress_ajax_scripts_before_shortcode' ); ?>

                // Generate the shortcode
				var shortcode = '[' + type;
				for ( var key in args ) {
					if ( args.hasOwnProperty( key ) ) {
						shortcode += ' ' + key + '="' + args[key] + '"';
					}
				}
				shortcode += ']';

                // Send the shortcode to the editor
                window.send_to_editor( shortcode );
            }
		</script>
		<?php
	}
}

new SP_Directory_AJAX();

