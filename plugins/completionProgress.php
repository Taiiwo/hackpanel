<?php
//This class MUST be called the same as the file name without ".php"
class completionProgress {
	//A short description of your plugin
	public $title = "Completion Progress";
	//an array of file locations for scripts you want to load
	public $scripts = array();
	//This sets whether you want the plugin to be continually updated.
	public $update = true;
	public $size = array(1, 250);
	// This function is run once at plugin initialisation
	// External calls to APIs where the data should not change
	// should be run here to help reduce update time.
	function __construct() {
		//untested
	}
	//This function will be executed every time your plugin is updated.
	function update($searchTerm){
		date_default_timezone_set('Europe/London');
		require_once('../../lib/searchTerm.php');
		$details = getSearchAlias($searchTerm, array('startDate', 'endDate'));
		//Replace /s for -s to abide by php's weird datetime formatting
		$details['endDate'] = str_replace('/','-',$details['endDate']);
		$details['startDate'] = str_replace('/','-',$details['startDate']);
		//calculate a few values about the dates
		//total event length
		$totalTime = strtotime($details['endDate'])- strtotime($details['startDate']);
		//event time elapsed
		$timeFromStart = time() - strtotime($details['startDate']);
		//percentage completed
		$time = $timeFromStart / $totalTime * 100;
		if ($time < 0){
			$time = 0;
		}
		if ($time > 100){
			$time = 100;
		}
		$retMe = array();
		$retMe[] = "<div id=\"completionProgress\"><h1 class=\"center large\">". round($time, 3)."%</h1>";
		$retMe[] = "<p>". $details['startDate'] ." - ". $details['endDate']."</p>";
		if ($time != 100 && $time != 0){
			$retMe[] = "<p>Time elapsed: ". round($timeFromStart / 60 / 60, 3) ." Hours</p>";
			$retMe[] = "<p>Time left: ". round(($totalTime - $timeFromStart) / 60 / 60, 3) ." Hours</p>";
		}
		$retMe[] = "<p>Total time: ". round($totalTime / 60 / 60, 3) ." Hours</p></div>";
		return implode('', $retMe);
	}
}
?>
