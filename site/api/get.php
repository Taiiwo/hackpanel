<?php
//import required function
$className = preg_replace("/^\w{40}$/", "", $_POST['className']);
$searchTerm = preg_replace("/^\w{140}$/", "", $_POST['searchTerm']);
require_once "../../plugins/" . $className . ".php";
$thisClass = new $className;
//run the class main() function
$pluginOutput = $thisClass->update($searchTerm);

//echo ouput
$returnObj=array("last_updated"=>time(),
				"markup"=>$pluginOutput,
				"scripts"=>$thisClass->scripts)
echo json_encode($returnObj);
?>
