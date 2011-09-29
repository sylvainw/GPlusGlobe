<?php

$geo = $_GET['data'];
$handle = fopen('gplus.json', 'a+') or die ('can\'t open gplus json');
fwrite($handle, $geo);
fclose($handle);

?>