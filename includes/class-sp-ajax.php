<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * SportsPress SP_AJAX
 *
 * AJAX Event Handler
 *
 * @class 		SP_AJAX
 * @version		2.1.3
 * @package		SportsPress/Classes
 * @category	Class
 * @author 		ThemeBoy
 */

class SP_AJAX {

	/**
	 * Hook into ajax events
	 */
	public function __construct() {

		// sportspress_EVENT => nopriv
		$ajax_events = array(
			'event_countdown_shortcode' => false,
			'event_details_shortcode' => false,
			'event_results_shortcode' => false,
			'event_performance_shortcode' => false,
			'event_calendar_shortcode' => false,
			'event_list_shortcode' => false,
			'event_blocks_shortcode' => false,
			'table_table_shortcode' => false,
			'player_details_shortcode' => false,
			'player_statistics_shortcode' => false,
			'player_list_shortcode' => false,
			'player_gallery_shortcode' => false,
		);

		foreach ( $ajax_events as $ajax_event => $nopriv ) {
			add_action( 'wp_ajax_sportspress_' . $ajax_event, array( $this, $ajax_event ) );

			if ( $nopriv ) {
				add_action( 'wp_ajax_nopriv_sportspress_' . $ajax_event, array( $this, $ajax_event ) );
			}
		}
	}

	/**
	 * AJAX event_countdown shortcode
	 */
	public function event_countdown_shortcode() {
		?>
		<div class="wrap sp-thickbox-content" id="sp-thickbox-event_calendar">
			<p>
				<label>
					<?php printf( __( 'Select %s:', 'sportspress' ), __( 'Event', 'sportspress' ) ); ?>
					<?php
					$args = array(
						'post_type' => 'sp_event',
						'name' => 'id',
						'values' => 'ID',
						'show_option_all' => __( '(Auto)', 'sportspress' ),
						'show_dates' => true,
						'post_status' => 'future',
					);
					sp_dropdown_pages( $args );
					?>
				</label>
			</p>
			<p>
				<label>
					<input class="checkbox" type="checkbox" name="show_venue">
					<?php _e( 'Display venue', 'sportspress' ); ?>
				</label>
			</p>
			<p>
				<label>
					<input class="checkbox" type="checkbox" name="show_league">
					<?php _e( 'Display competition', 'sportspress' ); ?>
				</label>
			</p>
			<?php do_action( 'sportspress_ajax_shortcode_form', 'countdown' ); ?>
			<p class="submit">
				<input type="button" class="button-primary" value="<?php _e( 'Insert Shortcode', 'sportspress' ); ?>" onclick="insertSportsPress('countdown');" />
				<a class="button-secondary" onclick="tb_remove();" title="<?php _e( 'Cancel', 'sportspress' ); ?>"><?php _e( 'Cancel', 'sportspress' ); ?></a>
			</p>
		</div>
		<?php
		self::scripts();
		die();
	}

	/**
	 * AJAX event_details shortcode
	 */
	public function event_details_shortcode() {
		?>
		<div class="wrap sp-thickbox-content" id="sp-thickbox-event_details">
			<p>
				<label>
					<?php printf( __( 'Select %s:', 'sportspress' ), __( 'Event', 'sportspress' ) ); ?>
					<?php
					$args = array(
						'post_type' => 'sp_event',
						'name' => 'id',
						'values' => 'ID',
					);
					sp_dropdown_pages( $args );
					?>
				</label>
			</p>
			<?php do_action( 'sportspress_ajax_shortcode_form', 'event-details' ); ?>
			<p class="submit">
				<input type="button" class="button-primary" value="<?php _e( 'Insert Shortcode', 'sportspress' ); ?>" onclick="insertSportsPress('event_details');" />
				<a class="button-secondary" onclick="tb_remove();" title="<?php _e( 'Cancel', 'sportspress' ); ?>"><?php _e( 'Cancel', 'sportspress' ); ?></a>
			</p>
		</div>
		<?php
		self::scripts();
		die();
	}

