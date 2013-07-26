<?php
function sp_settings_menu() {
	add_submenu_page(
		'options-general.php',
		'SportsPress',
		'SportsPress',
		'manage_options',
		'sp_settings',
		'sp_settings_page'
	);
}
add_action('admin_menu', 'sp_settings_menu');

function sp_settings_page() {
     echo '<input type="text" id="datepicker" name="example[datepicker]" value="" class="sp_datepicker" />';
     /*
	if ( true | ! current_user_can( 'manage_options' ) ) wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	$hidden_field_name = 'tb_submit_hidden';
?>
<div class="wrap">
	<div id="icon-options-general" class="icon32"><br></div>
	<h2><?php _e('General Settings', 'sportspress'); ?></h2>
	<?php
		if( isset( $_POST[ $hidden_field_name ] ) && $_POST[ $hidden_field_name ] == 'Y' ) {
			global $tb_option_fields;
			foreach( $tb_option_fields['settings'] as $option_field => $value ) {
				$new_value = isset( $_POST[$option_field] ) ? $_POST[$option_field] : null;
				update_option( $option_field, stripslashes( $new_value ) );
			}
	?>
	<div id="message" class="updated"><p><strong><?php _e( 'Settings saved.' ); ?></strong></p></div>
	<?php
		}
	?>
	<form name="sportspress_form" method="post" action="">
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><?php _e( 'Home Team', 'sportspress' ); ?></th>
					<td>
						<?php $option_slug = 'tb_default_club'; ?>
						<?php
						tb_dropdown_posts( array(
							'post_type' => 'tb_club',
							'limit' => -1,
							'show_option_none' => __( 'None' ),
							'selected' => get_option( $option_slug ),
							'name' => $option_slug,
							'id' => $option_slug
						) );
						?>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="tb_region_code"><?php _e( 'Country', 'sportspress' ); ?></label></th>
					<td>
						<?php
							global $tb_countries_of_the_world;
							asort( $tb_countries_of_the_world );
							echo form_dropdown( 'tb_region_code', $tb_countries_of_the_world, get_option( 'tb_region_code' ) );
						?>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e( 'Header' ); ?></th>
					<td>
						<?php $option_slug = 'tb_header_sponsor'; ?>
						<label for="<?php echo $option_slug; ?>"><?php _e( 'Sponsor', 'sportspress' ); ?> 1:</label>						
						<?php
						tb_dropdown_posts( array(
							'post_type' => 'tb_sponsor',
							'limit' => -1,
							'show_option_none' => __( 'None' ),
							'selected' => get_option( $option_slug ),
							'name' => $option_slug,
							'id' => $option_slug
						) );
						?><br />
						<?php $option_slug = 'tb_header_sponsor_2'; ?>
						<label for="<?php echo $option_slug; ?>"><?php _e( 'Sponsor', 'sportspress' ); ?> 2:</label>						
						<?php
						tb_dropdown_posts( array(
							'post_type' => 'tb_sponsor',
							'limit' => -1,
							'show_option_none' => __( 'None' ),
							'selected' => get_option( $option_slug ),
							'name' => $option_slug,
							'id' => $option_slug
						) );
						?>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e( 'Footer' ); ?></th>
					<td>
						<?php $option_slug = 'tb_footer_show_sponsors'; ?>
						<input name="<?php echo $option_slug; ?>" type="checkbox" id="<?php echo $option_slug; ?>" value="1"<?php if( get_option( $option_slug ) ) echo ' checked' ?> />
						<label for="<?php echo $option_slug; ?>"><?php _e( 'Sponsors', 'sportspress' ); ?></label>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e( 'Layout' ); ?></th>
					<td>
						<?php $option_slug = 'tb_responsive'; ?>
						<input name="<?php echo $option_slug; ?>" type="checkbox" id="<?php echo $option_slug; ?>" value="1"<?php if( get_option( $option_slug ) ) echo ' checked' ?> />
						<label for="<?php echo $option_slug; ?>"><?php _e( 'Responsive', 'sportspress' ); ?></label>
					</td>
				</tr>
			</tbody>
		</table>
		<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
		<?php submit_button( null, 'primary', 'save-sportspress-options' ); ?>
	</form>
</div>
<?php */ } ?>