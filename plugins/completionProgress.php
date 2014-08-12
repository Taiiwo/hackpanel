<?php
//This class MUST be called the same as the file name without ".php"
class completionProgress {
	//A short description of your plugin
	public $title = "Completion Progress";
	//an array of file locations for scripts you want to load
	public $scripts = array();
	//This sets whether you want the plugin to be continually updated.
	public $update = true;
	// This function is run once at plugin initialisation
	// External calls to APIs where the data should not change
	// should be run here to help reduce update time.
	function __construct() {
		//untested
	}
	//This function will be executed every time your plugin is updated.
	function update($searchTerm){
		require_once('../../lib/searchTerm.php');
		$details = getSearchAlias($searchTerm, array('start-date', 'end-date'));
		$totalTime = strtotime($details['end-date']) - strtotime($details['start-date']);
		$timeFromStart = time() - $details['start-date'];
		$time = $timeFromStart / $totalTime * 100;
		if ($time < 0){
			$time = 0;
		}
		if ($time > 100){
			$time = 100;
		}
		return "
<h1 class=\"center large\">$time%</h1><p>
start: ". $details['start-date'] ."

end: ". $details['end-date'] ."</p>
";
	}
}
?>
