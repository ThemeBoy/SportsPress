<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * SportsPress SP_AJAX
 *
 * AJAX Event Handler
 *
 * @class       SP_AJAX
 * @version     2.7.9
 * @package     SportsPress/Classes
 * @category    Class
 * @author      ThemeBoy
 */

class SP_AJAX {

	/**
	 * Hook into ajax events
	 */
	public function __construct() {

		// sportspress_EVENT => nopriv
		$ajax_events = array(
			'event_countdown_shortcode'   => false,
			'event_details_shortcode'     => false,
			'event_results_shortcode'     => false,
			'event_performance_shortcode' => false,
			'event_venue_shortcode'       => false,
			'event_officials_shortcode'   => false,
			'event_teams_shortcode'       => false,
			'event_full_shortcode'        => false,
			'event_calendar_shortcode'    => false,
			'event_list_shortcode'        => false,
			'event_blocks_shortcode'      => false,
			'team_standings_shortcode'    => false,
			'team_gallery_shortcode'      => false,
			'player_details_shortcode'    => false,
			'player_statistics_shortcode' => false,
			'player_list_shortcode'       => false,
			'player_gallery_shortcode'    => false,
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
					<?php printf( esc_html__( 'Select %s:', 'sportspress' ), esc_html__( 'Event', 'sportspress' ) ); ?>
					<?php
					$args = array(
						'post_type'       => 'sp_event',
						'name'            => 'id',
						'values'          => 'ID',
						'show_option_all' => esc_html__( '(Auto)', 'sportspress' ),
						'show_dates'      => true,
						'post_status'     => 'future',
					);
					sp_dropdown_pages( $args );
					?>
				</label>
			</p>
			<p>
				<label>
					<input class="checkbox" type="checkbox" name="show_venue">
					<?php esc_html_e( 'Display venue', 'sportspress' ); ?>
				</label>
			</p>
			<p>
				<label>
					<input class="checkbox" type="checkbox" name="show_league">
					<?php esc_html_e( 'Display league', 'sportspress' ); ?>
				</label>
			</p>
			<?php do_action( 'sportspress_ajax_shortcode_form', 'countdown' ); ?>
			<p class="submit">
				<input type="button" class="button-primary" value="<?php esc_attr_e( 'Insert Shortcode', 'sportspress' ); ?>" onclick="insertSportsPress('countdown');" />
				<a class="button-secondary" onclick="tb_remove();" title="<?php esc_attr_e( 'Cancel', 'sportspress' ); ?>"><?php esc_html_e( 'Cancel', 'sportspress' ); ?></a>
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
					<?php printf( esc_html__( 'Select %s:', 'sportspress' ), esc_html__( 'Event', 'sportspress' ) ); ?>
					<?php
					$args = array(
						'post_type' => 'sp_event',
						'name'      => 'id',
						'values'    => 'ID',
					);
					sp_dropdown_pages( $args );
					?>
				</label>
			</p>
			<?php do_action( 'sportspress_ajax_shortcode_form', 'event-details' ); ?>
			<p class="submit">
				<input type="button" class="button-primary" value="<?php esc_attr_e( 'Insert Shortcode', 'sportspress' ); ?>" onclick="insertSportsPress('event_details');" />
				<a class="button-secondary" onclick="tb_remove();" title="<?php esc_attr_e( 'Cancel', 'sportspress' ); ?>"><?php esc_html_e( 'Cancel', 'sportspress' ); ?></a>
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
					<?php printf( esc_html__( 'Select %s:', 'sportspress' ), esc_html__( 'Event', 'sportspress' ) ); ?>
					<?php
					$args = array(
						'post_type' => 'sp_event',
						'name'      => 'id',
						'values'    => 'ID',
					);
					sp_dropdown_pages( $args );
					?>
				</label>
			</p>
			<?php do_action( 'sportspress_ajax_shortcode_form', 'event-results' ); ?>
			<p class="submit">
				<input type="button" class="button-primary" value="<?php esc_attr_e( 'Insert Shortcode', 'sportspress' ); ?>" onclick="insertSportsPress('event_results');" />
				<a class="button-secondary" onclick="tb_remove();" title="<?php esc_attr_e( 'Cancel', 'sportspress' ); ?>"><?php esc_attr_e( 'Cancel', 'sportspress' ); ?></a>
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
					<?php printf( esc_html__( 'Select %s:', 'sportspress' ), esc_html__( 'Event', 'sportspress' ) ); ?>
					<?php
					$args = array(
						'post_type' => 'sp_event',
						'name'      => 'id',
						'values'    => 'ID',
					);
					sp_dropdown_pages( $args );
					?>
				</label>
			</p>
			<?php do_action( 'sportspress_ajax_shortcode_form', 'event-performance' ); ?>
			<p class="submit">
				<input type="button" class="button-primary" value="<?php esc_attr_e( 'Insert Shortcode', 'sportspress' ); ?>" onclick="insertSportsPress('event_performance');" />
				<a class="button-secondary" onclick="tb_remove();" title="<?php esc_attr_e( 'Cancel', 'sportspress' ); ?>"><?php esc_html_e( 'Cancel', 'sportspress' ); ?></a>
			</p>
		</div>
		<?php
		self::scripts();
		die();
	}

	/**
	 * AJAX event_venue shortcode
	 */
	public function event_venue_shortcode() {
		?>
		<div class="wrap sp-thickbox-content" id="sp-thickbox-event_venue">
			<p>
				<label>
					<?php printf( esc_html__( 'Select %s:', 'sportspress' ), esc_html__( 'Event', 'sportspress' ) ); ?>
					<?php
					$args = array(
						'post_type' => 'sp_event',
						'name'      => 'id',
						'values'    => 'ID',
					);
					sp_dropdown_pages( $args );
					?>
				</label>
			</p>
			<?php do_action( 'sportspress_ajax_shortcode_form', 'event-venue' ); ?>
			<p class="submit">
				<input type="button" class="button-primary" value="<?php esc_attr_e( 'Insert Shortcode', 'sportspress' ); ?>" onclick="insertSportsPress('event_venue');" />
				<a class="button-secondary" onclick="tb_remove();" title="<?php esc_attr_e( 'Cancel', 'sportspress' ); ?>"><?php esc_html_e( 'Cancel', 'sportspress' ); ?></a>
			</p>
		</div>
		<?php
		self::scripts();
		die();
	}

	/**
	 * AJAX event_officials shortcode
	 */
	public function event_officials_shortcode() {
		?>
		<div class="wrap sp-thickbox-content" id="sp-thickbox-event_officials">
			<p>
				<label>
					<?php printf( esc_html__( 'Select %s:', 'sportspress' ), esc_html__( 'Event', 'sportspress' ) ); ?>
					<?php
					$args = array(
						'post_type' => 'sp_event',
						'name'      => 'id',
						'values'    => 'ID',
					);
					sp_dropdown_pages( $args );
					?>
				</label>
			</p>
			<?php do_action( 'sportspress_ajax_shortcode_form', 'event-officials' ); ?>
			<p class="submit">
				<input type="button" class="button-primary" value="<?php esc_attr_e( 'Insert Shortcode', 'sportspress' ); ?>" onclick="insertSportsPress('event_officials');" />
				<a class="button-secondary" onclick="tb_remove();" title="<?php esc_attr_e( 'Cancel', 'sportspress' ); ?>"><?php esc_html_e( 'Cancel', 'sportspress' ); ?></a>
			</p>
		</div>
		<?php
		self::scripts();
		die();
	}

	/**
	 * AJAX event_teams shortcode
	 */
	public function event_teams_shortcode() {
		?>
		<div class="wrap sp-thickbox-content" id="sp-thickbox-event_teams">
			<p>
				<label>
					<?php printf( esc_html__( 'Select %s:', 'sportspress' ), esc_html__( 'Event', 'sportspress' ) ); ?>
					<?php
					$args = array(
						'post_type' => 'sp_event',
						'name'      => 'id',
						'values'    => 'ID',
					);
					sp_dropdown_pages( $args );
					?>
				</label>
			</p>
			<?php do_action( 'sportspress_ajax_shortcode_form', 'event-teams' ); ?>
			<p class="submit">
				<input type="button" class="button-primary" value="<?php esc_attr_e( 'Insert Shortcode', 'sportspress' ); ?>" onclick="insertSportsPress('event_teams');" />
				<a class="button-secondary" onclick="tb_remove();" title="<?php esc_attr_e( 'Cancel', 'sportspress' ); ?>"><?php esc_html_e( 'Cancel', 'sportspress' ); ?></a>
			</p>
		</div>
		<?php
		self::scripts();
		die();
	}

	/**
	 * AJAX event_full shortcode
	 */
	public function event_full_shortcode() {
		?>
		<div class="wrap sp-thickbox-content" id="sp-thickbox-event_full">
			<p>
				<label>
					<?php printf( esc_html__( 'Select %s:', 'sportspress' ), esc_html__( 'Event', 'sportspress' ) ); ?>
					<?php
					$args = array(
						'post_type' => 'sp_event',
						'name'      => 'id',
						'values'    => 'ID',
					);
					sp_dropdown_pages( $args );
					?>
				</label>
			</p>
			<?php do_action( 'sportspress_ajax_shortcode_form', 'event-full' ); ?>
			<p class="submit">
				<input type="button" class="button-primary" value="<?php esc_attr_e( 'Insert Shortcode', 'sportspress' ); ?>" onclick="insertSportsPress('event_full');" />
				<a class="button-secondary" onclick="tb_remove();" title="<?php esc_attr_e( 'Cancel', 'sportspress' ); ?>"><?php esc_html_e( 'Cancel', 'sportspress' ); ?></a>
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
					<?php printf( esc_html__( 'Select %s:', 'sportspress' ), esc_html__( 'Calendar', 'sportspress' ) ); ?>
					<?php
					$args = array(
						'post_type'       => 'sp_calendar',
						'name'            => 'id',
						'values'          => 'ID',
						'show_option_all' => esc_html__( 'All', 'sportspress' ),
					);
					sp_dropdown_pages( $args );
					?>
				</label>
			</p>
			<p>
				<label>
					<?php esc_html_e( 'Team:', 'sportspress' ); ?>
					<?php
					$args = array(
						'post_type'       => 'sp_team',
						'show_option_all' => esc_html__( 'Default', 'sportspress' ),
						'name'            => 'team',
						'values'          => 'ID',
					);
					sp_dropdown_pages( $args );
					?>
				</label>
			</p>
			<p>
				<label>
					<?php esc_html_e( 'League:', 'sportspress' ); ?>
					<?php
					$args = array(
						'taxonomy'        => 'sp_league',
						'show_option_all' => esc_html__( 'Default', 'sportspress' ),
						'name'            => 'league',
						'values'          => 'term_id',
					);
					sp_dropdown_taxonomies( $args );
					?>
				</label>
			</p>
			<p>
				<label>
					<?php esc_html_e( 'Season:', 'sportspress' ); ?>
					<?php
					$args = array(
						'taxonomy'        => 'sp_season',
						'show_option_all' => esc_html__( 'Default', 'sportspress' ),
						'name'            => 'season',
						'values'          => 'term_id',
					);
					sp_dropdown_taxonomies( $args );
					?>
				</label>
			</p>
			<p>
				<label>
					<?php esc_html_e( 'Venue:', 'sportspress' ); ?>
					<?php
					$args = array(
						'taxonomy'        => 'sp_venue',
						'show_option_all' => esc_html__( 'Default', 'sportspress' ),
						'name'            => 'venue',
						'values'          => 'term_id',
					);
					sp_dropdown_taxonomies( $args );
					?>
				</label>
			</p>
			<p>
				<label>
					<?php esc_html_e( 'Status:', 'sportspress' ); ?>
					<?php
					$args = array(
						'name'                => 'status',
						'show_option_default' => esc_html__( 'Default', 'sportspress' ),
					);
					sp_dropdown_statuses( $args );
					?>
				</label>
			</p>
			<div class="sp-date-selector">
				<p><?php esc_html_e( 'Date:', 'sportspress' ); ?> 
					<?php
					$args = array(
						'name' => 'date',
						'id'   => 'date',
						// 'selected' => $date,
					);
					sp_dropdown_dates( $args );
					?>
				</p>
				<div class="sp-date-range">
					<p class="sp-date-range-absolute">
						<input type="text" class="sp-datepicker-from" name="date_from" value="default" size="10">
						:
						<input type="text" class="sp-datepicker-to" name="date_to" value="default" size="10">
					</p>

					<p class="sp-date-range-relative">
						<?php esc_html_e( 'Past', 'sportspress' ); ?>
						<input type="number" min="0" step="1" class="tiny-text" name="date_past" value="default">
						<?php esc_html_e( 'days', 'sportspress' ); ?>
						&rarr;
						<?php esc_html_e( 'Next', 'sportspress' ); ?>
						<input type="number" min="0" step="1" class="tiny-text" name="date_future" value="default">
						<?php esc_html_e( 'days', 'sportspress' ); ?>
					</p>

					<p class="sp-date-relative">
						<label>
							<input type="checkbox" name="date_relative" value="0" id="date_relative">
							<?php esc_html_e( 'Relative', 'sportspress' ); ?>
						</label>
					</p>
				</div>
			</div>
			<p>
				<label>
					<?php esc_html_e( 'Match Day:', 'sportspress' ); ?>
					<input type="text" size="3" name="day" id="day" placeholder="<?php esc_html_e( 'All', 'sportspress' ); ?>">
				</label>
			</p>
			<p>
				<label>
					<?php esc_html_e( 'Display link to view all events', 'sportspress' ); ?>
					<input type="checkbox" name="show_all_events_link" id="show_all_events_link">
				</label>
			</p>
			<?php do_action( 'sportspress_ajax_shortcode_form', 'event-calendar' ); ?>
			<p class="submit">
				<input type="button" class="button-primary" value="<?php esc_html_e( 'Insert Shortcode', 'sportspress' ); ?>" onclick="insertSportsPress('event_calendar');" />
				<a class="button-secondary" onclick="tb_remove();" title="<?php esc_html_e( 'Cancel', 'sportspress' ); ?>"><?php esc_html_e( 'Cancel', 'sportspress' ); ?></a>
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
					<?php esc_html_e( 'Title:', 'sportspress' ); ?>
					<input class="regular-text" type="text" name="title">
				</label>
			</p>
			<p>
				<label>
					<?php printf( esc_html__( 'Select %s:', 'sportspress' ), esc_attr__( 'Calendar', 'sportspress' ) ); ?>
					<?php
					$args = array(
						'post_type'       => 'sp_calendar',
						'show_option_all' => esc_html__( 'All', 'sportspress' ),
						'name'            => 'id',
						'values'          => 'ID',
					);
					sp_dropdown_pages( $args );
					?>
				</label>
			</p>
			<p>
				<label>
					<?php esc_html_e( 'Team:', 'sportspress' ); ?>
					<?php
					$args = array(
						'post_type'       => 'sp_team',
						'show_option_all' => esc_html__( 'Default', 'sportspress' ),
						'name'            => 'team',
						'values'          => 'ID',
					);
					sp_dropdown_pages( $args );
					?>
				</label>
			</p>
			<p>
				<label>
					<?php esc_html_e( 'League:', 'sportspress' ); ?>
					<?php
					$args = array(
						'taxonomy'        => 'sp_league',
						'show_option_all' => esc_html__( 'Default', 'sportspress' ),
						'name'            => 'league',
						'values'          => 'term_id',
					);
					sp_dropdown_taxonomies( $args );
					?>
				</label>
			</p>
			<p>
				<label>
					<?php esc_html_e( 'Season:', 'sportspress' ); ?>
					<?php
					$args = array(
						'taxonomy'        => 'sp_season',
						'show_option_all' => esc_html__( 'Default', 'sportspress' ),
						'name'            => 'season',
						'values'          => 'term_id',
					);
					sp_dropdown_taxonomies( $args );
					?>
				</label>
			</p>
			<p>
				<label>
					<?php esc_html_e( 'Venue:', 'sportspress' ); ?>
					<?php
					$args = array(
						'taxonomy'        => 'sp_venue',
						'show_option_all' => esc_html__( 'Default', 'sportspress' ),
						'name'            => 'venue',
						'values'          => 'term_id',
					);
					sp_dropdown_taxonomies( $args );
					?>
				</label>
			</p>
			<p>
				<label>
					<?php esc_html_e( 'Status:', 'sportspress' ); ?>
					<?php
					$args = array(
						'name'                => 'status',
						'show_option_default' => esc_html__( 'Default', 'sportspress' ),
					);
					sp_dropdown_statuses( $args );
					?>
				</label>
			</p>
			<p>
				<label>
					<?php esc_html_e( 'Format:', 'sportspress' ); ?>
					<select name="format" class="postform">
						<option value="default">Default</option>
						<option value="all">All</option>
						<?php foreach ( SP()->formats->event as $key => $format ) : ?>
							<option value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $format ); ?></option>
						<?php endforeach; ?>
					</select>
				</label>
			</p>
			<div class="sp-date-selector">
				<p><?php esc_html_e( 'Date:', 'sportspress' ); ?> 
					<?php
					$args = array(
						'name' => 'date',
						'id'   => 'date',
						// 'selected' => $date,
					);
					sp_dropdown_dates( $args );
					?>
				</p>
				<div class="sp-date-range">
					<p class="sp-date-range-absolute">
						<input type="text" class="sp-datepicker-from" name="date_from" value="default" size="10">
						:
						<input type="text" class="sp-datepicker-to" name="date_to" value="default" size="10">
					</p>

					<p class="sp-date-range-relative">
						<?php esc_html_e( 'Past', 'sportspress' ); ?>
						<input type="number" min="0" step="1" class="tiny-text" name="date_past" value="default">
						<?php esc_html_e( 'days', 'sportspress' ); ?>
						&rarr;
						<?php esc_html_e( 'Next', 'sportspress' ); ?>
						<input type="number" min="0" step="1" class="tiny-text" name="date_future" value="default">
						<?php esc_html_e( 'days', 'sportspress' ); ?>
					</p>

					<p class="sp-date-relative">
						<label>
							<input type="checkbox" name="date_relative" value="0" id="date_relative">
							<?php esc_html_e( 'Relative', 'sportspress' ); ?>
						</label>
					</p>
				</div>
			</div>
			<p>
				<label>
					<?php esc_html_e( 'Match Day:', 'sportspress' ); ?>
					<input type="text" size="3" name="day" id="day" placeholder="<?php esc_attr_e( 'All', 'sportspress' ); ?>">
				</label>
			</p>
			<p>
				<label>
					<?php esc_html_e( 'Number of events to show:', 'sportspress' ); ?>
					<input type="text" size="3" name="number" id="number" value="5">
				</label>
			</p>
			<p>
				<label>
					<?php esc_html_e( 'Sort Order:', 'sportspress' ); ?>
					<select id="order" name="order">
						<option value="default"><?php esc_html_e( 'Default', 'sportspress' ); ?></option>
						<option value="ASC"><?php esc_html_e( 'Ascending', 'sportspress' ); ?></option>
						<option value="DESC"><?php esc_html_e( 'Descending', 'sportspress' ); ?></option>
					</select>
				</label>
			</p>
			<p class="sp-prefs">
				<?php esc_html_e( 'Columns:', 'sportspress' ); ?><br>
				<?php
				$the_columns = array(
					'event'       => esc_attr__( 'Event', 'sportspress' ),
					'teams'       => esc_attr__( 'Teams', 'sportspress' ),
					'time'        => esc_attr__( 'Time', 'sportspress' ),
					'league'      => esc_attr__( 'League', 'sportspress' ),
					'season'      => esc_attr__( 'Season', 'sportspress' ),
					'venue'       => esc_attr__( 'Venue', 'sportspress' ),
					'article'     => esc_attr__( 'Article', 'sportspress' ),
					'event_specs' => esc_attr__( 'Specs', 'sportspress' ),
				);
				$field_name  = 'columns[]';
				$field_id    = 'columns';
				?>
				<?php foreach ( $the_columns as $key => $label ) : ?>
					<label class="button"><input name="<?php echo esc_attr( $field_name ); ?>" type="checkbox" id="<?php echo esc_attr( $field_id ) . '-' . esc_attr( $key ); ?>" value="<?php echo esc_attr( $key ); ?>" checked="checked"><?php echo esc_html( $label ); ?></label>
				<?php endforeach; ?>
			</p>
			<p>
				<label>
					<input type="checkbox" name="show_all_events_link" id="show_all_events_link">
					<?php esc_html_e( 'Display link to view all events', 'sportspress' ); ?>
				</label>
			</p>
			<?php do_action( 'sportspress_ajax_shortcode_form', 'event-list' ); ?>
			<p class="submit">
				<input type="button" class="button-primary" value="<?php esc_attr_e( 'Insert Shortcode', 'sportspress' ); ?>" onclick="insertSportsPress('event_list');" />
				<a class="button-secondary" onclick="tb_remove();" title="<?php esc_attr_e( 'Cancel', 'sportspress' ); ?>"><?php esc_html_e( 'Cancel', 'sportspress' ); ?></a>
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
					<?php esc_html_e( 'Title:', 'sportspress' ); ?>
					<input class="regular-text" type="text" name="title">
				</label>
			</p>
			<p>
				<label>
					<?php esc_html_e( 'Event:', 'sportspress' ); ?>
					<input class="regular-text" type="number" name="event">
				</label>
			</p>
			<p>
				<label>
					<?php printf( esc_html__( 'Select %s:', 'sportspress' ), esc_html__( 'Calendar', 'sportspress' ) ); ?>
					<?php
					$args = array(
						'post_type'       => 'sp_calendar',
						'show_option_all' => esc_attr__( 'All', 'sportspress' ),
						'name'            => 'id',
						'values'          => 'ID',
					);
					sp_dropdown_pages( $args );
					?>
				</label>
			</p>
			<p>
				<label>
					<?php esc_html_e( 'Team:', 'sportspress' ); ?>
					<?php
					$args = array(
						'post_type'       => 'sp_team',
						'show_option_all' => esc_attr__( 'Default', 'sportspress' ),
						'name'            => 'team',
						'values'          => 'ID',
					);
					sp_dropdown_pages( $args );
					?>
				</label>
			</p>
			<p>
				<label>
					<?php esc_html_e( 'League:', 'sportspress' ); ?>
					<?php
					$args = array(
						'taxonomy'        => 'sp_league',
						'show_option_all' => esc_attr__( 'Default', 'sportspress' ),
						'name'            => 'league',
						'values'          => 'term_id',
					);
					sp_dropdown_taxonomies( $args );
					?>
				</label>
			</p>
			<p>
				<label>
					<?php esc_html_e( 'Season:', 'sportspress' ); ?>
					<?php
					$args = array(
						'taxonomy'        => 'sp_season',
						'show_option_all' => esc_attr__( 'Default', 'sportspress' ),
						'name'            => 'season',
						'values'          => 'term_id',
					);
					sp_dropdown_taxonomies( $args );
					?>
				</label>
			</p>
			<p>
				<label>
					<?php esc_html_e( 'Venue:', 'sportspress' ); ?>
					<?php
					$args = array(
						'taxonomy'        => 'sp_venue',
						'show_option_all' => esc_attr__( 'Default', 'sportspress' ),
						'name'            => 'venue',
						'values'          => 'term_id',
					);
					sp_dropdown_taxonomies( $args );
					?>
				</label>
			</p>
			<p>
				<label>
					<?php esc_html_e( 'Status:', 'sportspress' ); ?>
					<?php
					$args = array(
						'name'                => 'status',
						'show_option_default' => esc_attr__( 'Default', 'sportspress' ),
					);
					sp_dropdown_statuses( $args );
					?>
				</label>
			</p>
			<p>
				<label>
					<?php esc_html_e( 'Format:', 'sportspress' ); ?>
					<select name="format" class="postform">
					<option value="default">Default</option>
					<option value="all">All</option>
					<?php foreach ( SP()->formats->event as $key => $format ) : ?>
					<option value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $format ); ?></option>
					<?php endforeach; ?>
					</select>
				</label>
			</p>
			<div class="sp-date-selector">
				<p><?php esc_html_e( 'Date:', 'sportspress' ); ?> 
					<?php
					$args = array(
						'name' => 'date',
						'id'   => 'date',
						// 'selected' => $date,
					);
					sp_dropdown_dates( $args );
					?>
				</p>
				<div class="sp-date-range">
					<p class="sp-date-range-absolute">
						<input type="text" class="sp-datepicker-from" name="date_from" value="default" size="10">
						:
						<input type="text" class="sp-datepicker-to" name="date_to" value="default" size="10">
					</p>

