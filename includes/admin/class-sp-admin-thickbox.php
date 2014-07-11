<?php
/**
 * Post Types Admin
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin
 * @version     1.2
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'SP_Admin_Thickbox' ) ) :

/**
 * SP_Admin_Thickbox Class
 */
class SP_Admin_Thickbox {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'admin_footer', array( $this, 'admin_footer' ) );
	}

	/**
	 * Admin Footer For Thickbox
	 *
	 * Prints the footer code needed for the TinyMCE modal window.
	 *
	 * @since 1.2
	 * @global $pagenow
	 * @global $typenow
	 * @return void
	 */
	function admin_footer() {
		global $pagenow, $typenow;

		// Only run in post/page creation and edit screens
		if ( in_array( $pagenow, array( 'post.php', 'page.php', 'post-new.php', 'post-edit.php' ) ) ) { ?>
			<script type="text/javascript">
	            function insertSportsPress( type ) {
	                var $div = jQuery('#sp-thickbox-' + type);

	                // All shortcodes have an ID
	                var args = {id: $div.find('[name=id]').val()};

	                // Extract args based on type
	                if ( 'event_calendar' == type ) {
	                    args['status'] = $div.find('[name=status]').val();
	                    args['show_all_events_link'] = $div.find('[name=show_all_events_link]').val() == 'on' ? 1 : 0;
	                } else if ( 'event_list' == type ) {
	                    args['status'] = $div.find('[name=status]').val();
	                    args['date'] = $div.find('[name=date]').val();
	                    args['number'] = $div.find('[name=number]').val();
	                    args['order'] = $div.find('[name=order]').val();
	                    args['columns'] = $div.find('[name="columns[]"]:checked').map(function() { return this.value; }).get().join(',');
	                    args['show_all_events_link'] = $div.find('[name=show_all_events_link]').val() == 'on' ? 1 : 0;
	                } else if ( 'event_blocks' == type ) {
	                    args['status'] = $div.find('[name=status]').val();
	                    args['date'] = $div.find('[name=date]').val();
	                    args['number'] = $div.find('[name=number]').val();
	                    args['order'] = $div.find('[name=order]').val();
	                    args['show_all_events_link'] = $div.find('[name=show_all_events_link]').val() == 'on' ? 1 : 0;
	                } else if ( 'league_table' == type ) {
	                    args['number'] = $div.find('[name=number]').val();
	                    args['columns'] = $div.find('[name="columns[]"]:checked').map(function() { return this.value; }).get().join(',');
	                    args['show_team_logo'] = $div.find('[name=show_team_logo]').val() == 'on' ? 1 : 0;
	                    args['show_full_table_link'] = $div.find('[name=show_full_table_link]').val() == 'on' ? 1 : 0;
	                } else if ( 'player_list' == type ) {
	                    args['number'] = $div.find('[name=number]').val();
	                    args['columns'] = $div.find('[name="columns[]"]:checked').map(function() { return this.value; }).get().join(',');
	                    args['orderby'] = $div.find('[name=orderby]').val();
	                    args['order'] = $div.find('[name=order]').val();
	                    args['show_all_players_link'] = $div.find('[name=show_all_players_link]').val() == 'on' ? 1 : 0;
	                } else if ( 'player_gallery' == type ) {
	                    args['number'] = $div.find('[name=number]').val();
	                    args['orderby'] = $div.find('[name=orderby]').val();
	                    args['order'] = $div.find('[name=order]').val();
	                    args['show_all_players_link'] = $div.find('[name=show_all_players_link]').val() == 'on' ? 1 : 0;
	                    args['show_names_on_hover'] = $div.find('[name=show_names_on_hover]').val() == 'on' ? 1 : 0;
	                }

	                // Generate the shortcode
					var shortcode = '[' + type;
					for ( var key in args ) {
						if ( args.hasOwnProperty( key ) ) {
							shortcode += ' ' + key + '="' + args[key] + '"';
						}
					}
					shortcode += ']';

	                // Send the shortcode to the editor
	                window.send_to_editor( shortcode );
	            }
			</script>

			<?php
			ob_start();
			$args = array(
				'post_type' => 'sp_calendar',
				'name' => 'id',
				'values' => 'ID',
			);
			sp_dropdown_pages( $args );
			$calendar_dropdown = ob_get_clean();

			ob_start();
			$args = array(
				'post_type' => 'sp_table',
				'name' => 'id',
				'values' => 'ID',
			);
			sp_dropdown_pages( $args );
			$league_table_dropdown = ob_get_clean();

			ob_start();
			$args = array(
				'post_type' => 'sp_list',
				'name' => 'id',
				'values' => 'ID',
			);
			sp_dropdown_pages( $args );
			$player_list_dropdown = ob_get_clean();

			ob_start();
			$args = array(
				'prepend_options' => array(
					'default' => __( 'Default', 'sportspress' ),
					'number' => __( 'Number', 'sportspress' ),
					'name' => __( 'Name', 'sportspress' ),
					'eventsplayed' => __( 'Played', 'sportspress' )
				),
				'post_type' => array( 'sp_metric', 'sp_performance', 'sp_statistic' ),
				'name' => 'orderby',
				'id' => 'orderby',
				'values' => 'slug'
			);
			sp_dropdown_pages( $args );
			$player_list_orderby_dropdown = ob_get_clean();
			?>

			<div id="sp_choose_event_calendar" style="display: none;">
				<div class="wrap sp-thickbox-content" id="sp-thickbox-event_calendar">
					<p>
						<label>
							<?php printf( __( 'Select %s:', 'sportspress' ), __( 'Calendar', 'sportspress' ) ); ?>
							<?php echo $calendar_dropdown; ?>
						</label>
					</p>
					<p>
						<label>
							<?php _e( 'Status:', 'sportspress' ); ?>
							<?php
							$args = array(
								'name' => 'status',
								'show_option_default' => __( 'Default', 'sportspress' ),
							);
							sp_dropdown_statuses( $args );
							?>
						</label>
					</p>
					<p>
						<label>
							<?php _e( 'Display link to view all events', 'sportspress' ); ?>
							<input type="checkbox" name="show_all_events_link" id="show_all_events_link">
						</label>
					</p>
					<p class="submit">
						<input type="button" id="edd-insert-download" class="button-primary" value="<?php echo sprintf( __( 'Insert %s', 'sportspress' ), __( 'Shortcode', 'sportspress' ) ); ?>" onclick="insertSportsPress('event_calendar');" />
						<a class="button-secondary" onclick="tb_remove();" title="<?php _e( 'Cancel', 'sportspress' ); ?>"><?php _e( 'Cancel', 'sportspress' ); ?></a>
					</p>
				</div>
			</div>

			<div id="sp_choose_event_list" style="display: none;">
				<div class="wrap sp-thickbox-content" id="sp-thickbox-event_list">
					<p>
						<label>
							<?php printf( __( 'Select %s:', 'sportspress' ), __( 'Calendar', 'sportspress' ) ); ?>
							<?php echo $calendar_dropdown; ?>
						</label>
					</p>
					<p>
						<label>
							<?php _e( 'Status:', 'sportspress' ); ?>
							<?php
							$args = array(
								'name' => 'status',
								'show_option_default' => __( 'Default', 'sportspress' ),
							);
							sp_dropdown_statuses( $args );
							?>
						</label>
					</p>
					<p>
						<label>
							<?php _e( 'Date:', 'sportspress' ); ?>
							<select id="date" name="date">
								<option value="default"><?php _e( 'Default', 'sportspress' ); ?></option>
								<option value=""><?php _e( 'All', 'sportspress' ); ?></option>
								<option value="w"><?php _e( 'This week', 'sportspress' ); ?></option>
								<option value="day"><?php _e( 'Today', 'sportspress' ); ?></option>
							</select>
						</label>
					</p>
					<p>
						<label>
							<?php _e( 'Number of events to show:', 'sportspress' ); ?>
							<input type="text" size="3" name="number" id="number" value="5">
						</label>
					</p>
					<p>
						<label>
							<?php _e( 'Sort Order:', 'sportspress' ); ?>
							<select id="order" name="order">
								<option value="default"><?php _e( 'Default', 'sportspress' ); ?></option>
								<option value="ASC"><?php _e( 'Ascending', 'sportspress' ); ?></option>
								<option value="DESC"><?php _e( 'Descending', 'sportspress' ); ?></option>
							</select>
						</label>
					</p>
					<p class="sp-prefs">
						<?php _e( 'Columns:', 'sportspress' ); ?><br>
						<?php 
						$the_columns = array(
							'event' => __( 'Event', 'sportspress' ),
							'teams' => __( 'Teams', 'sportspress' ),
							'time' => __( 'Time', 'sportspress' ),
							'venue' => __( 'Venue', 'sportspress' ),
							'article' => __( 'Article', 'sportspress' ),
						);
						$field_name = 'columns[]';
						$field_id = 'columns';
						?>
						<?php foreach ( $the_columns as $key => $label ): ?>
							<label class="button"><input name="<?php echo $field_name; ?>" type="checkbox" id="<?php echo $field_id . '-' . $key; ?>" value="<?php echo $key; ?>" checked="checked"><?php echo $label; ?></label>
						<?php endforeach; ?>
					</p>
					<p>
						<label>
							<input type="checkbox" name="show_all_events_link" id="show_all_events_link">
							<?php _e( 'Display link to view all events', 'sportspress' ); ?>
						</label>
					</p>
					<p class="submit">
						<input type="button" id="edd-insert-download" class="button-primary" value="<?php echo sprintf( __( 'Insert %s', 'sportspress' ), __( 'Shortcode', 'sportspress' ) ); ?>" onclick="insertSportsPress('event_list');" />
						<a class="button-secondary" onclick="tb_remove();" title="<?php _e( 'Cancel', 'sportspress' ); ?>"><?php _e( 'Cancel', 'sportspress' ); ?></a>
					</p>
				</div>
			</div>

			<div id="sp_choose_event_blocks" style="display: none;">
				<div class="wrap sp-thickbox-content" id="sp-thickbox-event_blocks">
					<p>
						<label>
							<?php printf( __( 'Select %s:', 'sportspress' ), __( 'Calendar', 'sportspress' ) ); ?>
							<?php echo $calendar_dropdown; ?>
						</label>
					</p>
					<p>
						<label>
							<?php _e( 'Status:', 'sportspress' ); ?>
							<?php
							$args = array(
								'name' => 'status',
								'show_option_default' => __( 'Default', 'sportspress' ),
							);
							sp_dropdown_statuses( $args );
							?>
						</label>
					</p>
					<p>
						<label>
							<?php _e( 'Date:', 'sportspress' ); ?>
							<select id="date" name="date">
								<option value="default"><?php _e( 'Default', 'sportspress' ); ?></option>
								<option value=""><?php _e( 'All', 'sportspress' ); ?></option>
								<option value="w"><?php _e( 'This week', 'sportspress' ); ?></option>
								<option value="day"><?php _e( 'Today', 'sportspress' ); ?></option>
							</select>
						</label>
					</p>
					<p>
						<label>
							<?php _e( 'Number of events to show:', 'sportspress' ); ?>
							<input type="text" size="3" name="number" id="number" value="5">
						</label>
					</p>
					<p>
						<label>
							<?php _e( 'Sort Order:', 'sportspress' ); ?>
							<select id="order" name="order">
								<option value="default"><?php _e( 'Default', 'sportspress' ); ?></option>
								<option value="ASC"><?php _e( 'Ascending', 'sportspress' ); ?></option>
								<option value="DESC"><?php _e( 'Descending', 'sportspress' ); ?></option>
							</select>
						</label>
					</p>
					<p>
						<label>
							<input type="checkbox" name="show_all_events_link" id="show_all_events_link">
							<?php _e( 'Display link to view all events', 'sportspress' ); ?>
						</label>
					</p>
					<p class="submit">
						<input type="button" id="edd-insert-download" class="button-primary" value="<?php echo sprintf( __( 'Insert %s', 'sportspress' ), __( 'Shortcode', 'sportspress' ) ); ?>" onclick="insertSportsPress('event_blocks');" />
						<a class="button-secondary" onclick="tb_remove();" title="<?php _e( 'Cancel', 'sportspress' ); ?>"><?php _e( 'Cancel', 'sportspress' ); ?></a>
					</p>
				</div>
			</div>

			<div id="sp_choose_league_table" style="display: none;">
				<div class="wrap sp-thickbox-content" id="sp-thickbox-league_table">
					<p>
						<label>
							<?php printf( __( 'Select %s:', 'sportspress' ), __( 'League Table', 'sportspress' ) ); ?>
							<?php echo $league_table_dropdown; ?>
						</label>
					</p>
					<p>
						<label>
							<?php _e( 'Number of teams to show:', 'sportspress' ); ?>
							<input type="text" size="3" name="number" id="number" value="5">
						</label>
					</p>
					<p class="sp-prefs">
						<?php _e( 'Columns:', 'sportspress' ); ?><br>
						<?php 
						$args = array(
							'post_type' => 'sp_column',
							'numberposts' => -1,
							'posts_per_page' => -1,
							'orderby' => 'menu_order',
							'order' => 'ASC'
						);
						$the_columns = get_posts( $args );

						$field_name = 'columns[]';
						$field_id = 'columns';
						?>
						<?php foreach ( $the_columns as $column ): ?>
							<label class="button"><input name="<?php echo $field_name; ?>" type="checkbox" id="<?php echo $field_id . '-' . $column->post_name; ?>" value="<?php echo $column->post_name; ?>" checked="checked"><?php echo $column->post_title; ?></label>
						<?php endforeach; ?>
					</p>
					<p>
						<label>
							<input type="checkbox" name="show_team_logo" id="show_team_logo">
							<?php _e( 'Display logos', 'sportspress' ); ?>
						</label>
					</p>
					<p>
						<label>
							<input type="checkbox" name="show_full_table_link" id="show_full_table_link">
							<?php _e( 'Display link to view full table', 'sportspress' ); ?>
						</label>
					</p>
					<p class="submit">
						<input type="button" id="edd-insert-download" class="button-primary" value="<?php echo sprintf( __( 'Insert %s', 'sportspress' ), __( 'Shortcode', 'sportspress' ) ); ?>" onclick="insertSportsPress('league_table');" />
						<a class="button-secondary" onclick="tb_remove();" title="<?php _e( 'Cancel', 'sportspress' ); ?>"><?php _e( 'Cancel', 'sportspress' ); ?></a>
					</p>
				</div>
			</div>

			<div id="sp_choose_player_list" style="display: none;">
				<div class="wrap sp-thickbox-content" id="sp-thickbox-player_list">
					<p>
						<label>
							<?php printf( __( 'Select %s:', 'sportspress' ), __( 'Player List', 'sportspress' ) ); ?>
							<?php echo $player_list_dropdown; ?>
						</label>
					</p>
					<p>
						<label>
							<?php _e( 'Number of players to show:', 'sportspress' ); ?>
							<input type="text" size="3" name="number" id="number" value="5">
						</label>
					</p>
					<p class="sp-prefs">
						<?php _e( 'Columns:', 'sportspress' ); ?><br>
						<?php 
						$args = array(
							'post_type' => array( 'sp_metric', 'sp_performance', 'sp_statistic' ),
							'numberposts' => -1,
							'posts_per_page' => -1,
							'orderby' => 'menu_order',
							'order' => 'ASC'
						);
						$the_columns = get_posts( $args );

						$field_name = 'columns[]';
						$field_id = 'columns';
						?>
						<?php foreach ( $the_columns as $column ): ?>
							<label class="button"><input name="<?php echo $field_name; ?>" type="checkbox" id="<?php echo $field_id . '-' . $column->post_name; ?>" value="<?php echo $column->post_name; ?>" checked="checked"><?php echo $column->post_title; ?></label>
						<?php endforeach; ?>
					</p>
					<p>
						<label>
							<?php _e( 'Sort by:', 'sportspress' ); ?>
							<?php echo $player_list_orderby_dropdown; ?>
						</label>
					</p>
					<p>
						<label>
							<?php _e( 'Sort Order:', 'sportspress' ); ?>
							<select id="order" name="order">
								<option value=""><?php _e( 'Default', 'sportspress' ); ?></option>
								<option value="ASC"><?php _e( 'Ascending', 'sportspress' ); ?></option>
								<option value="DESC"><?php _e( 'Descending', 'sportspress' ); ?></option>
							</select>
						</label>
					</p>
					<p>
						<label>
							<input type="checkbox" name="show_all_players_link" id="show_all_players_link">
							<?php _e( 'Display link to view all players', 'sportspress' ); ?>
						</label>
					</p>
					<p class="submit">
						<input type="button" id="edd-insert-download" class="button-primary" value="<?php echo sprintf( __( 'Insert %s', 'sportspress' ), __( 'Shortcode', 'sportspress' ) ); ?>" onclick="insertSportsPress('player_list');" />
						<a class="button-secondary" onclick="tb_remove();" title="<?php _e( 'Cancel', 'sportspress' ); ?>"><?php _e( 'Cancel', 'sportspress' ); ?></a>
					</p>
				</div>
			</div>

			<div id="sp_choose_player_gallery" style="display: none;">
				<div class="wrap sp-thickbox-content" id="sp-thickbox-player_gallery">
					<p>
						<label>
							<?php printf( __( 'Select %s:', 'sportspress' ), __( 'Player List', 'sportspress' ) ); ?>
							<?php echo $player_list_dropdown; ?>
						</label>
					</p>
					<p>
						<label>
							<?php _e( 'Number of players to show:', 'sportspress' ); ?>
							<input type="text" size="3" name="number" id="number" value="5">
						</label>
					</p>
					<p>
						<label>
							<?php _e( 'Sort by:', 'sportspress' ); ?>
							<?php echo $player_list_orderby_dropdown; ?>
						</label>
					</p>
					<p>
						<label>
							<?php _e( 'Sort Order:', 'sportspress' ); ?>
							<select id="order" name="order">
								<option value=""><?php _e( 'Default', 'sportspress' ); ?></option>
								<option value="ASC"><?php _e( 'Ascending', 'sportspress' ); ?></option>
								<option value="DESC"><?php _e( 'Descending', 'sportspress' ); ?></option>
							</select>
						</label>
					</p>
					<p>
						<label>
							<input type="checkbox" name="show_all_players_link" id="show_all_players_link">
							<?php _e( 'Display link to view all players', 'sportspress' ); ?>
						</label>
					</p>
					<p>
						<label>
							<input type="checkbox" name="show_names_on_hover" id="show_names_on_hover">
							<?php _e( 'Display player names on hover', 'sportspress' ); ?>
						</label>
					</p>
					<p class="submit">
						<input type="button" id="edd-insert-download" class="button-primary" value="<?php echo sprintf( __( 'Insert %s', 'sportspress' ), __( 'Shortcode', 'sportspress' ) ); ?>" onclick="insertSportsPress('player_gallery');" />
						<a class="button-secondary" onclick="tb_remove();" title="<?php _e( 'Cancel', 'sportspress' ); ?>"><?php _e( 'Cancel', 'sportspress' ); ?></a>
					</p>
				</div>
			</div>
		<?php
		}
	}
}

endif;

return new SP_Admin_Thickbox();