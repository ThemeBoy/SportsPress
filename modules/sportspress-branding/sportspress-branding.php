<?php
/*
Plugin Name: SportsPress Branding
Plugin URI: http://sportspresspro.com/
Description: White label SportsPress branding.
Author: ThemeBoy
Author URI: http://sportspresspro.com
Version: 1.0
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Main SportsPress Branding Class
 *
 * @class SportsPress_Branding
 * @version	1.0
 */
class SportsPress_Branding {

	public $label = null;

	/**
	 * Constructor
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();

		// Include required files
		$this->includes();

		// Hooks
		add_action( 'init', array( $this, 'init' ) );

		add_filter( 'gettext', array( $this, 'gettext' ), 20, 3 );
		add_filter( 'sportspress_get_settings_pages', array( $this, 'add_settings_page' ) );
		add_filter( 'admin_init', array( $this, 'rename_color_scheme' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'admin_head', array( $this, 'admin_styles' ) );
	}

	/**
	 * Define constants.
	*/
	private function define_constants() {
		if ( !defined( 'SP_BRANDING_VERSION' ) )
			define( 'SP_BRANDING_VERSION', '1.0' );

		if ( !defined( 'SP_BRANDING_URL' ) )
			define( 'SP_BRANDING_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'SP_BRANDING_DIR' ) )
			define( 'SP_BRANDING_DIR', plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Include required files.
	*/
	private function includes() {
	}

	/**
	 * Init plugin when WordPress Initialises.
	 */
	public function init() {
		// Set up localisation
		$this->load_plugin_textdomain();

		// Get label
		$this->label = get_option( 'sportspress_branding_label' );
	}

	/**
	 * Load Localisation files.
	 *
	 * Note: the first-loaded translation file overrides any following ones if the same translation is present
	 */
	public function load_plugin_textdomain() {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'sportspress' );
		
		// Global + Frontend Locale
		load_plugin_textdomain( 'sportspress', false, plugin_basename( dirname( __FILE__ ) . "/languages" ) );
	}

	/** 
	 * Text filter.
	 */
	public function gettext( $translated_text, $untranslated_text, $domain ) {
		if ( $domain == 'sportspress' && ! empty( $this->label ) && strpos( $translated_text, 'SportsPress' ) !== false ):
			$translated_text = str_replace( 'SportsPress', $this->label, $translated_text );
		endif;
		
		return $translated_text;
	}

	/**
	 * Add settings page
	 */
	public function add_settings_page( $settings = array() ) {
		$settings[] = include( 'includes/class-sp-settings-branding.php' );
		return $settings;
	}

	/**
	 * Rename default color scheme
	 */
	public function rename_color_scheme() {
		global $_wp_admin_css_colors;

		$enabled = get_option( 'sportspress_enable_branding_css', 'no' );
		if ( $enabled !== 'yes' ) return $_wp_admin_css_colors;

		$colors = get_option( 'sportspress_branding_css_colors' );

		if ( ! $colors ) return $_wp_admin_css_colors;

		$base = sp_array_value( $colors, 'base', '222222' );
		$highlight = sp_array_value( $colors, 'highlight', '0074a2' );
		$notifications = sp_array_value( $colors, 'notifications', 'd54e21' );
		$actions = sp_array_value( $colors, 'actions', '2ea2cc' );
		$text = sp_array_value( $colors, 'text', 'ffffff' );

		$_wp_admin_css_colors['fresh']->colors = array(
			$base,
			$highlight,
			$notifications,
			$actions,
		);

		$_wp_admin_css_colors['fresh']->icon_colors = array(
			'base' => $base,
			'focus' => $highlight,
			'current' => $text,
		);

		if ( ! empty( $this->label ) ) {
			$_wp_admin_css_colors['fresh']->name = $this->label;
		}
		return $_wp_admin_css_colors;
	}

	/**
	 * Enqueue styles
	 */
	public function admin_enqueue_scripts() {
		global $wp_scripts;

		$screen = get_current_screen();

		if ( in_array( $screen->id, array( 'toplevel_page_sportspress' ) ) ) {
			wp_enqueue_style( 'sportspress-branding-admin', SP_BRANDING_URL . 'css/admin.css', array(), SP_BRANDING_VERSION );
			wp_enqueue_script( 'sportspress-branding-admin', SP_BRANDING_URL . 'js/admin.js', array( 'jquery' ), SP_BRANDING_VERSION );
			wp_enqueue_media();
			wp_enqueue_script( 'custom-header' );
		}
	}

	/**
	 * Custom admin styles
	 */
	public function admin_styles() {
		$icon = get_option( 'sportspress_branding_icon' );
		$src = wp_get_attachment_image_src( $icon, 'sportspress-fit-mini' );

		if ( $src ):
			?>
			<style type="text/css">
				#adminmenu #toplevel_page_sportspress .menu-icon-generic div.wp-menu-image:before {
					content: '';
					background-image: url(<?php echo $src[0]; ?>);
					background-repeat: no-repeat;
					background-size: contain;
					background-position: center center;
				}
			</style>
			<?php
		endif;
		
		$current_color = get_user_option( 'admin_color' );
		if ( $current_color != 'fresh' ) return;

		$enabled = get_option( 'sportspress_enable_branding_css', 'no' );
		if ( $enabled !== 'yes' ) return;

		$colors = get_option( 'sportspress_branding_css_colors', true );
		if ( ! $colors ) return;

		require_once( 'includes/libraries/class-sp-color.php' );

		$base = new SP_Color( str_replace( '#', '', sp_array_value( $colors, 'base', '222222' ) ) );
		$highlight = new SP_Color( str_replace( '#', '', sp_array_value( $colors, 'highlight', '0074a2' ) ) );
		$notifications = new SP_Color( str_replace( '#', '', sp_array_value( $colors, 'notifications', 'd54e21' ) ) );
		$actions = new SP_Color( str_replace( '#', '', sp_array_value( $colors, 'actions', '2ea2cc' ) ) );
		$text = new SP_Color( str_replace( '#', '', sp_array_value( $colors, 'text', 'ffffff' ) ) );
		?>
		<style type="text/css">
			#wpadminbar a.ab-item,
			#wpadminbar > #wp-toolbar span.ab-label,
			#wpadminbar > #wp-toolbar span.noticon {
				<?php if ( $text->isLight() ): ?>
					color: #<?php echo $text->darken(6.5); ?>;
				<?php else: ?>
					color: #<?php echo $text->lighten(7); ?>;
				<?php endif; ?>
			}

			#wpadminbar {
				color: #<?php echo $text->mix($base->getHex(), 54); ?>;
				background: #<?php echo $base->getHex(); ?>;
			}

