function viewport() {
    var e = window, a = 'inner';
    if (!('innerWidth' in window )) {
        a = 'client';
        e = document.documentElement || document.body;
    }
    return { width : e[ a+'Width' ] , height : e[ a+'Height' ] };
}

(function($) {

	var sp_responsive_activated = false;

	/* Header */
	$('body').prepend( '<div class="sp-header"></div>' );

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

	/* Style pagination control */
	$.extend( $.fn.dataTableExt.oPagination, {
	    "sportspress": {
	        "fnInit": function( oSettings, nPaging, fnDraw ) {
	            var oLang = oSettings.oLanguage.oPaginate;
	            var fnClickHandler = function ( e ) {
	                e.preventDefault();
	                if ( oSettings.oApi._fnPageChange(oSettings, e.data.action) ) {
	                    fnDraw( oSettings );
	                }
	            };
	 
	            $(nPaging).addClass('pagination').append(
	                '<table class="sp-data-table sp-pagination" role="navigation">'+
	                	'<tfoot><tr>' +
		                    '<td class="prev disabled"><a>&laquo; '+oLang.sPrevious+'</a></td>'+
		                    '<td class="next disabled"><a>'+oLang.sNext+' &raquo; </a></td>'+
						'</tr></tfoot>' +
	                '</table>'
	            );
	            var els = $('td', nPaging);
	            $(els[0]).bind( 'click.DT', { action: "previous" }, fnClickHandler );
	            $(els[1]).bind( 'click.DT', { action: "next" }, fnClickHandler );
	        },
	 
	        "fnUpdate": function ( oSettings, fnDraw ) {
	            var iListLength = 5;
	            var oPaging = oSettings.oInstance.fnPagingInfo();
	            var an = oSettings.aanFeatures.p;
	            var i, j, sClass, iStart, iEnd, iHalf=Math.floor(iListLength/2);
	 
	            if ( oPaging.iTotalPages < iListLength) {
	                iStart = 1;
	                iEnd = oPaging.iTotalPages;
	            }
	            else if ( oPaging.iPage <= iHalf ) {
	                iStart = 1;
	                iEnd = iListLength;
	            } else if ( oPaging.iPage >= (oPaging.iTotalPages-iHalf) ) {
	                iStart = oPaging.iTotalPages - iListLength + 1;
	                iEnd = oPaging.iTotalPages;
	            } else {
	                iStart = oPaging.iPage - iHalf + 1;
	                iEnd = iStart + iListLength - 1;
	            }
	 
	            for ( i=0, iLen=an.length ; i<iLen ; i++ ) {
	                // Remove the middle elements
	                $('td:gt(0)', an[i]).filter(':not(:last)').remove();
	 
	                // Add the new cells and their event handlers
	                for ( j=iStart ; j<=iEnd ; j++ ) {
	                    sClass = (j==oPaging.iPage+1) ? 'class="active"' : '';
	                    $('<td '+sClass+'><a>'+j+'</a></td>')
	                        .insertBefore( $('td:last', an[i])[0] )
	                        .bind('click', function (e) {
	                            e.preventDefault();
	                            oSettings._iDisplayStart = (parseInt($('a', this).text(),10)-1) * oPaging.iLength;
	                            fnDraw( oSettings );
	                        });
	                }
	 
	                // Add / remove disabled classes from the static elements
	                if ( oPaging.iPage === 0 ) {
	                    $('td:first', an[i]).addClass("disabled");
	                } else {
	                    $('td:first', an[i]).removeClass("disabled");
	                }
	 
	                if ( oPaging.iPage === oPaging.iTotalPages-1 || oPaging.iTotalPages === 0 ) {
	                    $('td:last', an[i]).addClass("disabled");
	                } else {
	                    $('td:last', an[i]).removeClass("disabled");
	                }
	            }
	        }
	    }
	} );

	/* Data Tables */
	$(".sp-data-table").each(function() {
		sortable = viewport().width > 640 && $(this).hasClass("sp-sortable-table");
		paginated = $(this).hasClass("sp-paginated-table");
		display_length = parseInt($(this).attr("data-sp-rows"));
		if ( display_length == undefined || isNaN( display_length ) ) display_length = 10;
		if ( $(this).find("tbody tr").length <= display_length ) paginated = false;
		if ( sortable || paginated ) {
			$(this).dataTable({
				"aaSorting": [],
				"bAutoWidth": false,
				"bFilter": false,
				"bInfo": false,
				"bPaginate": paginated,
				"bLengthChange": false,
				"sPaginationType": "sportspress",
				"iDisplayLength": display_length,
				"bSort": sortable,
			    "oLanguage": {
			      "oAria": {
			        "sSortAscending": "",
			        "sSortDescending": ""
			      },
			      "oPaginate": {
			      	"sPrevious": localized_strings.previous,
			      	"sNext": localized_strings.next,
			      }
			    },
			    "aoColumnDefs": [
			      { "sType": "numeric", "aTargets": [ ".data-number", ".data-rank" ] },
			    ]
			});
		}
	});


	/*
	 * Responsive Tables
	 *
	 * Based on responsive-tables.js by ZURB
	 *
	 * Credit:      ZURB
	 * Original:    https://github.com/zurb/responsive-tables
	 */
	var sp_responsive_activated = true;
	var sp_responsive_switched = false;
	var sp_update_tables = function() {
	if ((viewport().width <= 640) && !sp_responsive_switched ){
	  sp_responsive_switched = true;
	  $(".sp-responsive-table").each(function(i, element) {
	    sp_split_table($(element));
	  });
	  return true;
	}
	else if (sp_responsive_switched && (viewport().width > 640)) {
	  sp_responsive_switched = false;
	  $(".sp-responsive-table").each(function(i, element) {
	    sp_unsplit_table($(element));
	  });
	}
	};

	$(window).load(sp_update_tables);
	$(window).on("redraw",function(){sp_responsive_switched=false;sp_update_tables();}); // An event to listen for
	$(window).on("resize", sp_update_tables);


	function sp_split_table(original)
	{
		original.wrap("<div class='sp-responsive-table-wrapper' />");
		
		var copy = original.clone();
		copy.find("td:not(.data-number):not(.data-name):not(.data-rank):not(.data-date), th:not(.data-number):not(.data-name):not(.data-rank):not(.data-date)").css("display", "none");
		copy.removeClass("sp-responsive-table");
		
		original.closest(".sp-responsive-table-wrapper").append(copy);
		copy.wrap("<div class='sp-pinned-table' />");
		original.wrap("<div class='scrollable' />");

	sp_set_cell_heights(original, copy);
	}

	function sp_unsplit_table(original) {
		original.closest(".sp-responsive-table-wrapper").find(".sp-pinned-table").remove();
		original.unwrap();
		original.unwrap();
	}

	function sp_set_cell_heights(original, copy) {
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

})(jQuery);