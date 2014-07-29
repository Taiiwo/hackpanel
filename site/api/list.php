<?php
$list = scandir("../../plugins/");
$list = array_slice($list, 2);
for ( $i = 0; $i < $list.length; $i++){
	if (preg_match("/^\w{40}\.php$/", $list[$i])){
	$list[$i] = str_replace(".php", "", $list[$i]);
}
echo json_encode($list);
?>
