<?php //This class MUST be called the same as the file name without ".php"
class githubCommits {
	//A short description of your plugin
	public $title = "Live Git Commits";
	public $scripts;
        //This sets whether you want the plugin to be continually updated.
        public $update = false;
	public $size = array(1, 350);
	// This function is run once at plugin initialisation
	// External calls to APIs where the data should not change
	// should be run here to help reduce update time.
	function __construct() {

	}
	//This function will be executed every time your plugin is updated.
	function update($searchTerm){
		date_default_timezone_set("Europe/Lisbon");
		$commits=json_decode(file_get_contents("../../lib/githubCommits.json"));
		$return="<ul>";
		foreach($commits as $commit){
			$url_parts=explode('/',$commit->url);
			for($i=0;$i<count($url_parts);$i++){
				if(preg_match("/github.com/i",$url_parts[$i])){
					break;
				}
			}
			$i++;$i++;
			$git_user=(count($url_parts)>$i)?$url_parts[$i]:null;
			$i++;
			$git_repo=(count($url_parts)>$i)?$url_parts[$i]:null;
			$commitTime=new DateTime($commit->commit->author->date);
			$return.="<li>
						<div class='commitHeader'>
							<h3>".$commit->commit->author->name."</h3>
							<a href='".$commit->html_url."'>".$git_user."/".$git_repo."</a>
						</div>
						<div class='commitFooter'>
							<span class='commitMessage'>".$commit->commit->message."</span><br >
							<span class='commitTime'>".$commitTime->format("d-m-Y H:i:s")."</span>
						</div>
					</li>";
		}
		$return.="</ul>";
		return $return;
	}
}
?>
