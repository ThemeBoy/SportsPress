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
	  $player = new SP_Player( $post );
	  $leagues = get_the_terms( $post->ID, 'sp_league' );
	  $seasons = get_the_terms( $post->ID, 'sp_season' );
	  $teams = array_merge( $player->current_teams(), $player->past_teams() );
	  $leagues_args = array(
				'taxonomy' => 'sp_league',
				'name' => 'tax_input[sp_league][]',
				'selected' => null,
				'values' => 'term_id',
				'placeholder' => sprintf( __( 'Select %s', 'sportspress' ), __( 'League', 'sportspress' ) ),
				'class' => 'widefat',
				'property' => 'single',
				'chosen' => true,
			);
	  $seasons_args = array(
				'taxonomy' => 'sp_season',
				'name' => 'tax_input[sp_season][]',
				'selected' => null,
				'values' => 'term_id',
				'placeholder' => sprintf( __( 'Select %s', 'sportspress' ), __( 'Season', 'sportspress' ) ),
				'class' => 'widefat',
				'property' => 'single',
				'chosen' => true,
			);
	  $teams_args = array(
			'post_type' => 'sp_team',
			'name' => 'sp_current_team[]',
			'selected' => null,
			'values' => 'ID',
			'placeholder' => sprintf( __( 'Select %s', 'sportspress' ), __( 'Team', 'sportspress' ) ),
			'class' => 'widefat',
			'property' => 'single',
			'chosen' => true,
		);
	  //var_dump($player);
	  //var_dump($leagues);
	  //var_dump($teams); ?>
	  <table>
			<thead>
				<tr>
					<th>League</th><th>Season</th><th>Team</th><th>Date From:</th><th>Date To:</th><th>MidSeason</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
					<?php sp_dropdown_taxonomies( $leagues_args ); ?>
					</td>
					<td>
					<?php sp_dropdown_taxonomies( $seasons_args ); ?>
					</td>
					<td>
					<?php sp_dropdown_pages( $teams_args ); ?>
					</td>
					<td>
					<input type="date" name="datefrom"/>
					</td>
					<td>
					<input type="date" name="dateto"/>
					</td>
					<td>
					<input type="checkbox" name="midseason" value="true">
					</td>
				</tr>
			</tbody>
		</table>
	  <?php
  }

  /**
   * Save meta box data
   */
  public static function save( $post_id, $post ) {
    update_post_meta( $post_id, 'sp_transfers', sp_array_value( $_POST, 'sp_transfers', array() ) );
  }
}
