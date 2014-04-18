<?php
/**
 * Event Staff
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Templates
 * @version     0.8
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! isset( $id ) )
	$id = get_the_ID();
$staff = (array)get_post_meta( $id, 'sp_staff', false );

$output = '';

echo apply_filters( 'sportspress_event_staff',  $output );
