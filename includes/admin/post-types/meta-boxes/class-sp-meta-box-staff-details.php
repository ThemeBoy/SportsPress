<?php
/**
 * Staff Details
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Meta Boxes
 * @version     0.8
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * SP_Meta_Box_Staff_Details
 */
class SP_Meta_Box_Staff_Details {

	/**
	 * Output the metabox
	 */
	public static function output( $post ) {
		wp_nonce_field( 'sportspress_save_data', 'sportspress_meta_nonce' );
		$continents = SP()->countries->continents;

		$role = get_post_meta( $post->ID, 'sp_role', true );
		$nationality = get_post_meta( $post->ID, 'sp_nationality', true );

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
		
		$teams = get_posts( array( 'post_type' => 'sp_team', 'posts_per_page' => -1 ) );
		$past_teams = array_filter( get_post_meta( $post->ID, 'sp_past_team', false ) );
		$current_team = get_post_meta( $post->ID, 'sp_current_team', true );
		?>
		<p><strong><?php _e( 'Role', 'sportspress' ); ?></strong></p>
		<p><input type="text" id="sp_role" name="sp_role" value="<?php echo $role; ?>"></p>

		<p><strong><?php _e( 'Nationality', 'sportspress' ); ?></strong></p>
		<p><select id="sp_nationality" name="sp_nationality" data-placeholder="<?php printf( __( 'Select %s', 'sportspress' ), __( 'Nationality', 'sportspress' ) ); ?>" class="widefat chosen-select<?php if ( is_rtl() ): ?> chosen-rtl<?php endif; ?>">
			<option value=""></option>
			<?php foreach ( $continents as $continent => $countries ): ?>
				<optgroup label="<?php echo $continent; ?>">
					<?php foreach ( $countries as $code => $country ): ?>
						<option value="<?php echo $code; ?>" <?php selected ( $nationality, $code ); ?>><?php echo $country; ?></option>
					<?php endforeach; ?>
				</optgroup>
			<?php endforeach; ?>
		</select></p>

		<p><strong><?php _e( 'Current Team', 'sportspress' ); ?></strong></p>
		<p><?php
		$args = array(
			'post_type' => 'sp_team',
			'name' => 'sp_current_team',
			'show_option_blank' => true,
			'selected' => $current_team,
			'values' => 'ID',
			'placeholder' => sprintf( __( 'Select %s', 'sportspress' ), __( 'Team', 'sportspress' ) ),
			'class' => 'sp-current-team widefat',
			'chosen' => true,
		);
		sp_dropdown_pages( $args );
		?></p>

		<p><strong><?php _e( 'Past Teams', 'sportspress' ); ?></strong></p>
		<p><?php
		$args = array(
			'post_type' => 'sp_team',
			'name' => 'sp_past_team[]',
			'selected' => $past_teams,
			'values' => 'ID',
			'placeholder' => sprintf( __( 'Select %s', 'sportspress' ), __( 'Teams', 'sportspress' ) ),
			'class' => 'sp-past-teams widefat',
			'property' => 'multiple',
			'chosen' => true,
		);
		sp_dropdown_pages( $args );
		?></p>

		<p><strong><?php _e( 'Leagues', 'sportspress' ); ?></strong></p>
		<p><?php
		$args = array(
			'taxonomy' => 'sp_league',
			'name' => 'tax_input[sp_league][]',
			'selected' => $league_ids,
			'values' => 'term_id',
			'placeholder' => sprintf( __( 'Select %s', 'sportspress' ), __( 'Leagues', 'sportspress' ) ),
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
		<?php
	}

	/**
	 * Save meta box data
	 */
	public static function save( $post_id, $post ) {
		update_post_meta( $post_id, 'sp_role', sp_array_value( $_POST, 'sp_role', '' ) );
		update_post_meta( $post_id, 'sp_nationality', sp_array_value( $_POST, 'sp_nationality', '' ) );
		update_post_meta( $post_id, 'sp_current_team', sp_array_value( $_POST, 'sp_current_team', null ) );
		sp_update_post_meta_recursive( $post_id, 'sp_past_team', sp_array_value( $_POST, 'sp_past_team', array() ) );
		sp_update_post_meta_recursive( $post_id, 'sp_team', array_merge( array( sp_array_value( $_POST, 'sp_current_team', null ) ), sp_array_value( $_POST, 'sp_past_team', array() ) ) );
	}
}