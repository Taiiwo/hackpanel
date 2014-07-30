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
//fetch javascript if exists
if (file_exists("../../plugins/js/$className.js")){
	$script = file_get_contents("../../plugins/js/$className.js");
}
else {
	$script = NULL;
}
$thisClass = new $className;
$pluginOutput = $thisClass->update($searchTerm);
$returnObj = array("last_updated"=>time(),
			"update"=>$thisClass->update,
			"markup"=>$pluginOutput,
			"title"=>$thisClass->title,
			"scripts"=>$script);
//echo ouput
echo json_encode($returnObj);
?>
