<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\DataTpp;
use yii\helpers\ArrayHelper;

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

    <!--
    <?= $form->field($model, 'kode_tpp')->dropDownList(ArrayHelper::map(DataTpp::find()->all(), 'kode', 'golongan', 'eselon'), ['class'=>'form-control select2']) ?>
    -->
    <?= $form->field($model, 'kode_tpp')->dropDownList(ArrayHelper::map(DataTpp::find()->where(['<>', 'kode', '00'])->all(), 'kode', 'infoTpp'), ['class'=>'form-control select2'])->label('TPP') ?>

    <div class="form-group">
        <?= Html::submitButton('<i class="fa fa-save"></i> Save', ['class' => 'btn btn-success']) ?>
        <?= Html::a('<i class="fa fa-stop"></i> Cancel', ['data-pegawai-per-unit-kerja', 'unit'=>$model->unit_kerja], ['class' => 'btn btn-warning']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
