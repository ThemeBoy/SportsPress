(function($) {

	// Data tables
	$(".sp-data-table").dataTable({
		"aaSorting": [],
		"bAutoWidth": false,
		"bFilter": false,
		"bInfo": false,
		"bPaginate": false,
		"bSort": true,
	    "oLanguage": {
	      "oAria": {
	        "sSortAscending": "",
	        "sSortDescending": ""
	      }
	    },
		"aoColumnDefs": [
			{ "asSorting": [ "asc" ], "aTargets": [ 0 ] }
		]
	});

})(jQuery);