<?php
//This class MUST be called the same as the file name without ".php"
class pluginTemplate {
	//A short description of your plugin
	public $title = "The Template Plugin";
	//an array of file locations for scripts you want to load
	public $scripts = array();
	public $size = array(1, 250);
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
		return "<h1>Template Plugin</h1>";
	}
}
?>
