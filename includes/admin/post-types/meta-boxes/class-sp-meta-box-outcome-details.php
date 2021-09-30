<?php
/**
 * Outcome Details
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Meta_Boxes
 * @version		2.6.15
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'SP_Meta_Box_Config' ) )
	include( 'class-sp-meta-box-config.php' );

/**
 * SP_Meta_Box_Outcome_Details
 */
class SP_Meta_Box_Outcome_Details extends SP_Meta_Box_Config {

	/**
	 * Output the metabox
	 */
	public static function output( $post ) {
		wp_nonce_field( 'sportspress_save_data', 'sportspress_meta_nonce' );
		global $pagenow;
		if ( 'post.php' == $pagenow && 'draft' !== get_post_status() ) {
			$readonly = true;
		} else {
			$readonly = false;
		}
		$abbreviation = get_post_meta( $post->ID, 'sp_abbreviation', true );
		$color = get_post_meta( $post->ID, 'sp_color', true );
		$condition = get_post_meta( $post->ID, 'sp_condition', true );
		$main_result = get_option( 'sportspress_primary_result', null );
		$result = get_page_by_path( $main_result, ARRAY_A, 'sp_result' );
		$label = sp_array_value( $result, 'post_title', __( 'Primary', 'sportspress' ) );
		
		if ( '' === $color ) $color = '#888888';
		?>
		<p><strong><?php _e( 'Variable', 'sportspress' ); ?></strong></p>
		<p>
			<input name="sp_default_key" type="hidden" id="sp_default_key" value="<?php echo $post->post_name; ?>">
			<input name="sp_key" type="text" id="sp_key" value="<?php echo $post->post_name; ?>"<?php if ( $readonly ) { ?> readonly="readonly"<?php } ?>>
		</p>
		<p><strong><?php _e( 'Abbreviation', 'sportspress' ); ?></strong></p>
		<p>
			<input name="sp_abbreviation" type="text" id="sp_abbreviation" value="<?php echo $abbreviation; ?>" placeholder="<?php echo sp_substr( $post->post_title, 0, 1 ); ?>">
		</p>
		<p><strong><?php _e( 'Color', 'sportspress' ); ?></strong></p>
		<p>
			<div class="sp-color-box">
				<input name="sp_color" id="sp_color" type="text" value="<?php echo $color; ?>" class="colorpick">
				<div id="sp_color" class="colorpickdiv"></div>
		    </div>
		</p>
		<p><strong><?php _e( 'Condition', 'sportspress' ); ?></strong></p>
		<p>
			<select name="sp_condition">
				<?php
				$options = array(
					'0' => '&mdash;',
					'>' => sprintf( __( 'Most %s', 'sportspress' ), $label ),
					'<' => sprintf( __( 'Least %s', 'sportspress' ), $label ),
					'=' => sprintf( __( 'Equal %s', 'sportspress' ), $label ),
					'else' => sprintf( __( 'Default', 'sportspress' ), $label ),
				);

				foreach ( $options as $key => $value ):
					printf( '<option value="%s" %s>%s</option>', $key, selected( true, $key == $condition, false ), $value );
				endforeach;
				?>
			</select>
		</p>
		<?php
	}

	/**
	 * Save meta box data
	 */
	public static function save( $post_id, $post ) {
		update_post_meta( $post_id, 'sp_abbreviation', sp_array_value( $_POST, 'sp_abbreviation', array() ) );
		update_post_meta( $post_id, 'sp_color', sp_array_value( $_POST, 'sp_color', array() ) );
		update_post_meta( $post_id, 'sp_condition', sp_array_value( $_POST, 'sp_condition', array() ) );
	}
}