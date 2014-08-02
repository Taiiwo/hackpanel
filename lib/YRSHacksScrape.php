<?php
set_time_limit(240);
@require "simple_html_dom.php";
@require "db.php";
function loadYRSHacks(){
	$page_dom=file_get_html("http://hacks.youngrewiredstate.org/events/FOC2014");
	///ul/li/div[starts-with(@class,'image')/a/@href]
	$projects_links=$page_dom->find("div#projects ul li div.image a");
	$quit=0;
	$allUrls=array();
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
			$git_repo=(count($url_parts)>$i)?$url_parts[$i]:null;
			//echo $href.','.$git_location.','.$git_user.','.$git_name;
			//insert the git info into the local git db
			foreach($allUrls as $url){
				if($url["git_location"]==$git_location)continue;
			}
			$allUrls[]=array(
				"href"=>$href,
				"git_location"=>$git_location,
				"git_user"=>$git_user,
				"git_repo"=>$git_repo
			);
		}
		//echo $link->href;
	}
	$jsonFile=fopen('githubUrls.json', 'w');
	fwrite($jsonFile,json_encode($allUrls));
	fclose($jsonFile);
}

//this function compares two commits to see which one is more recent
function commitCompare($num1,$num2){
	$date1=new DateTime($num1->commit->author->date);
	$date2=new DateTime($num2->commit->author->date);
	if($date1<$date2){
		//echo 'bigger';
		return 1;
	}
	elseif($date1==$date2){
		//echo 'same';
		return 0;
	}
	else{
		//echo 'smaller';
		return -1;
	}
}

function getRepoCommits($user,$repo){
	if($user==''||$repo=='')return array();
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
	global $gitOAuth;
	$projects=json_decode(file_get_contents("githubUrls.json"),true);
	$allCommits=array();
	foreach ($projects as $project){
		if(count($project)<4)continue;
		$content = getRepoCommits($project["git_user"],$project["git_repo"]);
		$commits = json_decode($content);
		if(is_array($commits)){
			for($i=0;$i<count($commits)&$i<10;$i++){
				$allCommits[]=$commits[$i];
			}
		}
		print $project["git_user"]."/".$project["git_repo"];
	}
	usort($allCommits,"commitCompare");
	$jsonFile=fopen('githubCommits.json', 'w');
	$topCommits=array_chunk($allCommits,100);
	fwrite($jsonFile,json_encode($topCommits[0]));
	fclose($jsonFile);
}