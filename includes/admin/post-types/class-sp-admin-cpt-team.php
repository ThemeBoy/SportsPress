<?php
/**
 * Admin functions for the teams post type
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Post_Types
 * @version		2.5
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'SP_Admin_CPT' ) )
	include( 'class-sp-admin-cpt.php' );

if ( ! class_exists( 'SP_Admin_CPT_Team' ) ) :

/**
 * SP_Admin_CPT_Team Class
 */
class SP_Admin_CPT_Team extends SP_Admin_CPT {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->type = 'sp_team';

		// Post title fields
		add_filter( 'enter_title_here', array( $this, 'enter_title_here' ), 1, 2 );

		// Admin Columns
		add_filter( 'manage_edit-sp_team_columns', array( $this, 'edit_columns' ) );
		add_action( 'manage_sp_team_posts_custom_column', array( $this, 'custom_columns' ), 2, 2 );

		// Filtering
		add_action( 'restrict_manage_posts', array( $this, 'filters' ) );
		add_filter( 'parse_query', array( $this, 'filters_query' ) );
		
		// Quick edit
		add_action( 'quick_edit_custom_box', array( $this, 'quick_edit_competitions' ), 10, 2 );
		add_action( 'save_post', array( $this, 'quick_save' ) );
		
		// Bulk edit
		add_action( 'bulk_edit_custom_box', array( $this, 'bulk_edit_competitions' ), 10, 2 );
		add_action( 'wp_ajax_save_bulk_edit_sp_team', array( $this, 'bulk_save' ) );
		
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
		if ( $post->post_type == 'sp_team' )
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
			'sp_icon' => '<span class="dashicons sp-icon-shield sp-tip" title="' . __( 'Logo', 'sportspress' ) . '"></span>',
			'title' => null,
			'sp_url' => __( 'URL', 'sportspress' ),
			'sp_abbreviation' => __( 'Abbreviation', 'sportspress' ),
			'sp_competition' => __( 'Competitions', 'sportspress' ),
			'sp_league' => __( 'Leagues', 'sportspress' ),
			'sp_season' => __( 'Seasons', 'sportspress' ),
		), $existing_columns, array(
			'title' => __( 'Team', 'sportspress' ),
		) );
		return apply_filters( 'sportspress_team_admin_columns', $columns );
	}

	/**
	 * Define our custom columns shown in admin.
	 * @param  string $column
	 */
	public function custom_columns( $column, $post_id ) {
		switch ( $column ):
			case 'sp_competition':
				$competitions = get_post_meta( $post_id, 'sp_competition', false );
				$competitions = array_filter( $competitions );
				echo '<span class="hidden sp-team-competitions" data-competitions="' . implode( ',', $competitions ) . '"></span>';
				if ( empty( $competitions ) ):
					echo '&mdash;';
				else:
					foreach( $competitions as $competition_id ):
						echo get_the_title($competition_id);
						echo '<br/>';
					endforeach;
				endif;
				break;
			case 'sp_icon':
				echo has_post_thumbnail( $post_id ) ? edit_post_link( get_the_post_thumbnail( $post_id, 'sportspress-fit-mini' ), '', '', $post_id ) : '';
				break;
			case 'sp_url':
	        	echo strip_tags( sp_get_url( $post_id ), '<a>' );
				break;
			case 'sp_abbreviation':
				$abbreviation = get_post_meta ( $post_id, 'sp_abbreviation', true );
				echo $abbreviation ? esc_html( $abbreviation ) : '&mdash;';
				break;
			case 'sp_league':
				echo get_the_terms ( $post_id, 'sp_league' ) ? the_terms( $post_id, 'sp_league' ) : '&mdash;';
				break;
			case 'sp_season':
				echo get_the_terms ( $post_id, 'sp_season' ) ? the_terms( $post_id, 'sp_season' ) : '&mdash;';
				break;
		endswitch;
	}

	/**
	 * Show a category filter box
	 */
	public function filters() {
		global $typenow, $wp_query;

	    if ( $typenow != 'sp_team' )
	    	return;

		$selected = isset( $_REQUEST['competition'] ) ? $_REQUEST['competition'] : null;
		$args = array(
			'post_type' => 'sp_competition',
			'name' => 'competition',
			'show_option_none' => __( 'Show all Competitions', 'sportspress' ),
			'selected' => $selected,
			'values' => 'ID',
		);
		wp_dropdown_pages( $args );
		
		$selected = isset( $_REQUEST['sp_league'] ) ? $_REQUEST['sp_league'] : null;
		$args = array(
			'show_option_all' =>  __( 'Show all leagues', 'sportspress' ),
			'taxonomy' => 'sp_league',
			'name' => 'sp_league',
			'selected' => $selected
		);
		sp_dropdown_taxonomies( $args );

		$selected = isset( $_REQUEST['sp_season'] ) ? $_REQUEST['sp_season'] : null;
		$args = array(
			'show_option_all' =>  __( 'Show all seasons', 'sportspress' ),
			'taxonomy' => 'sp_season',
			'name' => 'sp_season',
			'selected' => $selected
		);
		sp_dropdown_taxonomies( $args );
	}
	
	/**
	 * Filter in admin based on options
	 *
	 * @param mixed $query
	 */
	public function filters_query( $query ) {
		global $typenow, $wp_query;

	    if ( $typenow == 'sp_team' ) {
			
			if ( ! empty( $_GET['competition'] ) ) {
		    	$query->query_vars['meta_value'] 	= $_GET['competition'];
		        $query->query_vars['meta_key'] 		= 'sp_competition';
		    }
		}
	}
	
	/**
	 * Quick edit competitions
	 *
	 * @param string $column_name
	 * @param string $post_type
	 */
	public function quick_edit_competitions( $column_name, $post_type ) {
		if ( $this->type !== $post_type ) return;
		if ( 'sp_competition' !== $column_name ) return;

		$competitions = get_posts( array(
			'post_type' => 'sp_competition',
			'numberposts' => -1,
			'post_status' => 'publish',
		) );
		
		if ( ! $competitions ) return;
		?>
		<fieldset class="inline-edit-col-right">
			<div class="inline-edit-col">
				<span class="title inline-edit-categories-label"><?php _e( 'Competitions', 'sportspress' ); ?></span>
				<!--<input type="hidden" name="sp_competition[]" value="0">-->
				<ul class="cat-checklist">
					<?php foreach ( $competitions as $competition ) { ?>
					<li><label class="selectit"><input value="<?php echo $competition->ID; ?>" type="checkbox" name="sp_competition[]"> <?php echo $competition->post_title; ?></label></li>
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

		// verify quick edit nonce
		if ( isset( $_POST[ '_inline_edit' ] ) && ! wp_verify_nonce( $_POST[ '_inline_edit' ], 'inlineeditnonce' ) ) return $post_id;

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return $post_id;
		if ( isset( $post->post_type ) && $post->post_type == 'revision' ) return $post_id;
		
		sp_update_post_meta_recursive( $post_id, 'sp_competition', sp_array_value( $_POST, 'sp_competition', array() ) );
	}
	
	/**
	 * Bulk edit competitions
	 *
	 * @param string $column_name
	 * @param string $post_type
	 */
	public function bulk_edit_competitions( $column_name, $post_type ) {
		if ( $this->type !== $post_type ) return;
		if ( 'sp_competition' !== $column_name ) return;

		static $print_nonce = true;
		if ( $print_nonce ) {
			$print_nonce = false;
			wp_nonce_field( plugin_basename( __FILE__ ), "{$this->type}_edit_nonce" );
		}

		$competitions = get_posts( array(
			'post_type' => 'sp_competition',
			'numberposts' => -1,
			'post_status' => 'publish',
		) );
		
		if ( ! $competitions ) return;
		?>
		<fieldset class="inline-edit-col-right">
			<div class="inline-edit-col">
				<span class="title inline-edit-categories-label"><?php _e( 'Competitions', 'sportspress' ); ?></span>
				<input type="hidden" name="sp_competition[]" value="0">
				<ul class="cat-checklist">
					<?php foreach ( $competitions as $competition ) { ?>
					<li><label class="selectit"><input value="<?php echo $competition->ID; ?>" type="checkbox" name="sp_competition[]"> <?php echo $competition->post_title; ?></label></li>
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

		$competitions_new = sp_array_value( $_POST, 'competitions', array() );

		if ( ! empty( $post_ids ) && is_array( $post_ids ) ) {
			foreach ( $post_ids as $post_id ) {
				if ( ! current_user_can( 'edit_post', $post_id ) ) continue;
				
				$competitions_current = get_post_meta( $post_id, 'sp_competition', false );
				$competitions = array_merge( $competitions_current, $competitions_new );
				$competitions = array_unique( array_filter( $competitions ) );
				
				sp_update_post_meta_recursive( $post_id, 'sp_competition', $competitions );
			}
		}

		die();
	}
}

endif;

return new SP_Admin_CPT_Team();
