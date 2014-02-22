<?php
function sportspress_media_buttons() {

	//if ( ! in_array( array('post.php', 'page.php', 'page-new.php', 'post-new.php')

	?>
	<a href="#TB_inline?width=480&inlineId=select_sp_table" class="thickbox button sp-insert sp-insert-map" id="add_gform" title="<?php _e( 'Add Map', 'sportspress' ); ?>"><span class="sp-buttons-icon sp-map-buttons-icon "></span> <?php _e( 'Add Map', 'sportspress' ); ?></a>
	<a href="#TB_inline?width=480&inlineId=select_sp_table" class="thickbox button sp-insert sp-insert-table" id="add_gform" title="<?php _e( 'Add League Table', 'sportspress' ); ?>"><span class="sp-buttons-icon sp-table-buttons-icon "></span> <?php _e( 'Add League Table', 'sportspress' ); ?></a>
	<?php
}
add_action( 'media_buttons', 'sportspress_media_buttons', 20 );
