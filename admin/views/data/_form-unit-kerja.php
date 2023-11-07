<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use common\models\DataPegawai;
use yii\bootstrap5\Modal;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model common\models\HariLibur */
/* @var $form yii\widgets\ActiveForm */
?>

<?php
$script = <<< JS

$(".select2").select2();

JS;
$this->registerJs($script);
?>

<div class="unit-kerja-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'kode')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nama')->textInput() ?>

    <?= $form->field($model, 'alamat')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'telp')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'jab_pl')->textInput(['maxlength' => true, 'style' => 'text-transform:uppercase']) ?>

    <?= $form->field($model, 'ka_unit')->dropDownList(ArrayHelper::map(DataPegawai::find()->where(['status'=>1])->all(), 'NIP', 'nama'), ['class'=>'form-control select2'])->label('Kepala Unit') ?>

    <?= $form->field($model, 'bendahara')->dropDownList(ArrayHelper::map(DataPegawai::find()->where(['status'=>1])->all(), 'NIP', 'nama'), ['prompt'=>'Pilih Bendahara', 'class'=>'form-control select2'])->label('Bendahara') ?>

    <?= $form->field($model, 'koordinat')->textInput(['id' => 'coordinate']) ?>

    <!--
    <div class="input-group" style="width: 100%; margin-bottom: 20px">
        <input id="origin" type="text" class="form-control" style="width: 100%; border-bottom: 1px solid #ddd;" placeholder="Cari nama tempat" />
    </div>
    -->

    <div id="map"></div>
    <div id="origin-center-marker"></div>

    <div class="form-group">
        <?= Html::submitButton('<i class="fa fa-save"></i> Simpan', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
