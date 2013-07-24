<?php
function sp_player_cpt_init() {
	$name = __( 'Players', 'sportspress' );
	$singular_name = __( 'Player', 'sportspress' );
	$labels = sp_get_cpt_labels( $name, $singular_name );
	$args = array(
		'label' => $name,
		'labels' => $labels,
		'public' => true,
		'hierarchical' => false,
		'supports' => array( 'title', 'author', 'thumbnail', 'page-attributes' ),
		'register_meta_box_cb' => 'sp_player_meta_init',
		'rewrite' => array( 'slug' => 'player' )
	);
	register_post_type( 'sp_player', $args );
}
add_action( 'init', 'sp_player_cpt_init' );

function sp_player_meta_init() {
	add_meta_box( 'sp_playerdiv', __( 'Player', 'sportspress' ), 'sp_player_basic_meta', 'sp_player', 'side', 'high' );
	add_meta_box( 'sp_profilediv', __( 'Profile', 'sportspress' ), 'sp_player_profile_meta', 'sp_player', 'normal', 'high' );
}
function sp_player_basic_meta( $post, $metabox ) {
	global $post_id;
	?>
	<ul id="sp_team-tabs" class="wp-tab-bar">
		<li class="tabs wp-tab-active"><?php _e( 'Teams', 'sportspress' ); ?></li>
	</ul>
	<div id="sp_team-all" class="wp-tab-panel">
		<input type="hidden" value="0" name="sportspress[sp_teams]" />
		<ul class="categorychecklist form-no-clear">
			<?php
			$player_teams = sp_get_teams( $post_id );
			$teams = get_pages( array( 'post_type' => 'sp_team') );
			foreach ( $teams as $team ):
				?>
				<li>
					<label class="selectit">
						<input type="checkbox" value="<?php echo $team->ID; ?>" name="sportspress[sp_teams][]"<?php if ( in_array( $team->ID, $player_teams ) ) echo ' checked="checked"'; ?>>
						<?php
						if ( $team->post_parent ):
							$parents = get_post_ancestors( $team );
							echo str_repeat( '— ', sizeof( $parents ) );
						endif;
						echo $team->post_title;
						?>
					</label>
				</li>
				<?php
			endforeach;
			?>
		</ul>
	</div>
	<div id="sp_league-adder" class="wp-hidden-children">
		<h4><?php add_thickbox(); ?>
			<a title="<?php echo sprintf( esc_attr__( 'Add New %s', 'sportspress' ), esc_attr__( 'Team', 'sportspress' ) ); ?>" href="<?php echo admin_url( 'post-new.php?post_type=sp_team' ); ?>" target="_blank">
				+ <?php echo sprintf( __( 'Add New %s', 'sportspress' ), __( 'Team', 'sportspress' ) ); ?>
			</a>
		</h4>
	</div>
	<?php
	wp_reset_postdata();
	echo '<input type="hidden" name="sp_event_team_nonce" id="sp_event_team_nonce" value="' . wp_create_nonce( plugin_basename( __FILE__ ) ) . '" />';
}

function sp_player_profile_meta( $post, $metabox ) {
	wp_editor( $post->post_content, 'content' );
}

function sp_player_edit_columns( $columns ) {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Name', 'sportspress' ),
		'sp_position' => __( 'Positions', 'sportspress' ),
		'sp_team' => __( 'Teams', 'sportspress' ),
		'sp_league' => __( 'Leagues', 'sportspress' ),
		'sp_season' => __( 'Seasons', 'sportspress' ),
		'sp_sponsor' => __( 'Sponsors', 'sportspress' )
	);
	return $columns;
}
add_filter( 'manage_edit-sp_player_columns', 'sp_player_edit_columns' );

function sp_player_custom_columns( $column, $post_id ) {
	global $post, $typenow;
	if ( $typenow == 'sp_player' ):
		switch ($column):
			case 'sp_position':
				if ( get_the_terms ( $post_id, 'sp_position' ) )
					the_terms( $post_id, 'sp_position' );
				else
					echo '—';
				break;
			case 'sp_team':
				$teams = sp_get_teams( $post_id );
				foreach ( $teams as $team ):
					$parents = get_post_ancestors( $team );
					$parents = array_combine( array_keys( $parents ), array_reverse( array_values( $parents ) ) );
					foreach ( $parents as $parent ):
						if ( !in_array( $parent, $teams ) )
							edit_post_link( get_the_title( $parent ), '', ' ', $parent );
						echo '— ';
					endforeach;
					edit_post_link( get_the_title( $team ), '', '<br />', $team );
				endforeach;
				break;
			case 'sp_league':
				if ( get_the_terms ( $post_id, 'sp_league' ) )
					the_terms( $post_id, 'sp_league' );
				else
					echo '—';
				break;
			case 'sp_season':
				if ( get_the_terms ( $post_id, 'sp_season' ) )
					the_terms( $post_id, 'sp_season' );
				else
					echo '—';
				break;
			case 'sp_sponsor':
				if ( get_the_terms ( $post_id, 'sp_sponsor' ) )
					the_terms( $post_id, 'sp_sponsor' );
				else
					echo '—';
				break;
		endswitch;
	endif;
}
add_action( 'manage_posts_custom_column', 'sp_player_custom_columns', 10, 2 );

function sp_player_request_filter_dropdowns() {
	global $typenow, $wp_query;
	if ( $typenow == 'sp_player' ) {

		// Positions
		$selected = isset( $_REQUEST['sp_position'] ) ? $_REQUEST['sp_position'] : null;
		$args = array(
			'show_option_all' =>  sprintf( __( 'All %s', 'sportspress' ), __( 'Positions', 'sportspress' ) ),
			'taxonomy' => 'sp_position',
			'name' => 'sp_position',
			'selected' => $selected
		);
		sp_dropdown_taxonomies( $args );
		echo PHP_EOL;

		// Leagues
		$selected = isset( $_REQUEST['sp_league'] ) ? $_REQUEST['sp_league'] : null;
		$args = array(
			'show_option_all' =>  sprintf( __( 'All %s', 'sportspress' ), __( 'Leagues', 'sportspress' ) ),
			'taxonomy' => 'sp_league',
			'name' => 'sp_league',
			'selected' => $selected
		);
		sp_dropdown_taxonomies( $args );
		echo PHP_EOL;

		// Seasons
		$selected = isset( $_REQUEST['sp_season'] ) ? $_REQUEST['sp_season'] : null;
		$args = array(
			'show_option_all' =>  sprintf( __( 'All %s', 'sportspress' ), __( 'Seasons', 'sportspress' ) ),
			'taxonomy' => 'sp_season',
			'name' => 'sp_season',
			'selected' => $selected
		);
		sp_dropdown_taxonomies( $args );
		echo PHP_EOL;

		// Sponsors
		$selected = isset( $_REQUEST['sp_sponsor'] ) ? $_REQUEST['sp_sponsor'] : null;
		$args = array(
			'show_option_all' =>  sprintf( __( 'All %s', 'sportspress' ), __( 'Sponsors', 'sportspress' ) ),
			'taxonomy' => 'sp_sponsor',
			'name' => 'sp_sponsor',
			'selected' => $selected
		);
		sp_dropdown_taxonomies( $args );
		
	}
}
add_action( 'restrict_manage_posts', 'sp_player_request_filter_dropdowns' );
?>