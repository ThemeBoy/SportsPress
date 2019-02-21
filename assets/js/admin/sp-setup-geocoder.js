//Get variables form input values
latitude = document.getElementById('sp_latitude').value;
longitude = document.getElementById('sp_longitude').value;
	
//Initialize the map and add the Search control box
var map = L.map('sp-location-picker').setView([latitude, longitude], 15),
	geocoder = L.Control.Geocoder.nominatim(),
	control = L.Control.geocoder({
		geocoder: geocoder,
		collapsed: false,
		defaultMarkGeocode: false
	}).addTo(map),
	//Add a marker to use from the begining
	marker = L.marker([latitude, longitude],{draggable: true, autoPan: true}).addTo(map);

L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
	attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);

//Pass the values to the fields after dragging
marker.on('dragend', function (e) {
	document.getElementById('sp_latitude').value = marker.getLatLng().lat;
	document.getElementById('sp_longitude').value = marker.getLatLng().lng;
	geocoder.reverse(marker.getLatLng(), map.options.crs.scale(map.getZoom()), function(results) {
		var r = results[0];
		if (r) {
			document.getElementById('sp_address').value = r.name;
		}
	})
});

//After searching
control.on('markgeocode', function(e) {
	var center = e.geocode.center;
	var address = e.geocode.name;
	map.setView([center.lat, center.lng], 15); //Center map to the new place
	map.removeLayer(marker); //Remove previous marker
	marker = L.marker([center.lat, center.lng],{draggable: true, autoPan: true}).addTo(map); //Add new marker to use
	//Pass the values to the fields after searching
	document.getElementById('sp_latitude').value = center.lat;
	document.getElementById('sp_longitude').value = center.lng;
	document.getElementById('sp_address').value = address;
	//Pass the values to the fields after dragging
	marker.on('dragend', function (e) {
		document.getElementById('sp_latitude').value = marker.getLatLng().lat;
		document.getElementById('sp_longitude').value = marker.getLatLng().lng;
		geocoder.reverse(marker.getLatLng(), map.options.crs.scale(map.getZoom()), function(results) {
			var r = results[0];
			if (r) {
				document.getElementById('sp_address').value = r.name;
			}
		})
	});
}).addTo(map);