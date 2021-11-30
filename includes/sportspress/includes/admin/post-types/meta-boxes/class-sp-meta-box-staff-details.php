<?php
/**
 * Staff Details
 *
 * @author      ThemeBoy
 * @category    Admin
 * @package     SportsPress/Admin/Meta_Boxes
 * @version     2.7.9
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

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

		$nationalities = get_post_meta( $post->ID, 'sp_nationality', false );
		foreach ( $nationalities as $index => $nationality ) :
			if ( 2 == strlen( $nationality ) ) :
				$legacy                  = SP()->countries->legacy;
				$nationality             = strtolower( $nationality );
				$nationality             = sp_array_value( $legacy, $nationality, null );
				$nationalities[ $index ] = $nationality;
			endif;
		endforeach;

		$leagues    = get_the_terms( $post->ID, 'sp_league' );
		$league_ids = array();
		if ( $leagues ) :
			foreach ( $leagues as $league ) :
				$league_ids[] = $league->term_id;
			endforeach;
		endif;

		$seasons    = get_the_terms( $post->ID, 'sp_season' );
		$season_ids = array();
		if ( $seasons ) :
			foreach ( $seasons as $season ) :
				$season_ids[] = $season->term_id;
			endforeach;
		endif;

		$roles    = get_the_terms( $post->ID, 'sp_role' );
		$role_ids = is_array( $roles ) ? wp_list_pluck( $roles, 'term_id' ) : array();

		$teams         = get_posts(
			array(
				'post_type'      => 'sp_team',
				'posts_per_page' => -1,
			)
		);
		$past_teams    = array_filter( get_post_meta( $post->ID, 'sp_past_team', false ) );
		$current_teams = array_filter( get_post_meta( $post->ID, 'sp_current_team', false ) );
		?>
		<p><strong><?php esc_attr_e( 'Jobs', 'sportspress' ); ?></strong></p>
		<p>
		<?php
		$args = array(
			'taxonomy'    => 'sp_role',
			'name'        => 'tax_input[sp_role][]',
			'selected'    => $role_ids,
			'values'      => 'term_id',
			'placeholder' => sprintf( esc_attr__( 'Select %s', 'sportspress' ), esc_attr__( 'Jobs', 'sportspress' ) ),
			'class'       => 'widefat',
			'property'    => 'multiple',
			'chosen'      => true,
		);
		if ( ! sp_dropdown_taxonomies( $args ) ) :
			sp_taxonomy_adder( 'sp_role', 'sp_staff', esc_attr__( 'Add New', 'sportspress' ) );
		endif;
		?>
		</p>

		<p><strong><?php esc_attr_e( 'Nationality', 'sportspress' ); ?></strong></p>
		<p><select id="sp_nationality" name="sp_nationality[]" data-placeholder="<?php printf( esc_attr__( 'Select %s', 'sportspress' ), esc_attr__( 'Nationality', 'sportspress' ) ); ?>" class="widefat chosen-select
																							   <?php
																								if ( is_rtl() ) :
																									?>
			 chosen-rtl<?php endif; ?>" multiple="multiple">
			<option value=""></option>
			<?php foreach ( $continents as $continent => $countries ) : ?>
				<optgroup label="<?php echo esc_attr( $continent ); ?>">
					<?php foreach ( $countries as $code => $country ) : ?>
						<option value="<?php echo esc_attr( $code ); ?>" <?php selected( in_array( $code, $nationalities ) ); ?>><?php echo esc_html( $country ); ?></option>
					<?php endforeach; ?>
				</optgroup>
			<?php endforeach; ?>
		</select></p>

		<p><strong><?php esc_attr_e( 'Current Teams', 'sportspress' ); ?></strong></p>
		<p>
		<?php
		$args = array(
			'post_type'   => 'sp_team',
			'name'        => 'sp_current_team[]',
			'selected'    => $current_teams,
			'values'      => 'ID',
			'placeholder' => sprintf( esc_attr__( 'Select %s', 'sportspress' ), esc_attr__( 'Teams', 'sportspress' ) ),
			'class'       => 'sp-current-teams widefat',
			'property'    => 'multiple',
			'chosen'      => true,
		);
		sp_dropdown_pages( $args );
		?>
		</p>

		<p><strong><?php esc_attr_e( 'Past Teams', 'sportspress' ); ?></strong></p>
		<p>
		<?php
		$args = array(
			'post_type'   => 'sp_team',
			'name'        => 'sp_past_team[]',
			'selected'    => $past_teams,
			'values'      => 'ID',
			'placeholder' => sprintf( esc_attr__( 'Select %s', 'sportspress' ), esc_attr__( 'Teams', 'sportspress' ) ),
			'class'       => 'sp-past-teams widefat',
			'property'    => 'multiple',
			'chosen'      => true,
		);
		sp_dropdown_pages( $args );
		?>
		</p>

		<p><strong><?php esc_attr_e( 'Leagues', 'sportspress' ); ?></strong></p>
		<p>
		<?php
		$args = array(
			'taxonomy'    => 'sp_league',
			'name'        => 'tax_input[sp_league][]',
			'selected'    => $league_ids,
			'values'      => 'term_id',
			'placeholder' => sprintf( esc_attr__( 'Select %s', 'sportspress' ), esc_attr__( 'Leagues', 'sportspress' ) ),
			'class'       => 'widefat',
			'property'    => 'multiple',
			'chosen'      => true,
		);
		sp_dropdown_taxonomies( $args );
		?>
		</p>

		<p><strong><?php esc_attr_e( 'Seasons', 'sportspress' ); ?></strong></p>
		<p>
		<?php
		$args = array(
			'taxonomy'    => 'sp_season',
			'name'        => 'tax_input[sp_season][]',
			'selected'    => $season_ids,
			'values'      => 'term_id',
			'placeholder' => sprintf( esc_attr__( 'Select %s', 'sportspress' ), esc_attr__( 'Seasons', 'sportspress' ) ),
			'class'       => 'widefat',
			'property'    => 'multiple',
			'chosen'      => true,
		);
		sp_dropdown_taxonomies( $args );
		?>
		</p>
		<?php
	}

	/**
	 * Save meta box data
	 */
	public static function save( $post_id, $post ) {
		sp_update_post_meta_recursive( $post_id, 'sp_nationality', sp_array_value( $_POST, 'sp_nationality', array(), 'key' ) );
		sp_update_post_meta_recursive( $post_id, 'sp_current_team', sp_array_value( $_POST, 'sp_current_team', array(), 'id' ) );
		sp_update_post_meta_recursive( $post_id, 'sp_past_team', sp_array_value( $_POST, 'sp_past_team', array(), 'id' ) );
		sp_update_post_meta_recursive( $post_id, 'sp_team', array_merge( array( sp_array_value( $_POST, 'sp_current_team', array(), 'id' ) ), sp_array_value( $_POST, 'sp_past_team', array(), 'id' ) ) );
	}
}
