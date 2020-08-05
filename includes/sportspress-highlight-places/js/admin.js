jQuery(document).ready(function($){
	// Split statistics row
	$(".sp-highlight-places").on("click", ".sp-add-row", function() {
		$row = `<tr class="sp-row">
						<td>
							<div class="sp-color-box">
								<input name="sp_highlight_places" id="sp_color" type="text" value="#ffffff" class="colorpick">
								<div id="sp_color" class="colorpickdiv"></div>
							</div>
						</td>
						<td>
							<input name="sp_place" id="sp_place" type="number" value="" class="sp_place" min="1">
						</td>
						<td>
							<input name="sp_highlight_places" id="sp_place_desc" type="text" value="" class="sp_place_desc" placeholder="i.e. Champion">
						</td>
						<td class="sp-actions-column">
							<a href="#" title="Delete row" class="dashicons dashicons-dismiss sp-delete-row"></a>
						</td>
					</tr>`;
		
		$("table.sp-highlight-places tbody").append($row);
		
		// Re-Initiate Color picker
		$('.colorpick').iris( {
			change: function(event, ui){
				$(this).css( { backgroundColor: ui.color.toString() } );
			},
			hide: true,
			border: true
		} ).each( function() {
			$(this).css( { backgroundColor: $(this).val() } );
		})
		.click(function(){
			$('.iris-picker').hide();
			$(this).closest('.sp-color-box, td').find('.iris-picker').show();
		});

		$('body').click(function() {
			$('.iris-picker').hide();
		});

		$('.sp-color-box, .colorpick').click(function(event){
			event.stopPropagation();
		});

	return false;
	});
	
	$(document).on('input', '#sp_place', function(){
		$self = $(this);
		var place = $(this).closest("tr.sp-row").find("input[id='sp_place']").val();
		$(this).closest("tr.sp-row").find("input#sp_color").attr('name', 'sp_highlight_places['+place+'][color]');
		$(this).closest("tr.sp-row").find("input#sp_place_desc").attr('name', 'sp_highlight_places['+place+'][desc]');
	})
	
	// Delete added row
	$(".sp-highlight-places").on("click", ".sp-delete-row", function() {
		$self = $(this);
		$self.closest("tr").css("background-color", "#f99").fadeOut(400, function() {
			$(this).remove();
		});
		return false;
	});
});