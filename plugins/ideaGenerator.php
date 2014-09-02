<?php
//This class MUST be called the same as the file name without ".php"
class ideaGenerator {
	//A short description of your plugin
	public $title = "Stuck for ideas?";
	//an array of file locations for scripts you want to load
	public $scripts = array();
	//This sets whether you want the plugin to be continually updated.
	public $update = false;
	public $size = array(1, 250);
	// This function is run once at plugin initialisation
	// External calls to APIs where the data should not change
	// should be run here to help reduce update time.
	function __construct() {
		//untested
	}
	//This function will be executed every time your plugin is updated.
	function update($searchTerm){
		return '
		<h3 id="suggestion"></h1><input type="button" id="button" onclick="handleButtonClick()" value="Generate Idea" />
		<script>
		function randint(max){
			return Math.floor(Math.random() * max);
		}

		var ideas = {
			"structures": [
				"mobile app",
				"mobile web app",
				"desktop app",
				"web app",
				"website",
				"command line program",
				"browser extension",
				"android widget",
				"hardware contraption"
			],
			"actions": [
				"gathers and shows information about",
				"collects and displays data on",
				"aggrigates and delivers news about",
				"helps organising events involving",
				"sorts data relating to",
				"helps out people who like",
				"gives information on",
				"tells people more about",
				"sends people news updates on",
				"tries to get people to greater enjoy",
				"eases the pain of"
			],
			"subjects": [
				"trains",
				"gardening",
				"skydiving",
				"getting jobs",
				"helping homeless people",
				"art",
				"social media",
				"going out with their friends",
				"drinking alchohol",
				"flying model planes",
				"fishing",
				"hunting",
				"riding horses",
				"arts and crafts",
				"3D printing",
				"going to the movies",
				"learning about new tech",
				"cakes",
				"birthday parties",
				"code projects",
				"holiday trips",
				"live music",
				"bikes",
				"parking cars",
				"computers",
				"websites",
				"driving cars",
				"playing musical instruments"
			]
		};

		function newSuggestion(){
			var structure = ideas["structures"][randint(ideas["structures"].length)];
			var action = ideas["actions"][randint(ideas["actions"].length)];
			var subject = ideas["subjects"][randint(ideas["subjects"].length)];
			return "How about a " + structure + " that " + action + " " + subject + "?";
		}

		function handleButtonClick(){
			document.getElementById("suggestion").innerHTML = newSuggestion();
		}
		</script>';
	}
}
?>
