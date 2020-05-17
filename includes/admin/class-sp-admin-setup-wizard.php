<?php
/**
 * Setup Wizard Class
 *
 * Takes new users through some basic steps to setup their club website.
 *
 * Adapted from code in WooCommerce (Copyright (c) 2017, Automattic).
 *
 * @author      WooThemes
 * @category    Admin
 * @package     SportsPress/Admin
 * @version     2.6.15
*/
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

/**
 * SP_Admin_Setup_Wizard class.
 */
class SP_Admin_Setup_Wizard {

  /** @var string Current Step */
  private $step   = '';

  /** @var array Steps for the setup wizard */
  private $steps  = array();

  /** @var array Tweets user can optionally send after install */
  private $tweets = array(
    "Someone give me a high five, I just set up a new sports data website with #SportsPress and #WordPress!"
  );

  /**
   * Hook in tabs.
   */
  public function __construct() {
    if ( apply_filters( 'sportspress_enable_setup_wizard', true ) && current_user_can( 'manage_sportspress' ) ) {
      add_action( 'admin_menu', array( $this, 'admin_menus' ) );
      add_action( 'admin_init', array( $this, 'setup_wizard' ) );
    }
  }

  /**
   * Add admin menus/screens.
   */
  public function admin_menus() {
    add_dashboard_page( '', '', 'manage_options', 'sp-setup', '' );
  }

  /**
   * Show the setup wizard.
   */
  public function setup_wizard() {
    if ( empty( $_GET['page'] ) || 'sp-setup' !== $_GET['page'] ) {
      return;
    }
    $this->steps = array(
      'introduction' => array(
        'name'    =>  __( 'Introduction', 'sportspress' ),
        'view'    => array( $this, 'sp_setup_introduction' ),
        'handler' => ''
      ),
      'basics' => array(
        'name'    =>  __( 'Basic Setup', 'sportspress' ),
        'view'    => array( $this, 'sp_setup_basics' ),
        'handler' => array( $this, 'sp_setup_basics_save' )
      ),
      'teams' => array(
        'name'    =>  __( 'Teams', 'sportspress' ),
        'view'    => array( $this, 'sp_setup_teams' ),
        'handler' => array( $this, 'sp_setup_teams_save' )
      ),
      'players_staff' => array(
        'name'    =>  __( 'Players', 'sportspress' ) . ' &amp; ' . __( 'Staff', 'sportspress' ),
        'view'    => array( $this, 'sp_setup_players_staff' ),
        'handler' => array( $this, 'sp_setup_players_staff_save' ),
      ),
      'venue' => array(
        'name'    =>  __( 'Venue', 'sportspress' ),
        'view'    => array( $this, 'sp_setup_venue' ),
        'handler' => array( $this, 'sp_setup_venue_save' ),
      ),
      'pages' => array(
        'name'    =>  __( 'Pages', 'sportspress' ),
        'view'    => array( $this, 'sp_setup_pages' ),
        'handler' => array( $this, 'sp_setup_pages_save' )
      ),
      'next_steps' => array(
        'name'    =>  __( 'Ready!', 'sportspress' ),
        'view'    => array( $this, 'sp_setup_ready' ),
        'handler' => ''
      )
    );
    $this->step = isset( $_GET['step'] ) ? sanitize_key( $_GET['step'] ) : current( array_keys( $this->steps ) );
    $suffix     = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

    wp_enqueue_style( 'jquery-chosen', SP()->plugin_url() . '/assets/css/chosen.css', array(), '1.1.0' );
    wp_enqueue_style( 'sportspress-admin', SP()->plugin_url() . '/assets/css/admin.css', array(), SP_VERSION );
    wp_enqueue_style( 'sportspress-setup', SP()->plugin_url() . '/assets/css/setup.css', array( 'dashicons', 'install' ), SP_VERSION );

    wp_register_script( 'chosen', SP()->plugin_url() . '/assets/js/chosen.jquery.min.js', array( 'jquery' ), '1.1.0', true );
    wp_register_script( 'jquery-tiptip', SP()->plugin_url() . '/assets/js/jquery.tipTip.min.js', array( 'jquery' ), '1.3', true );
    wp_register_script( 'sportspress-setup', SP()->plugin_url() . '/assets/js/admin/sportspress-setup.js', array( 'jquery', 'chosen', 'jquery-tiptip' ), SP_VERSION, true );

    do_action( 'sp_setup_geocoder_scripts' );

    $strings = apply_filters( 'sportspress_localized_strings', array(
      'none' => __( 'None', 'sportspress' ),
      'remove_text' => __( '&mdash; Remove &mdash;', 'sportspress' ),
    ) );

    // Localize scripts
    wp_localize_script( 'sportspress-setup', 'localized_strings', $strings );

    if ( ! empty( $_POST['save_step'] ) && isset( $this->steps[ $this->step ]['handler'] ) ) {
      call_user_func( $this->steps[ $this->step ]['handler'] );
    }

    ob_start();
    $this->setup_wizard_header();
    $this->setup_wizard_steps();
    $this->setup_wizard_content();
    $this->setup_wizard_footer();
    exit;
  }

