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

	// Google Maps
	function initialize_google_maps() {
		$maps = $('.sp-google-map');
		$maps.each(function() {
			$self = $(this);
			address = $self.attr('data-address');
			latitude = $self.attr('data-latitude');
			longitude = $self.attr('data-longitude');
			var ll = new google.maps.LatLng(latitude,longitude);
			var mapOptions = {
				zoom: 16,
				center: ll
			};
			var map = new google.maps.Map($self[0], mapOptions)
			var marker = new google.maps.Marker({
				position: ll,
				map: map,
				animation: google.maps.Animation.DROP,
				flat: true,
				title: address
			});
		});
	}
	google.maps.event.addDomListener(window, "load", initialize_google_maps);

})(jQuery);