function map1() {
    var myLatlng = new google.maps.LatLng(-6.2080184, 106.8282548);

    var mapOptions = {
        zoom: 17,
        center: myLatlng,
    }

    var map = new google.maps.Map(document.getElementById('map1'), mapOptions);

    var marker = new google.maps.Marker({
        position: myLatlng,
        map: map,
    });

    var infowindow = new google.maps.InfoWindow({
        content: '<a href="https://www.google.co.id/maps/place/MD+Corp/@-6.2080184,106.8282548,15z/data=!4m2!3m1!1s0x0:0xf1e67f41145bf7f3?sa=X&ved=0ahUKEwimwvaZivvTAhXMtY8KHZIwCiIQ_BIIlgEwCg" target="new"><B>MD Entertainment</B></a>'
    });

    infowindow.open(map,marker);
}

function map2() {
    var myLatlng = new google.maps.LatLng(-6.189831, 106.798742);

    var mapOptions = {
        zoom: 17,
        center: myLatlng,
    }

    var map = new google.maps.Map(document.getElementById('map2'), mapOptions);

    var marker = new google.maps.Marker({
        position: myLatlng,
        map: map,
    });

    var infowindow = new google.maps.InfoWindow({
        content: '<a href="https://www.google.co.id/maps/place/@-6.1898283,106.7976477,18z/data=!3m1!4b1!4m5!3m4!1s0x0:0x0!8m2!3d-6.189831!4d106.798742" target="new"><B>Tokopedia</B></a>'
    });

    infowindow.open(map,marker);
}

