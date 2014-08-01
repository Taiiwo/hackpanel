<?php
set_time_limit(240);
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

//this function compares two commits to see which one is more recent
function commitCompare($num1,$num2){
	if(DateTime($num1->commit->author->date)>DateTime($num2->commit->author->date)){
		return 1;
	}
	elseif(DateTime($num1->commit->author->date)==DateTime($num2->commit->author->date)){
		return 0;
	}
	else{
		return -1;
	}
}

function getRepoCommits($user,$repo){
	global $gitOAuth;
	$apiUrl="https://api.github.com/repos/".$user."/".$repo."/commits";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $apiUrl);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_USERAGENT, "The Hack Dash App");
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_USERPWD, "$gitOAuth:x-oauth-basic");
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	$content = curl_exec($ch);
	curl_close($ch);
	return $content;
}

function loadGithubCommits(){
	global $db,$gitOAuth;
	$db_obj = new mysqli($db["host"],$db["user"],$db["pass"],$db["db"]);
	$projects = $db_obj->query("SELECT `git_user`,`git_name` FROM `git` WHERE `git_user`!='' AND `git_name`!=''");
	$allCommits=array();
	if($projects!==FALSE & $projects->num_rows>0){
		while ($project = $projects->fetch_object()){
			$content=gitRepoCommits($project->git_user,$project->git_name);
			$commits = json_decode($content);
			for($i=0;$i<count($commits),$i<30;$i++){
				$allCommits[]=$commits[$i];
			}
			echo $apiUrl."</br>";
		}
		usort($allCommits,commitCompare);
	}
	?><ul><?php
	foreach($allCommits as $result){
		print_r($result);
		$url_parts=explode('/',$result->commit->url);
		for($i=0;$i<count($url_parts);$i++){
			if(preg_match("/github.com/i",$url_parts[$i])){
				break;
			}
		}
		$i++;$i++;
		$git_user=(count($url_parts)>$i)?$url_parts[$i]:null;
		$i++;
		$git_name=(count($url_parts)>$i)?$url_parts[$i]:null;
		echo "<li>".$result->commit->author->name." COMMITTED ".$result->commit->message." ON ".$git_user."/".$git_name."</li>";
	}
	?></ul><?php
}