<?php
/*
Plugin Name: SportsPress Sponsors
Plugin URI: http://tboy.co/pro
Description: Add sponsors to SportsPress.
Author: ThemeBoy
Author URI: http://themeboy.com
Version: 2.6.15
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_Sponsors' ) ) :

/**
 * Main SportsPress Sponsors Class
 *
 * @class SportsPress_Sponsors
 * @version	2.6.15
 */
class SportsPress_Sponsors {

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

		add_action( 'sportspress_include_post_type_handlers', array( $this, 'include_post_type_handler' ) );
		add_action( 'sportspress_screen_ids', array( $this, 'screen_ids' ) );
		add_action( 'sportspress_after_single_sponsor', array( $this, 'sponsor_link' ), 10 );
		add_filter( 'sportspress_post_types', array( $this, 'add_post_type' ) );
		add_filter( 'sportspress_primary_post_types', array( $this, 'add_post_type' ) );
		add_filter( 'sportspress_importable_post_types', array( $this, 'add_post_type' ) );
		add_filter( 'sportspress_taxonomies', array( $this, 'add_taxonomy' ) );
		add_filter( 'sportspress_post_type_hierarchy', array( $this, 'add_to_hierarchy' ) );
	    add_filter( 'sportspress_get_settings_pages', array( $this, 'add_settings_page' ) );
	    add_filter( 'sportspress_enqueue_styles', array( $this, 'add_styles' ) );
		add_action( 'sportspress_widgets', array( $this, 'widgets' ) );
		add_filter( 'sportspress_menu_items', array( $this, 'add_menu_item' ), 20 );
	    add_filter( 'sportspress_glance_items', array( $this, 'add_glance_item' ) );
	    add_filter( 'sportspress_enable_header', '__return_true' );
		add_filter( 'sportspress_importers', array( $this, 'register_importer' ) );
		add_filter( 'sportspress_setup_pages', array( $this, 'setup_pages' ) );

