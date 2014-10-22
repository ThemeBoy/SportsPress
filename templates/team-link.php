<?php
/**
 * Team Link
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Templates
 * @version     1.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if ( get_option( 'sportspress_team_show_link', 'yes' ) === 'no' ) return;

if ( ! isset( $id ) )
	$id = get_the_ID();

$url = get_post_meta( $id, 'sp_url', true );

if ( empty( $url ) )
	return false;
?>
<form action="<?php echo $url; ?>"<?php if ( get_option( 'sportspress_team_site_target_blank', 'no' ) == 'yes' ) { ?> target="_blank"<?php } ?>>
	<input type="submit" class="button sp-button sp-team-button" value="<?php _e( 'Visit Site', 'sportspress' ); ?>">
</form>
<br>