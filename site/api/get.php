<?php
//Really ugly stuff to make php error the way I want.
register_shutdown_function( "fatal_handler" );
function fatal_handler() {
  $errfile = "unknown file";
  $errstr  = "shutdown";
  $errno   = E_CORE_ERROR;
  $errline = 0;

  $error = error_get_last();

  if( $error !== NULL) {
    $errno   = $error["type"];
    $errfile = $error["file"];
    $errline = $error["line"];
    $errstr  = $error["message"];

    error_display(format_error( $errno, $errstr, $errfile, $errline));
  }
}
function format_error( $errno, $errstr, $errfile, $errline ) {
  $trace = print_r( debug_backtrace( false ), true );

  $content  = "<table><thead bgcolor='#c8c8c8'><th>Item</th><th>Description</th></thead><tbody>";
  $content .= "<tr valign='top'><td><b>Error</b></td><td><pre>$errstr</pre></td></tr>";
  $content .= "<tr valign='top'><td><b>Errno</b></td><td><pre>$errno</pre></td></tr>";
  $content .= "<tr valign='top'><td><b>File</b></td><td>$errfile</td></tr>";
  $content .= "<tr valign='top'><td><b>Line</b></td><td>$errline</td></tr>";
  $content .= "<tr valign='top'><td><b>Trace</b></td><td><pre>$trace</pre></td></tr>";
  $content .= '</tbody></table>';

  return $content;
}
function error_display($e){
	die("This plugin is broken: " . $e);
}
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
	@include "../../plugins/" . $className . ".php";
	$thisClass = new $className;
}
else {
	die("That plugin does not exist: $className.php");
}
$thisClass = new $className;
//fetch javascript if exists
if (file_exists("../../plugins/js/$className.js")){
	$thisClass->scripts[] = "/*Injected*/\n".file_get_contents("../../plugins/js/$className.js");
}
if (file_exists("../../plugins/css/$className.css")){
	$style = file_get_contents("../../plugins/css/$className.css");
}
else {
	$style = "null";
}
$pluginOutput = $thisClass->update($searchTerm);
$returnObj = array("last_updated"=>time(),
			"update"=>$thisClass->update,
			"markup"=>$pluginOutput,
			"title"=>$thisClass->title,
			"style"=>$style,
			"scripts"=>$thisClass->scripts);
//echo ouput
echo json_encode($returnObj);
?>
