<?php
/**
 * Player Transfers
 *
 * @author    Savvas <savvasha>
 * @author    ThemeBoy
 * @category  Admin
 * @package   SportsPress/Admin/Meta_Boxes
 * @version   2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * SP_Meta_Box_Player_Transfers
 */
class SP_Meta_Box_Player_Transfers {

  /**
   * Output the metabox
   */
  public static function output( $post ) {
	  //Use nonce for verification
	  wp_nonce_field( 'action_player_transfers', 'metabox_player_transfers' );
	  // Get all saved player transfers
	  $player_transfers = get_post_meta($post->ID, 'sp_transfers', true);
 ?>
	  <script type="text/javascript">
	jQuery(document).ready(function( $ ){
		$( '#add-row' ).on('click', function() {
			var row = $( '.empty-row.screen-reader-text' ).clone(true);
			row.removeClass( 'empty-row screen-reader-text' );
			row.find(".date").addClass("sp-datepicker3");
			row.insertBefore( '#repeatable-fieldset-one tbody>tr:last' );
			$(".sp-datepicker3").datepicker({
				dateFormat : "yy-mm-dd"
			});
			return false;
		});
  	
		$( '.remove-row' ).on('click', function() {
			$(this).parents('tr').remove();
			return false;
		});
		
		$(".sp-datepicker2").datepicker({
			dateFormat : "yy-mm-dd"
		});
	});
	</script>
	  <table id="repeatable-fieldset-one" width="100%">
			<thead>
				<tr>
					<th width="20%">Team</th>
					<th width="10%">Date From:</th>
					<th width="10%">Date To:</th>
					<th width="10%">Loan</th>
				</tr>
			</thead>
			<tbody>
			<?php
			if ( $player_transfers ) :
	
			foreach ( $player_transfers as $player_transfer ) {
			?>
				<tr>
					<td>
					<?php sp_dropdown_pages( array( 'post_type' => 'sp_team', 'name' => 'sp_pt_team[]', 'selected' => $player_transfer['team'], 'values' => 'ID', 'show_option_none' => __( '&mdash; Not set &mdash;', 'sportspress' ),	'class' => 'widefat', 'property' => 'single', 'chosen' => false ) ); ?>
					</td>
					<td>
					<input type="text" class="sp-datepicker2"  name="datefrom[]" value="<?php if( $player_transfer['date_from'] != '' ) echo esc_attr( $player_transfer['date_from'] ); ?>" />
					</td>
					<td>
					<input type="text" class="sp-datepicker2"  name="dateto[]" value="<?php if( isset( $player_transfer['date_to'] ) ) echo esc_attr( $player_transfer['date_to'] ); ?>" />
					</td>
					<td align="center">
					<input id="loan" type="checkbox" name="loan[]" value="true" <?php if( isset( $player_transfer['loan'] ) && $player_transfer['loan'] == 'true' ) echo 'checked'; ?> />
					<input id="loanHidden" type="hidden" name="loan[]" value="false">
					</td>
					<td><a class="button remove-row" href="#">Remove</a></td>
				</tr>
				<?php
			}
			else :
			?>
				<!-- Show blank row instead -->
				<tr>
					<td>
					<?php sp_dropdown_pages( array( 'post_type' => 'sp_team', 'name' => 'sp_pt_team[]', 'selected' => null,	'values' => 'ID', 'show_option_none' => __( '&mdash; Not set &mdash;', 'sportspress' ),	'class' => 'widefat', 'property' => 'single', 'chosen' => false ) ); ?>
					</td>
					<td>
					<input type="text" class="sp-datepicker2"  name="datefrom[]"/>
					</td>
					<td>
					<input type="text" class="sp-datepicker2"  name="dateto[]"/>
					</td>
					<td align="center">
					<input id="loan" type="checkbox" name="loan[]" value="true"/>
					<input id="loanHidden" type="hidden" name="loan[]" value="false">
					</td>
					<td><a class="button remove-row" href="#">Remove</a></td>
				</tr>
			<?php endif; ?>
				<!-- empty hidden one for jQuery -->
				<tr class="empty-row screen-reader-text">
					<td>
					<?php sp_dropdown_pages( array( 'post_type' => 'sp_team', 'name' => 'sp_pt_team[]', 'selected' => null,	'values' => 'ID', 'show_option_none' => __( '&mdash; Not set &mdash;', 'sportspress' ),	'class' => 'widefat', 'property' => 'single', 'chosen' => false ) ); ?>
					</td>
					<td>
					<input type="text" class="date"  name="datefrom[]"/>
					</td>
					<td>
					<input type="text" class="date"  name="dateto[]"/>
					</td>
					<td align="center">
					<input id="loan" type="checkbox" name="loan[]" value="true"/>
					<input id="loanHidden" type="hidden" name="loan[]" value="false">
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
	if ( !wp_verify_nonce( $_POST['metabox_player_transfers'], 'action_player_transfers' ) )
		return;
	
	// verify if this is an auto save routine. 
	// If it is our form has not been submitted, so we dont want to do anything
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
		return;
	
	// verify this came from the our screen and with proper authorization,
	// because save_post can be triggered at other times
	if ( !isset( $_POST['metabox_player_transfers'] ) )
		return;
	
	// verify if current user has enough permissions
	if (!current_user_can('edit_post', $post_id))
		return;
	
	// OK, we're authenticated: we need to find and save the data
	
	$old = get_post_meta($post_id, 'sp_transfers', true);
	$new = array();
	
	$teams = $_POST['sp_pt_team'];
	$dates_from = $_POST['datefrom'];
	$dates_to = $_POST['dateto'];
	$loans = $_POST['loan'];
	
	$count = count( $teams );
	
	for ( $i = 0; $i < $count; $i++ ) {
		if ( $teams[$i] != '' && $teams[$i] != '-1' ) {
			$new[$i]['team'] = stripslashes( strip_tags( $teams[$i] ) );
			$new[$i]['loan'] = 'false';
		}
			
		if ( $dates_from[$i] != '' ) {
			$new[$i]['date_from'] = stripslashes( strip_tags( $dates_from[$i] ) );
			$new[$i]['date_from_unix'] = strtotime( $dates_from[$i] );
		}
			
		if ( $dates_to[$i] != '' ) {
			$new[$i]['date_to'] = stripslashes( strip_tags( $dates_to[$i] ) );
		}
		
		if ( $loans[$i] == 'true' ) {
			$new[$i]['loan'] = 'true';
		}
	}
	
	// Sort by Date From (PHP5.2 supported)
	function sortByOrder($a, $b) {
		return $a['date_from_unix'] - $b['date_from_unix'];
	}

	usort($new, 'sortByOrder');

	if ( !empty( $new ) && $new != $old )
		update_post_meta( $post_id, 'sp_transfers', $new );
	elseif ( empty($new) && $old )
		delete_post_meta( $post_id, 'sp_transfers', $old );
  }
}
