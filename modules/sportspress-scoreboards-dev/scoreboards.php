<?php
/**
 * @package SportsPress Scoreboards
 */
/*
Plugin Name: SportsPress Scoreboards
Plugin URI: http://sportspress.com/
Description: Add Scoreboards to SportsPress.
Version: 0.1
Author: ThemeBoy
Author URI: http://themeboy.com/
*/

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

// Define version and plugin location
define( 'SPORTSPRESS_SCOREBOARDS_VERSION', '0.1' );
define( 'SPORTSPRESS_SCOREBOARDS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'SPORTSPRESS_SCOREBOARDS_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'SPORTSPRESS_SCOREBOARDS_PLUGIN_FILE', __FILE__ );

class SP_Scoreboards() {

}

function sportspress_scoreboard_post_init() {
	$labels = array(
		'name' => __( 'Scoreboards', 'sportspress' ),
		'singular_name' => __( 'Scoreboard', 'sportspress' ),
		'add_new_item' => __( 'Add New Scoreboard', 'sportspress' ),
		'edit_item' => __( 'Edit Scoreboard', 'sportspress' ),
		'new_item' => __( 'New', 'sportspress' ),
		'view_item' => __( 'View', 'sportspress' ),
		'search_items' => __( 'Search', 'sportspress' ),
		'not_found' => __( 'No results found.', 'sportspress' ),
		'not_found_in_trash' => __( 'No results found.', 'sportspress' ),
	);
	$args = array(
		'label' => __( 'Scoreboards', 'sportspress' ),
		'labels' => $labels,
		'public' => true,
		'has_archive' => false,
		'hierarchical' => true,
		'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'page-attributes' ),
		'register_meta_box_cb' => 'sportspress_scoreboard_meta_init',
		'rewrite' => array( 'slug' => get_option( 'sp_scoreboard_slug', 'scoreboard' ) ),
		'show_in_menu' => 'edit.php?post_type=sp_event',
		'capability_type' => 'sp_scoreboard'
	);
	register_post_type( 'sp_scoreboard', $args );
}
add_action( 'init', 'sportspress_scoreboard_post_init', 11 );

function sportspress_scoreboard_meta_init() {
}

function sportspress_scoreboard_edit_columns() {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Scoreboard', 'sportspress' ),
		'sp_team' => __( 'Teams', 'sportspress' ),
	);
	return $columns;
}
add_filter( 'manage_edit-sp_scoreboard_columns', 'sportspress_scoreboard_edit_columns' );

function sportspress_scoreboards_admin_head() {
	global $typenow;
	if ( $typenow == 'sp_scoreboard' )
		sportspress_highlight_admin_menu( 'edit.php?post_type=sp_event', 'edit.php?post_type=sp_scoreboard' );
}
add_action( 'admin_head-edit.php', 'sportspress_scoreboards_admin_head', 10, 2 );
add_action( 'admin_head-post.php', 'sportspress_scoreboards_admin_head', 10, 2 );
add_action( 'admin_head-post-new.php', 'sportspress_scoreboards_admin_head', 10, 2 );

function sportspress_scoreboards_admin_init() {
    $post_types = array(
        'sp_scoreboard',
    );

    $caps = array(
        'read',
        'read_private',
        'edit',
        'edit_others',
        'edit_private',
        'edit_published',
        'publish',
        'delete',
        'delete_others',
        'delete_private',
        'delete_published',
    );

    // Site Admin
    $administrator = get_role( 'administrator' );

    foreach( $post_types as $post_type ):
        $administrator->add_cap( 'read_' . $post_type );
        $administrator->add_cap( 'edit_' . $post_type );
        $administrator->add_cap( 'delete_' . $post_type );
        foreach ( $caps as $cap ):
            $administrator->add_cap( $cap . '_' . $post_type . 's' );
        endforeach;
    endforeach;
}
add_action( 'admin_init', 'sportspress_scoreboards_admin_init' );

function sportspress_scoreboards_define_formats_global() {
	global $sportspress_formats;
	$sportspress_formats['calendar']['scoreboard'] = __( 'Scoreboard', 'sportspress' );
}
add_action( 'init', 'sportspress_scoreboards_define_formats_global', 20 );

function sportspress_scoreboards_save_post( $post_id ) {
	global $post, $typenow;
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return $post_id;
    if ( !current_user_can( 'edit_post', $post_id ) ) return $post_id;
	if ( !isset( $_POST['sportspress_nonce'] ) || ! wp_verify_nonce( $_POST['sportspress_nonce'], SPORTSPRESS_SCOREBOARDS_PLUGIN_BASENAME ) ) return $post_id;
	switch ( $_POST['post_type'] ):
		case ( 'sp_event' ):
			update_post_meta( $post_id, 'sp_scoreboard', sportspress_array_value( $_POST, 'sp_scoreboard', null ) );
			break;
	endswitch;
}
add_action( 'save_post', 'sportspress_scoreboards_save_post' );

function sportspress_scoreboards_event_format_meta( $post ) {
	$format = get_post_meta( $post->ID, 'sp_format', true );
	?>
	<div id="post-formats-select">
		<input type="radio" name="sp_format" class="post-format" id="post-format-league" value="league" <?php checked( true, ! $format || $format == 'league' ); ?>> <label for="post-format-league" class="post-format-icon post-format-league">League</label>
		<br><input type="radio" name="sp_format" class="post-format" id="post-format-scoreboard" value="scoreboard" <?php checked( 'scoreboard', $format ); ?>> <label for="post-format-scoreboard" class="post-format-icon post-format-scoreboard">Scoreboard</label>
		<br><input type="radio" name="sp_format" class="post-format" id="post-format-friendly" value="friendly" <?php checked( 'friendly', $format ); ?>> <label for="post-format-friendly" class="post-format-icon post-format-friendly">Friendly</label>
		<br>
	</div>
	<?php
}

function sportspress_scoreboards_admin_enqueue_scripts() {
	wp_enqueue_style( 'sportspress-scoreboards-admin', SPORTSPRESS_SCOREBOARDS_PLUGIN_URL . 'assets/css/admin.css', array( 'sportspress-admin' ), time() );
}
add_action( 'admin_enqueue_scripts', 'sportspress_scoreboards_admin_enqueue_scripts' );

function sportspress_scoreboards_activation_hook() {
    sportspress_scoreboard_post_init();
}
register_activation_hook( SPORTSPRESS_SCOREBOARDS_PLUGIN_FILE, 'sportspress_scoreboards_activation_hook' );
