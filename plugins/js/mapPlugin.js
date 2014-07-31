function initialize() {
		var latLong = new google.maps.LatLng(50.375715, -4.139280);
		var mapOptions = {
			center: latLong,
			zoom: 17
		};
		var map = new google.maps.Map(document.getElementById("map-canvas"),
			mapOptions);

		var marker = new google.maps.Marker({
  		position: latLong,
	 		map: map,
  		title:"Plymouth University"
		});
}
