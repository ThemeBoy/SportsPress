<?php
/**
 * Admin functions for the players post type
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Post_Types
 * @version		2.6
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'SP_Admin_CPT' ) )
	include( 'class-sp-admin-cpt.php' );

if ( ! class_exists( 'SP_Admin_CPT_Player' ) ) :

/**
 * SP_Admin_CPT_Player Class
 */
class SP_Admin_CPT_Player extends SP_Admin_CPT {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->type = 'sp_player';

		// Post title fields
		add_filter( 'enter_title_here', array( $this, 'enter_title_here' ), 1, 2 );

		// Admin columns
		add_filter( 'manage_edit-sp_player_columns', array( $this, 'edit_columns' ) );
		add_action( 'manage_sp_player_posts_custom_column', array( $this, 'custom_columns' ), 2, 2 );

		// Filtering
		add_action( 'restrict_manage_posts', array( $this, 'filters' ) );
		add_filter( 'parse_query', array( $this, 'filters_query' ) );

		// Quick edit
		add_action( 'quick_edit_custom_box', array( $this, 'quick_edit_number' ), 10, 2 );
		add_action( 'quick_edit_custom_box', array( $this, 'quick_edit_teams' ), 10, 2 );
		add_action( 'save_post', array( $this, 'quick_save' ) );
		
		// Bulk edit
		add_action( 'bulk_edit_custom_box', array( $this, 'bulk_edit_teams' ), 10, 2 );
		add_action( 'wp_ajax_save_bulk_edit_sp_player', array( $this, 'bulk_save' ) );
		
