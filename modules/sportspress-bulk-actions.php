<?php
/*
Plugin Name: SportsPress Bulk Actions
Plugin URI: http://themeboy.com/
Description: Add bulk actions to SportsPress.
Author: ThemeBoy
Author URI: http://themeboy.com/
Version: 2.7
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_Bulk_Actions' ) ) :

/**
 * Main SportsPress Bulk Actions Class
 *
 * @class SportsPress_Bulk_Actions
 * @version 2.7
 */
class SportsPress_Bulk_Actions {

  /**
   * Constructor
   */
  public function __construct() {
    // Define constants
    $this->define_constants();

    // Teams
    add_filter( 'bulk_actions-edit-sp_team', array( $this, 'team_actions' ) );
    add_filter( 'handle_bulk_actions-edit-sp_team', array( $this, 'team_actions_handler' ), 10, 3 );

    // Events
    add_filter( 'bulk_actions-edit-sp_event', array( $this, 'event_actions' ) );
    add_filter( 'handle_bulk_actions-edit-sp_event', array( $this, 'event_actions_handler' ), 10, 3 );

    // Notices
    add_action( 'admin_notices', array( $this, 'admin_notices' ) );
  }

  /**
   * Define constants.
  */
  private function define_constants() {
    if ( !defined( 'SP_BULK_ACTIONS_VERSION' ) )
      define( 'SP_BULK_ACTIONS_VERSION', '2.7' );

    if ( !defined( 'SP_BULK_ACTIONS_URL' ) )
      define( 'SP_BULK_ACTIONS_URL', plugin_dir_url( __FILE__ ) );

    if ( !defined( 'SP_BULK_ACTIONS_DIR' ) )
      define( 'SP_BULK_ACTIONS_DIR', plugin_dir_path( __FILE__ ) );
  }

  /**
   * Add option to the team bulk actions dropdown.
   */
  public function team_actions( $bulk_actions ) {
    $bulk_actions['sp_calendar'] = __( 'Generate Calendars', 'sportspress' );
    return $bulk_actions;
  }

  /**
   * Handle form submission for team bulk actions.
   */
  public function team_actions_handler( $redirect_to, $doaction, $post_ids ) {
    if ( $doaction !== 'sp_calendar' ) {
      return $redirect_to;
    }

    foreach ( $post_ids as $post_id ) {
      $post = array();
      $post['post_title'] = get_the_title( $post_id ) . ' ' . __( 'Calendar', 'sportspress' );
      $post['post_type'] = 'sp_calendar';
      $post['post_status'] = 'publish';

      // Insert post
      $id = wp_insert_post( $post );

      // Flag as bulk
      update_post_meta( $id, '_sp_bulk', 1 );

      // Update meta
      update_post_meta( $id, 'sp_team', $post_id );
      update_post_meta( $id, 'sp_format', 'calendar' );
    }

    $redirect_to = add_query_arg( 'sp_bulk_generated_calendars', count( $post_ids ), $redirect_to );
    return $redirect_to;
  }

  /**
   * Add option to the event bulk actions dropdown.
   */
  public function event_actions( $bulk_actions ) {
    $bulk_actions['sp_postpone'] = __( 'Postpone events', 'sportspress' );
    $bulk_actions['sp_cancel'] = __( 'Cancel events', 'sportspress' );
    return $bulk_actions;
  }

  /**
   * Handle form submission for event bulk actions.
   */
  public function event_actions_handler( $redirect_to, $doaction, $post_ids ) {
    if ( ! in_array( $doaction, array( 'sp_postpone', 'sp_cancel' ) ) ) {
      return $redirect_to;
    }

    if ( 'sp_postpone' == $doaction ) {
      foreach ( $post_ids as $post_id ) {
        update_post_meta( $post_id, 'sp_status', 'postponed' );
      }
      $redirect_to = add_query_arg( 'sp_bulk_postponed_events', count( $post_ids ), $redirect_to );
    } elseif ( 'sp_cancel' == $doaction ) {
      foreach ( $post_ids as $post_id ) {
        update_post_meta( $post_id, 'sp_status', 'cancelled' );
      }
      $redirect_to = add_query_arg( 'sp_bulk_cancelled_events', count( $post_ids ), $redirect_to );
    }

    return $redirect_to;
  }

  /**
   * Display notices after form submission.
   */
  public function admin_notices() {
    if ( ! empty( $_REQUEST['sp_bulk_generated_calendars'] ) ) {
      $count = intval( $_REQUEST['sp_bulk_generated_calendars'] );

      printf( '<div id="message" class="updated notice notice-success is-dismissible"><p>' .
        _n( 'Generated %s calendar.',
        'Generated %s calendars.',
        $count,
        'sportspress'
      ) . ' <a href="' . admin_url('edit.php?post_type=sp_calendar') . '">' . __( 'View', 'sportspress' ) . '</a></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>', $count );
    } elseif ( ! empty( $_REQUEST['sp_bulk_postponed_events'] ) ) {
      $count = intval( $_REQUEST['sp_bulk_postponed_events'] );

      printf( '<div id="message" class="updated notice notice-success is-dismissible"><p>' .
        _n( 'Postponed %s event.',
        'Postponed %s events.',
        $count,
        'sportspress'
      ) . '</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>', $count );
    } elseif ( ! empty( $_REQUEST['sp_bulk_cancelled_events'] ) ) {
      $count = intval( $_REQUEST['sp_bulk_cancelled_events'] );

      printf( '<div id="message" class="updated notice notice-success is-dismissible"><p>' .
        _n( 'Canceled %s event.',
        'Canceled %s events.',
        $count,
        'sportspress'
      ) . '</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>', $count );
    }
  }
}

endif;

new SportsPress_Bulk_Actions();