  public function get_next_step_link() {
    $keys = array_keys( $this->steps );
    return add_query_arg( 'step', $keys[ array_search( $this->step, array_keys( $this->steps ) ) + 1 ] );
  }

  /**
   * Setup Wizard Header.
   */
  public function setup_wizard_header() {
    ?>
    <!DOCTYPE html>
    <html <?php language_attributes(); ?>>
    <head>
      <meta name="viewport" content="width=device-width" />
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <title><?php _e( 'SportsPress', 'sportspress' ); ?> &rsaquo; <?php echo $this->steps[ $this->step ]['name']; ?></title>
      <?php do_action( 'admin_print_styles' ); ?>
      <?php do_action( 'admin_head' ); ?>
    </head>
    <body class="sp-setup wp-core-ui">
      <h1 id="sp-logo"><?php echo apply_filters( 'sportspress_logo', '<img src="' . plugin_dir_url( SP_PLUGIN_FILE ) . 'assets/images/modules/sportspress' . ( class_exists( 'SportsPress_Pro' ) ? '-pro' : '' ) . '.png" alt="' . __( 'SportsPress', 'sportspress' ) . '">' ); ?></h1>
    <?php
  }

  /**
   * Setup Wizard Footer.
   */
  public function setup_wizard_footer() {
    ?>
      <?php if ( 'next_steps' === $this->step ) : ?>
        <p class="sp-return-to-dashboard"><a href="<?php echo esc_url( admin_url( 'index.php?page=sp-about' ) ); ?>"><?php _e( 'Return to the WordPress Dashboard', 'sportspress' ); ?></a></p>
      <?php endif; ?>
      <?php wp_print_scripts( 'sportspress-setup' ); ?>
      </body>
    </html>
    <?php
  }

  /**
   * Output the steps.
   */
  public function setup_wizard_steps() {
    $ouput_steps = $this->steps;
    array_shift( $ouput_steps );
    ?>
    <ol class="sp-setup-steps">
      <?php foreach ( $ouput_steps as $step_key => $step ) : ?>
        <li class="<?php
          if ( $step_key === $this->step ) {
            echo 'active';
          } elseif ( array_search( $this->step, array_keys( $this->steps ) ) > array_search( $step_key, array_keys( $this->steps ) ) ) {
            echo 'done';
          }
        ?>"><?php echo esc_html( $step['name'] ); ?></li>
      <?php endforeach; ?>
    </ol>
    <?php
  }

  /**
   * Output the content for the current step.
   */
  public function setup_wizard_content() {
    echo '<div class="sp-setup-content">';
    call_user_func( $this->steps[ $this->step ]['view'] );
    echo '</div>';
  }

  /**
   * Introduction Step.
   */
  public function sp_setup_introduction() {
    ?>
    <h1><?php _e( 'Welcome to SportsPress', 'sportspress' ); ?></h1>
    <p><?php _e( 'Thank you for choosing SportsPress to power your sports website! This quick setup wizard will help you configure the basic settings. <strong>It’s completely optional and shouldn’t take longer than five minutes.</strong>', 'sportspress' ); ?></p>
    <p><?php _e( 'No time right now? If you don’t want to go through the wizard, you can skip and return to the WordPress dashboard. Come back anytime if you change your mind!', 'sportspress' ); ?></p>
    <p class="sp-setup-actions step">
      <a href="<?php echo esc_url( $this->get_next_step_link() ); ?>" class="button-primary button button-large button-next"><?php _e( 'Let\'s Go!', 'sportspress' ); ?></a>
      <a href="<?php echo esc_url( admin_url( 'index.php?page=sp-about' ) ); ?>" class="button button-large button-muted"><?php _e( 'Not right now', 'sportspress' ); ?></a>
    </p>
    <?php
  }

