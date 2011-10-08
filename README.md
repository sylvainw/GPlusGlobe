## About the Project ##

URL : [http://gplusglobe.com](http://gplusglobe.com)

The Globe+ project is a delighted interface based on HTML5 technologies which allow visitors to add their own position on the Globe and view all the others previously added.

The Globe+ project was initiated during the first Google hackathon in Paris in september 2011, by 3 Tech enthusiastic guys : [Pierrick CAEN](http://pierrickcaen.fr), [Sylvain WEBER](http://sylvainweber.com) and Victor DELPEYROUX.

It's a Chrome experiment based on WebGL Globe, Google+ (OAuth2) and Gmaps APIs.
And it's open source, so feel free to fork it on Github or anything else ;)

## About WebGL Globe ##

The **WebGL Globe** is an open platform for visualizing geographic
information in WebGL enabled browsers.
It supports data in JSON format, a sample of which you can find [here]
(https://github.com/dataarts/dat.globe/raw/master/globe/population909500.json). dat.globe makes heavy use of the [Three.js](https://github.com/mrdoob/three.js/)
library, and is still in early open development.


### Data Format ###

The following illustrates the JSON data format that the globe expects:

```javascript
var data = [
  [
    'seriesA', [ latitude, longitude, magnitude, latitude, longitude, magnitude, ... ]
  ],
  [
    'seriesB', [ latitude, longitude, magnitude, latitude, longitude, magnitude, ... ]
  ]
];
```

### Basic Usage ###

The following code polls a JSON file (formatted like the one above)
for geo-data and adds it to an animated, interactive WebGL globe.

```javascript
// Where to put the globe?
var container = document.getElementById( 'container' );

// Make the globe
var globe = new DAT.Globe( container );

// We're going to ask a file for the JSON data.
xhr = new XMLHttpRequest();

// Where do we get the data?
xhr.open( 'GET', 'myjson.json', true );

// What do we do when we have it?
xhr.onreadystatechange = function() {

  // If we've received the data
  if ( xhr.readyState === 4 && xhr.status === 200 ) {

      // Parse the JSON
      var data = JSON.parse( xhr.responseText );

      // Tell the globe about your JSON data
      for ( i = 0; i < data.length; i++ ) {
        globe.addData( data[i][1], 'magnitude', data[i][0] );
      }

      // Create the geometry
      globe.createPoints();

      // Begin animation
      globe.animate();

    }

  }

};

// Begin request
xhr.send( null );
```