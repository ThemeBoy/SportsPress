<?php
function sp_event_cpt_init() {
	$name = __( 'Events', 'sportspress' );
	$singular_name = __( 'Event', 'sportspress' );
	$labels = sp_get_cpt_labels( $name, $singular_name );
	$args = array(
		'label' => $name,
		'labels' => $labels,
		'public' => true,
		'hierarchical' => false,
		'supports' => array( 'title', 'author', 'comments', 'page-attributes' ),
		'register_meta_box_cb' => 'sp_event_meta_init',
		'rewrite' => array( 'slug' => 'event' ),
	);
	register_post_type( 'sp_event', $args );
}
add_action( 'init', 'sp_event_cpt_init' );

function sp_event_display_scheduled( $posts ) {
	global $wp_query, $wpdb;
	if ( is_single() && $wp_query->post_count == 0 && isset( $wp_query->query_vars['sp_event'] )) {
		$posts = $wpdb->get_results( $wp_query->request );
	}
	return $posts;
}
add_filter( 'the_posts', 'sp_event_display_scheduled' );

function sp_event_text_replace( $input, $text, $domain ) {
	global $post;
	if ( is_admin() && get_post_type( $post ) == 'sp_event' )
		switch ( $text ):
			case 'Scheduled for: <b>%1$s</b>':
				return __( 'Kick-off: <b>%1$s</b>', 'sportspress' );
	    		break;
			case 'Published on: <b>%1$s</b>':
				return __( 'Kick-off: <b>%1$s</b>', 'sportspress' );
	        	break;
			case 'Publish <b>immediately</b>':
				return __( 'Kick-off: <b>%1$s</b>', 'sportspress' );
	        	break;
			default:
				return $input;
		endswitch;
	return $input;
}
add_filter( 'gettext', 'sp_event_text_replace', 20, 3 );

function sp_event_meta_init() {
	add_meta_box(
		'sp_teamdiv',
		__( 'Teams', 'sportspress' ),
		'sp_event_team_meta',
		'sp_event',
		'normal',
		'high'
	);
	add_meta_box(
		'sp_articlediv',
		__( 'Article', 'sportspress' ),
		'sp_event_article_meta',
		'sp_event',
		'normal',
		'high'
	);
}

