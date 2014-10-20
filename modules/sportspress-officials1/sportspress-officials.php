<?php
/*
Plugin Name: SportsPress Officials
Plugin URI: http://sportspresspro.com/
Description: Add officials to SportsPress.
Author: ThemeBoy
Author URI: http://sportspresspro.com
Version: 1.0
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Main SportsPress Officials Class
 *
 * @class SportsPress_Officials
 * @version	1.0
 */
class SportsPress_Officials {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();

		// Include required files
		$this->includes();

		// Hooks
		add_action( 'init', array( $this, 'init' ), 15 );
		add_action( 'admin_init', array( $this, 'check_version' ), 5 );
		add_action( 'admin_init', array( $this, 'register_importer' ) );

		//add_action( 'sportspress_include_post_type_handlers', array( $this, 'include_post_type_handler' ) );
		//add_action( 'sportspress_screen_ids', array( $this, 'screen_ids' ) );
		//add_action( 'sportspress_after_single_sponsor', array( $this, 'sponsor_link' ), 10 );
		add_filter( 'sportspress_post_types', array( $this, 'add_post_type' ) );
		add_filter( 'sportspress_primary_post_types', array( $this, 'add_post_type' ) );
		//add_filter( 'sportspress_post_type_hierarchy', array( $this, 'add_to_hierarchy' ) );
	    //add_filter( 'sportspress_get_settings_pages', array( $this, 'add_settings_page' ) );
	    //add_filter( 'sportspress_enqueue_styles', array( $this, 'add_styles' ) );
		//add_filter( 'sportspress_text', array( $this, 'add_text_options' ) );
		//add_action( 'sportspress_widgets', array( $this, 'widgets' ) );
		add_filter( 'sportspress_menu_items', array( $this, 'add_menu_item' ), 10 );

