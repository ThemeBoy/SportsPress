/* global tinymce */
( function () {
	tinymce.PluginManager.add( 'sp_shortcodes_button', function( editor, url ) {
		var ed = tinymce.activeEditor;
		editor.addButton( 'sp_shortcodes_button', {
			title: ed.getLang('sportspress.insert'),
			text: false,
			icon: false,
			type: 'menubutton',
			menu: [
				{
					text: ed.getLang( 'sportspress.countdown' ),
					onclick: function() {
						editor.insertContent( '[countdown id="" live=""]' );
					}
				},
				{
					text: ed.getLang( 'sportspress.event_details' ),
					onclick: function() {
						editor.insertContent( '[event_details id=""]' );
					}
				},
				{
					text: ed.getLang( 'sportspress.event_results' ),
					onclick: function() {
						editor.insertContent( '[event_results id=""]' );
					}
				},
				{
					text: ed.getLang( 'sportspress.event_performance' ),
					onclick: function() {
						editor.insertContent( '[event_performance id=""]' );
					}
				},
				{
					text: ed.getLang( 'sportspress.event_calendar' ),
					onclick: function() {
						editor.insertContent( '[event_calendar id="" status="default" show_all_events_link="0"]' );
					}
				},
				{
					text: ed.getLang( 'sportspress.event_list' ),
					onclick: function() {
						editor.insertContent( '[event_list id="" status="default" show_all_events_link="0"]' );
					}
				},
				{
					text: ed.getLang( 'sportspress.event_blocks' ),
					onclick: function() {
						editor.insertContent( '[event_blocks id="" status="default" show_all_events_link="0"]' );
					}
				},
				{
					text: ed.getLang( 'sportspress.league_table' ),
					onclick: function() {
						editor.insertContent( '[league_table id="" number="-1" show_team_logo="1" link_posts="0" show_full_table_link="0"]' );
					}
				},
				{
					text: ed.getLang( 'sportspress.player_list' ),
					onclick: function() {
						editor.insertContent( '[player_list id="" number="-1" orderby="default" order="ASC" show_all_players_link="0"]' );
					}
				},
				{
					text: ed.getLang( 'sportspress.player_gallery' ),
					onclick: function() {
						editor.insertContent( '[event_blocks id="" number="-1" columns="3" orderby="default" order="ASC" size="thumbnail" show_all_players_link="0"]' );
					}
				}
			]
		});
	});
})();
