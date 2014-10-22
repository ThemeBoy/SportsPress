jQuery(document).ready(function($){
	$(".sp-location-picker").locationpicker({
		location: {
			latitude: Number($(".sp-latitude").val()),
			longitude: Number($(".sp-longitude").val())
		},
		radius: 0,
		inputBinding: {
	        latitudeInput: $(".sp-latitude"),
	        longitudeInput: $(".sp-longitude"),
	        locationNameInput: $(".sp-address")
	    },
	    enableAutocomplete: true
	});
});