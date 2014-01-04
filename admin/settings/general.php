<h3 class="title"><?php _e( 'General Settings', 'sportspress' ); ?></h3>
<?php

	settings_fields( 'sportspress_general' );
	do_settings_sections( 'sportspress_general' );
	submit_button();

?>