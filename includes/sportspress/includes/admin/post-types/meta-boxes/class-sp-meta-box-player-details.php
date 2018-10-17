<?php
/**
 * Player Details
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Meta_Boxes
 * @version		2.6.9
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * SP_Meta_Box_Player_Details
 */
class SP_Meta_Box_Player_Details {

	/**
	 * Output the metabox
	 */
	public static function output( $post ) {
		wp_nonce_field( 'sportspress_save_data', 'sportspress_meta_nonce' );
		$continents = SP()->countries->continents;

		$number = get_post_meta( $post->ID, 'sp_number', true );
		$nationalities = get_post_meta( $post->ID, 'sp_nationality', false );
		$default_nationality = get_option( 'sportspress_default_nationality' , false );
		
		if ( empty( $nationalities ) && $default_nationality ) { 
			if ( $default_nationality != '' ) {
				$nationalities[] = $default_nationality;
			}
		}

		foreach ( $nationalities as $index => $nationality ):
			if ( 2 == strlen( $nationality ) ):
				$legacy = SP()->countries->legacy;
				$nationality = strtolower( $nationality );
				$nationality = sp_array_value( $legacy, $nationality, null );
				$nationalities[ $index ] = $nationality;
			endif;
		endforeach;

		if ( taxonomy_exists( 'sp_league' ) ):
			$leagues = get_the_terms( $post->ID, 'sp_league' );
			$league_ids = array();
			if ( $leagues ):
				foreach ( $leagues as $league ):
					$league_ids[] = $league->term_id;
				endforeach;
			endif;
		endif;

		if ( taxonomy_exists( 'sp_season' ) ):
			$seasons = get_the_terms( $post->ID, 'sp_season' );
			$season_ids = array();
			if ( $seasons ):
				foreach ( $seasons as $season ):
					$season_ids[] = $season->term_id;
				endforeach;
			endif;
		endif;

		if ( taxonomy_exists( 'sp_position' ) ):
			$positions = get_the_terms( $post->ID, 'sp_position' );
			$position_ids = array();
			if ( $positions ):
				foreach ( $positions as $position ):
					$position_ids[] = $position->term_id;
				endforeach;
			endif;
		endif;
		
		$teams = get_posts( array( 'post_type' => 'sp_team', 'posts_per_page' => -1 ) );
		$past_teams = array_filter( get_post_meta( $post->ID, 'sp_past_team', false ) );
		$current_teams = array_filter( get_post_meta( $post->ID, 'sp_current_team', false ) );
		?>

		<p><strong><?php _e( 'Squad Number', 'sportspress' ); ?></strong></p>
		<p><input type="text" size="4" id="sp_number" name="sp_number" value="<?php echo $number; ?>"></p>

		<p><strong><?php _e( 'Nationality', 'sportspress' ); ?></strong></p>
		<p><select id="sp_nationality" name="sp_nationality[]" data-placeholder="<?php printf( __( 'Select %s', 'sportspress' ), __( 'Nationality', 'sportspress' ) ); ?>" class="widefat chosen-select<?php if ( is_rtl() ): ?> chosen-rtl<?php endif; ?>" multiple="multiple">
			<option value=""></option>
			<?php foreach ( $continents as $continent => $countries ): ?>
				<optgroup label="<?php echo $continent; ?>">
					<?php foreach ( $countries as $code => $country ): ?>
						<option value="<?php echo $code; ?>" <?php selected ( in_array( $code, $nationalities ) ); ?>><?php echo $country; ?></option>
					<?php endforeach; ?>
				</optgroup>
			<?php endforeach; ?>
		</select></p>

		<?php if ( taxonomy_exists( 'sp_position' ) ) { ?>
			<p><strong><?php _e( 'Positions', 'sportspress' ); ?></strong></p>
			<p><?php
			$args = array(
				'taxonomy' => 'sp_position',
				'name' => 'tax_input[sp_position][]',
				'selected' => $position_ids,
				'values' => 'term_id',
				'placeholder' => sprintf( __( 'Select %s', 'sportspress' ), __( 'Positions', 'sportspress' ) ),
				'class' => 'widefat',
				'property' => 'multiple',
				'chosen' => true,
			);
			sp_dropdown_taxonomies( $args );
			?></p>
		<?php } ?>

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

		<?php if ( taxonomy_exists( 'sp_league' ) ) { ?>
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
		<?php } ?>

		<?php if ( taxonomy_exists( 'sp_season' ) ) { ?>
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
		<?php } ?>
		<?php
	}

	/**
	 * Save meta box data
	 */
	public static function save( $post_id, $post ) {
		update_post_meta( $post_id, 'sp_number', esc_attr( sp_array_value( $_POST, 'sp_number', '' ) ) );
		sp_update_post_meta_recursive( $post_id, 'sp_nationality', sp_array_value( $_POST, 'sp_nationality', array() ) );
		sp_update_post_meta_recursive( $post_id, 'sp_current_team', sp_array_value( $_POST, 'sp_current_team', array() ) );
		sp_update_post_meta_recursive( $post_id, 'sp_past_team', sp_array_value( $_POST, 'sp_past_team', array() ) );
		sp_update_post_meta_recursive( $post_id, 'sp_team', array_merge( array( sp_array_value( $_POST, 'sp_current_team', array() ) ), sp_array_value( $_POST, 'sp_past_team', array() ) ) );
	}
}
