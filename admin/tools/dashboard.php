<?php
/**
 * Admin Dashboard
 *
 * @author      ThemeBoy
 * @category    Admin
 * @package     SportsPress/Admin
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

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
        if ( current_user_can( 'view_sportspress_reports' ) || current_user_can( 'manage_sportspress' ) || current_user_can( 'publish_sp_tables' ) ) {
            add_action( 'wp_dashboard_setup', array( $this, 'init' ) );
        }
    }

    /**
     * Init dashboard widgets
     */
    public function init() {
        wp_add_dashboard_widget( 'sportspress_dashboard_status', __( 'SportsPress Status', 'sportspress' ), array( $this, 'status_widget' ) );
    }

    /**
     * Show status widget
     */
    public function status_widget() {
        $next_event = sportspress_get_next_event();
        $now = new DateTime( current_time( 'mysql', 0 ) );
        $date = new DateTime( $next_event->post_date );
        $interval = date_diff( $now, $date );

        $count = wp_count_posts( 'sp_event' );
        $scheduled_count = $count->future;
        $published_count = $count->publish;
        ?>
        <ul class="sp_status_list">
            <?php if ( $next_event ): ?>
            <li class="countdown" data-countdown="<?php echo str_replace( '-', '/', $next_event->post_date ); ?>">
                <a href="<?php echo get_edit_post_link( $next_event->ID ); ?>">
                    <?php printf( __( '<strong>%s</strong> until next event', 'sportspress' ), $interval->d . ' ' . __( 'days', 'sportspress' ) . ' ' . sprintf( '%02s:%02s:%02s', $interval->h, $interval->i, $interval->s ) ); ?>
                    (<?php echo $next_event->post_title; ?>)
                </a>
            </li>
            <?php endif; ?>
            <li class="events-scheduled">
                <a href="<?php echo admin_url( 'edit.php?post_type=sp_event&post_status=future' ); ?>">
                    <?php printf( _n( '<strong>%s event</strong> scheduled', '<strong>%s events</strong> scheduled', $scheduled_count, 'sportspress' ), $scheduled_count ); ?>
                </a>
            </li>
            <li class="events-published">
                <a href="<?php echo admin_url( 'edit.php?post_type=sp_event&post_status=publish' ); ?>">
                    <?php printf( _n( '<strong>%s event</strong> published', '<strong>%s events</strong> published', $published_count, 'sportspress' ), $published_count ); ?>
                </a>
            </li>
        </ul>
        <?php
    }

    /**
     * Recent reviews widget
     */
    public function recent_reviews() {
        global $wpdb;
        $comments = $wpdb->get_results( "SELECT *, SUBSTRING(comment_content,1,100) AS comment_excerpt
        FROM $wpdb->comments
        LEFT JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID = $wpdb->posts.ID)
        WHERE comment_approved = '1'
        AND comment_type = ''
        AND post_password = ''
        AND post_type = 'product'
        ORDER BY comment_date_gmt DESC
        LIMIT 8" );

        if ( $comments ) {
            echo '<ul>';
            foreach ( $comments as $comment ) {

                echo '<li>';

                echo get_avatar( $comment->comment_author, '32' );

                $rating = get_comment_meta( $comment->comment_ID, 'rating', true );

                echo '<div class="star-rating" title="' . esc_attr( $rating ) . '">
                    <span style="width:'. ( $rating * 20 ) . '%">' . $rating . ' ' . __( 'out of 5', 'sportspress' ) . '</span></div>';

                echo '<h4 class="meta"><a href="' . get_permalink( $comment->ID ) . '#comment-' . absint( $comment->comment_ID ) .'">' . esc_html__( $comment->post_title ) . '</a> ' . __( 'reviewed by', 'sportspress' ) . ' ' . esc_html( $comment->comment_author ) .'</h4>';
                echo '<blockquote>' . wp_kses_data( $comment->comment_excerpt ) . ' [...]</blockquote></li>';

            }
            echo '</ul>';
        } else {
            echo '<p>' . __( 'There are no product reviews yet.', 'sportspress' ) . '</p>';
        }
    }

}

endif;

return new SP_Admin_Dashboard();
