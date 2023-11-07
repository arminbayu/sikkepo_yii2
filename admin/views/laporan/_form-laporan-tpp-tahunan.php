<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\History */
/* @var $form yii\widgets\ActiveForm */

$years = range(date('Y'), date('Y')-5);
$year = array_combine($years, $years);
?>

<?php
$script = <<< JS

$(".select2").select2();

JS;
$this->registerJs($script);
?>

<div class="form-group">
    <label>Pilih Tahun</label>
    <?= Html::beginForm() ?>
    <?= Html::dropDownList(
        'year', //name
        $current_year, //select
        $year, //items
        ['class' => 'form-control select2', 'style' => 'width:100px; float:left'] //options
    ) ?>
    <?= Html::submitButton('Lihat', ['class' => 'btn-sm btn-primary', 'style' => 'border:none']) ?>
    <?= Html::endForm() ?>

</div>