	/**
	 * AJAX event_results shortcode
	 */
	public function event_results_shortcode() {
		?>
		<div class="wrap sp-thickbox-content" id="sp-thickbox-event_results">
			<p>
				<label>
					<?php printf( __( 'Select %s:', 'sportspress' ), __( 'Event', 'sportspress' ) ); ?>
					<?php
					$args = array(
						'post_type' => 'sp_event',
						'name' => 'id',
						'values' => 'ID',
					);
					sp_dropdown_pages( $args );
					?>
				</label>
			</p>
			<?php do_action( 'sportspress_ajax_shortcode_form', 'event-results' ); ?>
			<p class="submit">
				<input type="button" class="button-primary" value="<?php _e( 'Insert Shortcode', 'sportspress' ); ?>" onclick="insertSportsPress('event_results');" />
				<a class="button-secondary" onclick="tb_remove();" title="<?php _e( 'Cancel', 'sportspress' ); ?>"><?php _e( 'Cancel', 'sportspress' ); ?></a>
			</p>
		</div>
		<?php
		self::scripts();
		die();
	}

	/**
	 * AJAX event_performance shortcode
	 */
	public function event_performance_shortcode() {
		?>
		<div class="wrap sp-thickbox-content" id="sp-thickbox-event_performance">
			<p>
				<label>
					<?php printf( __( 'Select %s:', 'sportspress' ), __( 'Event', 'sportspress' ) ); ?>
					<?php
					$args = array(
						'post_type' => 'sp_event',
						'name' => 'id',
						'values' => 'ID',
					);
					sp_dropdown_pages( $args );
					?>
				</label>
			</p>
			<?php do_action( 'sportspress_ajax_shortcode_form', 'event-performance' ); ?>
			<p class="submit">
				<input type="button" class="button-primary" value="<?php _e( 'Insert Shortcode', 'sportspress' ); ?>" onclick="insertSportsPress('event_performance');" />
				<a class="button-secondary" onclick="tb_remove();" title="<?php _e( 'Cancel', 'sportspress' ); ?>"><?php _e( 'Cancel', 'sportspress' ); ?></a>
			</p>
		</div>
		<?php
		self::scripts();
		die();
	}

	/**
	 * AJAX event_calendar shortcode
	 */
	public function event_calendar_shortcode() {
		?>
		<div class="wrap sp-thickbox-content" id="sp-thickbox-event_calendar">
			<p>
				<label>
					<?php printf( __( 'Select %s:', 'sportspress' ), __( 'Calendar', 'sportspress' ) ); ?>
					<?php
					$args = array(
						'post_type' => 'sp_calendar',
						'name' => 'id',
						'values' => 'ID',
						'show_option_all' => __( 'All', 'sportspress' ),
					);
					sp_dropdown_pages( $args );
					?>
				</label>
			</p>
			<p>
				<label>
					<?php _e( 'Team:', 'sportspress' ); ?>
					<?php
					$args = array(
						'post_type' => 'sp_team',
						'show_option_all' => __( 'Default', 'sportspress' ),
						'name' => 'team',
						'values' => 'ID',
					);
					sp_dropdown_pages( $args );
					?>
				</label>
			</p>
			<p>
				<label>
					<?php _e( 'Competition:', 'sportspress' ); ?>
					<?php
					$args = array(
						'taxonomy' => 'sp_league',
						'show_option_all' => __( 'Default', 'sportspress' ),
						'name' => 'league',
						'values' => 'term_id',
					);
					sp_dropdown_taxonomies( $args );
					?>
				</label>
			</p>
			<p>
				<label>
					<?php _e( 'Season:', 'sportspress' ); ?>
					<?php
					$args = array(
						'taxonomy' => 'sp_season',
						'show_option_all' => __( 'Default', 'sportspress' ),
						'name' => 'season',
						'values' => 'term_id',
					);
					sp_dropdown_taxonomies( $args );
					?>
				</label>
			</p>
			<p>
				<label>
					<?php _e( 'Venue:', 'sportspress' ); ?>
					<?php
					$args = array(
						'taxonomy' => 'sp_venue',
						'show_option_all' => __( 'Default', 'sportspress' ),
						'name' => 'venue',
						'values' => 'term_id',
					);
					sp_dropdown_taxonomies( $args );
					?>
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
					<?php _e( 'Match Day:', 'sportspress' ); ?>
					<input type="text" size="3" name="day" id="day" placeholder="<?php _e( 'All', 'sportspress' ); ?>">
				</label>
			</p>
			<p>
				<label>
					<?php _e( 'Display link to view all events', 'sportspress' ); ?>
					<input type="checkbox" name="show_all_events_link" id="show_all_events_link">
				</label>
			</p>
			<?php do_action( 'sportspress_ajax_shortcode_form', 'event-calendar' ); ?>
			<p class="submit">
				<input type="button" class="button-primary" value="<?php _e( 'Insert Shortcode', 'sportspress' ); ?>" onclick="insertSportsPress('event_calendar');" />
				<a class="button-secondary" onclick="tb_remove();" title="<?php _e( 'Cancel', 'sportspress' ); ?>"><?php _e( 'Cancel', 'sportspress' ); ?></a>
			</p>
		</div>
		<?php
		self::scripts();
		die();
	}

