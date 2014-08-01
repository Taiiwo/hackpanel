<?php
//This class MUST be called the same as the file name without ".php"
class twitterPlugin {
	//A short description of your plugin
	public $title = "Twitter Plugin";
	public $scripts;
	//This sets whether you want the plugin to be continually updated.
	public $update = true;
	public $categories=array("twitterAccount","twitterHashtags");
	// This function is run once at plugin initialisation
	// External calls to APIs where the data should not change
	// should be run here to help reduce update time.
	function __construct() {
		//untested
	}
	//This function will be executed every time your plugin is updated.
	function update($searchTerm){
		$aliases=getSearchAlias($searchTerm,$this->categories);
		return '
<div class="twitter-timeline" href="https://twitter.com/hashtag/YRSFoC" data-widget-id="494093261502312451"></div>
<script>
	! function(d, s, id) {
		var js, fjs = d.getElementsByTagName(s)[0],
			p = /^http:/.test(d.location) ? \'http\':\'https\';
    		if (!d.getElementById(id)) {
			js = d.createElement(s);
			js.id = id;
			js.src = p + "://platform.twitter.com/widgets.js";
			fjs.parentNode.insertBefore(js, fjs);
		}
	}(document,"script","twitter-wjs");
	hashtags='.json_encode($aliases['twitterHashtags']).';
	account='.json_encode($aliases['twitterAccount']).';
	twttr.widgets.createTimeline(
		"494093261502312451",
		document.getElementsByClassName("twitter-timeline")[0],
		{
			hashtags:hashtags
		}
	);
</script>
';
	}
}
?>
