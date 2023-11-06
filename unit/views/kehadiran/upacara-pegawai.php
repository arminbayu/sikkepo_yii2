<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\AbsenPegawai;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$status = array(
    1 => 'Hadir',
    0 => 'Tidak Hadir',
);

?>

<?php
$script = <<< JS

$(".select2").select2();

$('#tanggal').datepicker({
    autoclose: true,
    format: 'yyyy-mm-dd',
    language: 'id',
    locale: 'id'
});

JS;
$this->registerJs($script);
?>

<section style="padding-top: 30px; min-height: 600px">
	<div class="row">
		<div class="col-md-12" style="margin-bottom: 20px"><h3>Upacara</h3></div>
		<div class="col-md-12">
			<?php $form = ActiveForm::begin(); ?>
			<table class="table table-striped">
				<tr>
					<th>No</th>
					<th>NIP</th>
					<th>Nama</th>
					<th style="text-align: center">Status Kehadiran</th>
				</tr>
				<?php $no = 1; ?>
				<?php foreach($upacara as $nip => $upacara): ?>
				<?= $form->field($upacara, "[$nip]tanggal")->hiddenInput(['value'=>$tanggal])->label(false) ?>
				<?= $form->field($upacara, "[$nip]NIP")->hiddenInput(['value'=>$nip])->label(false) ?>
				<tr>
					<td style="vertical-align: middle;"><?= $no ?></td>
					<td style="vertical-align: middle;" style="vertical-align: middle;"><?= $nip ?></td>
					<td style="vertical-align: middle;"><?= Yii::$app->runAction('kehadiran/get-nama', ['nip'=>$nip]) ?></td>
					<td style="text-align: center; padding-bottom: 0; padding-top: 15px">
					<?= $form->field($upacara, "[$nip]status")->dropDownList($status, ['class'=>'form-control select2', 'style' => 'width:150px; height:10px; margin-bottom:-10px'])->label(false) ?>
					</td>
				</tr>
				<?php $no++; ?>
				<?php endforeach; ?>
			</table>
			<?= Html::submitButton('Simpan', ['class' => 'btn btn-success']) ?>
			<?php ActiveForm::end(); ?>
		</div>
	</div>
</section>