<?php //This class MUST be called the same as the file name without ".php" 
class wolframResults {
	//A short description of your plugin
	public $title = "Searches Wolfram Alpha";
	public $scripts;
        //This sets whether you want the plugin to be continually updated.
        public $update = false;
	// This function is run once at plugin initialisation
	// External calls to APIs where the data should not change
	// should be run here to help reduce update time.
	function __construct() {

	}
	//This function will be executed every time your plugin is updated.
	function update($searchTerm){
		$wa = simplexml_load_file("http://api.wolframalpha.com/v2/query?input=" . $searchTerm . "&appid=QPEPAR-TKWEJ3W7VA");
		/*
		if ( $wa->pod[1]->subpod->plaintext == '' ){
			return "null";
		}
		else {*/
			return str_replace("\n", "<br />", '<p>' . $wa->pod[1]->subpod->plaintext . '</p>');
		//}
	}
}
?>
