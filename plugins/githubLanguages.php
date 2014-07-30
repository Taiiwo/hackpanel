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
		$langCount = array();
		foreach ($array->items as $item) {
			if ($item->language != NULL) {//[bug] also check the date they were created with ->created_at
				if (array_key_exists($item->language, $langCount)){//if the language of the current item
										//is already in the language counter
					$langCount[$item->language] += 1;
				}
				else{
					//append it to the array
					$langCount[$item->language] = 1;
				}
			}
		}
		$toRet = "";
		foreach ($langCount as $language => $count){
			$toRet .= "$language: $count <br />";
		}
		return $toRet;
	}
}
?>
