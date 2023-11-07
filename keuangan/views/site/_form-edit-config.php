<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use common\models\DataPegawai;

/* @var $this yii\web\View */
/* @var $model common\models\HariLibur */
/* @var $form yii\widgets\ActiveForm */
?>

<?php
$status = array(
    0 => 'Non Aktif',
    1 => 'Aktif',
);

$script = <<< JS

$(".select2").select2();

$('#awal-m').timepicker({
    showMeridian: false,
    defaultTime: '00:00'
});
$('#akhir-m').timepicker({
    showMeridian: false,
    defaultTime: '00:00'
});

$('#awal-s').timepicker({
    showMeridian: false,
    defaultTime: '00:00'
});
$('#akhir-s').timepicker({
    showMeridian: false,
    defaultTime: '00:00'
});

$('#awal-p').timepicker({
    showMeridian: false,
    defaultTime: '00:00'
});
$('#akhir-p').timepicker({
    showMeridian: false,
    defaultTime: '00:00'
});

JS;
$this->registerJs($script);
?>

<div class="config-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'radius')->textInput() ?>

    <?= $form->field($model, 'location_status')->dropDownList($status, ['class'=>'form-control select2']) ?>

    <?= $form->field($model, 'awal_m', ['inputOptions'=>['class'=>'form-control', 'id'=>'awal-m']])->textInput() ?>
    <?= $form->field($model, 'akhir_m', ['inputOptions'=>['class'=>'form-control', 'id'=>'akhir-m']])->textInput() ?>

    <?= $form->field($model, 'awal_s', ['inputOptions'=>['class'=>'form-control', 'id'=>'awal-s']])->textInput() ?>
    <?= $form->field($model, 'akhir_s', ['inputOptions'=>['class'=>'form-control', 'id'=>'akhir-s']])->textInput() ?>

    <?= $form->field($model, 'awal_p', ['inputOptions'=>['class'=>'form-control', 'id'=>'awal-p']])->textInput() ?>
    <?= $form->field($model, 'akhir_p', ['inputOptions'=>['class'=>'form-control', 'id'=>'akhir-p']])->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('<i class="fa fa-save"></i> Simpan', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

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

        // google.maps.event.addDomListener(document.getElementById('current-position-button'), 'click', function() {
        //     map.setCenter(current_position);
        //     circle.setCenter(current_position);

        //     app.deleteOriginMarker();
        //     from = app.addMarkerAtOrigin(map, circle);
        //     $('#coordinate').val(from.lat()+','+from.lng());

        //     drag.remove();
        //     dragend.remove();

        //     drag = map.addListener('drag', function() {
        //         $('#origin-center-marker').show();
        //         $("#done-button").hide();
        //         app.deleteOriginMarker();
        //     });

        //     dragend = map.addListener('dragend', function() {
        //         $('#origin-center-marker').hide();
        //         $("#done-button").show();
        //         from = app.addMarkerAtOrigin(map, circle);
        //         $('#coordinate').val(from.lat()+','+from.lng());
        //     });
        // });

        // google.maps.event.addDomListener(document.getElementById('done-button'), 'click', function() {
        //     $("#current-position-button").remove();
        //     $('#origin').attr('readonly', 'readonly');

        //     $("#done-button").hide();
        //     $('#coordinate').val(from.lat()+','+from.lng());
        // });

        // google.maps.event.addDomListener(document.getElementById('clear-origin'), 'click', function() {
        //     $('#origin').val('');
        //     $('#origin').focus();

        //     $('#origin-center-marker').show();

        //     app.deleteOriginMarker();

        //     drag.remove();
        //     dragend.remove();

        //     drag = map.addListener('drag', function() {
        //         $('#origin-center-marker').show();
        //         app.deleteOriginMarker();
        //     });

        //     dragend = map.addListener('dragend', function() {
        //         $('#origin-center-marker').hide();
        //         from = app.addMarkerAtOrigin(map, circle);
        //     });
        // });

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