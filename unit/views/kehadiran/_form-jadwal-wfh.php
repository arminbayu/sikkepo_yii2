<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use common\models\UnitKerja;

/* @var $this yii\web\View */
/* @var $model common\models\SearchDataKeterangan */
/* @var $form yii\widgets\ActiveForm */

?>
<?php
$script = <<< JS

$('#dari-tanggal').datepicker({
    autoclose: true,
    format: 'yyyy-mm-dd',
    language: 'id',
    locale: 'id'
});

$('#sampai-tanggal').datepicker({
    autoclose: true,
    format: 'yyyy-mm-dd',
    language: 'id',
    locale: 'id'
});

JS;
$this->registerJs($script);
?>

<div class="data-keterangan-search">

    <?= Html::beginForm(['jadwal-wfh-pegawai'], 'get') ?>
    <div style="float: left; padding-right: 50px">
    <?= Html::label('Dari Tanggal') ?>
    <?= Html::input('text', 'dari', '', ['class' => 'form-control', 'style' => 'width:300px; margin-bottom:20px', 'id' => 'dari-tanggal', 'placeholder' => 'Masukkan Tanggal', 'autocomplete' => 'off']) ?>
    </div>
    <div>
    <?= Html::label('Sampai Tanggal') ?>
    <?= Html::input('text', 'sampai', '', ['class' => 'form-control', 'style' => 'width:300px; margin-bottom:20px', 'id' => 'sampai-tanggal', 'placeholder' => 'Masukkan Tanggal', 'autocomplete' => 'off']) ?>
    </div>
    <?=  Html::hiddenInput('unit', $unit) ?>

    <div class="form-group" style= "margin-top:20px">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?= Html::endForm() ?>

</div>
