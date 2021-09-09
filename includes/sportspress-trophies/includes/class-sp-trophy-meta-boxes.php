<?php
/**
 * Trophy Meta Boxes
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress_Trophies
 * @version   2.8.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * SP_Trophy_Meta_Boxes
 */
class SP_Trophy_Meta_Boxes {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ), 30 );
		add_action( 'sportspress_process_sp_trophy_meta', array( $this, 'save' ) );
	}

	/**
	 * Add Meta boxes
	 */
	public function add_meta_boxes() {
		add_meta_box( 'sp_statisticsdiv', __( 'Statistics', 'sportspress' ), array( $this, 'statistics' ), 'sp_trophy', 'normal', 'high' );
		add_meta_box( 'sp_shortcodediv', __( 'Shortcode', 'sportspress' ), array( $this, 'shortcode' ), 'sp_trophy', 'side', 'default' );
		add_meta_box( 'sp_detailsdiv', __( 'Details', 'sportspress' ), array( $this, 'details' ), 'sp_trophy', 'side', 'default' );
	}

	/**
	 * Output the details metabox
	 */
	public static function statistics( $post ) {
		wp_nonce_field( 'sportspress_save_data', 'sportspress_meta_nonce' );
		$seasons = get_terms( array(
			'taxonomy' => 'sp_season',
			'hide_empty' => false,
			'orderby' => 'meta_value_num',
			'meta_query' => array(
								'relation' => 'OR',
								array(
									'key' => 'sp_order',
									'compare' => 'NOT EXISTS'
								),
								array(
									'key' => 'sp_order',
									'compare' => 'EXISTS'
								),
							),
			'order' => 'DESC',
		) );
		
		$winners_perseason = array_filter( (array)get_post_meta( $post->ID, 'sp_trophies', true ) );
		
		$selected_team = null;
		$selected_table = null;
		$selected_calendar = null;
		?>
		<div class="sp-data-table-container sp-table-values" id="sp-table-values">
			<table class="widefat sp-data-table sp-trophies-statistics-table">
				<thead>
					<tr>
						<th><?php _e( 'Season', 'sportspress' ); ?></th>
						<th><?php _e( 'Winner', 'sportspress' ); ?></th>
						<th><?php _e( 'Table', 'sportspress' ); ?></th>
						<th><?php _e( 'Calendar', 'sportspress' ); ?></th>
				</thead>
				<tbody>
				<?php 
				$i=0;
				foreach ( $seasons as $season ) {
				?>
					<tr class="sp-row sp-post<?php if ( $i % 2 == 0 ) echo ' alternate'; ?> ">
					<td>
						<label>
							<input type="hidden" name="sp_trophies[<?php echo $season->term_id; ?>][season]" value="<?php echo $season->name; ?>">
							<?php echo $season->name; ?>
						</label>
					</td>
					<td>
					<?php 
					if ( isset( $winners_perseason[ $season->term_id ] ) )
						$selected_team = sp_array_value( $winners_perseason[ $season->term_id ], 'team_id', '-1' ); ?>
					<?php
						$args = array(
							'post_type' => 'sp_team',
							'name' => 'sp_trophies[' . $season->term_id . '][team_id]',
							'show_option_none' => __( '&mdash; None &mdash;', 'sportspress' ),
							'option_none_value' => false,
							'sort_order'   => 'ASC',
							'sort_column'  => 'menu_order',
							'values' => 'ID',
							'selected' => $selected_team,
						);
						if ( ! sp_dropdown_pages( $args ) ):
							_e( '&mdash; None &mdash;', 'sportspress' );
						endif;
					?>
					</td>
					<td>
					<?php 
					if ( isset( $winners_perseason[ $season->term_id ] ) )
						$selected_table = sp_array_value( $winners_perseason[ $season->term_id ], 'table_id', '-1' ); ?>
					<?php
						$args = array(
							'post_type' => 'sp_table',
							'name' => 'sp_trophies[' . $season->term_id . '][table_id]',
							'show_option_none' => __( '&mdash; None &mdash;', 'sportspress' ),
							'sort_order'   => 'ASC',
							'sort_column'  => 'menu_order',
							'values' => 'ID',
							'selected' => $selected_table,
							'tax_query' => array(
												array(
													'taxonomy' => 'sp_season',
													'terms'    => $season->term_id,
												),
											),
						);
						if ( ! sp_dropdown_pages( $args ) ):
							_e( '&mdash; None &mdash;', 'sportspress' );
						endif;
					?>
					</td>
					<td>
					<?php 
					if ( isset( $winners_perseason[ $season->term_id ] ) )
						$selected_calendar = sp_array_value( $winners_perseason[ $season->term_id ], 'calendar_id', '-1' ); ?>
					<?php
						$args = array(
							'post_type' => 'sp_calendar',
							'name' => 'sp_trophies[' . $season->term_id . '][calendar_id]',
							'show_option_none' => __( '&mdash; None &mdash;', 'sportspress' ),
							'sort_order'   => 'ASC',
							'sort_column'  => 'menu_order',
							'values' => 'ID',
							'selected' => $selected_calendar,
							'tax_query' => array(
												array(
													'taxonomy' => 'sp_season',
													'terms'    => $season->term_id,
												),
											),
						);
						if ( ! sp_dropdown_pages( $args ) ):
							_e( '&mdash; None &mdash;', 'sportspress' );
						endif;
					?>
					</td>
					</tr>
				<?php
				$i++;
				} 
				?>
				</tbody>
			</table>
		</div>
		<?php
	}
	
	/**
	 * Output the shortcode metabox
	 */
	public static function shortcode( $post ) {
		?>
		<p class="howto">
			<?php _e( 'Copy this code and paste it into your post, page or text widget content.', 'sportspress' ); ?>
		</p>
		<p><input type="text" value="<?php sp_shortcode_template( 'team_trophies', $post->ID ); ?>" readonly="readonly" class="code widefat"></p>
		<?php
	}

	/**
	 * Output the details metabox
	 */
	public static function details( $post ) {
		wp_nonce_field( 'sportspress_save_data', 'sportspress_meta_nonce' );
		$caption = get_post_meta( $post->ID, 'sp_caption', true );
		?>
		<div>
			<p><strong><?php _e( 'Heading', 'sportspress' ); ?></strong></p>
			<p><input type="text" id="sp_caption" name="sp_caption" value="<?php echo esc_attr( $caption ); ?>" placeholder="<?php echo esc_attr( get_the_title() ); ?>"></p>
		</div>
		<?php
	}

	public static function save( $post_id ) {
		
		// Details
		update_post_meta( $post_id, 'sp_caption', sp_array_value( $_POST, 'sp_caption', '' ) );

		// Statistics
		$winners_perseason = sp_array_value( $_POST, 'sp_trophies', array() );
		$teams = array();
		$winners = array();
		foreach( $winners_perseason as $season_id => $season ) {
			$teams[] = $season['team_id'];
			$winners[ $season['team_id'] ][ $season_id ]['season_name'] = $season['season'];
			
			if ( isset( $season['table_id'] ) )
				$winners[ $season['team_id'] ][ $season_id ]['table_id'] = $season['table_id'];
			
			if ( isset( $season['calendar_id'] ) )
				$winners[ $season['team_id'] ][ $season_id ]['calendar_id'] = $season['calendar_id'];
		}
		$teams = array_filter( $teams );
		$teams = array_unique( $teams );
		update_post_meta( $post_id, 'sp_trophies', $winners_perseason );
		update_post_meta( $post_id, 'sp_teams', $teams );
		update_post_meta( $post_id, 'sp_winners', $winners );
	}
}

new SP_Trophy_Meta_Boxes();