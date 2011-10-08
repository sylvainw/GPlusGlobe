<?php
define('URL', 'http://' . $_SERVER['HTTP_HOST']);

// DATABASE
define('SERVER', 'localhost');
define('USER', 'root');
define('PASSWORD', 'root');
define('DATABASE', 'gplusglobe');

// Google + API
define('PLUS_CLIENT_ID', 'YOUR_GOOGLE+_API_CLIENT_ID');
define('PLUS_CLIENT_SECRET', 'YOUR_GOOGLE+_API_CLIENT_SECRET');
define('PLUS_REDIRECT_URI', URL . '/oauthcallback.php');
define('PLUS_DEVELOPPER_KEY', 'YOUR_GOOGLE+_API_DEVELOPPER_KEY');

// Google Maps
define('MAP_KEY', 'YOUR_GMAP_API_KEY');

// JSON filename
define('JSON_FILENAME', 'gplusglobe_sample.json');
?>