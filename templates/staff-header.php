<?php
/**
 * Staff Header
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Templates
 * @version   2.6
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! isset( $id ) )
  $id = get_the_ID();

$post = function_exists( 'icl_object_id' ) ? get_post( icl_object_id( $id, 'sp_staff', true ) ) : get_post( $id );
$title = $post->post_title;
if ( $title ) {
  $staff = new SP_Staff( $id );
  $role  = $staff->role();
  if ( $role )
    $title = '<strong class="sp-staff-role">' . $role->name . '</strong> ' . $title;
  ?>
    <h3 class="sp-staff-name"><?php echo $title ?></h3>
  <?php
}