			#wpadminbar .menupop .ab-sub-wrapper,
			#wpadminbar .shortlink-input {
				<?php if ( $base->isDark() ): ?>
					background: #<?php echo $base->lighten(7); ?>;
				<?php else: ?>
					background: #<?php echo $base->darken(6.5); ?>;
				<?php endif; ?>
			}

			#wpadminbar .ab-top-menu > li > .ab-item:focus,
			#wpadminbar.nojq .quicklinks .ab-top-menu > li > .ab-item:focus,
			#wpadminbar .ab-top-menu > li:hover > .ab-item,
			#wpadminbar .ab-top-menu > li.hover > .ab-item {
				<?php if ( $base->isDark() ): ?>
					background: #<?php echo $base->lighten(7); ?>;
				<?php else: ?>
					background: #<?php echo $base->darken(6.5); ?>;
				<?php endif; ?>
				color: #<?php echo $actions->getHex(); ?>;
			}

			#wpadminbar > #wp-toolbar li:hover span.ab-label,
			#wpadminbar > #wp-toolbar li.hover span.ab-label,
			#wpadminbar > #wp-toolbar a:focus span.ab-label {
				color: #<?php echo $actions->getHex(); ?>;
			}

			#wpadminbar .ab-icon:before,
			#wpadminbar .ab-item:before,
			#wpadminbar #adminbarsearch:before {
				color: #<?php echo $text->mix($base->getHex(), 8.3); ?>;
			}

			#wpadminbar .ab-submenu .ab-item,
			#wpadminbar .quicklinks .menupop ul li a,
			#wpadminbar .quicklinks .menupop ul li a strong,
			#wpadminbar .quicklinks .menupop.hover ul li a,
			#wpadminbar.nojs .quicklinks .menupop:hover ul li a {
				<?php if ( $text->isLight() ): ?>
					color: #<?php echo $text->darken(6.5); ?>;
				<?php else: ?>
					color: #<?php echo $text->lighten(7); ?>;
				<?php endif; ?>
			}

			#wpadminbar .quicklinks .menupop ul li a:hover,
			#wpadminbar .quicklinks .menupop ul li a:focus,
			#wpadminbar .quicklinks .menupop ul li a:hover strong,
			#wpadminbar .quicklinks .menupop ul li a:focus strong,
			#wpadminbar .quicklinks .menupop.hover ul li a:hover,
			#wpadminbar .quicklinks .menupop.hover ul li a:focus,
			#wpadminbar.nojs .quicklinks .menupop:hover ul li a:hover,
			#wpadminbar.nojs .quicklinks .menupop:hover ul li a:focus,
			#wpadminbar li:hover .ab-icon:before,
			#wpadminbar li:hover .ab-item:before,
			#wpadminbar li a:focus .ab-icon:before,
			#wpadminbar li .ab-item:focus:before,
			#wpadminbar li.hover .ab-icon:before,
			#wpadminbar li.hover .ab-item:before,
			#wpadminbar li:hover #adminbarsearch:before {
				color: #<?php echo $actions->getHex(); ?>;
			}

			#wpadminbar .quicklinks .menupop ul.ab-sub-secondary,
			#wpadminbar .quicklinks .menupop ul.ab-sub-secondary .ab-submenu {
				<?php if ( $base->isDark() ): ?>
					background: #<?php echo $base->lighten(16.1); ?>;
				<?php else: ?>
					background: #<?php echo $base->darken(15); ?>;
				<?php endif; ?>
			}

			#wpadminbar .quicklinks .menupop .ab-sub-secondary > li > a:hover,
			#wpadminbar .quicklinks .menupop .ab-sub-secondary > li .ab-item:focus a {
				color: #<?php echo $actions->getHex(); ?>;
			}

			#wpadminbar .quicklinks a span#ab-updates {
				<?php if ( $text->isLight() ): ?>
					background: #<?php echo $text->darken(6.5); ?>;
				<?php else: ?>
					background: #<?php echo $text->lighten(7); ?>;
				<?php endif; ?>
				<?php if ( $base->isDark() ): ?>
					color: #<?php echo $base->lighten(7); ?>;
				<?php else: ?>
					color: #<?php echo $base->darken(6.5); ?>;
				<?php endif; ?>
			}

			#wpadminbar #wp-admin-bar-user-info .username {
				color: #<?php echo $text->mix($base->getHex(), 8.3); ?>;
			}

			#wpadminbar .quicklinks li#wp-admin-bar-my-account.with-avatar > a img {
				<?php if ( $base->isDark() ): ?>
					border-color: #<?php echo $base->lighten(40); ?>;
				<?php else: ?>
					border-color: #<?php echo $base->darken(37); ?>;
				<?php endif; ?>
				<?php if ( $text->isLight() ): ?>
					background: #<?php echo $text->darken(6.5); ?>;
				<?php else: ?>
					background: #<?php echo $text->lighten(7); ?>;
				<?php endif; ?>
			}

			#wpadminbar .quicklinks li .blavatar {
				<?php if ( $text->isLight() ): ?>
					color: #<?php echo $text->darken(6.5); ?>;
				<?php else: ?>
					color: #<?php echo $text->lighten(7); ?>;
				<?php endif; ?>
			}

			#wpadminbar .quicklinks li a:hover .blavatar {
				color: #<?php echo $actions->getHex(); ?>;
			}

			#wpadminbar > #wp-toolbar > #wp-admin-bar-top-secondary > #wp-admin-bar-search #adminbarsearch input.adminbar-input {
				color: #<?php echo $text->mix($base->getHex(), 54); ?>;
			}

			#wpadminbar.ie8 > #wp-toolbar > #wp-admin-bar-top-secondary > #wp-admin-bar-search #adminbarsearch input.adminbar-input {
				<?php if ( $base->isDark() ): ?>
					background-color: #<?php echo $base->lighten(14.3); ?>;
				<?php else: ?>
					background-color: #<?php echo $base->darken(13); ?>;
				<?php endif; ?>
			}

			#wpadminbar .screen-reader-shortcut:focus {
				<?php if ( $text->isLight() ): ?>
					background: #<?php echo $text->darken(5.3); ?>;
				<?php else: ?>
					background: #<?php echo $text->lighten(6); ?>;
				<?php endif; ?>
				color: #<?php echo $highlight->getHex(); ?>;
			}

			.no-font-face #wpadminbar #wp-admin-bar-menu-toggle span.ab-icon:before {
				color: #<?php echo $text->getHex(); ?>;
			}

			.no-font-face #wpadminbar #wp-admin-bar-site-name a.ab-item {
				color: #<?php echo $text->getHex(); ?>;
			}

			@media screen and ( max-width: 782px ) {
				#wpadminbar #wp-admin-bar-user-info .display-name {
					<?php if ( $text->isLight() ): ?>
						color: #<?php echo $text->darken(6.5); ?>;
					<?php else: ?>
						color: #<?php echo $text->lighten(7); ?>;
					<?php endif; ?>
				}
			}

			#adminmenuback,
			#adminmenuwrap,
			#adminmenu {
				background-color: #<?php echo $base->getHex(); ?>;
			}

			#adminmenu .wp-submenu {
				<?php if ( $base->isDark() ): ?>
					background-color: #<?php echo $base->lighten(7); ?>;
				<?php else: ?>
					background-color: #<?php echo $base->darken(6.5); ?>;
				<?php endif; ?>
			}

			/* New Menu icons */

			.icon16:before {
				color: #<?php echo $text->mix($base->getHex(), 8.3); ?>;
			}

			#adminmenu a {
				<?php if ( $text->isLight() ): ?>
					color: #<?php echo $text->darken(6.5); ?>;
				<?php else: ?>
					color: #<?php echo $text->lighten(7); ?>;
				<?php endif; ?>
			}

			#adminmenu .wp-submenu a {
				color: #<?php echo $text->mix($base->getHex(), 39); ?>;
			}

			#adminmenu a:hover,
			#adminmenu li.menu-top > a:focus,
			#adminmenu .wp-submenu a:hover,
			#adminmenu .wp-submenu a:focus {
				color: #<?php echo $actions->getHex(); ?>;
			}

			#adminmenu .wp-has-current-submenu .wp-submenu,
			.no-js li.wp-has-current-submenu:hover .wp-submenu,
			#adminmenu a.wp-has-current-submenu:focus + .wp-submenu,
			#adminmenu .wp-has-current-submenu .wp-submenu.sub-open,
			#adminmenu .wp-has-current-submenu.opensub .wp-submenu {
				<?php if ( $base->isDark() ): ?>
					background-color: #<?php echo $base->lighten(7); ?>;
				<?php else: ?>
					background-color: #<?php echo $base->darken(6.5); ?>;
				<?php endif; ?>
			}

			/* ensure that wp-submenu's box shadow doesn't appear on top of the focused menu item's background. */
			#adminmenu li.menu-top:hover,
			#adminmenu li.opensub > a.menu-top,
			#adminmenu li > a.menu-top:focus {
				<?php if ( $base->isDark() ): ?>
					background-color: #<?php echo $base->darken(6.5); ?>;
				<?php else: ?>
					background-color: #<?php echo $base->lighten(7); ?>;
				<?php endif; ?>
			}

			#adminmenu li.wp-has-current-submenu a.wp-has-current-submenu,
			#adminmenu li.current a.menu-top,
			.folded #adminmenu li.wp-has-current-submenu,
			.folded #adminmenu li.current.menu-top,
			#adminmenu .wp-menu-arrow,
			#adminmenu .wp-has-current-submenu .wp-submenu .wp-submenu-head,
			#adminmenu .wp-menu-arrow div {
				background: #<?php echo $highlight->getHex(); ?>;
				color: #<?php echo $text->getHex(); ?>;
			}

			#adminmenu .wp-submenu li.current,
			#adminmenu .wp-submenu li.current a,
			#adminmenu .opensub .wp-submenu li.current a,
			#adminmenu a.wp-has-current-submenu:focus + .wp-submenu li.current a,
			#adminmenu .wp-submenu li.current a:hover,
			#adminmenu .wp-submenu li.current a:focus {
				color: #<?php echo $text->getHex(); ?>;
			}

			div.wp-menu-image:before {
				color: #<?php echo $text->mix($base->getHex(), 8.3); ?>;
			}

			#adminmenu div.wp-menu-image:before {
				color: #<?php echo $text->mix($base->getHex(), 8.3); ?>;
			}

			#adminmenu li.wp-has-current-submenu:hover div.wp-menu-image:before,
			#adminmenu .wp-has-current-submenu div.wp-menu-image:before,
			#adminmenu .current div.wp-menu-image:before,
			#adminmenu a.wp-has-current-submenu:hover div.wp-menu-image:before,
			#adminmenu a.current:hover div.wp-menu-image:before {
				color: #<?php echo $text->getHex(); ?>;
			}

			#adminmenu li:hover div.wp-menu-image:before {
				color: #<?php echo $actions->getHex(); ?>;
			}

			#adminmenu li.wp-has-submenu.wp-not-current-submenu.opensub:hover:after {
				<?php if ( $base->isDark() ): ?>
					border-right-color: #<?php echo $base->lighten(7); ?>;
				<?php else: ?>
					border-right-color: #<?php echo $base->darken(6.5); ?>;
				<?php endif; ?>
			}

			#adminmenu .wp-submenu .wp-submenu-head {
				color: #<?php echo $text->getHex(); ?>;
			}

			#adminmenu .awaiting-mod,
			#adminmenu .update-plugins,
			#sidemenu li a span.update-plugins,
			#adminmenu li.current a .awaiting-mod,
			#adminmenu  li a.wp-has-current-submenu .update-plugins {
				background-color: #<?php echo $notifications->getHex(); ?>;
				color: #<?php echo $text->getHex(); ?>;
			}

			#collapse-menu {
				color: #<?php echo $text->mix($base->getHex(), 23.5); ?>;
			}

			#collapse-menu:hover,
			#collapse-menu:hover #collapse-button div:after {
				color: #<?php echo $actions->getHex(); ?>;
			}

			@media screen and ( max-width: 782px ) {
				.wp-responsive-open #wpadminbar #wp-admin-bar-menu-toggle a {
					<?php if ( $base->isDark() ): ?>
						background: #<?php echo $base->lighten(7); ?>;
					<?php else: ?>
						background: #<?php echo $base->darken(6.5); ?>;
					<?php endif; ?>
				}
			}
		</style>
		<?php
	}
}

new SportsPress_Branding();
