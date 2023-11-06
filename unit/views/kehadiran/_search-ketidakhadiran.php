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

JS;
$this->registerJs($script);
?>

<div class="data-keterangan-search">

    <?= Html::beginForm(['edit-ketidakhadiran-pegawai'], 'get') ?>

    <?= Html::input('text', 'ymd', '', ['class' => 'form-control', 'style' => 'width:300px; margin-bottom:20px', 'id' => 'tanggal', 'placeholder' => 'Tanggal', 'autocomplete' => 'off']) ?>

    <?= Html::dropDownList(
        'nip', //name
        '', //select
        ArrayHelper::map(DataPegawai::find()->where(['status'=>1])->all(), 'NIP', 'nama'), //items
        ['class' => 'form-control select2', 'prompt' => 'Nama Pegawai'] //options
    ) ?>

    <div class="form-group" style= "margin-top:20px">
        <?= Html::submitButton('Cari', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?= Html::endForm() ?>

</div>