		add_action( 'wp_enqueue_scripts', array( $this, 'load_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'wp_footer', array( $this, 'header' ) );
		add_action( 'get_footer', array( $this, 'footer' ) );
		add_filter( 'manage_edit-sp_level_columns', array( $this, 'taxonomy_columns' ) );
		add_action( 'sp_level_edit_form_fields', array( $this, 'edit_taxonomy_fields' ), 10, 1 );
		add_action( 'edited_sp_level', array( $this, 'save_taxonomy_fields' ), 10, 1 );
		add_filter( 'manage_sp_level_custom_column', array( $this, 'taxonomy_column_value' ), 10, 3 );
		add_filter( 'manage_edit-sp_sponsor_columns', array( $this, 'edit_columns' ) );
		add_filter( 'gettext', array( $this, 'gettext' ), 20, 3 );

		add_action( 'sportspress_create_rest_routes', array( $this, 'create_rest_routes' ) );
		add_action( 'sportspress_register_rest_fields', array( $this, 'register_rest_fields' ) );

		if ( is_admin() ) {
			add_action( 'wp_ajax_nopriv_sp_clicks', array( $this, 'sp_clicks' ) );
			add_action( 'wp_ajax_sp_sponsors', array( $this, 'sp_sponsors' ) );
			add_action( 'wp_ajax_nopriv_sp_sponsors', array( $this, 'sp_sponsors' ) );
		}
	}

	/**
	 * Define constants
	*/
	private function define_constants() {
		if ( !defined( 'SP_SPONSORS_VERSION' ) )
			define( 'SP_SPONSORS_VERSION', '2.6.15' );

		if ( !defined( 'SP_SPONSORS_URL' ) )
			define( 'SP_SPONSORS_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'SP_SPONSORS_DIR' ) )
			define( 'SP_SPONSORS_DIR', plugin_dir_path( __FILE__ ) );
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
		include_once( 'includes/class-sp-sponsor-template-loader.php' );
		include_once( 'includes/class-sp-shortcode-sponsors.php' );
	}

	/**
	 * Init plugin when WordPress Initialises.
	 */
	public function init() {
		// Register taxonomy
		$this->register_taxonomy();

		// Register post type
		$this->register_post_type();
	}

	/**
	 * Register sponsorship level taxonomy
	 */
	public static function register_taxonomy() {
		if ( apply_filters( 'sportspress_has_levels', true ) ):
			$labels = array(
				'name' => __( 'Levels', 'sportspress' ),
				'singular_name' => __( 'Level', 'sportspress' ),
				'all_items' => __( 'All', 'sportspress' ),
				'edit_item' => __( 'Edit Level', 'sportspress' ),
				'view_item' => __( 'View', 'sportspress' ),
				'update_item' => __( 'Update', 'sportspress' ),
				'add_new_item' => __( 'Add New', 'sportspress' ),
				'new_item_name' => __( 'Name', 'sportspress' ),
				'parent_item' => __( 'Parent', 'sportspress' ),
				'parent_item_colon' => __( 'Parent:', 'sportspress' ),
				'search_items' =>  __( 'Search', 'sportspress' ),
				'not_found' => __( 'No results found.', 'sportspress' ),
			);
			$args = apply_filters( 'sportspress_register_taxonomy_level', array(
				'label' => __( 'Levels', 'sportspress' ),
				'labels' => $labels,
				'public' => true,
				'show_ui' => true,
				'show_in_menu' => true,
				'show_in_nav_menus' => false,
				'show_tagcloud' => false,
				'hierarchical' => true,
				'rewrite' => array( 'slug' => get_option( 'sportspress_level_slug', 'level' ) ),
				'show_in_rest' => true,
				'rest_controller_class' => 'SP_REST_Terms_Controller',
				'rest_base' => 'levels',
			) );
			$object_types = apply_filters( 'sportspress_level_object_types', array( 'sp_sponsor' ) );
			register_taxonomy( 'sp_level', $object_types, $args );
			foreach ( $object_types as $object_type ):
				register_taxonomy_for_object_type( 'sp_level', $object_type );
			endforeach;
		endif;
	}

	/**
	 * Register sponsors post type
	 */
	public static function register_post_type() {
		register_post_type( 'sp_sponsor',
			apply_filters( 'sportspress_register_post_type_sponsor',
				array(
					'labels' => array(
						'name' 					=> __( 'Sponsors', 'sportspress' ),
						'singular_name' 		=> __( 'Sponsor', 'sportspress' ),
						'add_new_item' 			=> __( 'Add New Sponsor', 'sportspress' ),
						'edit_item' 			=> __( 'Edit Sponsor', 'sportspress' ),
						'new_item' 				=> __( 'New', 'sportspress' ),
						'view_item' 			=> __( 'View', 'sportspress' ),
						'search_items' 			=> __( 'Search', 'sportspress' ),
						'not_found' 			=> __( 'No results found.', 'sportspress' ),
						'not_found_in_trash' 	=> __( 'No results found.', 'sportspress' ),
						'featured_image'		=> __( 'Logo', 'sportspress' ),
 						'set_featured_image' 	=> __( 'Select Logo', 'sportspress' ),
 						'remove_featured_image' => __( 'Remove Logo', 'sportspress' ),
 						'use_featured_image' 	=> __( 'Select Logo', 'sportspress' ),
					),
					'public' 				=> true,
					'show_ui' 				=> true,
					'capability_type' 		=> 'sp_sponsor',
					'map_meta_cap' 			=> true,
					'publicly_queryable' 	=> true,
					'exclude_from_search' 	=> false,
					'hierarchical' 			=> false,
					'rewrite' 				=> array( 'slug' => get_option( 'sportspress_sponsor_slug', 'sponsor' ) ),
					'supports' 				=> array( 'title', 'editor', 'author', 'thumbnail', 'page-attributes' ),
					'has_archive' 			=> false,
					'show_in_nav_menus' 	=> true,
					'menu_icon' 			=> 'dashicons-megaphone',
					'show_in_rest' 			=> true,
					'rest_controller_class' => 'SP_REST_Posts_Controller',
					'rest_base' 			=> 'sponsors',
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
		if ( ! defined( 'IFRAME_REQUEST' ) && get_option( 'sportspress_sponsors_version' ) != SP_SPONSORS_VERSION ) {
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
		$ids[] = 'edit-sp_sponsor';
		$ids[] = 'sp_sponsor';
		$ids[] = 'edit-sp_level';
		$ids[] = 'sp_level';
		return $ids;
	}

	/**
	 * Register importer
	 */
	public function register_importer( $importers = array() ) {
		$importers['sp_sponsor_csv'] = array(
			'name' => __( 'SportsPress Sponsors (CSV)', 'sportspress' ),
			'description' => __( 'Import <strong>sponsors</strong> from a csv file.', 'sportspress'),
			'callback' => array( $this, 'sponsors_importer' ),
		);
		return $importers;
	}

	/**
	 * Sponsors importer
	 */
	public function sponsors_importer() {
		SP_Admin_Importers::includes();
		
	    require 'includes/class-sp-sponsor-importer.php';

	    // Dispatch
	    $importer = new SP_Sponsor_Importer();
	    $importer->dispatch();
	}

	/**
	 * Add settings page
	 */
	public function add_settings_page( $settings = array() ) {
		$settings[] = include( 'includes/class-sp-settings-sponsors.php' );
		return $settings;
	}

	/**
	 * Add styles to SP frontend
	 */
	public function add_styles( $styles = array() ) {
		$styles['sportspress-sponsors'] = array(
			'src'     => str_replace( array( 'http:', 'https:' ), '', SP_SPONSORS_URL ) . 'css/sportspress-sponsors.css',
			'deps'    => 'sportspress-general',
			'version' => SP_SPONSORS_VERSION,
			'media'   => 'all'
		);
		return $styles;
	}

	/**
	 * Add pages to setup wizard.
	 */
	public function setup_pages( $pages = array() ) {
    $pages['sp_sponsor'] = __( 'Attract sponsors by offering them advertising space on your website.', 'sportspress' );
    return $pages;
  }

	/**
	 * Register/queue frontend scripts.
	 *
	 * @access public
	 * @return void
	 */
	public function load_scripts() {
		wp_enqueue_script( 'sportspress-sponsors', SP_SPONSORS_URL .'js/sportspress-sponsors.js', array( 'jquery' ), time(), true );
	}

	/**
	 * Install
	 */
	public function install() {
		$this->add_capabilities();
		$this->register_post_type();

		// Queue upgrades
		$current_version = get_option( 'sportspress_sponsors_version', null );

		// Update version
		update_option( 'sportspress_sponsors_version', SP_SPONSORS_VERSION );

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
			$capability_type = 'sp_sponsor';
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
		$sportspress_sponsor = array_search( 'edit.php?post_type=sp_sponsor', $menu_order );

		// Loop through menu order and do some rearranging
		foreach ( $menu_order as $index => $item ) :

			if ( ( ( 'sportspress' ) == $item ) ) :
				$sportspress_menu_order[] = 'separator-sportspress';
				$sportspress_menu_order[] = $item;
				$sportspress_menu_order[] = 'edit.php?post_type=sp_event';
				$sportspress_menu_order[] = 'edit.php?post_type=sp_team';
				$sportspress_menu_order[] = 'edit.php?post_type=sp_player';
				$sportspress_menu_order[] = 'edit.php?post_type=sp_sponsor';
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

	/**
	 * Ajax sponsors loader
	 */
	public static function sp_sponsors() {
		if ( isset( $_POST['nonce'] ) && wp_verify_nonce( $_POST['nonce'], 'sp_sponsors' ) ) {
			sp_get_template( 'sponsors-content.php', array( 'level' => $_POST['level'], 'limit' => $_POST['limit'], 'width' => $_POST['width'], 'height' => $_POST['height'], 'orderby' => 'rand', 'size' => $_POST['size'] ), '', SP_SPONSORS_DIR . 'templates/' );
		}
		exit();
	}

	public static function add_post_type( $post_types = array() ) {
		$post_types[] = 'sp_sponsor';
		return $post_types;
	}

	public static function add_taxonomy( $taxonomies = array() ) {
		$taxonomies[] = 'sp_level';
		return $taxonomies;
	}

	public static function add_to_hierarchy( $hierarchy = array() ) {
		$hierarchy['sp_sponsor'] = array( 'sp_level' );
		return $hierarchy;
	}

	/**
	 * Taxonomy columns.
	 *
	 * @access public
	 * @param mixed $columns
	 * @return array
	 */
	public function taxonomy_columns( $columns ) {
		$new_columns = array();
		
		if ( function_exists( 'get_term_meta' ) ) $new_columns['sp_order'] = __( 'Order', 'sportspress' );

		if ( array_key_exists('posts', $columns) ) {
		$new_columns['posts'] = $columns['posts'];

		unset( $columns['posts'] );
		}

		return array_merge( $columns, $new_columns );
	}

	/**
	 * Edit taxonomy fields.
	 *
	 * @access public
	 * @param mixed $term Term (category) being edited
	 */
	public function edit_taxonomy_fields( $term ) {
	 	$t_id = $term->term_id;
		?>
		<?php if ( function_exists( 'get_term_meta' ) ) { ?>
			<?php $order = get_term_meta( $t_id, 'sp_order', true ); ?>
			<tr class="form-field">
				<th scope="row" valign="top"><label for="sp_order"><?php _e( 'Order', 'sportspress' ); ?></label></th>
				<td><input name="sp_order" class="sp-number-input" type="text" step="1" size="4" id="sp_order" value="<?php echo (int) $order; ?>"></td>
			</tr>
		<?php } ?>
	<?php
	}

	/**
	 * Save taxonomy fields.
	 *
	 * @access public
	 * @param mixed $term_id Term ID being saved
	 * @return void
	 */
	public function save_taxonomy_fields( $term_id ) {
		if ( function_exists( 'add_term_meta' ) ) {
			update_term_meta( $term_id, 'sp_order', (int) sp_array_value( $_POST, 'sp_order', 0 ) );
		}
	}

	public static function edit_columns() {
		$columns = array(
			'cb' => '<input type="checkbox" />',
			'sp_icon' => '&nbsp;',
			'title' => __( 'Name', 'sportspress' ),
		);
		return $columns;
	}

	/**
	 * Column value added to category admin.
	 *
	 * @access public
	 * @param mixed $columns
	 * @param mixed $column
	 * @param mixed $id
	 * @return array
	 */
	public function taxonomy_column_value( $columns, $column, $id ) {

		if ( $column == 'sp_address' ) {

			$term_meta = get_option( "taxonomy_$id" );

			$address = ( isset( $term_meta['sp_address'] ) ? $term_meta['sp_address'] : '&mdash;' );

			$columns .= $address;

		} elseif ( $column == 'sp_sections' ) {
			
			$options = apply_filters( 'sportspress_performance_sections', array( 0 => __( 'Offense', 'sportspress' ), 1 => __( 'Defense', 'sportspress' ) ) );

			$sections = sp_get_term_sections( $id );
			
			$section_names = array();
			
			if ( is_array( $sections ) ) {
				foreach ( $sections as $section ) {
					if ( array_key_exists( $section, $options ) ) {
						$section_names[] = $options[ $section ];
					}
				}
			}
			
			$columns .= implode( ', ', $section_names );

		} elseif ( $column == 'sp_order' ) {

			$columns = (int) get_term_meta( $id, 'sp_order', true );

		}

		return $columns;
	}

	public static function gettext( $translated_text, $untranslated_text, $domain ) {
		global $typenow;

		if ( is_admin() ):
			if ( in_array( $typenow, array( 'sp_sponsor' ) ) ):
				switch ( $untranslated_text ):
				case 'Author':
					$translated_text = __( 'User', 'sportspress' );
					break;
				case 'Enter title here':
					$translated_text = __( 'Sponsor', 'sportspress' );
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
		$items[] = 'edit.php?post_type=sp_sponsor';
		return $items;
	}

	/**
	 * Add glance item
	 */
	public function add_glance_item( $items ) {
		$items[] = 'sp_sponsor';
		return $items;
	}

	public static function admin_enqueue_scripts() {
		wp_enqueue_style( 'sportspress-sponsors-admin', SP_SPONSORS_URL . 'css/admin.css', array( 'sportspress-admin-menu-styles' ), time() );
	}

	public static function sponsor_link() {
		$id = get_the_ID();
		$url = get_post_meta( $id, 'sp_url', true );
		if ( $url ): ?>
			<a class="button sponsor sp-sponsor sp-button sp-sponsor-button" href="<?php echo $url; ?>" data-nonce="<?php echo wp_create_nonce( 'sp_clicks_' . $id ); ?>" data-ajaxurl="<?php echo admin_url( 'admin-ajax.php' ); ?>" data-post="<?php echo $id; ?>"<?php if ( get_option( 'sportspress_sponsor_site_target_blank', 'no' ) == 'yes' ) { ?> target="_blank"<?php } ?>><?php _e( 'Visit Site', 'sportspress' ); ?></a>
		<?php endif;
	}

	public static function header() {
		$limit = get_option( 'sportspress_header_sponsors_limit', 0 );
		if ( $limit ) {
			$width = get_option( 'sportspress_header_sponsor_width', 128 );
			$height = get_option( 'sportspress_header_sponsor_height', 64 );
			$top = get_option( 'sportspress_header_sponsors_top', 10 );
			$right = get_option( 'sportspress_header_sponsors_right', 10 );
			$orderby = get_option( 'sportspress_header_sponsors_orderby', 'menu_order' );
			$order = get_option( 'sportspress_header_sponsors_order', 'ASC' );
			?>
			<div class="sp-header-sponsors" style="margin-top: <?php echo $top; ?>px; margin-right: <?php echo $right; ?>px;">
				<?php echo do_shortcode( '[sponsors limit="' . $limit . '" width="' . $width . '" height="' . $height . '" orderby="' . $orderby . '" order="' . $order . '"]' ); ?>
			</div>
			<script type="text/javascript">
			jQuery(document).ready( function($) {
				$('<?php echo apply_filters( 'sportspress_header_sponsors_selector', '.sp-header' ); ?>').prepend( $('.sp-header-sponsors') );
			} );
			</script>
			<?php
		}
	}

	public static function footer() {
		$limit = get_option( 'sportspress_footer_sponsors_limit', 0 );
		if ( $limit ) {
			$title = get_option( 'sportspress_footer_sponsors_title', null );
			$width = get_option( 'sportspress_footer_sponsor_width', 256 );
			$height = get_option( 'sportspress_footer_sponsor_height', 128 );
			$orderby = get_option( 'sportspress_footer_sponsors_orderby', 'menu_order' );
			$order = get_option( 'sportspress_footer_sponsors_order', 'ASC' );

			$background_color = get_option( 'sportspress_footer_sponsors_css_background', '#f4f4f4' );
			$text_color = get_option( 'sportspress_footer_sponsors_css_text', '#363f48' );
			?>
			<style type="text/css">
			.sp-footer-sponsors {
				background: <?php echo $background_color; ?>;
				color: <?php echo $text_color; ?>;
			}
			.sp-footer-sponsors .sp-sponsors .sp-sponsors-title {
				color: <?php echo $text_color; ?>;
			}
			</style>
			<div class="sp-footer-sponsors">
				<?php echo do_shortcode( '[sponsors limit="' . $limit . '" width="' . $width . '" height="' . $height . '" title="' . $title . '" orderby="' . $orderby . '" order="' . $order . '"]' ); ?>
			</div>
			<?php
		}
	}

	public static function widgets() {
		include_once( 'includes/class-sp-widget-sponsors.php' );
	}

	/**
	 * Create REST API routes.
	 */
	public function create_rest_routes() {
		$controller = new SP_REST_Posts_Controller( 'sp_sponsor' );
		$controller->register_routes();
	}

	/**
	 * Register REST API fields.
	 */
	public function register_rest_fields() {
		register_rest_field( 'sp_sponsor',
			'url',
			array(
				'get_callback'    => 'SP_REST_API::get_post_meta',
				'update_callback' => 'SP_REST_API::update_post_meta',
				'schema'          => array(
					'description'     => __( 'Site URL', 'sportspress' ),
					'type'            => 'string',
					'context'         => array( 'view', 'edit', 'embed' ),
					'arg_options'     => array(
						'sanitize_callback' => 'sanitize_text_field',
					),
				),
			)
		);
	}
}

endif;

if ( get_option( 'sportspress_load_sponsors_module', 'yes' ) == 'yes' ) {
	new SportsPress_Sponsors();
}

