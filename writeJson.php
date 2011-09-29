<?php

$geo = $_GET['geo'];
$filename = 'gplus.json';

$handleR = fopen($filename, 'r') or die ('can\'t open gplus json');
$content = fread($handleR, filesize($filename));
fclose($handleR);

$handleW = fopen($filename, 'w') or die ('can\'t open gplus json');
$content = str_replace(']', ', ' . $geo . ', 0.5]', $content);
fwrite($handleW, $content);
fclose($handleW);

?>