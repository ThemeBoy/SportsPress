jQuery(document).ready(function($){

	// Dashboard countdown
	$("#sportspress_dashboard_status .sp_status_list li.countdown").each(function() {
		var $this = $(this), finalDate = $(this).data('countdown');
		$this.countdown(finalDate, function(event) {
			$this.find('strong').html(event.strftime("%D "+localized_strings.days+" %H:%M:%S"));
		});
	});

});