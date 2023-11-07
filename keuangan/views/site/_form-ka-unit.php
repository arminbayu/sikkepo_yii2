<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use common\models\DataPegawai;

/* @var $this yii\web\View */
/* @var $model admin\models\UserPegawai */
/* @var $form yii\widgets\ActiveForm */
?>
<?php
$script = <<< JS

$(".select2").select2();

$('#tanggal').datepicker({
    autoclose: true,
    format: 'yyyy-mm-dd',
    language: 'id',
    locale: 'id'
});

JS;
$this->registerJs($script);
?>

<div class="user-pegawai-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= Html::label('Nama') ?>
    <?= Html::dropDownList(
        'username', //name
        '', //select
        ArrayHelper::map(DataPegawai::find()->where(['status'=>1])->all(), 'NIP', 'nama'), //items
        ['class' => 'form-control select2', 'prompt' => 'Nama Pegawai'] //options
    ) ?>
    <br /><br />
    <?= Html::label('Email') ?>
    <?= Html::textInput('email', '', ['class' => 'form-control', 'maxlength' => true, 'style' => 'margin-bottom:20px']) ?>

    <?= Html::label('Password') ?>
    <?= Html::passwordInput('password_hash', '', ['class' => 'form-control', 'maxlength' => true, 'style' => 'margin-bottom:20px']) ?>

    <div class="form-group">
        <?= Html::submitButton('<i class="fa fa-save"></i> Simpan', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
