<?php
/*
Plugin Name: SportsPress Widget Alignment
Plugin URI: http://themeboy.com/
Description: Add alignment options to SportsPress widgets.
Author: ThemeBoy
Author URI: http://themeboy.com
Version: 1.8.3
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_Widget_Alignment' ) ) :

/**
 * Main SportsPress Widget Alignment Class
 *
 * @class SportsPress_Widget_Alignment
 * @version	1.8.3
 */
class SportsPress_Widget_Alignment {

	/**
	 * @var array
	 */
	public $options = array();

	/**
	 * Constructor
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();

		// Initialize
		add_action( 'init', array( $this, 'init' ) );

		// Widgets
		add_filter( 'sportspress_widget_update', array( $this, 'widget_update' ), 10, 2 );
		add_filter( 'sportspress_widget_defaults', array( $this, 'widget_defaults' ) );
		add_filter( 'sportspress_shortcode_wrapper', array( $this, 'shortcode_wrapper' ), 10, 3 );
		add_action( 'sportspress_before_widget_template_form', array( $this, 'before_widget_form' ), 10, 2 );
		add_action( 'sportspress_before_widget', array( $this, 'before_widget'), 10, 2 );
		add_action( 'sportspress_after_widget', array( $this, 'after_widget') );
		add_action( 'sportspress_ajax_shortcode_form', array( $this, 'ajax_shortcode_form' ) );
		add_action( 'sportspress_ajax_scripts_before_shortcode', array( $this, 'ajax_scripts' ) );
	}

	/**
	 * Define constants
	*/
	private function define_constants() {
		if ( !defined( 'SP_WIDGET_ALIGNMENT_VERSION' ) )
			define( 'SP_WIDGET_ALIGNMENT_VERSION', '1.8.3' );

		if ( !defined( 'SP_WIDGET_ALIGNMENT_URL' ) )
			define( 'SP_WIDGET_ALIGNMENT_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'SP_WIDGET_ALIGNMENT_DIR' ) )
			define( 'SP_WIDGET_ALIGNMENT_DIR', plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Initialize
	 */
	public function init() {
		$this->options = array(
			'none' => __( 'None', 'sportspress' ),
			'left' => __( 'Left', 'sportspress' ),
			'right' => __( 'Right', 'sportspress' ),
		);
	}

	/**
	 * Widget update
	 */
	function widget_update( $instance, $new_instance ) {
		$instance['align'] = strip_tags( $new_instance['align'] );
		return $instance;
	}

	/**
	 * Widget defaults
	 */
	function widget_defaults( $defaults ) {
		$defaults['align'] = 'none';
		return $defaults;
	}

	/**
	 * Shortcode wrapper
	 */
	function shortcode_wrapper( $wrapper = array(), $function = null, $atts = array() ) {
		if ( isset( $atts['align'] ) ) {
			$wrapper['class'] = sp_array_value( $wrapper, 'class', '' ) . ' ' . 'sp-widget-align-' . $atts['align'];
		}

		return $wrapper;
	}

	/**
	 * Before widget forms
	 */
	function before_widget_form( $object, $instance ) {
		?>
		<p><label for="<?php echo $object->get_field_id('align'); ?>"><?php printf( __( 'Alignment: %s', 'sportspress' ), '' ); ?></label>
			<select name="<?php echo $object->get_field_name('align'); ?>" id="<?php echo $object->get_field_id('align'); ?>">
				<?php
				$align = strip_tags( sp_array_value( $instance, 'align', 'none' ) );
			    foreach ( $this->options as $value => $label ) {
			        printf( '<option value="%s" %s>%s</option>', $value, ( $align == $value ? 'selected' : '' ), $label );
			    }
				?>
			</select>
		</p>
		<?php
	}

	/**
	 * Before widget
	 */
	function before_widget( $args, $instance ) {
		echo '<div class="sp-widget-align-' . sp_array_value( $instance, 'align', 'none' ) . '">';
	}

	/**
	 * After widget
	 */
	function after_widget() {
		echo '</div>';
	}

	/**
	 * Ajax shortcode form
	 */
	function ajax_shortcode_form() {
		?>
		<p>
			<label>
				<?php printf( __( 'Alignment: %s', 'sportspress' ), '' ); ?>
				<select id="align" name="align">
					<?php
					foreach ( $this->options as $value => $label ) {
						printf( '<option value="%s">%s</option>', $value, $label );
					}
					?>
				</select>
			</label>
		</p>
		<?php
	}

	/**
	 * Ajax scripts
	 */
	function ajax_scripts() {
		?>
		args.align = $div.find('[name=align]').val();
		<?php
	}
}

endif;

new SportsPress_Widget_Alignment();
