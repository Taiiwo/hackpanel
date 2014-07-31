<?php
@require "simple_html_dom.php";
@require "db.php";
function loadYRSHacks(){
	$page_dom=file_get_html("http://hacks.youngrewiredstate.org/events/FOC2014");
	///ul/li/div[starts-with(@class,'image')/a/@href]
	$projects_links=$page_dom->find("div#projects ul li div.image a");
	global $db;
	$db_obj = new mysqli($db["host"],$db["user"],$db["pass"],$db["db"]);
	foreach($projects_links as $link){
		$href=$link->href;
		$project_dom=file_get_html("http://hacks.youngrewiredstate.org".$href);
		$git_location="";
		$git_user="";
		$git_name="";
		//insert the git info into the local git db
		$result=$db_obj->query("SELECT `git_location` FROM `git` WHERE `git_location`='".$link->href."'");
		if($result!==FALSE & $result->num_rows>0){
			echo "updating";
			$db_obj->query("UPDATE `git` SET `url`='".$href."', `git_location`=$git_location, `git_user`=$git_name, `git_name`=$git_user
						WHERE `git_location`=$git_location");
		}
		else{
			echo "inserting";
			$query="INSERT INTO `git` SET `url`='".$href."', 
										  `git_location`='".$git_location."', 
										  `git_user`='".$git_name."', 
										  `git_name`='".$git_user."'";
			$insert=$db_obj->query($query);
			if($insert==FALSE){
				die("error".$query);
			}
		}
		echo $link->href;
		return;
	}
}