		add_action( 'wp_enqueue_scripts', array( $this, 'load_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'wp_footer', array( $this, 'header' ) );
		add_action( 'get_footer', array( $this, 'footer' ) );
		add_filter( 'manage_edit-sp_official_columns', array( $this, 'edit_columns' ) );
		add_filter( 'gettext', array( $this, 'gettext' ), 20, 3 );
	}

	/**
	 * Define constants
	*/
	private function define_constants() {
		if ( !defined( 'SP_OFFICIALS_VERSION' ) )
			define( 'SP_OFFICIALS_VERSION', '1.0' );

		if ( !defined( 'SP_OFFICIALS_URL' ) )
			define( 'SP_OFFICIALS_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'SP_OFFICIALS_DIR' ) )
			define( 'SP_OFFICIALS_DIR', plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Include required files used in admin and on the frontend.
	 */
	private function includes() {
		include_once( 'includes/sp-sponsor-functions.php' );

		if ( ! is_admin() || defined( 'DOING_AJAX' ) ) {
			$this->frontend_includes();
		}
	}

	/**
	 * Include required frontend files.
	 */
	public function frontend_includes() {
		//include_once( 'includes/class-sp-sponsor-template-loader.php' );
		//include_once( 'includes/class-sp-shortcode-officials.php' );
	}

	/**
	 * Init plugin when WordPress Initialises.
	 */
	public function init() {
		// Register post type
		$this->register_post_type();
	}

	/**
	 * Register officials post type
	 */
	public static function register_post_type() {
		register_post_type( 'sp_official',
			apply_filters( 'sportspress_register_post_type_sponsor',
				array(
					'labels' => array(
						'name' 					=> __( 'Officials', 'sportspress' ),
						'singular_name' 		=> __( 'Official', 'sportspress' ),
						'add_new_item' 			=> __( 'Add New Official', 'sportspress' ),
						'edit_item' 			=> __( 'Edit Official', 'sportspress' ),
						'new_item' 				=> __( 'New', 'sportspress' ),
						'view_item' 			=> __( 'View', 'sportspress' ),
						'search_items' 			=> __( 'Search', 'sportspress' ),
						'not_found' 			=> __( 'No results found.', 'sportspress' ),
						'not_found_in_trash' 	=> __( 'No results found.', 'sportspress' ),
					),
					'public' 				=> true,
					'show_ui' 				=> true,
					'capability_type' 		=> 'sp_official',
					'map_meta_cap' 			=> true,
					'publicly_queryable' 	=> true,
					'exclude_from_search' 	=> false,
					'hierarchical' 			=> false,
					'rewrite' 				=> array( 'slug' => get_option( 'sportspress_sponsor_slug', 'sponsor' ) ),
					'supports' 				=> array( 'title', 'editor', 'author', 'thumbnail', 'page-attributes' ),
					'has_archive' 			=> false,
					'show_in_nav_menus' 	=> true,
					'menu_icon' 			=> 'dashicons-flag',
				)
			)
		);
	}

	/**
	 * check_version function.
	 *
	 * @access public
	 * @return void
	 */
	public function check_version() {
		if ( ! defined( 'IFRAME_REQUEST' ) && get_option( 'sportspress_officials_version' ) != SP_OFFICIALS_VERSION ) {
			$this->install();
		}
	}

	/**
	 * Conditonally load classes and functions only needed when viewing the post type.
	 */
	public function include_post_type_handler() {
		include( 'includes/class-sp-meta-box-sponsor-details.php' );
		include( 'includes/class-sp-admin-cpt-sponsor.php' );
	}

	/**
	 * Add screen ids
	 */
	public function screen_ids( $ids = array() ) {
		$ids[] = 'edit-sp_official';
		$ids[] = 'sp_official';
		return $ids;
	}

	/**
	 * Add menu item
	 */
	public function register_importer() {
		register_importer( 'sportspress_sponsor_csv', __( 'SportsPress Officials (CSV)', 'sportspress' ), __( 'Import <strong>officials</strong> from a csv file.', 'sportspress'), array( $this, 'officials_importer' ) );
	}

	/**
	 * Officials importer
	 */
	public function officials_importer() {
		SP_Admin_Importers::includes();
		
	    require 'includes/class-sp-sponsor-importer.php';

	    // Dispatch
	    $importer = new SP_Official_Importer();
	    $importer->dispatch();
	}

	/**
	 * Add settings page
	 */
	public function add_settings_page( $settings = array() ) {
		$settings[] = include( 'includes/class-sp-settings-officials.php' );
		return $settings;
	}

	/**
	 * Add styles to SP frontend
	 */
	public function add_styles( $styles = array() ) {
		$styles['sportspress-officials'] = array(
			'src'     => str_replace( array( 'http:', 'https:' ), '', SP_OFFICIALS_URL ) . 'css/sportspress-officials.css',
			'deps'    => 'sportspress-general',
			'version' => SP_OFFICIALS_VERSION,
			'media'   => 'all'
		);
		return $styles;
	}

	/**
	 * Register/queue frontend scripts.
	 *
	 * @access public
	 * @return void
	 */
	public function load_scripts() {
		wp_enqueue_script( 'sportspress-officials', SP_OFFICIALS_URL .'js/sportspress-officials.js', array( 'jquery' ), time(), true );
	}

	/**
	 * Add text options 
	 */
	public function add_text_options( $options = array() ) {
		return array_merge( $options, array(
			__( 'Officials', 'sportspress' ),
		) );
	}

	/**
	 * Install
	 */
	public function install() {
		$this->add_capabilities();
		$this->register_post_type();

		// Queue upgrades
		$current_version = get_option( 'sportspress_officials_version', null );

		// Update version
		update_option( 'sportspress_officials_version', SP_OFFICIALS_VERSION );

		// Flush rules after install
		flush_rewrite_rules();
	}

	/**
	 * Add capabilities
	 */
	public function add_capabilities() {
		global $wp_roles;

		if ( class_exists( 'WP_Roles' ) ):
			if ( ! isset( $wp_roles ) ):
				$wp_roles = new WP_Roles();
			endif;
		endif;

		if ( is_object( $wp_roles ) ):
			$capability_type = 'sp_official';
			$capabilities = array(
				"edit_{$capability_type}",
				"read_{$capability_type}",
				"edit_{$capability_type}s",
				"edit_published_{$capability_type}s",
				"assign_{$capability_type}_terms",
			);

			foreach ( $capabilities as $cap ):
				$wp_roles->add_cap( 'sp_team_manager', $cap );
			endforeach;

			$capabilities = array_merge( $capabilities, array(
				"delete_{$capability_type}",
				"edit_others_{$capability_type}s",
				"publish_{$capability_type}s",
				"read_private_{$capability_type}s",
				"delete_{$capability_type}s",
				"delete_private_{$capability_type}s",
				"delete_published_{$capability_type}s",
				"delete_others_{$capability_type}s",
				"edit_private_{$capability_type}s",
				"manage_{$capability_type}_terms",
				"edit_{$capability_type}_terms",
				"delete_{$capability_type}_terms",
			));

			foreach ( $capabilities as $cap ):
				$wp_roles->add_cap( 'sp_league_manager', $cap );
				$wp_roles->add_cap( 'administrator', $cap );
			endforeach;
		endif;
	}

	/**
	 * Reorder the SP menu items in admin.
	 *
	 * @param mixed $menu_order
	 * @return array
	 */
	public function menu_order( $menu_order ) {
		// Initialize our custom order array
		$sportspress_menu_order = array();

		// Get the index of our custom separator
		$sportspress_separator = array_search( 'separator-sportspress', $menu_order );

		// Get index of menu items
		$sportspress_event = array_search( 'edit.php?post_type=sp_event', $menu_order );
		$sportspress_team = array_search( 'edit.php?post_type=sp_team', $menu_order );
		$sportspress_player = array_search( 'edit.php?post_type=sp_player', $menu_order );
		$sportspress_sponsor = array_search( 'edit.php?post_type=sp_official', $menu_order );

		// Loop through menu order and do some rearranging
		foreach ( $menu_order as $index => $item ) :

			if ( ( ( 'sportspress' ) == $item ) ) :
				$sportspress_menu_order[] = 'separator-sportspress';
				$sportspress_menu_order[] = $item;
				$sportspress_menu_order[] = 'edit.php?post_type=sp_event';
				$sportspress_menu_order[] = 'edit.php?post_type=sp_team';
				$sportspress_menu_order[] = 'edit.php?post_type=sp_player';
				$sportspress_menu_order[] = 'edit.php?post_type=sp_official';
				unset( $menu_order[$sportspress_separator] );
				unset( $menu_order[$sportspress_event] );
				unset( $menu_order[$sportspress_team] );
				unset( $menu_order[$sportspress_player] );
				unset( $menu_order[$sportspress_sponsor] );
			elseif ( !in_array( $item, array( 'separator-sportspress' ) ) ) :
				$sportspress_menu_order[] = $item;
			endif;

		endforeach;

		// Return order
		return $sportspress_menu_order;
	}

	/**
	 * custom_menu_order
	 * @return bool
	 */
	public function custom_menu_order() {
		if ( ! current_user_can( 'manage_sportspress' ) )
			return false;
		return true;
	}

	/**
	 * Ajax click counter
	 */
	public static function sp_clicks() {
		if ( isset( $_POST['nonce'] ) &&  isset( $_POST['post_id'] ) && wp_verify_nonce( $_POST['nonce'], 'sp_clicks_' . $_POST['post_id'] ) ) {
			sp_set_post_clicks( $_POST['post_id'] );
	    }
	    exit();
	}

	public static function add_post_type( $post_types = array() ) {
		$post_types[] = 'sp_official';
		return $post_types;
	}

	public static function add_to_hierarchy( $hierarchy = array() ) {
		$hierarchy['sp_official'] = array();
		return $hierarchy;
	}

	public static function edit_columns() {
		$columns = array(
			'cb' => '<input type="checkbox" />',
			'sp_icon' => '&nbsp;',
			'title' => __( 'Name', 'sportspress' ),
		);
		return $columns;
	}

	public static function gettext( $translated_text, $untranslated_text, $domain ) {
		global $typenow;

		if ( is_admin() ):
			if ( in_array( $typenow, array( 'sp_official' ) ) ):
				switch ( $untranslated_text ):
				case 'Set featured image':
					$translated_text = __( 'Select Logo', 'sportspress' );
					break;
				case 'Featured Image':
					$translated_text = __( 'Logo', 'sportspress' );
					break;
				case 'Set Featured Image':
					$translated_text = __( 'Select Logo', 'sportspress' );
					break;
				case 'Remove featured image':
					$translated_text = __( 'Remove Logo', 'sportspress' );
					break;
				case 'Author':
					$translated_text = __( 'User', 'sportspress' );
					break;
				case 'Enter title here':
					$translated_text = __( 'Official', 'sportspress' );
					break;
				endswitch;
			endif;
		endif;
		
		return $translated_text;
	}

	/**
	 * Add menu item
	 */
	public function add_menu_item( $items ) {
		$items[] = 'edit.php?post_type=sp_official';
		return $items;
	}

	public static function admin_enqueue_scripts() {
		wp_enqueue_style( 'sportspress-officials-admin', SP_OFFICIALS_URL . 'css/admin.css', array( 'sportspress-admin-menu-styles' ), time() );
	}

	public static function sponsor_link() {
		$id = get_the_ID();
		$url = get_post_meta( $id, 'sp_url', true );
		if ( $url ): ?>
			<a class="button sponsor sp-sponsor sp-button sp-sponsor-button" href="<?php echo $url; ?>" data-nonce="<?php echo wp_create_nonce( 'sp_clicks_' . $id ); ?>" data-ajaxurl="<?php echo admin_url( 'admin-ajax.php' ); ?>" data-post="<?php echo $id; ?>"><?php _e( 'Visit Site', 'sportspress' ); ?></a>
		<?php endif;
	}

	public static function header() {
		$limit = get_option( 'sportspress_header_officials_limit', 0 );
		if ( $limit ) {
			$width = get_option( 'sportspress_header_sponsor_width', 128 );
			$height = get_option( 'sportspress_header_sponsor_height', 64 );
			$top = get_option( 'sportspress_header_officials_top', 10 );
			$right = get_option( 'sportspress_header_officials_right', 10 );
			$orderby = get_option( 'sportspress_header_officials_orderby', 'menu_order' );
			$order = get_option( 'sportspress_header_officials_order', 'ASC' );
			?>
			<div class="sp-header-officials" style="margin-top: <?php echo $top; ?>px; margin-right: <?php echo $right; ?>px;">
				<?php echo do_shortcode( '[officials limit="' . $limit . '" width="' . $width . '" height="' . $height . '" orderby="' . $orderby . '" order="' . $order . '"]' ); ?>
			</div>
			<script type="text/javascript">
			jQuery(document).ready( function($) {
				$('.sp-header').prepend( $('.sp-header-officials') );
			} );
			</script>
			<?php
		}
	}

	public static function footer() {
		$limit = get_option( 'sportspress_footer_officials_limit', 0 );
		if ( $limit ) {
			$title = get_option( 'sportspress_footer_officials_title', null );
			$width = get_option( 'sportspress_footer_sponsor_width', 256 );
			$height = get_option( 'sportspress_footer_sponsor_height', 128 );
			$orderby = get_option( 'sportspress_footer_officials_orderby', 'menu_order' );
			$order = get_option( 'sportspress_footer_officials_order', 'ASC' );

			$background_color = get_option( 'sportspress_footer_officials_css_background', '#f4f4f4' );
			$text_color = get_option( 'sportspress_footer_officials_css_text', '#363f48' );
			?>
			<style type="text/css">
			.sp-footer-officials .sp-officials {
				background: <?php echo $background_color; ?>;
				color: <?php echo $text_color; ?>;
			}
			.sp-footer-officials .sp-officials .sp-officials-title {
				color: <?php echo $text_color; ?>;
			}
			</style>
			<div class="sp-footer-officials">
				<?php echo do_shortcode( '[officials limit="' . $limit . '" width="' . $width . '" height="' . $height . '" title="' . $title . '" orderby="' . $orderby . '" order="' . $order . '"]' ); ?>
			</div>
			<?php
		}
	}

	public static function widgets() {
		include_once( 'includes/class-sp-widget-officials.php' );
	}
}

new SportsPress_Officials();