	/**
	 * AJAX event_list shortcode
	 */
	public function event_list_shortcode() {
		?>
		<div class="wrap sp-thickbox-content" id="sp-thickbox-event_list">
			<p>
				<label>
					<?php _e( 'Title:', 'sportspress' ); ?>
					<input class="regular-text" type="text" name="title">
				</label>
			</p>
			<p>
				<label>
					<?php printf( __( 'Select %s:', 'sportspress' ), __( 'Calendar', 'sportspress' ) ); ?>
					<?php
					$args = array(
						'post_type' => 'sp_calendar',
						'show_option_all' => __( 'All', 'sportspress' ),
						'name' => 'id',
						'values' => 'ID',
					);
					sp_dropdown_pages( $args );
					?>
				</label>
			</p>
			<p>
				<label>
					<?php _e( 'Team:', 'sportspress' ); ?>
					<?php
					$args = array(
						'post_type' => 'sp_team',
						'show_option_all' => __( 'Default', 'sportspress' ),
						'name' => 'team',
						'values' => 'ID',
					);
					sp_dropdown_pages( $args );
					?>
				</label>
			</p>
			<p>
				<label>
					<?php _e( 'Competition:', 'sportspress' ); ?>
					<?php
					$args = array(
						'taxonomy' => 'sp_league',
						'show_option_all' => __( 'Default', 'sportspress' ),
						'name' => 'league',
						'values' => 'term_id',
					);
					sp_dropdown_taxonomies( $args );
					?>
				</label>
			</p>
			<p>
				<label>
					<?php _e( 'Season:', 'sportspress' ); ?>
					<?php
					$args = array(
						'taxonomy' => 'sp_season',
						'show_option_all' => __( 'Default', 'sportspress' ),
						'name' => 'season',
						'values' => 'term_id',
					);
					sp_dropdown_taxonomies( $args );
					?>
				</label>
			</p>
			<p>
				<label>
					<?php _e( 'Venue:', 'sportspress' ); ?>
					<?php
					$args = array(
						'taxonomy' => 'sp_venue',
						'show_option_all' => __( 'Default', 'sportspress' ),
						'name' => 'venue',
						'values' => 'term_id',
					);
					sp_dropdown_taxonomies( $args );
					?>
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
					<?php _e( 'Match Day:', 'sportspress' ); ?>
					<input type="text" size="3" name="day" id="day" placeholder="<?php _e( 'All', 'sportspress' ); ?>">
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
					'league' => __( 'Competition', 'sportspress' ),
					'season' => __( 'Season', 'sportspress' ),
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
			<?php do_action( 'sportspress_ajax_shortcode_form', 'event-list' ); ?>
			<p class="submit">
				<input type="button" class="button-primary" value="<?php _e( 'Insert Shortcode', 'sportspress' ); ?>" onclick="insertSportsPress('event_list');" />
				<a class="button-secondary" onclick="tb_remove();" title="<?php _e( 'Cancel', 'sportspress' ); ?>"><?php _e( 'Cancel', 'sportspress' ); ?></a>
			</p>
		</div>
		<?php
		self::scripts();
		die();
	}

