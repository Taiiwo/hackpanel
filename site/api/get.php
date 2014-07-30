<?php
if (array_key_exists('searchTerm',$_POST) && $_POST['searchTerm'] != ''){
	$searchTerm = preg_replace("/^\w{140}$/", "", $_POST['searchTerm']);
}
else {
	$searchTerm = "Young Rewired State";
}
//import required function
if (array_key_exists('plugin',$_POST) && $_POST['plugin'] != ''){
	$className = preg_replace("/^\w{40}$/", "", $_POST['plugin']);
}
else {
	die("[E] invalid class name");
}
if (file_exists("../../plugins/" . $className . ".php")) {
	require_once "../../plugins/" . $className . ".php";
}
else {
	die("That plugin does not exist: $className.php");
}
$thisClass = new $className;
//fetch javascript if exists
if (file_exists("../../plugins/js/$className.js")){
	$thisClass->scripts[] = "/*Injected*/".file_get_contents("../../plugins/js/$className.js");
}
$pluginOutput = $thisClass->update($searchTerm);
$returnObj = array("last_updated"=>time(),
			"update"=>$thisClass->update,
			"markup"=>$pluginOutput,
			"title"=>$thisClass->title,
			"scripts"=>$thisClass->scripts);
//echo ouput
echo json_encode($returnObj);
?>
