<?php
/**
 * Tournament Groups
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Tournaments
 * @version     2.6.15
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if ( get_option( 'sportspress_tournament_show_tables', 'yes' ) === 'no' ) return;

$defaults = array(
	'id' => get_the_ID(),
);
extract( $defaults, EXTR_SKIP );

if ( ! isset( $id ) )
	$id = get_the_ID();

$tables = get_posts( array(
	'post_type' => 'sp_table',
	'posts_per_page' => -1,
	'orderby' => 'menu_order',
	'order' => 'ASC',
	'meta_query' => array(
		array(
			'key' => 'sp_tournament',
			'value' => $id,
		),
	),
) );

if ( empty( $tables ) ) return;

$table_ids = wp_list_pluck( $tables, 'ID' );
$i = 0;
?>
<div class="sp-tournament-tables">
	<h4 class="sp-tournament-tables-title"><?php _e( 'Groups', 'sportspress' ); ?></h4>
	<?php foreach ( $table_ids as $table_id ) { ?>
		<div class="sportspress sp-widget-align-<?php echo sizeof( $table_ids ) > 1 ? ( 0 == $i % 2 ? 'left' : 'right' ) : 'none'; ?>">
			<?php sp_get_template( 'league-table.php', array( 'id' => $table_id, 'columns' => null ) ); ?>
		</div>
	<?php $i++; } ?>
</div>