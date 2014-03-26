<?php
/**
 * SportsPress Email Settings
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin
 * @version     0.7
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'SP_Settings_Emails' ) ) :

/**
 * SP_Settings_Emails
 */
class SP_Settings_Emails extends SP_Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id    = 'email';
		$this->label = __( 'Emails', 'sportspress' );

		add_filter( 'sportspress_settings_tabs_array', array( $this, 'add_settings_page' ), 20 );
		add_action( 'sportspress_sections_' . $this->id, array( $this, 'output_sections' ) );
		add_action( 'sportspress_settings_' . $this->id, array( $this, 'output' ) );
		add_action( 'sportspress_settings_save_' . $this->id, array( $this, 'save' ) );
	}

	/**
	 * Get sections
	 *
	 * @return array
	 */
	public function get_sections() {
		$sections = array(
			''         => __( 'Email Options', 'sportspress' )
		);

		// Define emails that can be customised here
		$mailer 			= SP()->mailer();
		$email_templates 	= $mailer->get_emails();

		foreach ( $email_templates as $email ) {
			$title = empty( $email->title ) ? ucfirst( $email->id ) : ucfirst( $email->title );

			$sections[ strtolower( get_class( $email ) ) ] = esc_html( $title );
		}

		return $sections;
	}

	/**
	 * Get settings array
	 *
	 * @return array
	 */
	public function get_settings() {
		return apply_filters('sportspress_email_settings', array(

			array( 'type' => 'sectionend', 'id' => 'email_recipient_options' ),

			array(	'title' => __( 'Email Sender Options', 'sportspress' ), 'type' => 'title', 'desc' => __( 'The following options affect the sender (email address and name) used in SportsPress emails.', 'sportspress' ), 'id' => 'email_options' ),

			array(
				'title' => __( '"From" Name', 'sportspress' ),
				'desc' 		=> '',
				'id' 		=> 'sportspress_email_from_name',
				'type' 		=> 'text',
				'css' 		=> 'min-width:300px;',
				'default'	=> esc_attr(get_bloginfo('title')),
				'autoload'      => false
			),

			array(
				'title' => __( '"From" Email Address', 'sportspress' ),
				'desc' 		=> '',
				'id' 		=> 'sportspress_email_from_address',
				'type' 		=> 'email',
				'custom_attributes' => array(
					'multiple' 	=> 'multiple'
				),
				'css' 		=> 'min-width:300px;',
				'default'	=> get_option('admin_email'),
				'autoload'      => false
			),

			array( 'type' => 'sectionend', 'id' => 'email_options' ),

			array(	'title' => __( 'Email Template', 'sportspress' ), 'type' => 'title', 'desc' => sprintf(__( 'This section lets you customise the SportsPress emails. <a href="%s" target="_blank">Click here to preview your email template</a>. For more advanced control copy <code>sportspress/templates/emails/</code> to <code>yourtheme/sportspress/emails/</code>.', 'sportspress' ), wp_nonce_url(admin_url('?preview_sportspress_mail=true'), 'preview-mail')), 'id' => 'email_template_options' ),

			array(
				'title' => __( 'Header Image', 'sportspress' ),
				'desc' 		=> sprintf(__( 'Enter a URL to an image you want to show in the email\'s header. Upload your image using the <a href="%s">media uploader</a>.', 'sportspress' ), admin_url('media-new.php')),
				'id' 		=> 'sportspress_email_header_image',
				'type' 		=> 'text',
				'css' 		=> 'min-width:300px;',
				'default'	=> '',
				'autoload'  => false
			),

			array(
				'title' => __( 'Email Footer Text', 'sportspress' ),
				'desc' 		=> __( 'The text to appear in the footer of SportsPress emails.', 'sportspress' ),
				'id' 		=> 'sportspress_email_footer_text',
				'css' 		=> 'width:100%; height: 75px;',
				'type' 		=> 'textarea',
				'default'	=> get_bloginfo('title') . ' - ' . __( 'Powered by SportsPress', 'sportspress' ),
				'autoload'  => false
			),

			array(
				'title' => __( 'Base Colour', 'sportspress' ),
				'desc' 		=> __( 'The base colour for SportsPress email templates. Default <code>#557da1</code>.', 'sportspress' ),
				'id' 		=> 'sportspress_email_base_color',
				'type' 		=> 'color',
				'css' 		=> 'width:6em;',
				'default'	=> '#557da1',
				'autoload'  => false
			),

			array(
				'title' => __( 'Background Colour', 'sportspress' ),
				'desc' 		=> __( 'The background colour for SportsPress email templates. Default <code>#f5f5f5</code>.', 'sportspress' ),
				'id' 		=> 'sportspress_email_background_color',
				'type' 		=> 'color',
				'css' 		=> 'width:6em;',
				'default'	=> '#f5f5f5',
				'autoload'  => false
			),

			array(
				'title' => __( 'Email Body Background Colour', 'sportspress' ),
				'desc' 		=> __( 'The main body background colour. Default <code>#fdfdfd</code>.', 'sportspress' ),
				'id' 		=> 'sportspress_email_body_background_color',
				'type' 		=> 'color',
				'css' 		=> 'width:6em;',
				'default'	=> '#fdfdfd',
				'autoload'  => false
			),

			array(
				'title' => __( 'Email Body Text Colour', 'sportspress' ),
				'desc' 		=> __( 'The main body text colour. Default <code>#505050</code>.', 'sportspress' ),
				'id' 		=> 'sportspress_email_text_color',
				'type' 		=> 'color',
				'css' 		=> 'width:6em;',
				'default'	=> '#505050',
				'autoload'  => false
			),

			array( 'type' => 'sectionend', 'id' => 'email_template_options' ),

		)); // End email settings
	}

	/**
	 * Output the settings
	 */
	public function output() {
		global $current_section;

		// Define emails that can be customised here
		$mailer 			= SP()->mailer();
		$email_templates 	= $mailer->get_emails();

		if ( $current_section ) {
 			foreach ( $email_templates as $email ) {
				if ( strtolower( get_class( $email ) ) == $current_section ) {
					$email->admin_options();
					break;
				}
			}
 		} else {
			$settings = $this->get_settings();

			SP_Admin_Settings::output_fields( $settings );
		}
	}

	/**
	 * Save settings
	 */
	public function save() {
		global $current_section;

		if ( ! $current_section ) {

			$settings = $this->get_settings();
			SP_Admin_Settings::save_fields( $settings );

		} else {

			// Load mailer
			$mailer = SP()->mailer();

			if ( class_exists( $current_section ) ) {
				$current_section_class = new $current_section();
				do_action( 'sportspress_update_options_' . $this->id . '_' . $current_section_class->id );
				SP()->mailer()->init();
			} else {
				do_action( 'sportspress_update_options_' . $this->id . '_' . $current_section );
			}
		}
	}
}

endif;

return new SP_Settings_Emails();