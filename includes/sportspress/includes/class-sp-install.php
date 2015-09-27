<?php
/**
 * Installation related functions and actions.
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Classes
 * @version     1.9
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'SP_Install' ) ) :

/**
 * SP_Install Class
 */
class SP_Install {

	/**
	 * Hook in tabs.
	 */
	public function __construct() {
		register_activation_hook( SP_PLUGIN_FILE, array( $this, 'install' ) );

		add_action( 'admin_init', array( $this, 'install_actions' ) );
		add_action( 'admin_init', array( $this, 'check_version' ), 5 );
		add_action( 'in_plugin_update_message-sportspress/sportspress.php', array( $this, 'in_plugin_update_message' ) );
	}

	/**
	 * check_version function.
	 *
	 * @access public
	 * @return void
	 */
	public function check_version() {
		if ( ! defined( 'IFRAME_REQUEST' ) && get_option( 'sportspress_version' ) != SP()->version ) {
			$this->install();

			do_action( 'sportspress_updated' );
		}
	}

	/**
	 * Install actions such as installing pages when a button is clicked.
	 */
	public function install_actions() {
		// Install - Add pages button
		if ( ! empty( $_GET['install_sportspress'] ) ) {

			// We no longer need to install pages
			delete_option( '_sp_needs_welcome' );
			delete_transient( '_sp_activation_redirect' );

			// What's new redirect
			//wp_redirect( admin_url( 'index.php?page=sp-about&sp-installed=true' ) );
			//exit;

		// Skip button
		} elseif ( ! empty( $_GET['skip_install_sportspress'] ) ) {

			// We no longer need to install configs
			delete_option( '_sp_needs_welcome' );
			delete_transient( '_sp_activation_redirect' );

			// What's new redirect
			wp_redirect( admin_url( 'index.php?page=sp-about' ) );
			exit;
			
		}
	}

	/**
	 * Install SP
	 */
	public function install() {
		$this->remove_roles();
		$this->create_roles();

		// Register post types
		include_once( 'class-sp-post-types.php' );
		SP_Post_types::register_post_types();
		SP_Post_types::register_taxonomies();

		$this->create_options();

		// Queue upgrades
		$current_version = get_option( 'sportspress_version', null );

		// Update version
		update_option( 'sportspress_version', SP()->version );

		// Check if pages are needed
		if ( ! get_option( 'sportspress_sport' ) ) {
			update_option( '_sp_needs_welcome', 1 );
		}

		// Flush rules after install
		flush_rewrite_rules();

		// Redirect to welcome screen
		set_transient( '_sp_activation_redirect', 1, 60 * 60 );
	}

	/**
	 * Default options
	 *
	 * Sets up the default options used on the settings page
	 *
	 * @access public
	 */
	function create_options() {
		// Include settings so that we can run through defaults
		include_once( 'admin/class-sp-admin-settings.php' );

		$settings = SP_Admin_Settings::get_settings_pages();

		foreach ( $settings as $section ) {
			foreach ( $section->get_settings() as $value ) {
				if ( isset( $value['default'] ) && isset( $value['id'] ) ) {
					$autoload = isset( $value['autoload'] ) ? (bool) $value['autoload'] : true;
					add_option( $value['id'], $value['default'], '', ( $autoload ? 'yes' : 'no' ) );
				}
			}
		}

		// Default color scheme
	    add_option( 'sportspress_frontend_css_primary', '#2b353e' );
	    add_option( 'sportspress_frontend_css_background', '#f4f4f4' );
	    add_option( 'sportspress_frontend_css_text', '#222222' );
	    add_option( 'sportspress_frontend_css_heading', '#ffffff' );
	    add_option( 'sportspress_frontend_css_link', '#00a69c' );

		if ( ! get_option( 'sportspress_installed' ) ) {
			// Configure default sport
			$sport = 'custom';
		    update_option( 'sportspress_sport', $sport );

			// Flag as installed
			update_option( 'sportspress_installed', 1 );
		}
	}

