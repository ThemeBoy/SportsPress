<?php
/**
 * Tournament Bracket Meta Boxes
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress_Tournament_Brackets
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * SP_Tournament_Bracket_Meta_Boxes
 */
class SP_Tournament_Bracket_Meta_Boxes {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ), 30 );
		add_action( 'sportspress_process_sp_bracket_meta', array( $this, 'save' ), 10, 2 );
	}

	/**
	 * Add Meta boxes
	 */
	public function add_meta_boxes() {
		add_meta_box( 'sp_shortcodediv', __( 'Shortcode', 'sportspress' ), array( $this, 'shortcode' ), 'sp_bracket', 'side', 'default' );
		add_meta_box( 'sp_detailsdiv', __( 'Details', 'sportspress' ), array( $this, 'details' ), 'sp_bracket', 'side', 'default' );
		add_meta_box( 'sp_datadiv', __( 'Tournament Bracket', 'sportspress' ), array( $this, 'data' ), 'sp_bracket', 'normal', 'high' );
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
		$rounds = get_post_meta( $post->ID, 'sp_rounds', true );
		if ( $rounds === '' ) $rounds = 3;
		?>
		<div>
			<p><strong><?php _e( 'Rounds', 'sportspress' ); ?></strong></p>
			<p><input name="sp_rounds" type="number" value="<?php echo $rounds; ?>" placeholder="0" class="small-text"></p>
		</div>
		<?php
	}

	/**
	 * Output the data metabox
	 */
	public static function data( $post ) {
		$bracket = new SP_Tournament_Bracket( $post );
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
		update_post_meta( $post_id, 'sp_rounds', sp_array_value( $_POST, 'sp_rounds', 0 ) );

		// Data
		update_post_meta( $post_id, 'sp_events', sp_array_value( $_POST, 'sp_events', array() ) );
	}

	/**
	 * Admin edit table
	 */
	public static function table( $labels, $data = null, $rounds = 3, $rows = 23 ) {
		$events = get_posts( array( 'post_type' => 'sp_event', 'posts_per_page' => -1 ) );
		$counter = array_fill( 0, $rounds, 0 );
		?>
		<table class="widefat sp-tournament-bracket-container">
			<thead>
				<tr>
					<?php for ( $round = 0; $round < $rounds; $round++ ): ?>
						<th>
							<?php printf( __( 'Round %s', 'sportspress' ), $round + 1 ); ?>
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

							// Calculate event index
							$event_index = ( pow( 2, $rounds ) - pow( 2, ( $rounds - $round ) ) + floor( $counter[ $round ] / 3 ) );

							if ( sp_array_value( $cell, 'type', null ) === 'event' ):
								echo '<td rowspan="' . sp_array_value( $cell, 'rows', 1 ) . '" class="sp-event' . ( $round === 0 ? ' sp-first-round' : '' ) . ( $round === $rounds - 1 ? ' sp-last-round' : '' ) . '">';
								echo '<select class="postform sp-event-selector" name="sp_event[]" data-event="' . $event_index . '">';
									foreach ( $events as $event ):
										$teams = get_post_meta( $event->ID, 'sp_team' );
										$home = array_shift( $teams );
										$away = array_shift( $teams );
										echo '<option value="' . $event->ID . '" data-home="' . get_the_title( $home ) . '" data-away="' . get_the_title( $away ) . '">' . $event->post_title . '</option>';
									endforeach;
								echo '</select>';
								echo '</td>';
								$counter[ $round ] ++;
							elseif ( sp_array_value( $cell, 'type', null ) === 'team' ):
								echo '<td rowspan="' . sp_array_value( $cell, 'rows', 1 ) . '" class="sp-team' . ( $round === 0 ? ' sp-first-round' : '' ) . ( $round === $rounds - 1 ? ' sp-last-round' : '' ) . '">
									<input type="text" readonly="readonly" class="code widefat sp-team-display" data-event="' . $event_index . '">
								</td>';
								$counter[ $round ] ++;
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

new SP_Tournament_Bracket_Meta_Boxes();