<?php
/**
 * @package SportsPress Highlights
 */
/*
Plugin Name: SportsPress Highlights
Plugin URI: http://sportspress.com/
Description: Add Event Highlights to SportsPress Events.
Version: 0.1
Author: ThemeBoy
Author URI: http://themeboy.com/
*/

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

// Define version and plugin location
define( 'SPORTSPRESS_HIGHLIGHTS_VERSION', '0.1' );
define( 'SPORTSPRESS_HIGHLIGHTS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'SPORTSPRESS_HIGHLIGHTS_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'SPORTSPRESS_HIGHLIGHTS_PLUGIN_FILE', __FILE__ );

function sportspress_event_highlights_meta( $post ) {
	$teams = get_post_meta( $post->ID, 'sp_team', false );
	$players = get_post_meta( $post->ID, 'sp_player', false );
	$highlights = get_post_meta( $post->ID, 'sp_highlight', false );

	$highlights = array(
		array(
			'player' => 118,
			'statistic' => 'goals',
			'time' => 12,
		),
		array(
			'player' => 181,
			'statistic' => 'redcards',
			'time' => 27,
		),
		array(
			'player' => 120,
			'statistic' => 'goals',
			'time' => 75,
		),
		array(
			'player' => 99,
			'statistic' => 'assists',
			'time' => 75,
		),
		array(
			'player' => 118,
			'statistic' => 'yellowcards',
			'time' => 86,
		),
	);

	// Get player options
	$args = array(
		'post_type' => 'sp_player',
		'posts_per_page' => -1,
	    'include' => $players,
	);
	$player_options = get_posts( $args );

	// Get statistic options
	$args = array(
		'post_type' => 'sp_statistic',
		'posts_per_page' => -1,
	);
	$statistic_options = get_posts( $args );
	?>
	<div class="sp-data-table-container">
		<table class="widefat sp-data-table sp-highlight-table">
			<thead>
				<tr>
					<th scope="col"><?php _e( 'Player', 'sportspress' ); ?></th>
					<th scope="col"><?php _e( 'Statistic', 'sportspress' ); ?></th>
					<th scope="col" class="time-column"><?php _e( 'Time', 'sportspress' ); ?></th>
					<th scope="col"><?php _e( 'Notes', 'sportspress' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php if ( is_array( $highlights ) ): $i = 0; foreach ( $highlights as $highlight ): ?>
				<tr class="sp-row sp-post<?php if ( $i % 2 == 0 ) echo ' alternate'; ?>">
					<td>
						<?php
						$selected = sportspress_array_value( $highlight, 'player', null );
						?>
						<select name="highlight[<?php echo $i ?>][player]" class="sp-player-id-select chosen-select<?php if ( is_rtl() ): ?> chosen-rtl<?php endif; ?>" data-placeholder="<?php _e( '&mdash; Select &mdash;', 'sportspress' ) ?>">
							<option value=""></option>
							<?php $j = 0; foreach ( $teams as $team ): $team_players = array_filter( sportspress_array_between( (array)$players, 0, $j ) );?>
							<optgroup label="<?php echo get_the_title( $team ); ?>">
								<?php foreach ( $team_players as $player ): ?>
								<option value="<?php echo $player; ?>" <?php selected( $selected, $player, true ) ?>><?php echo get_the_title( $player ); ?></option>
								<?php endforeach; ?>
							</optgroup>
							<?php $j++; endforeach; ?>
						</select>
					</td>
					<td>
						<?php
						$value = sportspress_array_value( $highlight, 'statistic', null );
						$args = array(
							'post_type' => 'sp_statistic',
							'name' => 'sp_highlight[' . $i . '][statistic]',
							'show_option_none' => true,
							'option_none_value' => '',
							'selected' => $value,
							'chosen' => true,
							'class' => 'sp-player-statistic-select',
							'placeholder' => __( '&mdash; Select &mdash;', 'sportspress' ),
						);
						sportspress_dropdown_pages( $args );
						?>
					</td>
					<td class="time-column">
						<?php $value = sportspress_array_value( $highlight, 'time', 0 ); ?>
						<input name="sp_highlight[<?php echo $i; ?>][time]" class="sp-time" size="4" type="text" value="<?php echo $value; ?>" />
						<input class="sp-time-range" type="range" max="90" tabindex="-1" value="<?php echo $value; ?>" />
					</td>
					<td><input type="text"></td>
				</tr>
				<?php $i++; endforeach; endif; ?>
			</tbody>
		</table>
		<div class="tablenav bottom">
			<div class="alignleft actions">
				<a class="button" class=""><?php _e( 'Add Highlight', 'sportspress' ); ?></a>
			</div>
			<br class="clear">
		</div>
	</div>
	<?
}

function sportspress_highlights_admin_enqueue_scripts() {
	wp_enqueue_style( 'sportspress-highlights-admin', SPORTSPRESS_HIGHLIGHTS_PLUGIN_URL . 'assets/css/admin.css', array( 'sportspress-admin' ), time() );
	wp_enqueue_script( 'sportspress-highlights-admin', SPORTSPRESS_HIGHLIGHTS_PLUGIN_URL .'assets/js/admin.js', array( 'jquery', 'sportspress-admin' ), time(), true );
}
add_action( 'admin_enqueue_scripts', 'sportspress_highlights_admin_enqueue_scripts' );

function sportspress_highlights_event_meta_init() {
	add_meta_box( 'sp_highlightssdiv', __( 'Highlights', 'sportspress' ), 'sportspress_event_highlights_meta', 'sp_event', 'normal', 'high' );
}
add_action( 'sportspress_event_meta_init', 'sportspress_highlights_event_meta_init' );
