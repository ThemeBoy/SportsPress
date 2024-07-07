<?php
/**
 * Admin Dashboard
 *
 * @author      ThemeBoy
 * @category    Admin
 * @package     SportsPress/Admin
 * @version     2.7.15
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'SP_Admin_Dashboard' ) ) :

	/**
	 * SP_Admin_Dashboard Class
	 */
	class SP_Admin_Dashboard {

		/**
		 * Hook in tabs.
		 */
		public function __construct() {
			// Only hook in admin parts if the user has admin access
			if ( current_user_can( 'view_sportspress_reports' ) || current_user_can( 'manage_sportspress' ) || current_user_can( 'publish_sp_events' ) ) {
				add_action( 'wp_dashboard_setup', array( $this, 'init' ) );
			}
		}

		/**
		 * Init dashboard widgets
		 */
		public function init() {
			wp_add_dashboard_widget( 'sportspress_dashboard_status', esc_attr__( 'SportsPress', 'sportspress' ), array( $this, 'status_widget' ) );
			add_filter( 'dashboard_glance_items', array( $this, 'glance_items' ), 10, 1 );
		}

		/**
		 * Add links to At a Glance
		 */
		function glance_items( $items = array() ) {
			$post_types = apply_filters( 'sportspress_glance_items', array( 'sp_event', 'sp_team', 'sp_player', 'sp_staff' ) );
			foreach ( $post_types as $type ) :
				if ( ! post_type_exists( $type ) ) {
					continue;
				}
				$num_posts = wp_count_posts( $type );
				if ( $num_posts ) :
					$published = intval( $num_posts->publish );
					$post_type = get_post_type_object( $type );
					$text      = _n( '%s ' . $post_type->labels->singular_name, '%s ' . $post_type->labels->name, $published, 'sportspress' );
					$text      = sprintf( $text, number_format_i18n( $published ) );
					if ( current_user_can( $post_type->cap->edit_posts ) ) :
						$output = '<a href="edit.php?post_type=' . $post_type->name . '">' . $text . '</a>';
					else :
						$output = '<span>' . $text . '</span>';
					endif;
					echo '<li class="post-count ' . esc_attr( $post_type->name ) . '-count">' . wp_kses_post( $output ) . '</li>';
				endif;
			endforeach;
			return $items;
		}

		/**
		 * Show status widget
		 */
		public function status_widget() {
			?>
	  <ul class="sp_status_list">
			<?php
			  $count           = wp_count_posts( 'sp_event' );
			  $scheduled_count = isset( $count->future ) ? $count->future : 0;
			  $published_count = isset( $count->publish ) ? $count->publish : 0;
			  $next_event      = sp_get_next_event();
			if ( $next_event ) :
				$now      = new DateTime( current_time( 'mysql', 0 ) );
				$date     = new DateTime( $next_event->post_date );
				$interval = date_diff( $now, $date );
				?>
			  <li class="countdown" data-countdown="<?php echo esc_attr( str_replace( '-', '/', get_gmt_from_date( $next_event->post_date ) ) ); ?>">
				  <a href="<?php echo esc_url( get_edit_post_link( $next_event->ID ) ); ?>">
				  <?php printf( wp_kses_post( __( '<strong>%s</strong> until next event', 'sportspress' ) ), esc_html( $interval->days ) . ' ' . esc_html__( 'days', 'sportspress' ) . ' ' . sprintf( '%02s:%02s:%02s', esc_html( $interval->h ), esc_html( $interval->i ), esc_html( $interval->s ) ) ); ?>
					  (<?php echo esc_html( $next_event->post_title ); ?>)
				  </a>
			  </li>
		  <?php endif; ?>
		  <li class="events-scheduled">
			  <a href="<?php echo esc_url( admin_url( 'edit.php?post_type=sp_event&post_status=future' ).'savvas' ); ?>">
				  <?php printf( wp_kses_post( _n( '<strong>%s event</strong> scheduled', '<strong>%s events</strong> scheduled', $scheduled_count, 'sportspress' ) ), esc_attr( $scheduled_count ) ); ?>
			  </a>
		  </li>
		  <li class="events-published">
			  <a href="<?php echo esc_url( admin_url( 'edit.php?post_type=sp_event&post_status=publish' ) ); ?>">
				  <?php printf( wp_kses_post( _n( '<strong>%s event</strong> published', '<strong>%s events</strong> published', $published_count, 'sportspress' ) ), esc_attr( $published_count ) ); ?>
			  </a>
		  </li>
	  </ul>
			<?php
		}
	}

endif;

return new SP_Admin_Dashboard();
