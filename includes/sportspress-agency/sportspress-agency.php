<?php
/*
Plugin Name: SportsPress: Agency
Plugin URI: http://tboy.co/pro
Description: Tells SportsPress that it's an Agency License.
Author: ThemeBoy
Author URI: http://themeboy.com
Version: 1.9
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// Important: Do not delete this file. It tells SportsPress that the Agency License is installed.
if ( ! class_exists( 'SportsPress_Agency' ) ) :
	class SportsPress_Agency {}
endif;
