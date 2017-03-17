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

    $crowdsourcing = array(
      1 => array(
        31 => array(
          'goals' => 0,
          'assists' => 1,
          'redcards' => 0,
        ),
        32 => array(
          'goals' => 1,
          'yellowcards' => 2,
          'redcards' => 1,
        ),
      ),
      2 => array(
        31 => array(
          'goals' => 1,
          'yellowcards' => 2,
          'redcards' => 1,
        ),
        32 => array(
          'goals' => 0,
          'assists' => 1,
          'redcards' => 0,
        ),
      ),
    );
    ?>
    <?php foreach ( $crowdsourcing as $user_id => $players ) { ?>
      <?php $user_data = get_userdata( $user_id ); ?>
      <div class="sp-crowdsourcing-user-container">
        <p><strong>
          <?php printf( __( 'Submitted by %s', 'sportspress' ), '<a href="' . get_edit_user_link( $user_id ) . '">' . $user_data->display_name . '</a>' ); ?>
        </strong></p>
        <div class="sp-data-table-container">
          <table class="widefat sp-data-table sp-crowdsourcing-table">
            <thead>
              <tr>
                <th><?php _e( 'Player', 'sportspress' ); ?></th>
                <?php foreach ( $labels as $key => $label ): ?>
                  <?php if ( 'equation' === sp_array_value( $formats, $key, 'number' ) ) continue; ?>
                  <th><?php echo $label; ?></th>
                <?php endforeach; ?>
                <th><?php _e( 'Actions', 'sportspress' ); ?></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ( $players as $player_id => $player_stats ) { ?>
                <tr class="sp-row sp-post sp-row-unapproved unapproved" data-sp-player="<?php echo $player_id; ?>">
                  <td><?php echo get_the_title( $player_id ); ?></td>
                  <?php foreach ( $labels as $key => $label ): ?>
                    <?php if ( 'equation' === sp_array_value( $formats, $key, 'number' ) ) continue; ?>
                    <?php $value = sp_array_value( $player_stats, $key, '' ); ?>
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
    <?php } ?>
    <p class="sp-crowdsourcing-save">
      <input name="save" type="submit" class="button button-primary button-large" value="<?php _e( 'Save Changes', 'sportspress' ); ?>">
    </p>
  <?php
  }
}

new SP_Crowdsourcing_Meta_Boxes();