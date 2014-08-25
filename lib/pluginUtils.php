<?php
/*
Formats you a pretty list. array indexes:
0: header
1: link
2: link text
3: body
4: timestamp
*/
function styledList($items){
	$retMe = '';
	$retMe .= '<ul class="styledList">';
	foreach ($items as $item){
		$retMe.="<li>
				<div class='commitHeader'>
					<h3>". $item[0] ."</h3>
					<a href='".$item[1]."'>".$item[2]."</a>
				</div>
				<div class='commitFooter'>
					<span class='commitMessage'>".$item[3]."</span><br >
					<span class='commitTime'>". date('d-m-Y H:i:s', $item[4]) ."</span>
				</div>
			</li>";
	}
	$retMe .= '</ul>';
	return $retMe;
}
function htmlifyLinks($s) {
  return preg_replace('(http[s]?://(?:[a-zA-Z]|[0-9]|[$-_@.&+]|[!*\(\),]|(?:%[0-9a-fA-F][0-9a-fA-F]))+)', '<a href="$0">$0</a>', $s);
}
?>
