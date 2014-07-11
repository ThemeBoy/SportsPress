<?php
/**
 * Team Link
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Templates
 * @version     1.1
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! isset( $id ) )
	$id = get_the_ID();

$url = get_post_meta( $id, 'sp_url', true );

if ( empty( $url ) )
	return false;
?>
<form action="<?php echo $url; ?>">
	<input type="submit" class="button sp-button sp-team-button" value="<?php _e( 'Visit Site', 'sportspress' ); ?>">
</form>
<br>