	/**
	 * AJAX event_blocks shortcode
	 */
	public function event_blocks_shortcode() {
		?>
		<div class="wrap sp-thickbox-content" id="sp-thickbox-event_blocks">
			<p>
				<label>
					<?php _e( 'Title:', 'sportspress' ); ?>
					<input class="regular-text" type="text" name="title">
				</label>
			</p>
			<p>
				<label>
					<?php printf( __( 'Select %s:', 'sportspress' ), __( 'Calendar', 'sportspress' ) ); ?>
					<?php
					$args = array(
						'post_type' => 'sp_calendar',
						'show_option_all' => __( 'All', 'sportspress' ),
						'name' => 'id',
						'values' => 'ID',
					);
					sp_dropdown_pages( $args );
					?>
				</label>
			</p>
			<p>
				<label>
					<?php _e( 'Team:', 'sportspress' ); ?>
					<?php
					$args = array(
						'post_type' => 'sp_team',
						'show_option_all' => __( 'Default', 'sportspress' ),
						'name' => 'team',
						'values' => 'ID',
					);
					sp_dropdown_pages( $args );
					?>
				</label>
			</p>
			<p>
				<label>
					<?php _e( 'Competition:', 'sportspress' ); ?>
					<?php
					$args = array(
						'taxonomy' => 'sp_league',
						'show_option_all' => __( 'Default', 'sportspress' ),
						'name' => 'league',
						'values' => 'term_id',
					);
					sp_dropdown_taxonomies( $args );
					?>
				</label>
			</p>
			<p>
				<label>
					<?php _e( 'Season:', 'sportspress' ); ?>
					<?php
					$args = array(
						'taxonomy' => 'sp_season',
						'show_option_all' => __( 'Default', 'sportspress' ),
						'name' => 'season',
						'values' => 'term_id',
					);
					sp_dropdown_taxonomies( $args );
					?>
				</label>
			</p>
			<p>
				<label>
					<?php _e( 'Venue:', 'sportspress' ); ?>
					<?php
					$args = array(
						'taxonomy' => 'sp_venue',
						'show_option_all' => __( 'Default', 'sportspress' ),
						'name' => 'venue',
						'values' => 'term_id',
					);
					sp_dropdown_taxonomies( $args );
					?>
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
					<?php _e( 'Match Day:', 'sportspress' ); ?>
					<input type="text" size="3" name="day" id="day" placeholder="<?php _e( 'All', 'sportspress' ); ?>">
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
					<?php _e( 'Sort by:', 'sportspress' ); ?>
					<select id="orderby" name="orderby">
						<option value="default"><?php _e( 'Default', 'sportspress' ); ?></option>
						<option value="date"><?php _e( 'Date', 'sportspress' ); ?></option>
						<option value="day"><?php _e( 'Match Day', 'sportspress' ); ?></option>
					</select>
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
			<?php do_action( 'sportspress_ajax_shortcode_form', 'event-blocks' ); ?>
			<p class="submit">
				<input type="button" class="button-primary" value="<?php _e( 'Insert Shortcode', 'sportspress' ); ?>" onclick="insertSportsPress('event_blocks');" />
				<a class="button-secondary" onclick="tb_remove();" title="<?php _e( 'Cancel', 'sportspress' ); ?>"><?php _e( 'Cancel', 'sportspress' ); ?></a>
			</p>
		</div>
		<?php
		self::scripts();
		die();
	}

