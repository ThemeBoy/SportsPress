jQuery(document).ready(function($){
	// Split statistics row
	$(".sp-highlight-places").on("click", ".sp-add-row", function() {
		$row = `<tr class="sp-row">
						<td>
							<div class="sp-color-box">
								<input name="sp_color" id="sp_color" type="text" value="#75e462" class="colorpick">
								<div id="sp_color" class="colorpickdiv"></div>
							</div>
						</td>
						<td>
							<input name="sp_place" id="sp_place" type="number" value="" class="sp_place" min="1">
						</td>
						<td>
							<input name="sp_place_desc" id="sp_place_desc" type="text" value="" class="sp_place_desc" placeholder="i.e. Champion">
						</td>
						<td class="sp-actions-column">
							<a href="#" title="<?php _e( 'Delete row', 'sportspress' ); ?>" class="dashicons dashicons-dismiss sp-delete-row"></a>
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
	
	// Delete added row
	$(".sp-highlight-places").on("click", ".sp-delete-row", function() {
		$self = $(this);
		$self.closest("tr").css("background-color", "#f99").fadeOut(400, function() {
			$(this).remove();
		});
		return false;
	});
});