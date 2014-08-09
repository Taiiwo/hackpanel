<?php
function getSearchAlias($term,$keys){
	if(!file_exists("../../site/api/searches.json"))return false;
	$hackathonsJSON=file_get_contents("../../site/api/searches.json");
	$hackathons=json_decode($hackathonsJSON,true);
	foreach ($hackathons as $hackathon){
		if ($hackathon["default"]==$term){
			break;
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
function getEventDetails(searchTerm) {
	getSearchAlias
	//api url
	$url = "" . searchTerm;
	//get json data
	eventsJson = json_decode(file_get_contents($url));
	retme = array();
	retme['irc'] = eventsJson->ircChannel;
	//etc
	return retme;
}
?>
