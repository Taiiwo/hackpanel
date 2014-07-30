<?php
//This class MUST be called the same as the file name without ".php"
class githubLanguages {
	//A short description of your plugin
	public $title = "Github";
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
		$array = json_decode(get_file_contents('https://api.github.com/search/repositories?q=Tetris&sort=stars&order=desc'));
		return;
		$langCount = array();
		foreach ($array[$items] as $item) {
			$langCount->push($item['language']);
		}
		
	}
}
?>
