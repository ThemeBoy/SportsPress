<?php
/**
 * Player Transfers
 *
 * @author    Savvas <savvasha>
 * @author    ThemeBoy
 * @category  Admin
 * @package   SportsPress/Admin/Meta_Boxes
 * @version   2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * SP_Meta_Box_Player_Transfers
 */
class SP_Meta_Box_Player_Transfers {

  /**
   * Output the metabox
   */
  public static function output( $post ) {
    echo 'test';

  /**
   * Save meta box data
   */
  public static function save( $post_id, $post ) {
    update_post_meta( $post_id, 'sp_transfers', sp_array_value( $_POST, 'sp_transfers', array() ) );
  }
}
