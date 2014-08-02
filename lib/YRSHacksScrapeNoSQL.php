<?php
@require "simple_html_dom.php";
function loadYRSHacks(){
	$jsonFile = fopen('githubUrls.json','rw');
	$page_dom=file_get_html("http://hacks.youngrewiredstate.org/events/FOC2014");
	///ul/li/div[starts-with(@class,'image')/a/@href]
	$projects_links=$page_dom->find("div#projects ul li div.image a");
	$quit=0;
	foreach($projects_links as $link){
		echo "Scraping " . $link->href;
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
			$git_repo=(count($url_parts)>$i)?$url_parts[$i]:null;
			//echo $href.','.$git_location.','.$git_user.','.$git_repo;
			//insert the git info into the local git db
			$jsonArray = json_decode(fread($jsonFile));
			$exists = FALSE;
			foreach ( $jsonArray as $repo ){
				if ( array_key_exists('git_location', $repo)) {
					$repo['url'] = $href;
					$repo['git_location'] = $git_location;
					$repo['git_user'] = $git_user;
					$repo['git_repo'] = $git_repo;
					$exists = TRUE;
				}
			}
			//$result=$db_obj->query("SELECT `git_location` FROM `git` WHERE `git_location`='".$git_location."'");
			if(!$exists){
				//echo "inserting";
				$jsonArray = json_decode(fread($jsonFile));
				array_push($jsonArray, array(
						'url' => $href,
						'git_location' => $git_location,
						'git_user' => $git_user,
						'git_repo' => $git_repo
				));
				fwrite($jsonFile, json_encode($jsonArray));
				/*$query="INSERT INTO `git` SET `url`='".$href."',
											  `git_location`='".$git_location."',
											  `git_user`='".$git_repo."',
											  `git_name`='".$git_user."'";
				$insert=$db_obj->query($query);*/
			}
		}
		//echo $link->href;
	}
}
