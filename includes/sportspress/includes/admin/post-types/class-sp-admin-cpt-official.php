<?php
/**
 * Admin functions for the officials post type
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Post_Types
 * @version   2.5
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'SP_Admin_CPT' ) )
	include( 'class-sp-admin-cpt.php' );

if ( ! class_exists( 'SP_Admin_CPT_Official' ) ) :

/**
 * SP_Admin_CPT_Official Class
 */
class SP_Admin_CPT_Official extends SP_Admin_CPT {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->type = 'sp_official';

		// Post title fields
		add_filter( 'enter_title_here', array( $this, 'enter_title_here' ), 1, 2 );

		// Admin columns
		add_filter( 'manage_edit-sp_official_columns', array( $this, 'edit_columns' ) );
		add_action( 'manage_sp_official_posts_custom_column', array( $this, 'custom_columns' ), 2, 2 );

		// Filtering
		add_action( 'restrict_manage_posts', array( $this, 'filters' ) );
		add_filter( 'parse_query', array( $this, 'filters_query' ) );

		// Quick edit
		add_action( 'quick_edit_custom_box', array( $this, 'quick_edit_teams' ), 10, 2 );
		add_action( 'save_post', array( $this, 'quick_save' ) );
		
		// Bulk edit
		add_action( 'bulk_edit_custom_box', array( $this, 'bulk_edit_teams' ), 10, 2 );
		add_action( 'wp_ajax_save_bulk_edit_sp_official', array( $this, 'bulk_save' ) );
		
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
		if ( $post->post_type == 'sp_official' )
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
			'title' => null,
			'sp_duty' => __( 'Duties', 'sportspress' ),
			'sp_team' => __( 'Teams', 'sportspress' ),
		), $existing_columns, array(
			'title' => __( 'Name', 'sportspress' )
		) );
		return apply_filters( 'sportspress_official_admin_columns', $columns );
	}

	/**
	 * Define our custom columns shown in admin.
	 * @param  string $column
	 */
	public function custom_columns( $column, $post_id ) {
		switch ( $column ):
			case 'sp_duty':
				echo get_the_terms( $post_id, 'sp_duty' ) ? the_terms( $post_id, 'sp_duty' ) : '&mdash;';
				break;
			case 'sp_team':
				$current_teams = get_post_meta( $post_id, 'sp_current_team', false );
				$past_teams = get_post_meta( $post_id, 'sp_past_team', false );
				$current_teams = array_filter( $current_teams );
				$past_teams = array_filter( $past_teams );
				echo '<span class="hidden sp-official-teams" data-current-teams="' . implode( ',', $current_teams ) . '" data-past-teams="' . implode( ',', $past_teams ) . '"></span>';
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
		endswitch;
	}

	/**
	 * Show a category filter box
	 */
	public function filters() {
		global $typenow, $wp_query;

	    if ( $typenow != 'sp_official' )
	    	return;

	    if ( taxonomy_exists( 'sp_duty' ) ):
			$selected = isset( $_REQUEST['sp_duty'] ) ? $_REQUEST['sp_duty'] : null;
			$args = array(
				'show_option_all' =>  __( 'Show all duties', 'sportspress' ),
				'taxonomy' => 'sp_duty',
				'name' => 'sp_duty',
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
	}

	/**
	 * Filter in admin based on options
	 *
	 * @param mixed $query
	 */
	public function filters_query( $query ) {
		global $typenow, $wp_query;

	    if ( $typenow == 'sp_official' ) {

	    	if ( ! empty( $_GET['team'] ) ) {
		    	$query->query_vars['meta_value'] 	= $_GET['team'];
		        $query->query_vars['meta_key'] 		= 'sp_team';
		    }
		}
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
			wp_nonce_field( plugin_basename( __FILE__ ), 'sp_official_edit_nonce' );
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

return new SP_Admin_CPT_Official();
