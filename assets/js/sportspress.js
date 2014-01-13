(function($) {

	// League Table Sorting
	$(".sp-league-table").dataTable({
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
			{ "asSorting": [ "asc" ], "aTargets": [ 0 ] },
		]
	});

	// Player List Sorting
	$(".sp-player-list").dataTable({
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
			{ "asSorting": [ "asc" ], "aTargets": [ 0 ] },
		]
	});

	// Player Statistics Sorting
	$(".sp-player-statistics").dataTable({
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
	    }
	});

})(jQuery);