<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * SportsPress Results Matrix AJAX
 *
 * AJAX Event Handler
 *
 * @class 		SP_Results_Matrix_AJAX
 * @version		2.6.4
 * @package		SportsPress_Results_Matrix
 * @category	Class
 * @author 		ThemeBoy
 */

class SP_Results_Matrix_AJAX {

	/**
	 * Hook into ajax events
	 */
	public function __construct() {

		$ajax_events = array(
			'event_matrix_shortcode' => false,
		);

		foreach ( $ajax_events as $ajax_event => $nopriv ) {
			add_action( 'wp_ajax_sportspress_' . $ajax_event, array( $this, $ajax_event ) );

			if ( $nopriv ) {
				add_action( 'wp_ajax_nopriv_sportspress_' . $ajax_event, array( $this, $ajax_event ) );
			}
		}
	}

	/**
	 * AJAX results matrix shortcode
	 */
	public function event_matrix_shortcode() {
		?>
		<div class="wrap sp-thickbox-content" id="sp-thickbox-results-matrix">
			<p>
				<label>
					<?php printf( __( 'Select %s:', 'sportspress' ), __( 'Calendar', 'sportspress' ) ); ?>
					<?php
					$args = array(
						'post_type' => 'sp_calendar',
						'name' => 'id',
						'values' => 'ID',
					);
					sp_dropdown_pages( $args );
					?>
				</label>
			</p>
			<?php do_action( 'sportspress_ajax_shortcode_form', 'event-matrix' ); ?>
			<p class="submit">
				<input type="button" class="button-primary" value="<?php _e( 'Insert Shortcode', 'sportspress' ); ?>" onclick="insertSportsPress('event_matrix');" />
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

new SP_Results_Matrix_AJAX();

