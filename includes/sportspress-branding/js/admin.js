jQuery(document).ready(function($){
	$( "#remove_sp_branding_icon" ).click(function() {
		$(this).hide();
		$( ".sp-branding-icon-options img" ).hide();
		$("<input>").attr({
		    type: "hidden",
		    id: "sp_branding_icon_removed",
		    name: "sp_branding_icon_removed"
		}).appendTo( $(this).parent() );
	});
});