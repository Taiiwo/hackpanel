<?php
$list = scandir("../../plugins/");
$list = array_slice($list, 2);
for ( $i = 0; $i < count($list); $i++){
	//if the file/folder ends in ".php"
	if (preg_match("/\w*\.php$/", $list[$i])){
		$list[$i] = preg_replace("/.php$/", "", $list[$i]);
	}
	else {
		//remove incorrectly named files from the list
		array_splice($list, $i, 1);
		//Ajust the iteration after key removal
		$i -= 1;
	}
}
echo json_encode($list);
?>
