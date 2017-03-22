<?php
/**
 * User Scores Meta Boxes
 *
 * @author    ThemeBoy
 * @category  Admin
 * @package   SportsPress_User_Scoress
 * @version   2.3
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * SP_User_Scores_Meta_Boxes
 */
class SP_User_Scores_Meta_Boxes {

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
    add_meta_box( 'sp_user_resultsdiv', __( 'User Results', 'sportspress' ), array( $this, 'user_results' ), 'sp_event', 'normal', 'high' );
    add_meta_box( 'sp_user_scoresdiv', __( 'User Scores', 'sportspress' ), array( $this, 'user_scores' ), 'sp_event', 'normal', 'high' );
  }

  /**
   * Output the user results metabox
   */
  public static function user_results( $post ) {
    $event = new SP_Event( $post );
    list( $labels, $usecolumns, $results ) = $event->results( true );

    $i = 0;
    $results = (array) get_post_meta( $post->ID, 'sp_user_results', true );
    ?>
    <?php foreach ( $results as $user_id => $teams ) { ?>
      <?php
      if ( ! is_array( $teams ) ) continue;
      $teams = array_filter( $teams );
      if ( ! sizeof( $teams ) ) continue;
      ?>
      <div class="sp-user-results-user-container">
        <p><strong>
          <?php
          if ( $user_id ) {
            $user_data = get_userdata( $user_id );
            if ( is_object( $user_data ) ) {
              $display_name = '<a href="' . get_edit_user_link( $user_id ) . '">' . $user_data->display_name . '</a>';
            } else {
              $display_name = __( 'User', 'sportspress' );
            }
          } else {
            $display_name = __( 'Guest', 'sportspress' );
          }
          ?>
          <?php printf( __( 'Submitted by %s', 'sportspress' ), $display_name ); ?>
        </strong></p>
        <div class="sp-data-table-container">
          <table class="widefat sp-data-table sp-user-results-table">
            <thead>
              <tr>
                <th><?php _e( 'Team', 'sportspress' ); ?></th>
                <?php foreach ( $labels as $key => $label ): ?>
                  <th><?php echo $label; ?></th>
                <?php endforeach; ?>
                <th><?php _e( 'Actions', 'sportspress' ); ?></th>
              </tr>
            </thead>
            <tbody>
              <?php $r = 0; ?>
              <?php foreach ( $teams as $team_id => $team ) { ?>
                <tr class="sp-row sp-post sp-row-unapproved unapproved<?php if ( $r % 2 == 0 ) { ?> alternate<?php } ?>" data-sp-team="<?php echo $team_id; ?>" data-sp-user="<?php echo $user_id; ?>">
                  <td><?php echo get_the_title( $team_id ); ?></td>
                  <?php foreach ( $labels as $key => $label ): ?>
                    <?php $value = sp_array_value( $team, $key, '' ); ?>
                    <td class="sp-row-stat" data-sp-key="<?php echo $key; ?>" data-sp-value="<?php echo esc_html( $value ); ?>"><?php echo '' == $value ? '-' : $value; ?></td>
                  <?php endforeach; ?>
                  <td>
                    <div class="row-actions sp-row-actions">
                      <span class="approve"><a href="#approve" data-sp-action="approve" class="sp-row-approve vim-a"><?php _e( 'Approve', 'sportspress' ); ?></a></span>
                      <span class="trash"> | <a href="#reject" data-sp-action="reject" class="sp-row-reject delete vim-d vim-destructive"><?php _e( 'Reject', 'sportspress' ); ?></a></span>
                    </div>
                  </td>
                </tr>
              <?php $r++; } ?>
            </tbody>
          </table>
        </div>
      </div>
    <?php $i++; } ?>

    <?php if ( $i ) { ?>
      <p class="sp-user-results-save">
        <input name="save" type="submit" class="button button-primary button-large" value="<?php _e( 'Save Changes', 'sportspress' ); ?>">
      </p>
    <?php } else { ?>
      <p class="description"><?php _e( 'Submitted results will appear here.', 'sportspress' ); ?></p>
    <?php
    }
  }

  /**
   * Output the user scores metabox
   */
  public static function user_scores( $post ) {
    $event = new SP_Event( $post );
    list( $labels, $columns, $stats, $teams, $formats, $order, $timed ) = $event->performance( true );

    $i = 0;
    $scores = (array) get_post_meta( $post->ID, 'sp_user_scores', true );
    ?>
    <?php foreach ( $scores as $user_id => $players ) { ?>
      <?php
      if ( ! is_array( $players ) ) continue;
      $players = array_filter( $players );
      if ( ! sizeof( $players ) ) continue;
      ?>
      <div class="sp-user-scores-user-container">
        <p><strong>
          <?php
          if ( $user_id ) {
            $user_data = get_userdata( $user_id );
            if ( is_object( $user_data ) ) {
              $display_name = '<a href="' . get_edit_user_link( $user_id ) . '">' . $user_data->display_name . '</a>';
            } else {
              $display_name = __( 'User', 'sportspress' );
            }
          } else {
            $display_name = __( 'Guest', 'sportspress' );
          }
          ?>
          <?php printf( __( 'Submitted by %s', 'sportspress' ), $display_name ); ?>
        </strong></p>
        <div class="sp-data-table-container">
          <table class="widefat sp-data-table sp-user-scores-table">
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
              <?php $r = 0; ?>
              <?php foreach ( $players as $player_id => $player ) { ?>
                <tr class="sp-row sp-post sp-row-unapproved unapproved<?php if ( $r % 2 == 0 ) { ?> alternate<?php } ?>" data-sp-player="<?php echo $player_id; ?>" data-sp-user="<?php echo $user_id; ?>">
                  <td><?php echo get_the_title( $player_id ); ?></td>
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
              <?php $r++; } ?>
            </tbody>
          </table>
        </div>
      </div>
    <?php $i++; } ?>

    <?php if ( $i ) { ?>
      <p class="sp-user-scores-save">
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
    if ( isset( $_POST['sp_user_results_remove'] ) ) {

      $meta = (array) get_post_meta( $post_id, 'sp_user_results', true );
      $entries = $_POST['sp_user_results_remove'];

      if ( is_array( $entries ) ) {
        foreach ( $entries as $user_id => $teams ) {
          if ( ! is_array( $teams ) ) continue;

          foreach ( $teams as $team_id ) {
            unset( $meta[ $user_id ][ $team_id ] );
          }
        }
        update_post_meta( $post_id, 'sp_user_results', $meta );
      }
    }

    if ( isset( $_POST['sp_user_scores_remove'] ) ) {

      $meta = (array) get_post_meta( $post_id, 'sp_user_scores', true );
      $entries = $_POST['sp_user_scores_remove'];

      if ( is_array( $entries ) ) {
        foreach ( $entries as $user_id => $players ) {
          if ( ! is_array( $players ) ) continue;

          foreach ( $players as $player_id ) {
            unset( $meta[ $user_id ][ $player_id ] );
          }
        }
        update_post_meta( $post_id, 'sp_user_scores', $meta );
      }
    }
  }
}

new SP_User_Scores_Meta_Boxes();