	/**
	 * AJAX league_table shortcode
	 */
	public function table_table_shortcode() {
		?>
		<div class="wrap sp-thickbox-content" id="sp-thickbox-league_table">
			<p>
				<label>
					<?php _e( 'Title:', 'sportspress' ); ?>
					<input class="regular-text" type="text" name="title">
				</label>
			</p>
			<p>
				<label>
					<?php printf( __( 'Select %s:', 'sportspress' ), __( 'League Table', 'sportspress' ) ); ?>
					<?php
					$args = array(
						'post_type' => 'sp_table',
						'name' => 'id',
						'values' => 'ID',
					);
					sp_dropdown_pages( $args );
					?>
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
			<?php do_action( 'sportspress_ajax_shortcode_form', 'league-table' ); ?>
			<p class="submit">
				<input type="button" class="button-primary" value="<?php _e( 'Insert Shortcode', 'sportspress' ); ?>" onclick="insertSportsPress('league_table');" />
				<a class="button-secondary" onclick="tb_remove();" title="<?php _e( 'Cancel', 'sportspress' ); ?>"><?php _e( 'Cancel', 'sportspress' ); ?></a>
			</p>
		</div>
		<?php
		self::scripts();
		die();
	}

	/**
	 * AJAX player_details shortcode
	 */
	public function player_details_shortcode() {
		?>
		<div class="wrap sp-thickbox-content" id="sp-thickbox-player_details">
			<p>
				<label>
					<?php printf( __( 'Select %s:', 'sportspress' ), __( 'Player', 'sportspress' ) ); ?>
					<?php
					$args = array(
						'post_type' => 'sp_player',
						'name' => 'id',
						'values' => 'ID',
					);
					sp_dropdown_pages( $args );
					?>
				</label>
			</p>
			<?php do_action( 'sportspress_ajax_shortcode_form', 'player-details' ); ?>
			<p class="submit">
				<input type="button" class="button-primary" value="<?php _e( 'Insert Shortcode', 'sportspress' ); ?>" onclick="insertSportsPress('player_details');" />
				<a class="button-secondary" onclick="tb_remove();" title="<?php _e( 'Cancel', 'sportspress' ); ?>"><?php _e( 'Cancel', 'sportspress' ); ?></a>
			</p>
		</div>
		<?php
		self::scripts();
		die();
	}

	/**
	 * AJAX player_statistics shortcode
	 */
	public function player_statistics_shortcode() {
		?>
		<div class="wrap sp-thickbox-content" id="sp-thickbox-player_statistics">
			<p>
				<label>
					<?php printf( __( 'Select %s:', 'sportspress' ), __( 'Player', 'sportspress' ) ); ?>
					<?php
					$args = array(
						'post_type' => 'sp_player',
						'name' => 'id',
						'values' => 'ID',
					);
					sp_dropdown_pages( $args );
					?>
				</label>
			</p>
			<?php do_action( 'sportspress_ajax_shortcode_form', 'player-statistics' ); ?>
			<p class="submit">
				<input type="button" class="button-primary" value="<?php _e( 'Insert Shortcode', 'sportspress' ); ?>" onclick="insertSportsPress('player_statistics');" />
				<a class="button-secondary" onclick="tb_remove();" title="<?php _e( 'Cancel', 'sportspress' ); ?>"><?php _e( 'Cancel', 'sportspress' ); ?></a>
			</p>
		</div>
		<?php
		self::scripts();
		die();
	}

