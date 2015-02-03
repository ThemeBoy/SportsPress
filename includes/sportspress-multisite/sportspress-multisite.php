<?php
/*
Plugin Name: SportsPress Multisite
Plugin URI: http://sportspresspro.com/
Description: Add multisite network support to SportsPress.
Author: ThemeBoy
Author URI: http://themeboy.com
Version: 1.6
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_Multisite' ) ) :

/**
 * Main SportsPress Multisite Class
 *
 * @class SportsPress_Multisite
 * @version	1.6
 */
class SportsPress_Multisite {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();

		// Multisite support
		if ( is_multisite() ):
			// Permalinks
			add_filter( 'post_type_link', array( $this, 'fix_permalink' ) );

			// Widgets
			add_filter( 'sportspress_widget_update', array( $this, 'multisite_widget_update' ), 10, 4 );
			add_filter( 'sportspress_widget_defaults', array( $this, 'multisite_widget_defaults' ) );
			add_action( 'sportspress_before_widget_template', array( $this, 'multisite_before_widget'), 10, 3 );
			add_action( 'sportspress_after_widget_template', array( $this, 'multisite_after_widget'), 10, 3 );
			add_action( 'sportspress_before_widget_template_form', array( $this, 'multisite_before_widget_form' ), 10, 3 );
			add_action( 'sportspress_after_widget_template_form', array( $this, 'multisite_after_widget_form' ), 10, 3 );
		endif;
	}

	/**
	 * Define constants
	*/
	private function define_constants() {
		if ( !defined( 'SP_MULTISITE_VERSION' ) )
			define( 'SP_MULTISITE_VERSION', '1.6' );

		if ( !defined( 'SP_MULTISITE_URL' ) )
			define( 'SP_MULTISITE_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'SP_MULTISITE_DIR' ) )
			define( 'SP_MULTISITE_DIR', plugin_dir_path( __FILE__ ) );
	}

	function fix_permalink( $url, $post = null ) {
	    if ( is_sp_post_type( get_post_type( $post ) ) && 1 !== get_current_blog_id() ) {
	    	return str_replace( get_site_url() . '/blog', get_site_url(), $url );
	    }
	    return $url;
	}

	/**
	 * Before widget
	 */
	function multisite_before_widget( $args, $instance, $widget = 'default' ) {
		$id = intval( $instance['site_id'] );
		if ( $id ) {
			switch_to_blog( $id );
		}
	}

	/**
	 * After widget
	 */
	function multisite_after_widget( $args, $instance, $widget = 'default' ) {
		restore_current_blog();
	}

	/**
	 * Widget update
	 */
	function multisite_widget_update( $instance, $new_instance, $old_instance, $widget = 'default' ) {
		$instance['site_id'] = intval( $new_instance['site_id'] );
		return $instance;
	}

	/**
	 * Widget defaults
	 */
	function multisite_widget_defaults( $defaults, $widget = 'default' ) {
		global $blog_id;
		$defaults['site_id'] = $blog_id;
		return $defaults;
	}

	/**
	 * Before widget forms
	 */
	function multisite_before_widget_form( $object, $instance, $widget = 'default' ) {
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
	function multisite_after_widget_form( $object, $instance, $widget = 'default' ) {
		restore_current_blog();
	}
}

endif;

if ( get_option( 'sportspress_load_multisite_module', 'yes' ) == 'yes' ) {
	new SportsPress_Multisite();
}
