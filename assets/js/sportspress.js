function viewport() {
    var e = window, a = 'inner';
    if (!('innerWidth' in window )) {
        a = 'client';
        e = document.documentElement || document.body;
    }
    return { width : e[ a+'Width' ] , height : e[ a+'Height' ] };
}

(function($) {

	// Data tables
	if (viewport().width > 640) {
		$(".sp-league-table, .sp-event-statistics, .sp-player-list").dataTable({
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
		      { "sType": "numeric", "aTargets": [ 0 ] },
		    ]
		});

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
	}

	// Responsive tables
	var switched = false;
	var updateTables = function() {
	if ((viewport().width <= 640) && !switched ){
	  switched = true;
	  $(".sp-responsive-table").each(function(i, element) {
	    splitTable($(element));
	  });
	  return true;
	}
	else if (switched && (viewport().width > 640)) {
	  switched = false;
	  $(".sp-responsive-table").each(function(i, element) {
	    unsplitTable($(element));
	  });
	}
	};

	$(window).load(updateTables);
	$(window).on("redraw",function(){switched=false;updateTables();}); // An event to listen for
	$(window).on("resize", updateTables);


	function splitTable(original)
	{
		original.wrap("<div class='sp-responsive-table-wrapper' />");
		
		var copy = original.clone();
		copy.find("td:not(.data-number):not(.data-name), th:not(.data-number):not(.data-name)").css("display", "none");
		copy.removeClass("sp-responsive-table");
		
		original.closest(".sp-responsive-table-wrapper").append(copy);
		copy.wrap("<div class='sp-pinned-table' />");
		original.wrap("<div class='scrollable' />");

	setCellHeights(original, copy);
	}

	function unsplitTable(original) {
	original.closest(".sp-responsive-table-wrapper").find(".sp-pinned-table").remove();
	original.unwrap();
	original.unwrap();
	}

	function setCellHeights(original, copy) {
	var tr = original.find('tr'),
	    tr_copy = copy.find('tr'),
	    heights = [];

	tr.each(function (index) {
	  var self = $(this),
	      tx = self.find('th, td');

	  tx.each(function () {
	    var height = $(this).outerHeight(true);
	    heights[index] = heights[index] || 0;
	    if (height > heights[index]) heights[index] = height;
	  });

	});

	tr_copy.each(function (index) {
	  $(this).height(heights[index]);
	});
	}

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