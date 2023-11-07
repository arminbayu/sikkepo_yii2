<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\HariLibur */
/* @var $form yii\widgets\ActiveForm */
?>

<?php
$script = <<< JS

$('#tanggal').datepicker({
    autoclose: true,
    format: 'yyyy-mm-dd',
    language: 'id',
    locale: 'id'
});

JS;
$this->registerJs($script);
?>

<div class="hari-libur-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'tanggal', ['inputOptions'=>['class'=>'form-control', 'id'=>'tanggal']])->textInput() ?>

    <?= $form->field($model, 'keterangan')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('<i class="fa fa-save"></i> Simpan', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
