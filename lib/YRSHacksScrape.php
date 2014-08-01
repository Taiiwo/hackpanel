<?php
@require "simple_html_dom.php";
@require "db.php";
function loadYRSHacks(){
	$page_dom=file_get_html("http://hacks.youngrewiredstate.org/events/FOC2014");
	///ul/li/div[starts-with(@class,'image')/a/@href]
	$projects_links=$page_dom->find("div#projects ul li div.image a");
	global $db;
	$quit=0;
	$db_obj = new mysqli($db["host"],$db["user"],$db["pass"],$db["db"]);
	foreach($projects_links as $link){
		$href=$link->href;
		$project_url="http://hacks.youngrewiredstate.org".$href;
		$project_dom=file_get_html($project_url);
		$git_link=$project_dom->find(".icon_github a");
		if(count($git_link)>0){
			$git_location=$git_link[0]->href;
			$url_parts=explode('/',$git_location);
			for($i=0;$i<count($url_parts);$i++){
				if(preg_match("/github.com/i",$url_parts[$i])){
					break;
				}
			}
			$i++;
			$git_user=(count($url_parts)>$i)?$url_parts[$i]:null;
			$i++;
			$git_name=(count($url_parts)>$i)?$url_parts[$i]:null;
			//echo $href.','.$git_location.','.$git_user.','.$git_name;
			//insert the git info into the local git db
			$result=$db_obj->query("SELECT `git_location` FROM `git` WHERE `git_location`='".$git_location."'");
			if($result!==FALSE & $result->num_rows>0){
				//echo "updating";
				$db_obj->query("UPDATE `git` SET `url`='".$href."', 
												 `git_location`=$git_location, 
												 `git_user`=$git_name, 
												 `git_name`=$git_user
							WHERE `git_location`=$git_location");
			}
			else{
				//echo "inserting";
				$query="INSERT INTO `git` SET `url`='".$href."', 
											  `git_location`='".$git_location."', 
											  `git_user`='".$git_name."', 
											  `git_name`='".$git_user."'";
				$insert=$db_obj->query($query);
			}
		}
		//echo $link->href;
	}
}