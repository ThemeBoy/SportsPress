<?php
/**
 * List Details
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Meta_Boxes
 * @version		2.7.1
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * SP_Meta_Box_List_Details
 */
class SP_Meta_Box_List_Details {

	/**
	 * Output the metabox
	 */
	public static function output( $post ) {
		$taxonomies = get_object_taxonomies( 'sp_list' );
		$caption = get_post_meta( $post->ID, 'sp_caption', true );
		$team_id = get_post_meta( $post->ID, 'sp_team', true );
		$era = get_post_meta( $post->ID, 'sp_era', true );
		$grouping = get_post_meta( $post->ID, 'sp_grouping', true );
		$orderby = get_post_meta( $post->ID, 'sp_orderby', true );
		$order = get_post_meta( $post->ID, 'sp_order', true );
		$select = get_post_meta( $post->ID, 'sp_select', true );
		$number = get_post_meta( $post->ID, 'sp_number', true );
		$crop = get_post_meta( $post->ID, 'sp_crop', true );
		$date = get_post_meta( $post->ID, 'sp_date', true );
		$date_from = get_post_meta( $post->ID, 'sp_date_from', true );
		$date_to = get_post_meta( $post->ID, 'sp_date_to', true );
		$date_past = get_post_meta( $post->ID, 'sp_date_past', true );
		$date_relative = get_post_meta( $post->ID, 'sp_date_relative', true );
		$continents = SP()->countries->continents;
		$nationalities = get_post_meta( $post->ID, 'sp_nationality', false );
		$default_nationality = get_option( 'sportspress_default_nationality' , false );
		?>
		<div>
			<p><strong><?php _e( 'Heading', 'sportspress' ); ?></strong></p>
			<p><input type="text" id="sp_caption" name="sp_caption" value="<?php echo esc_attr( $caption ); ?>" placeholder="<?php echo esc_attr( get_the_title() ); ?>"></p>

			<div class="sp-date-selector">
				<p><strong><?php _e( 'Date', 'sportspress' ); ?></strong></p>
				<p>
					<?php
					$args = array(
						'name' => 'sp_date',
						'id' => 'sp_date',
						'selected' => $date,
					);
					sp_dropdown_dates( $args );
					?>
				</p>
				<div class="sp-date-range">
					<p class="sp-date-range-absolute">
						<input type="text" class="sp-datepicker-from" name="sp_date_from" value="<?php echo $date_from ? $date_from : date_i18n( 'Y-m-d' ); ?>" size="10">
						:
						<input type="text" class="sp-datepicker-to" name="sp_date_to" value="<?php echo $date_to ? $date_to : date_i18n( 'Y-m-d' ); ?>" size="10">
					</p>

					<p class="sp-date-range-relative">
						<?php _e( 'Past', 'sportspress' ); ?>
						<input type="number" min="0" step="1" class="tiny-text" name="sp_date_past" value="<?php echo '' !== $date_past ? $date_past : 7; ?>">
						<?php _e( 'days', 'sportspress' ); ?>
					</p>

					<p class="sp-date-relative">
						<label>
							<input type="checkbox" name="sp_date_relative" value="1" id="sp_date_relative" <?php checked( $date_relative ); ?>>
							<?php _e( 'Relative', 'sportspress' ); ?>
						</label>
					</p>
				</div>
			</div>

			<?php
			foreach ( $taxonomies as $taxonomy ) {
				sp_taxonomy_field( $taxonomy, $post, true );
			}
			?>
			<p><strong><?php _e( 'Team', 'sportspress' ); ?></strong></p>
			<p class="sp-tab-select sp-team-era-selector">
				<?php
				$args = array(
					'post_type' => 'sp_team',
					'name' => 'sp_team',
					'show_option_all' => __( 'All', 'sportspress' ),
					'selected' => $team_id,
					'values' => 'ID',
				);
				if ( ! sp_dropdown_pages( $args ) ):
					sp_post_adder( 'sp_team', __( 'Add New', 'sportspress' ) );
				endif;
				?>
				<select name="sp_era">
					<option value="all" <?php selected( 'all', $era ); ?>><?php _e( 'All', 'sportspress' ); ?></option>
					<option value="current" <?php selected( 'current', $era ); ?>><?php _e( 'Current', 'sportspress' ); ?></option>
					<option value="past" <?php selected( 'past', $era ); ?>><?php _e( 'Past', 'sportspress' ); ?></option>
				</select>
			</p>
			<p><strong><?php _e( 'Nationality', 'sportspress' ); ?></strong></p>
			<p>
				<select id="sp_nationality" name="sp_nationality[]" data-placeholder="<?php printf( __( 'Select %s', 'sportspress' ), __( 'Nationality', 'sportspress' ) ); ?>" class="widefat chosen-select<?php if ( is_rtl() ): ?> chosen-rtl<?php endif; ?>" multiple="multiple">
					<option value=""></option>
					<?php foreach ( $continents as $continent => $countries ): ?>
						<optgroup label="<?php echo $continent; ?>">
							<?php foreach ( $countries as $code => $country ): ?>
								<option value="<?php echo $code; ?>" <?php selected ( in_array( $code, $nationalities ) ); ?>><?php echo $country; ?></option>
							<?php endforeach; ?>
						</optgroup>
					<?php endforeach; ?>
				</select>
			</p>
			<p><strong><?php _e( 'Grouping', 'sportspress' ); ?></strong></p>
			<p>
			<select name="sp_grouping">
				<option value="0"><?php _e( 'None', 'sportspress' ); ?></option>
				<option value="position" <?php selected( $grouping, 'position' ); ?>><?php _e( 'Position', 'sportspress' ); ?></option>
			</select>
			</p>
			<p><strong><?php _e( 'Sort by', 'sportspress' ); ?></strong></p>
			<p>
			<?php
			$args = array(
				'prepend_options' => array(
					'number' => __( 'Squad Number', 'sportspress' ),
					'name' => __( 'Name', 'sportspress' ),
				),
				'post_type' => array( 'sp_performance', 'sp_metric', 'sp_statistic' ),
				'name' => 'sp_orderby',
				'selected' => $orderby,
				'values' => 'slug',
			);
			sp_dropdown_pages( $args );
			?>
			</p>
			<p>
				<label class="selectit">
					<input type="checkbox" name="sp_crop" value="1" <?php checked( $crop ); ?>>
					<?php _e( 'Skip if zero?', 'sportspress' ); ?>
				</label>
			</p>
			<p><strong><?php _e( 'Sort Order', 'sportspress' ); ?></strong></p>
			<p>
				<select name="sp_order">
					<option value="ASC" <?php selected( 'ASC', $order ); ?>><?php _e( 'Ascending', 'sportspress' ); ?></option>
					<option value="DESC" <?php selected( 'DESC', $order ); ?>><?php _e( 'Descending', 'sportspress' ); ?></option>
				</select>
			</p>
			<p><strong><?php _e( 'Players', 'sportspress' ); ?></strong></p>
			<p class="sp-select-setting">
				<select name="sp_select">
					<option value="auto" <?php selected( 'auto', $select ); ?>><?php _e( 'Auto', 'sportspress' ); ?></option>
					<option value="manual" <?php selected( 'manual', $select ); ?>><?php _e( 'Manual', 'sportspress' ); ?></option>
				</select>
			</p>
			<?php
			if ( 'manual' == $select ) {
				$player_filters = array( 'sp_league', 'sp_season' );
				if ( $team_id ) {					
					if ( in_array( $era, [ 'all', 'past' ] ) ) {
						$player_filters[] = 'sp_past_team';
					}
					if ( in_array( $era, [ 'all', 'current' ] ) ) {
						$player_filters[] = 'sp_current_team';
					}
				}
				sp_post_checklist( $post->ID, 'sp_player', ( 'auto' == $select ? 'none' : 'block' ), $player_filters );
				sp_post_adder( 'sp_player', __( 'Add New', 'sportspress' ) );
			} else {
				?>
				<p><strong><?php _e( 'Display', 'sportspress' ); ?></strong></p>
				<p><input name="sp_number" id="sp_number" type="number" step="1" min="0" class="small-text" placeholder="<?php _e( 'All', 'sportspress' ); ?>" value="<?php echo $number; ?>"> <?php _e( 'players', 'sportspress' ); ?></p>
				<?php
			}
			?>
		</div>
		<?php
	}

