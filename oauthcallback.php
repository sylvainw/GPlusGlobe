<?php
require_once 'config.php';
require_once 'src/apiClient.php';
require_once 'src/contrib/apiPlusService.php';
require_once 'src/gMaps.php';

$mysqli = new mysqli(SERVER, USER, PASSWORD, DATABASE);
$gmap   = new gMaps(MAP_KEY);
$client = new apiClient();
$plus   = new apiPlusService($client);

$mysqli->query('SET NAMES utf8');

session_start();

$client->setApplicationName('Globe +');
$client->setClientId(PLUS_CLIENT_ID);
$client->setClientSecret(PLUS_CLIENT_SECRET);
$client->setRedirectUri(PLUS_REDIRECT_URI);
$client->setDeveloperKey(PLUS_DEVELOPPER_KEY);

if (isset($_GET['code'])) 
{
  $client->authenticate();
  $_SESSION['access_token'] = $client->getAccessToken();
  header('Location: ' . URL . $_SERVER['PHP_SELF']);
}

if(isset($_GET['error']))
{
  header('Location: ' . URL . '?status=error');
  die();
}

if (isset($_SESSION['access_token'])) 
{
  $client->setAccessToken($_SESSION['access_token']);
}

if ($client->getAccessToken()) 
{
  $me = $plus->people->get('me');

  // These fields are currently filtered through the PHP sanitize filters.
  // See http://www.php.net/manual/en/filter.filters.sanitize.php
  $img         = filter_var($me['image']['url'], FILTER_VALIDATE_URL);
  $displayName = filter_var($me['displayName'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

  if(!array_key_exists('placesLived', $me))
  {
    header('Location: ' . URL . '?status=error_maps');
    die();
  }
  
  $city        = $me['placesLived'][0]['value'];
  $id          = $me['id'];

  if($gmap->getInfoLocation($city))
  {
    $latitude  = round($gmap->getLatitude(), 6);
    $longitude = round($gmap->getLongitude(), 6);
  }
  else
  {
    header('Location: ' . URL . '?status=error_maps');
    die();
  }

  if($latitude == 0 && $longitude == 0)
  {
    header('Location: ' . URL . '?status=error_maps');
    die();
  }

  // Retrieve city in database
  $query = "SELECT id FROM `city` WHERE latitude=$latitude AND longitude=$longitude";

  $resultCity    = $mysqli->query($query);
  $rowResultCity = $resultCity->fetch_array(MYSQLI_ASSOC);

  if(empty($rowResultCity['id']))
  {      
    $query = "INSERT INTO `globe_plus`.`city` (`id`, `name`, `latitude`, `longitude`) VALUES (NULL, '$city', '$latitude', '$longitude')";
    $mysqli->query($query);

    $city_id = $mysqli->insert_id; 
  }
  else
    $city_id = $rowResultCity['id'];
  
  $query = "SELECT id FROM `user` WHERE plus_id='$id'";

  $resultUser    = $mysqli->query($query);
  $rowResultUser = $resultUser->fetch_array(MYSQLI_ASSOC);

  if(empty($rowResultUser['id']))
  {
    // Insert user in DB
    $query = "INSERT INTO `globe_plus`.`user` (`id`, `plus_id`, `display_name`, `plus_picture`, `city_id`) 
              VALUES (NULL, $id, '$displayName', '$img', $city_id)";
  }

  $mysqli->query($query);

  // Write Json
  if(empty($rowResultUser['id']))
  {
      // Retrieve number of users
      $query              = "SELECT COUNT(plus_id) FROM `globe_plus`.`user`";
      $resultUserCount    = $mysqli->query($query);
      $rowResultUserCount = $resultUserCount->fetch_array(MYSQLI_NUM);
      $nbUsers            = $rowResultUserCount[0];

      // Retrieve all coordinates
      $query         = "SELECT id, latitude, longitude FROM `globe_plus`.`city`";
      $resultCity    = $mysqli->query($query);

      $handleW = fopen(JSON_FILENAME, 'w') or die ('can\'t open gplus json');
      
      $content = '[';
      while($rowResultCity = $resultCity->fetch_array(MYSQLI_ASSOC))
      {
        $city_id = $rowResultCity['id'];

        // Retrieve number of user who lived in this city
        $query              = "SELECT COUNT(city_id) FROM `globe_plus`.`user` WHERE city_id=$city_id";
        $resultUserLived    = $mysqli->query($query);
        $rowResultUserLived = $resultUserLived->fetch_array(MYSQLI_NUM);
        $nbUsersLived       = $rowResultUserLived[0];
        
        $content .= $rowResultCity['latitude'] . ', ';
        $content .= $rowResultCity['longitude'] . ', ';
        $content .= $nbUsersLived / $nbUsers * 18 . ', ';
      }

      $content .= ']';

      $content = str_replace(', ]', ']', $content);
      
      fwrite($handleW, $content);
      fclose($handleW);

      $status = 'add';
  }
  else
    $status = 'already_add';

  // The access token may have been updated lazily.
  $_SESSION['access_token'] = $client->getAccessToken();

  header('Location: ' . URL . '?status=' . $status);
}
else
  header('Location: ' . URL . '?status=error');

$mysqli->close();
?>
