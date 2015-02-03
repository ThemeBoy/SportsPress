<?php
/**
 * Team Details
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Meta_Boxes
 * @version     1.6
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * SP_Meta_Box_Team_Details
 */
class SP_Meta_Box_Team_Details {

	/**
	 * Output the metabox
	 */
	public static function output( $post ) {
		wp_nonce_field( 'sportspress_save_data', 'sportspress_meta_nonce' );
		$leagues = get_the_terms( $post->ID, 'sp_league' );
		$league_ids = array();
		if ( $leagues ):
			foreach ( $leagues as $league ):
				$league_ids[] = $league->term_id;
			endforeach;
		endif;

		$seasons = get_the_terms( $post->ID, 'sp_season' );
		$season_ids = array();
		if ( $seasons ):
			foreach ( $seasons as $season ):
				$season_ids[] = $season->term_id;
			endforeach;
		endif;

		$venues = get_the_terms( $post->ID, 'sp_venue' );
		$venue_ids = array();
		if ( $venues ):
			foreach ( $venues as $venue ):
				$venue_ids[] = $venue->term_id;
			endforeach;
		endif;

		$abbreviation = get_post_meta( $post->ID, 'sp_abbreviation', true );
		$url = get_post_meta( $post->ID, 'sp_url', true );
		?>
		<p><strong><?php _e( 'Competitions', 'sportspress' ); ?></strong></p>
		<p><?php
		$args = array(
			'taxonomy' => 'sp_league',
			'name' => 'tax_input[sp_league][]',
			'selected' => $league_ids,
			'values' => 'term_id',
			'placeholder' => sprintf( __( 'Select %s', 'sportspress' ), __( 'Competitions', 'sportspress' ) ),
			'class' => 'widefat',
			'property' => 'multiple',
			'chosen' => true,
		);
		sp_dropdown_taxonomies( $args );
		?></p>

		<p><strong><?php _e( 'Seasons', 'sportspress' ); ?></strong></p>
		<p><?php
		$args = array(
			'taxonomy' => 'sp_season',
			'name' => 'tax_input[sp_season][]',
			'selected' => $season_ids,
			'values' => 'term_id',
			'placeholder' => sprintf( __( 'Select %s', 'sportspress' ), __( 'Seasons', 'sportspress' ) ),
			'class' => 'widefat',
			'property' => 'multiple',
			'chosen' => true,
		);
		sp_dropdown_taxonomies( $args );
		?></p>

		<p><strong><?php _e( 'Home', 'sportspress' ); ?></strong></p>
		<p><?php
		$args = array(
			'taxonomy' => 'sp_venue',
			'name' => 'tax_input[sp_venue][]',
			'selected' => $venue_ids,
			'values' => 'term_id',
			'placeholder' => sprintf( __( 'Select %s', 'sportspress' ), __( 'Venue', 'sportspress' ) ),
			'class' => 'widefat',
			'property' => 'multiple',
			'chosen' => true,
		);
		sp_dropdown_taxonomies( $args );
		?></p>

		<p><strong><?php _e( 'Site URL', 'sportspress' ); ?></strong></p>
		<p><input type="text" class="widefat" id="sp_url" name="sp_url" value="<?php echo $url; ?>"></p>
		<?php if ( $url ): ?>
			<p><a class="sp-link" title="<?php _e( 'Visit Site', 'sportspress' ); ?>" href="<?php echo $url; ?>" target="_blank"><?php _e( 'Visit Site', 'sportspress' ); ?></a></p>
		<?php endif; ?>

		<p><strong><?php _e( 'Abbreviation', 'sportspress' ); ?></strong></p>
		<p><input type="text" id="sp_abbreviation" name="sp_abbreviation" value="<?php echo $abbreviation; ?>"></p>
		<?php
	}

	/**
	 * Save meta box data
	 */
	public static function save( $post_id, $post ) {
		update_post_meta( $post_id, 'sp_url', sp_array_value( $_POST, 'sp_url', '' ) );
		update_post_meta( $post_id, 'sp_abbreviation', sp_array_value( $_POST, 'sp_abbreviation', '' ) );
	}
}