<?php
//import required function
$className = preg_replace("/^\w{40}$/", "", $_POST['className']);
$searchTerm = preg_replace("^/\w{140}$/", "", $_POST['searchTerm']);
require_once "../../plugins/" . $className . ".php";
$thisClass = new $className;
//run the class main() function
print_r($thisClass);
$pluginOutput = $thisClass->main($searchTerm);

//echo ouput
echo $pluginOutput;
?>
