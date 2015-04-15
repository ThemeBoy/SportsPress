<?php
/**
 * Staff Details
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Meta_Boxes
 * @version     1.8
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

		$nationality = get_post_meta( $post->ID, 'sp_nationality', true );
		if ( 2 == strlen( $nationality ) ):
			$legacy = SP()->countries->legacy;
			$nationality = strtolower( $nationality );
			$nationality = sp_array_value( $legacy, $nationality, null );
		endif;

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

		$roles = get_the_terms( $post->ID, 'sp_role' );
		if ( $roles ):
			$term = array_shift( $roles );
			$role = $term->term_id;
		else:
			$role = null;
		endif;
		
		$teams = get_posts( array( 'post_type' => 'sp_team', 'posts_per_page' => -1 ) );
		$past_teams = array_filter( get_post_meta( $post->ID, 'sp_past_team', false ) );
		$current_teams = array_filter( get_post_meta( $post->ID, 'sp_current_team', false ) );
		?>
		<p><strong><?php _e( 'Job', 'sportspress' ); ?></strong></p>
		<p><?php
		$args = array(
			'taxonomy' => 'sp_role',
			'name' => 'sp_role',
			'selected' => $role,
			'values' => 'term_id',
			'show_option_blank' => true,
			'placeholder' => sprintf( __( 'Select %s', 'sportspress' ), __( 'Job', 'sportspress' ) ),
			'class' => 'widefat',
			'chosen' => true,
		);
		if ( ! sp_dropdown_taxonomies( $args ) ):
			sp_taxonomy_adder( 'sp_role', 'sp_player', __( 'Add New', 'sportspress' )  );
		endif;
		?></p>

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

		<?php if ( apply_filters( 'sportspress_staff_teams', true ) ) { ?>
		<p><strong><?php _e( 'Current Teams', 'sportspress' ); ?></strong></p>
		<p><?php
		$args = array(
			'post_type' => 'sp_team',
			'name' => 'sp_current_team[]',
			'selected' => $current_teams,
			'values' => 'ID',
			'placeholder' => sprintf( __( 'Select %s', 'sportspress' ), __( 'Teams', 'sportspress' ) ),
			'class' => 'sp-current-teams widefat',
			'property' => 'multiple',
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
		<?php } ?>

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
		<?php
	}

	/**
	 * Save meta box data
	 */
	public static function save( $post_id, $post ) {
		wp_set_post_terms( $post_id, sp_array_value( $_POST, 'sp_role', null ), 'sp_role', false );
		update_post_meta( $post_id, 'sp_nationality', sp_array_value( $_POST, 'sp_nationality', '' ) );
		
		sp_update_post_meta_recursive( $post_id, 'sp_current_team', sp_array_value( $_POST, 'sp_current_team', array() ) );
		sp_update_post_meta_recursive( $post_id, 'sp_past_team', sp_array_value( $_POST, 'sp_past_team', array() ) );
		sp_update_post_meta_recursive( $post_id, 'sp_team', array_merge( array( sp_array_value( $_POST, 'sp_current_team', array() ) ), sp_array_value( $_POST, 'sp_past_team', array() ) ) );
	}
}