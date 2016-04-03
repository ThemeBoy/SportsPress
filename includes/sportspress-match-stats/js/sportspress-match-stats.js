/* Match Stats */
(function($) {
	$(".sp-statistic-bar-fill").each(function() {
		$(this).css("width", 0).waypoint({
			handler: function(direction) {
				$(this.element).animate({
					width: $(this.element).data("sp-percentage")+"%"
				});
			},
  			offset: "100%"
		});
	});
})(jQuery);
