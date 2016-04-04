jQuery(document).ready(function($){
	$(".sp-scoreboard-content").each(function() {
		$parent = $(this);
		$el = $parent.find(".sp-scoreboard");
		if ( $el.outerWidth() <= $parent.innerWidth() ) {
			$(this).closest(".sp-scoreboard-wrapper").find(".sp-scoreboard-next").addClass("sp-scoreboard-nav-disabled");
		}
	});
	
	$(".sp-scoreboard-nav").click(function() {
		step = $(this).data("sp-step");
		if ( ! step ) step = 100;
		$wrapper = $(this).closest(".sp-scoreboard-wrapper");
		$parent = $(this).siblings(".sp-scoreboard-content");
		$el = $parent.find(".sp-scoreboard");
		if ( $el.is(":animated") ) return false;
		left = $el.position().left;
		if ( $(this).hasClass("sp-scoreboard-prev") ) {
			$(this).closest(".sp-scoreboard-wrapper").find(".sp-scoreboard-next").removeClass("sp-scoreboard-nav-disabled");
			if ( left >= $wrapper.innerWidth() - $parent.outerWidth() - step * 2 ) {
				$(this).closest(".sp-scoreboard-wrapper").find(".sp-scoreboard-prev").addClass("sp-scoreboard-nav-disabled");
				$el.animate({
					left : 0
				});
			} else {
				$el.animate({
					left : "+=" + step
				});
			}
		} else {
			$(this).closest(".sp-scoreboard-wrapper").find(".sp-scoreboard-prev").removeClass("sp-scoreboard-nav-disabled");
			overflow = $el.outerWidth() - $parent.innerWidth() + left;
			if ( overflow <= step * 2 ) {
				$(this).closest(".sp-scoreboard-wrapper").find(".sp-scoreboard-next").addClass("sp-scoreboard-nav-disabled");
				$el.animate({
					left : $parent.innerWidth() - $el.outerWidth()
				});
			} else {
				$el.animate({
					left : "-=" + step
				});
			}
		}
		return false;
	});
});