  /**
   * Basic Setup.
   */
  public function sp_setup_basics() {
    $class = 'chosen-select' . ( is_rtl() ? ' chosen-rtl' : '' );
    ?>
    <h1><?php _e( 'Basic Setup', 'sportspress' ); ?></h1>
    <form method="post">
      <p><?php _e( 'Select your timezone and sport to get started.', 'sportspress' ); ?></p>
      <table class="form-table" cellspacing="0">
        <tr>
          <th scope="row"><?php _e( 'Timezone', 'sportspress' ); ?> <i class="dashicons dashicons-editor-help sp-desc-tip" title="<?php _e( 'Choose a city in the same timezone as you.', 'sportspress' ); ?>"></i></th>
          <td>
            <select id="timezone_string" name="timezone_string" class="<?php echo esc_attr( $class ); ?>">
              <?php
              $current_offset = get_option('gmt_offset');
              $tzstring = get_option('timezone_string');

              $check_zone_info = true;

              // Remove old Etc mappings. Fallback to gmt_offset.
              if ( false !== strpos($tzstring,'Etc/GMT') )
                $tzstring = '';

              if ( empty($tzstring) ) { // Create a UTC+- zone if no timezone string exists
                $check_zone_info = false;
                if ( 0 == $current_offset )
                  $tzstring = 'UTC+0';
                elseif ($current_offset < 0)
                  $tzstring = 'UTC' . $current_offset;
                else
                  $tzstring = 'UTC+' . $current_offset;
              }
              echo wp_timezone_choice( $tzstring );
              ?>
            </select>
          </td>
        </tr>
        <tr>
          <th scope="row"><?php echo _x( 'Sport', 'Page title', 'sportspress' ); ?></th>
          <td>
            <?php
            $options = SP_Admin_Sports::get_preset_options();
            $default = apply_filters( 'sportspress_default_sport', 'soccer' );
            $sport = get_option( 'sportspress_sport', $default );
            if ( 'none' === $sport ) $sport = $default;
            $categories = SP_Admin_Sports::sport_category_names();
            ?>
            <select name="sport" id="sport" class="sp-select-sport <?php echo esc_attr( $class ); ?>">
              <?php
              foreach ( $options as $group => $options ) {
                ?>
                <optgroup label="<?php echo sp_array_value( $categories, $group, $group ); ?>">
                  <?php
                  foreach ( $options as $key => $val ) {
                    ?>
                    <option value="<?php echo esc_attr( $key ); ?>" <?php selected( $sport, $key ); ?>><?php echo $val ?></option>
                    <?php
                  }
                  ?>
                </optgroup>
                <?php
                }
              ?>
            </select>
          </td>
        </tr>
        <tr>
          <th scope="row"><?php _e( 'Main League', 'sportspress' ); ?> <i class="dashicons dashicons-editor-help sp-desc-tip" title="<?php _e( 'The name of a league or division.', 'sportspress' ); ?>"></i></th>
          <td>
            <input name="league" type="text" class="widefat" value="<?php _ex( 'Primary League', 'example', 'sportspress' ); ?>">
          </td>
        </tr>
        <tr>
          <th scope="row"><?php _e( 'Current Season', 'sportspress' ); ?></th>
          <td>
            <input name="season" type="text" class="widefat" value="<?php echo date( 'Y' ); ?>">
          </td>
        </tr>
      </table>

      <p class="sp-setup-actions step">
        <input type="submit" class="button-primary button button-large button-next" value="<?php esc_attr_e( 'Continue', 'sportspress' ); ?>" name="save_step" />
        <a href="<?php echo esc_url( $this->get_next_step_link() ); ?>" class="button button-large button-next button-muted"><?php _e( 'Skip this step', 'sportspress' ); ?></a>
        <?php wp_nonce_field( 'sp-setup' ); ?>
      </p>
    </form>
    <?php
  }