	/**
	 * AJAX player_list shortcode
	 */
	public function player_list_shortcode() {
		?>
		<div class="wrap sp-thickbox-content" id="sp-thickbox-player_list">
			<p>
				<label>
					<?php _e( 'Title:', 'sportspress' ); ?>
					<input class="regular-text" type="text" name="title">
				</label>
			</p>
			<p>
				<label>
					<?php printf( __( 'Select %s:', 'sportspress' ), __( 'Player List', 'sportspress' ) ); ?>
					<?php
					$args = array(
						'post_type' => 'sp_list',
						'name' => 'id',
						'values' => 'ID',
					);
					sp_dropdown_pages( $args );
					?>
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
				<label class="button"><input name="columns[]" type="checkbox" id="columns-number" value="number" checked="checked"><?php _e( '#', 'sportspress' ); ?></label>
				<label class="button"><input name="columns[]" type="checkbox" id="columns-team" value="team" checked="checked"><?php _e( 'Team', 'sportspress' ); ?></label>
				<label class="button"><input name="columns[]" type="checkbox" id="columns-position" value="position" checked="checked"><?php _e( 'Position', 'sportspress' ); ?></label>
				<?php foreach ( $the_columns as $column ): ?>
					<label class="button"><input name="<?php echo $field_name; ?>" type="checkbox" id="<?php echo $field_id . '-' . $column->post_name; ?>" value="<?php echo $column->post_name; ?>" checked="checked"><?php echo $column->post_title; ?></label>
				<?php endforeach; ?>
			</p>
			<p>
				<label>
					<?php _e( 'Sort by:', 'sportspress' ); ?>
					<?php
					$args = array(
						'prepend_options' => array(
							'default' => __( 'Default', 'sportspress' ),
							'number' => __( 'Squad Number', 'sportspress' ),
							'name' => __( 'Name', 'sportspress' ),
							'eventsplayed' => __( 'Played', 'sportspress' )
						),
						'post_type' => array( 'sp_metric', 'sp_performance', 'sp_statistic' ),
						'name' => 'orderby',
						'id' => 'orderby',
						'values' => 'slug'
					);
					sp_dropdown_pages( $args );
					?>
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
			<?php do_action( 'sportspress_ajax_shortcode_form', 'player-list' ); ?>
			<p class="submit">
				<input type="button" class="button-primary" value="<?php _e( 'Insert Shortcode', 'sportspress' ); ?>" onclick="insertSportsPress('player_list');" />
				<a class="button-secondary" onclick="tb_remove();" title="<?php _e( 'Cancel', 'sportspress' ); ?>"><?php _e( 'Cancel', 'sportspress' ); ?></a>
			</p>
		</div>
		<?php
		self::scripts();
		die();
	}

	/**
	 * AJAX player_gallery shortcode
	 */
	public function player_gallery_shortcode() {
		?>
		<div class="wrap sp-thickbox-content" id="sp-thickbox-player_gallery">
			<p>
				<label>
					<?php _e( 'Title:', 'sportspress' ); ?>
					<input class="regular-text" type="text" name="title">
				</label>
			</p>
			<p>
				<label>
					<?php printf( __( 'Select %s:', 'sportspress' ), __( 'Player List', 'sportspress' ) ); ?>
					<?php
					$args = array(
						'post_type' => 'sp_list',
						'name' => 'id',
						'values' => 'ID',
					);
					sp_dropdown_pages( $args );
					?>
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
					<?php
					$args = array(
						'prepend_options' => array(
							'default' => __( 'Default', 'sportspress' ),
							'number' => __( 'Squad Number', 'sportspress' ),
							'name' => __( 'Name', 'sportspress' ),
							'eventsplayed' => __( 'Played', 'sportspress' )
						),
						'post_type' => array( 'sp_metric', 'sp_performance', 'sp_statistic' ),
						'name' => 'orderby',
						'id' => 'orderby',
						'values' => 'slug'
					);
					sp_dropdown_pages( $args );
					?>
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
			<?php do_action( 'sportspress_ajax_shortcode_form', 'player-gallery' ); ?>
			<p class="submit">
				<input type="button" class="button-primary" value="<?php _e( 'Insert Shortcode', 'sportspress' ); ?>" onclick="insertSportsPress('player_gallery');" />
				<a class="button-secondary" onclick="tb_remove();" title="<?php _e( 'Cancel', 'sportspress' ); ?>"><?php _e( 'Cancel', 'sportspress' ); ?></a>
			</p>
		</div>
		<?php
		self::scripts();
		die();
	}

