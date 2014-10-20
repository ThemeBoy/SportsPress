jQuery(document).ready(function($){
	$(".sp-tournament .sp-team-name").hover(function() {
		$el = $(this).closest(".sp-tournament").find(".sp-team-name[data-team="+$(this).data("team")+"]");
		$el.addClass("sp-heading").removeClass("sp-highlight");
	}, function (){
		$el.addClass("sp-highlight").removeClass("sp-heading");
	});
});