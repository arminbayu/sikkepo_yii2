<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use common\models\UnitKerja;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
?>

<?php
$script = <<< JS

$(".select2").select2();

JS;
$this->registerJs($script);
?>

<div class="terminal-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'kode')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ip_address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nama')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'unit_kerja')->dropDownList(ArrayHelper::map(UnitKerja::find()->all(), 'kode', 'nama'), ['class'=>'form-control select2']) ?>

    <div class="form-group">
        <?= Html::submitButton('<i class="fa fa-save"></i> Simpan', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
