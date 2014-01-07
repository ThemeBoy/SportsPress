<?php
function sp_manage_posts_columns() {
	sp_highlight_admin_menu();
}
add_action( 'manage_posts_columns', 'sp_manage_posts_columns' );
