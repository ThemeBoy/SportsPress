<?php
/**
 * Plugin Name: SportsPress Pro
 * Plugin URI: http://sportspresspro.com/
 * Description: Manage your club and its players, staff, events, league tables, and player lists.
 * Version: 1.3
 * Author: ThemeBoy
 * Author URI: http://themeboy.com
 * Requires at least: 3.8
 * Tested up to: 4.0
 *
 * Text Domain: sportspress
 * Domain Path: /languages/
 *
 * @package SportsPress_Pro
 * @category Pro
 * @author ThemeBoy
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'SportsPress_Pro' ) ) :

/**
 * Main SportsPress Pro Class
 *
 * @class SportsPress_Pro
 * @version	1.3
 */
final class SportsPress_Pro {

	/**
	 * @var string
	 */
	public $version = '1.3';

	/**
	 * SportsPress Pro Constructor.
	 * @access public
	 * @return SportsPress_Pro
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();

		// Include required files
		$this->includes();

		// Install
		if ( class_exists( 'SP_Install' ) ):
			$install = new SP_Install();
			register_activation_hook( SP_PRO_PLUGIN_FILE, array( $install, 'install' ) );
		endif;

		// Hooks
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'action_links' ) );
		add_action( 'before_sportspress_init', array( $this, 'load_module_translations' ), 0 );
		add_action( 'get_the_generator_html', array( $this, 'generator_tag' ), 10, 2 );
		add_action( 'get_the_generator_xhtml', array( $this, 'generator_tag' ), 10, 2 );
		add_action( 'themeboy_required_plugins', array( $this, 'unrequire_core' ) );

		// Multisite support
		if ( is_multisite() ):
			// Widgets
			add_filter( 'sportspress_widget_update', array( $this, 'multisite_widget_update' ), 10, 4 );
			add_filter( 'sportspress_widget_defaults', array( $this, 'multisite_widget_defaults' ) );
			add_action( 'sportspress_before_widget', array( $this, 'multisite_before_widget'), 10, 3 );
			add_action( 'sportspress_after_widget', array( $this, 'multisite_after_widget'), 10, 3 );
			add_action( 'sportspress_before_widget_form', array( $this, 'multisite_before_widget_form' ), 10, 3 );
			add_action( 'sportspress_after_widget_form', array( $this, 'multisite_after_widget_form' ), 10, 3 );
		endif;

		// Loaded action
		do_action( 'sportspress_pro_loaded' );
	}

	/**
	 * Change menu icon
	 */
	public function menu_icon( ) {
		return 'dashicons-chart-bar';
	}

	/**
	 * Show action links on the plugin screen.
	 *
	 * @param mixed $links
	 * @return array
	 */
	public function action_links( $links ) {
		return array_merge( array(
			'<a href="' . admin_url( 'admin.php?page=sportspress' ) . '">' . __( 'Settings', 'sportspress' ) . '</a>',
			'<a href="' . apply_filters( 'sportspress_docs_url', 'http://sportspresspro.com/docs/' ) . '">' . __( 'Docs', 'sportspress' ) . '</a>',
			'<a href="' . apply_filters( 'sportspress_support_url', 'http://sportspresspro.com/support/' ) . '">' . __( 'Support', 'sportspress' ) . '</a>',
		), $links );
	}

	/**
	 * Define SP Constants.
	 */
	private function define_constants() {
		define( 'SP_PRO_PLUGIN_FILE', __FILE__ );
		define( 'SP_PRO_VERSION', $this->version );

		if ( ! defined( 'SP_PRO_TEMPLATE_PATH' ) ) {
			define( 'SP_PRO_TEMPLATE_PATH', $this->template_path() );
		}

		if ( ! defined( 'SP_PRO_DELIMITER' ) ) {
			define( 'SP_PRO_DELIMITER', '|' );
		}
	}

	/**
	 * Include SportsPress core and modules.
	 */
	private function includes() {
		include_once( $this->plugin_path() . '/core/sportspress.php' );

		$dir = scandir( $this->plugin_path() . '/modules' );
		if ( $dir ) {
			foreach ( $dir as $module ) {
				$path = $this->plugin_path() . '/modules/' . $module;
				if ( $path && substr( $module, 0, 1 ) !== '.' ) {
					$file = '/' . $module . '.php';
					if ( is_readable( $path . $file ) ) {
						include_once( $path . $file );
					}
				}
			}
		}
	}

