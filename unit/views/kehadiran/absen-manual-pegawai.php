<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\AbsenPegawai;
use yii\bootstrap5\Progress;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>

<?php
$script = <<< JS

$('.jam').timepicker({
    showMeridian: false,
    defaultTime: '00:00'
});

JS;
$this->registerJs($script);
?>

<section style="padding-top: 30px; min-height: 600px">
	<div class="row">
		<div class="col-md-12" style="margin-bottom: 20px"><h3>Absen Manual <?= ($absen == 1) ? 'Pagi' : (($absen == 2) ? 'Siang' : 'Sore') ?></h3></div>
		<div class="col-md-12">
			<?php $form = ActiveForm::begin(); ?>
			<table class="table table-striped">
				<tr>
					<th>No</th>
					<th>NIP</th>
					<th>Nama</th>
					<th style="text-align: center; width: 200px">Jam</th>
				</tr>
				<?php $no = 1; ?>
				<?php foreach($absen_manual as $nip => $absen_manual): ?>
				<?= $form->field($absen_manual, "[$nip]tanggal")->hiddenInput(['value'=>$tanggal])->label(false) ?>
				<?= $form->field($absen_manual, "[$nip]NIP")->hiddenInput(['value'=>$nip])->label(false) ?>
				<tr>
					<td style="vertical-align: middle;"><?= $no ?></td>
					<td style="vertical-align: middle;" style="vertical-align: middle;"><?= $nip ?></td>
					<td style="vertical-align: middle;"><?= Yii::$app->runAction('kehadiran/get-nama', ['nip'=>$nip]) ?></td>
					<td style="text-align: center; padding-bottom: 0; padding-top: 15px">
					<?= $form->field($absen_manual, "[$nip]jam")->textInput(['class' => 'form-control input-small input-group-addon jam', 'style' => 'width:200px', 'placeholder' => 'hh:mm', 'autocomplete' => 'off'])->label(false) ?>
					</td>
				</tr>
				<?= $form->field($absen_manual, "[$nip]absen")->hiddenInput(['value'=>$absen])->label(false) ?>
				<?= $form->field($absen_manual, "[$nip]keterangan")->hiddenInput(['value'=>$keterangan])->label(false) ?>
				<?php $no++; ?>
				<?php endforeach; ?>
			</table>
			<?= Html::submitButton('Simpan', ['class' => 'btn btn-success']) ?>
			<?php ActiveForm::end(); ?>
		</div>
	</div>
</section>