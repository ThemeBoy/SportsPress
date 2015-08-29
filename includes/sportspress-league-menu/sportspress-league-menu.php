<?php
/*
Plugin Name: SportsPress League Menu
Plugin URI: http://tboy.co/pro
Description: Add a league menu to SportsPress.
Author: ThemeBoy
Author URI: http://themeboy.com
Version: 1.6
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_League_Menu' ) ) :

/**
 * Main SportsPress League Menu Class
 *
 * @class SportsPress_League_Menu
 * @version	1.6
 */
class SportsPress_League_Menu {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();

		// Hooks
		add_action( 'init', array( $this, 'init' ), 11 );

	    add_filter( 'sportspress_enqueue_styles', array( $this, 'add_styles' ) );
	    add_filter( 'sportspress_get_settings_pages', array( $this, 'add_settings_page' ) );
	    add_filter( 'sportspress_enable_header', '__return_true' );

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

		add_action( 'sportspress_header', array( $this, 'menu' ), 20 );
		add_action( 'wp_footer', array( $this, 'footer' ), 20 );
	}

	/**
	 * Define constants
	*/
	private function define_constants() {
		if ( !defined( 'SP_LEAGUE_MENU_VERSION' ) )
			define( 'SP_LEAGUE_MENU_VERSION', '1.6' );

		if ( !defined( 'SP_LEAGUE_MENU_URL' ) )
			define( 'SP_LEAGUE_MENU_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'SP_LEAGUE_MENU_DIR' ) )
			define( 'SP_LEAGUE_MENU_DIR', plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Init plugin when WordPress Initialises.
	 */
	public function init() {
	}

	/**
	 * Enqueue styles
	 */
	public function admin_enqueue_scripts() {
		global $wp_scripts;

		$screen = get_current_screen();

		if ( in_array( $screen->id, array( 'toplevel_page_sportspress' ) ) ) {
			wp_enqueue_style( 'sportspress-league-menu-admin', SP_LEAGUE_MENU_URL . 'css/admin.css', array(), SP_LEAGUE_MENU_VERSION );
			wp_enqueue_script( 'sportspress-league-menu-admin', SP_LEAGUE_MENU_URL . 'js/admin.js', array( 'jquery' ), SP_LEAGUE_MENU_VERSION );
			wp_enqueue_media();
			wp_enqueue_script( 'custom-header' );
		}
	}

	/**
	 * Add settings page
	 */
	public function add_settings_page( $settings = array() ) {
		$settings[] = include( 'includes/class-sp-settings-league-menu.php' );
		return $settings;
	}

	/**
	 * Add styles to SP frontend
	 */
	public function add_styles( $styles = array() ) {
		$styles['sportspress-league-menu'] = array(
			'src'     => str_replace( array( 'http:', 'https:' ), '', SP_LEAGUE_MENU_URL ) . 'css/sportspress-league-menu.css',
			'deps'    => 'sportspress-general',
			'version' => SP_LEAGUE_MENU_VERSION,
			'media'   => 'all'
		);
		return $styles;
	}

	public static function menu() {
		$teams = get_option( 'sportspress_league_menu_teams' );
		if ( is_array( $teams ) && empty( $teams ) )
			return;

		$title = get_option( 'sportspress_league_menu_title', null );

		$align = get_option( 'sportspress_league_menu_align', null );
		if ( ! $align ) {
			$align = 'default';
		}

		$height = (int) get_option( 'sportspress_league_menu_team_height', 32 );
		$width = (int) get_option( 'sportspress_league_menu_team_width', 32 );
		if ( $height > 32 || $width > 32 ) {
			$size = 'sportspress-fit-icon';
		} else {
			$size = 'sportspress-fit-mini';
		}

		$background_color = get_option( 'sportspress_league_menu_css_background', '#000000' );
		$text_color = get_option( 'sportspress_league_menu_css_text', '#ffffff' );

		$logo = get_option( 'sportspress_league_menu_logo', null );
		$logo_width = (int) get_option( 'sportspress_league_menu_logo_width', 64 );
		$logo_height = (int) get_option( 'sportspress_league_menu_logo_height', 32 );
		$logo_bottom = (int) get_option( 'sportspress_league_menu_logo_bottom', 0 );
		$logo_left = (int) get_option( 'sportspress_league_menu_logo_left', 0 );
		if ( $logo_height > 32 || $logo_width > 32 ) {
			$logo_size = 'sportspress-fit-icon';
		} elseif ( $logo_height <= 128 && $logo_width <= 128 ) {
			$logo_size = 'sportspress-fit-mini';
		} else {
			$logo_size = 'sportspress-fit-medium';
		}

		$orderby = get_option( 'sportspress_league_menu_teams_orderby', 'title' );
		$order = get_option( 'sportspress_league_menu_teams_order', 'ASC' );

		$limit = -1;

		$args = array(
			'post_type' => 'sp_team',
			'numberposts' => $limit,
			'posts_per_page' => $limit,
			'orderby' => $orderby,
			'order' => $order,
		);
		if ( is_array( $teams ) ) $args['include'] = $teams;
		$teams = get_posts( $args );

		if ( $teams || ! empty( $logo ) || $title ):
			?>
			<style type="text/css">
			.sp-league-menu {
				background: <?php echo $background_color; ?>;
				color: <?php echo $text_color; ?>;
			}
			.sp-league-menu .sp-league-menu-title {
				color: <?php echo $text_color; ?>;
			}
			.sp-league-menu .sp-team-logo {
				max-height: <?php echo $height; ?>px;
				max-width: <?php echo $width; ?>px;
			}
			.sp-league-menu .sp-league-menu-logo {
				max-height: <?php echo $logo_height; ?>px;
				max-width: <?php echo $logo_width; ?>px;
				margin-bottom: <?php echo $logo_bottom; ?>px;
				margin-left: <?php echo $logo_left; ?>px;
			}
			</style>
			<div class="sp-league-menu sp-align-<?php echo $align; ?>">
				<div class="sp-inner">
					<?php if ( ! empty( $logo ) ): ?>
						<span class="sp-league-menu-title">
							<?php echo wp_get_attachment_image( $logo, $logo_size, false, array( 'class' => 'sp-league-menu-logo', 'alt' => $title, 'title' => $title ) ); ?>
						</span>
					<?php elseif ( $title ): ?>
					<span class="sp-league-menu-title">
						<?php echo $title; ?>
					</span>
					<?php endif; ?>
					<?php if ( $teams ): foreach ( $teams as $team ): ?>
						<a class="sp-team-link" href="<?php echo get_post_permalink( $team->ID ); ?>">
							<?php
							echo get_the_post_thumbnail( $team->ID, $size, array(
								'title' => $team->post_title,
								'class' => 'sp-team-logo',
							) );
							?>
						</a>
					<?php endforeach; endif; ?>
					<?php do_action( 'sportspress_league_menu' ); ?>
				</div>
			</div>
			<?php
		endif;
	}

	public static function menu_scripts() {
		?>
			<script type="text/javascript">
			jQuery(document).ready( function($) {
				$('.sp-header-loaded').prepend( $('.sp-league-menu') );
			} );
			</script>
		<?php
	}

	public static function footer() {
		if ( did_action( 'sportspress_header' ) ) return;
		self::menu();
		self::menu_scripts();
	}
}

endif;

if ( get_option( 'sportspress_load_league_menu_module', 'yes' ) == 'yes' ) {
	new SportsPress_League_Menu();
}
