function sp_viewport() {
    var e = window, a = 'inner';
    if (!('innerWidth' in window )) {
        a = 'client';
        e = document.documentElement || document.body;
    }
    return { width : e[ a+'Width' ] , height : e[ a+'Height' ] };
}

(function($) {

	/* Header */
	if ( ! $('.sp-header').size() ) {
		$('body').prepend( '<div class="sp-header sp-header-loaded"></div>' );
	}

	/* Countdown */
	$("[data-countdown]").each(function() {
		var $this = $(this), finalDate = $(this).data('countdown');
		$this.countdown(finalDate, function(event) {
			$this.html(event.strftime("<span>%D <small>" + localized_strings.days + "</small></span> "
			+ "<span>%H <small>" + localized_strings.hrs + "</small></span> "
			+ "<span>%M <small>" + localized_strings.mins + "</small></span> "
			+ "<span>%S <small>" + localized_strings.secs + "</small></span>" ));
		});
	});

	/* API method to get paging information */
	$.fn.dataTableExt.oApi.fnPagingInfo = function ( oSettings )
	{
	    return {
	        "iStart":         oSettings._iDisplayStart,
	        "iEnd":           oSettings.fnDisplayEnd(),
	        "iLength":        oSettings._iDisplayLength,
	        "iTotal":         oSettings.fnRecordsTotal(),
	        "iFilteredTotal": oSettings.fnRecordsDisplay(),
	        "iPage":          oSettings._iDisplayLength === -1 ?
	            0 : Math.ceil( oSettings._iDisplayStart / oSettings._iDisplayLength ),
	        "iTotalPages":    oSettings._iDisplayLength === -1 ?
	            0 : Math.ceil( oSettings.fnRecordsDisplay() / oSettings._iDisplayLength )
	    };
	}

	/* Data Tables */
	$(".sp-data-table").each(function() {
		sortable = sp_viewport().width > 640 && $(this).hasClass("sp-sortable-table");
		paginated = $(this).hasClass("sp-paginated-table");
		display_length = parseInt($(this).attr("data-sp-rows"));
		if ( display_length == undefined || isNaN( display_length ) ) display_length = 10;
		if ( $(this).find("tbody tr").length <= display_length ) paginated = false;
		if ( sortable || paginated ) {
			$(this).dataTable({
				"order": [],
				"autoWidth": false,
				"searching": false,
				"info": false,
				"paging": paginated,
				"lengthChange": false,
				"pagingType": "simple_numbers",
				"pageLength": display_length,
				"ordering": sortable,
			    "language": {
			      "aria": {
			        "sortAscending": "",
			        "sortDescending": ""
			      },
			      "paginate": {
			      	"previous": localized_strings.previous,
			      	"next": localized_strings.next,
			      }
			    },
			    "columnDefs": [
			      { "type": "num-fmt", "targets": [ ".data-number", ".data-rank" ] },
			    ]
			});
		}
	});

	/* Scrollable Tables */
	$(".sp-scrollable-table").wrap("<div class=\"sp-scrollable-table-wrapper\"></div>");

})(jQuery);