function sp_event_team_meta( $post, $metabox ) {
	global $post_id;
	$limit = get_option( 'sp_event_team_count' );
	for ( $i = 1; $i <= $limit; $i++ ):
		$args = array(
			'post_type' => 'sp_team',
			'name' => 'sportspress[sp_team_' . $i . ']',
			'selected' => get_post_meta( $post_id, 'sp_team_' . $i, true ),
		);
		echo '<label for="sp_team_' . $i . '">' . __( 'Team', 'sportspress' ) . ' ' . $i . ':</label>' . PHP_EOL;
		wp_dropdown_pages( $args );
		/*
		$players = unserialize( get_post_meta( $post_id, 'sp_players', true ) );
		?>
		<div class="categorydiv" id="sp_team_<?php echo $i; ?>">
			<ul class="tb_stats-tabs category-tabs">
				<li class="tabs"><a href="#tb_home_lineup" tabindex="3"><?php _e( 'Players', 'sportspress' ); ?></a></li>
				<li class="hide-if-no-js"><a href="#tb_home_subs" tabindex="3"><?php _e( 'Staff', 'sportspress' ); ?></a></li>
			</ul>
			<div id="tb_home_lineup" class="tabs-panel">
				<?php tb_match_player_stats_table( $players, $home_club, 'home', 'lineup' ); ?>
			</div>
			<div id="tb_home_subs" class="tabs-panel" style="display: none;">
				<?php tb_match_player_stats_table( $players, $home_club, 'home', 'subs' ); ?>
			</div>
		</div>
		<div class="categorydiv" id="tb_away_players">
			<h4><?php _ex( 'Away', 'team', 'sportspress' ); ?></h4>
			<ul class="tb_stats-tabs category-tabs">
				<li class="tabs"><a href="#tb_away_lineup" tabindex="3"><?php _e( 'Players', 'sportspress' ); ?></a></li>
				<li class="hide-if-no-js"><a href="#tb_away_subs" tabindex="3"><?php _e( 'Staff', 'sportspress' ); ?></a></li>
			</ul>
			<div id="tb_away_lineup" class="tabs-panel">
				<?php tb_match_player_stats_table( $players, $away_club, 'away', 'lineup' ); ?>
			</div>
			<div id="tb_away_subs" class="tabs-panel" style="display: none;">
				<?php tb_match_player_stats_table( $players, $away_club, 'away', 'subs' ); ?>
			</div>
		</div>
		<div class="clear"></div>
		<script type="text/javascript">
			(function($) {
				// swap teams
				$('#tb_match-fixture-meta .tb-swap-teams-button').click(function() {
					// swap club buttons
					var home_button = $('#tb_home_club_button');
					var away_button = $('#tb_away_club_button');
					var temp = $(home_button).html();
					$(home_button).html($(away_button).html());
					$(away_button).html(temp);
					// swap club inputs
					var home_input = $('#tb_home_club');
					var away_input = $('#tb_away_club');
					var temp = $(home_input).val();
					$(home_input).val($(away_input).val());
					$(away_input).val(temp);
				});
				// stats tabs
				$('.tb_stats-tabs a').click(function(){
					var t = $(this).attr('href');
					$(this).parent().addClass('tabs').siblings('li').removeClass('tabs');
					$(this).parent().parent().parent().find('.tabs-panel').hide();
					$(t).show();
					return false;
				});
				$('#tb_match-players-meta table input[type="checkbox"]').live('change', function() {
					player_id = $(this).attr('data-player');
					$(this).closest('tr').find('input[type="number"]').prop('readonly', !$(this).prop('checked'));
					$(this).closest('tr').find('select').prop('disabled', !$(this).prop('checked'));
				});
				// update auto goals
				tb_update_auto_goals = function() {
					home_goals = 0;
					away_goals = 0;
					$('#tb_match-players-meta #tb_home_players table .goals input:not([readonly])').each(function() {
						home_goals += parseInt($(this).val());
					});
					$('#tb_match-players-meta #tb_away_players table .goals input:not([readonly])').each(function() {
						away_goals += parseInt($(this).val());
					});
					manual_home_goals = $('#tb_match-details-meta #results-table input#tb_goals_manual_home').val();
					manual_away_goals = $('#tb_match-details-meta #results-table input#tb_goals_manual_away').val();
					$('#tb_match-details-meta #results-table input#tb_goals_auto_home').val(home_goals);
					$('#tb_match-details-meta #results-table input#tb_goals_auto_away').val(away_goals);
					$('#tb_match-details-meta #results-table input#tb_goals_total_home').val(parseInt(home_goals) + parseInt(manual_home_goals));
					$('#tb_match-details-meta #results-table input#tb_goals_total_away').val(parseInt(away_goals) + parseInt(manual_away_goals));
				}
				$('#tb_match-players-meta table input[type="checkbox"]').live('click', function() {
					tb_update_auto_goals();
				});
				$('#tb_match-details-meta #results-table input, #tb_match-players-meta table .goals input, #tb_match-players-meta table input[type="checkbox"]').live('change', function() {
					tb_update_auto_goals();
				});
				// refresh players list
				tb_refresh_players_lists = function(side) {
					tb_refresh_players_list(side, 'lineup');
					tb_refresh_players_list(side, 'subs');				
				}
				tb_refresh_players_list = function(side, type) {
					nonce = '<?php echo wp_create_nonce('tb_players_nonce'); ?>';
					club = $('#tb_' + side + '_club').val();
					$.ajax({
						type : 'post',
						dataType : 'html',
						url : ajaxurl,
						data : {
							action: 'tb_players_table',
							nonce: nonce,
							club: club,
							side: side,
							type: type
						},
						success: function(response) {
							$('#tb_match-players-meta #tb_' + side + '_' + type).html(response);
						}
					});
				}
				$('#tb_home_club').live('change', function() {
					tb_refresh_players_lists('home');
				})
				$('#tb_away_club').live('change', function() {
					tb_refresh_players_lists('away');
				})
			})(jQuery);
		</script>
		<?php
		*/
	endfor;
	echo '<input type="hidden" name="sp_event_team_nonce" id="sp_event_team_nonce" value="' . wp_create_nonce( plugin_basename( __FILE__ ) ) . '" />';
}

