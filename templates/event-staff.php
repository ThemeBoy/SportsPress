<?php
/**
 * Event Staff
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Templates
 * @version     1.1
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$defaults = array(
	'id' => get_the_ID(),
	'index' => 0,
	'link_posts' => get_option( 'sportspress_link_staff', 'yes' ) == 'yes' ? true : false,
);

$staff = array_filter( sp_array_between( (array)get_post_meta( $id, 'sp_staff', false ), 0, $index ) );

if ( ! $staff ) return;

extract( $defaults, EXTR_SKIP );
?>
<p class="sp-event-staff">
<?php
foreach( $staff as $staff_id ):

	if ( ! $staff_id )
		continue;

	$name = get_the_title( $staff_id );

	if ( ! $name )
		continue;

	$role = get_post_meta( $staff_id, 'sp_role', true );

	if ( $role )
		echo $role;
	else
		_e( 'Staff', 'sportspress' );

	echo ': ';

	if ( $link_posts ):
		$permalink = get_post_permalink( $staff_id );
		$name =  '<a href="' . $permalink . '">' . $name . '</a>';
	endif;

	echo $name . '<br>';

endforeach;
?>
</p>