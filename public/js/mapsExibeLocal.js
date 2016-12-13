function initialize() {
    var mapLatlng = new google.maps.LatLng(latitude, longitude);
    var mapOptions = {
        zoom: 17,
        center: mapLatlng,
        disableDefaultUI: false,
        panControl: true,
        zoomControl: true,
        scaleControl: true,
        mapTypeId: google.maps.MapTypeId.SATELLITE
    }
    var map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions);

    createMarker(map, new google.maps.LatLng(latitude, longitude), nm_local, "green");

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

    google.maps.event.addListener(marker, 'click', function() {
        infowindow.open(map, marker);
        /*if (selecionado == false) {
         $("#idLocal").val(id);
         iconFile = 'http://maps.google.com/mapfiles/ms/icons/red-dot.png';
         marker.setIcon(iconFile);
         selecionado = true;
         }*/
    });


    /* if (id == '1') {
     google.maps.event.addListener(map, 'center_changed', function() {
     window.setTimeout(function() {
     map.panTo(marker.getPosition());
     }, 3000);
     });
     }*/
}
google.maps.event.addDomListener(window, 'load', initialize);