<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use common\models\DataPegawai;

/* @var $this yii\web\View */
/* @var $model common\models\SearchDataKeterangan */
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

$('#dari-jam').timepicker({
    showMeridian: false,
    defaultTime: '00:00'
});

$('#sampai-jam').timepicker({
    showMeridian: false,
    defaultTime: '00:00'
});

JS;
$this->registerJs($script);
?>

<div class="data-keterangan-search">

    <?= Html::beginForm(['input-tugas-luar'], 'post') ?>

    <label>No. Surat Tugas</label>
    <?= Html::input('text', 'no_surat', '', ['class' => 'form-control', 'style' => 'width:300px; margin-bottom:20px', 'placeholder' => 'No. Surat Tugas', 'autocomplete' => 'off']) ?>

    <label>Tanggal</label>
    <?= Html::input('text', 'tanggal', '', ['class' => 'form-control', 'style' => 'width:300px; margin-bottom:20px', 'id' => 'tanggal', 'placeholder' => 'yyyy-mm-dd', 'autocomplete' => 'off']) ?>

    <label>Dari Jam</label>
    <div class="input-group bootstrap-timepicker timepicker">
        <?= Html::input('text', 'dari_jam', '', ['class' => 'form-control input-small input-group-addon', 'style' => 'width:300px', 'id' => 'dari-jam', 'placeholder' => 'hh:mm', 'autocomplete' => 'off']) ?>
        <span class="input-group-addon" style="width: 30px; border: 0; border-bottom: 1px solid #ccc; border-radius: 0"><i class="fa fa-clock-o"></i></span>
    </div>

    <label style="margin-top: 20px">Sampai Jam</label>
    <div class="input-group bootstrap-timepicker timepicker">
        <?= Html::input('text', 'sampai_jam', '', ['class' => 'form-control input-group-addon', 'style' => 'width:300px', 'id' => 'sampai-jam', 'placeholder' => 'hh:mm', 'autocomplete' => 'off']) ?>
        <span class="input-group-addon" style="width: 30px; border: 0; border-bottom: 1px solid #ccc; border-radius: 0"><i class="fa fa-clock-o"></i></span>
    </div>

    <label style="margin-top: 20px">Pegawai</label>
    <?= Html::dropDownList(
        'nip', //name
        '', //select
        ArrayHelper::map(DataPegawai::find()->where(['status'=>1])->all(), 'NIP', 'nama'), //items
        ['class' => 'form-control select2', 'prompt' => 'Nama Pegawai'] //options
    ) ?>

    <label style="margin-top: 20px">Keterangan</label>
    <?= Html::input('text', 'keterangan', '', ['class' => 'form-control', 'style' => 'margin-bottom:40px', 'placeholder' => 'Keterangan', 'autocomplete' => 'off']) ?>

    <div class="form-group" style= "margin-top:20px">
        <?= Html::submitButton('Simpan', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?= Html::endForm() ?>

</div>
