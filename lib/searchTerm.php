<?php
function getSearchAlias($term,$keys){
	if(!file_exists("../../site/api/searches.json"))die("couldn't find json");
	$hackathonsJSON=file_get_contents("../../site/api/searches.json");
	$hackathons=json_decode($hackathonsJSON,true);
	foreach ($hackathons as $hackathon){
		if ($hackathon["default"]==$term){
			break;
		}
	}
	$returnArray=[];
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