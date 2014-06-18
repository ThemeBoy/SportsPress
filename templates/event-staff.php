<?php
/**
 * Event Staff
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Templates
 * @version     1.1
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$defaults = array(
	'id' => get_the_ID(),
	'index' => 0,
	'number' => -1,
	'link_posts' => get_option( 'sportspress_event_link_staff', 'yes' ) == 'yes' ? true : false,
	'sortable' => get_option( 'sportspress_enable_sortable_tables', 'yes' ) == 'yes' ? true : false,
	'responsive' => get_option( 'sportspress_enable_responsive_tables', 'yes' ) == 'yes' ? true : false,
);

$staff = array_filter( sp_array_between( (array)get_post_meta( $id, 'sp_staff', false ), 0, $index ) );

if ( ! $staff ) return;

extract( $defaults, EXTR_SKIP );
?>
<div class="sp-table-wrapper sp-scrollable-table-wrapper">
	<table class="sp-event-performance sp-data-table <?php if ( $responsive ) { ?> sp-responsive-table<?php } if ( $sortable ) { ?> sp-sortable-table<?php } ?>">
		<thead>
			<tr>
				<th class="data-name"><?php _e( 'Staff', 'sportspress' ); ?></th>
				<th class="data-role"><?php _e( 'Role', 'sportspress' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php
			$i = 0;
			foreach( $staff as $staff_id ):

				if ( ! $staff_id )
					continue;

				$name = get_the_title( $staff_id );

				if ( ! $name )
					continue;

				echo '<tr class="' . ( $i % 2 == 0 ? 'odd' : 'even' ) . '">';

				if ( $link_posts ):
					$permalink = get_post_permalink( $staff_id );
					$name =  '<a href="' . $permalink . '">' . $name . '</a>';
				endif;

				echo '<td class="data-name">' . $name . '</td>';

				$role = get_post_meta( $staff_id, 'sp_role', true );

				// Staff role
				echo '<td class="data-role">' . $role . '</td>';

				echo '</tr>';

				$i++;

			endforeach;
			?>
		</tbody>
	</table>
</div>