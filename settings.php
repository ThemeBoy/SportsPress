<?php
function sp_settings_menu() {
	add_submenu_page(
		'options-general.php',
		__( 'SportsPress', 'sportspress' ),
		__( 'SportsPress', 'sportspress' ),
		'manage_options',
		'sportspress',
		'sportspress_settings_page'
	);
}
add_action('admin_menu', 'sp_settings_menu');

function sportspress_settings_add_js() {
?>
<script type="text/javascript">
//<![CDATA[
	jQuery(document).ready(function($){
		$("input[name='date_format']").click(function(){
			if ( "date_format_custom_radio" != $(this).attr("id") )
				$("input[name='date_format_custom']").val( $(this).val() ).siblings('.example').text( $(this).siblings('span').text() );
		});
		$("input[name='date_format_custom']").focus(function(){
			$("#date_format_custom_radio").attr("checked", "checked");
		});

		$("input[name='time_format']").click(function(){
			if ( "time_format_custom_radio" != $(this).attr("id") )
				$("input[name='time_format_custom']").val( $(this).val() ).siblings('.example').text( $(this).siblings('span').text() );
		});
		$("input[name='time_format_custom']").focus(function(){
			$("#time_format_custom_radio").attr("checked", "checked");
		});
		$("input[name='date_format_custom'], input[name='time_format_custom']").change( function() {
			var format = $(this);
			format.siblings('.spinner').css('display', 'inline-block'); // show(); can't be used here
			$.post(ajaxurl, {
					action: 'date_format_custom' == format.attr('name') ? 'date_format' : 'time_format',
					date : format.val()
				}, function(d) { format.siblings('.spinner').hide(); format.siblings('.example').text(d); } );
		});
	});
//]]>
</script>
<?php
}
add_action('admin_head', 'sportspress_settings_add_js');

function sportspress_settings_page() {
	?>
	<div class="wrap">
		<div id="icon-options-general" class="icon32"><br></div>
		<h2 class="nav-tab-wrapper">
			<?php _e( 'SportsPress Settings', 'sportspress' ); ?>
			<a href="#" class="nav-tab nav-tab-active">Tab 1</a>
			<a href="#" class="nav-tab">Tab 2</a>
		</h2>
	<?php
	if ( ! current_user_can( 'manage_options' ) ) wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
?>

<form method="post" action="options.php">
<input type="hidden" name="option_page" value="general"><input type="hidden" name="action" value="update"><input type="hidden" id="_wpnonce" name="_wpnonce" value="e1cad3625d"><input type="hidden" name="_wp_http_referer" value="/sportspress/wp-admin/options-general.php">
<table class="form-table">
<tbody><tr valign="top">
<th scope="row"><label for="sp_team_stats_columns">Team Statistics</label></th>
<td>
	<p><?php sp_team_stats_sport_choice(); ?></p>
	<p>
		<textarea name="sp_team_stats_columns" rows="10" cols="50" id="sp_team_stats_columns" class="large-text code"><?php form_option('sp_team_stats_columns'); ?></textarea>
	</p>
</td>
</tr>
</tbody></table>

		<?php submit_button( null, 'primary', 'save-sportspress-options' ); ?>
	</form>
</div>
<?php } ?>