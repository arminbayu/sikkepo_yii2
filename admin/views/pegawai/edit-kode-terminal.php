<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
?>

<section id="mu-contact" style="padding-top: 30px; min-height: 600px">
  <div class="container">
    <div class="row">
      <div class="mu-contact-area" style="border: 0px solid #000">
        <div class="col-md-12" style="margin-bottom: 20px"><h3><?= $model->nama ?> (<?= $model->NIP ?>)</h3></div>
        <div class="col-md-12">
    			<?= $this->render('_form-edit-kode-terminal', [
    			    'model' => $model,
    			]) ?>
        </div>
      </div>
    </div>
  </div>
</section>
