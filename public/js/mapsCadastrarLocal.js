var map;
var markers = [];
latitude = '-22.907105';
longitude = '-47.063239';
function initialize() {
    var mapLatlng = new google.maps.LatLng(latitude, longitude);//Carrega com as coordenadas da cidade de Campinas
    var mapOptions = {
        zoom: 13,
        center: mapLatlng,
        disableDefaultUI: false,
        panControl: true,
        zoomControl: true,
        scaleControl: true,
        mapTypeId: google.maps.MapTypeId.SATELLITE
    }
    map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions);
    createMarker(map, new google.maps.LatLng(latitude, longitude), "Campinas", "green");
    var input = /** @type {HTMLInputElement} */(document.getElementById('endereco'));
    var searchBox = new google.maps.places.SearchBox(input);
    /**
     * Box de pesquisa do google maps
     */
    google.maps.event.addListener(searchBox, 'places_changed', function() {
        var places = searchBox.getPlaces();

        for (var i = 0, marker; marker = markers[i]; i++) {
            marker.setMap(null);
        }

        markers = [];
        var bounds = new google.maps.LatLngBounds();
        for (var i = 0, place; place = places[i]; i++) {
            var image = {
                url: place.icon,
                size: new google.maps.Size(71, 71),
                origin: new google.maps.Point(0, 0),
                anchor: new google.maps.Point(17, 34),
                scaledSize: new google.maps.Size(25, 25)
            };

            var marker = new google.maps.Marker({
                map: map,
                icon: image,
                title: place.name,
                position: place.geometry.location
            });
            console.log(place.geometry.location.nb);
            globalLatitude = place.geometry.location.nb;
            globalLongitude = place.geometry.location.ob;            
            markers.push(marker);

            bounds.extend(place.geometry.location);
        }

        map.fitBounds(bounds);
    });

}
function createMarker(map, latlng, label, color) {
    var iconShadow = new google.maps.MarkerImage('http://maps.google.com/mapfiles/ms/micons/msmarker.shadow.png',
            new google.maps.Size(59, 32),
            new google.maps.Point(0, 0),
            new google.maps.Point(16, 32));
    var contentString = '<b>' + label + '</b>';
    var marker = new google.maps.Marker({
        shadow: iconShadow,
        position: latlng,
        map: map,
        title: label,
        zIndex: Math.round(latlng.lat() * -100000) << 5
    });

    iconFile = 'http://maps.google.com/mapfiles/ms/icons/' + color + '-dot.png';
    marker.setIcon(iconFile);

    var infowindow = new google.maps.InfoWindow({content: label});

    google.maps.event.addListener(map, 'click', function(event) {
        placeMarker(event.latLng);                        
        globalLatitude = event.latLng.lat();
        globalLongitude = event.latLng.lng();

    });
    function placeMarker(location) {
        if (globalLatitude != 'undefined') {
            clearOverlays();
        }
        var marker = new google.maps.Marker({
            position: location,
            map: map
        });
        markers.push(marker);
        map.setCenter(location);
    }
    function clearOverlays() {
        setAllMap(null);
    }
    function setAllMap(map) {
        for (var i = 0; i < markers.length; i++) {
            markers[i].setMap(map);
        }
    }
}
google.maps.event.addDomListener(window, 'load', initialize);