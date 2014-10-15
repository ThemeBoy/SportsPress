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
					text: ed.getLang( 'sportspress.event' ),
					menu: [
						{
							text: ed.getLang( 'sportspress.details' ),
							onclick: function() {
								// triggers the thickbox
								var width = jQuery(window).width(), H = jQuery(window).height(), W = ( 720 < width ) ? 720 : width;
								W = W - 80;
								H = H - 84;
								tb_show( ed.getLang( 'sportspress.event' ) + ' - ' + ed.getLang( 'sportspress.details' ), 'admin-ajax.php?action=sportspress_event_details_shortcode&width=' + W + '&height=' + H );
							}
						},
						{
							text: ed.getLang( 'sportspress.results' ),
							onclick: function() {
								// triggers the thickbox
								var width = jQuery(window).width(), H = jQuery(window).height(), W = ( 720 < width ) ? 720 : width;
								W = W - 80;
								H = H - 84;
								tb_show( ed.getLang( 'sportspress.event' ) + ' - ' + ed.getLang( 'sportspress.results' ), 'admin-ajax.php?action=sportspress_event_results_shortcode&width=' + W + '&height=' + H );
							}
						},
						{
							text: ed.getLang( 'sportspress.performance' ),
							onclick: function() {
								// triggers the thickbox
								var width = jQuery(window).width(), H = jQuery(window).height(), W = ( 720 < width ) ? 720 : width;
								W = W - 80;
								H = H - 84;
								tb_show( ed.getLang( 'sportspress.event' ) + ' - ' + ed.getLang( 'sportspress.performance' ), 'admin-ajax.php?action=sportspress_event_performance_shortcode&width=' + W + '&height=' + H );
							}
						},
						{
							text: ed.getLang( 'sportspress.countdown' ),
							onclick: function() {
		                        // triggers the thickbox
		                        var width = jQuery(window).width(), H = jQuery(window).height(), W = ( 720 < width ) ? 720 : width;
		                        W = W - 80;
		                        H = H - 84;
		                        tb_show( ed.getLang( 'sportspress.event' ) + ' - ' + ed.getLang( 'sportspress.countdown' ), 'admin-ajax.php?action=sportspress_countdown_shortcode&width=' + W + '&height=' + H );
							}
						}
					]
				},
				{
					text: ed.getLang( 'sportspress.calendar' ),
					menu: [
						{
							text: ed.getLang( 'sportspress.calendar' ),
									onclick : function() {
		                        // triggers the thickbox
		                        var width = jQuery(window).width(), H = jQuery(window).height(), W = ( 720 < width ) ? 720 : width;
		                        W = W - 80;
		                        H = H - 84;
                        		tb_show( ed.getLang( 'sportspress.calendar' ) + ' - ' + ed.getLang( 'sportspress.calendar' ), 'admin-ajax.php?action=sportspress_event_calendar_shortcode&width=' + W + '&height=' + H );
		                    }
						},
						{
							text: ed.getLang( 'sportspress.list' ),
							onclick : function() {
		                        // triggers the thickbox
		                        var width = jQuery(window).width(), H = jQuery(window).height(), W = ( 720 < width ) ? 720 : width;
		                        W = W - 80;
		                        H = H - 84;
                        		tb_show( ed.getLang( 'sportspress.calendar' ) + ' - ' + ed.getLang( 'sportspress.list' ), 'admin-ajax.php?action=sportspress_event_list_shortcode&width=' + W + '&height=' + H );
		                    }
						},
						{
							text: ed.getLang( 'sportspress.blocks' ),
							onclick : function() {
		                        // triggers the thickbox
		                        var width = jQuery(window).width(), H = jQuery(window).height(), W = ( 720 < width ) ? 720 : width;
		                        W = W - 80;
		                        H = H - 84;
                        		tb_show( ed.getLang( 'sportspress.calendar' ) + ' - ' + ed.getLang( 'sportspress.blocks' ), 'admin-ajax.php?action=sportspress_event_blocks_shortcode&width=' + W + '&height=' + H );
		                    }
						}
					]
				},
				{
					text: ed.getLang( 'sportspress.league_table' ),
					onclick : function() {
                        // triggers the thickbox
                        var width = jQuery(window).width(), H = jQuery(window).height(), W = ( 720 < width ) ? 720 : width;
                        W = W - 80;
                        H = H - 84;
                		tb_show( ed.getLang( 'sportspress.league_table' ), 'admin-ajax.php?action=sportspress_league_table_shortcode&width=' + W + '&height=' + H );
                    }
				},
				{
					text: ed.getLang( 'sportspress.player' ),
					menu: [
						{
							text: ed.getLang( 'sportspress.details' ),
							onclick: function() {
								// triggers the thickbox
								var width = jQuery(window).width(), H = jQuery(window).height(), W = ( 720 < width ) ? 720 : width;
								W = W - 80;
								H = H - 84;
								tb_show( ed.getLang( 'sportspress.player' ) + ' - ' + ed.getLang( 'sportspress.details' ), 'admin-ajax.php?action=sportspress_player_details_shortcode&width=' + W + '&height=' + H );
							}
						},
						{
							text: ed.getLang( 'sportspress.statistics' ),
							onclick: function() {
								// triggers the thickbox
								var width = jQuery(window).width(), H = jQuery(window).height(), W = ( 720 < width ) ? 720 : width;
								W = W - 80;
								H = H - 84;
								tb_show( ed.getLang( 'sportspress.player' ) + ' - ' + ed.getLang( 'sportspress.statistics' ), 'admin-ajax.php?action=sportspress_player_statistics_shortcode&width=' + W + '&height=' + H );
							}
						}
					]
				},
				{
					text: ed.getLang( 'sportspress.player_list' ),		
					menu: [			
						{
							text: ed.getLang( 'sportspress.list' ),
							onclick : function() {
		                        // triggers the thickbox
		                        var width = jQuery(window).width(), H = jQuery(window).height(), W = ( 720 < width ) ? 720 : width;
		                        W = W - 80;
		                        H = H - 84;
                        		tb_show( ed.getLang( 'sportspress.player_list' ) + ' - ' + ed.getLang( 'sportspress.list' ), 'admin-ajax.php?action=sportspress_player_list_shortcode&width=' + W + '&height=' + H );
		                    }
						},
						{
							text: ed.getLang( 'sportspress.gallery' ),
							onclick : function() {
		                        // triggers the thickbox
		                        var width = jQuery(window).width(), H = jQuery(window).height(), W = ( 720 < width ) ? 720 : width;
		                        W = W - 80;
		                        H = H - 84;
                        		tb_show( ed.getLang( 'sportspress.player_list' ) + ' - ' + ed.getLang( 'sportspress.gallery' ), 'admin-ajax.php?action=sportspress_player_gallery_shortcode&width=' + W + '&height=' + H );
		                    }
						}
					]
				}
			]
		});
	});
})();
