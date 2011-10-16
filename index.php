<?php
require_once 'config.php';

$mysqli = new mysqli(SERVER, USER, PASSWORD, DATABASE);

$query = "SELECT plus_picture, plus_id, display_name FROM `user` ORDER BY id DESC LIMIT 0,10";

if (isset($_REQUEST['logout'])) 
  unset($_SESSION['access_token']);

$resultPicture = $mysqli->query($query);

// Retrieve number of users
$query              = "SELECT COUNT(plus_id) FROM `globe_plus`.`user`";
$resultUserCount    = $mysqli->query($query);
$rowResultUserCount = $resultUserCount->fetch_array(MYSQLI_NUM);
$nbUsers            = $rowResultUserCount[0];
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">

    <!-- Authors -->
    <meta name="author" content="Pierrick CAEN" />
    <meta name="author" content="Sylvain WEBER" />
    <meta name="author" content="Victor DELPEYROUX" />
    <!-- End authors -->

    <!-- Geo position -->
    <meta name="geo.region" content="FR-75" />
    <meta name="geo.placename" content="PARIS" />
    <meta name="geo.position" content="48.84355085737824;2.3878097534179688" />
    <meta name="ICBM" content="48.84355085737824, 2.3878097534179688" />
    <!-- End geo position -->

    <!-- Defaults meta -->
    <meta name="description" content="The Globe+ project is a delighted interface based on HTML5 technologies which allow the user to add his own position on the Globe and view all the others previously added." />
    <meta name="keywords" content="HTML5, Google+, Gmaps, OAuth, Chrome Experiment, WebGL, Globe, APIs" />
    <!-- End defaults meta -->

    <!-- Facebook Open Graph Tags -->
    <meta property="og:title" content="Globe+ | a Chrome experiment project based on WebGL Globe"/>
    <meta property="og:type" content="website"/>
    <meta property="og:image" content="http://www.gplusglobe.com/img/og-globe+.jpg" />
    <meta property="og:url" content="http://www.gplusglobe.com"/>
    <meta property="og:site_name" content="Globe+ | a Chrome experiment project based on WebGL Globe"/>
    <meta property="fb:admins" content="prcaen, sylvain.weber"/>
    <meta property="og:latitude" content="48.84355085737824"/>
    <meta property="og:longitude" content="2.3878097534179688"/>
    <meta property="og:locality" content="Paris"/>
    <meta property="og:region" content="Ile de France"/>
    <meta property="og:postal-code" content="75012"/>
    <meta property="og:country-name" content="FRANCE"/>
    <meta property="og:email" content="prcaen@gmail.com"/>
    <!-- End Facebook Open Graph Tags -->

    <title>Globe+ | a Chrome experiment project based on WebGL Globe and Google APIs</title>

    <link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />

    <link rel="canonical" href="http://www.gplusglobe.com" />

    <link rel="stylesheet" type="text/css" href="globe/globe.css" />
    <link rel="stylesheet" type="text/css" href="css/main-min.css" />

    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
    <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
    <script type="text/javascript" src="js/main-min.js"></script>

    <!-- Google Analytics -->
    <script type="text/javascript">
      var _gaq=_gaq||[];_gaq.push(['_setAccount','UA-415654-35']);_gaq.push(['_trackPageview']);(function(){var ga=document.createElement('script');ga.type='text/javascript';ga.async=true;ga.src=('https:'==document.location.protocol?'https://ssl':'http://www')+'.google-analytics.com/ga.js';var s=document.getElementsByTagName('script')[0];s.parentNode.insertBefore(ga,s)})();
    </script>
    <!-- End Google Analytics -->
  </head>
  <body>
    <div id="container"></div>
    <header>
      <h1><span class="underline">Google+</span>Globe
        <strong>
          <a href="https://accounts.google.com/o/oauth2/auth?client_id=<?php echo PLUS_CLIENT_ID ?>&amp;redirect_uri=<?php echo PLUS_REDIRECT_URI ?>&amp;scope=https://www.googleapis.com/auth/plus.me&amp;response_type=code" class="button">
          Add my Google + profile</a> on the Globe.
        </strong>
        <a href="#" class="button about">About the project</a>
      </h1>
      <div id="wall_picture">
      <strong><?php echo $nbUsers ?></strong> people added :
        <?php while($rowResultPicture = $resultPicture->fetch_array(MYSQLI_ASSOC)): ?>
          <a href="https://plus.google.com/<?php echo $rowResultPicture['plus_id'] ?>"><img src="<?php echo $rowResultPicture['plus_picture'] ?>" alt="<?php echo htmlentities($rowResultPicture['display_name'], null, 'UTF-8') ?>" title="<?php echo $rowResultPicture['display_name'] ?>" height="29" /></a>
        <?php endwhile; ?>
      </div>      
    </header>

    <?php if (isset($_GET['status'])): ?>
    <div id="warn">
      <?php if ($_GET['status'] == 'add'): ?>
      <span class="label success">Success</span> Your profile has been add on the map.
      <?php elseif($_GET['status'] == 'already_add'): ?>
      <span class="label warning">Warning</span> Your profile is already on the map.
      <?php elseif($_GET['status'] == 'error_maps'): ?>
      <span class="label warning">Warning</span> Your profile cannot be located. <a href="#" class="about">Why ?</a>
      <?php else: ?>
      <span class="label important">Error</span> An error has appear.
      <?php endif ?> 
    </div>
    <?php endif ?>

    <div id="about_box">
      <a href="#" class="about_close_box">Close</a>
      <p>
        The Globe+ project is a delighted interface based on HTML5 technologies which allow the user to add his own position on the Globe and view all the others previously added.
      </p>
      <p>
        The Globe+ project was created during the first Google hackathon in Paris by 3 Tech enthusiastic guys : <a href="http://www.pierrickcaen.fr">Pierrick CAEN</a>, <a href="http://www.sylvainweber.com/">Sylvain WEBER</a> and Victor DELPEYROUX.
      </p>
      <p>
        <strong>Why I cannot be located ?</strong>
        You cannot be located because you have not a public location on your Google+ profile. Please switch it in your profile. Change "Places I have lived" to "Visible to anyone on the web". And after that retry to add you on the map. This will be ok now ;).
      </p>
      <p>
        <a href="https://github.com/sylvainw/Globe-Plus">Hosted on GitHub</a>
      </p>
      <p>
        <img src="http://www.w3.org/html/logo/downloads/HTML5_Logo_64.png" alt="A HTML5 project" />
      </p> 
    </div>

    <footer>
      <a id="ce" href="http://www.chromeexperiments.com/globe">
        <span>This is a Chrome Experiment</span>
      </a>
      <div id="info">
        <div id="share_box" class="addthis_toolbox addthis_default_style">
          <a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
          <a class="addthis_button_tweet" tw:text="I just add my profile on the #Google+ WebGL Globe project. Add yours !"></a>
          <a class="addthis_button_google_plusone" g:plusone:size="medium"></a>
        </div>
      <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4e8c844c4fe8d5ee"></script>
        <p>
          The Globe+ project is an experiment based on <a href="http://www.chromeexperiments.com/globe">WebGL Globe</a>, <a href="https://developers.google.com/+/api/">Google+ (OAuth2)</a> and <a href="http://code.google.com/intl/en-EN/apis/maps/">Gmaps APIs</a>.
        </p>

      </div>
    </footer>
    <!-- <script type="text/javascript" src="globe/third-party/Three/ThreeWebGL.js"></script>
    <script type="text/javascript" src="globe/third-party/Three/ThreeExtras.js"></script>
    <script type="text/javascript" src="globe/third-party/Three/RequestAnimationFrame.js"></script>
    <script type="text/javascript" src="globe/third-party/Three/Detector.js"></script>
    <script type="text/javascript" src="globe/globe.js"></script> -->

    <script type="text/javascript" src="http://data-arts.appspot.com/globe/third-party/Three/ThreeWebGL.js"></script>
    <script type="text/javascript" src="http://data-arts.appspot.com/globe/third-party/Three/ThreeExtras.js"></script>
    <script type="text/javascript" src="http://data-arts.appspot.com/globe/third-party/Three/RequestAnimationFrame.js"></script>
    <script type="text/javascript" src="http://data-arts.appspot.com/globe/third-party/Three/Detector.js"></script>
    <script type="text/javascript" src="http://data-arts.appspot.com/globe/globe.js"></script>
    <script type="text/javascript">
      var globe=DAT.Globe(document.getElementById('container'));xhr=new XMLHttpRequest();xhr.open('GET','gplus.json',true);xhr.onreadystatechange=function(e){if(xhr.readyState===4){if(xhr.status===200){var data=JSON.parse(xhr.responseText);window.data=data;globe.addData(data,{format:'magnitude'});globe.createPoints();globe.animate()}}};xhr.send(null);
    </script>
  </body>
</html>

<?php $mysqli->close(); ?>