function sp_event_article_meta( $post, $metabox ) {
	wp_editor( $post->post_content, 'content' );
}

function sp_event_save() {
	global $post, $post_id, $typenow;
	if ( $typenow  == 'sp_event' && isset( $_POST ) ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return $post_id;
		if ( ! isset( $_POST['sp_event_team_nonce'] ) || ! wp_verify_nonce( $_POST['sp_event_team_nonce'], plugin_basename( __FILE__ ) ) ) return $post_id;
		$sportspress = (array)$_POST['sportspress'];
		foreach ( $sportspress as $key => $value ):
			update_post_meta( $post_id, $key, $value );
		endforeach;
	}
}
add_action( 'save_post', 'sp_event_save' );

function sp_event_edit_columns( $columns ) {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Event', 'sportspress' ),
		'sp_team' => __( 'Teams', 'sportspress' ),
		'sp_league' => __( 'League', 'sportspress' ),
		'sp_season' => __( 'Season', 'sportspress' ),
		'sp_sponsor' => __( 'Sponsor', 'sportspress' ),
		'sp_kickoff' => __( 'Kick-off', 'sportspress' ),
		'date' => __( 'Date' ),
	);
	return $columns;
}
add_filter( 'manage_edit-sp_event_columns', 'sp_event_edit_columns' );

function sp_event_custom_columns( $column, $post_id ) {
	global $typenow;
	if ( $typenow == 'sp_event' ):
		switch ( $column ):
			case 'sp_team':
				$limit = get_option( 'sp_event_team_count' );
				for ( $i = 1; $i <= $limit; $i++ ):
					$team = get_post_meta( $post_id, 'sp_team_' . $i, true );
					edit_post_link( get_the_title( $team ), '', '<br />', $team );
				endfor;
				break;
			case 'sp_league':
				the_terms( $post_id, 'sp_league' );
				break;
			case 'sp_season':
				the_terms( $post_id, 'sp_season' );
				break;
			case 'sp_sponsor':
				the_terms( $post_id, 'sp_sponsor' );
				break;
			case 'sp_kickoff':
				echo get_the_time ( get_option ( 'time_format' ) );
				break;
			case 'date':
				echo get_the_date ( get_option ( 'date_format' ) );
				break;
		endswitch;
	endif;
}
add_action( 'manage_posts_custom_column', 'sp_event_custom_columns', 10, 2 );

/*
function sp_event_edit_sortable_columns( $columns ) {
	$columns['sp_team'] = 'sp_team';
	return $columns;
}
add_filter( 'manage_edit-sp_event_sortable_columns', 'sp_event_edit_sortable_columns' );

function sp_event_column_sorting( $vars ) {
  if( isset( $vars['orderby'] ) && 'sp_team' == $vars['orderby'] ){
    $vars = array_merge( $vars, array(
      'meta_key' => 'sp_team_1',
      'orderby'  => 'meta_value'
    ) );
  }
  return $vars;
}
add_filter('requests', 'sp_event_column_sorting');
*/

function sp_event_request_filter_dropdowns() {
	global $typenow, $wp_query;
	if ( $typenow == 'sp_event' ) {

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
add_action( 'restrict_manage_posts', 'sp_event_request_filter_dropdowns' );
?>