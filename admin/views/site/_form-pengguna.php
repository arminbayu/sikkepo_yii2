<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model admin\models\UserPegawai */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-pegawai-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= Html::label('NIP') ?>
    <?= Html::textInput('username', '', ['class' => 'form-control', 'maxlength' => true, 'style' => 'margin-bottom:20px']) ?>

    <?= Html::label('Email') ?>
    <?= Html::textInput('email', '', ['class' => 'form-control', 'maxlength' => true, 'style' => 'margin-bottom:20px']) ?>

    <?= Html::label('Password') ?>
    <?= Html::passwordInput('password_hash', '', ['class' => 'form-control', 'maxlength' => true, 'style' => 'margin-bottom:20px']) ?>

    <div class="form-group">
        <?= Html::submitButton('<i class="fa fa-save"></i> Simpan', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
