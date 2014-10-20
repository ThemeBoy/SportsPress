<?php
/**
 * Tournament Meta Boxes
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress_Tournaments
 * @version     1.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * SP_Tournament_Meta_Boxes
 */
class SP_Tournament_Meta_Boxes {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ), 30 );
		add_action( 'sportspress_process_sp_tournament_meta', array( $this, 'save' ), 10, 2 );
	}

	/**
	 * Add Meta boxes
	 */
	public function add_meta_boxes() {
		add_meta_box( 'sp_shortcodediv', __( 'Shortcode', 'sportspress' ), array( $this, 'shortcode' ), 'sp_tournament', 'side', 'default' );
		add_meta_box( 'sp_detailsdiv', __( 'Details', 'sportspress' ), array( $this, 'details' ), 'sp_tournament', 'side', 'default' );
		add_meta_box( 'sp_datadiv', __( 'Tournament', 'sportspress' ), array( $this, 'data' ), 'sp_tournament', 'normal', 'high' );
	}

	/**
	 * Output the shortcode metabox
	 */
	public static function shortcode( $post ) {
		?>
		<p class="howto">
			<?php _e( 'Copy this code and paste it into your post, page or text widget content.', 'sportspress' ); ?>
		</p>
		<p><input type="text" value="[tournament_bracket <?php echo $post->ID; ?>]" readonly="readonly" class="code widefat"></p>
		<?php
	}

	/**
	 * Output the details metabox
	 */
	public static function details( $post ) {
		wp_nonce_field( 'sportspress_save_data', 'sportspress_meta_nonce' );
		$rounds = get_post_meta( $post->ID, 'sp_rounds', true );
		if ( $rounds === '' ) $rounds = 3;
		?>
		<div>
			<p><strong><?php _e( 'Rounds', 'sportspress' ); ?></strong></p>
			<p><input name="sp_rounds" type="number" min="1" max="6" value="<?php echo $rounds; ?>" placeholder="0" class="small-text sp-autosave"></p>
		</div>
		<?php
	}

	/**
	 * Output the data metabox
	 */
	public static function data( $post ) {
		$bracket = new SP_Tournament( $post );
		list( $labels, $data, $rounds, $rows ) = $bracket->data( true );
		self::table( $labels, $data, $rounds, $rows );
	}

	/**
	 * Output the editor metabox
	 */
	public static function editor( $post ) {
		wp_editor( $post->post_content, 'content' );
	}

	/**
	 * Save meta boxes data
	 */
	public static function save( $post_id, $post ) {
		// Details
		$rounds = sp_array_value( $_POST, 'sp_rounds', 1 );
		if ( $rounds < 1 ) $rounds = 1;
		elseif ( $rounds > 6 ) $rounds = 6;
		update_post_meta( $post_id, 'sp_rounds', $rounds );

		// Data
		update_post_meta( $post_id, 'sp_labels', sp_array_value( $_POST, 'sp_labels', array() ) );
		$events = sp_array_value( $_POST, 'sp_event', array() );
		ksort( $events );
		update_post_meta( $post_id, 'sp_events', $events );
		sp_update_post_meta_recursive( $post_id, 'sp_event', $events );
	}

	/**
	 * Admin edit table
	 */
	public static function table( $labels = array(), $data = null, $rounds = 3, $rows = 23 ) {
		$posts = get_posts(
			array(
				'post_type' => 'sp_event',
				'posts_per_page' => -1,
				'post_status' => 'any',
				'meta_key' => 'sp_format',
				'meta_value' => 'tournament',
			)
		);
		?>
		<table class="widefat sp-tournament-container">
			<thead>
				<tr>
					<?php for ( $round = 0; $round < $rounds; $round++ ): ?>
						<th>
							<input type="text" class="widefat" name="sp_labels[]" value="<?php echo sp_array_value( $labels, $round, '' ); ?>" placeholder="<?php printf( __( 'Round %s', 'sportspress' ), $round + 1 ); ?>">
						</th>
					<?php endfor; ?>
				</tr>
			</thead>
			<tbody>
				<?php for ( $row = 0; $row < $rows; $row++ ): ?>
					<tr>
						<?php
						for ( $round = 0; $round < $rounds; $round++ ):
							$cell = sp_array_value( sp_array_value( $data, $row, array() ), $round, null );
							if ( $cell === null ) continue;

							$index = sp_array_value( $cell, 'index' );
							$event = sp_array_value( $cell, 'event', 0 );

							if ( sp_array_value( $cell, 'type', null ) === 'event' ):
								echo '<td rowspan="' . sp_array_value( $cell, 'rows', 1 ) . '" class="sp-event' . ( $round === 0 ? ' sp-first-round' : '' ) . ( $round === $rounds - 1 ? ' sp-last-round' : '' ) . '">';
								echo '<select class="postform sp-event-selector" name="sp_event[' . $index . ']" data-event="' . $index . '">';
									echo '<option value="0" data-home="" data-away="">' . sprintf( __( 'Select %s', 'sportspress' ), __( 'Event', 'sportspress' ) ) . '</option>';
									foreach ( $posts as $post ):
										$teams = get_post_meta( $post->ID, 'sp_team' );
										$home = array_shift( $teams );
										$away = array_shift( $teams );
										echo '<option value="' . $post->ID . '" data-home="' . get_the_title( $home ) . '" data-away="' . get_the_title( $away ) . '"' . selected( $event, $post->ID ) . '>' . $post->post_date . ' &mdash; ' . $post->post_title . '</option>';
									endforeach;
								echo '</select>';
								echo '</td>';
							elseif ( sp_array_value( $cell, 'type', null ) === 'team' ):
								echo '<td rowspan="' . sp_array_value( $cell, 'rows', 1 ) . '" class="sp-team' . ( $round === 0 ? ' sp-first-round' : '' ) . ( $round === $rounds - 1 ? ' sp-last-round' : '' ) . '">
									<input type="text" readonly="readonly" class="widefat sp-team-display" data-event="' . $index . '">
								</td>';
							else:
								echo '<td rowspan="' . sp_array_value( $cell, 'rows', 1 ) . '">&nbsp;</td>';
							endif;

						endfor;
						?>
					</tr>
				<?php endfor;?>
			</tbody>
		</table>
		<?php
	}
}

new SP_Tournament_Meta_Boxes();