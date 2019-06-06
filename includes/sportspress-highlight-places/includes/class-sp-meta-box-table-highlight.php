<?php
/**
 * Table Format
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress Highlight Places
 * @version   2.7
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * SP_Meta_Box_Table_Highlight_Places
 */
class SP_Meta_Box_Table_Highlight_Places {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ), 30 );
		add_action( 'save_post', array( $this, 'save' ), 1, 2 );
	}
	
	/**
	 * Add Meta box
	 */
	public function add_meta_box() {
		add_meta_box( 'sp_highlightdiv', __( 'Highlight Places', 'sportspress' ), array( $this, 'output' ), 'sp_table', 'side', 'default' );
	}
	
	/**
	 * Output the metabox
	 */
	public static function output( $post ) {
		wp_nonce_field( 'sportspress_save_data', 'sportspress_meta_nonce' );
		$sp_highlight_places = get_post_meta( $post->ID, 'sp_highlight_places', true );
		ksort( $sp_highlight_places );
		var_dump($sp_highlight_places);
		?>
		<div class="sp-data-table-container">
			<table class="widefat sp-data-table sp-highlight-places">
				<thead>
					<tr><th>Color</th><th>Place</th><th>Comment</th><th><a href="#" title="<?php _e( 'Insert row', 'sportspress' ); ?>" class="dashicons dashicons-plus-alt sp-add-row" ></a></th></tr>
				</thead>
				<tbody>
				<?php 
				$sp_rows = null;
				foreach ( $sp_highlight_places as $place => $info) { 
					$sp_rows .= '<tr class="sp-row">
						<td>
							<div class="sp-color-box">
								<input name="sp_highlight_places['.$place.'][color]" id="sp_color" type="text" value="'.$info["color"].'" class="colorpick">
								<div id="sp_color" class="colorpickdiv"></div>
							</div>
						</td>
						<td>
							<input name="sp_place" id="sp_place" type="number" value="'.$place.'" class="sp_place" min="1">
						</td>
						<td>
							<input name="sp_highlight_places['.$place.'][desc]" id="sp_place_desc" type="text" value="'.$info["desc"].'" class="sp_place_desc">
						</td>
						<td class="sp-actions-column">
							<a href="#" title="Delete row" class="dashicons dashicons-dismiss sp-delete-row"></a>
						</td>
					</tr>';
				 }
				echo $sp_rows; ?>
				</tbody>
			</table>
		</div>
		<?php
	}

	/**
	 * Save meta box data
	 */
	public static function save( $post_id, $post ) {
		update_post_meta( $post_id, 'sp_highlight_places', sp_array_value( $_POST, 'sp_highlight_places', null ) );
	}
}