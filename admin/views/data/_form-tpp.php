<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\HariLibur */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="hari-libur-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'kode')->textInput() ?>

    <?= $form->field($model, 'tpp')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('<i class="fa fa-save"></i> Simpan', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
