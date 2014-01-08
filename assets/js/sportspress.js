(function($) {

	// Data tables
	$('.sp-data-table').dataTable({
		'aaSorting': [],
		'bFilter': false,
		'bInfo': false,
		'bPaginate': false,
		'bSort': true,
	    'oLanguage': {
	      'oAria': {
	        'sSortAscending': '',
	        'sSortDescending': ''
	      }
	    },
		'aoColumnDefs': [
			{ 'bSortable': false, 'aTargets': [ 0 ] }
		]
	});

})(jQuery);