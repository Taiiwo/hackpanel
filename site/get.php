<?php
//import required function
$className = preg_replace("/\w{40}/", "", $_POST['className']);
require_once "../plugins/$className.php";
//run all class's main() function
//echo ouput
?>
