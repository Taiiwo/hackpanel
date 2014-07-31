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
function initialize() {
	var latLong = new google.maps.LatLng(50.375715, -4.139280);
  var mapOptions = {
    zoom: 17,
    center: latLong
  };
	var map = new google.maps.Map(document.getElementById(\'map-canvas\'),
			mapOptions);
	var marker = new google.maps.Marker({
    position: latLong,
    map: map,
    title:"Festival of Code 2014"
	});
}

initialize();
</script>';
	}
}
?>
