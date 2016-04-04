jQuery(document).ready(function($){
	$(".sp-scoreboard-nav").click(function() {
		step = $(this).data("sp-step");
		if ( ! step ) step = 100;
		$wrapper = $(this).closest(".sp-scoreboard-wrapper");
		$parent = $(this).siblings(".sp-scoreboard-content");
		$el = $parent.find(".sp-scoreboard");
		if ( $el.is(":animated") ) return false;
		left = $el.position().left;
		if ( $(this).hasClass("sp-scoreboard-prev") ) {
			if ( left >= $wrapper.innerWidth() - $parent.outerWidth() - step ) {
				$(this).closest(".sp-scoreboard-wrapper").find(".sp-scoreboard-prev").addClass("sp-scoreboard-nav-disabled");
				$el.animate({
					left : 0
				});
			} else {
				$(this).closest(".sp-scoreboard-wrapper").find(".sp-scoreboard-next").removeClass("sp-scoreboard-nav-disabled");
				$el.animate({
					left : "+=" + step
				});
			}
		} else {
			overflow = $el.outerWidth() - $parent.innerWidth() + left;
			console.log(overflow);
			if ( overflow <= step ) {
				$(this).closest(".sp-scoreboard-wrapper").find(".sp-scoreboard-next").addClass("sp-scoreboard-nav-disabled");
				$el.animate({
					left : $parent.innerWidth() - $el.outerWidth()
				});
			} else {
				$(this).closest(".sp-scoreboard-wrapper").find(".sp-scoreboard-prev").removeClass("sp-scoreboard-nav-disabled");
				$el.animate({
					left : "-=" + step
				});
			}
		}
		return false;
	});
});