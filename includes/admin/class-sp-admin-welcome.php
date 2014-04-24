<?php
/**
 * Welcome Page Class
 *
 * Shows a feature overview for the new version (major) and credits.
 *
 * Adapted from code in EDD (Copyright (c) 2012, Pippin Williamson) and WP.
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin
 * @version     0.7.5
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * SP_Admin_Welcome class.
 */
class SP_Admin_Welcome {

	private $plugin;

	/**
	 * __construct function.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {
		$this->plugin             = 'sportspress/sportspress.php';

		add_action( 'admin_menu', array( $this, 'admin_menus') );
		add_action( 'admin_head', array( $this, 'admin_head' ) );
		add_action( 'admin_init', array( $this, 'welcome'    ) );
	}

	/**
	 * Add admin menus/screens
	 *
	 * @access public
	 * @return void
	 */
	public function admin_menus() {
		$welcome_page_title = __( 'Welcome to SportsPress', 'sportspress' );

		// About
		$about = add_dashboard_page( $welcome_page_title, $welcome_page_title, 'manage_options', 'sp-about', array( $this, 'about_screen' ) );

		add_action( 'admin_print_styles-'. $about, array( $this, 'admin_css' ) );
	}

	/**
	 * admin_css function.
	 *
	 * @access public
	 * @return void
	 */
	public function admin_css() {
		wp_enqueue_style( 'sportspress-activation', plugins_url(  '/assets/css/activation.css', SP_PLUGIN_FILE ), array(), SP_VERSION );
	}

	/**
	 * Add styles just for this page, and remove dashboard page links.
	 *
	 * @access public
	 * @return void
	 */
	public function admin_head() {
		remove_submenu_page( 'index.php', 'sp-about' );
		remove_submenu_page( 'index.php', 'sp-translators' );

		// Badge for welcome page
		$badge_url = SP()->plugin_url() . '/assets/images/welcome/sp-badge.png';
		?>
		<style type="text/css">
			/*<![CDATA[*/
			.sp-badge {
				position: relative;;
				background: #2f4265 url(<?php echo $badge_url; ?>) no-repeat center top;
				text-rendering: optimizeLegibility;
				padding-top: 160px;
				height: 42px;
				width: 165px;
				font-size: 14px;
				text-align: center;
				color: #e6e7e8;
				margin: 5px 0 0 0;
				-webkit-box-shadow: 0 1px 3px rgba(0,0,0,.2);
				box-shadow: 0 1px 3px rgba(0,0,0,.2);
			}
			.about-wrap .sp-badge {
				position: absolute;
				top: 0;
				right: 0;
			}
			.about-wrap .sp-feature {
				overflow: visible !important;
				*zoom:1;
			}
			.about-wrap .sp-feature:before,
			.about-wrap .sp-feature:after {
				content: " ";
				display: table;
			}
			.about-wrap .sp-feature:after {
				clear: both;
			}
			.about-wrap .feature-rest div {
				width: 50% !important;
				padding-right: 100px;
				-moz-box-sizing: border-box;
				box-sizing: border-box;
				margin: 0 !important;
			}
			.about-wrap .feature-rest div.last-feature {
				padding-left: 100px;
				padding-right: 0;
			}
			.about-integrations {
				background: #fff;
				margin: 20px 0;
				padding: 1px 20px 10px;
			}
			/*]]>*/
		</style>
		<?php
	}

