<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use common\models\UnitKerja;

/* @var $this yii\web\View */
/* @var $model common\models\SearchDataKeterangan */
/* @var $form yii\widgets\ActiveForm */

$status = array(
    1 => 'Absen Pagi',
    2 => 'Absen Siang',
    3 => 'Absen Sore',
);

$keterangan = array(
    1 => 'Mati Listrik',
    2 => 'Mesin Rusak',
    3 => 'Kendala Teknis',
);

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

<div class="data-keterangan-search">

    <?= Html::beginForm(['absen-manual-pegawai'], 'get') ?>

    <?= Html::label('Tanggal') ?>
    <?= Html::input('text', 'ymd', '', ['class' => 'form-control', 'style' => 'width:300px; margin-bottom:20px', 'id' => 'tanggal', 'placeholder' => 'Masukkan Tanggal', 'autocomplete' => 'off']) ?>

    <?= Html::label('Unit Kerja') ?>
    <?= Html::dropDownList(
        'unit', //name
        '', //select
        ArrayHelper::map(UnitKerja::find()->all(), 'kode', 'nama'), //items
        ['class' => 'form-control select2'] //options
    ) ?>

    <br /><br />

    <?= Html::label('Waktu Absen') ?>
    <?= Html::dropDownList(
        'absen', //name
        '', //select
        $status, //items
        ['class' => 'form-control select2'] //options
    ) ?>

    <br /><br />

    <?= Html::label('Keterangan') ?>
    <?= Html::dropDownList(
        'keterangan', //name
        '', //select
        $keterangan, //items
        ['class' => 'form-control select2'] //options
    ) ?>

    <div class="form-group" style= "margin-top:20px">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?= Html::endForm() ?>

</div>