	/**
	 * Create roles and capabilities
	 */
	public function create_roles() {
		global $wp_roles;

		if ( class_exists( 'WP_Roles' ) ):
			if ( ! isset( $wp_roles ) ):
				$wp_roles = new WP_Roles();
			endif;
		endif;

		if ( is_object( $wp_roles ) ):

		    add_role(
		        'sp_player',
		        __( 'Player', 'sportspress' ),
		        array(
					'level_1' 						=> true,
					'level_0' 						=> true,

		            'read' 							=> true,
		            'delete_posts' 					=> true,
		            'edit_posts' 					=> true,
		            'upload_files' 					=> true,

		            'edit_sp_player'				=> true,
		            'read_sp_player'				=> true,
		            'edit_sp_players' 				=> true,
		            'edit_published_sp_players' 	=> true,
					'assign_sp_player_terms' 		=> true,

		            'edit_sp_event'					=> true,
		            'read_sp_event'					=> true,
		            'edit_sp_events' 				=> true,
		            'edit_published_sp_events' 		=> true,
					'assign_sp_event_terms' 		=> true,

		            'edit_sp_team'					=> true,
		            'read_sp_team'					=> true,
		            'edit_sp_teams' 				=> true,
		            'edit_published_sp_teams' 		=> true,
					'assign_sp_team_terms' 			=> true,
		        )
		    );

		    add_role(
		        'sp_staff',
		        __( 'Staff', 'sportspress' ),
		        array(
					'level_1' 						=> true,
					'level_0' 						=> true,

		            'read' 							=> true,
		            'delete_posts' 					=> true,
		            'edit_posts' 					=> true,
		            'upload_files' 					=> true,

		            'edit_sp_staff'					=> true,
		            'read_sp_staff'					=> true,
		            'edit_sp_staffs' 				=> true,
		            'edit_published_sp_staffs' 		=> true,
					'assign_sp_staff_terms' 		=> true,

		            'edit_sp_event'					=> true,
		            'read_sp_event'					=> true,
		            'edit_sp_events' 				=> true,
		            'edit_published_sp_events' 		=> true,
					'assign_sp_event_terms' 		=> true,

		            'edit_sp_team'					=> true,
		            'read_sp_team'					=> true,
		            'edit_sp_teams' 				=> true,
		            'edit_published_sp_teams' 		=> true,
					'assign_sp_team_terms' 			=> true,

		            'edit_sp_player'				=> true,
		            'read_sp_player'				=> true,
		            'edit_sp_players' 				=> true,
		            'edit_published_sp_players' 	=> true,
					'assign_sp_player_terms' 		=> true,
		        )
		    );

		    add_role(
		        'sp_event_manager',
		        __( 'Event Manager', 'sportspress' ),
		        array(
					'level_1' 						=> true,
					'level_0' 						=> true,

		            'read' 							=> true,
		            'delete_posts' 					=> true,
		            'edit_posts' 					=> true,
		            'upload_files' 					=> true,
					'manage_categories' 			=> true,

		            'edit_sp_event'					=> true,
		            'read_sp_event'					=> true,
		            'delete_sp_event'				=> true,
		            'edit_sp_events' 				=> true,
		            'edit_others_sp_events' 		=> true,
		            'publish_sp_events' 			=> true,
		            'delete_sp_events' 				=> true,
		            'delete_published_sp_events' 	=> true,
		            'edit_published_sp_events' 		=> true,
					'manage_sp_event_terms' 		=> true,
					'edit_sp_event_terms' 			=> true,
					'delete_sp_event_terms' 		=> true,
					'assign_sp_event_terms' 		=> true,

		            'edit_sp_team'					=> true,
		            'read_sp_team'					=> true,
		            'edit_sp_teams' 				=> true,
		            'edit_published_sp_teams' 		=> true,
					'assign_sp_team_terms' 			=> true,

		            'edit_sp_player'				=> true,
		            'read_sp_player'				=> true,
		            'edit_sp_players' 				=> true,
		            'edit_published_sp_players' 	=> true,
					'assign_sp_player_terms' 		=> true,

		            'edit_sp_staff'					=> true,
		            'read_sp_staff'					=> true,
		            'edit_sp_staffs' 				=> true,
		            'edit_published_sp_staffs' 		=> true,
					'assign_sp_staff_terms' 		=> true,
		        )
			);

		    add_role(
		        'sp_team_manager',
		        __( 'Team Manager', 'sportspress' ),
		        array(
					'level_2' 						=> true,
					'level_1' 						=> true,
					'level_0' 						=> true,

		            'read' 							=> true,
		            'delete_posts' 					=> true,
		            'edit_posts' 					=> true,
		            'delete_published_posts' 		=> true,
		            'publish_posts' 				=> true,
		            'upload_files' 					=> true,
		            'edit_published_posts' 			=> true,

		            'edit_sp_player'				=> true,
		            'read_sp_player'				=> true,
		            'delete_sp_player'				=> true,
		            'edit_sp_players' 				=> true,
		            'edit_others_sp_players'		=> true,
		            'publish_sp_players' 			=> true,
		            'delete_sp_players' 			=> true,
		            'delete_published_sp_players' 	=> true,
		            'edit_published_sp_players' 	=> true,
					'assign_sp_player_terms' 		=> true,

		            'edit_sp_staff'					=> true,
		            'read_sp_staff'					=> true,
		            'delete_sp_staff'				=> true,
		            'edit_sp_staffs' 				=> true,
		            'edit_others_sp_staffs'			=> true,
		            'publish_sp_staffs' 			=> true,
		            'delete_sp_staffs' 				=> true,
		            'delete_published_sp_staffs' 	=> true,
		            'edit_published_sp_staffs' 		=> true,
					'assign_sp_staff_terms' 		=> true,

		            'edit_sp_event'					=> true,
		            'read_sp_event'					=> true,
		            'delete_sp_event'				=> true,
		            'edit_sp_events' 				=> true,
		            'edit_others_sp_events' 		=> true,
		            'publish_sp_events' 			=> true,
		            'delete_sp_events' 				=> true,
		            'delete_published_sp_events' 	=> true,
		            'edit_published_sp_events' 		=> true,
					'assign_sp_event_terms' 		=> true,

		            'edit_sp_team'					=> true,
		            'read_sp_team'					=> true,
		            'edit_sp_teams' 				=> true,
		            'edit_published_sp_teams' 		=> true,
					'assign_sp_team_terms' 			=> true,

		            'edit_sp_list'					=> true,
		            'read_sp_list'					=> true,
		            'delete_sp_list'				=> true,
		            'edit_sp_lists' 				=> true,
		            'publish_sp_lists' 				=> true,
		            'delete_sp_lists' 				=> true,
		            'delete_published_sp_lists' 	=> true,
		            'edit_published_sp_lists' 		=> true,
					'assign_sp_list_terms' 			=> true,
		        )
		    );

			add_role(
				'sp_league_manager',
				__( 'League Manager', 'sportspress' ),
				array(
					'level_7' 						=> true,
					'level_6' 						=> true,
					'level_5' 						=> true,
					'level_4' 						=> true,
					'level_3' 						=> true,
					'level_2' 						=> true,
					'level_1' 						=> true,
					'level_0' 						=> true,

					'read' 							=> true,
					'read_private_pages' 			=> true,
					'read_private_posts' 			=> true,
					'edit_users' 					=> true,
					'edit_posts' 					=> true,
					'edit_pages' 					=> true,
					'edit_published_posts' 			=> true,
					'edit_published_pages' 			=> true,
					'edit_private_pages' 			=> true,
					'edit_private_posts' 			=> true,
					'edit_others_posts' 			=> true,
					'edit_others_pages' 			=> true,
					'publish_posts' 				=> true,
					'publish_pages' 				=> true,
					'delete_posts' 					=> true,
					'delete_pages' 					=> true,
					'delete_private_pages' 			=> true,
					'delete_private_posts' 			=> true,
					'delete_published_pages' 		=> true,
					'delete_published_posts' 		=> true,
					'delete_others_posts' 			=> true,
					'delete_others_pages' 			=> true,
					'manage_categories' 			=> true,
					'manage_links' 					=> true,
					'moderate_comments' 			=> true,
					'unfiltered_html' 				=> true,
					'upload_files' 					=> true,
					'export' 						=> true,
					'import' 						=> true,
					'list_users' 					=> true
				)
			);

			$capabilities = $this->get_core_capabilities();

			foreach ( $capabilities as $cap_group ):
				foreach ( $cap_group as $cap ):
					$wp_roles->add_cap( 'sp_league_manager', $cap );
					$wp_roles->add_cap( 'administrator', $cap );
				endforeach;
			endforeach;
		endif;
	}

