jQuery(document).ready(function($){
	$("#title").keyup(function() {
		val = $(this).val();
		if ( val == '' ) val = 'f(x)';
		$(".sp-equation-variable").text( val + ' =' );
	});

    $(".sp-equation-parts .button").draggable({
      appendTo: "body",
      helper: "clone",
      cursor: "move",
      distance: 10,
      containment: "#sp_equationdiv",
    }).click(function() {
    	$("<label class='button'></label>").text( $(this).text() ).append("<span class='remove'>&times;</span><input name='sp_equation[]' type='hidden' value='" + $(this).data("variable") + "'>").appendTo( $(".sp-equation-formula") );
    });

    $(".sp-equation").droppable({
      activeClass: "ui-state-active",
      hoverClass: "ui-state-hover",
      accept: ".button:not(.ui-sortable-helper)",
      drop: function( event, ui ) {
        $("<label class='button'></label>").text( ui.draggable.text() ).append("<span class='remove'>&times;</span><input name='sp_equation[]' type='hidden' value='" + ui.draggable.data("variable") + "'>").appendTo( $(".sp-equation-formula") );
      }
    }).sortable({
      items: ".button",
      tolerance: "pointer",
      containment: "#sp_equationdiv",
      sort: function() {
        $( this ).removeClass( "ui-state-active" );
      }
    });

    $(".sp-equation-formula").on("click", ".button .remove", function() {
    	$(this).closest(".button").remove();
    });
});