<?php
//This class MUST be called the same as the file name without ".php"
class infoPlugin {
	//A short description of your plugin
	public $title = "Event Information";
	//an array of file locations for scripts you want to load
	public $scripts = array();
	//This sets plugin size
	public $size = array(2, 250);
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
		$details = getSearchAlias($searchTerm, array('description', 'default','image'));
		return "
		<style>.infoPlugin {
			background-image: url(". $details['image'] .");
			background-size: cover;
			background-position: center center;
		}
		</style>
		<div class=\"infoContainer\">
		<h1>". $details['default'] ."</h1>
		<p>". $details['description'] ."</p>
		</div>
";
	}
}
?>
