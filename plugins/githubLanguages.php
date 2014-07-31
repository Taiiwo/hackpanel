<?php //This class MUST be called the same as the file name without ".php"
function recursive_array_search($needle,$haystack) {
    foreach($haystack as $key=>$value) {
        $current_key=$key;
        if($needle===$value OR (is_array($value) && recursive_array_search($needle,$value) !== false)) {
            return $current_key;
        }
    }
    return false;
}
class githubLanguages {
	//A short description of your plugin
	public $title = "Github";
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
		$query = urlencode('yrs');
		$url = "https://api.github.com/search/repositories?q=$query&sort=updated&order=desc";
		//Begin messy curl (This is the equivalent of $content = file_get_contents($url);, but with a useragent)
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_USERAGENT, "The Hack Dash App");
		$content = curl_exec($ch);
		curl_close($ch);
		//End messy curl
		$array = json_decode($content);
		$langCount = array(array("Language","Number of Projects"));
		foreach ($array->items as $item) {
			if ($item->language != NULL) {//[suggestion] also check the date they were created with ->created_at
				if (recursive_array_search($item->language, $langCount)){//if the language of the current item
										//is already in the language counter
					//get current number of languages for this language
					foreach ( $langCount as $i => $lang ){
						if ($lang[0] == $item->language){
							$index = $i;
						}
					}
					//add one to the current number
					$langCount[$index][1] = $langCount[$index][1] + 1;
				}
				else{
					//append it to the array
					array_push($langCount, array($item->language, 1));
				}
			}
		}
		$arrayData = json_encode($langCount);
		return '<div id="piechart" style="height: 265px;"></div>
    <script type="text/javascript">
drawChart();
function drawChart() {
	debugger;
        var data = google.visualization.arrayToDataTable('. $arrayData .');

        var options = {
	  chartArea: {left: 0, right: 0,  width: "100%", height: "80%", top: 30, bottom: 10},
	  height: 290,
	  legend: \'none\',//Omit this line and re-add the next two to put the legends back
		/*
	  legend: {textStyle: {color: "white", fontSize: 14}},
	  titleTextStyle: {color: "white"},
		*/
	  pieHole: 0.333,
          pieSliceText: \'label\',
	  backgroundColor: \'white\'
        };

        var chart = new google.visualization.PieChart(document.getElementById(\'piechart\'));
        chart.draw(data, options);
      }
    </script>
';
	}
}
?>
