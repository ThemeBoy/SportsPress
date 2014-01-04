<?php
$sportspress_sports = array();

include_once dirname( __FILE__ ) . '/soccer.php';
include_once dirname( __FILE__ ) . '/football.php';
include_once dirname( __FILE__ ) . '/footy.php';
include_once dirname( __FILE__ ) . '/baseball.php';
include_once dirname( __FILE__ ) . '/basketball.php';
include_once dirname( __FILE__ ) . '/gaming.php';
include_once dirname( __FILE__ ) . '/cricket.php';
include_once dirname( __FILE__ ) . '/golf.php';
include_once dirname( __FILE__ ) . '/handball.php';
include_once dirname( __FILE__ ) . '/hockey.php';
include_once dirname( __FILE__ ) . '/racing.php';
include_once dirname( __FILE__ ) . '/rugby.php';
include_once dirname( __FILE__ ) . '/swimming.php';
include_once dirname( __FILE__ ) . '/tennis.php';
include_once dirname( __FILE__ ) . '/volleyball.php';

$sportspress_sports[] = array( 'name' => __( 'Custom', 'sportspress' ), 'posts' => array() );
?>