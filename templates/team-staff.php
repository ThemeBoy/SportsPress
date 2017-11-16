<?php
/**
 * Team Staff
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Templates
 * @version   2.5
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! isset( $id ) )
	$id = get_the_ID();

$team = new SP_Team( $id );
$members = $team->staff();

foreach ( $members as $staff ):
	$id = $staff->ID;
	$name = $staff->post_title;
	
	$staff = new SP_Staff( $id );
	$roles = $staff->roles();

	if ( ! empty( $roles ) ):
		$roles = wp_list_pluck( $roles, 'name' );
		$name = '<span class="sp-staff-role">' . implode( '<span class="sp-staff-role-delimiter">/</span>', $roles ) . '</span> ' . $name;
	endif;
	?>
	<h4 class="sp-staff-name"><?php echo $name; ?></h4>
	<?php
	sp_get_template( 'staff-photo.php', array( 'id' => $id ) );
	sp_get_template( 'staff-details.php', array( 'id' => $id ) );
endforeach;
