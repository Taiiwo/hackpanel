<?php
require_once "YRSHacksScrape.php";
print_r(json_decode(loadGithubCommits()));
?>