<?php
/*
Plugin Name: SportsPress WordPay
Plugin URI: http://themeboy.com/
Description: Add team and player registration shortcodes to WordPay.
Author: ThemeBoy
Author URI: http://themeboy.com/
Version: 2.6
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_WordPay' ) ) :

/**
 * Main SportsPress WordPay Class
 *
 * @class SportsPress_WordPay
 * @version	2.6
 */
class SportsPress_WordPay {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();

		// Shortcode
		add_action( 'init', array( $this, 'add_shortcodes' ) );
		add_action( 'wpay_register_form_after_fields', array( $this, 'form_field' ) );

		// Editor
		add_filter( 'wordpay_shortcodes', array( $this, 'editor_shortcodes' ) );
		add_filter( 'wordpay_tinymce_strings', array( $this, 'editor_strings' ) );

		// Widgets
		add_action( 'wordpay_after_widget_register_form', array( $this, 'widget_form' ), 10, 2 );
		add_filter( 'wordpay_widget_register_update', array( $this, 'widget_update' ), 10, 2 );
		add_filter( 'wordpay_widget_register_shortcode', array( $this, 'widget_shortcode' ), 10, 2 );
	}

	/**
	 * Define constants.
	*/
	private function define_constants() {
		if ( !defined( 'SP_WORDPAY_VERSION' ) )
			define( 'SP_WORDPAY_VERSION', '2.6' );

		if ( !defined( 'SP_WORDPAY_URL' ) )
			define( 'SP_WORDPAY_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'SP_WORDPAY_DIR' ) )
			define( 'SP_WORDPAY_DIR', plugin_dir_path( __FILE__ ) );
	}
	/**
	 * Add team and player registration shortcodes.
	 */
	public function add_shortcodes() {
		add_shortcode( 'wpay-register-team', array( $this, 'register_team' ) );
		add_shortcode( 'wpay-register-player', array( $this, 'register_player' ) );
	}

	/**
	 * Team registration shortcode.
	 */
	public static function register_team( $atts = array() ) {
    $args = array(
    	'post_type' => 'wpay-subscription',
    	'post_status' => 'active',
    	'posts_per_page' => 500,
    	'meta_query' => array(
    		array(
    			'key' => 'wpay_subscription_plan_user_role',
    			'value' => 'sp_team_manager',
    		),
    	),
    	'fields' => 'ids',
    );

    $plans = get_posts( $args );

    if ( empty( $plans ) ) {
    	return '<p>' . __( 'There are no plans associated with the Team Manager role.', 'sportspress' ) . '<p>';
    }

		return self::register_form( $atts, 'team', $plans );
	}

	/**
	 * Player registration shortcode.
	 */
	public static function register_player( $atts = array() ) {
    $args = array(
    	'post_type' => 'wpay-subscription',
    	'post_status' => 'active',
    	'posts_per_page' => 500,
    	'meta_query' => array(
    		array(
    			'key' => 'wpay_subscription_plan_user_role',
    			'value' => 'sp_player',
    		),
    	),
    	'fields' => 'ids',
    );

    $plans = get_posts( $args );

    if ( empty( $plans ) ) {
    	return '<p>' . __( 'There are no plans associated with the Player role.', 'sportspress' ) . '<p>';
    }

		return self::register_form( $atts, 'player', $plans );
	}

	/**
	 * Registration form template.
	 */
	public static function register_form( $atts = array(), $context = '', $plans = array() ) {

    $atts = shortcode_atts( array(
        'plans_position'     => 'bottom',
        'selected'           => '',
    ), $atts );

    $atts['subscription_plans'] = implode( ',', $plans );

    $atts['context'] = $context;

    $shortcode = '[wpay-register';

    foreach ( $atts as $key => $value ) {
    	$shortcode .= ' ' . $key . '="' . esc_attr( $value ) . '"';
    }

    $shortcode .= ']';

    return do_shortcode( $shortcode );
	}

	/**
	 * Add field to registration form.
	 */
	public static function form_field( $atts = array() ) {
		if ( 'team' == $atts['context'] ) {
			?>
			<li class="wpay-field">
			<label for="wpay_team_name"><?php _e( 'Team Name', 'sportspress' ); ?></label>
			<input id="wpay_team_name" name="team_name" type="text" value="">
			</li>
			<?php
			wp_nonce_field( 'submit_team_name', 'sp_register_form_team' );
		} elseif ( 'player' == $atts['context'] ) {
			?>
			<li class="wpay-field">
				<label for="sp_team"><?php _e( 'Team', 'sportspress' ); ?></label>
				<?php
				$args = array(
					'post_type' => 'sp_team',
					'name' => 'sp_team',
					'values' => 'ID',
					'show_option_none' => sprintf( __( 'Select %s', 'sportspress' ), __( 'Team', 'sportspress' ) ),
					'class' => 'widefat',
				);
				sp_dropdown_pages( $args );
				?>
			</li>
			<?php
			wp_nonce_field( 'submit_team', 'sp_register_form_player' );
		}
	}

	/**
	 * Add shortcodes to editor.
	 */
	public static function editor_shortcodes( $shortcodes = array() ) {
		$shortcodes[] = 'register_team';
		$shortcodes[] = 'register_player';
		return $shortcodes;
	}

	/**
	 * Add strings to editor.
	 */
	public static function editor_strings( $strings = array() ) {
		$strings['register_team'] = __( 'Register Team', 'sportspress' );
		$strings['register_player'] = __( 'Register Player', 'sportspress' );
		return $strings;
	}

	/**
	 * Add selector to widget form.
	 */
	public static function widget_form( $widget, $instance = array() ) {
		$contexts = array(
			'' => __( 'Members', 'sportspress' ),
			'team' => __( 'Teams', 'sportspress' ),
			'player' => __( 'Players', 'sportspress' ),
		);
		?>
		<p>
			<label for="<?php echo $widget->get_field_id('context'); ?>"><?php _e( 'For:', 'sportspress' ); ?></label>
				<select id="<?php echo $widget->get_field_id('context'); ?>" name="<?php echo $widget->get_field_name('context'); ?>">
					<?php foreach ( $contexts as $value => $label ) { ?>
						<option value="<?php echo $value; ?>" <?php selected( $value, sp_array_value( $instance, 'context' ) ); ?>><?php echo $label; ?></option>
					<?php } ?>
				</select>
		</p>
		<?php
	}

	/**
	 * Update widget form.
	 */
	public static function widget_update( $instance = array(), $new_instance = array() ) {
		$instance['context'] = strip_tags($new_instance['context']);
		return $instance;
	}

	/**
	 * Modify widget shortcode.
	 */
	public static function widget_shortcode( $shortcode = '[wpay-register]', $instance = array() ) {
		if ( ! empty( $instance['context'] ) && in_array( $instance['context'], array( 'team', 'player' ) ) ) {
			$shortcode = str_replace( 'wpay-register', 'wpay-register-' . $instance['context'], $shortcode );
		}
		return $shortcode;
	}
}

endif;

new SportsPress_WordPay();