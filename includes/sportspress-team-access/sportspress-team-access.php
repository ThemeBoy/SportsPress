<?php
/*
Plugin Name: SportsPress Team Access
Plugin URI: http://tboy.co/pro
Description: Assign users to a specific team and limit their access to data related to that team.
Author: ThemeBoy
Author URI: http://themeboy.com
Version: 1.9
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_Team_Access' ) ) :

/**
 * Main SportsPress Team Access Class
 *
 * @class SportsPress_Team_Access
 * @version	1.9
 */
class SportsPress_Team_Access {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();

		// Hooks
		add_filter( 'sportspress_screen_ids', array( $this, 'screen_ids' ) );
		add_action( 'show_user_profile', array( $this, 'profile' ) );
		add_action( 'edit_user_profile', array( $this, 'profile' ) );
		add_action( 'edit_user_profile_update', array( $this, 'save' ) );
		add_filter( 'pre_get_posts', array( $this, 'filter' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_filter( 'sportspress_user_can', array( $this, 'user_can' ), 10, 2 );
	}

	/**
	 * Define constants.
	 */
	private function define_constants() {
		if ( !defined( 'SP_TEAM_ACCESS_VERSION' ) )
			define( 'SP_TEAM_ACCESS_VERSION', '1.9' );

		if ( !defined( 'SP_TEAM_ACCESS_URL' ) )
			define( 'SP_TEAM_ACCESS_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'SP_TEAM_ACCESS_DIR' ) )
			define( 'SP_TEAM_ACCESS_DIR', plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Add profile pages to screen ids.
	 */
	public function screen_ids( $ids ) {
		$ids[] = 'profile';
		$ids[] = 'user-edit';
		return $ids;
	}

	/**
	 * Add team selector to user profile.
	 */
	public function profile( $user ) {
		$roles = $user->roles;
		$role = array_shift( $roles );
		if ( ! $this->role_is_limited( $role ) ) return;

		$teams = get_user_meta( $user->ID, 'sp_team', false );
		?>
		<h3><?php _e( 'Team Access', 'sportspress' ); ?></h3>
		<table class="form-table">
			<tr>
				<th>
					<?php _e( 'Team', 'sportspress' ); ?>
				</th>
				<td>
					<?php
					$args = array(
						'post_type' => 'sp_team',
						'name' => 'sp_team[]',
						'selected' => $teams,
						'values' => 'ID',
						'class' => 'widefat',
						'placeholder' => __( 'All', 'sportspress' ),
						'property' => 'multiple',
						'chosen' => true,
					);
					sp_dropdown_pages( $args );
					?>
				</td>
			</tr>
		</table>
		<?php
	}

	/**
	 * Save team to user profile.
	 */
	function save( $user_id ) {
		if ( current_user_can( 'edit_user', $user_id ) ) {
			sp_update_user_meta_recursive( $user_id, 'sp_team', sp_array_value( $_POST, 'sp_team', array() ) );
		}
	}

	/**
	 * Filter admin access by team.
	 *
	 * @param mixed $query
	 */
	public function filter( $query ) {
		// Return if not admin
		if ( ! is_admin() ) return $query;

		// Return if not SportsPress post type
		if ( ! is_sp_post_type() ) return $query;

		// Return if limit doesn't apply to user and post type
		global $current_user;
		$post_type = sp_array_value( $query->query_vars, 'post_type', 'post' );
		$roles = $current_user->roles;
		$role = array_shift( $roles );
		if ( ! $this->role_is_limited( $role ) || ! $this->limit_applies( $post_type ) ) return $query;

		// Get current user team setting and return if not set
		$user = wp_get_current_user();
		$teams = get_user_meta( $user->ID, 'sp_team', false );
		if ( ! $teams || ! is_array( $teams ) ) return $query;

		$query = $this->query( $query, $teams, $post_type );

		return $query;
	}

	/**
	 * Enqueue scripts
	 */
	public function admin_enqueue_scripts() {
		if ( $this->role_is_limited() && $this->limit_applies() ) {
			wp_enqueue_script( 'sportspress-team-access-admin', SP_TEAM_ACCESS_URL . 'js/admin.js', array( 'jquery' ), SP_TEAM_ACCESS_VERSION, true );
		}
	}

	/**
	 * Limit saving data
	 */
	public function user_can( $can = true, $id = null ) {
		if ( ! $can || ! $id ) return $can;
		$post_type = get_post_type( $id );
		if ( $this->role_is_limited() && $this->limit_applies( $post_type ) ) {
			$key = $this->key();
			$user = wp_get_current_user();
			$teams = get_user_meta( $user->ID, 'sp_team', false );
			if ( 'sp_team' == $post_type ) {
				$meta = array( $id );
			} else {
				$meta = get_post_meta( $id, $key, false );
			}
			$intersect = array_intersect( $meta, $teams );
			return 0 < sizeof( $intersect );
		}
		return $can;
	}

	/** Helper functions ******************************************************/

	/**
	 * Determine if role is limited access.
	 */
	public function role_is_limited( $role = null ) {
		if ( ! $role ) {
			global $current_user;
			$roles = $current_user->roles;
			$role = array_shift( $roles );
		}

		if ( in_array( $role, apply_filters( 'sportspress_team_access_roles', array( 'sp_player', 'sp_staff', 'sp_team_manager', 'sp_event_manager' ) ) ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Determine if limit applies to the post type
	 */
	public function limit_applies( $typenow = null ) {
		if ( ! $typenow ) global $typenow;

		if ( in_array( $typenow, apply_filters( 'sportspress_team_access_post_types', array( 'sp_event', 'sp_calendar', 'sp_team', 'sp_table', 'sp_player', 'sp_list', 'sp_staff' ) ) ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Determine key for the post type
	 */
	public function key( $typenow = null ) {
		if ( ! $typenow ) global $typenow;

		if ( in_array( $typenow, array( 'sp_player', 'sp_staff' ) ) ) {
			$key = 'sp_current_team';
		} else {
			$key = 'sp_team';
		}

		return $key;
	}

	/**
	 * Modify the query
	 */
	public function query( $query, $teams = array(), $typenow = null ) {
		if ( 'sp_team' == $typenow ) {
			$query->query_vars['post__in'] = $teams;
		} else {
			// Determine meta key
			$key = $this->key( $typenow );

			// Get current meta query
			$meta = sp_array_value( $query->query_vars, 'meta_query', array() );

			// Add teams to meta query
			$meta[] = array(
				'key' => $key,
				'value' => $teams,
				'compare' => 'IN',
			);
			$query->query_vars['meta_query'] = $meta;
		}

		return $query;
	}
}

endif;

if ( get_option( 'sportspress_load_team_access_module', 'yes' ) == 'yes' ) {
	new SportsPress_Team_Access();
}