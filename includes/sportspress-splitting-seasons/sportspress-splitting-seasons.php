<?php
/*
Plugin Name: SportsPress Splitting Seasons
Plugin URI: http://tboy.co/pro
Description: Add Splitting Seasons (Mid-Season Transfers) to SportsPress players.
Author: ThemeBoy
Author URI: http://themeboy.com
Version: 2.6.0
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_Splitting_Seasons' ) ) :

/**
 * Main SportsPress Splitting Seasons Class
 *
 * @class SportsPress_Splitting_Seasons
 * @version	2.6.0
 *
 */
class SportsPress_Splitting_Seasons {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();

		// Hooks
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'sportspress_save_meta_player_statistics', array( $this, 'save_additional_statistics' ), 10, 2 );
		add_action( 'sportspress_empty_row_player_statistics', array( $this, 'add_empty_row' ), 10, 6 );
	}

	/**
	 * Define constants
	 */
	private function define_constants() {
		if ( !defined( 'SP_SPLITTING_SEASONS_VERSION' ) )
			define( 'SP_SPLITTING_SEASONS_VERSION', '2.6.0' );

		if ( !defined( 'SP_SPLITTING_SEASONS_URL' ) )
			define( 'SP_SPLITTING_SEASONS_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'SP_SPLITTING_SEASONS_DIR' ) )
			define( 'SP_SPLITTING_SEASONS_DIR', plugin_dir_path( __FILE__ ) );
	}
	
	/**
	 * Enqueue scripts
	 */
	public function admin_enqueue_scripts() {
		$screen = get_current_screen();

		if ( in_array( $screen->id, array( 'sp_player', 'edit-sp_player' ) ) ) {
		    wp_enqueue_script( 'sportspress-splitting-seasons', SP_SPLITTING_SEASONS_URL .'js/sportspress-splitting-seasons.js', array( 'jquery' ), SP_SPLITTING_SEASONS_VERSION, true );
		}
	}
	
	/**
	 * Save Additional Statistics
	 */
	public function save_additional_statistics( $post_id, $post_data ) {
		$old = get_post_meta($post_id, 'sp_additional_statistics', true);
		$new = array();
		
		$leagues = $post_data['sp_add_league'];
		$seasons = $post_data['sp_add_season'];
		$teams = $post_data['sp_add_team'];
		$columns = $post_data['sp_add_columns'];
		$labels = array_keys($columns);
		
		$i = 0;
		foreach ( $leagues as $league ) {
			if ( $league != '-99' ) {
				foreach ( $labels as $label ) {
					$new[$league][$seasons[$i]][$teams[$i]][$label] = $columns[$label][$i];
				}
			}
			$i++;
		}
		if ( !empty( $new ) && $new != $old ) {
			update_post_meta( $post_id, 'sp_additional_statistics', $new );
		}
		elseif ( empty($new) && $old ) {
			delete_post_meta( $post_id, 'sp_additional_statistics', $old );
		}
	}
	
	/**
	 * Add an empty row
	 */
	public function add_empty_row( $team_select, $league_id, $div_id, $columns, $teams, $formats ) {
		?>
		<tr class="empty-row screen-reader-text">
							<td>
								<label>
									&Gt;
								</label>
								<input id="leagueHidden" type="hidden" name="sp_add_league[]" value="-99">
								<input id="seasonHidden" type="hidden" name="sp_add_season[]" value="-99">
								<input id="teamHidden" type="hidden" name="sp_add_team[]" value="-1">
							</td>
							<?php if ( $team_select && apply_filters( 'sportspress_player_team_statistics', $league_id ) ): ?>
									<td>
										<?php
										$args = array(
											'post_type' => 'sp_team',
											//'name' => 'sp_additional_team[-99][-99][]',
											'show_option_none' => __( '&mdash; None &mdash;', 'sportspress' ),
										    'sort_order'   => 'ASC',
										    'sort_column'  => 'menu_order',
											'selected' => null,
											'values' => 'ID',
											'id' => 'additional_team',
											'include' => $teams,
											'tax_query' => array(
												'relation' => 'AND',
												array(
													'taxonomy' => 'sp_league',
													'terms' => $league_id,
													'field' => 'term_id',
												),
												array(
													'taxonomy' => 'sp_season',
													'terms' => $div_id,
													'field' => 'term_id',
												),
											),
										);
										if ( ! sp_dropdown_pages( $args ) ):
											_e( '&mdash; None &mdash;', 'sportspress' );
										?>
									</td>
								<?php endif; ?>
							<?php endif; ?>
							<?php foreach ( $columns as $column => $label ): if ( $column == 'team' ) continue;
								?>
								<td><?php
										if ( 'time' === sp_array_value( $formats, $column, 'number' ) ) {
											echo '<input class="sp-convert-time-input" type="text" name="sp_additional_times[' . $column . '][]" placeholder="0" />';
											echo '<input class="sp-convert-time-output" type="hidden" name="sp_add_columns[' . $column . '][]" />';
										} else {
											echo '<input type="text" name="sp_add_columns[' . $column . '][]" placeholder="0" />';
										}
								?></td>
							<?php endforeach; ?>
							<td class="sp-actions-column">
								<a href="#" title="<?php _e( 'Delete row', 'sportspress' ); ?>" class="dashicons dashicons-dismiss sp-delete-row" style="display:none; color:red;"></a>
								<a href="#" title="<?php _e( 'Insert row after', 'sportspress' ); ?>" class="dashicons dashicons-plus-alt sp-add-row"></a>
							</td>
						</tr>
						<?php
	}

}

endif;

if ( get_option( 'sportspress_load_splitting_seasons_module', 'yes' ) == 'yes' ) {
	new SportsPress_Splitting_Seasons();
}
