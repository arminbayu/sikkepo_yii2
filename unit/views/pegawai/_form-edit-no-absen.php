<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model admin\models\AbsenPegawai */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="data-pegawai-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'no_absen')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('<i class="fa fa-save"></i> Save', ['class' => 'btn btn-success']) ?>
        <?= Html::a('<i class="fa fa-stop"></i> Cancel', ['data-pegawai'], ['class' => 'btn btn-warning']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
