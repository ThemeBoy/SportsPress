<?php
/*
Plugin Name: SportsPress Tutorials
Plugin URI: http://themeboy.com/
Description: Display SportsPress video tutorials.
Author: ThemeBoy
Author URI: http://themeboy.com/
Version: 1.7
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_Tutorials' ) ) :

/**
 * Main SportsPress Tutorials Class
 *
 * @class SportsPress_Tutorials
 * @version	1.7
 */
class SportsPress_Tutorials {

	/**
	 * @var array
	 */
	public $ids = array();

	/**
	 * Constructor
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();

		add_action( 'init', array( $this, 'get_video_ids' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu' ), 8 );
		add_action( 'sportspress_screen_ids', array( $this, 'screen_ids' ) );
		add_filter( 'sportspress_next_steps', array( $this, 'next_steps' ) );
	}

	/**
	 * Define constants.
	*/
	private function define_constants() {
		if ( !defined( 'SP_TUTORIALS_VERSION' ) )
			define( 'SP_TUTORIALS_VERSION', '1.7' );

		if ( !defined( 'SP_TUTORIALS_URL' ) )
			define( 'SP_TUTORIALS_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'SP_TUTORIALS_DIR' ) )
			define( 'SP_TUTORIALS_DIR', plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Get video IDs.
	*/
	public function get_video_ids() {
		$this->ids = apply_filters( 'sportspress_tutorial_videos', array(
			__( 'Get Started', 'sportspress' ) => apply_filters( 'sportspress_get_started_tutorial_videos', array(
				__( 'Installation', 'sportspress' ) => '121430679',
				__( 'Competitions', 'sportspress' ) . ' &amp; ' . __( 'Seasons', 'sportspress' ) => '121438196',
				__( 'Venues', 'sportspress' ) => '121438615',
				__( 'Positions', 'sportspress' ) . ' &amp; ' . __( 'Jobs', 'sportspress' ) => '121438826',
			) ),
			__( 'Teams', 'sportspress' ) => apply_filters( 'sportspress_team_tutorial_videos', array(
				__( 'Add New Team', 'sportspress' ) => '121439873',
				__( 'League Tables', 'sportspress' ) => '121592514',
			) ),
			__( 'Players', 'sportspress' ) . ' &amp; ' . __( 'Staff', 'sportspress' ) => array_merge(
				apply_filters( 'sportspress_player_tutorial_videos', array(
					__( 'Add New Player', 'sportspress' ) => '121440032',
				) ),
				apply_filters( 'sportspress_staff_tutorial_videos', array(
					__( 'Add New Staff', 'sportspress' ) => '121440185',
				) )
			),
			__( 'Events', 'sportspress' ) => apply_filters( 'sportspress_event_tutorial_videos', array(
				__( 'Add New Event', 'sportspress' ) => '121524233',
				__( 'Edit Event', 'sportspress' ) => '121590015',
				__( 'Calendars', 'sportspress' ) => '121591259',
			) ),
		) );
	}

	/**
	 * Add menu item
	 */
	public function admin_menu() {
		add_submenu_page( 'sportspress', __( 'Tutorials', 'sportspress' ), __( 'Tutorials', 'sportspress' ), 'manage_sportspress', 'sportspress-tutorials', array( $this, 'tutorials_page' ) );
	}

	/**
	 * Init the tutorials page
	 */
	public function tutorials_page() {
		$i = 0;
		?>
		<div class="wrap sportspress sportspress-tutorials-wrap">
			<h2>
				<?php _e( 'Tutorials', 'sportspress' ); ?>
			</h2>
			<div class="sp-tutorials-main">
				<?php foreach ( $this->ids as $section => $ids ) { ?>
					<h3><?php echo $section; ?></h3>
					<ul class="sp-tutorials-list">
						<?php foreach ( $ids as $label => $id ) { $i++; ?>
							<li>
								<table class="widefat" cellspacing="0">
									<thead>
										<tr><th>
											<strong><?php echo $i; ?></strong> 
											<?php echo $label; ?>
											<a href="#" class="sp-popup sp-icon-popup" title="<?php _e( 'Pop-out', 'sportspress' ); ?>" onclick="window.open('//player.vimeo.com/video/<?php echo $id; ?>?color=00a69c&amp;portrait=0&amp;title=0&amp;byline=0&amp;autoplay=1', '_blank', 'width=640, height=360');return false;"></a>
										</th></tr>
									</thead>
									<tbody>
										<tr>
											<td>
												<div class="sp-fitvids">
													<iframe src="//player.vimeo.com/video/<?php echo $id; ?>?color=00a69c&amp;portrait=0&amp;title=0&amp;byline=0" width="320" height="180" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
												</div>
											</td>
										</tr>
									</tbody>
								</table>
							</li>
						<?php } ?>
					</ul>
				<?php } ?>
				<?php do_action( 'sportspress_tutorials_page' ); ?>
			</div>
		<?php
	}

	/**
	 * Add screen ids
	 */
	public function screen_ids( $ids = array() ) {
		$ids[] = 'sportspress_page_sportspress-tutorials';
		return $ids;
	}

	/**
	 * Add link to next steps
	 */
	public function next_steps( $steps = array() ) {
		$steps = array(
			'tutorials' => array(
				'link' => admin_url( add_query_arg( array( 'page' => 'sportspress-tutorials' ), 'admin.php' ) ),
				'icon' => 'dashicons dashicons-video-alt3',
				'label' => __( 'Tutorials', 'sportspress' ),
			) ) + $steps;
		return $steps;
	}
}

endif;

if ( get_option( 'sportspress_load_tutorials_module', 'yes' ) == 'yes' ) {
	new SportsPress_Tutorials();
}
