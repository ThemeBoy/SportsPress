<?php
/**
 * Team Staff Directories
 *
 * @author 		ThemeBoy
 * @package 	SportsPress_Staff_Directories
 * @version     2.1.2
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! isset( $id ) )
	$id = get_the_ID();

$args = array(
	'post_type' => 'sp_directory',
	'numberposts' => -1,
	'posts_per_page' => -1,
	'orderby' => 'menu_order',
	'order' => 'ASC',
	'meta_query' => array(
		'relation' => 'OR',
		array(
			'key' => 'sp_team',
			'value' => $id,
		),
		array(
			'key' => 'sp_team',
			'value' => 0,
		),
	),
);
$directories = get_posts( $args );

$checked = (array) get_post_meta( $id, 'sp_directory' );

foreach ( $directories as $directory ):
	$id = $directory->ID;

	if ( ! in_array( $id, $checked ) ) continue;
	?>
	<h4 class="sp-table-caption"><?php echo $directory->post_title; ?></h4>
	<?php
	$format = get_post_meta( $id, 'sp_format', true );
	if ( array_key_exists( $format, SP()->formats->directory ) )
		sp_get_template( 'staff-' . $format . '.php', array( 'id' => $id ), '', SP_STAFF_DIRECTORIES_DIR . 'templates/' );
	else
		sp_get_template( 'staff-list.php', array( 'id' => $id ), '', SP_STAFF_DIRECTORIES_DIR . 'templates/' );
endforeach;
