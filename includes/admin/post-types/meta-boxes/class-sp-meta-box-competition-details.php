<?php
/**
 * Competition Details
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Meta_Boxes
 * @version     2.5.3
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * SP_Meta_Box_Competition_Details
 */
class SP_Meta_Box_Competition_Details {

	/**
	 * Output the metabox
	 */
	public static function output( $post ) {
		wp_nonce_field( 'sportspress_save_data', 'sportspress_meta_nonce' );
		//$taxonomies = get_object_taxonomies( 'sp_competition' );
		if ( taxonomy_exists( 'sp_league' ) ):
			$leagues = get_the_terms( $post->ID, 'sp_league' );
			if ( $leagues ):
				$league = reset( $leagues );
				$league_id = $league->term_id;
			else:
				$league_id = null;
			endif;
		endif;
		if ( taxonomy_exists( 'sp_season' ) ):
			$seasons = get_the_terms( $post->ID, 'sp_season' );
			if ( $seasons ):
				$season = reset( $seasons );
				$season_id = $season->term_id;
			else:
				$season_id = null;
			endif;
		endif;
		$caption = get_post_meta( $post->ID, 'sp_caption', true );
		?>
		<div>
			<p><strong><?php _e( 'Heading', 'sportspress' ); ?></strong></p>
			<p><input type="text" id="sp_caption" name="sp_caption" value="<?php echo esc_attr( $caption ); ?>" placeholder="<?php echo esc_attr( get_the_title() ); ?>"></p>
		<?php if ( taxonomy_exists( 'sp_league' ) ) { ?>
		<p><strong><?php _e( 'League', 'sportspress' ); ?></strong></p>
		<p><?php
		$args = array(
			'taxonomy' => 'sp_league',
			'name' => 'tax_input[sp_league][]',
			'selected' => $league_id,
			'values' => 'term_id',
			'placeholder' => sprintf( __( 'Select %s', 'sportspress' ), __( 'League', 'sportspress' ) ),
			'class' => 'widefat',
			//'property' => 'multiple',
			'chosen' => true,
		);
		if ( ! sp_dropdown_taxonomies( $args ) ):
			echo '<p>' . __( 'None', 'sportspress' ) . '</p>';
			sp_taxonomy_adder( 'sp_league', 'sp_competition', __( 'Add New', 'sportspress' ) );
		endif;
		?></p>
		<?php } ?>

		<?php if ( taxonomy_exists( 'sp_season' ) ) { ?>
		<p><strong><?php _e( 'Season', 'sportspress' ); ?></strong></p>
		<p><?php
		$args = array(
			'taxonomy' => 'sp_season',
			'name' => 'tax_input[sp_season][]',
			'selected' => $season_id,
			'values' => 'term_id',
			'placeholder' => sprintf( __( 'Select %s', 'sportspress' ), __( 'Season', 'sportspress' ) ),
			'class' => 'widefat',
			//'property' => 'multiple',
			'chosen' => true,
		);
		if ( ! sp_dropdown_taxonomies( $args ) ):
			echo '<p>' . __( 'None', 'sportspress' ) . '</p>';
			sp_taxonomy_adder( 'sp_season', 'sp_competition', __( 'Add New', 'sportspress' ) );
		endif;
		?></p>
		<?php } ?>
		<?php do_action( 'sportspress_meta_box_competition_details', $post->ID ); ?>
		</div>
		<?php
	}

	/**
	 * Save meta box data
	 */
	public static function save( $post_id, $post ) {
		update_post_meta( $post_id, 'sp_caption', esc_attr( sp_array_value( $_POST, 'sp_caption', 0 ) ) );
	}
}