  /**
   * Save Basic Settings.
   */
  public function sp_setup_basics_save() {
    check_admin_referer( 'sp-setup' );

    // Update timezone
    $timezone_string = $_POST['timezone_string'];
    if ( ! empty( $timezone_string ) && preg_match( '/^UTC[+-]/', $timezone_string ) ) {
      $gmt_offset = $timezone_string;
      $gmt_offset = preg_replace( '/UTC\+?/', '', $gmt_offset );
      $timezone_string = '';
    }

    if ( isset( $timezone_string ) )
      update_option( 'timezone_string', $timezone_string );

    if ( isset( $gmt_offset ) )
      update_option( 'gmt_offset', $gmt_offset );

    // Update sport
    $sport = sanitize_text_field( $_POST['sport'] );
    if ( ! empty( $sport ) && get_option( 'sportspress_sport', null ) !== $sport ) {
      SP_Admin_Sports::apply_preset( $sport );
    }
    update_option( 'sportspress_sport', $sport );

    // Insert league
    $league = sanitize_text_field( $_POST['league'] );
    if ( ! is_string( $league ) || empty( $league ) ) {
      $league = _x( 'Primary League', 'example', 'sportspress' ); 
    }
    $inserted = wp_insert_term( $league, 'sp_league' );
    if ( ! is_wp_error( $inserted ) ) {
      update_option( 'sportspress_league', sp_array_value( $inserted, 'term_id', null ) );
    }

    // Insert season
    $season = sanitize_text_field( $_POST['season'] );
    if ( ! is_string( $season ) || empty( $season ) ) {
      $season = date( 'Y' ); 
    }
    $inserted = wp_insert_term( $season, 'sp_season' );
    if ( ! is_wp_error( $inserted ) ) {
      update_option( 'sportspress_season', sp_array_value( $inserted, 'term_id', null ) );
    }

    wp_redirect( esc_url_raw( $this->get_next_step_link() ) );
    exit;
  }

  /**
   * Team Setup.
   */
  public function sp_setup_teams() {
    $class = 'chosen-select' . ( is_rtl() ? ' chosen-rtl' : '' );
    ?>
    <h1><?php _e( 'Team Setup', 'sportspress' ); ?></h1>
    <form method="post">
      <p><?php _e( "Great! Now let's add some teams.", 'sportspress' ); ?></p>
      <table class="form-table" cellspacing="0">
        <tr>
          <th scope="row"><?php _e( 'Home Team', 'sportspress' ); ?></th>
          <td>
            <input name="home_team" type="text" class="widefat" placeholder="<?php _e( 'What is your team called?', 'sportspress' ); ?>">
          </td>
        </tr>
        <tr>
          <th scope="row"><?php _e( 'Rival Team', 'sportspress' ); ?></th>
          <td>
            <input name="away_team" type="text" class="widefat" placeholder="<?php _e( 'Who are you playing against next?', 'sportspress' ); ?>">
            <p class="description"><?php _e( "You can add more teams later.", 'sportspress' ); ?></p>
          </td>
        </tr>
      </table>

      <p class="sp-setup-actions step">
        <input type="submit" class="button-primary button button-large button-next" value="<?php esc_attr_e( 'Continue', 'sportspress' ); ?>" name="save_step" />
        <a href="<?php echo esc_url( $this->get_next_step_link() ); ?>" class="button button-large button-next button-muted"><?php _e( 'Skip this step', 'sportspress' ); ?></a>
        <?php wp_nonce_field( 'sp-setup' ); ?>
      </p>
    </form>
    <?php
  }

  /**
   * Save Team Settings.
   */
  public function sp_setup_teams_save() {
    check_admin_referer( 'sp-setup' );

    // Add away team
    $post['post_title'] = $_POST['away_team'];
    $post['post_type'] = 'sp_team';
    $post['post_status'] = 'publish';
    $post['tax_input'] = array();
    $taxonomies = array( 'sp_league', 'sp_season' );
    foreach ( $taxonomies as $taxonomy ) {
      $post['tax_input'][ $taxonomy ] = get_terms( $taxonomy, array( 'hide_empty' => 0, 'fields' => 'ids' ) );
    };
    wp_insert_post( $post );

    // Add home team
    $post['post_title'] = $_POST['home_team'];
    wp_insert_post( $post );

    wp_redirect( esc_url_raw( $this->get_next_step_link() ) );
    exit;
  }

