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
 * @version     1.9.12
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

		// Drop minor version if 0
		$major_version = substr( SP()->version, 0, 3 );
		?>
		<h2 class="sp-welcome-logo"><?php echo apply_filters( 'sportspress_logo', '<img src="' . plugin_dir_url( SP_PLUGIN_FILE ) . 'assets/images/welcome/sportspress' . ( class_exists( 'SportsPress_Pro' ) ? '-pro' : '' ) . '.png" alt="' . __( 'SportsPress', 'sportspress' ) . '">' ); ?></h2>

		<div class="sp-badge"><?php printf( __( 'Version %s', 'sportspress' ), SP()->version ); ?></div>

		<div class="about-text sp-about-text">
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

		<p class="sportspress-actions">
			<a href="<?php echo admin_url( add_query_arg( array( 'page' => 'sportspress', 'tab' => 'general' ), 'admin.php' ) ); ?>" class="button button-primary"><?php _e( 'Settings', 'sportspress' ); ?></a>
			<a href="<?php echo esc_url( apply_filters( 'sportspress_docs_url', 'http://tboy.co/docs', 'sportspress' ) ); ?>" class="docs button button-primary"><?php _e( 'Docs', 'sportspress' ); ?></a>
			<a href="https://twitter.com/share" class="twitter-share-button" data-url="http://wordpress.org/plugins/sportspress" data-text="An open-source (free) #WordPress plugin that helps you build professional league websites." data-via="ThemeBoy" data-size="large" data-hashtags="SportsPress">Tweet</a>
			<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
		</p>

		<h2 class="nav-tab-wrapper">
			<a class="nav-tab <?php if ( $_GET['page'] == 'sp-about' ) echo 'nav-tab-active'; ?>" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'sp-about' ), 'index.php' ) ) ); ?>">
				<?php _e( 'Get Started', 'sportspress' ); ?>
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
	    		update_option( '_sportspress_needs_welcome', 1 );
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
			?>
			<div class="sp-feature feature-section col two-col">
				<div>
					<?php if ( get_option( 'sportspress_basic_setup' ) ) { ?>
						<h4><?php _e( 'Sport', 'sportspress' ); ?></h4>
						<?php
						$sport = get_option( 'sportspress_sport' );
						$sport_options = SP_Admin_Sports::get_preset_options();
						foreach ( $sport_options as $options ):
							foreach ( $options as $slug => $name ):
								if ( $sport === $slug ):
									$sport = $name;
									break;
								endif;
							endforeach;
						endforeach;
						echo $sport;
						?>
						<a href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'sportspress', 'tab' => 'general' ), 'admin.php' ) ) ); ?>"><i class="dashicons dashicons-edit"></i> <?php _e( 'Change', 'sportspress' ); ?></a>

						<h4><?php _e( 'Next Steps', 'sportspress' ); ?></h4>
						<p><?php _e( 'We&#8217;ve assembled some links to get you started:', 'sportspress' ); ?></p>
						<?php
						$steps = apply_filters( 'sportspress_next_steps', array(
							'teams' => array(
								'link' => admin_url( add_query_arg( array( 'post_type' => 'sp_team' ), 'edit.php' ) ),
								'icon' => 'sp-icon-shield',
								'label' => __( 'Add New Team', 'sportspress' ),
							),
							'players' => array(
								'link' => admin_url( add_query_arg( array( 'post_type' => 'sp_player' ), 'edit.php' ) ),
								'icon' => 'sp-icon-tshirt',
								'label' => __( 'Add New Player', 'sportspress' ),
							),
							'events' => array(
								'link' => admin_url( add_query_arg( array( 'post_type' => 'sp_event' ), 'edit.php' ) ),
								'icon' => 'sp-icon-calendar',
								'label' => __( 'Add New Event', 'sportspress' ),
							),
						) );
						?>
						<?php if ( sizeof ( $steps ) ) { ?>
						<div class="sportspress-steps">
							<ul>
								<?php foreach ( $steps as $step ) { ?>
									<li><a href="<?php echo esc_url( $step['link'] ); ?>" class="welcome-icon sp-welcome-icon"><i class="<?php echo sp_array_value( $step, 'icon' ); ?>"></i> <?php echo $step['label']; ?></a></li>
								<?php } ?>
							</ul>
						</div>
						<?php } ?>

						<h4><?php _e( 'Settings', 'sportspress' ); ?></h4>
						<a href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'sportspress', 'tab' => 'general' ), 'admin.php' ) ) ); ?>"><?php _e( 'Go to SportsPress Settings', 'sportspress' ); ?></a>
					<?php } else { ?>
						<form method="post" id="mainform" action="" enctype="multipart/form-data">
							<h4><?php _e( 'Basic Setup', 'sportspress' ); ?></h4>
							<p><?php _e( 'Select your timezone and sport to get started.', 'sportspress' ); ?></p>
							<table class="form-table">
								<tbody>
									<tr valign="top">
										<th scope="row" class="titledesc">
											<label for="timezone_string"><?php _e( 'Timezone', 'sportspress' ); ?> <i class="dashicons dashicons-editor-help sp-desc-tip" title="<?php _e( 'Choose a city in the same timezone as you.', 'sportspress' ); ?>"></i></label>
										</th>
										<td>
											<select id="timezone_string" name="timezone_string" class="<?php echo $class; ?>">
												<?php
												$current_offset = get_option('gmt_offset');
												$tzstring = get_option('timezone_string');

												$check_zone_info = true;

												// Remove old Etc mappings. Fallback to gmt_offset.
												if ( false !== strpos($tzstring,'Etc/GMT') )
													$tzstring = '';

												if ( empty($tzstring) ) { // Create a UTC+- zone if no timezone string exists
													$check_zone_info = false;
													if ( 0 == $current_offset )
														$tzstring = 'UTC+0';
													elseif ($current_offset < 0)
														$tzstring = 'UTC' . $current_offset;
													else
														$tzstring = 'UTC+' . $current_offset;
												}
												echo wp_timezone_choice($tzstring);
												?>
											</select>
										</td>
									</tr>
									<?php
									$sport_options = SP_Admin_Sports::get_preset_options();
									$settings = array(
										array(
											'id'        => 'sportspress_sport',
											'default'   => 'custom',
											'type'      => 'sport',
											'title'		=> __( 'Sport', 'sportspress' ),
											'welcome' 	=> true,
											'class' 	=> $class,
											'options'   => $sport_options,
										),
									);
									SP_Admin_Settings::output_fields( $settings );
									?>
								</tbody>
							</table>
					        <p class="submit sportspress-actions">
					        	<input name="save" class="button-primary" type="submit" value="<?php _e( 'Save Changes', 'sportspress' ); ?>" />
					        	<input type="hidden" name="subtab" id="last_tab" />
					        	<?php wp_nonce_field( 'sportspress-settings' ); ?>
					        </p>
						</form>
					<?php } ?>
				</div>
				<?php if ( current_user_can( 'install_themes' ) && ! current_theme_supports( 'sportspress' ) ) { ?>
					<div class="last-feature">
						<h4><?php _e( 'Free SportsPress Theme', 'sportspress' ); ?></h4>
						<a href="<?php echo add_query_arg( array( 'theme' => 'rookie' ), network_admin_url( 'theme-install.php' ) ); ?>" class="sp-theme-screenshot"><img src="<?php echo plugin_dir_url( SP_PLUGIN_FILE ); ?>/assets/images/modules/rookie.png"></a>
						<p><?php _e( 'Have you tried the free Rookie theme yet?', 'sportspress' ); ?></p>
						<p><?php _e( 'Rookie is a free starter theme for SportsPress designed by ThemeBoy.', 'sportspress' ); ?></p>
						<p class="sp-module-actions">
							<a class="button button-large" href="<?php echo add_query_arg( array( 'theme' => 'rookie' ), network_admin_url( 'theme-install.php' ) ); ?>"><?php _e( 'Install Now', 'sportspress' ); ?></a>
						</p>
					</div>
				<?php } ?>
			</div>
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
				<h4><?php _e( 'Developers', 'sportspress' ); ?></h4>
				<?php echo $this->contributors(); ?>
			</div>
			
			<p class="about-description"><?php printf( __( 'Some presets have been submitted by our helpful and generous users. Want to see your name? <a href="%s">Add a Sport Preset</a>.', 'sportspress' ), 'http://tboy.co/preset' ); ?></p>

			<div class="sp-feature feature-section col one-col">
				<h4><?php _e( 'Presets', 'sportspress' ); ?></h4>
				<?php
				$preset_credits = array(
					__( 'Counter-Strike: Global Offensive', 'sportspress' ) => 'Oscar Wong',
					__( 'Lacrosse', 'sportspress' ) => 'Jamie',
				);
				?>
				<dl class="sp-presets">
					<?php foreach ( $preset_credits as $preset => $name ) { ?>
					<dt><?php echo $preset; ?></dt>
					<dd><em><?php echo $name; ?></em></dd>
					<?php } ?>
				</dl>
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

			<p class="about-description"><?php printf( __( 'SportsPress has been kindly translated into several other languages thanks to our translation team. Want to see your name? <a href="%s">Translate SportsPress</a>.', 'sportspress' ), 'https://www.transifex.com/projects/p/sportspress/' ); ?></p>
			<div class="postbox sp-top-translations">
				<h3 class="hndle"><span><?php _e( 'Top Translations', 'sportspress' ); ?></span></h3>
				<p class="sp-transifex-chart">
					<a target="_blank" href="https://www.transifex.com/projects/p/sportspress"><img border="0" src="https://www.transifex.com/projects/p/sportspress/resource/sportspress/chart/image_png"/></a>
				</p>
			</div>
			<p class="wp-credits-list">
			<?php
			$translators = array(
				'Shqip' => array(
					'albpower',
				),
				'العربية' => array(
					'Abdulelah',
					'elgolden',
					'hushiea',
				),
				'Հայերեն' => array(
					'ArtakEVN',
				),
				'বাংলা' => array(
					'alisiddique',
				),
				'Bosanski' => array(
					'etcloki',
				),
				'Български' => array(
					'alltimecams',
					'den_zlateva',
				),
				'简体中文' => array(
					'dic_2008',
					'mobking',
				),
				'繁體中文' => array(
					'wah826',
				),
				'Hrvatski' => array(
					'etcloki',
					'i__k',
					'iojvan',
					'vlinicx',
				),
				'Čeština' => array(
					'eifelstudio',
					'thegreat',
				),
				'Nederlands' => array(
					'paulcoppen',
					'poelie',
					'SilverXp',
					'valentijnreza',
				),
				'Suomi' => array(
					'hanro',
					'Hermanni',
					'JuKi',
					'Taurus',
				),
				'Français' => array(
					'francois53',
					'fredodq',
					'HuguesD',
					'MohamedZ',
					'wolforg',
				),
				'Deutsch' => array(
					'alexander.salomon99',
					'Bhelpful2',
					'chr86',
					'deckerweb',
					'denkuhn',
					'FollowCandyPanda',
					'green_big_frog',
					'King3R',
					'Tandor',
					'tkausch',
				),
				'Ελληνικά' => array(
					'filippos.sdr',
					'Spirossmil',
				),
				'Íslenska' => array(
					'ValliFudd',
				),
				'Italiano' => array(
					'eNnvi',
					'Flubber89',
					'GhiMax',
					'joegalaxy66',
					'massimo.marra',
					'sododesign',
					'violaud',
					'webby1973',
					'xFrAx',
				),
				'日本語' => array(
					'aylaview',
				),
				'한국어' => array(
					'jikji96',
				),
				'Македонски' => array(
					'doncer',
				),
				'Norsk bokmål' => array(
					'jenymoen',
					'Laislebai',
					'm4rsal',
					'sijo',
					'slappfiskene.no',
					'vetsmi',
				),
				'فارسی' => array(
					'mahdi12',
				),
				'Polski' => array(
					'Elmister',
					'karimjarro',
					'krisop',
				),
				'Português do Brasil' => array(
					'AugustoNeto',
					'Ferenan',
					'lfrodines',
					'Ozias',
					'pgbenini',
					'rochester',
				),
				'Português' => array(
					'Andrew_Melim',
					'nagashitw',
				),
				'Română' => array(
					'GonerSTUDIO',
					'tyby94',
				),
				'Русский' => array(
					'elrawys',
					'kanakoff',
					'sashaCZ',
					'Selskei',
					'SmilyCarrot',
					'zzcs',
				),
				'Српски језик' => array(
					'etcloki',
				),
				'Slovenščina' => array(
					'Ales70',
					'BOCo',
					'cofeman.sl',
					'matiqos',
				),
				'Español' => array(
					'albertone',
					'diego.battistella',
					'elarequi',
					'EmiDelCaz',
					'edesl',
					'fernandori',
					'GonerSTUDIO',
					'i1m3a7n92',
					'latixns',
					'opticadeharo',
					'popeosorio',
				),
				'Svenska' => array(
					'fiiz',
					'JensZ',
				),
				'தமிழ்' => array(
					'chinnz25',
				),
				'Türkçe' => array(
					'ALooNeBoy87',
					'ceyhunulas',
					'GuneshGamza95',
					'muhahmetkara',
					'overbite',
				),
				'Українська' => array(
					'ViktoriaRuzhylo',
				),
				'Tiếng Việt' => array(
					'bizover',
				),
				'ಕನ್ನಡ' => array(
					'Paramamithra',
				),
				'ไทย' => array(
					'Xyteton',
				),
			);
			$languages = array_keys( $translators );
			shuffle( $languages );
			$translation_teams = array();
			foreach ( $languages as $language ):
				$handles = $translators[ $language ];
				$team = '<strong>' . $language . '</strong> ' . __( 'by', 'sportspress' ) . ' ';
				$team_members = array();
				foreach ( $handles as $handle ):
					$team_members[] = '<a href="https://www.transifex.com/accounts/profile/' . $handle . '">' . $handle . '</a>';
				endforeach;
				$members = implode( ', ', $team_members );
				$team .= $members;
				$team .= '';
				$translation_teams[] = $team;
			endforeach;
			echo implode( '<br>', $translation_teams );
			?>
			</p>
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

		wp_redirect( admin_url( 'index.php?page=sp-about' ) );
		exit;
	}
}

new SP_Admin_Welcome();
