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
					menu: [
						{
							text: ed.getLang( 'sportspress.manual' ),
							onclick: function() {
								editor.insertContent( '[countdown id="" live="1"]' );
							}
						},
						{
							text: ed.getLang( 'sportspress.auto' ),
							onclick: function() {
								editor.insertContent( '[countdown]' );
							}
						}
					]
				},
				{
					text: ed.getLang( 'sportspress.event' ),
					menu: [
						{
							text: ed.getLang( 'sportspress.single' ),
							menu: [
								{
									text: ed.getLang( 'sportspress.details' ),
									onclick: function() {
										editor.insertContent( '[event_details id=""]' );
									}
								},
								{
									text: ed.getLang( 'sportspress.results' ),
									onclick: function() {
										editor.insertContent( '[event_results id=""]' );
									}
								},
								{
									text: ed.getLang( 'sportspress.performance' ),
									onclick: function() {
										editor.insertContent( '[event_performance id=""]' );
									}
								}
							]
						},
						{
							text: ed.getLang( 'sportspress.calendar' ),
							menu: [
								{
									text: ed.getLang( 'sportspress.select' ),
									onclick : function() {
				                        // triggers the thickbox
				                        var width = jQuery(window).width(), H = jQuery(window).height(), W = ( 720 < width ) ? 720 : width;
				                        W = W - 80;
				                        H = H - 84;
				                        tb_show( 'My WIndow POPUP Title', '#TB_inline?width=' + W + '&height=' + H + '&inlineId=sp_choose_event_calendar' );
				                    }
								},
								{
									text: ed.getLang( 'sportspress.manual' ),
									onclick: function() {
										editor.insertContent( '[event_calendar id="" status="default" initial="1" show_all_events_link="0"]' );
									}
								},
								{
									text: ed.getLang( 'sportspress.auto' ),
									onclick: function() {
										editor.insertContent( '[event_calendar]' );
									}
								}
							]
						},
						{
							text: ed.getLang( 'sportspress.list' ),
							menu: [
								{
									text: ed.getLang( 'sportspress.select' ),
									onclick : function() {
				                        // triggers the thickbox
				                        var width = jQuery(window).width(), H = jQuery(window).height(), W = ( 720 < width ) ? 720 : width;
				                        W = W - 80;
				                        H = H - 84;
				                        tb_show( 'My WIndow POPUP Title', '#TB_inline?width=' + W + '&height=' + H + '&inlineId=sp_choose_event_list' );
				                    }
								},
								{
									text: ed.getLang( 'sportspress.manual' ),
									onclick: function() {
										editor.insertContent( '[event_list id="" status="default" show_all_events_link="0"]' );
									}
								}
							]
						},
						{
							text: ed.getLang( 'sportspress.blocks' ),
							menu: [
								{
									text: ed.getLang( 'sportspress.select' ),
									onclick : function() {
				                        // triggers the thickbox
				                        var width = jQuery(window).width(), H = jQuery(window).height(), W = ( 720 < width ) ? 720 : width;
				                        W = W - 80;
				                        H = H - 84;
				                        tb_show( 'My WIndow POPUP Title', '#TB_inline?width=' + W + '&height=' + H + '&inlineId=sp_choose_event_blocks' );
				                    }
								},
								{
									text: ed.getLang( 'sportspress.manual' ),
									onclick: function() {
										editor.insertContent( '[event_blocks id="" status="default" show_all_events_link="0"]' );
									}
								}
							]
						}
					]
				},
				{
					text: ed.getLang( 'sportspress.league_table' ),
					menu: [
						{
							text: ed.getLang( 'sportspress.select' ),
									onclick : function() {
				                        // triggers the thickbox
				                        var width = jQuery(window).width(), H = jQuery(window).height(), W = ( 720 < width ) ? 720 : width;
				                        W = W - 80;
				                        H = H - 84;
				                        tb_show( 'My WIndow POPUP Title', '#TB_inline?width=' + W + '&height=' + H + '&inlineId=sp_choose_league_table' );
				                    }
						},
						{
							text: ed.getLang( 'sportspress.manual' ),
							onclick: function() {
								editor.insertContent( '[league_table id="" number="-1" show_team_logo="1" link_posts="0" show_full_table_link="0"]' );
							}
						}
					]
				},
				{
					text: ed.getLang( 'sportspress.player' ),
					menu: [
						{
							text: ed.getLang( 'sportspress.single' ),
							menu: [
								{
									text: ed.getLang( 'sportspress.details' ),
									onclick: function() {
										editor.insertContent( '[player_details id=""]' );
									}
								},
								{
									text: ed.getLang( 'sportspress.statistics' ),
									onclick: function() {
										editor.insertContent( '[player_statistics id=""]' );
									}
								}
							]
						},
						{
							text: ed.getLang( 'sportspress.list' ),
							menu: [
								{
									text: ed.getLang( 'sportspress.select' ),
									onclick : function() {
				                        // triggers the thickbox
				                        var width = jQuery(window).width(), H = jQuery(window).height(), W = ( 720 < width ) ? 720 : width;
				                        W = W - 80;
				                        H = H - 84;
				                        tb_show( 'My WIndow POPUP Title', '#TB_inline?width=' + W + '&height=' + H + '&inlineId=sp_choose_player_list' );
				                    }
								},
								{
									text: ed.getLang( 'sportspress.manual' ),
									onclick: function() {
										editor.insertContent( '[player_list id="" number="-1" orderby="default" order="ASC" show_all_players_link="0"]' );
									}
								}
							]
						},
						{
							text: ed.getLang( 'sportspress.gallery' ),
							menu: [
								{
									text: ed.getLang( 'sportspress.select' ),
									onclick : function() {
				                        // triggers the thickbox
				                        var width = jQuery(window).width(), H = jQuery(window).height(), W = ( 720 < width ) ? 720 : width;
				                        W = W - 80;
				                        H = H - 84;
				                        tb_show( 'My WIndow POPUP Title', '#TB_inline?width=' + W + '&height=' + H + '&inlineId=sp_choose_player_gallery' );
				                    }
								},
								{
									text: ed.getLang( 'sportspress.manual' ),
									onclick: function() {
										editor.insertContent( '[player_gallery id="" number="-1" columns="3" orderby="default" order="ASC" size="thumbnail" show_all_players_link="0"]' );
									}
								}
							]
						}
					]
				}
			]
		});
	});
})();
