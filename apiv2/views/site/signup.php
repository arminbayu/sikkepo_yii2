<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap5\ActiveForm */
/* @var $model \participant\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

$category = array(
    1 => 'Dosen/Umum',
    2 => 'Mahasiswa',
);
?>
<style type="text/css">
    .register-title {
      width: 50%;
      line-height: 43px;
      margin: 50px auto 20px;
      font-size: 19px;
      font-weight: 500;
      color: white;
      color: rgba(255, 255, 255, 0.95);
      text-align: center;
      text-shadow: 0 1px rgba(0, 0, 0, 0.3);
      background: #d7604b;
      border-radius: 3px;
      background-image: -webkit-linear-gradient(top, #dc745e, #d45742);
      background-image: -moz-linear-gradient(top, #dc745e, #d45742);
      background-image: -o-linear-gradient(top, #dc745e, #d45742);
      background-image: linear-gradient(to bottom, #dc745e, #d45742);
      -webkit-box-shadow: inset 0 1px rgba(255, 255, 255, 0.1), inset 0 0 0 1px rgba(255, 255, 255, 0.05), 0 0 1px 1px rgba(0, 0, 0, 0.1), 0 1px 3px rgba(0, 0, 0, 0.3);
      box-shadow: inset 0 1px rgba(255, 255, 255, 0.1), inset 0 0 0 1px rgba(255, 255, 255, 0.05), 0 0 1px 1px rgba(0, 0, 0, 0.1), 0 1px 3px rgba(0, 0, 0, 0.3);
    }

    .register {
      margin: 0 auto;
      width: 50%;
      padding: 20px;
      background: #f4f4f4;
      border-radius: 3px;
      -webkit-box-shadow: 0 0 1px 1px rgba(0, 0, 0, 0.1), 0 1px 3px rgba(0, 0, 0, 0.3);
      box-shadow: 0 0 1px 1px rgba(0, 0, 0, 0.1), 0 1px 3px rgba(0, 0, 0, 0.3);
    }

    input {
      font-family: inherit;
      font-size: inherit;
      color: inherit;
      -webkit-box-sizing: border-box;
      -moz-box-sizing: border-box;
      box-sizing: border-box;
    }

    .register-input {
      display: block;
      width: 100%;
      height: 38px;
      margin-top: 2px;
      font-weight: 500;
      background: none;
      border: 0;
      border-bottom: 1px solid #d8d8d8;
    }
    .register-input:focus {
      border-color: #1e9ce6;
      outline: 0;
    }

    .register-button {
      display: block;
      width: 100%;
      height: 42px;
      margin-top: 25px;
      font-size: 16px;
      font-weight: bold;
      color: #494d59;
      text-align: center;
      text-shadow: 0 1px rgba(255, 255, 255, 0.5);
      background: #fcfcfc;
      border: 1px solid;
      border-color: #d8d8d8 #d1d1d1 #c3c3c3;
      border-radius: 2px;
      cursor: pointer;
      background-image: -webkit-linear-gradient(top, #fefefe, #eeeeee);
      background-image: -moz-linear-gradient(top, #fefefe, #eeeeee);
      background-image: -o-linear-gradient(top, #fefefe, #eeeeee);
      background-image: linear-gradient(to bottom, #fefefe, #eeeeee);
      -webkit-box-shadow: inset 0 -1px rgba(0, 0, 0, 0.03), 0 1px rgba(0, 0, 0, 0.04);
      box-shadow: inset 0 -1px rgba(0, 0, 0, 0.03), 0 1px rgba(0, 0, 0, 0.04);
    }
    .register-button:active {
      background: #eee;
      border-color: #c3c3c3 #d1d1d1 #d8d8d8;
      background-image: -webkit-linear-gradient(top, #eeeeee, #fcfcfc);
      background-image: -moz-linear-gradient(top, #eeeeee, #fcfcfc);
      background-image: -o-linear-gradient(top, #eeeeee, #fcfcfc);
      background-image: linear-gradient(to bottom, #eeeeee, #fcfcfc);
      -webkit-box-shadow: inset 0 1px rgba(0, 0, 0, 0.03);
      box-shadow: inset 0 1px rgba(0, 0, 0, 0.03);
    }
    .register-button:focus {
      outline: 0;
    }

    .register-switch {
      height: 40px;
      margin-bottom: 15px;
      padding: 4px;
      background: #6db244;
      border-radius: 2px;
      background-image: -webkit-linear-gradient(top, #60a83a, #7dbe52);
      background-image: -moz-linear-gradient(top, #60a83a, #7dbe52);
      background-image: -o-linear-gradient(top, #60a83a, #7dbe52);
      background-image: linear-gradient(to bottom, #60a83a, #7dbe52);
      -webkit-box-shadow: inset 0 1px rgba(0, 0, 0, 0.05), inset 1px 0 rgba(0, 0, 0, 0.02), inset -1px 0 rgba(0, 0, 0, 0.02);
      box-shadow: inset 0 1px rgba(0, 0, 0, 0.05), inset 1px 0 rgba(0, 0, 0, 0.02), inset -1px 0 rgba(0, 0, 0, 0.02);
    }

    .register-switch-input {
      display: none;
    }

    .register-switch-label {
      float: left;
      width: 50%;
      line-height: 32px;
      color: white;
      text-align: center;
      text-shadow: 0 -1px rgba(0, 0, 0, 0.2);
      cursor: pointer;
    }
    .register-switch-input:checked + .register-switch-label {
      font-weight: 500;
      color: #434248;
      text-shadow: 0 1px rgba(255, 255, 255, 0.5);
      background: white;
      border-radius: 2px;
      background-image: -webkit-linear-gradient(top, #fefefe, #eeeeee);
      background-image: -moz-linear-gradient(top, #fefefe, #eeeeee);
      background-image: -o-linear-gradient(top, #fefefe, #eeeeee);
      background-image: linear-gradient(to bottom, #fefefe, #eeeeee);
      -webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, 0.15), 0 0 0 1px rgba(0, 0, 0, 0.1);
      box-shadow: 0 1px 1px rgba(0, 0, 0, 0.15), 0 0 0 1px rgba(0, 0, 0, 0.1);
    }

    :-moz-placeholder {
      color: #aaa;
      font-weight: 300;
    }

    ::-moz-placeholder {
      color: #aaa;
      font-weight: 300;
      opacity: 1;
    }

    ::-webkit-input-placeholder {
      color: #aaa;
      font-weight: 300;
    }

    :-ms-input-placeholder {
      color: #aaa;
      font-weight: 300;
    }

    ::-moz-focus-inner {
      border: 0;
      padding: 0;
    }
</style>
<section id="mu-contact" style="padding-top: 30px">
    <div class="container">
<!--
          <h1 class="register-title">Welcome</h1>
          <?php $form = ActiveForm::begin(['id' => 'form-signup', 'options' => ['class' => 'register']]); ?>
            <div class="register-switch">
              <input type="radio" name="category" value="1" id="category_u" class="register-switch-input" checked>
              <label for="category_u" class="register-switch-label">Dosen/Umum</label>
              <input type="radio" name="category" value="2" id="category_m" class="register-switch-input">
              <label for="category_m" class="register-switch-label">Mahasiswa</label>
              <?= $form->field($model, 'category')->radio(['class' => 'register-switch-input', 'value' => 1, 'id' => 'category_u'])->label(false) ?>
              <label for="category_u" class="register-switch-label">Dosen/Umum</label>
              <?= $form->field($model, 'category')->radio(['class' => 'register-switch-input', 'value' => 2, 'id' => 'category_m'])->label(false) ?>
              <label for="category_m" class="register-switch-label">Mahasiswa</label>
            </div>
            <?= $form->field($model, 'name')->textInput(['autofocus' => true, 'class' => 'register-input', 'placeholder' => 'Nama Lengkap'])->label(false) ?>
            <?= $form->field($model, 'phone')->textInput(['class' => 'register-input', 'placeholder' => 'Telp/HP'])->label(false) ?>
            <?= $form->field($model, 'email')->textInput(['class' => 'register-input', 'placeholder' => 'Email'])->label(false) ?>
            <?= $form->field($model, 'password')->passwordInput(['class' => 'register-input', 'placeholder' => 'Password'])->label(false) ?>
            <?= $form->field($model, 'password_repeat')->passwordInput(['class' => 'register-input', 'placeholder' => 'Password'])->label(false) ?>
            <div class="form-group">
                <?= Html::submitButton('Signup', ['class' => 'register-button', 'name' => 'signup-button']) ?>
            </div>
          <?php ActiveForm::end(); ?>
-->

        <div class="row">
            <div class="col-lg-8" style="padding: 0">
            <div class="col-md-12" style="margin-bottom: 20px;"><h3>PENDAFTARAN PESERTA</h3></div>
              <div class="col-lg-8">
                <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

                    <?= $form->field($model, 'name')->textInput(['placeholder' => "Silakan isi nama lengkap dan gelar dengan benar"])->label('Nama Lengkap') ?>

                    <?= $form->field($model, 'phone')->textInput(['placeholder' => "Nomor yang dapat dihubungi"])->label('Telp/HP') ?>

                    <?= $form->field($model, 'email')->textInput(['autofocus' => true, 'placeholder' => "Alamat email yang dapat dihubungi"])->label('Email') ?>

                    <?= $form->field($model, 'institution')->textInput(['placeholder' => "Perguruan Tinggi/Institusi"])->label('Perguruan Tinggi/Institusi') ?>

                    <?= $form->field($model, 'city')->textInput(['placeholder' => "Kota/Kabupaten"])->label('Kota/Kabupaten') ?>

                    <?= $form->field($model, 'category')->dropDownList($category, ['prompt'=>'Pilih Kategori'])->label('Kategori') ?>

                    <?= $form->field($model, 'password')->passwordInput(['placeholder' => "Minimal 6 karakter"])->label('Password') ?>

                    <?= $form->field($model, 'password_repeat')->passwordInput(['placeholder' => "Minimal 6 karakter"])->label('Ulangi Password') ?>

                    <div class="form-group">
                        <?= Html::submitButton('Daftar', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                    </div>
                <?php ActiveForm::end(); ?>
              </div>
            </div>
            <?= $this->render('right') ?>
        </div>
    </div>
</section>
