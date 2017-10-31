<?php
/**
 * Event Officials
 *
 * @author     Rob Tucker <rtucker-scs>
 * @category   Admin
 * @package   SportsPress/Admin/Meta_Boxes
 * @version   2.3
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * SP_Meta_Box_Event_Officials
 */
class SP_Meta_Box_Event_Officials {

  /**
   * Output the metabox
   */
  public static function output( $post ) {
	wp_nonce_field( 'sportspress_save_data', 'sportspress_meta_nonce' );
	$abbreviation = get_post_meta( $post->ID, 'sp_abbreviation', true );
        $redirect = get_post_meta( $post->ID, 'sp_redirect', true );
        $url = get_post_meta( $post->ID, 'sp_url', true );
	if ( taxonomy_exists( 'sp_officials' ) ):
		$officials = get_the_terms( $post->ID, 'sp_officials' );
		$official_ids = array();
		if ( $officials ):
			foreach ( $officials as $official ):
				$official_ids[] = $official->term_id;
			endforeach;
		endif;
	endif;
  ?>
       <?php if ( taxonomy_exists( 'sp_officials' ) ) { ?>
	<p><strong><?php _e( 'Officials', 'sportspress' ); ?></strong></p>
	<p><?php
	$args = array(
		'taxonomy' => 'sp_officials',
		'name' => 'tax_input[sp_officials][]',
		'selected' => $official_ids,
		'values' => 'term_id',
		'placeholder' => sprintf( __( 'Select %s', 'sportspress' ), __( 'Officials', 'sportspress' ) ),
		'class' => 'widefat',
		'property' => 'multiple',
		'chosen' => true,
	);
	sp_dropdown_taxonomies( $args );
	?></p>
	<?php } ?>
    <?php
  }

  /**
   * Save meta box data
   */
  public static function save( $post_id, $post ) {
    update_post_meta( $post_id, 'sp_officials', sp_array_value( $_POST, 'sp_officials', 'official' ) );
  }
}
