<?php
//This class MUST be called the same as the file name without ".php"
class mapPlugin {
	//A short description of your plugin
	public $title = "Map Plugin";
	public $scripts;
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
		return '<div id="map-canvas"/>
		<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDmP4F7uI_pUSW9duPY6xQjoRb0iJgcuic"></script>
		<script type="text/javascript">
      function initialize() {
        var mapOptions = {
          center: new google.maps.LatLng(-34.397, 150.644),
          zoom: 8
        };
        var map = new google.maps.Map(document.getElementById("map-canvas"),
            mapOptions);
      }
      google.maps.event.addDomListener(window, \'load\', initialize);
    </script>';
	}
}
?>
