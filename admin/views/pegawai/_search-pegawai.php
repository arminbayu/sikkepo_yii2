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

$(".select2").select2();

JS;
$this->registerJs($script);


$unit = UnitKerja::find()->all();

$all = [
    ['kode' => '', 'nama' => 'SEMUA UNIT'],
];

$unit_kerja = ArrayHelper::merge($all, $unit);
?>

<div class="data-keterangan-search">

    <?= Html::beginForm(['pegawai'], 'get') ?>

    <?= Html::input('text', 'nama', '', ['class' => 'form-control', 'style' => 'width:300px; margin-bottom:20px', 'id' => 'tanggal', 'placeholder' => 'Masukkan Nama']) ?>

    <?= Html::dropDownList(
        'unit', //name
        '', //select
        ArrayHelper::map($unit_kerja, 'kode', 'nama'), //items
        ['class' => 'form-control select2'] //options
    ) ?>

    <div class="form-group" style= "margin-top:20px">
        <?= Html::submitButton('Cari', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?= Html::endForm() ?>

</div>
