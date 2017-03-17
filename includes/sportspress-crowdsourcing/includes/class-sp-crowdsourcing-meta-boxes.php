<?php
/**
 * Crowdsourcing Meta Boxes
 *
 * @author    ThemeBoy
 * @category  Admin
 * @package   SportsPress_Crowdsourcings
 * @version   2.2.11
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * SP_Crowdsourcing_Meta_Boxes
 */
class SP_Crowdsourcing_Meta_Boxes {

  /**
   * Constructor
   */
  public function __construct() {
    add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ), 30 );
    add_action( 'sportspress_process_sp_event_meta', array( $this, 'save' ) );
  }

  /**
   * Add Meta boxes
   */
  public function add_meta_boxes() {
    add_meta_box( 'sp_crowdsourcingdiv', __( 'Crowdsourcing', 'sportspress' ), array( $this, 'crowdsourcing' ), 'sp_event', 'normal', 'high' );
  }

  /**
   * Output the crowdsourcing metabox
   */
  public static function crowdsourcing( $post ) {
    $event = new SP_Event( $post );
    list( $labels, $columns, $stats, $teams, $formats, $order, $timed ) = $event->performance( true );

    $i = 0;
    $crowdsourcing = (array) get_post_meta( $post->ID, 'sp_crowdsourcing', true );
    ?>
    <?php foreach ( $crowdsourcing as $user_id => $players ) { ?>
      <?php
      if ( ! is_array( $players ) ) continue;
      $players = array_filter( $players );
      if ( ! sizeof( $players ) ) continue;
      ?>
      <div class="sp-crowdsourcing-user-container">
        <p><strong>
          <?php
          if ( $user_id ) {
            $user_data = get_userdata( $user_id );
            $display_name = '<a href="' . get_edit_user_link( $user_id ) . '">' . $user_data->display_name . '</a>';
          } else {
            $display_name = __( 'Guest', 'sportspress' );
          }
          ?>
          <?php printf( __( 'Submitted by %s', 'sportspress' ), $display_name ); ?>
        </strong></p>
        <div class="sp-data-table-container">
          <table class="widefat sp-data-table sp-crowdsourcing-table">
            <thead>
              <tr>
                <th><?php _e( 'Submitted on', 'sportspress' ); ?></th>
                <th><?php _e( 'Player', 'sportspress' ); ?></th>
                <?php foreach ( $labels as $key => $label ): ?>
                  <?php if ( 'equation' === sp_array_value( $formats, $key, 'number' ) ) continue; ?>
                  <th><?php echo $label; ?></th>
                <?php endforeach; ?>
                <th><?php _e( 'Actions', 'sportspress' ); ?></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ( $players as $i => $player ) { ?>
                <tr class="sp-row sp-post sp-row-unapproved unapproved" data-sp-player="<?php echo $player['_player_id']; ?>" data-sp-user="<?php echo $user_id; ?>" data-sp-index="<?php echo $i; ?>">
                  <td><?php echo date_i18n( 'M j, Y @ H:i', strtotime( $player['_timestamp'] ) ); ?></td>
                  <td><?php echo get_the_title( $player['_player_id'] ); ?></td>
                  <?php foreach ( $labels as $key => $label ): ?>
                    <?php if ( 'equation' === sp_array_value( $formats, $key, 'number' ) ) continue; ?>
                    <?php $value = sp_array_value( $player, $key, '' ); ?>
                    <td class="sp-row-stat" data-sp-key="<?php echo $key; ?>" data-sp-value="<?php echo esc_html( $value ); ?>"><?php echo '' == $value ? '-' : $value; ?></td>
                  <?php endforeach; ?>
                  <td>
                    <div class="row-actions sp-row-actions">
                      <span class="approve"><a href="#approve" data-sp-action="approve" class="sp-row-approve vim-a"><?php _e( 'Approve', 'sportspress' ); ?></a></span>
                      <span class="trash"> | <a href="#reject" data-sp-action="reject" class="sp-row-reject delete vim-d vim-destructive"><?php _e( 'Reject', 'sportspress' ); ?></a></span>
                    </div>
                  </td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    <?php $i++; } ?>

    <?php if ( $i ) { ?>
      <p class="sp-crowdsourcing-save">
        <input name="save" type="submit" class="button button-primary button-large" value="<?php _e( 'Save Changes', 'sportspress' ); ?>">
      </p>
    <?php } else { ?>
      <p class="description"><?php _e( 'Submitted scores will appear here.', 'sportspress' ); ?></p>
    <?php
    }
  }

  /**
   * Save meta boxes data
   */
  public static function save( $post_id ) {
    if ( ! isset( $_POST['sp_crowdsourcing_remove'] ) ) return;

    $meta = (array) get_post_meta( $post_id, 'sp_crowdsourcing', true );
    $entries = $_POST['sp_crowdsourcing_remove'];

    if ( ! is_array( $entries ) ) return;

    foreach ( $entries as $user_id => $indexes ) {
      if ( ! is_array( $indexes ) ) continue;

      foreach ( $indexes as $index ) {
        unset( $meta[ $user_id ][ $index ] );
      }
    }
    update_post_meta( $post_id, 'sp_crowdsourcing', $meta );
  }
}

new SP_Crowdsourcing_Meta_Boxes();