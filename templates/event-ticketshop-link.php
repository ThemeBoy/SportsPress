<?php
/**
 * Event Video
 *
 * @author      ThemeBoy
 * @package     SportsPress/Templates
 * @version     1.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! isset( $id ) ) {
	$id = get_the_ID();
}
    $event = new SP_Event( $id );
    $post = get_post($id);
    $ticketshop_link = $event->ticketshop_link();
    $hide_past_events = get_option( 'sportspress_ticketshop_hide_past_events', 'yes' ) == 'yes' ? true : false;

    if ($hide_past_events && $post->post_status != 'future' ) {
        return;
    }

    if(isset($ticketshop_link) && $ticketshop_link != '') {
        $ticketshop_label = get_option( 'sportspress_ticketshop_label');

        ?>
        <h3 class="gdlr-core-sp-title-box">Ticketshop</h3>
        <div class="sp-template sp-template-event-ticketshop sp-template-event-blocks sp-template-event-ticketshop-block">
            <div class="sp-table-wrapper">
                <table class="sp-event-blocks sp-data-table" data-sp-rows="1">
                    <thead><tr><th></th></tr></thead> <?php // Required for DataTables ?>
                    <tbody>
                    <tr class="sp-row sp-post alternate">
                        <td>
                            <a href="<?php echo $ticketshop_link; ?>" target="_blank" class="sp-event-ticketshop-link"><?php echo $ticketshop_label; ?></a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <?php
    }



?>

