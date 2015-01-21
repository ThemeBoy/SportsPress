/* global tinymce */
( function () {
	tinymce.PluginManager.add( 'sp_shortcodes_button', function( editor, url ) {
		var ed = tinymce.activeEditor;

		var groups = ed.getLang( 'sportspress.shortcodes' ).split("]");
		var menu = new Array();

		groups.forEach(function(g) {
			if ( "" == g ) return;
			var p = g.split("[");
			var label = p.shift();
			var variations = p.shift();
			var shortcodes = variations.split("|");
			var submenu = new Array();
			shortcodes.forEach(function(s) {
				submenu.push({
					text: ed.getLang( 'sportspress.' + s ),
					onclick: function() {
                        // triggers the thickbox
                        var width = jQuery(window).width(), H = jQuery(window).height(), W = ( 720 < width ) ? 720 : width;
                        W = W - 80;
                        H = H - 84;
                        tb_show( ed.getLang( 'sportspress.' + label ) + ' - ' + ed.getLang( 'sportspress.' + s ), 'admin-ajax.php?action=sportspress_' + label + '_' + s + '_shortcode&width=' + W + '&height=' + H );
					}
				});
			});
			menu.push({
				text: ed.getLang( 'sportspress.' + label ),
				menu: submenu
			});
		});

		editor.addButton( 'sp_shortcodes_button', {
			title: ed.getLang('sportspress.insert'),
			text: false,
			icon: false,
			type: 'menubutton',
			menu: menu
		});
	});
})();
