<?php
/**
 * Fixture exporter - export fixtures from SportsPress.
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Exporters
 * @version		2.8.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( $_POST && isset( $_POST['submit'] ) ) {
	$args = array(
			'post_type' => 'sp_event',
			'posts_per_page' => -1,
			'meta_query' => array(
				'relation' => 'AND'
			),
			'tax_query' => array(
				'relation' => 'AND'
			),
		);
	if ( $_POST['sp_league'] != "-1" ) {
		$args['tax_query'][] = array(
					'taxonomy' => 'sp_league',
					'field' => 'slug',
					'terms' => $_POST['sp_league']
				);
	}
	if ( $_POST['sp_season'] != "-1" ) {
		$args['tax_query'][] = array(
					'taxonomy' => 'sp_season',
					'field' => 'slug',
					'terms' => $_POST['sp_season']
				);
	}
	$events = get_posts( $args );
	if ( $events ) {
		$number_of_events = count ( $events );
		ob_start();
		$df = fopen("php://output", 'w');
		$events_array[] = array ( 'event_id', 'date', 'time', 'home', 'away', 'venue', 'day' );
		$i = 1;
		foreach ( $events as $event ) {
			$events_array[$i]['event_id'] = $event->ID; //team_id
			$events_array[$i]['date'] = get_the_date( 'Y/m/d', $event ); //date
			$events_array[$i]['time'] = get_the_date( 'H:i:s', $event ); //time
			$teams = get_post_meta ( $event->ID, 'sp_team' );
			$events_array[$i]['home'] = get_the_title( $teams[0] ); //home
			$events_array[$i]['away'] = get_the_title( $teams[1] ); //away
			$venues = get_the_terms( $event->ID, 'sp_venue' );
			if ( $venues ) {
				$venue = $venues[0]->name;
			}else{
				$venue = '';
			}
			$events_array[$i]['venue'] = $venue; //venue
			$events_array[$i]['day'] = get_post_meta ( $event->ID, 'sp_day', true ); //day
			$i++;
		}
		foreach ( $events_array as $event_row ) {
			fputcsv( $df, $event_row );
		}
		fclose( $df );
		$export_data = ob_get_clean();
		
		header("Content-type: application/x-msdownload",true,200);
		header("Content-Disposition: attachment; filename=data.csv");
		header("Pragma: no-cache");
		header("Expires: 0");
		
		echo $export_data;
		
		echo '</br>Exported ' . $number_of_events . ' events';
		echo '</br><button type="button" onclick="history.back();">Go Back </button>';
		exit;
	}
}
?>
<div class="wrap">
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
	<form method="post" action="<?php //echo esc_html( admin_url( 'admin-post.php' ) ); ?>">
		<ul id="post-filters" class="export-filters" style="display: block;">
			<li>
				<label><span class="label-responsive"><?php _e( 'League', 'sportspress' ); ?></span>
					<?php
					$args = array(
						'taxonomy' => 'sp_league',
						'name' => 'sp_league',
						'values' => 'slug',
						'show_option_none' => __( '&mdash; Not set &mdash;', 'sportspress' ),
					);
					if ( ! sp_dropdown_taxonomies( $args ) ):
						echo '<p>' . __( 'None', 'sportspress' ) . '</p>';
						sp_taxonomy_adder( 'sp_league', 'sp_team', __( 'Add New', 'sportspress' ) );
					endif;
					?>
				</label>
			</li>
			<li>
				<label><span class="label-responsive"><?php _e( 'Season', 'sportspress' ); ?></span>
				<?php
					$args = array(
						'taxonomy' => 'sp_season',
						'name' => 'sp_season',
						'values' => 'slug',
						'show_option_none' => __( '&mdash; Not set &mdash;', 'sportspress' ),
					);
					if ( ! sp_dropdown_taxonomies( $args ) ):
						echo '<p>' . __( 'None', 'sportspress' ) . '</p>';
						sp_taxonomy_adder( 'sp_season', 'sp_team', __( 'Add New', 'sportspress' ) );
					endif;
					?>
				</label>
			</li>
		</ul>
		<?php submit_button( __( 'Export', 'textdomain' ) );?>
	</form>
</div>
