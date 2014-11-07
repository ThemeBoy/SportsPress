jQuery(document).ready(function($){
	$( "#remove_sp_league_menu_logo" ).click(function() {
		$( ".sp-league-menu-logo-options" ).hide();
		$("<input>").attr({
		    type: "hidden",
		    id: "sp_league_menu_logo_removed",
		    name: "sp_league_menu_logo_removed"
		}).appendTo( $(this).parent() );
	});

	$( "#sportspress_league_menu_logo_width" ).on( "input", function() {
		$( ".sp-league-menu-logo-options img" ).css( "max-width", $(this).val() + 'px' );
	});

	$( "#sportspress_league_menu_logo_height" ).on( "input", function() {
		$( ".sp-league-menu-logo-options img" ).css( "max-height", $(this).val() + 'px' );
	});
});