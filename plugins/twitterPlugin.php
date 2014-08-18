<?php
//This class MUST be called the same as the file name without ".php"
class twitterPlugin {
	//A short description of your plugin
	public $title = "Twitter Plugin";
	public $scripts;
	//This sets whether you want the plugin to be continually updated.
	public $update = true;
	public $size = array(1, 300);
	public $categories=array("twitterAccount","twitterHashtags");
	// This function is run once at plugin initialisation
	// External calls to APIs where the data should not change
	// should be run here to help reduce update time.
	function __construct() {
		//untested
	}
	//This function will be executed every time your plugin is updated.
	function update($searchTerm){
		require_once('../../lib/twitterApi.php');
		require_once('../../lib/pluginUtils.php');
		date_default_timezone_set('Europe/London');
		$aliases=getSearchAlias($searchTerm, array('twitterHashtags'));
		$settings = array(
			'oauth_access_token' => "227461689-4iy4cpxkgArKyMgex2cERrrt5URaT8957iJcHGUO",
			'oauth_access_token_secret' => "OgV7YuzTfFAF5FxFkeB0ZGvnJxY7CVUvM35fqDbWmatCt",
			'consumer_key' => "W6vC0juzsnu8A3I93Hu8PLIbd",// not sure if these are right
			'consumer_secret' => "MDE3HEG9aHK4j3mguMD3SIyGWJBvvwlDwCvPRSqA5z3MWPWhra"//^
		);
		$url = 'https://api.twitter.com/1.1/search/tweets.json';
		$urlHashtags = array();
		foreach ($aliases['twitterHashtags'] as $tag){
			array_push($urlHashtags, urlencode($tag));
		}
		$getfield = '?q=' . implode(',',$urlHashtags);
		$requestMethod = 'GET';
		$twitter = new TwitterAPIExchange($settings);
		$tweets = json_decode($twitter->setGetfield($getfield)
			->buildOauth($url, $requestMethod)
			->performRequest());
		$retMe = array();
		foreach ($tweets->statuses as $tweet){
			$item = array();
			$item[] = $tweet->user->name;// title
			$item[] = "https://twitter.com/@" . $tweet->user->screen_name;// link location
			$item[] = $tweet->user->screen_name;// link title
			$item[] = htmlifyLinks($tweet->text);// tweet
			$item[] = strtotime($tweet->created_at);// timestamp
			$retMe[] = $item;
		}
		return styledList($retMe);
	}
}
?>