  /**
   * Players & Staff Setup.
   */
  public function sp_setup_players_staff() {
    $positions = (array) get_terms( 'sp_position', array( 'hide_empty' => 0, 'orderby' => 'slug', 'fields' => 'names' ) )
    ?>
    <h1><?php esc_html_e( 'Player & Staff Setup', 'sportspress' ); ?></h1>
    <form method="post">
      <p><?php _e( "Let's add players and a staff member.", 'sportspress' ); ?></p>
      <table class="form-table" cellspacing="0">
        <tr>
          <th scope="row"><?php _e( 'Players', 'sportspress' ); ?> <i class="dashicons dashicons-editor-help sp-desc-tip" title="<?php _e( 'Enter a squad number, name, and position for each player.', 'sportspress' ); ?>"></i></th>
          <td>
            <ul>
              <?php for ( $i = 0; $i < 3; $i++ ) { ?>
                <li class="player"><input name="players[<?php echo $i; ?>][number]" type="text" class="player-number" placeholder="#" value="<?php echo $i + 1; ?>"> <input name="players[<?php echo $i; ?>][name]" type="text" placeholder="<?php _e( 'Name', 'sportspress' ); ?>"> <input name="players[<?php echo $i; ?>][position]" type="text" placeholder="<?php _e( 'Position', 'sportspress' ); ?>" <?php if ( sizeof( $positions ) ) { ?> value="<?php echo $positions[ $i % sizeof( $positions ) ]; ?>"<?php } ?>></li>
              <?php } ?>
            </ul>
            <p class="description"><?php _e( "You can add more players later.", 'sportspress' ); ?></p>
          </td>
        </tr>
        <tr>
          <th scope="row"><?php _e( 'Staff', 'sportspress' ); ?></th>
          <td>
            <ul>
              <li class="staff"><input name="staff" type="text" class="staff-name" placeholder="<?php _e( 'Name', 'sportspress' ); ?>"> <input name="role" type="text" placeholder="<?php _e( 'Job', 'sportspress' ); ?>" value="Coach"></li>
            </ul>
          </td>
        </tr>
      </table>

      <p class="sp-setup-actions step">
        <input type="submit" class="button-primary button button-large button-next" value="<?php esc_attr_e( 'Continue', 'sportspress' ); ?>" name="save_step" />
        <a href="<?php echo esc_url( $this->get_next_step_link() ); ?>" class="button button-large button-next button-muted"><?php _e( 'Skip this step', 'sportspress' ); ?></a>
        <?php wp_nonce_field( 'sp-setup' ); ?>
      </p>
    </form>
    <?php
  }

  /**
   * Save Player & Staff Settings.
   */
  public function sp_setup_players_staff_save() {
    check_admin_referer( 'sp-setup' );

    // Get home team
    $teams = (array) get_posts( array( 'posts_per_page' => 1, 'post_type' => 'sp_team', 'fields' => 'ids' ) );
    $team = reset( $teams );

    // Add players
    $post['post_type'] = 'sp_player';
    $post['post_status'] = 'publish';
    $post['tax_input'] = array();
    $taxonomies = array( 'sp_league', 'sp_season' );
    foreach ( $taxonomies as $taxonomy ) {
      $post['tax_input'][ $taxonomy ] = get_terms( $taxonomy, array( 'hide_empty' => 0, 'fields' => 'ids' ) );
    };
    if ( is_array( $_POST['players'] ) ) {
      foreach ( $_POST['players'] as $i => $player ) {
        if ( empty( $player['name'] ) ) continue;

        $post['post_title'] = $player['name'];
        $id = wp_insert_post( $post );

        // Add squad number
        $number = sp_array_value( $player, 'number' );
        update_post_meta( $id, 'sp_number', $number );

        // Add position
        wp_set_object_terms( $id, sp_array_value( $player, 'position', __( 'Position', 'sportspress' ) ), 'sp_position', false );

        // Add team
        if ( $team ) {
          update_post_meta( $id, 'sp_team', $team );
          update_post_meta( $id, 'sp_current_team', $team );
        }
      }
    }

    // Add staff
    if ( ! empty( $_POST['staff'] ) ) {

      $post['post_type'] = 'sp_staff';
      $post['post_title'] = $_POST['staff'];
      $id = wp_insert_post( $post );

      // Add role
      wp_set_object_terms( $id, sp_array_value( $_POST, 'role', 'Coach' ), 'sp_role', false );

      // Add team
      if ( $team ) {
        update_post_meta( $id, 'sp_team', $team );
        update_post_meta( $id, 'sp_current_team', $team );
      }
    }

    wp_redirect( esc_url_raw( $this->get_next_step_link() ) );
    exit;
  }

