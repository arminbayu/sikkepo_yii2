<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\HariLibur */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="data-keterangan-search" style="height: 100%">

    <?= Html::beginForm(['update-koordinat'], 'post') ?>

    <label>Lokasi</label>
    <div class="input-group">
        <?= Html::hiddenInput('unit', $id) ?>
        <?= Html::input('text', 'koordinat', '', ['class' => 'form-control', 'style' => 'width:300px', 'id' => 'coordinate', 'autocomplete' => 'off', 'readonly' => false]) ?>
        <?= Html::submitButton('Simpan', ['class' => 'btn btn-primary']) ?>
    </div>
    <div class="input-group" style="width: 100%">
        <input id="origin" type="text" class="form-control" style="width: 100%; border-bottom: 1px solid #ddd;" />
    </div>

    <div id="map"></div>
    <div id="origin-center-marker"></div>

    <div class="form-group" style= "margin-top:320px"></div>

    <?= Html::endForm() ?>

</div>

<?php
$script = <<< JS

var origin_marker = [];
var circle;
var drag;
var dragend;
var loc;

var app = {
    initialize: function() {
        app.googleMaps();
    },
    googleMaps: function() {
        var map;

        var mapOptions = {
            zoom: 16,
            disableDefaultUI: false,
        }

        map = new google.maps.Map(document.getElementById('map'), mapOptions);

        map.myLocationEnabled = true;

        app.startApplication(map);
    },
    startApplication: function(map) {
        circle = new google.maps.Circle({
            map: map,
            radius: 800,
            fillOpacity: 0,
            strokeOpacity: 0,
            strokeWeight: 0
        });
        
        // if (navigator.geolocation) {
        //     navigator.geolocation.getCurrentPosition(function(position) {
                var pos = {
                    lat: -0.9209839274500506, //position.coords.latitude,
                    lng: 134.03183937766266 //position.coords.longitude
                };

                map.setCenter(pos);
                app.addMarkerAtOrigin(map, circle);
                app.autoComplete(map, circle, pos);
            // });
        // }
    },
    autoComplete: function(map, circle, current_position) {
        var from;
        var to;
        var place;
        var origin;

        from = current_position;

        var options = {
            types: ['geocode'],
            componentRestrictions: {country: 'ID'}
        }; 

        origin = new google.maps.places.Autocomplete((document.getElementById('origin')), options);

        drag = map.addListener('drag', function() {
            $('#origin-center-marker').show();
            $("#done-button").hide();
            app.deleteOriginMarker();
        });

        dragend = map.addListener('dragend', function() {
            $('#origin-center-marker').hide();
            $("#done-button").show();
            from = app.addMarkerAtOrigin(map, circle);
            $('#coordinate').val(from.lat()+','+from.lng());
        });

        origin.addListener('place_changed', function() {
            // $('#origin-center-marker').show();

            place = origin.getPlace();

            if (!place.geometry) {
                alert("No details available for input: '" + place.name + "'");
                return;
            }

            if (place.geometry.viewport) {
                map.fitBounds(place.geometry.viewport);
            }
            else {
                map.setCenter(place.geometry.location);
            }

            app.addMarkerAtOrigin(map, circle);
            from = place.geometry.location;
            $('#coordinate').val(from.lat()+','+from.lng());

            drag.remove();
            dragend.remove();

            drag = map.addListener('drag', function() {
                $('#origin-center-marker').show();
                $("#done-button").hide();
                app.deleteOriginMarker();
            });

            dragend = map.addListener('dragend', function() {
                $('#origin-center-marker').hide();
                $("#done-button").show();
                from = app.addMarkerAtOrigin(map, circle);
                $('#coordinate').val(from.lat()+','+from.lng());
            });
        });

        var clear_origin = document.getElementById('origin');
        if (clear_origin) {
            clear_origin.addEventListener('focus', function() {
                $('#origin').val('');
                $('#origin').focus();
            });
            clear_origin.addEventListener('blur', function() {
                $('#origin').val(loc);
                $('#origin').blur();
            });
        }

        
    },
    deleteOriginMarker: function() {
        app.clearOriginMarker();
        origin_marker = [];
    },
    clearOriginMarker: function() {
        app.setMapOnOrigin(null);
    },
    setMapOnOrigin: function(map) {
        for (var i = 0; i < origin_marker.length; i++) {
            origin_marker[i].setMap(map);
        }
    },
    addMarkerAtOrigin: function(map, circle) {
        var mark;
        var geocoder;

        img = 'cordova/img/blue-marker.png';
        
        mark = new google.maps.Marker({
            position: map.getCenter(),
            map: map,
            icon: img
        });

        mark.setAnimation(google.maps.Animation.DROP);

        origin_marker.push(mark);

        var current_position = map.getCenter();
        circle.setCenter(map.getCenter());

        geocoder = new google.maps.Geocoder();
        geocoder.geocode({'latLng': map.getCenter()}, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                $('#origin').val(results[0].formatted_address);
                loc = results[0].formatted_address;
            }
        });

        return map.getCenter();
    },
};

app.initialize();

JS;
$this->registerJs($script);
?>

