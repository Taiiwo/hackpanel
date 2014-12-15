HackPanel
========
HackPanel is a modular dashboard for hackathons. It aims to be a little like Wolfram Alpha, but for more social and real time things, like who's doing what, and meta information like who got through to the finals?

## How it works
HackPanel works in a really simple way. You simply type in the event you want to see live information on in the top, and the app will search for relevant plugins for your search, and puts them in little movable boxes.

## Technologies we used.
HackPanel is running on an Apache server. The backend programming was written in PHP, and the front end in JavaScript, HTML, and CSS. Information on git commits is stored on a MySQL database.

## Where next?
More plugins! Due to the expandability of our idea, all it needs is more plugins! (And maybe a better events API). Plugins are single files written in PHP and placed in the plugins folder. They are automatically loaded, so there's no need to set anything up. There's some information and a template in the plugins folder on the GitHub repo.