	public function scripts() {
		?>
		<script type="text/javascript">
            function insertSportsPress( type ) {
                var $div = jQuery('.sp-thickbox-content');

                // Initialize shortcode arguments
                var args = {};

                // Add ID if available and not 0
                id = $div.find('[name=id]').val();
                if ( id != 0 ) args.id = id;

                // Extract args based on type
                if ( 'countdown' == type ) {
                    args.show_venue = $div.find('[name=show_venue]:checked').length;
                    args.show_league = $div.find('[name=show_league]:checked').length;
                } else if ( 'event_calendar' == type ) {
                    args.team = $div.find('[name=team]').val();
                    args.league = $div.find('[name=league]').val();
                    args.season = $div.find('[name=season]').val();
                    args.venue = $div.find('[name=venue]').val();
                    args.status = $div.find('[name=status]').val();
                    args.day = $div.find('[name=day]').val();
                    args.show_all_events_link = $div.find('[name=show_all_events_link]:checked').length;
                } else if ( 'event_list' == type ) {
                    args.title = $div.find('[name=title]').val();
                    args.team = $div.find('[name=team]').val();
                    args.league = $div.find('[name=league]').val();
                    args.season = $div.find('[name=season]').val();
                    args.venue = $div.find('[name=venue]').val();
                    args.status = $div.find('[name=status]').val();
                    args.date = $div.find('[name=date]').val();
                    args.day = $div.find('[name=day]').val();
                    args.number = $div.find('[name=number]').val();
                    args.order = $div.find('[name=order]').val();
                    args.columns = $div.find('[name="columns[]"]:checked').map(function() { return this.value; }).get().join(',');
                    args.show_all_events_link = $div.find('[name=show_all_events_link]:checked').length;
                } else if ( 'event_blocks' == type ) {
                    args.title = $div.find('[name=title]').val();
                    args.team = $div.find('[name=team]').val();
                    args.league = $div.find('[name=league]').val();
                    args.season = $div.find('[name=season]').val();
                    args.venue = $div.find('[name=venue]').val();
                    args.status = $div.find('[name=status]').val();
                    args.date = $div.find('[name=date]').val();
                    args.day = $div.find('[name=day]').val();
                    args.number = $div.find('[name=number]').val();
                    args.orderby = $div.find('[name=orderby]').val();
                    args.order = $div.find('[name=order]').val();
                    args.show_all_events_link = $div.find('[name=show_all_events_link]:checked').length;
                } else if ( 'league_table' == type ) {
                    args.title = $div.find('[name=title]').val();
                    args.number = $div.find('[name=number]').val();
                    args.columns = $div.find('[name="columns[]"]:checked').map(function() { return this.value; }).get().join(',');
                    args.show_team_logo = $div.find('[name=show_team_logo]:checked').length;
                    args.show_full_table_link = $div.find('[name=show_full_table_link]:checked').length;
                } else if ( 'player_list' == type ) {
                    args.title = $div.find('[name=title]').val();
                    args.number = $div.find('[name=number]').val();
                    args.columns = $div.find('[name="columns[]"]:checked').map(function() { return this.value; }).get().join(',');
                    args.orderby = $div.find('[name=orderby]').val();
                    args.order = $div.find('[name=order]').val();
                    args.show_all_players_link = $div.find('[name=show_all_players_link]:checked').length;
                } else if ( 'player_gallery' == type ) {
                    args.title = $div.find('[name=title]').val();
                    args.number = $div.find('[name=number]').val();
                    args.orderby = $div.find('[name=orderby]').val();
                    args.order = $div.find('[name=order]').val();
                    args.show_all_players_link = $div.find('[name=show_all_players_link]:checked').length;
                }

                <?php do_action( 'sportspress_ajax_scripts_before_shortcode' ); ?>

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
	}
}

new SP_AJAX();

