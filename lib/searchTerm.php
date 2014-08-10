<?php
function getSearchAlias($term,$keys){
	//check if searches file exists
	if(!file_exists("../../site/api/searches.json"))return false;
	$hackathonsJSON=file_get_contents("../../site/api/searches.json");
	$hackathons=json_decode($hackathonsJSON,true);
	$found = false;
	//see if we have the hackathon already
	foreach ($hackathons as $hackathon){
		if ($hackathon["default"]==$term){
			$found = true;
			break;
		}
	}
	//If we haven't got it
	if (!$found) {
		//We don't have information on that hackathon yet, so let's search for it!
		$hackathon = getEventDetails($term);
		if ($hackathon) {
			//put some stuff here to add $hackathon to searches.json
		}
		else {
			//no results found, die
			return false;
		}
	}
	$returnArray = array();
	foreach ($keys as $key){
		if(array_key_exists($key,$hackathon)){
			$returnArray[$key]=$hackathon[$key];
		}
		else{
			$returnArray[$key]=NULL;
		}
	}
	return $returnArray;
}
function getEventDetails($searchTerm) {
	//api url
	$apiKey = "kMvCXkjNRCk7X787";
	$url = "http://api.eventful.com/rest/events/search?app_key=$apiKey&keywords=$searchTerm";
	$events = simplexml_load_file($url);
	if (!count($events) > 0){
		//no results
		return false;
	}
	$events = $events->events->event[0];
	$retme = array();
	$retme["default"] = $searchTerm;
	$retme["lat"] = $events->latitude;
	$retme["lng"] = $events->longitude;
	$retme["start-date"] = $events->start_time;
	$retme["end-date"] = $events->stop_time;
	$retme["description"] = $events->description;
	return $retme;
}
?>
