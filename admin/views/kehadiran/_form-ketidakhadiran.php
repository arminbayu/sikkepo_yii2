<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use common\models\DataPegawai;
use common\models\DataKeterangan;

/* @var $this yii\web\View */
/* @var $model common\models\SearchDataKeterangan */
/* @var $form yii\widgets\ActiveForm */
?>
<?php
$script = <<< JS

$(".select2").select2();

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

    <?= Html::beginForm(['input-ketidakhadiran'], 'post') ?>

    <label>No. SK</label>
    <?= Html::input('text', 'no_sk', '', ['class' => 'form-control', 'style' => 'width:300px; margin-bottom:20px', 'placeholder' => 'No. SK', 'autocomplete' => 'off']) ?>

    <label>Dari Tanggal</label>
    <?= Html::input('text', 'dari', '', ['class' => 'form-control', 'style' => 'width:300px; margin-bottom:20px', 'id' => 'dari-tanggal', 'placeholder' => 'Dari Tanggal', 'autocomplete' => 'off']) ?>

    <label>Sampai Tanggal</label>
    <?= Html::input('text', 'sampai', '', ['class' => 'form-control', 'style' => 'width:300px; margin-bottom:20px', 'id' => 'sampai-tanggal', 'placeholder' => 'Sampai Tanggal', 'autocomplete' => 'off']) ?>

    <label>Pegawai</label>
    <?= Html::dropDownList(
        'nip', //name
        '', //select
        ArrayHelper::map(DataPegawai::find()->where(['status'=>1])->all(), 'NIP', 'nama'), //items
        ['class' => 'form-control select2', 'prompt' => 'Nama Pegawai'] //options
    ) ?>
    <br /><br />
    <label>Keterangan</label>
    <?= Html::dropDownList(
        'keterangan', //name
        '', //select
        ArrayHelper::map(DataKeterangan::find()->where(['<>','id', 1])->all(), 'id', 'keterangan'), //items
        ['class' => 'form-control select2', 'prompt' => 'Pilih Keterangan'] //options
    ) ?>

    <div class="form-group" style= "margin-top:20px">
        <?= Html::submitButton('Simpan', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?= Html::endForm() ?>

</div>
