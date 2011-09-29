$(document).ready(function() {
	var placesLived;
	var city
	$('.submit').click(onClickSubmit);
});

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
function successPlus(data, textStatus, lol)
{
	placesLived = data.placesLived;
	city = placesLived['0']['value'];
}
