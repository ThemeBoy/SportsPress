<?php
/**
 * Sponsor Details
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress Sponsors
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * SP_Meta_Box_Sponsor_Details
 */
class SP_Meta_Box_Sponsor_Details {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ), 30 );
		add_action( 'save_post', array( $this, 'save' ), 1, 2 );
	}

	/**
	 * Add Meta box
	 */
	public function add_meta_box() {
		add_meta_box( 'sp_detailsdiv', __( 'Details', 'sportspress' ), array( $this, 'output' ), 'sp_sponsor', 'side', 'default' );
	}

	/**
	 * Output the metabox
	 */
	public static function output( $post ) {
		wp_nonce_field( 'sportspress_save_data', 'sportspress_meta_nonce' );
		$url = get_post_meta( $post->ID, 'sp_url', true );
		$impressions = get_post_meta( $post->ID, 'sp_impressions', true );
		$clicks = get_post_meta( $post->ID, 'sp_clicks', true );
		?>
		<p><strong><?php _e( 'Site URL', 'sportspress' ); ?></strong></p>
		<p><input type="text" class="widefat" id="sp_url" name="sp_url" value="<?php echo $url; ?>"></p>
		<?php if ( $url ): ?>
			<p><a class="sp-sponsor-link" title="<?php _e( 'Visit Site', 'sportspress' ); ?>" href="<?php echo $url; ?>" target="_blank"><?php _e( 'Visit Site', 'sportspress' ); ?></a></p>
		<?php endif; ?>

		<p><strong><?php _e( 'Impressions', 'sportspress' ); ?></strong></p>
		<p><input type="text" class="widefat" id="sp_impressions" name="sp_impressions" value="<?php echo $impressions; ?>" readonly="readonly"></p>

		<p><strong><?php _e( 'Clicks', 'sportspress' ); ?></strong></p>
		<p><input type="text" class="widefat" id="sp_clicks" name="sp_clicks" value="<?php echo $clicks; ?>" readonly="readonly"></p>
		<?php
	}

	/**
	 * Save meta box data
	 */
	public static function save( $post_id, $post ) {
		if ( empty( $post_id ) || empty( $post ) ) return;
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
		if ( is_int( wp_is_post_revision( $post ) ) ) return;
		if ( is_int( wp_is_post_autosave( $post ) ) ) return;
		if ( empty( $_POST['sportspress_meta_nonce'] ) || ! wp_verify_nonce( $_POST['sportspress_meta_nonce'], 'sportspress_save_data' ) ) return;
		if ( ! current_user_can( 'edit_post', $post_id  )) return;
		if ( 'sp_sponsor' != $post->post_type ) return;

		update_post_meta( $post_id, 'sp_url', sp_array_value( $_POST, 'sp_url', '' ) );
	}
}

new SP_Meta_Box_Sponsor_Details();
