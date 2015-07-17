<?php
/**
 * Team League Tables
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Templates
 * @version     1.8.7
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! isset( $id ) )
	$id = get_the_ID();

$team = new SP_Team( $id );
$tables = $team->tables();

foreach ( $tables as $table ):
	if ( ! $table ) continue;

	sp_get_template( 'league-table.php', array( 'id' => $table->ID, 'highlight' => $id ) );
endforeach;
