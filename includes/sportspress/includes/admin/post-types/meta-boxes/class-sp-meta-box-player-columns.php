<?php
/**
 * Player Columns
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Meta_Boxes
 * @version     2.1
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * SP_Meta_Box_Player_Columns
 */
class SP_Meta_Box_Player_Columns {

	/**
	 * Output the metabox
	 */
	public static function output( $post ) {
		$selected = (array) get_post_meta( $post->ID, 'sp_columns', true );
		$tabs = apply_filters( 'sportspress_player_column_tabs', array( 'sp_performance', 'sp_statistic' ) );
		?>
		<div class="sp-instance">
			<?php if ( $tabs ) { ?>
			<ul id="sp_column-tabs" class="sp-tab-bar category-tabs">
				<?php foreach ( $tabs as $index => $post_type ) { $object = get_post_type_object( $post_type ); ?>
				<li class="<?php if ( 0 == $index ) { ?>tabs<?php } ?>"><a href="#<?php echo $post_type; ?>-all"><?php echo $object->labels->menu_name; ?></a></li>
				<?php } ?>
			</ul>
			<?php
				foreach ( $tabs as $index => $post_type ) {
					sp_column_checklist( $post->ID, $post_type, ( 0 == $index ? 'block' : 'none' ), $selected );
				}
			?>
			<?php } ?>
		</div>
		<?php
	}

	/**
	 * Save meta box data
	 */
	public static function save( $post_id, $post ) {
		update_post_meta( $post_id, 'sp_columns', sp_array_value( $_POST, 'sp_columns', array() ) );
	}
}