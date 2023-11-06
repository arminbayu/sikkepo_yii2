<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use common\models\DataKeterangan;

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

JS;
$this->registerJs($script);
?>

<section style="padding-top: 30px; min-height: 600px">
	<div class="row">
		<div class="col-md-12" style="margin-bottom: 20px"><h3>Ketidakhadiran</h3></div>
		<div class="col-md-12">
			<?php if ($model) : ?>
				<?php $form = ActiveForm::begin(); ?>
				<table class="table table-hover">
					<tr>
						<th>Tanggal</th>
						<th>NIP</th>
						<th>Nama</th>
						<th style="text-align: center">Keterangan</th>
						<th>Action</th>
					</tr>
					<tr>
						<td style="vertical-align: middle;" style="vertical-align: middle;"><?= (new \DateTime($model->tanggal, new \DateTimeZone(TIMEZONE)))->format('d-m-Y') ?></td>
						<td style="vertical-align: middle;" style="vertical-align: middle;"><?= $model->NIP ?></td>
						<td style="vertical-align: middle;"><?= $model->nip->nama ?></td>
						<td style="text-align: center; padding-bottom: 0; padding-top: 15px">
						<?= $form->field($model, 'keterangan')->dropDownList(ArrayHelper::map(DataKeterangan::find()->all(), 'id', 'keterangan'), ['class'=>'form-control select2', 'style' => 'width:150px'])->label(false) ?>
						</td>
						<td style="vertical-align: middle;">
							<?= Html::a('<i class="fa fa-trash"></i> Delete', ['delete-ketidakhadiran', 'id'=>$model->id], [
								'class' => 'btn-sm btn-danger',
								'data' => [
									'confirm' => 'Apakah keterangan ingin dihapus?',
									'method' => 'post',
								],
							]) ?>
						</td>
					</tr>
				</table>
				<?= Html::submitButton('Simpan', ['class' => 'btn btn-success']) ?>
				<?php ActiveForm::end(); ?>
			<?php else : ?>
				Data tidak ditemukan.
			<?php endif; ?>
		</div>
	</div>
</section>