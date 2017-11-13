<?php
/**
 * Event Officials
 *
 * @author    Rob Tucker <rtucker-scs>
 * @author    ThemeBoy
 * @category  Admin
 * @package   SportsPress/Admin/Meta_Boxes
 * @version   2.5
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
    $duties = get_terms( array(
      'taxonomy' => 'sp_duty',
      'hide_empty' => false,
      'orderby' => 'meta_value_num',
      'meta_query' => array(
        'relation' => 'OR',
        array(
          'key' => 'sp_order',
          'compare' => 'NOT EXISTS'
        ),
        array(
          'key' => 'sp_order',
          'compare' => 'EXISTS'
        ),
      ),
    ) );

    $officials = (array) get_post_meta( $post->ID, 'sp_officials', true );

    foreach ( $duties as $duty ) {
      ?>
    	<p><strong><?php echo $duty->name; ?></strong></p>
      <p><?php
      $args = array(
        'post_type' => 'sp_official',
        'name' => 'sp_officials[' . $duty->term_id . '][]',
        'selected' => sp_array_value( $officials, $duty->term_id, array() ),
        'values' => 'ID',
        'placeholder' => sprintf( __( 'Select %s', 'sportspress' ), __( 'Officials', 'sportspress' ) ),
        'class' => 'widefat',
        'property' => 'multiple',
        'chosen' => true,
      );
      sp_dropdown_pages( $args );
      ?></p>
      <?php
    }
  }

  /**
   * Save meta box data
   */
  public static function save( $post_id, $post ) {
    update_post_meta( $post_id, 'sp_officials', sp_array_value( $_POST, 'sp_officials', array() ) );
  }
}