					<p class="sp-date-range-relative">
						<?php esc_html_e( 'Past', 'sportspress' ); ?>
						<input type="number" min="0" step="1" class="tiny-text" name="date_past" value="default">
						<?php esc_html_e( 'days', 'sportspress' ); ?>
						&rarr;
						<?php esc_html_e( 'Next', 'sportspress' ); ?>
						<input type="number" min="0" step="1" class="tiny-text" name="date_future" value="default">
						<?php esc_attr_e( 'days', 'sportspress' ); ?>
					</p>

					<p class="sp-date-relative">
						<label>
							<input type="checkbox" name="date_relative" value="0" id="date_relative">
							<?php esc_html_e( 'Relative', 'sportspress' ); ?>
						</label>
					</p>
				</div>
			</div>
			<p>
				<label>
					<?php esc_html_e( 'Match Day:', 'sportspress' ); ?>
					<input type="text" size="3" name="day" id="day" placeholder="<?php esc_attr_e( 'All', 'sportspress' ); ?>">
				</label>
			</p>
			<p>
				<label>
					<?php esc_html_e( 'Number of events to show:', 'sportspress' ); ?>
					<input type="text" size="3" name="number" id="number" value="5">
				</label>
			</p>
			<p>
				<label>
					<?php esc_html_e( 'Sort by:', 'sportspress' ); ?>
					<select id="orderby" name="orderby">
						<option value="default"><?php esc_html_e( 'Default', 'sportspress' ); ?></option>
						<option value="date"><?php esc_html_e( 'Date', 'sportspress' ); ?></option>
						<option value="day"><?php esc_html_e( 'Match Day', 'sportspress' ); ?></option>
					</select>
				</label>
			</p>
			<p>
				<label>
					<?php esc_html_e( 'Sort Order:', 'sportspress' ); ?>
					<select id="order" name="order">
						<option value="default"><?php esc_html_e( 'Default', 'sportspress' ); ?></option>
						<option value="ASC"><?php esc_html_e( 'Ascending', 'sportspress' ); ?></option>
						<option value="DESC"><?php esc_html_e( 'Descending', 'sportspress' ); ?></option>
					</select>
				</label>
			</p>
			<p>
				<label>
					<input type="checkbox" name="show_all_events_link" id="show_all_events_link">
					<?php esc_html_e( 'Display link to view all events', 'sportspress' ); ?>
				</label>
			</p>
			<?php do_action( 'sportspress_ajax_shortcode_form', 'event-blocks' ); ?>
			<p class="submit">
				<input type="button" class="button-primary" value="<?php esc_attr_e( 'Insert Shortcode', 'sportspress' ); ?>" onclick="insertSportsPress('event_blocks');" />
				<a class="button-secondary" onclick="tb_remove();" title="<?php esc_attr_e( 'Cancel', 'sportspress' ); ?>"><?php esc_html_e( 'Cancel', 'sportspress' ); ?></a>
			</p>
		</div>
		<?php
		self::scripts();
		die();
	}

	/**
	 * AJAX team_standings shortcode
	 */
	public function team_standings_shortcode() {
		?>
		<div class="wrap sp-thickbox-content" id="sp-thickbox-team_standings">
			<p>
				<label>
					<?php esc_html_e( 'Title:', 'sportspress' ); ?>
					<input class="regular-text" type="text" name="title">
				</label>
			</p>
			<p>
				<label>
					<?php printf( esc_html__( 'Select %s:', 'sportspress' ), esc_html__( 'League Table', 'sportspress' ) ); ?>
					<?php
					$args = array(
						'post_type' => 'sp_table',
						'name'      => 'id',
						'values'    => 'ID',
					);
					sp_dropdown_pages( $args );
					?>
				</label>
			</p>
			<p>
				<label>
					<?php esc_html_e( 'Number of teams to show:', 'sportspress' ); ?>
					<input type="text" size="3" name="number" id="number" value="5">
				</label>
			</p>
			<p class="sp-prefs">
				<?php esc_html_e( 'Columns:', 'sportspress' ); ?><br>
				<?php
				$args        = array(
					'post_type'      => 'sp_column',
					'numberposts'    => -1,
					'posts_per_page' => -1,
					'orderby'        => 'menu_order',
					'order'          => 'ASC',
				);
				$the_columns = get_posts( $args );

				$field_name = 'columns[]';
				$field_id   = 'columns';
				?>
				<?php foreach ( $the_columns as $column ) : ?>
					<label class="button"><input name="<?php echo esc_attr( $field_name ); ?>" type="checkbox" id="<?php echo esc_attr( $field_id ) . '-' . esc_attr( $column->post_name ); ?>" value="<?php echo esc_attr( $column->post_name ); ?>" checked="checked"><?php echo esc_html( $column->post_title ); ?></label>
				<?php endforeach; ?>
			</p>
			<p>
				<label>
					<input type="checkbox" name="show_team_logo" id="show_team_logo">
					<?php esc_html_e( 'Display logos', 'sportspress' ); ?>
				</label>
			</p>
			<p>
				<label>
					<input type="checkbox" name="show_full_table_link" id="show_full_table_link">
					<?php esc_html_e( 'Display link to view full table', 'sportspress' ); ?>
				</label>
			</p>
			<p>
				<?php esc_html_e( 'Event Status:', 'sportspress' ); ?><br/>
				<label>
					<input type="checkbox" name="show_published_events" id="show_published_events" checked>
					<?php esc_html_e( 'Include Published/Played Events with results', 'sportspress' ); ?>
				</label>
				<br/>
				<label>
					<input type="checkbox" name="show_future_events" id="show_future_events" checked>
					<?php esc_html_e( 'Include Scheduled/Future Events with results', 'sportspress' ); ?>
				</label>
			</p>
			<?php do_action( 'sportspress_ajax_shortcode_form', 'league-table' ); ?>
			<p class="submit">
				<input type="button" class="button-primary" value="<?php esc_attr_e( 'Insert Shortcode', 'sportspress' ); ?>" onclick="insertSportsPress('team_standings');" />
				<a class="button-secondary" onclick="tb_remove();" title="<?php esc_attr_e( 'Cancel', 'sportspress' ); ?>"><?php esc_html_e( 'Cancel', 'sportspress' ); ?></a>
			</p>
		</div>
		<?php
		self::scripts();
		die();
	}

	/**
	 * AJAX team_gallery shortcode
	 */
	public function team_gallery_shortcode() {
		?>
		<div class="wrap sp-thickbox-content" id="sp-thickbox-team_gallery">
			<p>
				<label>
					<?php esc_html_e( 'Title:', 'sportspress' ); ?>
					<input class="regular-text" type="text" name="title">
				</label>
			</p>
			<p>
				<label>
					<?php printf( esc_html__( 'Select %s:', 'sportspress' ), esc_html__( 'League Table', 'sportspress' ) ); ?>
					<?php
					$args = array(
						'post_type' => 'sp_table',
						'name'      => 'id',
						'values'    => 'ID',
					);
					sp_dropdown_pages( $args );
					?>
				</label>
			</p>
			<p>
				<label>
					<?php esc_html_e( 'Number of teams to show:', 'sportspress' ); ?>
					<input type="text" size="3" name="number" id="number" value="5">
				</label>
			</p>
			<p>
				<label>
					<?php esc_html_e( 'Columns:', 'sportspress' ); ?>
					<input type="text" size="3" name="columns" id="columns" value="3">
				</label>
			</p>
			<p>
				<label>
					<?php esc_html_e( 'Order by', 'sportspress' ); ?>:
					<select name="orderby">
						<option value="default"><?php esc_html_e( 'Rank', 'sportspress' ); ?></option>
						<option value="name"><?php esc_html_e( 'Alphabetical', 'sportspress' ); ?></option>
						<option value="rand"><?php esc_html_e( 'Random', 'sportspress' ); ?></option>
					</select>
				</label>
			</p>
			<p>
				<label>
					<input type="checkbox" name="show_full_table_link" id="show_full_table_link">
					<?php esc_html_e( 'Display link to view full table', 'sportspress' ); ?>
				</label>
			</p>
			<?php do_action( 'sportspress_ajax_shortcode_form', 'league-table' ); ?>
			<p class="submit">
				<input type="button" class="button-primary" value="<?php esc_attr_e( 'Insert Shortcode', 'sportspress' ); ?>" onclick="insertSportsPress('team_gallery');" />
				<a class="button-secondary" onclick="tb_remove();" title="<?php esc_attr_e( 'Cancel', 'sportspress' ); ?>"><?php esc_html_e( 'Cancel', 'sportspress' ); ?></a>
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
					<?php printf( esc_html__( 'Select %s:', 'sportspress' ), esc_html__( 'Player', 'sportspress' ) ); ?>
					<?php
					$args = array(
						'post_type' => 'sp_player',
						'name'      => 'id',
						'values'    => 'ID',
					);
					sp_dropdown_pages( $args );
					?>
				</label>
			</p>
			<?php do_action( 'sportspress_ajax_shortcode_form', 'player-details' ); ?>
			<p class="submit">
				<input type="button" class="button-primary" value="<?php esc_attr_e( 'Insert Shortcode', 'sportspress' ); ?>" onclick="insertSportsPress('player_details');" />
				<a class="button-secondary" onclick="tb_remove();" title="<?php esc_attr_e( 'Cancel', 'sportspress' ); ?>"><?php esc_html_e( 'Cancel', 'sportspress' ); ?></a>
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
					<?php printf( esc_html__( 'Select %s:', 'sportspress' ), esc_html__( 'Player', 'sportspress' ) ); ?>
					<?php
					$args = array(
						'post_type' => 'sp_player',
						'name'      => 'id',
						'values'    => 'ID',
					);
					sp_dropdown_pages( $args );
					?>
				</label>
			</p>
			<?php do_action( 'sportspress_ajax_shortcode_form', 'player-statistics' ); ?>
			<p class="submit">
				<input type="button" class="button-primary" value="<?php esc_attr_e( 'Insert Shortcode', 'sportspress' ); ?>" onclick="insertSportsPress('player_statistics');" />
				<a class="button-secondary" onclick="tb_remove();" title="<?php esc_attr_e( 'Cancel', 'sportspress' ); ?>"><?php esc_html_e( 'Cancel', 'sportspress' ); ?></a>
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
					<?php esc_html_e( 'Title:', 'sportspress' ); ?>
					<input class="regular-text" type="text" name="title">
				</label>
			</p>
			<p>
				<label>
					<?php printf( esc_html__( 'Select %s:', 'sportspress' ), esc_html__( 'Player List', 'sportspress' ) ); ?>
					<?php
					$args = array(
						'post_type' => 'sp_list',
						'name'      => 'id',
						'values'    => 'ID',
					);
					sp_dropdown_pages( $args );
					?>
				</label>
			</p>
			<p>
				<label>
					<?php esc_html_e( 'Team:', 'sportspress' ); ?>
					<?php
					$args = array(
						'post_type'       => 'sp_team',
						'show_option_all' => esc_attr__( 'Default', 'sportspress' ),
						'name'            => 'team',
						'values'          => 'ID',
					);
					sp_dropdown_pages( $args );
					?>
				</label>
			</p>
			<p>
				<label>
					<?php esc_html_e( 'League:', 'sportspress' ); ?>
					<?php
					$args = array(
						'taxonomy'        => 'sp_league',
						'show_option_all' => esc_attr__( 'Default', 'sportspress' ),
						'name'            => 'league',
						'values'          => 'term_id',
					);
					sp_dropdown_taxonomies( $args );
					?>
				</label>
			</p>
			<p>
				<label>
					<?php esc_html_e( 'Season:', 'sportspress' ); ?>
					<?php
					$args = array(
						'taxonomy'        => 'sp_season',
						'show_option_all' => esc_attr__( 'Default', 'sportspress' ),
						'name'            => 'season',
						'values'          => 'term_id',
					);
					sp_dropdown_taxonomies( $args );
					?>
				</label>
			</p>
			<p>
				<label>
					<?php esc_html_e( 'Number of players to show:', 'sportspress' ); ?>
					<input type="text" size="3" name="number" id="number" value="5">
				</label>
			</p>
			<p class="sp-prefs">
				<?php esc_html_e( 'Columns:', 'sportspress' ); ?><br>
				<?php
				$args        = array(
					'post_type'      => array( 'sp_metric', 'sp_performance', 'sp_statistic' ),
					'numberposts'    => -1,
					'posts_per_page' => -1,
					'orderby'        => 'menu_order',
					'order'          => 'ASC',
				);
				$the_columns = get_posts( $args );

				$field_name = 'columns[]';
				$field_id   = 'columns';
				?>
				<label class="button"><input name="columns[]" type="checkbox" id="columns-number" value="number" checked="checked"><?php esc_html_e( '#', 'sportspress' ); ?></label>
				<label class="button"><input name="columns[]" type="checkbox" id="columns-team" value="team" checked="checked"><?php esc_html_e( 'Team', 'sportspress' ); ?></label>
				<label class="button"><input name="columns[]" type="checkbox" id="columns-position" value="position" checked="checked"><?php esc_html_e( 'Position', 'sportspress' ); ?></label>
				<?php foreach ( $the_columns as $column ) : ?>
					<label class="button"><input name="<?php echo esc_attr( $field_name ); ?>" type="checkbox" id="<?php echo esc_attr( $field_id ) . '-' . esc_attr( $column->post_name ); ?>" value="<?php echo esc_attr( $column->post_name ); ?>" checked="checked"><?php echo esc_html( $column->post_title ); ?></label>
				<?php endforeach; ?>
			</p>
			<p>
				<label>
					<?php esc_html_e( 'Sort by:', 'sportspress' ); ?>
					<?php
					$args = array(
						'prepend_options' => array(
							'default'      => esc_attr__( 'Default', 'sportspress' ),
							'number'       => esc_attr__( 'Squad Number', 'sportspress' ),
							'name'         => esc_attr__( 'Name', 'sportspress' ),
							'eventsplayed' => esc_attr__( 'Played', 'sportspress' ),
						),
						'post_type'       => array( 'sp_metric', 'sp_performance', 'sp_statistic' ),
						'name'            => 'orderby',
						'id'              => 'orderby',
						'values'          => 'slug',
					);
					sp_dropdown_pages( $args );
					?>
				</label>
			</p>
			<p>
				<label>
					<?php esc_html_e( 'Sort Order:', 'sportspress' ); ?>
					<select id="order" name="order">
						<option value=""><?php esc_html_e( 'Default', 'sportspress' ); ?></option>
						<option value="ASC"><?php esc_html_e( 'Ascending', 'sportspress' ); ?></option>
						<option value="DESC"><?php esc_html_e( 'Descending', 'sportspress' ); ?></option>
					</select>
				</label>
			</p>
			<p>
				<label>
					<input type="checkbox" name="show_all_players_link" id="show_all_players_link">
					<?php esc_html_e( 'Display link to view all players', 'sportspress' ); ?>
				</label>
			</p>
			<?php do_action( 'sportspress_ajax_shortcode_form', 'player-list' ); ?>
			<p class="submit">
				<input type="button" class="button-primary" value="<?php esc_attr_e( 'Insert Shortcode', 'sportspress' ); ?>" onclick="insertSportsPress('player_list');" />
				<a class="button-secondary" onclick="tb_remove();" title="<?php esc_attr_e( 'Cancel', 'sportspress' ); ?>"><?php esc_html_e( 'Cancel', 'sportspress' ); ?></a>
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
					<?php esc_html_e( 'Title:', 'sportspress' ); ?>
					<input class="regular-text" type="text" name="title">
				</label>
			</p>
			<p>
				<label>
					<?php printf( esc_html__( 'Select %s:', 'sportspress' ), esc_html__( 'Player List', 'sportspress' ) ); ?>
					<?php
					$args = array(
						'post_type' => 'sp_list',
						'name'      => 'id',
						'values'    => 'ID',
					);
					sp_dropdown_pages( $args );
					?>
				</label>
			</p>
			<p>
				<label>
					<?php esc_html_e( 'Number of players to show:', 'sportspress' ); ?>
					<input type="text" size="3" name="number" id="number" value="5">
				</label>
			</p>
			<p>
				<label>
					<?php esc_html_e( 'Columns:', 'sportspress' ); ?>
					<input type="text" size="3" name="columns" id="columns" value="3">
				</label>
			</p>
			<p>
				<label>
					<?php esc_html_e( 'Sort by:', 'sportspress' ); ?>
					<?php
					$args = array(
						'prepend_options' => array(
							'default'      => esc_attr__( 'Default', 'sportspress' ),
							'number'       => esc_attr__( 'Squad Number', 'sportspress' ),
							'name'         => esc_attr__( 'Name', 'sportspress' ),
							'eventsplayed' => esc_attr__( 'Played', 'sportspress' ),
						),
						'post_type'       => array( 'sp_metric', 'sp_performance', 'sp_statistic' ),
						'name'            => 'orderby',
						'id'              => 'orderby',
						'values'          => 'slug',
					);
					sp_dropdown_pages( $args );
					?>
				</label>
			</p>
			<p>
				<label>
					<?php esc_html_e( 'Sort Order:', 'sportspress' ); ?>
					<select id="order" name="order">
						<option value=""><?php esc_html_e( 'Default', 'sportspress' ); ?></option>
						<option value="ASC"><?php esc_html_e( 'Ascending', 'sportspress' ); ?></option>
						<option value="DESC"><?php esc_html_e( 'Descending', 'sportspress' ); ?></option>
					</select>
				</label>
			</p>
			<p>
				<label>
					<input type="checkbox" name="show_all_players_link" id="show_all_players_link">
					<?php esc_html_e( 'Display link to view all players', 'sportspress' ); ?>
				</label>
			</p>
			<?php do_action( 'sportspress_ajax_shortcode_form', 'player-gallery' ); ?>
			<p class="submit">
				<input type="button" class="button-primary" value="<?php esc_attr_e( 'Insert Shortcode', 'sportspress' ); ?>" onclick="insertSportsPress('player_gallery');" />
				<a class="button-secondary" onclick="tb_remove();" title="<?php esc_attr_e( 'Cancel', 'sportspress' ); ?>"><?php esc_html_e( 'Cancel', 'sportspress' ); ?></a>
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
					args.date = $div.find('[name=date]').val();
					args.date_from = $div.find('[name=date_from]').val();
					args.date_to = $div.find('[name=date_to]').val();
					args.date_past = $div.find('[name=date_past]').val();
					args.date_future = $div.find('[name=date_future]').val();
					args.date_relative = $div.find('[name=date_relative]:checked').length;
					args.day = $div.find('[name=day]').val();
					args.show_all_events_link = $div.find('[name=show_all_events_link]:checked').length;
				} else if ( 'event_list' == type ) {
					args.title = $div.find('[name=title]').val();
					args.team = $div.find('[name=team]').val();
					args.league = $div.find('[name=league]').val();
					args.season = $div.find('[name=season]').val();
					args.venue = $div.find('[name=venue]').val();
					args.status = $div.find('[name=status]').val();
					args.format = $div.find('[name=format]').val();
					args.date = $div.find('[name=date]').val();
					args.date_from = $div.find('[name=date_from]').val();
					args.date_to = $div.find('[name=date_to]').val();
					args.date_past = $div.find('[name=date_past]').val();
					args.date_future = $div.find('[name=date_future]').val();
					args.date_relative = $div.find('[name=date_relative]:checked').length;
					args.day = $div.find('[name=day]').val();
					args.number = $div.find('[name=number]').val();
					args.order = $div.find('[name=order]').val();
					args.columns = $div.find('[name="columns[]"]:checked').map(function() { return this.value; }).get().join(',');
					args.show_all_events_link = $div.find('[name=show_all_events_link]:checked').length;
				} else if ( 'event_blocks' == type ) {
					args.title = $div.find('[name=title]').val();
					args.event = $div.find('[name=event]').val();
					args.team = $div.find('[name=team]').val();
					args.league = $div.find('[name=league]').val();
					args.season = $div.find('[name=season]').val();
					args.venue = $div.find('[name=venue]').val();
					args.status = $div.find('[name=status]').val();
					args.format = $div.find('[name=format]').val();
					args.date = $div.find('[name=date]').val();
					args.date_from = $div.find('[name=date_from]').val();
					args.date_to = $div.find('[name=date_to]').val();
					args.date_past = $div.find('[name=date_past]').val();
					args.date_future = $div.find('[name=date_future]').val();
					args.date_relative = $div.find('[name=date_relative]:checked').length;
					args.day = $div.find('[name=day]').val();
					args.number = $div.find('[name=number]').val();
					args.orderby = $div.find('[name=orderby]').val();
					args.order = $div.find('[name=order]').val();
					args.show_all_events_link = $div.find('[name=show_all_events_link]:checked').length;
				} else if ( 'team_standings' == type ) {
					args.title = $div.find('[name=title]').val();
					args.number = $div.find('[name=number]').val();
					args.columns = $div.find('[name="columns[]"]:checked').map(function() { return this.value; }).get().join(',');
					args.show_team_logo = $div.find('[name=show_team_logo]:checked').length;
					args.show_published_events = $div.find('[name=show_published_events]:checked').length;
					args.show_future_events = $div.find('[name=show_future_events]:checked').length;
					args.show_full_table_link = $div.find('[name=show_full_table_link]:checked').length;
				} else if ( 'team_gallery' == type ) {
					args.title = $div.find('[name=title]').val();
					args.number = $div.find('[name=number]').val();
					args.columns = $div.find('[name=columns]').val();
					args.orderby = $div.find('[name=orderby]').val();
					args.show_full_table_link = $div.find('[name=show_full_table_link]:checked').length;
				} else if ( 'player_list' == type ) {
					args.title = $div.find('[name=title]').val();
					args.number = $div.find('[name=number]').val();
					args.team = $div.find('[name=team]').val();
					args.seasons = $div.find('[name=season]').val();
					args.leagues = $div.find('[name=league]').val();
					args.columns = $div.find('[name="columns[]"]:checked').map(function() { return this.value; }).get().join(',');
					args.orderby = $div.find('[name=orderby]').val();
					args.order = $div.find('[name=order]').val();
					args.show_all_players_link = $div.find('[name=show_all_players_link]:checked').length;
				} else if ( 'player_gallery' == type ) {
					args.title = $div.find('[name=title]').val();
					args.number = $div.find('[name=number]').val();
					args.columns = $div.find('[name=columns]').val();
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
		<script type="text/javascript">
			jQuery(document).ready(function($){
				// Datepicker
				$(".sp-datepicker").datepicker({
					dateFormat : "yy-mm-dd"
				});
				$(".sp-datepicker-from").datepicker({
					dateFormat : "yy-mm-dd",
					onClose: function( selectedDate ) {
						$(this).closest(".sp-date-selector").find(".sp-datepicker-to").datepicker("option", "minDate", selectedDate);
					}
				});
				$(".sp-datepicker-to").datepicker({
					dateFormat : "yy-mm-dd",
					onClose: function( selectedDate ) {
						$(this).closest(".sp-date-selector").find(".sp-datepicker-from").datepicker("option", "maxDate", selectedDate);
					}
				});

				// Show or hide datepicker
				$(".sp-date-selector select").change(function() {
					if ( $(this).val() == "range" ) {
						$(this).closest(".sp-date-selector").find(".sp-date-range").show();
					} else {
						$(this).closest(".sp-date-selector").find(".sp-date-range").hide();
					}
				});
				$(".sp-date-selector select").trigger("change");

				// Toggle date range selectors
				$(".sp-date-relative input").change(function() {
					$relative = $( this ).closest( ".sp-date-relative" ).siblings( ".sp-date-range-relative" ).toggle( 0, $( this ).attr( "checked" ) );
					$absolute = $( this ).closest( ".sp-date-relative" ).siblings( ".sp-date-range-absolute" ).toggle( 0, $( this ).attr( "checked" ) );
					
					if ( $( this ).is( ":checked" ) ) {
						$relative.show();
						$absolute.hide();
					} else {
						$absolute.show();
						$relative.hide();
					}
				});
				$(".sp-date-selector input").trigger("change");
					});
		</script>
		<?php
	}
}

new SP_AJAX();