	/**
	 * Load Localisation files.
	 *
	 * Note: the first-loaded translation file overrides any following ones if the same translation is present
	 */
	public function load_module_translations() {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'sportspress' );

		// Global + Frontend Locale
		load_textdomain( 'sportspress', dirname( __FILE__ ) . "/languages/sportspress-pro-$locale.mo" );
	}

	/**
	 * Output generator tag to aid debugging.
	 */
	function generator_tag( $gen, $type ) {
		switch ( $type ) {
			case 'html':
				$gen .= "\n" . '<meta name="generator" content="SportsPress Pro ' . esc_attr( SP_PRO_VERSION ) . '">';
				break;
			case 'xhtml':
				$gen .= "\n" . '<meta name="generator" content="SportsPress Pro ' . esc_attr( SP_PRO_VERSION ) . '" />';
				break;
		}
		return $gen;
	}

	/**
	 * Unrequire SportsPress core
	 */
	function unrequire_core( $plugins = array() ) {
		foreach ( $plugins as $index => $plugin ):
			if ( sp_array_value( $plugin, 'slug' ) == 'sportspress' ) unset( $plugins[ $index ] );
		endforeach;
		return $plugins;
	}

	/**
	 * Before widget
	 */
	function  multisite_before_widget( $args, $instance, $widget = 'default' ) {
		$id = intval( $instance['site_id'] );
		if ( $id ) {
			switch_to_blog( $id);
		}
	}

	/**
	 * After widget
	 */
	function  multisite_after_widget( $args, $instance, $widget = 'default' ) {
		restore_current_blog();
	}

	/**
	 * Widget update
	 */
	function  multisite_widget_update( $instance, $new_instance, $old_instance, $widget = 'default' ) {
		$instance['site_id'] = intval( $new_instance['site_id'] );
		return $instance;
	}

	/**
	 * Widget defaults
	 */
	function  multisite_widget_defaults( $defaults, $widget = 'default' ) {
		global $blog_id;
		$defaults['site_id'] = $blog_id;
		return $defaults;
	}

	/**
	 * Before widget forms
	 */
	function  multisite_before_widget_form( $object, $instance, $widget = 'default' ) {
		?>
		<p><label for="<?php echo $object->get_field_id('site_id'); ?>"><?php printf( __( 'Site: %s', 'sportspress' ), '' ); ?></label>
			<select name="<?php echo $object->get_field_name('site_id'); ?>" id="<?php echo $object->get_field_id('site_id'); ?>" onchange="jQuery(this).closest('form').find('input[type=submit]').trigger('click')">
				<?php
				$id = intval( $instance['site_id'] );
				if ( $id ) {
					switch_to_blog( $id);
				}

				global $wpdb, $blog_id;
		 
			    $blogs = $wpdb->get_results("
			        SELECT blog_id
			        FROM {$wpdb->blogs}
			        WHERE site_id = '{$wpdb->siteid}'
			        AND spam = '0'
			        AND deleted = '0'
			        AND archived = '0'
			    ");
			 
			    $sites = array();
			    foreach ($blogs as $blog) {
			        $sites[$blog->blog_id] = get_blog_option($blog->blog_id, 'blogname');
			    }
			    natsort($sites);

			    foreach ( $sites as $site_id => $site_title ) {
			        printf( '<option value="%d" %s>%s</option>', $site_id, ( $site_id == $blog_id ? 'selected' : '' ), $site_title );
			    }
				?>
			</select>
		</p>
		<?php
	}

	/**
	 * After widget forms
	 */
	function  multisite_after_widget_form( $object, $instance, $widget = 'default' ) {
		restore_current_blog();
	}

	/** Helper functions ******************************************************/

	/**
	 * Get the plugin url.
	 *
	 * @return string
	 */
	public function plugin_url() {
		return untrailingslashit( plugins_url( '/', __FILE__ ) );
	}

	/**
	 * Get the plugin path.
	 *
	 * @return string
	 */
	public function plugin_path() {
		return untrailingslashit( plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Get the template path.
	 *
	 * @return string
	 */
	public function template_path() {
		return apply_filters( 'SP_PRO_TEMPLATE_PATH', 'sportspress-pro/' );
	}
}

endif;

new SportsPress_Pro();