	/**
	 * Save meta box data
	 */
	public static function save( $post_id, $post ) {
		update_post_meta( $post_id, 'sp_caption', esc_attr( sp_array_value( $_POST, 'sp_caption', 0 ) ) );
		update_post_meta( $post_id, 'sp_date', sp_array_value( $_POST, 'sp_date', 0 ) );
		update_post_meta( $post_id, 'sp_date_from', sp_array_value( $_POST, 'sp_date_from', null ) );
		update_post_meta( $post_id, 'sp_date_to', sp_array_value( $_POST, 'sp_date_to', null ) );
		update_post_meta( $post_id, 'sp_date_past', sp_array_value( $_POST, 'sp_date_past', 0 ) );
		update_post_meta( $post_id, 'sp_date_relative', sp_array_value( $_POST, 'sp_date_relative', 0 ) );
		$tax_input = sp_array_value( $_POST, 'tax_input', array() );
		update_post_meta( $post_id, 'sp_main_league', in_array( 'auto', sp_array_value( $tax_input, 'sp_league' ) ) );
		update_post_meta( $post_id, 'sp_current_season', in_array( 'auto', sp_array_value( $tax_input, 'sp_season' ) ) );
		update_post_meta( $post_id, 'sp_team', sp_array_value( $_POST, 'sp_team', array() ) );
		update_post_meta( $post_id, 'sp_era', sp_array_value( $_POST, 'sp_era', array() ) );
		update_post_meta( $post_id, 'sp_grouping', sp_array_value( $_POST, 'sp_grouping', array() ) );
		update_post_meta( $post_id, 'sp_orderby', sp_array_value( $_POST, 'sp_orderby', array() ) );
		update_post_meta( $post_id, 'sp_crop', sp_array_value( $_POST, 'sp_crop', 0 ) );
		update_post_meta( $post_id, 'sp_order', sp_array_value( $_POST, 'sp_order', array() ) );
		update_post_meta( $post_id, 'sp_select', sp_array_value( $_POST, 'sp_select', array() ) );
		update_post_meta( $post_id, 'sp_number', sp_array_value( $_POST, 'sp_number', array() ) );
		sp_update_post_meta_recursive( $post_id, 'sp_player', sp_array_value( $_POST, 'sp_player', array() ) );
		sp_update_post_meta_recursive( $post_id, 'sp_nationality', sp_array_value( $_POST, 'sp_nationality', array() ) );
	}
}
