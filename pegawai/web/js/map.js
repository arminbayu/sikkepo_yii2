function map() {
    var myLatlng = new google.maps.LatLng(-6.19636839, 106.84902549);

    var mapOptions = {
        zoom: 17,
        center: myLatlng,
    }

    var map = new google.maps.Map(document.getElementById('map'), mapOptions);

    var marker = new google.maps.Marker({
        position: myLatlng,
        map: map,
    });

    var infowindow = new google.maps.InfoWindow({
        content: "<B>SEMNAS IKRA-ITH</B>"
    });

    infowindow.open(map,marker);

    var options = {
        types: ['geocode'],
        componentRestrictions: {country: 'ID'}
    }; 

    var origin = new google.maps.places.Autocomplete((document.getElementById('origin')), options);

    origin.addListener('place_changed', function() {
        //$('#address').hide();
        var geocoder = new google.maps.Geocoder();
        geocoder.geocode({'latLng': myLatlng}, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                $('#address').text('results[0].formatted_address');
            }
        });
    });

}