  /**
   * Venue Step.
   */
  public function sp_setup_venue() {
    do_action( 'sp_setup_venue_geocoder_scripts' );
    ?>
    <h1><?php _e( 'Venue Setup', 'sportspress' ); ?></h1>
    <form method="post">
      <p><?php _e( "Enter the details of your home venue.", 'sportspress' ); ?></p>
      <table class="form-table" cellspacing="0">
        <tr>
          <th scope="row"><?php _e( 'Name', 'sportspress' ); ?></th>
          <td>
            <input name="venue" type="text" placeholder="<?php _e( 'Venue', 'sportspress' ); ?>">
          </td>
        </tr>
        <tr>
          <th scope="row"><?php _e( 'Address', 'sportspress' ); ?></th>
          <td>
            <input name="address" id="sp_address" class="sp-address" type="text" value="Marvel Stadium, Melbourne">
            <div id="sp-location-picker" class="sp-location-picker" style="width: 95%; height: 320px"></div>
            <p class="description"><?php _e( "Drag the marker to the venue's location.", 'sportspress' ); ?></p>
            <input name="latitude" id="sp_latitude" class="sp-latitude" type="hidden" value="-37.8165647">
            <input name="longitude" id="sp_longitude" class="sp-longitude" type="hidden" value="144.9475055">
          </td>
        </tr>
      </table>

      <p class="sp-setup-actions step">
        <input type="submit" class="button-primary button button-large button-next" value="<?php esc_attr_e( 'Continue', 'sportspress' ); ?>" name="save_step" />
        <a href="<?php echo esc_url( $this->get_next_step_link() ); ?>" class="button button-large button-next button-muted"><?php _e( 'Skip this step', 'sportspress' ); ?></a>
        <?php wp_nonce_field( 'sp-setup' ); ?>
      </p>
    </form>
    <?php
    do_action( 'sp_admin_geocoder_scripts' );
  }

  /**
   * Venue Settings.
   */
  public function sp_setup_venue_save() {
    check_admin_referer( 'sp-setup' );

    // Get home team
    $teams = (array) get_posts( array( 'posts_per_page' => 1, 'post_type' => 'sp_team', 'fields' => 'ids' ) );
    $team = reset( $teams );

    // Insert venue
    $venue = sanitize_text_field( $_POST['venue'] );
    if ( ! is_string( $venue ) || empty( $venue ) ) {
      $venue = sp_array_value( $_POST, 'address', __( 'Venue', 'sportspress' ) );
    }
    $inserted = wp_insert_term( $venue, 'sp_venue' );

    // Add address and coordinates to venue
    if ( ! is_wp_error( $inserted ) ) {
      $t_id = sp_array_value( $inserted, 'term_id', 1 );

      if ( $team ) {
        wp_set_object_terms( $team, $t_id, 'sp_venue', true );
      }

      $meta = array(
        'sp_address' => sp_array_value( $_POST, 'address' ),
        'sp_latitude' => sp_array_value( $_POST, 'latitude' ),
        'sp_longitude' => sp_array_value( $_POST, 'longitude' ),
      );
      update_option( "taxonomy_$t_id", $meta );
    }

    wp_redirect( esc_url_raw( $this->get_next_step_link() ) );
    exit;
  }

