<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\History */
/* @var $form yii\widgets\ActiveForm */

$month = array();
for ($m=1; $m<=12; ++$m) {
    $month[date('m', mktime(0, 0, 0, $m, 1))] = date('F', mktime(0, 0, 0, $m, 1));
}

$years = range(date('Y'), date('Y')-1);
$year = array_combine($years, $years);

$monthList = array(
    'January' => 'Januari',
    'February' => 'Februari',
    'March' => 'Maret',
    'April' => 'April',
    'May' => 'Mei',
    'June' => 'Juni',
    'July' => 'Juli',
    'August' => 'Agustus',
    'September' => 'September',
    'October' => 'Oktober',
    'November' => 'November',
    'December' => 'Desember'
);
?>

<?php
$script = <<< JS

$(".select2").select2();

JS;
$this->registerJs($script);
?>

<div class="form-group">
    <label>Pilih Bulan dan Tahun</label>
    <?= Html::beginForm() ?>
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
    <?= Html::submitButton('Lihat', ['class' => 'btn-sm btn-primary', 'style' => 'border:none']) ?>
    <?= Html::endForm() ?>

</div>
