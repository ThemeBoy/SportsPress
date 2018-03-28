<?php
/**
 * Event Charts
 *
 * @author    Savvas <savvasha>
 * @author    ThemeBoy
 * @category  Admin
 * @package   SportsPress/Admin/Meta_Boxes
 * @version   2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * SP_Meta_Box_Event_Charts
 */
class SP_Meta_Box_Event_Charts {

	/**
	* Output the metabox
	*/
	public static function output( $post ) {
	//Use nonce for verification
	wp_nonce_field( 'action_event_charts', 'metabox_event_charts' );
	// Get all saved player transfers
	$event_scores = get_post_meta($post->ID, 'sp_event_scores', true);
	// Get all the teams that play for this event_score
	$teams = (array) get_post_meta( $post->ID, 'sp_team', false );
	foreach ( $teams as $team ) {
		$teams_en[$team] = get_the_title( $team );
	}
?>
	<script type="text/javascript">
	jQuery(document).ready(function( $ ){
		$( '#add-row' ).on('click', function() {
			var row = $( '.empty-row.screen-reader-text' ).clone(true);
			row.removeClass( 'empty-row screen-reader-text' );
			row.insertBefore( '#repeatable-fieldset-one tbody>tr:last' );
			return false;
		});
	
		$( '.remove-row' ).on('click', function() {
			$(this).parents('tr').remove();
			return false;
		});
	});
	</script>
	<table id="repeatable-fieldset-one" width="100%">
			<thead>
				<tr>
					<th width="10%">Minute</th>
					<th width="10%">Home Team</th>
					<th width="10%">Away Team</th>
				</tr>
			</thead>
			<tbody>
			<?php
			if ( $event_scores ) :
	
			foreach ( $event_scores as $event_score ) {
			?>
				<tr>
					<td>
					<input type="number" name="minute[]" value="<?php if( $event_score['minute'] != '' ) echo esc_attr( $event_score['minute'] ); ?>" />
					</td>
					<td>
					<input type="number" name="homescore[]" value="<?php if( $event_score['homescore'] != '' ) echo esc_attr( $event_score['homescore'] ); ?>" />
					</td>
					<td align="center">
					<input type="number" name="awayscore[]" value="<?php if( $event_score['awayscore'] != '' ) echo esc_attr( $event_score['awayscore'] ); ?>" />
					</td>
					<td><a class="button remove-row" href="#">Remove</a></td>
				</tr>
				<?php
			}
			else :
			?>
				<!-- Show blank row instead -->
				<tr>
					<td align="center">
					<input type="number" name="minute[]"/>
					</td>
					<td align="center">
					<input type="number" name="homescore[]"/>
					</td>
					<td align="center">
					<input type="number" name="awayscore[]"/>
					</td>
					<td><a class="button remove-row" href="#">Remove</a></td>
				</tr>
			<?php endif; ?>
				<!-- empty hidden one for jQuery -->
				<tr class="empty-row screen-reader-text">
					<td align="center">
					<input type="number" name="minute[]"/>
					</td>
					<td align="center">
					<input type="number" name="homescore[]"/>
					</td>
					<td align="center">
					<input type="number" name="awayscore[]"/>
					</td>
					<td><a class="button remove-row" href="#">Remove</a></td>
				</tr>
			</tbody>
		</table>
		<p><a id="add-row" class="button" href="#">Add another</a></p>
	  <?php
  }

	/**
	* Save meta box data
	*/
	public static function save( $post_id, $post ) {
	// verify nonce
	if ( !wp_verify_nonce( $_POST['metabox_event_charts'], 'action_event_charts' ) )
		return;
	
	// verify if this is an auto save routine. 
	// If it is our form has not been submitted, so we dont want to do anything
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
		return;
	
	// verify this came from the our screen and with proper authorization,
	// because save_post can be triggered at other times
	if ( !isset( $_POST['metabox_event_charts'] ) )
		return;
	
	// verify if current user has enough permissions
	if (!current_user_can('edit_post', $post_id))
		return;
	
	// OK, we're authenticated: we need to find and save the data
	
	$old = get_post_meta($post_id, 'sp_event_scores', true);
	$new = array();
	
	$minutes = $_POST['minute'];
	$homescore = (int)$_POST['homescore'];
	$awayscore = (int)$_POST['awayscore'];
	$diff = $homescore - $awayscore;
	
	$count = count( $minutes );
	
	for ( $i = 0; $i < $count; $i++ ) {
		$new[i]['minute'] = $minutes[$i];
		$new[i]['homescore'] = $homescore[$i];
		$new[i]['awayscore'] = $awayscore[$i];
		$new[i]['diff'] = $diff[$i];
	}
	
	// Sort by Date From (PHP5.2 supported)
	function sortByOrder($a, $b) {
		return $a['minute'] - $b['minute'];
	}

	usort($new, 'sortByOrder');

	if ( !empty( $new ) && $new != $old )
		update_post_meta( $post_id, 'sp_event_scores', $new );
	elseif ( empty($new) && $old )
		delete_post_meta( $post_id, 'sp_event_scores', $old );
  }
}