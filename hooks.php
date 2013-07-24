<?php
// Flush rewrite rules on activation to make sure permalinks work properly
function sp_rewrite_flush() {
    sp_team_cpt_init();
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'sp_rewrite_flush' );
?>