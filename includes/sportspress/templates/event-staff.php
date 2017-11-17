<?php
/**
 * Event Staff
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Templates
 * @version   2.5
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$defaults = array(
	'id' => get_the_ID(),
	'index' => 0,
	'link_posts' => get_option( 'sportspress_link_staff', 'yes' ) == 'yes' ? true : false,
);

$staffs = array_filter( sp_array_between( (array)get_post_meta( $id, 'sp_staff', false ), 0, $index ) );

if ( ! $staffs ) return;

extract( $defaults, EXTR_SKIP );
?>
<div class="sp-template sp-template-event-staff">
	<p class="sp-event-staff">
	<?php
	foreach( $staffs as $staff_id ):

		if ( ! $staff_id )
			continue;

		$name = get_the_title( $staff_id );

		if ( ! $name )
			continue;

		$staff = new SP_Staff( $staff_id );

		$roles = $staff->roles();
		if ( ! empty( $roles ) ):
			$roles = wp_list_pluck( $roles, 'name' );
			echo implode( '<span class="sp-staff-role-delimiter">/</span>', $roles );
		else:
			_e( 'Staff', 'sportspress' );
		endif;

		echo ': ';

		if ( $link_posts ):
			$permalink = get_post_permalink( $staff_id );
			$name =  '<a href="' . $permalink . '">' . $name . '</a>';
		endif;

		echo $name . '<br>';

	endforeach;
	?>
	</p>
</div>