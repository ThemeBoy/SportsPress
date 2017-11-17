<?php
/**
 * Event Officials List
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Templates
 * @version   2.5
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$scrollable = get_option( 'sportspress_enable_scrollable_tables', 'yes' ) == 'yes' ? true : false;
?>
<div class="sp-template sp-template-event-officials sp-template-details">
	<h4 class="sp-table-caption"><?php _e( 'Officials', 'sportspress' ); ?></h4>
	<div class="sp-list-wrapper">
		<dl class="sp-event-officials">
			<?php
			foreach ( $labels as $key => $label ) {
				$appointed_officials = (array) sp_array_value( $data, $key, array() );
				if ( empty( $appointed_officials ) ) continue;

				echo '<dt>' . $label . '</dt>';

				foreach ( $appointed_officials as $official_id => $official_name ) {
					if ( $link_officials && sp_post_exists( $official_id ) ) {
						$official_name = '<a href="' . get_post_permalink( $official_id ) . '">' . $official_name . '</a>';
					}
					echo '<dd>' . $official_name . '</dd>';
				}
			}
			?>
		</dl>
	</div>
</div>