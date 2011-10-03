$(document).ready(function()
{
  initialize();
	var placesLived;
	var city
	$('#submit').click(onClickSubmit);

  function onClickSubmit(e)
{
	e.preventDefault();
	$.ajax({
	  type: 'GET',
	  url: 'https://www.googleapis.com/plus/v1/people/' + $('.userIdPlus').val(),
	  data: {'fields' : 'placesLived', 'key' : 'AIzaSyC2b1DFxfc0lNrpmS7fazYKJV0E77ojvpQ'},
	  success: successPlus,
	  dataType: 'jsonp'
	});
}
function successPlus(data, textStatus)
{
	placesLived = data.placesLived;
	city = placesLived['0']['value'];
	
	codeAddress(city);
}

var geocoder;
var map;

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
    url: '/writeJson.php',
    data: {'geo' : geo.Ja + ', ' + geo.Ka},
    success: successWriteJson
  });
}

function successWriteJson()
{
  window.location.reload();
}
});