	/**
	 * Get capabilities for SportsPress - these are assigned during installation or reset
	 *
	 * @access public
	 * @return array
	 */
	public function get_core_capabilities() {
		include_once( 'sp-conditional-functions.php' );
		$capabilities = array();

		$capabilities['core'] = array(
			'manage_sportspress',
			'view_sportspress_reports',
		);

		$post_types = sp_post_types();
		array_unshift( $post_types, 'sp_config' );

		$capability_types = apply_filters( 'sportspress_post_types', $post_types );

		foreach ( $capability_types as $capability_type ) {

			$capabilities[ $capability_type ] = array(
				// Post type
				"edit_{$capability_type}",
				"read_{$capability_type}",
				"delete_{$capability_type}",
				"edit_{$capability_type}s",
				"edit_others_{$capability_type}s",
				"publish_{$capability_type}s",
				"read_private_{$capability_type}s",
				"delete_{$capability_type}s",
				"delete_private_{$capability_type}s",
				"delete_published_{$capability_type}s",
				"delete_others_{$capability_type}s",
				"edit_private_{$capability_type}s",
				"edit_published_{$capability_type}s",

				// Terms
				"manage_{$capability_type}_terms",
				"edit_{$capability_type}_terms",
				"delete_{$capability_type}_terms",
				"assign_{$capability_type}_terms"
			);
		}

		return $capabilities;
	}

