<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
?>
  <!-- Start Slider -->
  <section id="mu-slider">
    <!-- Start single slider item -->
    <div class="mu-slider-single">
      <div class="mu-slider-img">
        <figure>
          <img src="custom/img/slider/1.jpg" alt="img">
        </figure>
      </div>
      <div class="mu-slider-content">
        <div style="background-color: transparent;">
          <div style="background-color: transparent; float: left; width: 10%"><img src="custom/img/lambang.png" width="100" /></div>
          <div style="float: left; width: 80%">
            <h4>SISTEM INFORMASI KEHADIRAN DAN KINERJA PEGAWAI ONLINE</h4>
            <h3 style="margin-bottom: 60px">BADAN KEPEGAWAIAN DAERAH PROVINSI PAPUA BARAT</h3>
          </div>
          <!--<div style="background-color: transparent; float: left; width: 10%"><img src="custom/img/lambang.png" width="100" /></div>-->
        </div>
        <div style="background-color: transparent; left: 40%; right: 40%; width: 20%; top: 220px; position: fixed;">
          
        </div>
      </div>
    </div>
    <!-- Start single slider item -->
  </section>
  <!-- End Slider -->
  <?php if (Yii::$app->user->isGuest) : ?>
  <!-- Start service  -->
  
  <!-- End service  -->
  <?php endif ?>