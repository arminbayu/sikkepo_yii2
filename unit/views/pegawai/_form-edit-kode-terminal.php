<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\Terminal;

/* @var $this yii\web\View */
/* @var $model admin\models\AbsenPegawai */
/* @var $form yii\widgets\ActiveForm */
?>

<?php
$script = <<< JS

$(".select2").select2();

JS;
$this->registerJs($script);
?>

<div class="data-pegawai-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'kode_terminal')->dropDownList(ArrayHelper::map(Terminal::find()->all(), 'kode', 'kode'), ['class'=>'form-control select2']) ?>

    <div class="form-group">
        <?= Html::submitButton('<i class="fa fa-save"></i> Save', ['class' => 'btn btn-success']) ?>
        <?= Html::a('<i class="fa fa-stop"></i> Cancel', ['data-pegawai-per-unit-kerja', 'kode'=>$model->unit_kerja], ['class' => 'btn btn-warning']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
