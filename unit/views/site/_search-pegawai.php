<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use common\models\DataPegawai;

/* @var $this yii\web\View */
/* @var $model common\models\SearchDataKeterangan */
/* @var $form yii\widgets\ActiveForm */

$month = array();
for ($m=1; $m<=12; ++$m) {
    $month[date('m', mktime(0, 0, 0, $m, 1))] = date('F', mktime(0, 0, 0, $m, 1));
}

$years = range(date('Y'), date('Y')-1);
$year = array_combine($years, $years);
?>

<?php
$script = <<< JS

$(".select2").select2();

JS;
$this->registerJs($script);
?>

<div class="data-keterangan-search">

    <?= Html::beginForm(['pegawai'], 'get') ?>

    <div>
    <?= Html::dropDownList(
        'nip', //name
        '', //select
        ArrayHelper::map(DataPegawai::find()->where(['unit_kerja'=>$unit, 'status'=>1])->all(), 'NIP', 'nama'), //items
        ['class' => 'form-control select2', 'prompt' => 'Nama Pegawai'] //options
    ) ?>
    </div>
    <div class="form-group" style= "margin-top:20px">
    <?= Html::dropDownList(
        'month', //name
        $current_month, //select
        $month, //items
        ['class' => 'form-control select2', 'style' => 'width:200px; float:left'] //options
    ) ?>

    <?= Html::dropDownList(
        'year', //name
        $current_year, //select
        $year, //items
        ['class' => 'form-control select2', 'style' => 'width:100px; float:left'] //options
    ) ?>
    </div>

    <div class="form-group" style= "margin-top:20px">
        <?= Html::submitButton('Cari', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?= Html::endForm() ?>

</div>
