<?php

use yii\helpers\Html;
?>
<section id="mu-contact" style="padding-top: 30px; min-height: 600px">
  <div class="container">
    <div class="row">
      <div class="mu-contact-area" style="border: 0px solid #000">
        <div class="col-md-12" style="margin-bottom: 20px"><h3>Upload Data Absen</h3></div>
        <div class="col-md-12">
          <p>File yang di-upload harus mempunyai ekstensi .json</p>
          <br />
          <div style="color: #f00"><?= $error ?></div>

          <?= $this->render('_form-upload', [
              'file'=>$file,
          ]) ?>

        </div>
      </div>
    </div>
  </div>
</section>