		// Call SP_Admin_CPT constructor
		parent::__construct();
	}

	/**
	 * Change title boxes in admin.
	 * @param  string $text
	 * @param  object $post
	 * @return string
	 */
	public function enter_title_here( $text, $post ) {
		if ( $post->post_type == 'sp_player' )
			return __( 'Name', 'sportspress' );

		return $text;
	}

	/**
	 * Change the columns shown in admin.
	 */
	public function edit_columns( $existing_columns ) {
		unset( $existing_columns['author'], $existing_columns['date'] );
		$columns = array_merge( array(
			'cb' => '<input type="checkbox" />',
			'sp_number' => '<span class="dashicons sp-icon-tshirt sp-tip" title="' . __( 'Squad Number', 'sportspress' ) . '"></span>',
			'title' => null,
			'sp_position' => __( 'Positions', 'sportspress' ),
			'sp_team' => __( 'Teams', 'sportspress' ),
			'sp_league' => __( 'Leagues', 'sportspress' ),
			'sp_season' => __( 'Seasons', 'sportspress' ),
		), $existing_columns, array(
			'title' => __( 'Name', 'sportspress' )
		) );
		return apply_filters( 'sportspress_player_admin_columns', $columns );
	}

	/**
	 * Define our custom columns shown in admin.
	 * @param  string $column
	 */
	public function custom_columns( $column, $post_id ) {
		switch ( $column ):
			case 'sp_number':
				echo get_post_meta ( $post_id, 'sp_number', true );
				break;
			case 'sp_position':
				echo get_the_terms( $post_id, 'sp_position' ) ? the_terms( $post_id, 'sp_position' ) : '&mdash;';
				break;
			case 'sp_team':
				$current_teams = get_post_meta( $post_id, 'sp_current_team', false );
				$past_teams = get_post_meta( $post_id, 'sp_past_team', false );
				$current_teams = array_filter( $current_teams );
				$past_teams = array_filter( $past_teams );
				echo '<span class="hidden sp-player-teams" data-current-teams="' . implode( ',', $current_teams ) . '" data-past-teams="' . implode( ',', $past_teams ) . '"></span>';
				$teams = (array)get_post_meta( $post_id, 'sp_team', false );
				$teams = array_filter( $teams );
				$teams = array_unique( $teams );
				if ( empty( $teams ) ):
					echo '&mdash;';
				else:
					foreach( $teams as $team_id ):
						if ( ! $team_id ) continue;
						$team = get_post( $team_id );
						if ( $team ):
							echo $team->post_title;
							if ( in_array( $team_id, $current_teams ) ):
								echo '<span class="dashicons dashicons-yes" title="' . __( 'Current Team', 'sportspress' ) . '"></span>';
							endif;
							echo '<br>';
						endif;
					endforeach;
				endif;
				break;
			case 'sp_league':
				echo get_the_terms ( $post_id, 'sp_league' ) ? the_terms( $post_id, 'sp_league' ) : '&mdash;';
				break;
			case 'sp_season':
				echo get_the_terms ( $post_id, 'sp_season' ) ? the_terms( $post_id, 'sp_season' ) : '&mdash;';
				break;
			case 'sp_venue':
				echo get_the_terms ( $post_id, 'sp_venue' ) ? the_terms( $post_id, 'sp_venue' ) : '&mdash;';
				break;
		endswitch;
	}

	/**
	 * Show a category filter box
	 */
	public function filters() {
		global $typenow, $wp_query;

	    if ( $typenow != 'sp_player' )
	    	return;

	    if ( taxonomy_exists( 'sp_position' ) ):
			$selected = isset( $_REQUEST['sp_position'] ) ? $_REQUEST['sp_position'] : null;
			$args = array(
				'show_option_all' =>  __( 'Show all positions', 'sportspress' ),
				'taxonomy' => 'sp_position',
				'name' => 'sp_position',
				'selected' => $selected
			);
			sp_dropdown_taxonomies( $args );
		endif;

		$selected = isset( $_REQUEST['team'] ) ? $_REQUEST['team'] : null;
		$args = array(
			'post_type' => 'sp_team',
			'name' => 'team',
			'show_option_none' => __( 'Show all teams', 'sportspress' ),
			'selected' => $selected,
			'values' => 'ID',
		);
		wp_dropdown_pages( $args );

	    if ( taxonomy_exists( 'sp_league' ) ):
			$selected = isset( $_REQUEST['sp_league'] ) ? $_REQUEST['sp_league'] : null;
			$args = array(
				'show_option_all' =>  __( 'Show all leagues', 'sportspress' ),
				'taxonomy' => 'sp_league',
				'name' => 'sp_league',
				'selected' => $selected
			);
			sp_dropdown_taxonomies( $args );
		endif;

	    if ( taxonomy_exists( 'sp_season' ) ):
			$selected = isset( $_REQUEST['sp_season'] ) ? $_REQUEST['sp_season'] : null;
			$args = array(
				'show_option_all' =>  __( 'Show all seasons', 'sportspress' ),
				'taxonomy' => 'sp_season',
				'name' => 'sp_season',
				'selected' => $selected
			);
			sp_dropdown_taxonomies( $args );
		endif;
	}

	/**
	 * Filter in admin based on options
	 *
	 * @param mixed $query
	 */
	public function filters_query( $query ) {

		if ( empty ( $query->query_vars['post_type'] ) || $query->query_vars['post_type'] !== 'sp_player' ) return $query;

		global $typenow, $wp_query;

		if ( $typenow == 'sp_player' ) {

			if ( ! empty( $_GET['team'] ) ) {
				$query->query_vars['meta_value'] 	= $_GET['team'];
				$query->query_vars['meta_key'] 		= 'sp_team';
			}
		}

		return $query;
	}

	/**
	 * Quick edit squad number
	 *
	 * @param string $column_name
	 * @param string $post_type
	 */
	public function quick_edit_number( $column_name, $post_type ) {
		if ( $this->type !== $post_type ) return;
		if ( 'sp_number' !== $column_name ) return;

		static $print_nonce = true;
		if ( $print_nonce ) {
			$print_nonce = false;
			wp_nonce_field( plugin_basename( __FILE__ ), 'sp_player_edit_nonce' );
		}
		?>
		<fieldset class="inline-edit-col-right">
			<div class="inline-edit-col">
				<label>
					<span class="title"><?php _e( 'Squad Number', 'sportspress' ); ?></span>
					<span class="input-text-wrap"><input type="text" name="sp_number" class="inline-edit-menu-order-input"></span>
				</label>
			</div>
		</fieldset>
		<?php
	}

	/**
	 * Quick edit teams
	 *
	 * @param string $column_name
	 * @param string $post_type
	 */
	public function quick_edit_teams( $column_name, $post_type ) {
		if ( $this->type !== $post_type ) return;
		if ( 'sp_team' !== $column_name ) return;

		$teams = get_posts( array(
			'post_type' => 'sp_team',
			'numberposts' => -1,
			'post_status' => 'publish',
		) );
		
		if ( ! $teams ) return;
		?>
		<fieldset class="inline-edit-col-right">
			<div class="inline-edit-col">
				<span class="title inline-edit-categories-label"><?php _e( 'Current Teams', 'sportspress' ); ?></span>
				<input type="hidden" name="sp_current_team[]" value="0">
				<ul class="cat-checklist">
					<?php foreach ( $teams as $team ) { ?>
					<li><label class="selectit"><input value="<?php echo $team->ID; ?>" type="checkbox" name="sp_current_team[]"> <?php echo $team->post_title; ?></label></li>
					<?php } ?>
				</ul>
				<span class="title inline-edit-categories-label"><?php _e( 'Past Teams', 'sportspress' ); ?></span>
				<input type="hidden" name="sp_past_team[]" value="0">
				<ul class="cat-checklist">
					<?php foreach ( $teams as $team ) { ?>
					<li><label class="selectit"><input value="<?php echo $team->ID; ?>" type="checkbox" name="sp_past_team[]"> <?php echo $team->post_title; ?></label></li>
					<?php } ?>
				</ul>
			</div>
		</fieldset>
		<?php
	}

	/**
	 * Save quick edit boxes
	 *
	 * @param int $post_id
	 */
	public function quick_save( $post_id ) {
		if ( empty( $_POST ) ) return $post_id;
		if ( ! current_user_can( 'edit_post', $post_id ) )  return $post_id;;

		$_POST += array( "{$this->type}_edit_nonce" => '' );
		if ( ! wp_verify_nonce( $_POST["{$this->type}_edit_nonce"], plugin_basename( __FILE__ ) ) )  return $post_id;;

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return $post_id;
		if ( isset( $post->post_type ) && $post->post_type == 'revision' ) return $post_id;

		if ( isset( $_POST[ 'sp_number' ] ) ) {
			update_post_meta( $post_id, 'sp_number', $_POST[ 'sp_number' ] );
		}

		sp_update_post_meta_recursive( $post_id, 'sp_current_team', sp_array_value( $_POST, 'sp_current_team', array() ) );
		sp_update_post_meta_recursive( $post_id, 'sp_past_team', sp_array_value( $_POST, 'sp_past_team', array() ) );
		sp_update_post_meta_recursive( $post_id, 'sp_team', array_merge( array( sp_array_value( $_POST, 'sp_current_team', array() ) ), sp_array_value( $_POST, 'sp_past_team', array() ) ) );
	}

	/**
	 * Bulk edit teams
	 *
	 * @param string $column_name
	 * @param string $post_type
	 */
	public function bulk_edit_teams( $column_name, $post_type ) {
		if ( $this->type !== $post_type ) return;
		if ( 'sp_team' !== $column_name ) return;

		static $print_nonce = true;
		if ( $print_nonce ) {
			$print_nonce = false;
			wp_nonce_field( plugin_basename( __FILE__ ), 'sp_player_edit_nonce' );
		}

		$teams = get_posts( array(
			'post_type' => 'sp_team',
			'numberposts' => -1,
			'post_status' => 'publish',
		) );
		
		if ( ! $teams ) return;
		?>
		<fieldset class="inline-edit-col-right">
			<div class="inline-edit-col">
				<span class="title inline-edit-categories-label"><?php _e( 'Current Teams', 'sportspress' ); ?></span>
				<input type="hidden" name="sp_current_team[]" value="0">
				<ul class="cat-checklist">
					<?php foreach ( $teams as $team ) { ?>
					<li><label class="selectit"><input value="<?php echo $team->ID; ?>" type="checkbox" name="sp_current_team[]"> <?php echo $team->post_title; ?></label></li>
					<?php } ?>
				</ul>
				<span class="title inline-edit-categories-label"><?php _e( 'Past Teams', 'sportspress' ); ?></span>
				<input type="hidden" name="sp_past_team[]" value="0">
				<ul class="cat-checklist">
					<?php foreach ( $teams as $team ) { ?>
					<li><label class="selectit"><input value="<?php echo $team->ID; ?>" type="checkbox" name="sp_past_team[]"> <?php echo $team->post_title; ?></label></li>
					<?php } ?>
				</ul>
			</div>
		</fieldset>
		<?php
	}

	/**
	 * Save bulk edit boxes
	 */
	public function bulk_save() {
		$_POST += array( "nonce" => '' );
		if ( ! wp_verify_nonce( $_POST["nonce"], plugin_basename( __FILE__ ) ) ) return;

		$post_ids = ( ! empty( $_POST[ 'post_ids' ] ) ) ? $_POST[ 'post_ids' ] : array();

		$current_teams = sp_array_value( $_POST, 'current_teams', array() );
		$past_teams = sp_array_value( $_POST, 'past_teams', array() );
		$teams = array_merge( $current_teams, $past_teams );

		if ( ! empty( $post_ids ) && is_array( $post_ids ) ) {
			foreach ( $post_ids as $post_id ) {
				if ( ! current_user_can( 'edit_post', $post_id ) ) continue;

				sp_add_post_meta_recursive( $post_id, 'sp_current_team', $current_teams );
				sp_add_post_meta_recursive( $post_id, 'sp_past_team', $past_teams );
				sp_add_post_meta_recursive( $post_id, 'sp_team', $teams );
			}
		}

		die();
	}
}

endif;

return new SP_Admin_CPT_Player();