	/**
	 * Into text/links shown on all about pages.
	 *
	 * @access private
	 * @return void
	 */
	private function intro() {

		// Flush after upgrades
		if ( ! empty( $_GET['sp-updated'] ) || ! empty( $_GET['sp-installed'] ) )
			flush_rewrite_rules();

		// Drop minor version if 0
		$major_version = substr( SP()->version, 0, 3 );
		?>
		<h1><?php printf( __( 'Welcome to SportsPress!', 'sportspress' ), $major_version ); ?></h1>

		<div class="about-text sportspress-about-text">
			<?php
				if ( ! empty( $_GET['sp-installed'] ) )
					$message = __( 'Thanks, all done!', 'sportspress' );
				elseif ( ! empty( $_GET['sp-updated'] ) )
					$message = __( 'Thank you for updating to the latest version!', 'sportspress' );
				else
					$message = __( 'Thanks for installing!', 'sportspress' );

				printf( __( '%s SportsPress %s has lots of refinements we think you&#8217;ll love.', 'sportspress' ), $message, $major_version );
			?>
		</div>

		<div class="sp-badge"><?php printf( __( 'Version %s', 'sportspress' ), SP()->version ); ?></div>

		<p class="sportspress-actions">
			<?php if ( false ): ?><a href="<?php echo admin_url( add_query_arg( array( 'page' => 'sportspress' ), 'admin.php' ) ); ?>" class="button button-primary"><?php _e( 'Settings', 'sportspress' ); ?></a><?php endif; ?>
			<a href="https://twitter.com/share" class="twitter-share-button" data-url="http://ow.ly/vaGUv" data-text="An open-source (free) #WordPress plugin that helps you build professional league websites." data-via="ThemeBoy" data-size="large" data-hashtags="SportsPress">Tweet</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
		</p>

		<?php if ( false ): ?>
		<h2 class="nav-tab-wrapper">
			<a class="nav-tab <?php if ( $_GET['page'] == 'sp-about' ) echo 'nav-tab-active'; ?>" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'sp-about' ), 'index.php' ) ) ); ?>">
				<?php _e( 'Get Started', 'sportspress' ); ?>
			</a><a class="nav-tab <?php if ( $_GET['page'] == 'sp-translators' ) echo 'nav-tab-active'; ?>" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'sp-translators' ), 'index.php' ) ) ); ?>">
				<?php _e( 'Translators', 'sportspress' ); ?>
			</a>
		</h2>
		<?php
		endif;
	}

	/**
	 * Output the about screen.
	 */
	public function about_screen() {
		include_once( 'class-sp-admin-settings.php' );
		?>
		<div class="wrap about-wrap about-sportspress-wrap">

			<?php $this->intro(); ?>

			<!--<div class="changelog point-releases"></div>-->

			<div class="changelog">
				<h3><?php _e( 'Get Started', 'sportspress' ); ?></h3>
			
			<?php
			// Save settings
			if ( isset( $_POST['sportspress_sport'] ) && ! empty( $_POST['sportspress_sport'] ) && get_option( 'sportspress_sport', null ) != $_POST['sportspress_sport'] ):
				$sport = SP()->sports->$_POST['sportspress_sport'];
				SP_Admin_Settings::configure_sport( $sport );
				update_option( 'sportspress_sport', $_POST['sportspress_sport'] );
	    	endif;
	    	if ( isset( $_POST['sportspress_default_country'] ) ):
	    		update_option( 'sportspress_default_country', $_POST['sportspress_default_country'] );
	    		update_option( '_sportspress_needs_welcome', 1 );
			?>
				<div id="message" class="updated sportspress-message">
					<p><strong><?php _e( 'Your settings have been saved.', 'sportspress' ); ?></strong></p>
				</div>
			<?php endif; ?>
				<div class="sp-feature feature-section col three-col">
					<div>
						<form method="post" id="mainform" action="" enctype="multipart/form-data">
							<h4><?php _e( 'Base Location', 'sportspress' ); ?></h4>
							<?php
							$selected = (string) get_option( 'sportspress_default_country', 'AU' );
							$continents = SP()->countries->continents;
					    	?>
					    	<p>
								<select name="sportspress_default_country" data-placeholder="<?php _e( 'Choose a country&hellip;', 'sportspress' ); ?>" title="Country" class="chosen-select<?php if ( is_rtl() ): ?> chosen-rtl<?php endif; ?>">
					        		<?php SP()->countries->country_dropdown_options( $selected ); ?>
					        	</select>
					        </p>
							<h4><?php printf( __( 'Select %s', 'sportspress' ), __( 'Sport', 'sportspress' ) ); ?></h4>
							<?php
							$sport_options = sp_get_sport_options();
							$class = 'chosen-select' . ( is_rtl() ? ' chosen-rtl' : '' );
							$settings = array( array(
								'id'        => 'sportspress_sport',
								'default'   => 'soccer',
								'type'      => 'select',
								'class' 	=> $class,
								'options'   => $sport_options,
							));
							SP_Admin_Settings::output_fields( $settings );
							?>
					        <p class="submit sportspress-actions">
					        	<input name="save" class="button-primary" type="submit" value="<?php _e( 'Save changes', 'sportspress' ); ?>" />
					        	<input type="hidden" name="subtab" id="last_tab" />
					        	<?php wp_nonce_field( 'sportspress-settings' ); ?>
					        </p>
						</form>
					</div>
					<div>
						<h4><?php _e( 'Next Steps', 'sportspress' ); ?></h4>
						<ul class="sportspress-steps">
							<li><a href="<?php echo esc_url( admin_url( add_query_arg( array( 'post_type' => 'sp_team' ), 'post-new.php' ) ) ); ?>" class="welcome-icon welcome-add-team"><?php _e( 'Add New Team', 'sportspress' ); ?></a></li>
							<li><a href="<?php echo esc_url( admin_url( add_query_arg( array( 'post_type' => 'sp_player' ), 'post-new.php' ) ) ); ?>" class="welcome-icon welcome-add-player"><?php _e( 'Add New Player', 'sportspress' ); ?></a></li>
							<li><a href="<?php echo esc_url( admin_url( add_query_arg( array( 'post_type' => 'sp_event' ), 'post-new.php' ) ) ); ?>" class="welcome-icon welcome-add-event"><?php _e( 'Add New Event', 'sportspress' ); ?></a></li>
						</ul>
					</div>
					<div class="last-feature">
						<h4><?php _e( 'Translators', 'sportspress' ); ?></h4>
						<p><?php _e( 'SportsPress has been kindly translated into several other languages thanks to our translation team. Want to see your name? <a href="https://www.transifex.com/projects/p/sportspress/">Translate SportsPress</a>.', 'sportspress' ); ?></p>
						<?php
						$translator_handles = array( 'Abdulelah', 'albertone', 'aylaview', 'Bhelpful2', 'bizover', 'i__k', 'JensZ', 'karimjarro', 'rochester', 'Selskei', 'Spirossmil', 'ThemeBoy' );
						$translator_links = array();
						foreach ( $translator_handles as $handle ):
							$translator_links[] = '<a href="https://www.transifex.com/accounts/profile/' . $handle . '">' . $handle . '</a>';
						endforeach;
						?>
						<p class="wp-credits-list">
							<?php echo implode( ', ', $translator_links ); ?>
						</p>
					</div>
				</div>
			</div>

			<div class="return-to-dashboard">
				<a href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'sportspress' ), 'admin.php' ) ) ); ?>"><?php _e( 'Go to SportsPress Settings', 'sportspress' ); ?></a>
			</div>
		</div>
		<?php
	}

	/**
	 * Sends user to the welcome page on first activation
	 */
	public function welcome() {

		// Bail if no activation redirect transient is set
	    if ( ! get_transient( '_sp_activation_redirect' ) )
			return;

		// Delete the redirect transient
		delete_transient( '_sp_activation_redirect' );

		// Bail if we are waiting to install or update via the interface update/install links
		if ( get_option( '_sp_needs_update' ) == 1 || get_option( '_sp_needs_pages' ) == 1 )
			return;

		// Bail if activating from network, or bulk, or within an iFrame
		if ( is_network_admin() || isset( $_GET['activate-multi'] ) || defined( 'IFRAME_REQUEST' ) )
			return;

		if ( ( isset( $_GET['action'] ) && 'upgrade-plugin' == $_GET['action'] ) && ( isset( $_GET['plugin'] ) && strstr( $_GET['plugin'], 'sportspress.php' ) ) )
			return;

		wp_redirect( admin_url( 'index.php?page=sp-about' ) );
		exit;
	}
}

new SP_Admin_Welcome();
