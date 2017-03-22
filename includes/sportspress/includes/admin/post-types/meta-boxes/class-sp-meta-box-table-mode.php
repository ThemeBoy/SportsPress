<?php
/**
 * League Table Mode
 *
 * @author     ThemeBoy
 * @category   Admin
 * @package   SportsPress/Admin/Meta_Boxes
 * @version   2.3
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * SP_Meta_Box_Table_Mode
 */
class SP_Meta_Box_Table_Mode {

  /**
   * Output the metabox
   */
  public static function output( $post ) {
    $the_mode = sp_get_post_mode( $post->ID );
    ?>
    <div id="post-formats-select">
      <?php foreach ( array( 'team' => __( 'Team vs team', 'sportspress' ), 'player' => __( 'Player vs player', 'sportspress' ) ) as $key => $mode ): ?>
        <input type="radio" name="sp_mode" class="post-format" id="post-format-<?php echo $key; ?>" value="<?php echo $key; ?>" <?php checked( $the_mode, $key ); ?>> <label for="post-format-<?php echo $key; ?>" class="post-format-icon post-format-<?php echo $key; ?>"><?php echo $mode; ?></label><br>
      <?php endforeach; ?>
    </div>
    <?php
  }

  /**
   * Save meta box data
   */
  public static function save( $post_id, $post ) {
    update_post_meta( $post_id, 'sp_mode', sp_array_value( $_POST, 'sp_mode', 'team' ) );
  }
}