  /**
   * Pages Step.
   */
  public function sp_setup_pages() {
    $pages = apply_filters( 'sportspress_setup_pages', array(
      'sp_calendar' => __( 'Organize and publish calendars using different layouts.', 'sportspress' ),
      'sp_table' => __( 'Create automated league tables to keep track of team standings.', 'sportspress' ),
      'sp_list' => __( 'Create team rosters, player galleries, and ranking charts.', 'sportspress' ),
    ) );
    ?>
    <h1><?php _e( 'Pages', 'sportspress' ); ?></h1>
    <form method="post">
      <p><?php printf( __( 'The following will be created automatically (if they do not already exist):', 'sportspress' ), '<a href="' . esc_url( admin_url( 'edit.php?post_type=page' ) ) . '" target="_blank">', '</a>' ); ?></p>
      <table class="form-table" cellspacing="0">
        <?php foreach ( $pages as $post_type => $description ) { ?>
          <?php
          $obj = get_post_type_object( $post_type );
          if ( ! is_object( $obj ) ) continue;
          ?>
          <tr>
            <th scope="row"><?php echo $obj->labels->singular_name; ?></th>
            <td><?php echo $description; ?></td>
          </tr>
        <?php } ?>
      </table>

      <p><?php printf( __( 'Once created, these pages can be managed from your admin dashboard.', 'sportspress' ), '<a href="' . esc_url( admin_url( 'edit.php?post_type=page' ) ) . '" target="_blank">', '</a>', '<a href="' . esc_url( admin_url( 'nav-menus.php' ) ) . '" target="_blank">', '</a>' ); ?></p>

      <p class="sp-setup-actions step">
        <input type="submit" class="button-primary button button-large button-next" value="<?php esc_attr_e( 'Continue', 'sportspress' ); ?>" name="save_step" />
        <a href="<?php echo esc_url( $this->get_next_step_link() ); ?>" class="button button-large button-next button-muted"><?php _e( 'Skip this step', 'sportspress' ); ?></a>
        <?php wp_nonce_field( 'sp-setup' ); ?>
      </p>
    </form>
    <?php
  }

  /**
   * Pages Settings.
   */
  public function sp_setup_pages_save() {
    check_admin_referer( 'sp-setup' );

    $pages = apply_filters( 'sportspress_setup_pages', array(
      'sp_calendar' => __( 'Organize and publish calendars using different layouts.', 'sportspress' ),
      'sp_table' => __( 'Create automated league tables to keep track of team standings.', 'sportspress' ),
      'sp_list' => __( 'Create team rosters, player galleries, and ranking charts.', 'sportspress' ),
    ) );

    // Initialize post
    $post = array( 'post_status' => 'publish' );
    $sample_content = _x( 'This is an example %1$s. As a new SportsPress user, you should go to <a href=\"%3$s\">your dashboard</a> to delete this %1$s and create new %2$s for your content. Have fun!', 'example', 'sportspress' );

    // Insert posts
    foreach ( $pages as $post_type => $description ) {
      $obj = get_post_type_object( $post_type );
      if ( ! is_object( $obj ) ) continue;

      // Skip if post exists
      $posts = get_posts( array( 'posts_per_page' => 1, 'post_type' => $post_type ) );
      if ( $posts ) continue;

      // Add post args
      $post['post_title'] = $obj->labels->singular_name;
      $post['post_type'] = $post_type;
      $post['post_content'] = sprintf( $sample_content, $obj->labels->singular_name, $obj->labels->name, add_query_arg( 'post_type', $post_type, admin_url( 'edit.php' ) ) );

      // Insert post
      $id = wp_insert_post( $post );

      // Flag as sample
      update_post_meta( $id, '_sp_sample', 1 );
    }

    wp_redirect( esc_url_raw( $this->get_next_step_link() ) );
    exit;
  }