	/**
	 * sportspress_remove_roles function.
	 *
	 * @access public
	 * @return void
	 */
	public function remove_roles() {
		global $wp_roles;

		if ( class_exists( 'WP_Roles' ) ) {
			if ( ! isset( $wp_roles ) ) {
				$wp_roles = new WP_Roles();
			}
		}

		if ( is_object( $wp_roles ) ) {

			$capabilities = $this->get_core_capabilities();

			foreach ( $capabilities as $cap_group ) {
				foreach ( $cap_group as $cap ) {
					$wp_roles->remove_cap( 'sp_player', $cap );
					$wp_roles->remove_cap( 'sp_staff', $cap );
					$wp_roles->remove_cap( 'sp_team_manager', $cap );
					$wp_roles->remove_cap( 'sp_league_manager', $cap );
					$wp_roles->remove_cap( 'administrator', $cap );
				}
			}

			remove_role( 'sp_player' );
			remove_role( 'sp_staff' );
			remove_role( 'sp_event_manager' );
			remove_role( 'sp_team_manager' );
			remove_role( 'sp_league_manager' );
		}
	}

	/**
	 * Active plugins pre update option filter
	 *
	 * @param string $new_value
	 * @return string
	 */
	function pre_update_option_active_plugins( $new_value ) {
		$old_value = (array) get_option( 'active_plugins' );

		if ( $new_value !== $old_value && in_array( W3TC_FILE, (array) $new_value ) && in_array( W3TC_FILE, (array) $old_value ) ) {
			$this->_config->set( 'notes.plugins_updated', true );
			try {
				$this->_config->save();
			} catch( Exception $ex ) {}
		}

		return $new_value;
	}

	/**
	 * Show plugin changes. Code adapted from W3 Total Cache.
	 *
	 * @return void
	 */
	function in_plugin_update_message() {
		$response = wp_remote_get( 'https://plugins.svn.wordpress.org/sportspress/trunk/readme.txt' );

		if ( ! is_wp_error( $response ) && ! empty( $response['body'] ) ) {

			// Output Upgrade Notice
			$matches = null;
			$regexp = '~==\s*Upgrade Notice\s*==\s*=\s*(.*)\s*=(.*)(=\s*' . preg_quote( SP_VERSION ) . '\s*=|$)~Uis';

			if ( preg_match( $regexp, $response['body'], $matches ) ) {
				$version = trim( $matches[1] );
				$notices = (array) preg_split('~[\r\n]+~', trim( $matches[2] ) );

				if ( version_compare( SP_VERSION, $version, '<' ) ) {

					echo '<div style="font-weight: normal; background: #cc99c2; color: #fff !important; border: 1px solid #b76ca9; padding: 9px; margin: 9px 0;">';

					foreach ( $notices as $index => $line ) {
						echo '<p style="margin: 0; font-size: 1.1em; color: #fff; text-shadow: 0 1px 1px #b574a8;">' . wp_kses_post( preg_replace( '~\[([^\]]*)\]\(([^\)]*)\)~', '<a href="${2}">${1}</a>', $line ) ) . '</p>';
					}

					echo '</div> ';
				}
			}

			// Output Changelog
			$matches = null;
			$regexp = '~==\s*Changelog\s*==\s*=\s*[0-9.]+\s*-(.*)=(.*)(=\s*' . preg_quote( SP_VERSION ) . '\s*-(.*)=|$)~Uis';

			if ( preg_match( $regexp, $response['body'], $matches ) ) {
				$changelog = (array) preg_split( '~[\r\n]+~', trim( $matches[2] ) );

				_e( 'What\'s new:', 'sportspress' ) . '<div style="font-weight: normal;">';

				$ul = false;

				foreach ( $changelog as $index => $line ) {
					if ( preg_match('~^\s*\*\s*~', $line ) ) {
						if ( ! $ul ) {
							echo '<ul style="list-style: disc inside; margin: 9px 0 9px 20px; overflow:hidden; zoom: 1;">';
							$ul = true;
						}
						
						$line = preg_replace( '~^\s*\*\s*~', '', htmlspecialchars( $line ) );
						
						echo '<li style="width: 50%; margin: 0; float: left; ' . ( $index % 2 == 0 ? 'clear: left;' : '' ) . '">' . esc_html( $line ) . '</li>';
					} else {

						$version = trim( current( explode( '-', str_replace( '=', '', $line ) ) ) );

						if ( version_compare( SP_VERSION, $version, '>=' ) ) {
							break;
						}

						if ( $ul ) {
							echo '</ul>';
							$ul = false;
						}

						echo '<p style="margin: 9px 0;">' . esc_html( htmlspecialchars( $line ) ) . '</p>';
					}
				}

				if ( $ul ) {
					echo '</ul>';
				}

				echo '</div>';
			}
		}
	}
}

endif;

return new SP_Install();
