<?php

$geo      = $_GET['geo'];
$filename = 'gplus.json';
$pattern  = '[-]?[0-9]*[.]{0,1}[0-9]{4}';

if(preg_match($pattern,$geo))
{
	$handleR = fopen($filename, 'r') or die ('can\'t open gplus json');
	$content = fread($handleR, filesize($filename));
	fclose($handleR);

	$handleW = fopen($filename, 'w') or die ('can\'t open gplus json');
	$content = str_replace(']', ', ' . $geo . ', 0.5]', $content);
	fwrite($handleW, $content);
	fclose($handleW);
}
?>