  /**
   * Actions on the final step.
   */
  private function sp_setup_ready_actions() {
    delete_option( '_sp_needs_welcome' );
    update_option( 'sportspress_installed', 1 );
    update_option( 'sportspress_completed_setup', 1 );
    delete_transient( '_sp_activation_redirect' );

    // Check if first event already exists
    $events = get_posts(
      array(
        'post_type' => 'sp_event',
        'posts_per_page' => 1,
        'post_status' => 'draft',
        'meta_query' => array(
          array(
            'key' => '_sp_first',
            'value' => 1
          )
        )
      )
    );

    if ( $events ) {
      $event = reset( $events );
      return $event->ID;
    }

    // Get teams
    $team_post_type = 'sp_team';
    if ( 'player' === get_option( 'sportspress_mode', 'team' ) ) {
      $team_post_type = 'sp_player';
    }
    $teams = get_posts( array( 'posts_per_page' => 2, 'post_type' => $team_post_type ) );

    // Get players
    $players = (array) get_posts( array( 'posts_per_page' => 3, 'post_type' => 'sp_player', 'fields' => 'ids' ) );

    // Get staff
    $staff = (array) get_posts( array( 'posts_per_page' => 1, 'post_type' => 'sp_staff', 'fields' => 'ids' ) );

    // Initialize post
    $post['post_type'] = 'sp_event';
    $post['post_status'] = 'draft';
    $post['tax_input'] = array();

    // Add taxonomies
    $taxonomies = array( 'sp_league', 'sp_season', 'sp_venue' );
    foreach ( $taxonomies as $taxonomy ) {
      $post['tax_input'][ $taxonomy ] = get_terms( $taxonomy, array( 'hide_empty' => 0, 'fields' => 'ids', 'number' => 1 ) );
    };

    // Add post title
    if ( is_array( $teams ) && sizeof( $teams ) ) {
      $team_names = array();
      foreach ( $teams as $team ) {
        if ( ! $team ) continue;
        $team_names[] = $team->post_title;
      }
      $post['post_title'] = implode( ' ' . get_option( 'sportspress_event_teams_delimiter', 'vs' ) . ' ', $team_names );
    } else {
      $post['post_title'] = __( 'Event', 'sportspress' );
    }

    // Insert event
    $id = wp_insert_post( $post );

    // Add teams
    if ( is_array( $teams ) && sizeof( $teams ) ) {
      foreach ( $teams as $team ) {
        if ( ! $team ) continue;
        add_post_meta( $id, 'sp_team', $team->ID );
      }
    }

    // Add players
    add_post_meta( $id, 'sp_player', 0 );
    foreach ( $players as $player ) {
      if ( ! $player ) continue;
      add_post_meta( $id, 'sp_player', $player );
    }
    add_post_meta( $id, 'sp_player', 0 );

    update_post_meta( $id, '_sp_first', 1 );

    return $id;
  }

  /**
   * Final step.
   */
  public function sp_setup_ready() {
    $id = $this->sp_setup_ready_actions();
    shuffle( $this->tweets );

    $steps = apply_filters( 'sportspress_setup_wizard_next_steps', array(
      'first' => array(
        'label' => __( 'Next Steps', 'sportspress' ),
        'content' => '<a class="button button-primary button-large button-first-event" href="' . esc_url( admin_url( 'post.php?post=' . $id . '&action=edit' ) ) . '">' . __( 'Schedule your first event!', 'sportspress' ) . '</a>',
      ),
      'last' => array(
        'label' => __( 'Upgrade to Pro', 'sportspress' ),
        'content' => __( 'Get SportsPress Pro to get access to all modules. You can upgrade any time without losing any of your data.', 'sportspress' ) . ' <a href="' . apply_filters( 'sportspress_pro_url', 'http://tboy.co/pro' ) . '" target="_blank">' . __( 'Learn more', 'sportspress' ) . '</a>',
      ),
    ) );
    ?>
    <a href="https://twitter.com/share" class="twitter-share-button" data-url="http://tboy.co/sp" data-text="<?php echo esc_attr( $this->tweets[0] ); ?>" data-via="ThemeBoy" data-size="large">Tweet</a>
    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>

    <h1><?php _e( 'Thanks for installing!', 'sportspress' ); ?></h1>

    <div class="sp-banner"><img src="//ps.w.org/sportspress/assets/banner-772x250.png"></div>

    <div class="sp-setup-next-steps">
      <?php foreach ( $steps as $class => $step ) { ?>
        <div class="sp-setup-next-steps-<?php echo $class; ?>">
          <h2><?php echo $step['label']; ?></h2>
          <ul>
            <li><?php echo $step['content']; ?></li>
          </ul>
        </div>
      <?php } ?>
    </div>
    <?php
  }
}

new SP_Admin_Setup_Wizard();
