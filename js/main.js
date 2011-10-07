$(document).ready(function()
{
  var geocoder;
  var map;
  
  initialize();
  $('#user_add_profil').click(onClickUserAddProfil);
  $('.about').click(onClickAbout);
  $('.about_close_box').click(onClickAbout);

  function onClickSubmit(e)
  {
    e.preventDefault();
    $.ajax({
      type: 'POST',
      url: 'https://accounts.google.com/o/oauth2/auth',
      data: {'client_id' : '699574009061.apps.googleusercontent.com', 
             'key'       : 'AIzaSyC2b1DFxfc0lNrpmS7fazYKJV0E77ojvpQ',
             'scope'     : 'https://www.googleapis.com/auth/plus.me',
             ''},
      success: successPlus,
      dataType: 'jsonp'
    });
  }
  function onClickSubmit(e)
  {
    e.preventDefault();
    $.ajax({
      type: 'GET',
      url: 'https://www.googleapis.com/plus/v1/people/' + $('#userIdPlus').val(),
      data: {'fields' : 'placesLived', 'key' : 'AIzaSyC2b1DFxfc0lNrpmS7fazYKJV0E77ojvpQ'},
      success: successPlus,
      dataType: 'jsonp'
    });
  }

  function onClickAbout(e)
  {
    e.preventDefault();
    $('#about_box').fadeToggle();
  }

  function successPlus(data, textStatus)
  {
    codeAddress(data.placesLived['0']['value']);
  }

  function initialize() {
   geocoder = new google.maps.Geocoder();
   var latlng = new google.maps.LatLng(0, 0);
  }

  function codeAddress(city) {
   geocoder.geocode( { 'address': city}, function(results, status) {
     if (status == google.maps.GeocoderStatus.OK) {
       writeJson(results[0].geometry.location);
     } else {
       alert("Geocode was not successful for the following reason: " + status);
     }
   });
  }

  function writeJson(geo) {

    $.ajax({
      type: 'GET',
      url: 'http://localhost/Globe-Plus/writeJson.php',
      data: {'geo' : geo.Ja + ', ' + geo.Ka},
      success: successWriteJson
    });
  }

  function successWriteJson()
  {
    window.location.reload();
  }
});
