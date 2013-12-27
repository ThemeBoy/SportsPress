<?php
// Flush rewrite rules on activation to make sure permalinks work properly
function sp_rewrite_flush() {
    sp_config_cpt_init();
    sp_event_cpt_init();
    sp_team_cpt_init();
    sp_table_cpt_init();
    sp_player_cpt_init();
    sp_list_cpt_init();
    sp_staff_cpt_init();
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'sp_rewrite_flush' );
?>