<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
?>
<div style="background-color: #ccc; width: 100%; height: 100%; background: url(custom/img/verifikasi.jpg); background-size: 100% 100%">
	<div style="background-color: transparent; height: 90px">
		<div style="width: 100%; padding-top: 20px">
			<h4 style="text-align: center; color: white; margin-bottom: 10px">BIDANG PEMBINAAN,  PERUNDANG-UNDANGAN DAN KESEJAHTERAAN PEGAWAI</h4>
			<h3 style="text-align: center; color: white; ">VERIFIKASI BERKAS TPP DAN KINERJA</h3>
			<h6 style="text-align: center; color: yelloW; ">SKP PERMENPAN&RB NO. 6 TAHUN 2022</h6>

		</div>
	</div>
	<div style="background-color: rgba(18, 74, 122, 0.7); left: 25%; right: 25%; width: 50%; height: 55%; top: 20%; position: fixed; padding: 20px">
		<div style="background-color: transparent; display: block; text-align: center; margin-bottom: 20px"><img src="custom/img/lambang.png" width="100" /></div>
		<?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

		    <?= $form->field($model, 'username')->textInput(['autofocus' => true, 'placeholder' => 'Masukkan username', 'style' => 'background-color:transparent; color:#fff'])->label(false) ?>

		    <?= $form->field($model, 'password')->passwordInput(['placeholder' => 'Masukkan password', 'style' => 'background-color:transparent; color:#fff'])->label(false) ?>

		    <div class="form-group">
		        <?= Html::submitButton('Login', ['class' => 'btn btn-success', 'name' => 'login-button', 'style' => 'width: 100%']) ?>
		    </div>

		<?php ActiveForm::end(); ?>
	</div>
</div>
