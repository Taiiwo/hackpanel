<?php
//This class MUST be called the same as the file name without ".php"
class pluginTemplate {
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
		return "<h1>LOL</h1>";
	}
}
?>
