<?php
require_once 'config.php';

$mysqli = new mysqli(SERVER, USER, PASSWORD, DATABASE);

// Doublons
$query  = "SELECT id, COUNT(id), name, `latitude`, `longitude` FROM `city` GROUP BY `latitude`, `longitude` HAVING count(*)>1";
$result = $mysqli->query($query);

while($row1 = $result->fetch_array(MYSQLI_ASSOC))
{
  $latitude  = $row1['latitude'];
  $longitude = $row1['longitude'];
  
  // One doublon
  $query = "SELECT id FROM `city` where `latitude` = $latitude and `longitude` = $longitude";
  $result2 = $mysqli->query($query);

  $save = $result2->fetch_array(MYSQLI_ASSOC);
  $save = $save['id'];

  while($row2 = $result2->fetch_array(MYSQLI_ASSOC))
  {
    $city_id = $row2['id'];

    $query   = "SELECT id FROM `user` where city_id=$city_id";
    $result3 = $mysqli->query($query);
    while($row3 = $result3->fetch_array(MYSQLI_ASSOC))
    {
      $user_id = $row3['id'];
      // change city id of user with $save
      $query = "UPDATE  `globe_plus`.`user` SET  `city_id` = $save WHERE `user`.`id` = $user_id";
      $mysqli->query($query);
    }

    // delete city
    $query ="DELETE FROM `globe_plus`.`city` WHERE `city`.`id`= $city_id";
    $mysqli->query($query);
  }
}

$mysqli->close();
?>