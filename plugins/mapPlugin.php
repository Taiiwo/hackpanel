<?php
//This class MUST be called the same as the file name without ".php"
class mapPlugin {
	//A short description of your plugin
	public $title = "Map Plugin";
	public $scripts = array();
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
		return '<div id="map-canvas"/><script>
var map;
function initialize() {
  var mapOptions = {
    zoom: 8,
    disableDefaultUI: true,
    center: new google.maps.LatLng(-34.397, 150.644)
  };
  map = new google.maps.Map(document.getElementById(\'map-canvas\'),
      mapOptions);
}
initialize();
</script>';
	}
}
?>
