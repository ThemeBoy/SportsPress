<?php
/*
Plugin Name: SportsPress User Registration
Plugin URI: http://themeboy.com/
Description: Create a new player during user registration.
Author: ThemeBoy
Author URI: http://themeboy.com/
Version: 2.3
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_User_Registration' ) ) :

/**
 * Main SportsPress User Registration Class
 *
 * @class SportsPress_User_Registration
 * @version  2.3
 */
class SportsPress_User_Registration {

  /**
   * Constructor
   */
  public function __construct() {
    // Define constants
    $this->define_constants();

    // Hooks
    add_filter( 'sportspress_player_options', array( $this, 'add_player_options' ) );
    add_action( 'register_form', array( $this, 'register_form' ) );
    add_action( 'user_register', array( $this, 'user_register' ) );
  }

  /**
   * Define constants.
  */
  private function define_constants() {
    if ( !defined( 'SP_USER_REGISTRATION_VERSION' ) )
      define( 'SP_USER_REGISTRATION_VERSION', '2.3' );

    if ( !defined( 'SP_USER_REGISTRATION_URL' ) )
      define( 'SP_USER_REGISTRATION_URL', plugin_dir_url( __FILE__ ) );

    if ( !defined( 'SP_USER_REGISTRATION_DIR' ) )
      define( 'SP_USER_REGISTRATION_DIR', plugin_dir_path( __FILE__ ) );
  }

  /**
   * Add options to player settings page.
   *
   * @return array
   */
  public function add_player_options( $options ) {
    $options = array_merge( $options, array(
      array(
        'title'     => __( 'User Registration', 'sportspress' ),
        'desc'     => __( 'Add name fields to signup form', 'sportspress' ),
        'id'     => 'sportspress_registration_name_inputs',
        'default'  => 'no',
        'type'     => 'checkbox',
        'checkboxgroup'    => 'start',
      ),

      array(
        'desc'     => __( 'Create player profiles for new users', 'sportspress' ),
        'id'     => 'sportspress_registration_add_player',
        'default'  => 'no',
        'type'     => 'checkbox',
        'checkboxgroup'    => 'end',
      ),
    ) );

    return $options;
  }

  /**
   * Add name fields to user registration form.
   */
  public static function register_form() {
    if ( 'yes' === get_option( 'sportspress_registration_name_inputs', 'no' ) ) {
      $first_name = ( ! empty( $_POST['first_name'] ) ) ? trim( $_POST['first_name'] ) : '';
      $last_name = ( ! empty( $_POST['last_name'] ) ) ? trim( $_POST['last_name'] ) : '';
      ?>
      <p>
          <label for="first_name"><?php _e( 'First Name', 'themeboy' ) ?><br />
              <input type="text" name="first_name" id="first_name" class="input" value="<?php echo esc_attr( wp_unslash( $first_name ) ); ?>" size="25" /></label>
      </p>

      <p>
          <label for="last_name"><?php _e( 'Last Name', 'themeboy' ) ?><br />
              <input type="text" name="last_name" id="last_name" class="input" value="<?php echo esc_attr( wp_unslash( $last_name ) ); ?>" size="25" /></label>
      </p>
      <?php
    }
  }

  /**
   * Save fields and add player during user registration.
   */
  public static function user_register( $user_id ) {
    $parts = array();

    // Save first and last name
    if ( 'yes' === get_option( 'sportspress_registration_name_inputs', 'no' ) ) {
      if ( ! empty( $_POST['first_name'] ) ) {
        $meta = trim( $_POST['first_name'] );
        $parts[] = $meta;
        update_user_meta( $user_id, 'first_name', $meta );
      }

      if ( ! empty( $_POST['last_name'] ) ) {
        $meta = trim( $_POST['last_name'] );
        $parts[] = $meta;
        update_user_meta( $user_id, 'last_name', $meta );
      }
    }

    // Add player
    if ( 'yes' === get_option( 'sportspress_registration_add_player', 'no' ) ) {
      if ( ! sizeof( $parts ) && ! empty( $_POST['user_login'] ) ) {
        $parts[] = trim( $_POST['user_login'] );
      }

      if ( sizeof( $parts ) ) {
        $name = implode( ' ', $parts );
        $post['post_type'] = 'sp_player';
        $post['post_title'] = trim( $name );
        $post['post_author'] = $user_id;
        $post['post_status'] = 'publish';
        $id = wp_insert_post( $post );
      }
    }
  }
}

endif;

new SportsPress_User_Registration();
