<?php
//This class MUST be called the same as the file name without ".php"
class twilioSMSPlugin {
	//A short description of your plugin
	public $title = "SMS Plugin";
	//an array of file locations for scripts you want to load
	public $scripts = array();
	public $size = array(1, 250);
	//This sets whether you want the plugin to be continually updated.
	public $update = false;
	// This function is run once at plugin initialisation
	// External calls to APIs where the data should not change
	// should be run here to help reduce update time.
	function __construct() {
		//untested
	}
	//This function will be executed every time your plugin is updated.
	function update($searchTerm){
		require_once('../../lib/twilio-php/Services/Twilio.php');
		require_once('../../lib/searchTerm.php');

	 	return "
		<script type='text/javascript'>
			$('#messageForm').submit(function () {
				return false;
			});
		</script>
		<div id='twilioPlugin'>
			<h1>Send the organiser a text message</h1>
			<form method='POST' id='messageForm' name='messageForm' action=''>
				<input id='theirName' name='theirName' type='text' autocomplete='off' placeholder='Your Name'>
				<input id='theirPhoneNumber' name='theirPhoneNumber' autocomplete='off' type='text' placeholder='Your Phone Number'>
				<textarea id='theirMessage' name='theirMessage' autocomplete='off' placeholder='Your Message'></textarea>
				<input id='textSubmit' name='textSubmit' type='submit'>
			</form>
			<a class='poweredTwilio' href='http://www.twilio.com/'><img src='https://www.twilio.com/packages/company/img/logos_downloadable_powerdby_small.png'></a>
		</div>";

		$AccountSid = "AC1e28b08f691757ac021e8603aa9b38a5";
		$AuthToken = "f1c5ba5814760292b5ed0d8f7e3f4bcb";
		$client = new Services_Twilio($AccountSid, $AuthToken);

		$phoneNumber = getSearchAlias($searchTerm, array('phoneNumber'));
		$theirNumber = $_POST['theirPhoneNumber'];
		$theirMessage = $_POST['theirMessage'];

		$sms = $client->account->messages->sendMessage (
			"+441158243172",
			$phoneNumber,
			"New message from $theirName ($theirNumber), $theirMessage"
		);
	}
}
?>
