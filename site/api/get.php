<?php
if (array_key_exists('searchTerm',$_POST)){
	$searchTerm = preg_replace("/^\w{140}$/", "", $_POST['searchTerm']);
}
else {
	$searchTerm = "yrs";
}
//import required function
$className = preg_replace("/^\w{40}$/", "", $_POST['plugin']);
require_once "../../plugins/" . $className . ".php";
$thisClass = new $className;
//run the class main() function
$pluginOutput = $thisClass->update($searchTerm);

//echo ouput
$returnObj=array("last_updated"=>time(),
				"update"=>$thisClass->update,
				"markup"=>$pluginOutput,
			//	"scripts"=>file_get_contents("../../plugins/js/$className.js"),
				"scripts"=>$thisClass->scripts);
echo json_encode($returnObj);
?>
