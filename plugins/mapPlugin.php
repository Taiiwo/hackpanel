<?php
//This class MUST be called the same as the file name without ".php"
class mapPlugin {
	//A short description of your plugin
	public $title = "Map Plugin";
	public $scripts = array();
	public $size = array(2, 1);
	//This sets whether you want the plugin to be continually updated.
	public $update = false;
	// This function is run once at plugin initialisation
	// External calls to APIs where the data should not change
	// should be run here to help reduce update time.
	function __construct() {
		//untested
	}
	//This function will be executed every time your plugin is updated.
	function update($searchTerm){
		require_once('../../lib/searchTerm.php');
		$details = getSearchAlias($searchTerm, array('lat','lng'));
		$lat = $details['lat'];
		$lng = $details['lng'];
		return "<div id=\"map-canvas\"/><script>
function initialize() {
	var latLong = new google.maps.LatLng($lat, $lng);
  var mapOptions = {
    zoom: 17,
    center: latLong
  };
	var map = new google.maps.Map(document.getElementById('map-canvas'),
			mapOptions);
	var marker = new google.maps.Marker({
    position: latLong,
    map: map,
    title:\"$searchTerm\"
	});
}

initialize();
</script>";
	}
}
?>
