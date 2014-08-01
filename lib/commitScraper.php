<?php
require_once "../lib/YRSHacksScrape.php";
print_r(json_decode(loadGithubCommits()));
?>