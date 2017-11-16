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
 * @version   2.5
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
		if ( empty( $_GET['page'] ) ) {
			return;
		}

		$welcome_page_name  = __( 'About SportsPress', 'sportspress' );
		$welcome_page_title = __( 'Welcome to SportsPress', 'sportspress' );

		switch ( $_GET['page'] ) {
			case 'sp-about' :
				$page = add_dashboard_page( $welcome_page_title, $welcome_page_name, 'manage_options', 'sp-about', array( $this, 'about_screen' ) );
				add_action( 'admin_print_styles-'. $page, array( $this, 'admin_css' ) );
			break;
			case 'sp-credits' :
				$page = add_dashboard_page( $welcome_page_title, $welcome_page_name, 'manage_options', 'sp-credits', array( $this, 'credits_screen' ) );
				add_action( 'admin_print_styles-'. $page, array( $this, 'admin_css' ) );
			break;
			case 'sp-translators' :
				$page = add_dashboard_page( $welcome_page_title, $welcome_page_name, 'manage_options', 'sp-translators', array( $this, 'translators_screen' ) );
				add_action( 'admin_print_styles-'. $page, array( $this, 'admin_css' ) );
			break;
		}
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
		remove_submenu_page( 'index.php', 'sp-credits' );
		remove_submenu_page( 'index.php', 'sp-translators' );
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

		// Get major version number
		$version = explode( '.', SP()->version, 3 );
		unset( $version[2] );
		$display_version = implode( '.', $version );
		?>
		<h1 class="sp-welcome-logo"><?php echo apply_filters( 'sportspress_logo', '<img src="' . plugin_dir_url( SP_PLUGIN_FILE ) . 'assets/images/welcome/sportspress' . ( class_exists( 'SportsPress_Pro' ) ? '-pro' : '' ) . '.png" alt="' . __( 'SportsPress', 'sportspress' ) . '">' ); ?></h1>

		<div class="sp-badge"><?php printf( __( 'Version %s', 'sportspress' ), SP()->version ); ?></div>

		<div class="about-text sp-about-text">
			<?php
				if ( ! empty( $_GET['sp-installed'] ) )
					$message = __( 'Thanks, all done!', 'sportspress' );
				elseif ( ! empty( $_GET['sp-updated'] ) )
					$message = __( 'Thank you for updating to the latest version!', 'sportspress' );
				else
					$message = __( 'Thanks for installing!', 'sportspress' );

				printf( __( '%s SportsPress %s has lots of refinements we think you&#8217;ll love.', 'sportspress' ), $message, $display_version );
			?>
		</div>

		<p class="sportspress-actions">
			<a href="<?php echo admin_url( add_query_arg( array( 'page' => 'sportspress', 'tab' => 'general' ), 'admin.php' ) ); ?>" class="button button-primary"><?php _e( 'Settings', 'sportspress' ); ?></a>
			<a href="<?php echo esc_url( apply_filters( 'sportspress_docs_url', 'http://tboy.co/docs', 'sportspress' ) ); ?>" class="docs button button-primary"><?php _e( 'Docs', 'sportspress' ); ?></a>
			<a href="https://twitter.com/share" class="twitter-share-button" data-url="http://wordpress.org/plugins/sportspress" data-text="An open-source (free) #WordPress plugin that helps you build professional league websites" data-via="ThemeBoy" data-size="large" data-hashtags="SportsPress">Tweet</a>
			<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
		</p>

		<h2 class="nav-tab-wrapper">
			<a class="nav-tab <?php if ( $_GET['page'] == 'sp-about' ) echo 'nav-tab-active'; ?>" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'sp-about' ), 'index.php' ) ) ); ?>">
				<?php _e( 'Welcome', 'sportspress' ); ?>
			</a><a class="nav-tab <?php if ( $_GET['page'] == 'sp-credits' ) echo 'nav-tab-active'; ?>" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'sp-credits' ), 'index.php' ) ) ); ?>">
				<?php _e( 'Credits', 'sportspress' ); ?>
			</a><a class="nav-tab <?php if ( $_GET['page'] == 'sp-translators' ) echo 'nav-tab-active'; ?>" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'sp-translators' ), 'index.php' ) ) ); ?>">
				<?php _e( 'Translators', 'sportspress' ); ?>
			</a>
		</h2>
		<?php
	}

	/**
	 * Output the about screen.
	 */
	public function about_screen() {
		include_once( 'class-sp-admin-settings.php' );
		$class = 'chosen-select' . ( is_rtl() ? ' chosen-rtl' : '' );
		?>
		<div class="wrap about-wrap about-sportspress-wrap">

			<?php $this->intro(); ?>

			<?php
			// Save settings
	    	if ( isset( $_POST['timezone_string'] ) ):
	    		update_option( 'timezone_string', $_POST['timezone_string'] );
				update_option( 'sportspress_basic_setup', 1 );
	    	endif;
			if ( isset( $_POST['sportspress_sport'] ) && ! empty( $_POST['sportspress_sport'] ) ):
				$sport = $_POST['sportspress_sport'];
				SP_Admin_Sports::apply_preset( $sport );
				update_option( 'sportspress_sport', $_POST['sportspress_sport'] );
    			delete_option( '_sp_needs_welcome' );
	    		update_option( 'sportspress_installed', 1 );
				?>
				<div id="message" class="updated sportspress-message">
					<p><strong><?php _e( 'Your settings have been saved.', 'sportspress' ); ?></strong></p>
				</div>
			<?php
			endif;
			if ( isset( $_POST['add_sample_data'] ) ):
				SP_Admin_Sample_Data::delete_posts();
				SP_Admin_Sample_Data::insert_posts();
			endif;

			do_action( 'sportspress_before_welcome_features' );
			?>
			<div class="feature-section one-col">
				<div class="col">
					<h2>New Features 🌟</h2>
				</div>
			</div>

			<div class="feature-section two-col">
				<div class="col">
					<img src="<?php echo plugin_dir_url( SP_PLUGIN_FILE ); ?>assets/images/welcome/screenshot-officials.png" alt="Officials Module">
					<h3>Officials Module</h3>
					<p>Manage referees, umpires, judges, timekeepers, and other officials. Don't forget to enable the <strong>Officials</strong> module from <a href="<?php echo add_query_arg( array( 'page' => 'sportspress' ), admin_url( 'admin.php' ) ); ?>">SportsPress Settings</a> to start using this feature.</p>
				</div>
				<div class="col">
					<img src="<?php echo plugin_dir_url( SP_PLUGIN_FILE ); ?>assets/images/welcome/screenshot-awards.png" alt="Player of the Match">
					<h3>Player of the Match</h3>
					<p>Select player(s) or a star number for certain players in events. Stars can be displayed as icons or with a number. To enable this feature, choose an <strong>Awards</strong> type from the <strong>Box Score</strong> section in <a href="<?php echo add_query_arg( array( 'page' => 'sportspress' ), admin_url( 'admin.php' ) ); ?>">Event Settings</a>.</p>
				</div>
			</div>

			<div class="feature-section two-col">
				<div class="col">
					<img src="<?php echo plugin_dir_url( SP_PLUGIN_FILE ); ?>assets/images/welcome/screenshot-event-performance-importer.png" alt="Box Score Importer">
					<h3>Box Score Importer</h3>
					<p>A new importer has been added to each event. Upload a CSV file and instantly update the player performance for the selected team.</p>
				</div>
				<div class="col">
					<img src="<?php echo plugin_dir_url( SP_PLUGIN_FILE ); ?>assets/images/welcome/screenshot-performance-icons.png" alt="New Performance Icons">
					<h3>New Performance Icons</h3>
					<p>A set of new icons have been added to <strong>Player Performance</strong>, giving you more options to choose from.<p>
				</div>
			</div>

			<?php if ( ! class_exists( 'SportsPress_Pro' ) ) { ?>
			<hr>

			<div class="feature-section one-col">
				<div class="col">
					<h2>SportsPress Pro Updates 🏆</h2>
				</div>
			</div>

			<div class="feature-section two-col">
				<div class="col">
					<img src="<?php echo plugin_dir_url( SP_PLUGIN_FILE ); ?>assets/images/welcome/screenshot-group-stages.png" alt="Tournament Group Stages">
					<h3>Tournament Group Stages</h3>
					<p>Support for group stages have been added natively to tournaments, allowing you to create and attach league tables to be displayed directly below tournament brackets.</p>
				</div>
				<div class="col">
					<img src="<?php echo plugin_dir_url( SP_PLUGIN_FILE ); ?>assets/images/welcome/screenshot-sponsor-levels.png" alt="Sponsorship Levels">
					<h3>Sponsorship Levels</h3>
					<p>Assign levels to sponsors based on their contribution or other factors. Sponsors can now be filtered based on their sponsorship level within widgets, allowing for level-specific sponsor treament.<p>
				</div>
			</div>

			<a class="button button-primary button-hero" href="<?php echo esc_url( apply_filters( 'sportspress_pro_url', 'http://tboy.co/pro' ) ); ?>"><?php _e( 'Upgrade to Pro', 'sportspress' ); ?></a>

			<p><?php _e( 'Get SportsPress Pro to get access to all modules. You can upgrade any time without losing any of your data.','sportspress' ); ?></p>
			<?php } ?>

			<hr>

			<div class="feature-section one-col">
				<div class="col">
					<h2>Improved Usability and Control</h2>
				</div>
			</div>

			<div class="feature-section three-col">
				<div class="col">
					<h3>Custom Taxonomy Order</h3>
					<p>A new setting has been added to leagues, seasons, and positions, allowing you to define a customer order of terms.</p>
				</div>
				<div class="col">
					<h3>Filtering Player Lists</h3>
					<p>Player lists can now be configured to skip players that don't have a particular statistic, allowing you to create more specific lists.</p>
				</div>
				<div class="col">
					<h3>Multiple Jobs</h3>
					<p>Staff members can now be assigned multiple jobs, which are displayed consecutively in their profiles.</p>
				</div>
				<div class="col">
					<h3>Event Calendar Indicators</h3>
					<p>With the frontend stylesheet enabled, days with events will now be displayed with a filled circle to indicate an event on that day.</p>
				</div>
				<div class="col">
					<h3>Relative Ranges</h3>
					<p>Additional options now allow you to select events within a particular range, from last week, next week, yesterday, or tomorrow.</p>
				</div>
				<div class="col">
					<h3>Sort by Match Day</h3>
					<p>Use the columns in the admin to sort events by match day. A search bar has also been added to filter events by match day.</p>
				</div>
			</div>

			<hr>

			<div class="feature-section one-col">
				<div class="col">
					<h2>Other Notes</h2>
				</div>
			</div>

			<div class="feature-section three-col">
				<div class="col">
					<h3>Schema Microdata</h3>
					<p>We've complied with schema.org's microdata formats by adding these attributes to event calendars, blocks, and lists throughout.</p>
				</div>
				<div class="col">
					<h3>Competitions Are Now Leagues</h3>
					<p>The competitions taxonomy has been renamed to leagues, which still work in the exact same way.</p>
				</div>
				<div class="col">
					<h3>Calendar Caching</h3>
					<p>To prevent event calendars from caching in third-party apps, an E-Tag header has been added to these feeds.</p>
				</div>
			</div>

			<?php do_action( 'sportspress_after_welcome_features' ); ?>

			<a href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'sportspress', 'tab' => 'general' ), 'admin.php' ) ) ); ?>"><?php _e( 'Go to SportsPress Settings', 'sportspress' ); ?></a>
		</div>
		<?php
	}

	/**
	 * Output the credits.
	 */
	public function credits_screen() {
		?>
		<div class="wrap about-wrap about-sportspress-wrap">
			<?php $this->intro(); ?>
			
			<p class="about-description"><?php printf( __( 'SportsPress is developed and maintained by a worldwide team of passionate individuals and backed by an awesome developer community. Want to see your name? <a href="%s">Contribute to SportsPress</a>.', 'sportspress' ), 'https://github.com/ThemeBoy/SportsPress/blob/master/CONTRIBUTING.md' ); ?></p>

			<div class="sp-feature feature-section col one-col">
				<?php echo $this->contributors(); ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Output the translators screen
	 */
	public function translators_screen() {
		?>
		<div class="wrap about-wrap about-sportspress-wrap">

			<?php $this->intro(); ?>

			<p class="about-description"><?php printf( __( 'SportsPress has been kindly translated into several other languages thanks to our translation team. Want to see your name? <a href="%s">Translate SportsPress</a>.', 'sportspress' ), 'https://translate.wordpress.org/projects/wp-plugins/sportspress' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Render Contributors List
	 *
	 * @access public
	 * @return string $contributor_list HTML formatted list of contributors.
	 */
	public function contributors() {
		$contributors = $this->get_contributors();

		if ( empty( $contributors ) ) {
			return '';
		}

		$contributor_list = '<ul class="wp-people-group">';

		foreach ( $contributors as $contributor ) {
			$contributor_list .= '<li class="wp-person">';
			$contributor_list .= sprintf( '<a href="%s" title="%s">',
				esc_url( 'https://github.com/' . $contributor->login ),
				esc_html( sprintf( __( 'View %s', 'sportspress' ), $contributor->login ) )
			);
			$contributor_list .= sprintf( '<img src="%s" width="64" height="64" class="gravatar" alt="%s" />', esc_url( $contributor->avatar_url ), esc_html( $contributor->login ) );
			$contributor_list .= '</a>';
			$contributor_list .= sprintf( '<a class="web" href="%s">%s</a>', esc_url( 'https://github.com/' . $contributor->login ), esc_html( $contributor->login ) );
			$contributor_list .= '</a>';
			$contributor_list .= '</li>';
		}

		$contributor_list .= '</ul>';

		return $contributor_list;
	}

	/**
	 * Retrieve list of contributors from GitHub.
	 *
	 * @access public
	 * @return mixed
	 */
	public function get_contributors() {
		$contributors = get_transient( 'sportspress_contributors' );

		if ( false !== $contributors ) {
			return $contributors;
		}

		$response = wp_remote_get( 'https://api.github.com/repos/ThemeBoy/SportsPress/contributors', array( 'sslverify' => false ) );

		if ( is_wp_error( $response ) || 200 != wp_remote_retrieve_response_code( $response ) ) {
			return array();
		}

		$contributors = json_decode( wp_remote_retrieve_body( $response ) );

		if ( ! is_array( $contributors ) ) {
			return array();
		}

		set_transient( 'sportspress_contributors', $contributors, HOUR_IN_SECONDS );

		return $contributors;
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

		if ( ! get_option( 'sportspress_completed_setup' ) ) {
			wp_redirect( admin_url( 'admin.php?page=sp-setup' ) );
			exit;
		}

		wp_redirect( admin_url( 'index.php?page=sp-about' ) );
		exit;
	}
}

new SP_Admin_Welcome();
