<!DOCTYPE html>
<html>
<head>
  <title>Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/>
  <link rel="stylesheet" href="css/main.css"/>
  <link href='http://fonts.googleapis.com/css?family=Titan+One' rel='stylesheet' type='text/css'>
  <link rel="icon" type="image/png" href="img/favicon.png">
  <link href='http://fonts.googleapis.com/css?family=Roboto:400,700' rel='stylesheet' type='text/css'>
  <script src="js/jQuery.js" type="text/javascript"></script>
  <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
  <script src="js/shapeshift.js" type="text/javascript"></script>
  <script src="js/main.js" type="text/javascript"></script>
  <script src="js/lightboxes.js" type="text/javascript"></script>
  <script src="js/jQuery.cookie.js"></script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDmP4F7uI_pUSW9duPY6xQjoRb0iJgcuic"></script>
  <script src="https://www.google.com/jsapi" type="text/javascript"></script>
  <script src="http://platform.twitter.com/widgets.js" type="text/javascript"></script>
  <script>google.load("visualization", "1", {packages:["corechart"]});</script>

  <script>
    //alpha/beta bar script
  $(function(){
     var hash = <?php
                  //read first line of ORIG_HEAD file
                  $refLocationFile=fopen("../.git/HEAD","r");
                  $refLocation=array();
                  preg_match("/(ref\:\ )(.*)/",fgets($refLocationFile),$refLocation);
                  fclose($refLocationFile);
                  $gitLine=fopen("../.git/".$refLocation[2],"r");
                  echo "'".trim(fgets($gitLine))."'";
                  fclose($gitLine);
                 ?>;
     $('.version').text(hash);
     $('.version').attr("target","_blank");
     $('.version').attr("href","https://github.com/Taiiwo/hackpanel/commit/" + hash);
  });
  </script>
</head>
  <body>
    <div class="lightbox" id="settings">
      <div id="settingsClose">x</div>
      <div class="content">
      </div>
    </div>
    <div class="lightbox" id="add">
      <div id="addClose">x</div>
      <div class="content">
        <h1 class="addHeading">Add an event.</h1>
        <form method="POST" action"">
          <label id="nameLabel" for="eventName">Event Name</label><p class="nameRemaining">30 characters remaining</p><input id="eventName" type="text" maxlength="30" placeholder="event name"/>
          <label id="urlLabel" for="URLcontainer">Event URL</label><div class="URLcontainer"><span>http://hackpa.nl/#</span><input id="eventURL" type="url" placeholder="my-event"/></div>
          <label id="descriptionLabel" for="eventDescription">Event Description</label><p class="descRemaining">350 characters remaining</p><textarea id="eventDescription" placeholder="event description" maxlength="350"></textarea>
          <div class="dates">
            <div class="startDate"><label for="eventDate">Starts</label><input id="eventDate" type="date"/><input id="eventTime" type="time"/></div>
            <div class="endDate"><label for="eventDate">Ends</label><input id="eventDate" type="date"/><input id="eventTime" type="time"/></div>
          </div>
          <label id="imageLabel" for="eventImage">Event Image</label><input id="imageInput" type="file"/><button id="imageButton">Select an image</button><p class="imagePath"></p>
          <div id="colorPicker"><label for="color1">Page color</label><input id="color1" value="#e00032" type="color"/></div>
          <div id="colorPicker"><label for="color2">Accent color</label><input id="color2" value="#03a9f4" type="color"/></div>
          <input id="eventSubmit" type="submit"/>
        </form>
      </div>
    </div>

    <div id="top-bar">
      <h1 class="logo">HackPanel</h1>
      <div id="header-elements">
        <form id="searchBox" action="" method="GET">
          <input id="search" placeholder="search for an event" type="search" name="search" autocomplete="off">
          <div class="items-container">
            <div class="search-items">
              <!-- EVENTS ADDED HERE -->
            </div>
          </div>
        </form>
        <div id="buttons">
          <div id="settings-button"><img src="img/cog.png"></div>
          <div id="add-button"><img src="img/add.png"></div>
        </div>
      </div>
    </div>
    <div id="main-content">
				<div class="container">
			            <!-- PLUGINS ADDED HERE -->
				</div>
    </div>
    <div id="alphaBar">
      <p>This site is still in alpha stages. Current version: </p><a class="version"></a>
    </div>
</body>
</html>
