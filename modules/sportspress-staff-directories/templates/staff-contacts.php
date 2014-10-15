<?php
/**
 * Staff Contact Info
 *
 * @author 		ThemeBoy
 * @package 	SportsPress_Staff_Directories
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! isset( $id ) )
	$id = get_the_ID();

$defaults = array(
	'link_phone' => get_option( 'sportspress_staff_link_phone', 'yes' ) == 'yes' ? true : false,
	'link_email' => get_option( 'sportspress_staff_link_email', 'yes' ) == 'yes' ? true : false,
);

extract( $defaults, EXTR_SKIP );

$staff = new SP_Staff( $id );

$phone = $staff->phone;
$email = $staff->email;

$data = array();

if ( $phone !== '' ):
	if ( $link_phone ) $phone = '<a href="tel:' . $phone . '">' . $phone . '</a>';
	$data[ __( 'Phone', 'sportspress' ) ] = $phone;
endif;

if ( $email !== '' ):
	if ( $link_email ) $email = '<a href="mailto:' . $email . '">' . $email . '</a>';
	$data[ __( 'Email', 'sportspress' ) ] = $email;
endif;

$output = '<div class="sp-list-wrapper">' .
	'<dl class="sp-staff-details sp-staff-contact">';

foreach( $data as $label => $value ):

	$output .= '<dt>' . $label . '<dd>' . $value . '</dd>';

endforeach;

$output .= '</dl></div>';
?>
<?php echo $output; ?>